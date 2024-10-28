<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
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
        $materias = Asignatura::all();
        return view('admin.asignaturas.index', compact('materias'));
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
    public function create()
    {
        if (Auth::user()) {
        return view('admin.asignaturas.create');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
    public function store(Request $request)
    { //guardar BD los Registro
        if (Auth::user()) {
        $request->validate([
            'nombre_asignatura' => 'required|unique:asignaturas,nombre_asignatura',
            'estado_asignatura' => 'required',
        ]);
        $materia = $request->all();

        $materia = new Asignatura();
        $materia->nombre_asignatura = strtoupper($request->nombre_asignatura);
        $materia->estado_asignatura = $request->estado_asignatura;
        $materia->save();

        return redirect()->route('admin.asignaturas.index')->with('guardar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }

    public function edit(Asignatura $asignatura)
    { // Abrir un formualrio para Edicion de un registro BD
        if (Auth::user()) {
        return view('admin.asignaturas.edit', compact('asignatura'));
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
    public function update(Request $request, $id)
    { //Actualizar el regsitro en el BD
        if (Auth::user()) {
        $request->validate([
            'nombre_asignatura' => 'required',
            'estado_asignatura' => 'required',
        ]);
        $materia = $request->all();

        $materia = Asignatura::findOrFail($id);
        $materia->nombre_asignatura = strtoupper($request->nombre_asignatura);
        $materia->estado_asignatura = $request->estado_asignatura;
        $materia->update();

        return redirect()->route('admin.asignaturas.index')->with('actualizar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
    public function destroy(Asignatura $materia)
    { if (Auth::user()) {
        // Eliminar un registro BD
        $materia->delete();
        return redirect()->route('admin.asignaturas.index')->with('eliminar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
}
