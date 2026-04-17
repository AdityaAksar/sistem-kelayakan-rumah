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
            'penghasilan_per_bulan'   => 'required|integer|min:0',
            'jumlah_penghuni'         => 'required|integer|min:1',
            'luas_rumah'              => 'required|integer|min:1',
            'luas_lahan'              => 'required|integer|min:1',
            'kepemilikan_rumah'       => 'required|string',
            'jenis_kawasan'           => 'required|string',
            // Kondisi fisik
            'pondasi'                 => 'required|string',
            'material_atap_terluas'   => 'required|string',
            'kondisi_atap'            => 'required|string',
            'material_dinding_terluas'=> 'required|string',
            'kondisi_dinding'         => 'required|string',
            'material_lantai_terluas' => 'required|string',
            'kondisi_lantai'          => 'required|string',
            'kondisi_kolom'           => 'required|string',
            'kondisi_rangka_atap'     => 'required|string',
            'kondisi_plafon'          => 'required|string',
            'kondisi_balok'           => 'required|string',
            'kondisi_sloof'           => 'required|string',
            'kondisi_jendela'         => 'required|string',
            'kondisi_ventilasi'       => 'required|string',
            // Sanitasi
            'sumber_penerangan'       => 'required|string',
            'sumber_air_minum'        => 'required|string',
            'jarak_sumber_air_tinja'  => 'required|string',
            'kamar_mandi_jamban'      => 'required|string',
            'jenis_jamban'            => 'required|string',
            'jenis_tpa_tinja'         => 'required|string',
            'bantuan_pemerintah'      => 'required|string',
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
