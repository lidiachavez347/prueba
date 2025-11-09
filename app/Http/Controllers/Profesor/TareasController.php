<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\ActividadDetalle;
use App\Models\Actividades;
use App\Models\Asignatura;
use App\Models\Criterio;
use App\Models\Estudiante;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Tareas;
use App\Models\Temas;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TareasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if (Auth::check()) {
            $prof = Profesore::where('id_user', Auth::id())->first();
            $asig = Profesor_asignatura::where('id_profesor', $prof->id)->first();

            $tareas = Actividades::where('id_curso', $asig->id_curso)->get();
            return view('profesor.tareas.index', compact('tareas'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function show(string $id)
    {
        $tarea = Actividades::find($id);

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrado'], 404);
        }
        // Devuelve los detalles del trimestre en una vista parcial o JSON
        return view('profesor.tareas.show', compact('tarea'))->render();
    }
    public function edit($id)
    {
        $tarea = Actividades::findOrFail($id);
        $tema = Temas::pluck('titulo', 'id');
        $asignatura = Asignatura::pluck('nombre_asig', 'id');
        $trimestre = Trimestre::pluck('periodo', 'id');
        $criterio = Criterio::pluck('nombres', 'id');

        return view('profesor.tareas.edit', compact('criterio', 'tema', 'asignatura', 'trimestre', 'tarea'));
    }
    public function update(Request $request, $id)
    {
        $tarea = Actividades::findOrFail($id);
        $tarea->nombre = $request->nombre;
        $tarea->descripcion = $request->descripcion;
        $tarea->video = $request->video;
        $tarea->fecha = $request->fecha;
        $tarea->estado = $request->estado;
        $tarea->evaluar = $request->evaluar;
        $tarea->id_asignatura = $request->id_asignatura;
        $tarea->id_trimestre = $request->id_trimestre;
        $tarea->id_criterio = $request->id_criterio;
        $tarea->id_tema = $request->id_tema;
        $tarea->tipo = $request->tipo;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $filename = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images'), $filename);
            $tarea->imagen = $filename;
        }
        if ($archivo = $request->file('archivo')) {
            $rutaguardar = 'archivos/';
            $imagenCat = $archivo->getClientOriginalName();
            //date('YmdHis').".".$recurso->getClientOriginalExtension();
            $archivo->move($rutaguardar, $imagenCat);
            $tarea['archivo'] = "$imagenCat";
        }

        $tarea->update();

        return response()->json(['success' => true, 'message' => 'Tarea actualizado correctamente']);
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

            return view('profesor.tareas.create', compact('asignatura', 'trimestre', 'criterio'));
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
                    'descripcion' => 'required',
                    'fecha' => 'required',
                    'estado' => 'required',
                    'evaluar' => 'required',
                    'id_asignatura' => 'required',
                    'id_trimestre' => 'required',
                    'id_criterio' => 'required',
                    'id_tema' => 'required',
                    'tipo' => 'required',
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]
            );
            // Obtener los estudiantes y sus asistencias
            $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
                ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
                ->where('profesores.id_user', Auth::user()->id)
                ->select('profesor_asignaturas.id_curso')
                ->first();

            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            $tarea = new Actividades();
            $tarea->nombre = $request->nombre;
            $tarea->descripcion = $request->descripcion;
            $tarea->video = $request->video;
            $tarea->fecha = $request->fecha;
            $tarea->estado = $request->estado;
            $tarea->evaluar = $request->evaluar;
            $tarea->id_asignatura = $request->id_asignatura;
            $tarea->id_trimestre = $request->id_trimestre;
            $tarea->id_criterio = $request->id_criterio;
            $tarea->id_tema = $request->id_tema;
            $tarea->id_curso = $idParalelo;
            $tarea->tipo = $request->tipo;

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $tarea->imagen = $filename;
            }
            if ($archivo = $request->file('archivo')) {
                $rutaguardar = 'archivos/';
                $imagenCat = $archivo->getClientOriginalName();
                //date('YmdHis').".".$recurso->getClientOriginalExtension();
                $archivo->move($rutaguardar, $imagenCat);
                $tarea['archivo'] = "$imagenCat";
            }

            $tarea->save();
            if ($request->tipo == 'AULA') {

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
            }


            return redirect()->route('profesor.tareas.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function destroy(string $id)
    {
        $tarea = Actividades::find($id);
        if ($tarea) {
            $tarea->delete();
            return response()->json(['success' => true, 'message' => 'tarea eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'tarea no encontrado']);
    }
}
