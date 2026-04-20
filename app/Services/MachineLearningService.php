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
        $this->baseUrl = config('ml.base_url', 'https://api-perkimtan.astrantia.site');
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
            $apiPayload = [
                'luas_rumah'          => $payload['luas_rumah'] ?? 0,
                'jumlah_penghuni'     => $payload['jumlah_penghuni'] ?? 0,
                'pondasi'             => $payload['pondasi'] ?? '',
                'kondisi_kolom'       => $payload['kondisi_kolom'] ?? '',
                'kondisi_rangka_atap' => $payload['kondisi_rangka_atap'] ?? '',
                'kondisi_plafon'      => $payload['kondisi_plafon'] ?? '',
                'kondisi_balok'       => $payload['kondisi_balok'] ?? '',
                'kondisi_sloof'       => $payload['kondisi_sloof'] ?? '',
                'kondisi_jendela'     => $payload['kondisi_jendela'] ?? '',
                'kondisi_ventilasi'   => $payload['kondisi_ventilasi'] ?? '',
                'kondisi_lantai'      => $payload['kondisi_lantai'] ?? '',
                'kondisi_dinding'     => $payload['kondisi_dinding'] ?? '',
                'kondisi_atap'        => $payload['kondisi_atap'] ?? '',
                'material_atap'       => $payload['material_atap_terluas'] ?? '',
                'material_dinding'    => $payload['material_dinding_terluas'] ?? '',
                'material_lantai'     => $payload['material_lantai_terluas'] ?? '',
                'sumber_air'          => $payload['sumber_air_minum'] ?? '',
                'jarak_air'           => $payload['jarak_sumber_air_tinja'] ?? '',
                'kamar_mandi'         => $payload['kamar_mandi_jamban'] ?? '',
                'jenis_jamban'        => $payload['jenis_jamban'] ?? '',
                'jenis_tpa'           => $payload['jenis_tpa_tinja'] ?? '',
            ];

            $response = Http::withToken($this->apiKey)
                ->timeout($this->timeout)
                ->acceptJson()
                ->post("{$this->baseUrl}/predict", $apiPayload);

            if ($response->successful() && $response->json('status') === 'success') {
                $data = $response->json('data');
                $label = ($data['prediction'] ?? '') === 'Tidak Layak Huni' ? 'rtlh' : 'rlh';
                
                return [
                    'success'          => true,
                    'prediction_label' => $label,
                    'confidence_score' => $data['probability'] ?? 0.0
                ];
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
                ->attach('model', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post("{$this->baseUrl}/update_model");

            if ($response->successful() && $response->json('status') === 'success') {
                return [
                    'success' => true,
                    'active_version' => $versionName
                ];
            }

            Log::error('ML API Model Upload Failed: HTTP ' . $response->status(), ['response' => $response->body()]);
            return null;
        } catch (Exception $e) {
            Log::error('ML API Model Upload Exception: ' . $e->getMessage());
            return null;
        }
    }
}
