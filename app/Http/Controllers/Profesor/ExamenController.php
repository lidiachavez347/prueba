<?php

namespace App\Http\Controllers\Profesor;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Criterio;
use App\Models\Estudiante;
use App\Models\Examene;
use App\Models\Pregunta;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Temas;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /*'nombre',
    'estado_eval',
    'evaluar',
    'fecha',
    'tipo',
    'id_tema',
    'id_curso',
    'id_trimestre',
    'id_criterio',
    'id_asignatura'*/
    public function index(Request $request)
    {
        if (Auth::check()) {
            $prof = Profesore::where('id_user', Auth::id())->first();
            $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();

            $examenes = Examene::where('id_curso', $asig->id_curso)->get();
            return view('profesor.evaluaciones.index', compact('examenes'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }

    public function create()
    {
        //
        if (Auth::user()) {

            //$profesorId = ; // Obtén el ID del profesor autenticado
            $profe = Profesore::where('id_user', Auth::id())->first();
            // Filtra las asignaturas según las asignaciones del profesor
            $asignatura = Asignatura::join('profesor_asignaturas', 'asignaturas.id', '=', 'profesor_asignaturas.id_asignatura')
                ->where('profesor_asignaturas.id_profesor', $profe->id)
                ->pluck('asignaturas.nombre_asig', 'asignaturas.id')
                ->prepend('SELECCIONE ASIGNATURA', '');

            $trimestre = Trimestre::where('estado', 1)->pluck('periodo', 'id');
            $criterio = Criterio::pluck('nombres', 'id');

            return view('profesor.evaluaciones.create', compact('asignatura', 'trimestre', 'criterio'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function obtenerTemasPorAsignatura($asignatura_id)
    {
        $prof = Profesore::where('id_user', Auth::id())->first();
        $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();

        $temas = Temas::where('id_asignatura', $asignatura_id)->where('id_curso', $asig->id_curso)->pluck('titulo', 'id');
        return response()->json($temas);
    }
    public function store(Request $request)
    {

        if (Auth::user()) {
            $request->validate(
                [
                    'nombre' => 'required',
                    'fecha' => 'required',
                    'estado_eval' => 'required',
                    'evaluar' => 'required',
                    'id_asignatura' => 'required',
                    'id_trimestre' => 'required',
                    'id_criterio' => 'required',
                    'id_tema' => 'required',
                    'tipo' => 'required',

                ]
            );
            // Obtener los estudiantes y sus asistencias
            $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
                ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
                ->where('profesores.id_user', Auth::user()->id)
                ->select('profesor_asignaturas.id_curso')
                ->first();

            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            $tarea = new Examene();
            $tarea->nombre = $request->nombre;
            $tarea->fecha = $request->fecha;
            $tarea->estado_eval = $request->estado_eval;
            $tarea->evaluar = $request->evaluar;
            $tarea->id_asignatura = $request->id_asignatura;
            $tarea->id_trimestre = $request->id_trimestre;
            $tarea->id_criterio = $request->id_criterio;
            $tarea->id_tema = $request->id_tema;
            $tarea->id_curso = $idParalelo;
            $tarea->tipo = $request->tipo;

            $tarea->save();
            /* if ($request->tipo == 'AUTOMATICO') {

                $prof = Profesore::where('id_user', Auth::id())->first();
                $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();
                $estudiantes = Estudiante::where('id_curso', $asig->id_curso)->get();
                foreach ($estudiantes as $estudiante) {
                    ActividadDetalle::create([
                        'id_actividad' => $tarea->id, // ID de la actividad
                        'id_estudiante' => $estudiante->id, // ID del estudiante
                        'nota' => 0, // Nota inicial
                        'estado' => 'pendiente', // Estado inicial
                    ]);
                }
            }*/


            return redirect()->route('profesor.evaluaciones.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function show($id)
    {
        // Obtener la actividad por ID
        $examen = Examene::findOrFail($id);
        $prof = Profesore::where('id_user', Auth::id())->first();
        $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();
        $preguntas = Pregunta::where('id_exam',$id)->get();


        /*$notas = ActividadDetalle::where('id_actividad', $id)
            ->join('estudiantes', 'actividad_detalles.id_estudiante', '=', 'estudiantes.id')
            ->select('estudiantes.id as estudiante_id', 'estudiantes.nombres_es', 'estudiantes.apellidos_es', 'actividad_detalles.nota','actividad_detalles.observacion','actividad_detalles.archivo','actividad_detalles.imagen')
            ->get();
*/
        return view('profesor.evaluaciones.show', compact('examen','preguntas'));
    }
}
