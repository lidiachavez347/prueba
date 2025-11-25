<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Curso;
use App\Models\DetalleAsistencia;
use App\Models\Estudiante;
use App\Models\Estudiante_tutor;
use App\Models\NotaDetalle;
use App\Models\Paralelo;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Promedio;
use App\Models\Trimestre;
use App\Models\Tutore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::user()->estado_user == 1) {
            if (auth()->user()->hasRole('DIRECTOR')) {

                Artisan::call('app:enviar-notas-whats-app');


                // CANTIDAD DE ESTUDIANTES POR CURSO
                $estudiantesPorCurso = Estudiante::join('cursos', 'cursos.id', '=', 'estudiantes.id_curso')
                    ->select('cursos.nombre_curso', DB::raw('count(estudiantes.id) as total'))
                    ->groupBy('cursos.nombre_curso')
                    ->get();

                // ESTUDIANTES POR CURSO
                $data = [];
                foreach ($estudiantesPorCurso as $curso) {
                    $data[] = [
                        'name' => $curso->nombre_curso,
                        'y' => $curso->total
                    ];
                }


                //CANTIDAD DE ESTUDIANTES POR GENERO
                $estudiantesPorGenero = Estudiante::select('genero_es', DB::raw('count(*) as total'))
                    ->groupBy('genero_es')
                    ->get();

                // ESTUDIANTES POR GENERO
                $dataGenero = [];
                foreach ($estudiantesPorGenero as $genero) {
                    $dataGenero[] = [
                        'name' => ($genero->genero_es == '1') ? 'Masculino' : 'Femenino',
                        'y' => $genero->total
                    ];
                }

                $fechas = [];
                $presentes = [];
                $ausentes = [];
                //$tarde = [];
                $justificados = [];

                // OBTENER ASISTENCIAS AGRUPADAS POR DIA
                $asistenciasPorDia = DB::table('detalle_asistencias')
                    ->select(
                        DB::raw('DATE(created_at) as fecha'),
                        DB::raw("SUM(CASE WHEN estado = 'P' THEN 1 ELSE 0 END) as presentes"),
                        DB::raw("SUM(CASE WHEN estado = 'A' THEN 1 ELSE 0 END) as ausentes"),
                        //DB::raw("SUM(CASE WHEN estado = 'tarde' THEN 1 ELSE 0 END) as tarde"),
                        DB::raw("SUM(CASE WHEN estado = 'J' THEN 1 ELSE 0 END) as justificados")
                    )
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('fecha')
                    ->get();


                foreach ($asistenciasPorDia as $asistencia) {

                    $fechas[] = $asistencia->fecha;
                    $presentes[] = (int) $asistencia->presentes;
                    $ausentes[] = (int) $asistencia->ausentes;
                    //$tarde[] = (int) $asistencia->tarde;
                    $justificados[] = (int) $asistencia->justificados;
                }
                //dd($presentes);


                $totalDias = 180; // ejemplo de total de días

                $diasAsistidos = DetalleAsistencia::where('estado', 'P')->count();
                // Validar que el total no sea cero para evitar división por cero
                $porcentajeAsistencia = $totalDias > 0 ? ($diasAsistidos / $totalDias) * 100 : 0;

                //CONTAR CANTIDAD DE ESTUDIANTES
                $estudiantes = Estudiante::count();
                //CONTAR CANTIDAD DE PROFESORES
                $profesores = User::where('id_rol', 2)->count();
                //CONTAR CANTIDAD DE CURSOS ACTIVOS
                $cursos = Curso::count();
                //dd($asistenciasPorDia);

                //AVANCE ACADEMICO
                $items =  Profesor_asignatura::with(['profesor', 'curso.temas'])
                    ->get()
                    ->groupBy(function ($item) {
                        return $item->id_profesor . '-' . $item->id_curso;
                    });

                $datos = [];

                foreach ($items as $item) {
                    $nolose = $item->first();

                    $cursovariable = $nolose->curso;
                    $profesorvariable = $nolose->profesor;
                    //temas por curso
                    $totalTemas = $cursovariable->temas->count();
                    $temasCompletados = $cursovariable->temas->where('avance', 1)->count();

                    $porcentaje = $totalTemas > 0
                        ? round(($temasCompletados / $totalTemas) * 100)
                        : 0;

                    $datos[] = [
                        'profesor'   => $profesorvariable->nombres . ' ' . $profesorvariable->apellidos,
                        'curso'      => $cursovariable->nombre_curso . ' ' . $cursovariable->paralelo,
                        'porcentaje' => $porcentaje,
                        'id_curso'   => $cursovariable->id,
                        'id_profesor' => $profesorvariable->id,
                    ];
                }
                //dd($fechas, $presentes, $ausentes, $tarde, $justificados);

                return view('home', compact(
                    'cursos',
                    'fechas',
                    'presentes',
                    'ausentes',

                    'justificados',
                    'dataGenero',
                    'data',
                    'estudiantes',
                    'profesores',
                    'porcentajeAsistencia',
                    'datos',

                ));
            } elseif (auth()->user()->hasRole('PROFESOR')) {

                //------------------------------------------------cantidad estudiantes
                // Obtener la cantidad de estudiantes por curso
                $paralelo = User::join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'users.id')
                    ->where('users.id', Auth::user()->id)
                    //->whereColumn('profesor_asignaturas.id_profesor', 'profesores.id')
                    ->select('profesor_asignaturas.id_curso')
                    ->first();

                // Acceder al valor de id_paralelo
                $idParalelo = $paralelo ? $paralelo->id_curso : null;

                $estudiantes = Estudiante::where('estudiantes.id_curso', $idParalelo)
                    ->count();



                //----------------------------------estudiantes por genero

                $estudiantesPorGenero = Estudiante::where('estudiantes.id_curso', $idParalelo)
                    ->select('estudiantes.genero_es', DB::raw('count(*) as total'))
                    ->groupBy('estudiantes.genero_es')
                    ->get();

                // Convertir los datos para Highcharts
                $dataGenero = [];
                foreach ($estudiantesPorGenero as $genero) {
                    $dataGenero[] = [
                        'name' => ($genero->genero_es == '1') ? 'Masculino' : 'Femenino',
                        'y' => $genero->total
                    ];
                }

                //--------------------este nooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                $estudiantesPorCurso = Estudiante::join('cursos', 'cursos.id', '=', 'estudiantes.id_curso')
                    ->select('cursos.nombre_curso', DB::raw('count(estudiantes.id) as total'))
                    ->groupBy('cursos.nombre_curso')
                    ->get();

                // Convertir los datos para Highcharts
                $data = [];
                foreach ($estudiantesPorCurso as $curso) {
                    $data[] = [
                        'name' => $curso->nombre_curso,
                        'y' => $curso->total
                    ];
                }

                $fechas = [];
                $presentes = [];
                $ausentes = [];
                //$tarde = [];
                $justificados = [];


                // Obtener la cantidad de registros por estado por mes
                $asistenciasPorDia = DB::table('detalle_asistencias')
                    ->select(
                        DB::raw('DATE(created_at) as fecha'),
                        DB::raw("SUM(CASE WHEN estado = 'P' THEN 1 ELSE 0 END) as presentes"),
                        DB::raw("SUM(CASE WHEN estado = 'A' THEN 1 ELSE 0 END) as ausentes"),
                        //DB::raw("SUM(CASE WHEN estado = 'tarde' THEN 1 ELSE 0 END) as tarde"),
                        DB::raw("SUM(CASE WHEN estado = 'J' THEN 1 ELSE 0 END) as justificados")
                    )
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('fecha')
                    ->get();


                // Formatear los datos para el gráfico


                foreach ($asistenciasPorDia as $asistencia) {
                    $fechas[] = $asistencia->fecha; // ← importante: viene como "2025-01"
                    $presentes[] = (int) $asistencia->presentes;
                    $ausentes[] = (int) $asistencia->ausentes;
                    //$tarde[] = (int) $asistencia->tarde;
                    $justificados[] = (int) $asistencia->justificados;
                }


                /////////////////////////////////////////////////////////////
                // Supongamos que tienes un total de días y los días asistidos registrados en la base de datos
                $totalDias = 200; // ejemplo de total de días
                // contar los días con asistencia

                $diasAsistidos = DetalleAsistencia::where('estado', 'P')->count();

                // Validar que el total no sea cero para evitar división por cero
                $porcentajeAsistencia = $totalDias > 0 ? ($diasAsistidos / $totalDias) * 100 : 0;

                //contar porfesores y cursos
                $profesores = User::where('id_rol', 4)->count();
                //$cursos = Paralelo::count();
                // Para depurar y ver los roles asignados al usuario
                // Consultar los roles en la base de datos

                // Fecha de hoy
                $hoy = Carbon::today();

                // Contar las asistencias presentes, ausentes y justificadas
                $presentess = DetalleAsistencia::where('estado', 'P')
                    ->whereDate('fecha', $hoy)
                    ->count();

                $ausentess = DetalleAsistencia::where('estado', 'A')
                    ->whereDate('fecha', $hoy)
                    ->count();

                $justificadoss = DetalleAsistencia::where('estado', 'J')
                    ->whereDate('fecha', $hoy)
                    ->count();

                $pr = User::where('id', Auth::user()->id)->first();

                $cargo = Profesor_asignatura::where('id_profesor', $pr->id)->count();


                return view('home', compact(
                    'cargo',
                    'hoy',
                    'presentess',
                    'ausentess',
                    'justificadoss',
                    'fechas',
                    'presentes',
                    'ausentes',
                    'justificados',
                    'dataGenero',
                    'data',
                    'estudiantes',
                    'profesores',
                    'porcentajeAsistencia'
                ));
            } elseif (auth()->user()->hasRole('TUTOR')) {
                // Obtener el tutor autenticado
                $tutor = User::where('id', Auth::user()->id)->first();

                if ($tutor) {
                    $estudiantes = $tutor->estudiantes;
                    $estudiantesDatos = [];

                    foreach ($estudiantes as $estudiante) {
                        $promedios = NotaDetalle::where('id_estudiante', $estudiante->id)->get();
                        $asistenciaPresente = DetalleAsistencia::where('user_id', $estudiante->id)->where('estado', 'P')->count();
                        $asistenciaJustificada = DetalleAsistencia::where('user_id', $estudiante->id)->where('estado', 'J')->count();
                        $asistenciaAusente = DetalleAsistencia::where('user_id', $estudiante->id)->where('estado', 'A')->count();
                        $asistenciaTotal = $asistenciaPresente + $asistenciaJustificada + $asistenciaAusente;

                        $estudiantesDatos[] = [
                            'estudiante' => $estudiante,
                            'nom' => $estudiante->nombres_es,
                            'promedios' => $promedios,
                            'asistencia' => [
                                'presente' => $asistenciaPresente,
                                'justificado' => $asistenciaJustificada,
                                'ausente' => $asistenciaAusente,
                                'total' => $asistenciaTotal,
                            ],
                            'nombres_es' => $estudiante->nombres_es,
                            'apellidos_es' => $estudiante->apellidos_es,
                        ];
                    }
                    $estudiantes = $tutor->estudiantes;
                    $hijos = $estudiantes->count();
                    $ultimoLogin = $tutor->last_login_at;


// Obtener todos los trimestres en orden
    $trimestres = Trimestre::orderBy('id')->pluck('periodo')->toArray();

    $series = []; // cada hijo tendrá una línea en la gráfica

    foreach ($estudiantes as $estudiante) {

        // obtener promedios por trimestre del estudiante
        $promedios = NotaDetalle::where('id_estudiante', $estudiante->id)
            ->select('id_trimestre', DB::raw('AVG(promedio_materia) as promedio'))
            ->groupBy('id_trimestre')
            ->get();

        $rend = [];

        foreach ($trimestres as $tri) {
            // buscar el trimestre dentro de los promedios
            $prom = $promedios->first(function ($item) use ($tri) {
                return $item->trimestre->periodo === $tri;
            });

            $rend[] = $prom ? round($prom->promedio, 2) : null;
        }

        $series[] = [
            'label' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
            'data' => $rend
        ];
    }



                    return view('home', compact('tutor', 'estudiantes', 'estudiantesDatos', 'hijos', 'ultimoLogin','trimestres',
                'series'));
                }
            } elseif (auth()->user()->hasRole('SECRETARIA')) {
                //CONTAR CANTIDAD DE ESTUDIANTES
                $estudiantes = Estudiante::count();
                //CONTAR CANTIDAD DE PROFESORES
                $tutores = User::where('id_rol', 3)->count();
                //----------------------------------estudiantes por genero

                $estudiantesPorGenero = Estudiante::select('estudiantes.genero_es', DB::raw('count(*) as total'))
                    ->groupBy('estudiantes.genero_es')
                    ->get();

                // Convertir los datos para Highcharts
                $dataGenero = [];
                foreach ($estudiantesPorGenero as $genero) {
                    $dataGenero[] = [
                        'name' => ($genero->genero_es == '1') ? 'Masculino' : 'Femenino',
                        'y' => $genero->total
                    ];
                }
                //----------------------------------tutores por genero


                $tutoresporgenero = User::where('users.id_rol', 3)
                    ->select('users.genero', DB::raw('count(*) as total'))
                    ->groupBy('users.genero')
                    ->get();

                // Convertir los datos para Highcharts
                $tutorgenero = [];
                foreach ($tutoresporgenero as $generotu) {
                    $tutorgenero[] = [
                        'name' => ($generotu->genero == '1') ? 'Masculino' : 'Femenino',
                        'y' => $generotu->total
                    ];
                }

                //-----------ESTUDIANTES POR CURSO
                // CANTIDAD DE ESTUDIANTES POR CURSO
                $estudiantesPorCurso = Estudiante::join('cursos', 'cursos.id', '=', 'estudiantes.id_curso')
                    ->select('cursos.nombre_curso', DB::raw('count(estudiantes.id) as total'))
                    ->groupBy('cursos.nombre_curso')
                    ->get();

                // ESTUDIANTES POR CURSO
                $data = [];
                foreach ($estudiantesPorCurso as $curso) {
                    $data[] = [
                        'name' => $curso->nombre_curso,
                        'y' => $curso->total
                    ];
                }
                //dd($tutorgenero);
                return view('home', compact('tutores', 'estudiantes', 'dataGenero', 'tutorgenero', 'data'));
            }
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
