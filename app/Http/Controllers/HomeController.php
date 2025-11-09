<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Curso;
use App\Models\DetalleAsistencia;
use App\Models\Estudiante;
use App\Models\Estudiante_tutor;
use App\Models\Paralelo;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Promedio;
use App\Models\Tutore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

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
            if (auth()->user()->hasRole('ADMIN')) {
                // Obtener la cantidad de estudiantes por curso
                $estudiantesPorCurso = Estudiante::join('cursos', 'cursos.id', '=', 'estudiantes.id_curso')
                    ->select('cursos.nombre_curso', DB::raw('count(estudiantes.id) as total'))
                    ->groupBy('cursos.nombre_curso')
                    ->get();

                // Verifica el resultado de la consulta
                // dd($estudiantesPorCurso);

                // Convertir los datos para Highcharts
                $data = [];
                foreach ($estudiantesPorCurso as $curso) {
                    $data[] = [
                        'name' => $curso->nombre_curso,
                        'y' => $curso->total
                    ];
                }
                ///////////////////////////////////////////////////////////////////////////////////////

                $estudiantesPorGenero = Estudiante::select('genero_es', DB::raw('count(*) as total'))
                    ->groupBy('genero_es')
                    ->get();

                // Convertir los datos para Highcharts
                $dataGenero = [];
                foreach ($estudiantesPorGenero as $genero) {
                    $dataGenero[] = [
                        'name' => ($genero->genero_es == '1') ? 'Masculino' : 'Femenino',
                        'y' => $genero->total
                    ];
                }
                //////////////////////////////////////////////////////////////////////////////
                // Obtener la cantidad de registros por estado por día
                $asistenciasPorDia = DB::table('detalle_asistencias')
                    ->select(
                        DB::raw('DATE(created_at) as fecha'),
                        DB::raw("SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) as presentes"),
                        DB::raw("SUM(CASE WHEN estado = 'ausente' THEN 1 ELSE 0 END) as ausentes"),
                        DB::raw("SUM(CASE WHEN estado = 'tarde' THEN 1 ELSE 0 END) as tarde"),
                        DB::raw("SUM(CASE WHEN estado = 'justificado' THEN 1 ELSE 0 END) as justificados")
                    )
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy('fecha')
                    ->get();


                // Formatear los datos para el gráfico
                $fechas = [];
                $presentes = [];
                $ausentes = [];
                $tarde = [];
                $justificados = [];

                foreach ($asistenciasPorDia as $asistencia) {
                    $fechas[] = $asistencia->fecha;
                    $presentes[] = $asistencia->presentes;
                    $ausentes[] = $asistencia->ausentes;
                    $tarde[] = $asistencia->tarde;
                    $justificados[] = $asistencia->justificados;
                }


                /////////////////////////////////////////////////////////////
                // Supongamos que tienes un total de días y los días asistidos registrados en la base de datos
                $totalDias = 180; // ejemplo de total de días
                // contar los días con asistencia

                $diasAsistidos = DetalleAsistencia::where('estado', 'presente')->count();

                // Validar que el total no sea cero para evitar división por cero
                $porcentajeAsistencia = $totalDias > 0 ? ($diasAsistidos / $totalDias) * 100 : 0;

                $estudiantes = Estudiante::count();
                $profesores = User::where('id_rol', 2)->count();
                $cursos = Curso::count();
                // Para depurar y ver los roles asignados al usuario
                // Consultar los roles en la base de datos


                return view('home', compact('cursos', 'fechas', 'presentes', 'ausentes', 'tarde', 'justificados', 'dataGenero', 'data', 'estudiantes', 'profesores', 'porcentajeAsistencia'));
            } elseif (auth()->user()->hasRole('PROFESOR')) {

                //------------------------------------------------cantidad estudiantes
                // Obtener la cantidad de estudiantes por curso
                $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
                    ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
                    ->where('profesores.id_user', Auth::user()->id)
                    //->whereColumn('profesor_asignaturas.id_profesor', 'profesores.id')
                    ->select('profesor_asignaturas.id_curso')
                    ->first();

                // Acceder al valor de id_paralelo
                $idParalelo = $paralelo ? $paralelo->id_curso : null;

                $estudiantes = Estudiante::where('estudiantes.id_curso', $idParalelo)
                    ->count();
                // Obtener la cantidad de estudiantes por curso


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
                //--------------------------------------asistencia mensual
                // Supongamos que tienes un modelo de Asistencia que te da la información
                // Obtener las fechas únicas de registro




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

                //////////////////////////////////////////////////////////////////////////////
                // Obtener la cantidad de registros por estado por día
                $asistenciasPorDia = DB::table('detalle_asistencias')
                    ->select(
                        DB::raw('DATE(fecha) as fecha'),
                        DB::raw("SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) as presentes"),
                        DB::raw("SUM(CASE WHEN estado = 'ausente' THEN 1 ELSE 0 END) as ausentes"),
                        DB::raw("SUM(CASE WHEN estado = 'tarde' THEN 1 ELSE 0 END) as tarde"),
                        DB::raw("SUM(CASE WHEN estado = 'justificado' THEN 1 ELSE 0 END) as justificados")
                    )
                    ->groupBy(DB::raw('DATE(fecha)'))
                    ->orderBy('fecha')
                    ->get();


                // Formatear los datos para el gráfico
                $fechas = [];
                $presentes = [];
                $ausentes = [];
                $tarde = [];
                $justificados = [];

                foreach ($asistenciasPorDia as $asistencia) {
                    $fechas[] = $asistencia->fecha;
                    $presentes[] = $asistencia->presentes;
                    $ausentes[] = $asistencia->ausentes;
                    $tarde[] = $asistencia->tarde;
                    $justificados[] = $asistencia->justificados;
                }


                /////////////////////////////////////////////////////////////
                // Supongamos que tienes un total de días y los días asistidos registrados en la base de datos
                $totalDias = 200; // ejemplo de total de días
                // contar los días con asistencia

                $diasAsistidos = DetalleAsistencia::where('estado', 'presente')->count();

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
                $presentess = DetalleAsistencia::where('estado', 'presente')
                    ->whereDate('fecha', $hoy)
                    ->count();

                $ausentess = DetalleAsistencia::where('estado', 'ausente')
                    ->whereDate('fecha', $hoy)
                    ->count();

                $justificadoss = DetalleAsistencia::where('estado', 'justificado')
                    ->whereDate('fecha', $hoy)
                    ->count();

                $pr = Profesore::where('id_user', Auth::user()->id)->first();
                $cargo = Profesor_asignatura::where('id_profesor', $pr->id)->count();


                return view('home', compact('cargo', 'hoy', 'presentess', 'ausentess', 'justificadoss', 'fechas', 'presentes', 'ausentes', 'tarde', 'justificados', 'dataGenero', 'data', 'estudiantes', 'profesores', 'porcentajeAsistencia'));
            } elseif (auth()->user()->hasRole('TUTOR')) {
                // Obtener el tutor autenticado
                $tutor = User::where('id', Auth::user()->id)->first();

        if ($tutor) {
            $estudiantes = $tutor->estudiantes;
            $estudiantesDatos = [];

            foreach ($estudiantes as $estudiante) {
                $promedios = Promedio::where('id_estudiante', $estudiante->id)->get();
                $asistenciaPresente = DetalleAsistencia::where('user_id', $estudiante->id)->where('estado', 'presente')->count();
                $asistenciaJustificada = DetalleAsistencia::where('user_id', $estudiante->id)->where('estado', 'justificado')->count();
                $asistenciaAusente = DetalleAsistencia::where('user_id', $estudiante->id)->where('estado', 'ausente')->count();
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

            return view('home', compact('tutor', 'estudiantes', 'estudiantesDatos'));
        }
            } elseif (auth()->user()->hasRole('SECRETARIA')) {
                //dd(auth()->user());
                return view('home');
            }
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
