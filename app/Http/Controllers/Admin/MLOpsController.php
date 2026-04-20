<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelVersion;
use App\Services\MachineLearningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MLOpsController extends Controller
{
    public function index()
    {
        $modelAktif = ModelVersion::where('is_active', true)->first();
        $riwayat    = ModelVersion::latest()->paginate(10);
        return view('admin.mlops.index', compact('modelAktif', 'riwayat'));
    }

    public function upload(Request $request, MachineLearningService $mlService)
    {
        $request->validate([
            'model_file'           => 'required|file|max:51200',
            'imputer_file'         => 'nullable|file|max:51200',
            'feature_columns_file' => 'nullable|file|max:51200',
            'version_name'         => 'required|string|max:50'
        ]);

        $response = $mlService->updateModel(
            $request->version_name,
            $request->file('model_file'),
            $request->file('imputer_file'),
            $request->file('feature_columns_file')
        );

        if (!$response) {
            return back()->with('error', 'Gagal terhubung ke API Machine Learning (Offline/Timeout).');
        }

        if (isset($response['success']) && $response['success']) {
            ModelVersion::where('is_active', true)->update(['is_active' => false]);

            $path = $request->file('model_file')->storeAs('models', $request->file('model_file')->getClientOriginalName());

            ModelVersion::create([
                'user_id'   => Auth::id(),
                'version'   => $request->version_name,
                'file_path' => $path,
                'is_active' => true,
            ]);

            return back()->with('success', 'Model berhasil diperbarui ke aktif: ' . ($response['active_version'] ?? $request->version_name));
        }

        return back()->with('error', 'API gagal memproses model.');
    }
}
