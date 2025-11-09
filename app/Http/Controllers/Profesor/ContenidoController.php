<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\Asignatura;
use App\Models\Examene;
use App\Models\Profesore;
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
            // Obtener todos los trimestres con asignaturas y temas
            $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
            ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
            ->where('profesores.id_user', Auth::user()->id)
            ->select('profesor_asignaturas.id_curso')
            ->first();

        $idParalelo = $paralelo ? $paralelo->id_curso : null;



            $trimestres = Trimestre::get();
            $nivel = Temas::with('trimestre')->select('id_trimestre')->distinct()->orderBy('id_trimestre')->get();
            //$materias = Asignatura::get();

            $profe = Profesore::where('id_user', Auth::id())->first();
            // Filtra las asignaturas según las asignaciones del profesor
            $materias = Asignatura::join('profesor_asignaturas', 'asignaturas.id', '=', 'profesor_asignaturas.id_asignatura')
                ->where('profesor_asignaturas.id_profesor', $profe->id)
                ->select('asignaturas.nombre_asig', 'asignaturas.id','asignaturas.estado_asig')->get();

            $temas = Temas::with('curso')->with('trimestre')->with('asignatura')
                ->where('id_curso', '=', $idParalelo)->where('estado', '=', 1)->get();
            $actividades = Actividades::where('id_curso', '=', $idParalelo)->orderBy('id', 'asc')->paginate(10);

            $evaluaciones = Examene::with('curso')->with('tema')->where('estado_eval', '=', 1)->where('id_curso', '=', $idParalelo)->get();

            return view('profesor.contenidos.index', compact('evaluaciones','actividades','temas','materias', 'trimestres', 'nivel'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
