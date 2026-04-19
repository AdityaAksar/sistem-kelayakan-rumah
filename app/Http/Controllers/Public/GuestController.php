<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\DataRtlh;
use App\Models\Kecamatan;

class GuestController extends Controller
{
    public function index()
    {
        $totalSurvei = DataRtlh::count();
        $totalRtlh   = DataRtlh::whereHas('hasilPrediksi', fn($q) => $q->where('label_prediksi','rtlh'))->count();
        $beritaTerkini = Berita::where('status','published')->latest()->take(3)->get();

        return view('guest.home', compact('totalSurvei','totalRtlh','beritaTerkini'));
    }

    public function profil()  { return view('guest.profil'); }
    public function prosedur(){ return view('guest.prosedur'); }
    public function faq()     { return view('guest.faq'); }

    public function statistik(\Illuminate\Http\Request $request)
    {
        $kecamatans = Kecamatan::with('kelurahans')->get();
        // Base Query
        $base = DataRtlh::with(['kelurahan.kecamatan', 'hasilPrediksi']);

        if ($request->filled('kecamatan')) {
            $base->whereHas('kelurahan', fn($q) => $q->where('kecamatan_id', $request->kecamatan));
        }
        if ($request->filled('status')) {
            $base->whereHas('hasilPrediksi', fn($q) => $q->where('label_prediksi', $request->status));
        }
        if ($request->filled('kawasan')) {
            $base->where('jenis_kawasan', $request->kawasan);
        }

        $allData = $base->get();

        $totalSurvei = $allData->count();
        $totalRtlh = $allData->filter(fn($d) => optional($d->hasilPrediksi)->label_prediksi === 'rtlh')->count();
        $totalRlh = $allData->filter(fn($d) => optional($d->hasilPrediksi)->label_prediksi === 'rlh')->count();

        // Top 5 Wilayah Perhatian (Kecamatan)
        $perKecamatan = $allData->groupBy(fn($d) => optional($d->kelurahan->kecamatan)->nama_kecamatan ?? 'Lainnya')
            ->map(function($items) {
                return [
                    'total' => $items->count(),
                    'rtlh'  => $items->filter(fn($d) => optional($d->hasilPrediksi)->label_prediksi === 'rtlh')->count(),
                    'rlh'   => $items->filter(fn($d) => optional($d->hasilPrediksi)->label_prediksi === 'rlh')->count(),
                ];
            });
        
        $top5KecamatanRtlh = collect($perKecamatan)
            ->map(fn($item, $key) => ['kecamatan' => $key, 'rtlh' => $item['rtlh'], 'persentase' => $item['total'] > 0 ? round(($item['rtlh'] / $item['total']) * 100, 1) : 0])
            ->sortByDesc('persentase')
            ->take(5)->values();

        // Distribusi Tipologi Kawasan
        $tipologiKawasan = $allData->groupBy('jenis_kawasan')
            ->map(fn($items) => $items->count())
            ->sortDesc()->take(6);

        // Kondisi Utama
        $sumberAir = $allData->groupBy('sumber_air_minum')->map(fn($items) => $items->count())->sortDesc()->take(5);
        $materialAtap = $allData->groupBy('material_atap_terluas')->map(fn($items) => $items->count())->sortDesc()->take(5);

        // Kepadatan Rata-rata
        $avgPenghuni = $allData->avg('jumlah_penghuni') ?? 0;
        $avgLuas = $allData->avg('luas_rumah') ?? 0;

        return view('guest.statistik', compact(
            'totalSurvei', 'totalRtlh', 'totalRlh', 
            'top5KecamatanRtlh', 'tipologiKawasan', 'sumberAir', 'materialAtap',
            'avgPenghuni', 'avgLuas', 'perKecamatan', 'kecamatans', 'allData'
        ));
    }
}
