<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use App\Models\Tema;
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


    public function update(Request $request, $id)

    {
        $request->validate(
                [
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'titulo' => 'required',
                    'detalle' => 'required'
                ],[
                    'titulo.required' => 'Titulo es requerido',
                    'detalle.required'=> 'Campo detalle es requerido'
                ]
            );
        $tema = Tema::findOrFail($id);
        $tema->titulo = $request->titulo;
        $tema->detalle = $request->detalle;

        $tema->video = $request->video;
        $tema->estado = 1;
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

        //return redirect()->route('profesor.contenidos.index')->with('guardar', 'ok');
        return response()->json(['success' => true, 'message' => 'tema actualizado correctamente']);

    }


    public function store(Request $request)
    {

        if (Auth::user()) {
            $request->validate(
                [
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'titulo' => 'required',
                    'detalle' => 'required'
                ],[
                    'titulo.required' => 'Titulo es requerido',
                    'detalle.required'=> 'Campo detalle es requerido'
                ]
            );

            $paralelo = User::join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'users.id')
                ->where('users.id', Auth::user()->id)
                ->select('profesor_asignaturas.id_curso')
                ->first();

            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            $tema = new Tema();
            $tema->titulo = $request->titulo;
            $tema->detalle = $request->detalle;
            $tema->video = $request->video;
            $tema->estado = 1;
            $tema->id_asignatura = $request->id_asignatura;
            $tema->id_trimestre = $request->id_trimestre;
            $tema->id_curso = $idParalelo;
            $tema->avance = 0;

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

            return redirect()->route('profesor.contenidos.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function marcarAvance(Request $request)
    {
        $tema = Tema::findOrFail($request->id);

        $tema->avance = $request->estado ? 1 : 0; // 1 avanzado, 0 pendiente
        $tema->save();

        return response()->json(['success' => true]);
    }
    public function edit($id)
    {
        $tema = Tema::findOrFail($id);
        return view('profesor.temas.edit', compact('tema'));
    }

    public function destroy($id)
    {
        $tema = Tema::find($id);
        if ($tema) {
            $tema->delete();
            return response()->json(['success' => true, 'message' => 'tema eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'tema no encontrado']);
    }

}
