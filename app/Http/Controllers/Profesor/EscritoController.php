<?php

namespace App\Http\Controllers\Profesor;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Estudiante_tutor;
use App\Models\Examene;
use App\Models\Nota_actividades;
use App\Models\Nota_examenes;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use Illuminate\Http\Request;
use App\Providers\TwilioService;

class EscritoController extends Controller
{
    public function show($id)
    {
        // Obtener la actividad por ID
        $actividad = Examene::findOrFail($id);

        $prof = Profesore::where('id_user', Auth::id())->first();
        $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();
        $estudiantes = Estudiante::where('id_curso', $asig->id_curso)->select('id as estudiante_id', 'nombres_es', 'apellidos_es')->get();


        $notas = Nota_examenes::where('id_examen', $id)
            ->join('estudiantes', 'nota_examenes.id_estudiante', '=', 'estudiantes.id')
            ->select('estudiantes.id as estudiante_id', 'estudiantes.nombres_es', 'estudiantes.apellidos_es', 'nota_examenes.nota', 'nota_examenes.observacion')
            ->get();

        $actualizar = count($notas);
        // Retornar la vista con la actividad y los estudiantes
        return view('profesor.escrito.show', compact('actividad', 'estudiantes', 'actualizar', 'notas'));
    }
    public function update(Request $request, $id)
    {
        $estudiantes = $request->input('estudiantes');
        $e = Examene::where('id', $id)->first();

        foreach ($estudiantes as $estudiante) {
            Nota_examenes::updateOrCreate(
                [
                    'id_examen' => $id,
                    'id_estudiante' => $estudiante['id'],
                ],
                [
                    'id_asignatura' => $e->id_asignatura,
                    'id_trimestre' => $e->id_trimestre,
                    'nota' => $estudiante['nota'],
                    'observacion' => $estudiante['observacion'],
                ]
            );
        }

        return redirect()->route('profesor.escrito.show', $id)->with('success', 'Notas actualizadas correctamente.');
    }
    public function store(Request $request, $id)
    {
        // Validar entrada básica
        $request->validate([
            'estudiantes' => 'required|array',
            'estudiantes.*.id' => 'required|integer|exists:estudiantes,id',
            'estudiantes.*.nota' => 'required|numeric|min:0|max:100',
            'estudiantes.*.observacion' => 'nullable|string',
        ]);

        // Recibir la lista de estudiantes con sus notas
        $estudiantes = $request->input('estudiantes');
        $idActividad = $request->id;

        $e = Examene::where('id', $id)->first();

        foreach ($estudiantes as $dataEstudiante) {
            // Crear o actualizar la nota de cada estudiante para la actividad
            Nota_examenes::updateOrCreate(
                [
                    'id_examen' => $id,
                    'id_estudiante' => $dataEstudiante['id'],
                ],
                [
                    'id_asignatura' => $e->id_asignatura,
                    'id_trimestre' => $e->id_trimestre,
                    'nota' => $dataEstudiante['nota'],
                    'observacion' => $dataEstudiante['observacion'] ?? null,
                ]
            );

            // Obtener el tutor asociado al estudiante
            $acti = Examene::where('id', $idActividad)->first();
            $tutor = Estudiante_tutor::join('estudiantes', 'estudiantes.id', '=', 'estudiante_tutors.id_estudiante')
                ->join('tutores', 'tutores.id', '=', 'estudiante_tutors.id_tutor')
                ->join('users', 'users.id', '=', 'tutores.id_user')
                ->where('estudiantes.id', $dataEstudiante['id'])
                ->select('users.nombres', 'users.apellidos', 'users.telefono', 'estudiantes.nombres_es', 'estudiantes.apellidos_es')
                ->first();

            if ($tutor) {
                // Crear el mensaje
                $mensaje = "Estimado {$tutor->nombres} {$tutor->apellidos}, se ha registrado la nota del estudiante {$tutor->nombres_es} {$tutor->apellidos_es} para la evaluacion {$acti->nombre} con una nota de: {$dataEstudiante['nota']} ptos, {$dataEstudiante['observacion']}.";


                $twilioService = new TwilioService();
                //dd($mensaje);
                try {
                    $response = $twilioService->sendSMS($tutor->telefono, $mensaje);
                    \Log::info("SMS enviado a {$tutor->telefono}: " . json_encode($response));
                    //dd($response);
                } catch (\Exception $e) {
                    \Log::error("Error enviando SMS a {$tutor->telefono}: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('profesor.escrito.show', $id)->with('success', 'Notas guardadas correctamente.');
    }
}
