<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\ActividadDetalle;
use App\Models\Actividades;
use App\Models\Asignatura;
use App\Models\Estudiante;
use App\Models\Examene;
use App\Models\Temas;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ContenidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if (Auth::check()) {

            $trimestres = Trimestre::get();
            $nivel = Temas::with('trimestre')->select('id_trimestre')->distinct()->orderBy('id_trimestre')->get();
            //$materias = Asignatura::get();

            $estudiante = Estudiante::where('id_estudiante', Auth::id())->first();
            //dd($estudiante);
            // Filtra las asignaturas según las asignaciones del profesor
            $materias = Asignatura::join('profesor_asignaturas', 'asignaturas.id', '=', 'profesor_asignaturas.id_asignatura')
                ->where('profesor_asignaturas.id_curso', $estudiante->id_curso)
                ->select('asignaturas.nombre_asig', 'asignaturas.id','asignaturas.estado_asig')->get();

            $temas = Temas::with('curso')->with('trimestre')->with('asignatura')
                ->where('id_curso', '=', $estudiante->id_curso)->where('estado', '=', 1)->get();
            $actividades = Actividades::where('id_curso', '=', $estudiante->id_curso)->orderBy('id', 'asc')->paginate(10);

            $evaluaciones = Examene::with('curso')->with('tema')->where('estado_eval', '=', 1)->where('id_curso', '=',  $estudiante->id_curso)->get();

            return view('estudiante.contenidos.index', compact('evaluaciones','actividades','temas','materias', 'trimestres', 'nivel'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function show(Request $request, $id)
    {
        // Buscar la actividad por su ID
        $actividad = Actividades::findOrFail($id);
        $estudiante = Estudiante::where('id_estudiante', Auth::id())->first();
        // Obtener los detalles de la actividad del estudiante autenticado
        $detalle = ActividadDetalle::where('id_actividad', $id)
                    ->where('id_estudiante',  $estudiante->id)
                    ->first();
    //dd($detalle);
        // Si no existe un detalle para esta actividad, inicializar uno vacío
        if (!$detalle) {
            $detalle = new ActividadDetalle([
                'id_actividad' => $id,
                'id_estudiante' =>  $estudiante->id,
                'archivo' => null,
                'imagen' => null,
                'estado' => 'pendiente', // Estado inicial
                'observacion' => null,
                'nota' => null,
            ]);
        }
    
        // Retornar la vista con la actividad y sus detalles
        return view('estudiante.contenidos.show', compact('actividad', 'detalle'));
    }
    
}
