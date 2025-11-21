<?php

namespace App\Http\Controllers;

use App\Models\Arduino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class ManagerController extends Controller
{
    public function index(Arduino $arduino)
    {
        $baseUrl = env('COMPILATION_SERVICE_URL', 'http://127.0.0.1');
        $port = env('COMPILATION_SERVICE_PORT', '8001');

        $compilerServiceHttpUrl = rtrim($baseUrl, '/') . ':' . $port;

        $compilerServiceWsUrl = str_replace(['http://', 'https://'], ['ws://', 'wss://'], $compilerServiceHttpUrl);

        return view('codeManager.codeEditor', [
            'arduino' => $arduino,
            'code' => $arduino->code ?? '',
            'compilerServiceHttpUrl' => $compilerServiceHttpUrl,
            'compilerServiceWsUrl' => $compilerServiceWsUrl,
        ]);
    }

    public function compile(Request $request, Arduino $arduino)
    {
        $request->validate(['code' => 'required|string']);
        $codigoArduino = $request->input('code');
        
        $compilationServiceUrl = env('COMPILATION_SERVICE_URL') . ':' . env('COMPILATION_SERVICE_PORT') . '/api/compiler/compilar';

        try {
            $arduino->update(['code' => $codigoArduino]);

            $response = Http::timeout(15)->post($compilationServiceUrl, [
                'code' => $codigoArduino
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] === true) {
                    return response()->json([
                        'output' => $data['output'] ?? '> Proceso completado sin salida adicional.'
                    ]);
                } else {
                    return response()->json([
                        'error' => $data['error'] ?? 'El servicio de compilación devolvió un error desconocido.',
                        'output' => $data['output'] ?? '> Compilación fallida.'
                    ], 422);
                }
            } else {
                $errorMessage = "Error del servicio de compilación (HTTP " . $response->status() . ")";
                return response()->json([
                    'error' => $errorMessage,
                    'output' => $response->body()
                ], $response->status());
            }
        } catch (ConnectionException $e) {
            $errorMessage = "Error de conexión: No se pudo contactar el servicio de compilación.";
            return response()->json([
                'error' => $errorMessage,
                'output' => 'Verifica que el servicio esté en línea y vuelve a intentarlo.'
            ], 503);
        }
    }

    public function save(Request $request, Arduino $arduino)
    {
        $request->validate(['code' => 'nullable|string']);

        try {
            $arduino->update(['code' => $request->input('code') ?? '']);
            return response()->json([
                'output' => '> Código guardado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el código en el servidor.',
                'output' => '> Fallo al guardar.'
            ], 500);
        }
    }
}
