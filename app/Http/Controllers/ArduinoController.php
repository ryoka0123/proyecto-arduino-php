<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arduino;

class ArduinoController extends Controller
{
    public function index()
    {
        $arduinos = Arduino::where('user_id', auth()->id())->get();

        return view('dashboard.microcontrolador', [
            'arduinos' => $arduinos,
            'username' => auth()->user()->name,
            'year' => date('Y'),
        ]);
    }

    // Mostrar formulario de registro de Arduino
    public function create()
    {
        return view('forms.registroArduino');
    }

    // Guardar Arduino en la base de datos
    public function store(Request $request)
    {
        // Validar que la IP no esté registrada por otro Arduino
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ip' => 'required|string|max:100|unique:arduinos,ip',
        ], [
            'ip.unique' => 'La IP ya está registrada por otro Arduino.',
        ]);

        Arduino::create([
            'user_id' => auth()->id(),
            'nombre' => $request->nombre,
            'ip' => $request->ip,
        ]);

        return redirect()->route('microcontrolador');
    }
    public function destroy($id)
    {
        // Buscar el Arduino del usuario autenticado
        $arduino = \App\Models\Arduino::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Eliminar el Arduino (y triggers si tienes onDelete('cascade'))
        $arduino->delete();

        return redirect()->route('microcontrolador');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'ip' => 'required|string|max:100',
        ]);

        $arduino = Arduino::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $arduino->nombre = $request->nombre;
        $arduino->ip = $request->ip;
        $arduino->save();

        return redirect()->route('microcontrolador')->with('success', 'Arduino actualizado correctamente.');
    }
}
