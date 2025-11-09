<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date'
        ]);
    
        Evento::create($validatedData);
    
        return response()->json(['message' => 'Evento agregado correctamente'], 201);
    }
    
}
