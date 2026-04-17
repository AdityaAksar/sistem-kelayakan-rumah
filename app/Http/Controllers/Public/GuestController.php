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

    public function statistik()
    {
        $kecamatans = Kecamatan::with('kelurahans')->get();

        // Chart: RLH vs RTLH per kecamatan
        $perKecamatan = DataRtlh::with('kelurahan.kecamatan')
            ->get()
            ->groupBy(fn($d) => optional($d->kelurahan->kecamatan)->nama_kecamatan ?? 'Lainnya')
            ->map(function($items) {
                return [
                    'total' => $items->count(),
                    'rlh'   => $items->filter(fn($d) => optional($d->hasilPrediksi)->label_prediksi === 'rlh')->count(),
                    'rtlh'  => $items->filter(fn($d) => optional($d->hasilPrediksi)->label_prediksi === 'rtlh')->count(),
                ];
            });

        // Sumber air dominan
        $sumberAir = DataRtlh::selectRaw('sumber_air_minum, count(*) as total')
            ->groupBy('sumber_air_minum')->orderByDesc('total')->take(6)->pluck('total','sumber_air_minum');

        // Kondisi atap
        $kondisiAtap = DataRtlh::selectRaw('kondisi_atap, count(*) as total')
            ->groupBy('kondisi_atap')->orderByDesc('total')->take(5)->pluck('total','kondisi_atap');

        // Material lantai
        $materialLantai = DataRtlh::selectRaw('material_lantai_terluas, count(*) as total')
            ->groupBy('material_lantai_terluas')->orderByDesc('total')->take(5)->pluck('total','material_lantai_terluas');

        // Kepemilikan rumah
        $kepemilikanRumah = DataRtlh::selectRaw('kepemilikan_rumah, count(*) as total')
            ->groupBy('kepemilikan_rumah')->orderByDesc('total')->take(5)->pluck('total','kepemilikan_rumah');

        return view('guest.statistik', compact('perKecamatan','sumberAir','kondisiAtap','materialLantai','kepemilikanRumah','kecamatans'));
    }
}
