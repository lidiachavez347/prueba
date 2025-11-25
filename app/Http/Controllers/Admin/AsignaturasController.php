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
            $profesores = User::whereHas('profesorAsignaturas')
                ->with(['cursos', 'asignaturas'])
                ->get();

            return view('admin.asignaturas.index', compact('profesores'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $profesor = User::whereHas('profesorAsignaturas')
            ->with(['cursos', 'asignaturas'])
            ->where('id', $id)
            ->firstOrFail();

        return view('admin.asignaturas.show', compact('profesor'));
    }

    public function create()
    {
        if (Auth::user()) {

             // Obtener asignaciones ya existentes
    $asignaciones = Profesor_asignatura::select('id_profesor', 'id_curso')
        ->get()
        ->toArray();

    // Filtrar profesores que NO estén ya asignados
    $profesores = User::where('users.id_rol', 2)
        ->whereNotIn('id', array_column($asignaciones, 'id_profesor'))
        ->get();

    // Filtrar cursos que NO estén ya asignados
    $cursos = Curso::whereNotIn('id', array_column($asignaciones, 'id_curso'))
        ->where('estado_curso',1)
        ->get();

    // Todas las asignaturas siempre se muestran
    $asignaturas = Asignatura::all();

            //$profesores = User::where('users.id_rol', 2)->get();
            //$cursos = Curso::all();
            //$asignaturas = Asignatura::all();
            //return view('admin.profesores.asignar'
            return view('admin.asignaturas.create', compact('profesores', 'cursos', 'asignaturas'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }



    public function store(Request $request)
    {
        if (Auth::user()) {

            $validated = $request->validate([
                'id_profesor' => 'required|exists:users,id',
                'id_curso' => 'required|exists:cursos,id',
                'asignaturas' => 'required|array',
                'asignaturas.*' => 'exists:asignaturas,id',
            ],[
    // id_profesor
    'id_profesor.required' => 'Debe seleccionar un profesor.',
    'id_profesor.exists'   => 'El profesor seleccionado no existe en el sistema.',

    // id_curso
    'id_curso.required' => 'Debe seleccionar un curso.',
    'id_curso.exists'   => 'El curso seleccionado no existe en la base de datos.',

    // asignaturas
    'asignaturas.required' => 'Debe seleccionar al menos una asignatura.',
    'asignaturas.array'    => 'El formato de asignaturas no es válido.',
    'asignaturas.*.exists' => 'Una o más asignaturas seleccionadas no existen.'
]);


            $profesorId = $validated['id_profesor'];
            $cursoId = $validated['id_curso'];

            // Consultar si ya existe algún registro para este profesor y curso
            $existeCurso = Profesor_asignatura::where('id_profesor', $profesorId)
                ->where('id_curso', $cursoId)
                ->exists();

            if (!$existeCurso) {
                foreach ($validated['asignaturas'] as $asignaturaId) {
                    Profesor_asignatura::create([
                        'id_profesor' => $profesorId,
                        'id_curso' => $cursoId,
                        'id_asignatura' => $asignaturaId,
                        'estado' => 1,
                    ]);
                }
            } else {
                return redirect()->back()->with('error', 'Ya existe un registro para este profesor y curso');
            }
            return redirect()->route('admin.asignaturas.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function edit($id_usuario, $id_curso)
    {
        if (Auth::user()) {
            // PROFESOR seleccion
            $profesor = User::findOrFail($id_usuario);

            //$cursosA = Curso::where('id', $id_curso)->pluck('id')->toArray();
            // Asignaturas que ya tiene el profesor
            $asignacionesActuales = Profesor_asignatura::where('id_profesor', $profesor->id)
                //donde sea del paralelo A
                ->where('id_curso', $id_curso)
                ->pluck('id_asignatura')
                ->toArray();
            // Curso actual del profesor (solo el primero)
            $cursoActual = Curso::findOrFail($id_curso);


            //$profesores = User::where('id_rol', 4)->get(); // lista de profesores
            //$cursos = Curso::all();
            $asignaturas = Asignatura::all();

            return view('admin.asignaturas.edit', compact(
                'profesor',       // ← profesor seleccionado
                'cursoActual',
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
       // dd($request);

        if (Auth::user()) {
            // Validar los datos del formulario
            $validated = $request->validate([
                'id_curso' => 'required|exists:cursos,id',
                'asignaturas' => 'required|array',
                'asignaturas.*' => 'exists:asignaturas,id',
            ],[
                'asignaturas' => 'Debe seleccionar al menos una materia'
            ]);

            $cursoId = $validated['id_curso'];
            $asignaturasSeleccionadas = $validated['asignaturas'];

            // 1Eliminar las asignaciones actuales del profesor en este curso
            Profesor_asignatura::where('id_profesor', $id)
                ->where('id_curso', $cursoId)
                ->delete();

            // 2 Insertar las nuevas asignaturas seleccionadas
            foreach ($asignaturasSeleccionadas as $asignaturaId) {
                Profesor_asignatura::create([
                    'id_profesor' => $id,
                    'id_curso' => $cursoId,
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

            $cursoId = $request->id_curso;

            Profesor_asignatura::where('id_profesor', $id)
                ->where('id_curso', $cursoId)
                ->delete();

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
