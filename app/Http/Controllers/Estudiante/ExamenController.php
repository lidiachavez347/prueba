<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Examene;
use App\Models\Opcion;
use App\Models\Pregunta;
use App\Models\PreguntaResutado;
use App\Models\Resultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamenController extends Controller
{
    public function show($id)
    {
        // Obtener el examen por su ID
        $examen = Examene::findOrFail($id);

        // Obtener las preguntas del examen
        $preguntas = Pregunta::where('id_exam', $id)->get();

        // Verificar si el estudiante ya ha respondido el examen
        $estudiante = Estudiante::where('id_estudiante', Auth::user()->id)->first();
        $resultado = Resultado::where('id_estudiante', $estudiante->id)
            ->where('id_examen', $examen->id)
            ->first();
        // Pasar los datos a la vista
        return view('estudiante.automatico.show', compact('examen', 'preguntas','resultado','estudiante'));
    }
    public function store(Request $request)
    {
        $estudiante = Estudiante::where('id_estudiante', Auth::user()->id)->first();

        // Inicializar el total de puntos
        $totalPuntos = 0;
        $e = Examene::where('id', $request->examen)->first();
        // Guardar el resultado inicial en la tabla Resultado
        $resultado = new Resultado();
        $resultado->id_estudiante = $estudiante->id;
        $resultado->id_examen = $request->examen; // El ID del examen se pasa como parámetro
        $resultado->total_puntos = 0; // Inicializar el total de puntos en 0
        $resultado->estado = 1; // Definir el estado por defecto (puedes modificar esto según tu lógica)
        $resultado->id_asignatura = $e->id_asignatura;
        $resultado->id_trimestre = $e->id_trimestre; // Asegúrate de tener la lógica para obtener el trimestre
        $resultado->save();

        // Recorremos todas las respuestas enviadas
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'pregunta_') === 0) {
                $preguntaId = str_replace('pregunta_', '', $key);
                $opcionId = $value;

                // Obtener la opción seleccionada para la pregunta
                $opcion = Opcion::find($opcionId);

                // Sumar los puntos de la opción seleccionada al total
                $totalPuntos += $opcion->puntos;

                // Guardar la respuesta en la tabla PreguntaResultado
                $respuesta = new PreguntaResutado();
                $respuesta->id_pregunta = $preguntaId;
                $respuesta->id_opcion = $opcionId;
                $respuesta->id_resultado = $resultado->id; // Usar el ID del resultado creado
                $respuesta->puntos = $opcion->puntos;
                $respuesta->save();
            }
        }

        // Actualizar el resultado con los puntos finales
        $resultado->total_puntos = $totalPuntos;
        $resultado->estado = 1; // Aquí usas la función para calcular el estado
        $resultado->save();

        // Redirigir con el mensaje de éxito
        return redirect()->route('estudiante.contenidos.index')->with('success', 'Respuestas enviadas correctamente.');
    }
}
