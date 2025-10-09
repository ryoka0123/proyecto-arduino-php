<?php

namespace App\Http\Controllers;

use App\Models\Arduino;

class ManagerController extends Controller
{
    public function index($arduino_id)
    {
        $arduino = Arduino::findOrFail($arduino_id); // Cargar el modelo Arduino
        return view('codeManager.codeEditor', [
            'arduino' => $arduino,
        ]);
    }
}
