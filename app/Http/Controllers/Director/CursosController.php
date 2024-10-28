<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class CursosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()) {
        $cursos = Curso::get();

        return view('director.cursos.index', compact('cursos'));
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }

    public function create()
    {
        if (Auth::user()) {
        return view('director.cursos.create');
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
                'nombre_curso' => 'required|unique:cursos,nombre_curso',
                'estado_curso' => 'required',
            ]
        );

        $curso = new Curso();
        $curso->nombre_curso = strtoupper($request->nombre_curso);
        $curso->estado_curso = $request->estado_curso;
        $curso->save();

        return redirect()->route('director.cursos.index')->with('guardar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }


    public function edit($id)
    {
        if (Auth::user()) {
        $curso = Curso::findOrFail($id);
        return view('director.cursos.edit', compact('curso'));
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
    public function update(Request $request, $id)
    {
        if (Auth::user()) {
        $request->validate(
            [
                'nombre_curso' => 'required',
                'estado_curso' => 'required',
            ]
        );
        $curso = Curso::findOrFail($id);
        $curso->nombre_curso = strtoupper($request->input('nombre_curso'));
        $curso->estado_curso = $request->input('estado_curso');

        $curso->update();

        return redirect()->route('director.cursos.index')->with('actualizar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
    public function destroy($id)
    {
        if (Auth::user()) {
        $curso = Curso::findOrFail($id);
        $curso->delete();


        return redirect()->route('director.cursos.index')->with('eliminar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
}
