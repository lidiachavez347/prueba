<?php

namespace App\Http\Controllers\Profesor;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\Alerta;
use App\Models\Asignatura;
use App\Models\Estudiante;
use App\Models\Nota_actividades;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Promedio;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultadoTareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if (Auth::check()) {


            $prof = Profesore::where('id_user', Auth::id())->first();

            $asignado = Profesor_asignatura::where('id_profesor',  $prof->id)->first();

            $asignaturas = Asignatura::join('actividades', 'asignaturas.id', '=', 'actividades.id_asignatura')
                ->join('profesor_asignaturas', 'asignaturas.id', '=', 'profesor_asignaturas.id_asignatura')
                ->where('profesor_asignaturas.id_profesor', $prof->id) // Filtra por profesor
                ->where('actividades.id_curso', $asignado->id_curso) // Filtra por curso del profesor
                ->whereNotNull('actividades.id') // Verifica que la asignatura tenga tareas asociadas
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
            $actividades = Actividades::where('id_trimestre', $idTrimestre)->where('id_curso', $idParalelo)
            ->where('id_asignatura', $asignaturaId)->get();



            $estudiantes = Estudiante::where('id_curso', $idParalelo)->with([
                'notasActividadDetalle' => function ($query) use ($asignaturaId, $idTrimestre) {
                    $query->whereHas('actividad', function ($query) use ($asignaturaId, $idTrimestre) {
                        $query->where('id_asignatura', $asignaturaId)
                            ->where('id_trimestre', $idTrimestre);
                    });
                },
                'notasActividades' => function ($query) use ($asignaturaId, $idTrimestre) {
                    $query->whereHas('actividad', function ($query) use ($asignaturaId, $idTrimestre) {
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
                    $notaDetalle = $estudiante->notasActividadDetalle->where('id_actividad', $actividad->id)->first();

                    // Buscar notas en NotaActividades
                    $notaActividad = $estudiante->notasActividades->where('id_actividad', $actividad->id)->first();

                    // Determinar la nota final para esta actividad
                    $nota = $notaDetalle?->nota ?? $notaActividad?->nota;

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
                                'id_asignatura' => $actividad->id_asignatura,
                                'id_estudiante' => $estudiante->id,
                                'tipo' => 'tarea', // Condiciones para buscar la alerta existente
                                'id_curso' => $id_curso,

                            ],
                            [
                                'promedio' => number_format($promedio, 2),
                                'fecha_alerta' => now(), // Actualizar la fecha y el promedio
                            ]
                        );
                        // Actualizar o crear el promedio
                        // Tareas
                        // Exámenes
                        $protareas =  number_format($promedio, 2);
                        // Asegúrate de que este valor sea correcto para los exámenes
                        $proevalu = 0; // Calcula correctamente el valor de los exámenes

                        // Verificar si el registro de exámenes ya existe
                        $promedioTarea = Promedio::where([
                            'nombre_estudiante' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
                            'asignatura' => $mat,
                            'id_asignatura' => $actividad->id_asignatura ?? null,
                            'id_estudiante' => $estudiante->id,
                            'id_trimestre' => $actividad->id_trimestre,
                        ])->first();

                        if (!$promedioTarea) {
                            // Si no existe, crear un nuevo registro para los exámenes
                            //dd($actividad->id_trimestre);
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
                            if ($protareas > 0) {
                                // Recalcular el promedio
                                $nuevoPromedio = ($protareas + $promedioTarea->proevalu);

                                $promedioTarea->update([
                                    'protareas' => $protareas ?: $promedioTarea->protareas,  // Mantener el valor si es 0
                                    'proevalu' => $proevalu ?: $promedioTarea->proevalu, // Mantener el valor si es 0
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
            return view('profesor.resultadotarea.index', compact('trimestres', 'asignaturas', 'actividades', 
            'estudiantes', 'fechas'));

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
            $alertas = Alerta::where('id_curso', $asignado->id_curso)->get(); // O puedes agregar filtros según lo necesites
            //return view('admin.alertas.index', compact('alertas'));

            return view('profesor.alertas.index', compact('alertas'));
        } else {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
