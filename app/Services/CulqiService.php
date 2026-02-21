<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CulqiService
{
    protected string $baseUrl = 'https://api.culqi.com/v2';
    protected ?string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.culqi.secret_key');
    }

    /**
     * Crear un cargo (Charge) en Culqi.
     */
    public function createCharge(array $data)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/charges", [
                    'amount' => $data['amount'], // En céntimos
                    'currency_code' => $data['currency'] ?? 'PEN',
                    'email' => $data['email'],
                    'source_id' => $data['source_id'], // Token de tarjeta o ID de PagoEfectivo
                    'description' => $data['description'] ?? 'Pago ACIS',
                    'metadata' => $data['metadata'] ?? [],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Culqi Charge Error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return [
                'error' => true,
                'message' => $response->json()['user_message'] ?? 'Error al procesar el pago con Culqi.',
                'code' => $response->json()['code'] ?? 'unknown',
            ];

        } catch (\Exception $e) {
            Log::error('Culqi Exception', ['message' => $e->getMessage()]);
            return [
                'error' => true,
                'message' => 'Ocurrió un error inesperado al conectar con Culqi.',
            ];
        }
    }

    /**
     * Obtener los detalles de un cargo.
     */
    public function getCharge(string $chargeId)
    {
        $response = Http::withToken($this->secretKey)->get("{$this->baseUrl}/charges/{$chargeId}");
        return $response->successful() ? $response->json() : null;
    }
}
