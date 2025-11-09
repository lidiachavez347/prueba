<?php

namespace App\Http\Controllers\Profesor;
//use App\Services\VonageService;
use App\Http\Controllers\Profesor\VonageService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\Estudiante;
use App\Models\Estudiante_tutor;
use App\Models\Nota_actividades;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Providers\TwilioService;
use Illuminate\Http\Request;

class CasaController extends Controller
{
    public function show($id)
    {
        // Obtener la actividad por ID
        $actividad = Actividades::findOrFail($id);
        $prof = Profesore::where('id_user', Auth::id())->first();
        $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();
        $estudiantes = Estudiante::where('id_curso', $asig->id_curso)->select('id as estudiante_id', 'nombres_es', 'apellidos_es')->get();


        $notas = Nota_actividades::where('id_actividad', $id)
            ->join('estudiantes', 'nota_actividades.id_user', '=', 'estudiantes.id')
            ->select('estudiantes.id as estudiante_id', 'estudiantes.nombres_es', 'estudiantes.apellidos_es', 'nota_actividades.nota', 'nota_actividades.opservacion')
            ->get();

        $actualizar = count($notas);
        // Retornar la vista con la actividad y los estudiantes
        return view('profesor.casa.show', compact('actividad', 'estudiantes', 'actualizar', 'notas'));
    }
    public function update(Request $request, $id)
    {
        $estudiantes = $request->input('estudiantes');

        foreach ($estudiantes as $estudiante) {
            Nota_actividades::updateOrCreate(
                [
                    'id_actividad' => $id,
                    'id_user' => $estudiante['id'],
                ],
                [
                    'nota' => $estudiante['nota'],
                    'opservacion' => $estudiante['opservacion'],
                ]
            );
        }

        return redirect()->route('profesor.casa.show', $id)->with('success', 'Notas actualizadas correctamente.');
    }
    public function store(Request $request, $id)
    {
        // Validar entrada básica
        $request->validate([
            'estudiantes' => 'required|array',
            'estudiantes.*.id' => 'required|integer|exists:estudiantes,id',
            'estudiantes.*.nota' => 'required|numeric|min:0|max:100',
            'estudiantes.*.opservacion' => 'nullable|string',
        ]);

        // Recibir la lista de estudiantes con sus notas
        $estudiantes = $request->input('estudiantes');
        $idActividad = $request->id;

        foreach ($estudiantes as $dataEstudiante) {
            // Crear o actualizar la nota de cada estudiante para la actividad
            Nota_actividades::updateOrCreate(
                [
                    'id_actividad' => $id,
                    'id_user' => $dataEstudiante['id'],
                ],
                [
                    'nota' => $dataEstudiante['nota'],
                    'opservacion' => $dataEstudiante['opservacion'] ?? null,
                ]
            );

            // Obtener el tutor asociado al estudiante
            $acti = Actividades::where('id', $idActividad)->first();
            $tutor = Estudiante_tutor::join('estudiantes', 'estudiantes.id', '=', 'estudiante_tutors.id_estudiante')
                ->join('tutores', 'tutores.id', '=', 'estudiante_tutors.id_tutor')
                ->join('users', 'users.id', '=', 'tutores.id_user')
                ->where('estudiantes.id', $dataEstudiante['id'])
                ->select('users.nombres', 'users.apellidos', 'users.telefono', 'estudiantes.nombres_es', 'estudiantes.apellidos_es')
                ->first();
                
            /*if ($tutor) {
                // Crear el mensaje
                $mensaje = "Estimado {$tutor->nombres} {$tutor->apellidos}, se ha registrado la nota del estudiante {$tutor->nombres_es} {$tutor->apellidos_es} para la actividad {$acti->descripcion} con una nota de: {$dataEstudiante['nota']} ptos. Observación: {$dataEstudiante['opservacion']}.";
                
                // Instanciar el servicio de Vonage
                $vonageService = new TwilioService();
                $response = $vonageService->sendSMS($tutor->telefono, $mensaje);

                if ($response['success']) {
                    \Log::info("Mensaje enviado a {$tutor->telefono}");
                } else {
                
                    \Log::error("Error enviando SMS a {$tutor->telefono}: " . $response['error']);
                }
            }*/

            if ($tutor) {
                    // Crear el mensaje
                    $mensaje = "Estimado {$tutor->nombres} {$tutor->apellidos}, se ha registrado la nota del estudiante {$tutor->nombres_es} {$tutor->apellidos_es} para la actividad {$acti->descripcion} con una nota de: {$dataEstudiante['nota']} ptos. Observación: {$dataEstudiante['opservacion']}. Nota: Este mensaje es enviado desde una cuenta de prueba.";
                
                    // Instanciar el servicio de Twilio
                    $twilioService = new TwilioService();
                
                    try {
                        // Enviar mensaje por SMS
                        $response = $twilioService->sendSMS($tutor->telefono, $mensaje);
                
                        \Log::info("Mensaje SMS enviado a {$tutor->telefono}: " . json_encode($response));
                    } catch (\Exception $e) {
                        \Log::error("Error enviando mensaje SMS a {$tutor->telefono}: " . $e->getMessage());
                    }
                }
        }

        return redirect()->route('profesor.casa.show', $id)->with('success', 'Notas guardadas correctamente.');
    }
}
