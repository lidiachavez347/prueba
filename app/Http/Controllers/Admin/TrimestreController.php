<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Gestion;
use App\Models\Trimestre;
use Illuminate\Http\Request;

class TrimestreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if (Auth::check()) {

            $trimestres = Trimestre::get();
            return view('admin.trimestres.index', compact('trimestres'));
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

            $gestiones = Gestion::where('estado', 1)->pluck('gestion', 'id');
            return view('admin.trimestres.create', compact('gestiones'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()) {
            $request->validate([
                'periodo' => 'required|unique:trimestres,periodo',
                'estado' => 'required',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'id_gestion' => 'required|exists:gestiones,id',
            ], [
                'periodo.required' => 'El campo periodo es obligatorio.',
                'periodo.unique' => 'Ya existe un trimestre con este periodo.',
                'estado.required' => 'El campo estado es obligatorio.',
                'fecha_inicio.required' => 'Debe ingresar una fecha de inicio.',
                'fecha_fin.required' => 'Debe ingresar una fecha de fin.',
                'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
                'id_gestion.required' => 'Debe seleccionar una gestión válida.',
                'id_gestion.exists' => 'La gestión seleccionada no existe.',
            ]);

            $trimestre = new Trimestre();
            $trimestre->periodo = strtoupper($request->periodo); // convierte a MAYÚSCULAS
            $trimestre->estado = $request->estado;
            $trimestre->fecha_inicio = $request->fecha_inicio;
            $trimestre->fecha_fin = $request->fecha_fin;
            $trimestre->id_gestion = $request->id_gestion;
            $trimestre->save();

            return redirect()->route('admin.trimestres.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function show(string $id)
    {
        if (Auth::user()) {
            $trimestre = Trimestre::find($id);

            if (!$trimestre) {
                return response()->json(['message' => 'Trimestre no encontrado'], 404);
            }

            // Devuelve los detalles del trimestre en una vista parcial o JSON
            return view('admin.trimestres.show', compact('trimestre'))->render();
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        if (Auth::user()) {
            $trimestre = Trimestre::findOrFail($id);
            return view('admin.trimestres.edit', compact('trimestre'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()) {
            $request->validate([
              'periodo' => 'required|unique:trimestres,periodo,' . $id,
                'estado' => 'required',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',

            ], [
                'periodo.required' => 'El campo periodo es obligatorio.',
                'periodo.unique' => 'Ya existe un trimestre con este periodo.',
                'estado.required' => 'El campo estado es obligatorio.',
                'fecha_inicio.required' => 'Debe ingresar una fecha de inicio.',
                'fecha_fin.required' => 'Debe ingresar una fecha de fin.',
                'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',

            ]);

            $trimestre = Trimestre::findOrFail($id);
            $trimestre->periodo = strtoupper($request->periodo);
            $trimestre->estado = $request->estado;
            $trimestre->fecha_inicio = $request->fecha_inicio;
            $trimestre->fecha_fin = $request->fecha_fin;
            $trimestre->save(); // 👈 Usa save() en lugar de update()

               return redirect()->route('admin.curricular.index')->with('success', 'Trimestre actualizado con éxito');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        if (Auth::user()) {
            $trimestre = Trimestre::find($id);
            if ($trimestre) {
                $trimestre->delete();
                return response()->json(['success' => true, 'message' => 'Trimestre eliminado correctamente']);
            }
            return response()->json(['success' => false, 'message' => 'Trimestre no encontrado']);
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
