<?php

namespace App\Http\Controllers\Profesor;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Alerta;
use App\Models\Asignatura;
use App\Models\Estudiante;
use App\Models\Examene;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Promedio;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;

class ResultadoEvalController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {


            $prof = Profesore::where('id_user', Auth::id())->first();

            $asignado = Profesor_asignatura::where('id_profesor',  $prof->id)->first();

            $asignaturas = Asignatura::join('examenes', 'asignaturas.id', '=', 'examenes.id_asignatura')
                ->join('profesor_asignaturas', 'asignaturas.id', '=', 'profesor_asignaturas.id_asignatura')
                ->where('profesor_asignaturas.id_profesor', $prof->id) // Filtra por profesor
                ->where('examenes.id_curso', $asignado->id_curso) // Filtra por curso del profesor
                ->whereNotNull('examenes.id') // Verifica que la asignatura tenga tareas asociadas
                ->select('asignaturas.nombre_asig', 'asignaturas.id')
                ->distinct() // Asegura que no haya asignaturas duplicadas
                ->get();
            //dd($asignaturas);



            $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
                ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
                ->where('profesores.id_user', Auth::user()->id)
                ->select('profesor_asignaturas.id_curso')
                ->first();

            // Acceder al valor de id_paralelo
            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            $asignaturaId = $request->input('id_asignatura');
            $idTrimestre = $request->input('id_trimestre');
            $trimestres = Trimestre::where('estado', 1)->get();

            // Obtener las actividades filtradas por asignatura
            $actividades = Examene::where('id_trimestre', $idTrimestre)->where('id_curso', $idParalelo)->where('id_asignatura', $asignaturaId)->get();

            $estudiantes = Estudiante::where('id_curso', $idParalelo)->with([
                'notasResultadoDetalle' => function ($query) use ($asignaturaId, $idTrimestre) {
                    $query->whereHas('examen', function ($query) use ($asignaturaId, $idTrimestre) {
                        $query->where('id_asignatura', $asignaturaId)
                            ->where('id_trimestre', $idTrimestre);
                    });
                },
                'notasExamenes' => function ($query) use ($asignaturaId, $idTrimestre) {
                    $query->whereHas('examen', function ($query) use ($asignaturaId, $idTrimestre) {
                        $query->where('id_asignatura', $asignaturaId)
                            ->where('id_trimestre', $idTrimestre);
                    });
                },
            ])->get();


            // Obtener las fechas de las actividades
            $fechas = $actividades->pluck('fecha')->unique();




            ///-------------------------------------AELRTA
            // Generar alertas
            // Generar alertas
            $alertas = collect(); // Colección para almacenar alertas, agrupadas por materia

            foreach ($estudiantes as $estudiante) {
                $sumaNotas = 0;
                $cantidadNotas = 0;
                $mat = '';
                $id_curso = 0;

                foreach ($actividades as $actividad) {
                    // Buscar notas en ActividadDetalle
                    $notaDetalle = $estudiante->notasResultadoDetalle->where('id_examen', $actividad->id)->first();

                    // Buscar notas en NotaActividades
                    $notaActividad = $estudiante->notasExamenes->where('id_examen', $actividad->id)->first();

                    // Determinar la nota final para esta actividad
                    $nota = $notaDetalle?->total_puntos ?? $notaActividad?->nota;

                    // Si hay una nota, acumular para el promedio
                    if ($nota !== null) {
                        if ($mat !== null) {
                            $sumaNotas += $nota;
                            $cantidadNotas++;
                            $mat = $actividad->asignatura->nombre_asig;
                            $id_curso = $actividad->id_curso;
                        }
                    }
                }

                // Calcular el promedio del estudiante
                $promedio = $cantidadNotas > 0 ? $sumaNotas / $cantidadNotas : 0;


                // Agregar alerta si el promedio es menor a 15
                if ($promedio < 40) {
                    // si la materia es distinto de vacio entonces crear o actualizar
                    if ($mat > 0) {
                        //dd($id_curso);

                        Alerta::updateOrCreate(
                            [
                                'nombre_estudiante' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
                                'asignatura' => $mat,
                                'tipo' => 'evaluaciones', // Condiciones para buscar la alerta existente
                                'id_curso' => $id_curso,
                                'id_asignatura' => $actividad->id_asignatura,
                                'id_estudiante' => $estudiante->id,
                            ],
                            [
                                'promedio' => number_format($promedio, 2),
                                'fecha_alerta' => now(), // Actualizar la fecha y el promedio
                            ]
                        );

                        // Exámenes
                        $protareas = 0; // Asegúrate de que este valor sea correcto para los exámenes
                        $proevalu = number_format($promedio, 2); // Calcula correctamente el valor de los exámenes

                        // Verificar si el registro de exámenes ya existe
                        $promedioExamenes = Promedio::where([
                            'nombre_estudiante' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
                            'asignatura' => $mat,
                            'id_asignatura' => $actividad->id_asignatura ?? null,
                            'id_estudiante' => $estudiante->id,
                            'id_trimestre' => $actividad->id_trimestre,
                        ])->first();

                        if (!$promedioExamenes) {
                            // Si no existe, crear un nuevo registro para los exámenes
                            Promedio::create([
                                'nombre_estudiante' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
                                'asignatura' => $mat,
                                'id_asignatura' => $actividad->id_asignatura ?? null,
                                'id_estudiante' => $estudiante->id,
                                'id_trimestre' => $actividad->id_trimestre,
                                'protareas' => $protareas,
                                'proevalu' => $proevalu,
                                'promedio' => $protareas + $proevalu, // Sumar correctamente
                            ]);
                        } else {
                            // Si existe, solo actualizar si el valor de proevalu es mayor a cero
                            if ($proevalu > 0) {
                                // Recalcular el promedio
                                $nuevoPromedio = ($promedioExamenes->protareas + $proevalu);

                                $promedioExamenes->update([
                                    'protareas' => $protareas ?: $promedioExamenes->protareas,  // Mantener el valor si es 0
                                    'proevalu' => $proevalu ?: $promedioExamenes->proevalu, // Mantener el valor si es 0
                                    'promedio' => $nuevoPromedio, // Recalcular el promedio
                                ]);
                            }
                        }

                        // También guardar en la colección para mostrar en la vista si es necesario
                        $alertas->push([
                            'nombre' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
                            'promedio' => number_format($promedio, 2),
                            'asignatura' => $mat,
                            'id_curso' => $id_curso,
                        ]);
                    }
                }
            }

            //----------------------------------------ATLERTA
            return view('profesor.resultadoevaluaciones.index', compact('trimestres', 'asignaturas', 'actividades', 'estudiantes', 'fechas'));

            // return view('profesor.resultadotarea.index', compact('estudiantes','actividades','materias', 'materiaSeleccionada', 'notas'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }

    public function mostrarAlertas(Request $request)
    {
        if (Auth::check()) {
            $prof = Profesore::where('id_user', Auth::id())->first();

            $asignado = Profesor_asignatura::where('id_profesor',  $prof->id)->first();
            $alertas = Alerta::where('promedio', '<', 16)
                ->where('id_curso', $asignado->id_curso)
                ->where('estado', 'activo') // Ejemplo de un filtro adicional
                ->get();

            return view('profesor.alertas.index', compact('alertas'));
        } else {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
