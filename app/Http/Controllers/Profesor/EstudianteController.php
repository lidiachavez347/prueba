<?php

namespace App\Http\Controllers\Profesor;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Profesore;
use App\Models\Tutore;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EstudianteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::check()) {
            // Obtener al profesor logueado
            $profesor = Profesore::where('id_user', Auth::id())->first();

            if (!$profesor) {
                // Si no se encuentra al profesor, redirigir con un mensaje de error
                return redirect()->back()->with('error', 'No se encontró al profesor logueado.');
            }

            // Obtener los estudiantes asociados a los paralelos del profesor

            /* $estudiantes = Estudiante::join('estudiante_paralelo', 'estudiantes.id', '=', 'estudiante_paralelo.id_estudiante')
                ->join('profesores', 'profesores.id_user', '=', Auth::id())
                ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id_user')
                ->where('estudiante_paralelo.id_paralelo', 'profesor_asignaturas.id_paralelo')
                ->select('estudiantes.nombres_es', 'estudiantes.id', 'estudiantes.apellidos_es', 'estudiantes.fecha_nac_es', 'estudiantes.genero_es', 'estudiantes.estado_es', 'estudiantes.rude_es')
                ->get();*/
            //dd(Auth::id());


            $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
                ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
                ->where('profesores.id_user', Auth::user()->id)
                ->whereColumn('profesor_asignaturas.id_profesor', 'profesores.id')
                ->select('profesor_asignaturas.id_curso')
                ->first();

            // Acceder al valor de id_paralelo
            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            // Verificar el valor
            //dd($idParalelo);

            $estudiantes = Estudiante::where('estudiantes.id_curso', $idParalelo)
                ->select(
                    'estudiantes.nombres_es',
                    'estudiantes.id',
                    'estudiantes.apellidos_es',
                    'estudiantes.fecha_nac_es',
                    'estudiantes.genero_es',
                    'estudiantes.estado_es',
                    'estudiantes.rude_es'
                )
                ->get();


            // Retornar vista con los estudiantes
            return view('profesor.estudiantes.index', compact('estudiantes'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function show(String $id)
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados
            $estudiante = Estudiante::with('tutors')->findOrFail($id);

            // Obtener información detallada de los tutores relacionados al estudiante
            $tutores = Tutore::join('users', 'tutores.id_user', '=', 'users.id')
                ->join('estudiante_tutors', 'estudiante_tutors.id_tutor', '=', 'tutores.id')
                ->where('estudiante_tutors.id_estudiante', $id)
                ->select(
                    'users.nombres',
                    'users.apellidos',
                    'users.imagen',
                    'users.direccion',
                    'tutores.relacion',
                    'tutores.estado_tutor',
                    'tutores.ocupacion',
                    'users.telefono',
                    'users.estado_user'
                )
                ->get();

            // Retornar la vista con los datos del estudiante y sus tutores
            return view('profesor.estudiantes.show', compact('estudiante', 'tutores'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }

    public function descargarEstudiantesPdf()
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados

            $estudiante = Estudiante::
                join('estudiante_tutors', 'estudiantes.id', '=', 'estudiante_tutors.id_estudiante') // Corregido: campo `id_estudiante`
                ->join('tutores', 'estudiante_tutors.id_tutor', '=', 'tutores.id')
                ->join('users', 'tutores.id_user', '=', 'users.id')
                ->where('estudiantes.id_curso', 1)
                ->select(
                    'estudiantes.nombres_es',
                    'estudiantes.id',
                    'estudiantes.apellidos_es',
                    'estudiantes.fecha_nac_es',
                    'estudiantes.genero_es',
                    'estudiantes.estado_es',
                    'estudiantes.rude_es',
                    'users.nombres',
                    'users.apellidos',
                    'users.telefono',
                    'users.direccion' // Alias para evitar confusión
                )
                ->get();

            // Renderizar la vista de PDF con los datos
            $pdf = Pdf::loadView('pdf.estudiantes', compact('estudiante'));

            return $pdf->stream('estudiantes.pdf');

            // Descargar el PDF
            //return $pdf->download('estudiantes.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
