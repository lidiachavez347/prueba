<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Asignatura;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::user()) {

            $materias = Asignatura::get();

            return view('admin.materias.index', compact('materias'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function create()
    {
        if (Auth::user()) {
            $areas = Area::pluck('area','id');
            return view('admin.materias.create',compact('areas'));
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
                'nombre_asig' => 'required|unique:asignaturas,nombre_asig', // Validar en la tabla gestiones y la columna 'gestion'
                'estado_asig' => 'required',
            ], [
                'nombre_asig.required' => 'El campo nombre es obligatorio.',
                'nombre_asig.unique' => 'Ya existe una materia con este nombre.',
                'estado_asig.required' => 'El campo estado es obligatorio.',
            ]
        );
    
            // Crear una nueva gestión
            $materia = new Asignatura();
            $materia->nombre_asig = strtoupper($request->nombre_asig);
            $materia->estado_asig = $request->estado_asig;
            $materia->id_area = $request->id_area;
            // Guardar la gestión
            $materia->save();
    
            // Redirigir a la vista con un mensaje de éxito
            return redirect()->route('admin.materias.index')->with('guardar', 'ok');
        } else {
            // Si no hay usuario autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $materia = Asignatura::findOrFail($id);
        $areas = Area::pluck('area','id');

        return view('admin.materias.edit', compact('materia','areas'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
        'nombre_asig' => 'required|string',
        'estado_asig' => 'required',
       // 'id_area' => 'required|exists:areas,id', // Suponiendo que 'areas' es la tabla de áreas
    ], [
        'nombre_asig.required' => 'El nombre de la asignatura es obligatorio.',
        'nombre_asig.string' => 'El nombre de la asignatura debe ser texto.',
        'estado_asig.required' => 'Debe seleccionar el estado de la asignatura.',

    ]);
        $materia = Asignatura::findOrFail($id);
        $materia->nombre_asig = strtoupper($request->nombre_asig);
        $materia->estado_asig = $request->estado_asig;
        $materia->id_area = $request->id_area;
        $materia->update();

        return response()->json(['success' => true, 'message' => 'Materia actualizado correctamente']);
    }
    public function show(string $id) 
    {
        if (Auth::check()) {
            
            $materia = Asignatura::findOrFail($id); // Encuentra la gestión por ID
            if (!$materia) {
                return response()->json(['message' => 'Materia no encontrado'], 404);
            }
            // Devuelve los detalles del trimestre en una vista parcial o JSON
            return view('admin.materias.show', compact('materia'))->render();
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function destroy(string $id)
    {
        $materia = Asignatura::find($id);
        if ($materia) {
            $materia->delete();
            return response()->json(['success' => true, 'message' => 'materia eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'materia no encontrado']);
    }
}
