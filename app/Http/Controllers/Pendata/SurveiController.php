<?php

namespace App\Http\Controllers\Pendata;

use App\Http\Controllers\Controller;
use App\Models\DataRtlh;
use App\Models\HasilPrediksi;
use App\Models\Kelurahan;
use App\Services\MachineLearningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveiController extends Controller
{
    public function dashboard(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $query = DataRtlh::where('user_id', $user->id)->with('hasilPrediksi', 'kelurahan.kecamatan');

        // Filter Rentang Waktu
        if ($request->filled('waktu')) {
            if ($request->waktu == 'hari_ini') $query->whereDate('tanggal_pendataan', now()->format('Y-m-d'));
            if ($request->waktu == 'kemarin') $query->whereDate('tanggal_pendataan', now()->subDay()->format('Y-m-d'));
            if ($request->waktu == 'minggu_ini') $query->whereBetween('tanggal_pendataan', [now()->startOfWeek(), now()->endOfWeek()]);
            if ($request->waktu == 'bulan_ini') $query->whereMonth('tanggal_pendataan', now()->month);
        }

        // Filter Area
        if ($request->filled('kelurahan_id')) {
            $query->where('kelurahan_id', $request->kelurahan_id);
        }

        // Output Filtered
        $allData = $query->get();
        $totalInput = $allData->count();
        $totalPending = $allData->where('status_validasi', 'pending')->count();
        $totalDisetujui = $allData->where('status_validasi', 'disetujui')->count();
        $totalDitolak = $allData->where('status_validasi', 'ditolak')->count();

        // 1. Kinerja Harian
        $dataHariIni = DataRtlh::where('user_id', $user->id)->whereDate('tanggal_pendataan', now()->format('Y-m-d'))->count();
        $targetHarian = 10;
        
        // 2. Kalender Intensitas
        $kalenderHeatmap = DataRtlh::where('user_id', $user->id)
            ->whereDate('tanggal_pendataan', '>=', now()->subDays(60))
            ->select(DB::raw('DATE(tanggal_pendataan) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        // 3. Progress Cakupan Area (Top 5 Kelurahan/Desa yg dipegang pendata)
        $progressArea = $allData->groupBy(fn($d) => optional($d->kelurahan)->nama_kelurahan ?? 'Lainnya')->map->count();

        // 4. Quality Assurance (Tabel Cacat Data)
        $dataCacat = $allData->filter(function($d) {
            return empty($d->latitude) || empty($d->longitude) || empty($d->nama_file_foto) || strlen($d->nik) != 16;
        })->values();

        // 5. Radar Anomali Data
        $dataAnomali = $allData->filter(function($d) {
            $isRtlh = optional($d->hasilPrediksi)->label_prediksi === 'rtlh';
            $gajiTinggi = $d->penghasilan_per_bulan > 5000000;
            $luasNol = $d->luas_rumah == 0;
            $penghuniBanyakSempit = ($d->luas_rumah > 0) && ($d->luas_rumah / $d->jumlah_penghuni < 2);

            return ($isRtlh && $gajiTinggi) || $luasNol || $penghuniBanyakSempit;
        })->values();

        // 6. Log Aktivitas Terbaru
        $recentData = DataRtlh::where('user_id', $user->id)->with('hasilPrediksi', 'kelurahan')->latest()->take(10)->get();
        $kelurahansTugas = Kelurahan::whereIn('id', DataRtlh::where('user_id', $user->id)->pluck('kelurahan_id')->unique())->get();

        return view('pendata.dashboard', compact(
            'totalInput','totalPending','totalDisetujui','totalDitolak',
            'dataHariIni','targetHarian','kalenderHeatmap','progressArea',
            'dataCacat','dataAnomali','recentData', 'user', 'kelurahansTugas'
        ));
    }

    public function index(Request $request)
    {
        $query = DataRtlh::where('user_id', Auth::id())->with(['kelurahan.kecamatan','hasilPrediksi']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('nama_kepala_rumah_tangga', 'like', "%{$q}%")
                      ->orWhere('nik', 'like', "%{$q}%")
                      ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        $data = $query->latest()->paginate(15);
        return view('pendata.survei.index', compact('data'));
    }

    public function create()
    {
        $kelurahans = Kelurahan::with('kecamatan')->orderBy('nama_kelurahan')->get();
        return view('pendata.survei.create', compact('kelurahans'));
    }

    public function store(Request $request, MachineLearningService $mlService)
    {
        $request->validate([
            'kelurahan_id'              => 'required|exists:kelurahans,id',
            'nama_kepala_rumah_tangga'  => 'required|string|max:100',
            'nomor_kartu_keluarga'      => 'required|string|max:100',
            'nik'                       => 'required|string|max:100',
            'alamat'                    => 'required|string',
            'umur'                      => 'required|integer|min:0',
            'jenis_kelamin'             => 'required|string',
            'pendidikan_terakhir'       => 'required|string',
            'pekerjaan'                 => 'required|string',
            'penghasilan_per_bulan'     => 'required|integer|min:0',
            'jumlah_keluarga_kk'        => 'required|integer|min:0',
            'jumlah_penghuni'           => 'required|integer|min:0',
            'kepemilikan_rumah'         => 'required|string',
            'kepemilikan_tanah'         => 'required|string',
            'aset_rumah_di_lokasi_lain' => 'required|boolean',
            'aset_tanah_di_lokasi_lain' => 'required|boolean',
            'jenis_kawasan'             => 'required|string',
            'fungsi_ruang'              => 'required|string',
            'luas_rumah'                => 'required|integer|min:0',
            'luas_lahan'                => 'required|integer|min:0',
            'pondasi'                   => 'required|string',
            'kondisi_kolom'             => 'required|string',
            'kondisi_rangka_atap'       => 'required|string',
            'kondisi_plafon'            => 'required|string',
            'kondisi_balok'             => 'required|string',
            'kondisi_sloof'             => 'required|string',
            'kondisi_jendela'           => 'required|string',
            'kondisi_ventilasi'         => 'required|string',
            'material_lantai_terluas'   => 'required|string',
            'kondisi_lantai'            => 'required|string',
            'material_dinding_terluas'  => 'required|string',
            'kondisi_dinding'           => 'required|string',
            'material_atap_terluas'     => 'required|string',
            'kondisi_atap'              => 'required|string',
            'sumber_penerangan'         => 'required|string',
            'sumber_air_minum'          => 'required|string',
            'jarak_sumber_air_tinja'    => 'required|string',
            'kamar_mandi_jamban'        => 'required|string',
            'jenis_jamban'              => 'required|string',
            'jenis_tpa_tinja'           => 'required|string',
            'foto'                      => 'nullable|image|max:5120|mimes:jpg,jpeg,png',
            'tanggal_pendataan'         => 'required|date',
        ]);

        $validated = $request->except(['_token', '_method', 'foto']);
        $validated['user_id'] = Auth::id();
        $validated['status_validasi'] = 'pending';

        if ($request->hasFile('foto')) {
            $validated['nama_file_foto'] = $request->file('foto')->store('foto_rumah', 'public');
        }

        DB::beginTransaction();
        try {
            $dataRtlh = DataRtlh::create($validated);
            $activeModel = \App\Models\ModelVersion::where('is_active', true)->first();

            // Kirim ke API ML
            $mlPayload = collect($validated)->except(['user_id','status_validasi','nama_file_foto','tanggal_pendataan','kelurahan_id'])->toArray();
            $prediction = $mlService->predict($mlPayload);

            if ($prediction && !empty($prediction['prediction_label'])) {
                HasilPrediksi::create([
                    'data_rtlh_id'     => $dataRtlh->id,
                    'model_version_id' => $activeModel?->id,
                    'label_prediksi'   => $prediction['prediction_label'],
                    'confidence_score' => $prediction['confidence_score'],
                ]);
            }
            // Graceful degradation: jika gagal, data tetap tersimpan sebagai pending

            DB::commit();
            return redirect()->route('pendata.survei.index')
                ->with('success', 'Data survei berhasil disimpan. ' .
                    ($prediction ? 'Hasil prediksi: ' . strtoupper($prediction['prediction_label']) : 'Layanan ML sedang dalam pemeliharaan, data disimpan sebagai Pending.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(DataRtlh $dataRtlh)
    {
        // Manual ownership check — pendata hanya bisa lihat data miliknya sendiri
        abort_if($dataRtlh->user_id !== Auth::id(), 403, 'Anda tidak memiliki akses ke data ini.');
        $dataRtlh->load(['kelurahan.kecamatan','hasilPrediksi.modelVersion','user']);
        return view('pendata.survei.show', compact('dataRtlh'));
    }

    public function edit(DataRtlh $dataRtlh)
    {
        abort_if($dataRtlh->user_id !== Auth::id() || $dataRtlh->status_validasi !== 'pending', 403, 'Tidak dapat mengedit data yang sudah divalidasi.');
        $kelurahans = Kelurahan::with('kecamatan')->orderBy('nama_kelurahan')->get();
        return view('pendata.survei.edit', compact('dataRtlh','kelurahans'));
    }

    public function update(Request $request, DataRtlh $dataRtlh, MachineLearningService $mlService)
    {
        abort_if($dataRtlh->user_id !== Auth::id() || $dataRtlh->status_validasi !== 'pending', 403);
        $validated = $request->except(['_token','_method','foto']);
        if ($request->hasFile('foto')) {
            $validated['nama_file_foto'] = $request->file('foto')->store('foto_rumah', 'public');
        }
        $dataRtlh->update($validated);

        // Re-predict setelah update
        $mlPayload = collect($validated)->except(['user_id','status_validasi','nama_file_foto','tanggal_pendataan','kelurahan_id'])->toArray();
        $prediction = $mlService->predict($mlPayload);
        if ($prediction && !empty($prediction['prediction_label'])) {
            $dataRtlh->hasilPrediksi()->updateOrCreate(
                ['data_rtlh_id' => $dataRtlh->id],
                ['label_prediksi' => $prediction['prediction_label'], 'confidence_score' => $prediction['confidence_score']]
            );
        }

        return redirect()->route('pendata.survei.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Request $request, DataRtlh $dataRtlh)
    {
        abort_if($dataRtlh->user_id !== Auth::id(), 403);
        if ($dataRtlh->status_validasi !== 'pending') {
            return back()->with('error', 'Data yang sudah divalidasi tidak bisa dihapus.');
        }
        $dataRtlh->delete();
        return redirect()->route('pendata.survei.index')->with('success', 'Data berhasil dihapus.');
    }

    public function exportPdf(DataRtlh $dataRtlh)
    {
        abort_if($dataRtlh->user_id !== Auth::id(), 403);
        return back()->with('info', 'Fitur ekspor PDF akan segera tersedia.');
    }
}
