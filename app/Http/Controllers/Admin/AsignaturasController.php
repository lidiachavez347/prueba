<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Asistencia;
use App\Models\Curso;
use App\Models\Evento;
use App\Models\Nivel;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignaturasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()) {
            //mostrar a losprofesores con sus asignaciones asignatura y curso
//$events = [];
            $profesores = User::whereHas('asignaciones')
            ->with(['asignaciones.curso', 'asignaciones.asignatura'])
            ->get();
            
            return view('admin.asignaturas.index', compact('profesores'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $profesor = Profesore::with(['user', 'asignaciones.curso', 'asignaciones.asignatura'])
            ->where('id_user', $id)
            ->firstOrFail();

        return view('admin.asignaturas.show', compact('profesor'));
    }

    public function create()
    {
        if (Auth::user()) {
            $profesores = User::where('users.id_rol', 2)->get();
            $cursos = Curso::all();

            $asignaturas = Asignatura::all();
            //return view('admin.profesores.asignar'
            return view('admin.asignaturas.create', compact('niveles', 'profesores', 'cursos', 'asignaturas'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }



    public function store(Request $request)
    { //guardar BD los Registro

        if (Auth::user()) {

            // Validar los datos del formulario
            $validated = $request->validate([

                'id_curso' => 'required|exists:cursos,id',
                'id_nivel' => 'required|exists:niveles,id',
                'asignaturas' => 'required|array',
                'asignaturas.*' => 'exists:asignaturas,id',
            ]);
            //dd($request->id_profesor);
            $profesorId = Profesore::where('id_user', $request->id_profesor)
                ->first();
            //dd($profesorId->id);
            $cursoId = $validated['id_curso'];
            $nivelId = $validated['id_nivel'];
            $asignaturasSeleccionadas = $validated['asignaturas'];

            // Insertar registros en la tabla profesor_asignaturas
            foreach ($asignaturasSeleccionadas as $asignaturaId) {
                Profesor_asignatura::create([
                    'id_profesor' => $profesorId->id,
                    'id_curso' => $cursoId,
                    'id_nivel' => $nivelId,
                    'id_asignatura' => $asignaturaId,
                ]);
            }


            return redirect()->route('admin.asignaturas.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (Auth::user()) {
            // Buscar el profesor y sus asignaciones
            $profesorAsignado = User::findOrFail($id); // O el ID de la relación
            $encuentralo = Profesore::where('id_user', $id)->first();

            $asignacionesActuales = Profesor_asignatura::where('id_profesor', $encuentralo->id)->pluck('id_asignatura')->toArray();

            $profesores = User::where('users.id_rol', 4)->get();
            $cursos = Curso::all();
            $niveles = Nivel::all();
            $asignaturas = Asignatura::all();

            return view('admin.asignaturas.edit', compact(
                'profesores',
                'profesorAsignado',
                'cursos',
                'niveles',
                'asignaturas',
                'asignacionesActuales'
            ));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        if (Auth::user()) {
            // Validar los datos del formulario
            $validated = $request->validate([
                'id_curso' => 'required|exists:cursos,id',
                'id_nivel' => 'required|exists:niveles,id',
                'asignaturas' => 'required|array',
                'asignaturas.*' => 'exists:asignaturas,id',
            ]);

            $cursoId = $validated['id_curso'];
            $nivelId = $validated['id_nivel'];
            $asignaturasSeleccionadas = $validated['asignaturas'];

            // Eliminar asignaciones previas
            $encuentralo = Profesore::where('id_user', $id)->first();
            $profesorAsignado = Profesor_asignatura::where('id_profesor', $encuentralo->id)->delete();

            // Insertar nuevas asignaciones
            foreach ($asignaturasSeleccionadas as $asignaturaId) {
                Profesor_asignatura::create([
                    'id_profesor' => $encuentralo->id,
                    'id_curso' => $cursoId,
                    'id_nivel' => $nivelId,
                    'id_asignatura' => $asignaturaId,
                ]);
            }

            return redirect()->route('admin.asignaturas.index')->with('actualizar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function destroy(Request $request, $id)
    {
        if (Auth::user()) {
            // Eliminar un registro BD

            $encuentralo = Profesore::where('id_user', $id)->first();

            $materia = Profesor_asignatura::where('id_profesor', $encuentralo->id);
            $materia->delete();
            
            return redirect()->route('admin.asignaturas.index')->with('eliminar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }




    ///////////eventos
    public function getEventos()
    {
        // Obtener todos los eventos desde la tabla 'eventos'
        $eventos = Evento::all();

        // Formatear los datos para FullCalendar
        $eventosFormateados = $eventos->map(function ($evento) {
            return [
                'title' => $evento->titulo,
                'start' => $evento->fecha,
                'description' => $evento->descripcion,
            ];
        });

        return response()->json($eventosFormateados);
    }
}
