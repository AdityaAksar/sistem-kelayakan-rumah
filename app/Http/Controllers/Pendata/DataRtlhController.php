<?php

namespace App\Http\Controllers\Pendata;

use App\Http\Controllers\Controller;
use App\Models\DataRtlh;
use App\Models\HasilPrediksi;
use App\Services\MachineLearningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataRtlhController extends Controller
{
    public function index()
    {
        $data = DataRtlh::where('user_id', Auth::id())->get();
        return view('pendata.data_rtlhs.index', compact('data'));
    }

    public function store(Request $request, MachineLearningService $mlService)
    {
        // ... (Validasi panjang 40 atribut di-skip di sini untuk skeleton) ...
        $validated = $request->except(['_token', 'foto']);
        
        $validated['user_id'] = Auth::id();
        $validated['status_validasi'] = 'pending';

        if ($request->hasFile('foto')) {
            // Compress foto and upload logic here
            $validated['nama_file_foto'] = $request->file('foto')->store('foto_rumah', 'public');
        }

        DB::beginTransaction();
        try {
            $dataRtlh = DataRtlh::create($validated);

            // Fetch active model ID for logging
            $activeModel = \App\Models\ModelVersion::where('is_active', true)->first();

            // Panggil service ML
            $mlPayload = $validated; // Atau map field spesifik API
            $prediction = $mlService->predict($mlPayload);

            if ($prediction && isset($prediction['success']) && $prediction['success']) {
                HasilPrediksi::create([
                    'data_rtlh_id' => $dataRtlh->id,
                    'model_version_id' => $activeModel ? $activeModel->id : null,
                    'label_prediksi' => $prediction['prediction_label'],
                    'confidence_score' => $prediction['confidence_score'],
                ]);
            } else {
                // Graceful degradation
                // Prediksi null berarti "pending", nanti admin bisa retry.
            }

            DB::commit();
            return redirect()->route('pendata.dashboard')->with('success', 'Data berhasil diajukan dan diprediksi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Koneksi ke DB gagal: ' . $e->getMessage());
        }
    }
}
