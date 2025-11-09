<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Examene;
use App\Models\Opcion;
use App\Models\Pregunta;
use App\Models\Profesor_asignatura;
use App\Models\Profesore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutomaticoController extends Controller
{
    public function create($id)
    {
        if (Auth::user()) {
            $evaluaciones = Examene::where('id', $id)->pluck('id');

            return view('profesor.automatico.create', compact('evaluaciones'));
        } else {
            Auth::logout();
            return redirect()->back();
        }}
    public function destroy(){}
    public function update(){}
    public function edit(){}

    public function store(Request $request)
    { //guardar BD los Registro

        if (Auth::user()) {
            $this->validate(
                $request,
                [
                    'descripcion' => 'required|unique:preguntas,descripcion',
                    'examene_id' => 'required',
                    'detalle.*' => 'required'
                ]
            );

            $preguntas = new Pregunta();
            $preguntas->descripcion = $request->descripcion;
            $preguntas->id_exam = $request->examene_id;
            $preguntas->save();

            $pre = Pregunta::latest('id')->pluck('id')->first();

            foreach ($request->detalle as $key => $detalle) {
                $opciones = new Opcion();
                $opciones->opcion = $detalle;
                $opciones->id_pregunta = $pre;
                $opciones->estado = $request->estado[$key];
                $opciones->puntos = $request->puntos[$key];

                // dd($opciones);
                $opciones->save();
            }


            return redirect()->route('profesor.evaluaciones.index')->with('guardar', 'ok');;
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function show($id)
    {
        if (Auth::user()) {
            $preguntas = Pregunta::find($id);
            $respuestas = Opcion::with('question')->where('id_pregunta', '=', $id)->get();

            return view('profesor.automatico.show', compact('preguntas', 'respuestas'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
