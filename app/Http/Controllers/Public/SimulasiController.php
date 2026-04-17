<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Services\MachineLearningService;
use Illuminate\Http\Request;

class SimulasiController extends Controller
{
    public function index()
    {
        $kelurahans = Kelurahan::with('kecamatan')->orderBy('nama_kelurahan')->get();
        return view('guest.simulasi', compact('kelurahans'));
    }

    public function process(Request $request, MachineLearningService $mlService)
    {
        $request->validate([
            'g-recaptcha-response' => 'nullable|string',
            'luas_rumah'           => 'required|integer|min:0',
            'luas_lahan'           => 'required|integer|min:0',
            'kondisi_atap'         => 'required|string',
            'kondisi_dinding'      => 'required|string',
            'kondisi_lantai'       => 'required|string',
            'sumber_air_minum'     => 'required|string',
            'jenis_jamban'         => 'required|string',
            'penghasilan_per_bulan'=> 'required|integer|min:0',
        ]);

        // Kirim ke API ML – tidak disimpan ke DB (by SRS RF-PUB-04)
        $payload = $request->except(['_token','g-recaptcha-response']);
        $result  = $mlService->predict($payload);

        if (!$result) {
            return back()->withInput()->with('error', 'Layanan simulasi sedang dalam pemeliharaan. Silakan coba beberapa saat lagi.');
        }

        return back()->withInput()->with([
            'simulasi_label'  => $result['prediction_label'] ?? null,
            'simulasi_score'  => $result['confidence_score'] ?? null,
        ]);
    }
}
