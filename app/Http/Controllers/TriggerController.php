<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arduino;
use App\Models\Trigger;

class TriggerController extends Controller
{
    public function index($arduino_id)
    {
        $arduino = Arduino::where('id', $arduino_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $triggers = Trigger::where('arduino_id', $arduino->id)->get();

        return view('dashboard.triggers', [
            'arduino' => $arduino,
            'triggers' => $triggers,
        ]);
    }

    public function create($arduino_id)
    {
        // Puedes validar que el arduino pertenezca al usuario autenticado si lo deseas
        return view('forms.registroTriggers', [
            'arduino_id' => $arduino_id
        ]);
    }

    public function store(Request $request, $arduino_id)
    {
        // Validar que el Arduino pertenezca al usuario autenticado
        $arduino = Arduino::where('id', $arduino_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:100',
            'contexto' => 'required|string|max:255',
        ]);

        // Crear el trigger
        Trigger::create([
            'arduino_id' => $arduino->id,
            'nombre' => $request->nombre,
            'contexto' => $request->contexto,
        ]);

        return redirect()->route('triggers', $arduino->id);
    }

    public function update(Request $request, $arduino_id, $trigger_id)
    {
        // Validar que el Arduino pertenezca al usuario autenticado
        $arduino = Arduino::where('id', $arduino_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Buscar el trigger
        $trigger = Trigger::where('id', $trigger_id)
            ->where('arduino_id', $arduino->id)
            ->firstOrFail();

        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:100',
            'contexto' => 'required|string|max:255',
        ]);

        // Actualizar el trigger
        $trigger->nombre = $request->nombre;
        $trigger->contexto = $request->contexto;
        $trigger->save();

        return redirect()->route('triggers', $arduino->id);
    }

    public function destroy($arduino_id, $trigger_id)
    {
        // Validar que el Arduino pertenezca al usuario autenticado
        $arduino = Arduino::where('id', $arduino_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Buscar el trigger
        $trigger = Trigger::where('id', $trigger_id)
            ->where('arduino_id', $arduino->id)
            ->firstOrFail();

        // Eliminar el trigger
        $trigger->delete();

        return redirect()->route('triggers', $arduino->id);
    }

    // Otros métodos...
}
