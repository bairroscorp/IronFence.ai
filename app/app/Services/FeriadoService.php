<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FeriadoService
{
    public function getFeriados($ano, $estado = 'SP')
    {
        return Cache::remember("feriados:$ano:$estado", 86400, function () use ($ano, $estado) {
            $apiKey = env('INVERTEXTO_API_KEY');
            $url = "https://api.invertexto.com/v1/holidays/{$ano}?token={$apiKey}&state={$estado}";

            $response = Http::get($url);

            if (!$response->successful()) {
                dd('Erro na chamada API', $response->status(), $response->body());
            }

            $data = $response->json();

            return is_array($data) ? $data : [];
        });
    }

    public function isFeriado($data, $estado = 'SP')
    {
        $ano = date('Y', strtotime($data));

        $feriados = $this->getFeriados($ano, $estado);
        
        if (!is_array($feriados)) {
            $feriados = [];
        }

        foreach ($feriados as $feriado) {
            if (($feriado['date'] ?? null) === $data) {
                return true;
            }
        }

        return false;
    }
}
