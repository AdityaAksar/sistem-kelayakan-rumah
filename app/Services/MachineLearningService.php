<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Exception;

class MachineLearningService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('ml.base_url', 'https://api-perkimtan.astrantia.site/api/v1');
        $this->apiKey = config('ml.api_key', '');
        $this->timeout = 3;
    }

    /**
     * Mengirim data survei ke API ML untuk mendapatkan prediksi kelayakan.
     * Menerapkan Graceful Degradation jika Request Timeout atau Error (SRS RNF-03).
     *
     * @param array $payload
     * @return array|null Null jika koneksi gagal/timeout
     */
    public function predict(array $payload): ?array
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout($this->timeout)
                ->acceptJson()
                ->post("{$this->baseUrl}/predict", $payload);

            if ($response->successful()) {
                // Response yang diharapkan: { success: true, prediction_label: 'rlh', confidence_score: 0.98 }
                return $response->json();
            }

            Log::error('ML API Prediction Failed: HTTP ' . $response->status(), ['response' => $response->body()]);
            return null;
        } catch (Exception $e) {
            Log::error('ML API Prediction Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * MLOps: Mengirim file model .pkl terbaru ke API ML.
     *
     * @param UploadedFile $file
     * @param string $versionName
     * @return array|null Null jika koneksi gagal/timeout
     */
    public function updateModel(UploadedFile $file, string $versionName): ?array
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(10) // Timeout lebih lama untuk upload file
                ->attach('model_file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post("{$this->baseUrl}/model/upload", [
                    'version_name' => $versionName
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('ML API Model Upload Failed: HTTP ' . $response->status(), ['response' => $response->body()]);
            return null;
        } catch (Exception $e) {
            Log::error('ML API Model Upload Exception: ' . $e->getMessage());
            return null;
        }
    }
}
