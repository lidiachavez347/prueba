<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\ActividadDetalle;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->all());
        $archivoPath = "u";
        $imagenPath = "u";
        $request->validate([
            'id_actividad' => 'required|exists:actividades,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:2048',
        ]);

        // Procesar archivo subido

        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $archivoPath = $archivo->store('archivos', 'public');
        }
        //dd($request->all());

        // Procesar imagen subida

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenPath = $imagen->store('images', 'public');
        }

        // Obtener al estudiante autenticado
        $estudiante = Estudiante::where('id_estudiante', Auth::id())->first();  // Corregido: debe ser 'Auth::id()'

        // Validar que el estudiante exista
        if (!$estudiante) {
            return redirect()->back()->with('error', 'No se encontró al estudiante autenticado.');
        }

        // Guardar o actualizar el detalle de la actividad
        ActividadDetalle::updateOrCreate(
            [
                'id_actividad' => $request->id_actividad,
                'id_estudiante' => $estudiante->id,  // Corregido: usar el id correcto del estudiante
            ],
            [
                'archivo' => $archivoPath,
                'imagen' => $imagenPath,
                'estado' => 'enviado',
                'nota' => 0,
            ]
        );


        // Redirigir con mensaje de éxito
        return redirect()->route('estudiante.contenidos.show', $request->id_actividad)
            ->with('success', 'La tarea ha sido enviada correctamente.');
    }
}
