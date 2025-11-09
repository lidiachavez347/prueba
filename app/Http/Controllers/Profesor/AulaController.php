<?php

namespace App\Http\Controllers\Profesor;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ActividadDetalle;
use App\Models\Actividades;
use App\Models\Estudiante;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    public function show($id)
    {
        // Obtener la actividad por ID
        $actividad = Actividades::findOrFail($id);
        $prof = Profesore::where('id_user', Auth::id())->first();
        $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();
        $estudiantes = Estudiante::where('id_curso', $asig->id_curso)->select('id as estudiante_id', 'nombres_es', 'apellidos_es')->get();


        $notas = ActividadDetalle::where('id_actividad', $id)
            ->join('estudiantes', 'actividad_detalles.id_estudiante', '=', 'estudiantes.id')
            ->select('estudiantes.id as estudiante_id', 'estudiantes.nombres_es', 'estudiantes.apellidos_es', 'actividad_detalles.nota','actividad_detalles.observacion','actividad_detalles.archivo','actividad_detalles.imagen')
            ->get();

        return view('profesor.aula.show', compact('actividad', 'estudiantes', 'notas'));
    }

    public function update(Request $request, $id)
    {
        $estudiantes = $request->input('estudiantes');

        foreach ($estudiantes as $estudiante) {
            ActividadDetalle::updateOrCreate(
                [
                    'id_actividad' => $id,
                    'id_estudiante' => $estudiante['id'],
                ],
                [
                    'nota' => $estudiante['nota'],
                    'observacion' => $estudiante['observacion'],
                ]
            );
        }

        return redirect()->route('profesor.aula.show', $id)->with('success', 'Notas actualizadas correctamente.');
    }
}
