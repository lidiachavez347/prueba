<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Temas;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemastreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if (Auth::check()) {
            $prof = Profesore::where('id_user', Auth::id())->first();
            $asig = Profesor_asignatura::where('id_profesor',$prof->id)->first();
            $temas = Temas::where('id_curso',$asig->id_curso)->get();


            return view('profesor.temas.index', compact('temas'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function show(string $id)
    {
        if (Auth::check()) {

            $tema = Temas::find($id);
            if (!$tema) {
                return response()->json(['message' => 'Tema no encontrado'], 404);
            }
            // Devuelve los detalles del trimestre en una vista parcial o JSON
            return view('profesor.temas.show', compact('tema'))->render();
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function edit($id)
    {
        $tema = Temas::findOrFail($id);
        $asignatura = Asignatura::pluck('nombre_asig', 'id');
        $trimestre = Trimestre::pluck('periodo', 'id');
        return view('profesor.temas.edit', compact('tema', 'asignatura', 'trimestre'));
    }
    public function update(Request $request, $id)
    {
        $tema = Temas::findOrFail($id);
        $tema->titulo = $request->titulo;
        $tema->detalle = $request->detalle;

        $tema->video = $request->video;
        $tema->estado = $request->estado;
        $tema->id_asignatura = $request->id_asignatura;
        $tema->id_trimestre = $request->id_trimestre;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $filename = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images'), $filename);
            $tema->imagen = $filename;
        }
        if ($archivo = $request->file('archivo')) {
            $rutaguardar = 'archivos/';
            $imagenCat = $archivo->getClientOriginalName();
            //date('YmdHis').".".$recurso->getClientOriginalExtension();
            $archivo->move($rutaguardar, $imagenCat);
            $tema['archivo'] = "$imagenCat";
        }

        $tema->update();

        return response()->json(['success' => true, 'message' => 'Tema actualizado correctamente']);
    }

    public function create()
    {
        //
        if (Auth::user()) {
            $profe = Profesore::where('id_user', Auth::id())->first();
            // Filtra las asignaturas según las asignaciones del profesor
            $asignatura = Asignatura::join('profesor_asignaturas', 'asignaturas.id', '=', 'profesor_asignaturas.id_asignatura')
                ->where('profesor_asignaturas.id_profesor', $profe->id)
                ->pluck('asignaturas.nombre_asig', 'asignaturas.id')
                ->prepend('SELECCIONE ASIGNATURA', '');



            $trimestre = Trimestre::where('estado',1)->pluck('periodo', 'id');

            return view('profesor.temas.create', compact('asignatura', 'trimestre'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()) {
            $request->validate(
                [
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

            $tema = new Temas();
            $tema->titulo = $request->titulo;
            $tema->detalle = $request->detalle;
            $tema->video = $request->video;
            $tema->estado = $request->estado;
            $tema->id_asignatura = $request->id_asignatura;
            $tema->id_trimestre = $request->id_trimestre;
            $tema->id_curso = $idParalelo;

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $tema->imagen = $filename;
            }
            if ($archivo = $request->file('archivo')) {
                $rutaguardar = 'archivos/';
                $imagenCat = $archivo->getClientOriginalName();
                //date('YmdHis').".".$recurso->getClientOriginalExtension();
                $archivo->move($rutaguardar, $imagenCat);
                $tema['archivo'] = "$imagenCat";
            }

            $tema->save();

            return redirect()->route('profesor.temas.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        $tema = Temas::find($id);
        if ($tema) {
            $tema->delete();
            return response()->json(['success' => true, 'message' => 'Tema eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'Tema no encontrado']);
    }
}
