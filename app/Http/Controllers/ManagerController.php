<?php

namespace App\Http\Controllers;

use App\Models\Arduino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Importar el cliente HTTP de Laravel
use Illuminate\Http\Client\ConnectionException; // Importar la excepción de conexión

class ManagerController extends Controller
{
    /**
     * Muestra la vista del editor de código.
     */
    public function index(Arduino $arduino)
    {
        return view('codeManager.codeEditor', [
            'arduino' => $arduino,
            'code' => $arduino->code ?? '' // Cargar el código o una cadena vacía
        ]);
    }

    /**
     * Recibe, envía a compilar y procesa el código del editor.
     */
    public function compile(Request $request, Arduino $arduino)
    {
        // 1. Validar que el código no esté vacío.
        $request->validate([
            'code' => 'required|string',
        ]);

        $codigoArduino = $request->input('code');

        $compilationServiceUrl = config('services.server_url.url');
        
        try {
            // 2. Realizar la petición POST al servicio externo.
            // Se envía el código en formato JSON.
            $response = Http::timeout(15) // Esperar 15 segundos antes de considerar que falló
                           ->post($compilationServiceUrl, [
                                'code' => $codigoArduino,
                                'board' => 'arduino:avr:uno', // Opcional: puedes enviar el modelo de la placa
                            ]);

            // 3. Procesar la respuesta del servicio.
            if ($response->successful()) {
                // El servicio respondió con un código 2xx (Éxito)
                
                $data = $response->json();

                if (isset($data['success']) && $data['success'] === true) {
                    // CASO 1: Compilación exitosa
                    $output = $data['output'] ?? '> Proceso completado sin salida adicional.';
                    return redirect()->route('editor-codigo', $arduino->id)
                                     ->with('compilation_output', $output);
                } else {
                    // CASO 2: Error de compilación devuelto por el servicio
                    $error = $data['error'] ?? 'El servicio de compilación devolvió un error desconocido.';
                    $output = $data['output'] ?? '> Compilación fallida.';
                    return redirect()->route('editor-codigo', $arduino->id)
                                     ->with('compilation_error', $error)
                                     ->with('compilation_output', $output);
                }

            } else {
                // CASO 3: El servidor respondió con un error (4xx o 5xx)
                $errorMessage = "El servicio de compilación respondió con un error HTTP " . $response->status() . ".";
                $errorBody = $response->body(); // Puede contener más detalles del error
                
                return redirect()->route('editor-codigo', $arduino->id)
                                 ->with('compilation_error', $errorMessage . "\n\nRespuesta del servidor:\n" . $errorBody);
            }

        } catch (ConnectionException $e) {
            // CASO 4: Error de conexión (el servidor no responde, timeout, etc.)
            $errorMessage = "Error de conexión: No se pudo establecer comunicación con el servicio de compilación. Por favor, verifica que el servicio esté en línea y vuelve a intentarlo.";
            
            return redirect()->route('editor-codigo', $arduino->id)
                             ->with('compilation_error', $errorMessage);
        }
    }

    /**
     * Guarda el código del editor.
     */
    public function save(Request $request, Arduino $arduino)
    {
        $request->validate([
            'code' => 'nullable|string',
        ]);

        $arduino->update(['code' => $request->input('code')]);

        return redirect()->route('editor-codigo', $arduino->id)
                         ->with('success', 'Código guardado exitosamente.');
    }
}