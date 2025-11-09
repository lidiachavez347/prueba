<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Nivel;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::user()) {

            $grados = Curso::get();

            return view('admin.grados.index', compact('grados'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function show(string $id)
    {
        if (Auth::check()) {

            $grado = Curso::findOrFail($id); // Encuentra la gestión por ID
            if (!$grado) {
                return response()->json(['message' => 'Grado no encontrado'], 404);
            }
            // Devuelve los detalles del trimestre en una vista parcial o JSON
            return view('admin.grados.show', compact('grado'))->render();
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function edit($id)
    {
        $grado = Curso::findOrFail($id);

        return view('admin.grados.edit', compact('grado'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_curso' => ['required', 'string'],
            'paralelo' => [
                'required',
                 'string',
                Rule::unique('cursos')
                    ->where(function ($query) use ($request) {
                        return $query->where('nombre_curso', $request->nombre_curso);
                    })
                    ->ignore($id),
            ],
            'estado_curso' => ['required', 'boolean'],
        ]);

        $grado = Curso::findOrFail($id);
        $grado->nombre_curso = $request->nombre_curso;
        $grado->paralelo = $request->paralelo;
        $grado->estado_curso = $request->estado_curso;
        $grado->update();

        return response()->json(['success' => true, 'message' => 'Grado actualizado correctamente']);
    }
    public function create()
    {
        if (Auth::user()) {
            return view('admin.grados.create');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()) {

            // Validar los datos del formulario
           $request->validate([
            'nombre_curso' => ['required', 'string'],
            'paralelo' => [
                'required',
                'string',
                Rule::unique('cursos')->where(function ($query) use ($request) {
                    return $query->where('nombre_curso', $request->nombre_curso);
                }),
            ],
            'estado_curso' => ['required', 'boolean'],
        ]);

            // Crear
            $grado = new Curso();
            $grado->nombre_curso = $request->nombre_curso;
            $grado->paralelo = $request->paralelo;
            $grado->estado_curso = $request->estado_curso;
            $grado->save();

            // Redirigir a la vista con un mensaje de éxito
            return redirect()->route('admin.grados.index')->with('guardar', 'ok');
        } else {
            // Si no hay usuario autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->back();
        }
    }
    public function destroy(string $id)
    {
        $grado = Curso::find($id);
        if ($grado) {
            $grado->delete();
            return response()->json(['success' => true, 'message' => 'Grado eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'Grado no encontrado']);
    }
}
