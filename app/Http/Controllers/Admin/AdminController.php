<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataRtlh;
use App\Models\ModelVersion;
use App\Models\User;
use App\Models\Berita;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSurvei = DataRtlh::count();
        $totalPending = DataRtlh::where('status_validasi', 'pending')->count();
        $totalDisetujui = DataRtlh::where('status_validasi', 'disetujui')->count();
        $totalDitolak = DataRtlh::where('status_validasi', 'ditolak')->count();
        $totalPendata = User::where('role', 'pendata')->where('is_active', true)->count();
        $modelAktif = ModelVersion::where('is_active', true)->first();

        // Data untuk chart
        $rtlhCount = DataRtlh::whereHas('hasilPrediksi', fn($q) => $q->where('label_prediksi', 'rtlh'))->count();
        $rlhCount  = DataRtlh::whereHas('hasilPrediksi', fn($q) => $q->where('label_prediksi', 'rlh'))->count();

        // Distribusi per kecamatan (top 8)
        $perKecamatan = DataRtlh::with('kelurahan.kecamatan')
            ->get()
            ->groupBy(fn($d) => $d->kelurahan->kecamatan->nama_kecamatan ?? 'Tidak Diketahui')
            ->map->count()
            ->sortDesc()
            ->take(8);

        // Mayoritas sumber air
        $sumberAir = DataRtlh::selectRaw('sumber_air_minum, count(*) as total')
            ->groupBy('sumber_air_minum')
            ->orderByDesc('total')
            ->take(6)
            ->pluck('total', 'sumber_air_minum');

        // Tren survei bulanan (12 bulan terakhir)
        $trendSurvei = DataRtlh::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Kondisi atap dominan
        $kondisiAtap = DataRtlh::selectRaw('kondisi_atap, count(*) as total')
            ->groupBy('kondisi_atap')->orderByDesc('total')->take(5)
            ->pluck('total', 'kondisi_atap');

        return view('admin.dashboard', compact(
            'totalSurvei','totalPending','totalDisetujui','totalDitolak',
            'totalPendata','modelAktif','rtlhCount','rlhCount',
            'perKecamatan','sumberAir','trendSurvei','kondisiAtap'
        ));
    }
}
