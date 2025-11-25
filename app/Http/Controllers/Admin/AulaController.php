<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profesor_asignatura;
use App\Models\Tema;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AulaController extends Controller
{
    public function show($id)
    {
        if (Auth::check()) {
            //$usuario = Auth::user();
            //dd($idProfesor);
            //VALIDAR SI ES EL DIRECTOR O EL PROFESOR

            $profesorId = $id;

            $trimestres = Trimestre::orderBy('id')
                ->get()
                ->map(function ($trimestre) use ($profesorId) {

                    // Materias asignadas al profesor
                    $materias = Profesor_asignatura::where('id_profesor', $profesorId)
                        ->where('estado', 1)
                        ->with('asignatura')
                        ->select('id_asignatura', 'id_curso')
                        ->distinct()
                        ->get()
                        ->map(function ($asig) use ($trimestre) {

                            // Temas por trimestre y asignatura
                            $temas = Tema::where('id_asignatura', $asig->id_asignatura)
                                ->where('id_trimestre', $trimestre->id)
                                ->where('id_curso', $asig->id_curso)
                                ->orderBy('id')
                                ->get();

                            return [
                                'asignatura' => $asig->asignatura,
                                'id_asignatura' => $asig->id_asignatura,
                                'id_curso' => $asig->id_curso,
                                'temas' => $temas
                            ];
                        });

                    return [
                        'trimestre' => $trimestre,
                        'materias' => $materias
                    ];
                });


            return view('admin.contenidos.show', compact(

                'trimestres',
            ));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
