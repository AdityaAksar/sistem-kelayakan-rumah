<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataRtlh;
use App\Models\ModelVersion;
use App\Models\User;
use App\Models\Berita;

class AdminController extends Controller
{
    public function dashboard(\Illuminate\Http\Request $request)
    {
        // Parameter Referensi untuk dropdown
        $kecamatans = \App\Models\Kecamatan::with('kelurahans')->get();
        $pendatas = User::where('role', 'pendata')->get();

        $query = DataRtlh::with(['kelurahan.kecamatan', 'hasilPrediksi', 'user']);

        // --- Filter Berlapis ---
        if ($request->filled('kecamatan')) $query->whereHas('kelurahan', fn($q) => $q->where('kecamatan_id', $request->kecamatan));
        if ($request->filled('kelurahan')) $query->where('kelurahan_id', $request->kelurahan);
        if ($request->filled('bantuan')) $query->where('bantuan_pemerintah', $request->bantuan);
        if ($request->filled('kepemilikan_tanah')) $query->where('kepemilikan_tanah', $request->kepemilikan_tanah);
        if ($request->filled('umur_min')) $query->where('umur', '>=', $request->umur_min);
        if ($request->filled('umur_max')) $query->where('umur', '<=', $request->umur_max);
        if ($request->filled('pekerjaan')) $query->where('pekerjaan', 'LIKE', '%' . $request->pekerjaan . '%');
        if ($request->filled('tanggal_start')) $query->whereDate('tanggal_pendataan', '>=', $request->tanggal_start);
        if ($request->filled('tanggal_end')) $query->whereDate('tanggal_pendataan', '<=', $request->tanggal_end);

        $allData = $query->get();

        // High Level Metrics
        $totalSurvei = $allData->count();
        $totalPending = $allData->where('status_validasi', 'pending')->count();
        $totalDisetujui = $allData->where('status_validasi', 'disetujui')->count();
        $totalDitolak = $allData->where('status_validasi', 'ditolak')->count();

        // 1. Peta Titik Parsial (Geotagging)
        $gpsValid = $allData->filter(fn($d) => !empty($d->latitude) && !empty($d->longitude))->values();
        $gpsValidCount = $gpsValid->count();
        $gpsKosongCount = $totalSurvei - $gpsValidCount;

        $mapData = $gpsValid->map(function($d) {
            return [
                'lat' => $d->latitude,
                'lng' => $d->longitude,
                'nama' => $d->nama_kepala_rumah_tangga,
                'nik' => $d->nik,
                'status' => optional($d->hasilPrediksi)->label_prediksi === 'rtlh' ? 'rtlh' : 'rlh'
            ];
        })->values();

        // 2. Grafik Kelengkapan Geotagging per Kecamatan
        $geotagKecamatan = $allData->groupBy(fn($d) => optional($d->kelurahan->kecamatan)->nama_kecamatan ?? 'Lainnya')
            ->map(function($items) {
                return [
                    'valid' => $items->filter(fn($d) => !empty($d->latitude) && !empty($d->longitude))->count(),
                    'kosong' => $items->filter(fn($d) => empty($d->latitude) || empty($d->longitude))->count(),
                ];
            });

        // 3. Matriks Prioritas (Penghasilan vs Kerusakan Fisik)
        $scatterPrioritas = $allData->map(function($d) {
            $kerusakan = 0;
            // Hitung variabel tidak layak
            if(in_array($d->kondisi_atap, ['Kurang Layak', 'Tidak Layak'])) $kerusakan++;
            if(in_array($d->kondisi_dinding, ['Kurang Layak', 'Tidak Layak'])) $kerusakan++;
            if(in_array($d->kondisi_lantai, ['Kurang Layak', 'Tidak Layak'])) $kerusakan++;
            if($d->kamar_mandi_jamban == 'Tidak Ada') $kerusakan++;
            
            return [
                'x' => (float) $d->penghasilan_per_bulan,
                'y' => $kerusakan,
                'nik' => $d->nik,
                'nama' => $d->nama_kepala_rumah_tangga
            ];
        })->values();

        // 4. Fisik Bangunan (Stacked Bar)
        $getFisik = function($field) use ($allData) {
            $grouped = $allData->groupBy($field)->map->count();
            return [
                'Layak' => ($grouped['Layak']??0) + ($grouped['Menuju Layak']??0),
                'Agak Layak' => $grouped['Agak Layak'] ?? 0,
                'Tidak Layak' => ($grouped['Kurang Layak']??0) + ($grouped['Tidak Layak']??0),
            ];
        };
        $fisikData = [
            'Pondasi' => $getFisik('pondasi'),
            'Kolom' => $getFisik('kondisi_kolom'),
            'Atap' => $getFisik('kondisi_atap'),
            'Dinding' => $getFisik('kondisi_dinding'),
            'Lantai' => $getFisik('kondisi_lantai')
        ];

        // 5. Overcrowding (Scatter)
        $scatterOvercrowding = $allData->map(function($d) {
            return [
                'x' => (float) $d->luas_rumah,
                'y' => (int) $d->jumlah_penghuni,
                'nama' => $d->nama_kepala_rumah_tangga
            ];
        })->values();

        // 6. Syarat Legalitas
        $legalitasTanah = $allData->groupBy('kepemilikan_tanah')->map->count();

        // 7. Analisis Kerentanan Demografi (Umur) ranges
        $demografiUmur = [
            '< 30' => $allData->where('umur', '<', 30)->count(),
            '30-45' => $allData->whereBetween('umur', [30, 45])->count(),
            '46-60' => $allData->whereBetween('umur', [46, 60])->count(),
            '> 60' => $allData->where('umur', '>', 60)->count(),
        ];

        $tabelBnba = $allData; // Bisa di pagination jika perlu, tapi kita oper semua untuk client-side datatable atau slicing

        return view('admin.dashboard', compact(
            'totalSurvei', 'totalPending', 'totalDisetujui', 'totalDitolak',
            'kecamatans', 'pendatas', 'mapData', 'gpsValidCount', 'gpsKosongCount',
            'geotagKecamatan', 'scatterPrioritas', 'fisikData', 'scatterOvercrowding',
            'legalitasTanah', 'demografiUmur', 'tabelBnba'
        ));
    }
}
