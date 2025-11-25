<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Criterio;
use App\Models\Estudiante;
use App\Models\Nota;
use App\Models\Profesor_asignatura;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DecidirController extends Controller
{
    public function index(Request $request)
    {
        //
        if (Auth::user()) {
            //----------------------------------

            // Acceder al valor de id_paralelo
            // $cursoActivo = Auth::user()->cursos->where('estado', 1)->first();
            $curso = Profesor_asignatura::where('id_profesor', Auth::user()->id)->first();

            $idParalelo = $curso ? $curso->id_curso : null;


            // $idParalelo = Auth::user()->cursos->id;
            $materias = Asignatura::all();
            $trimestres = Trimestre::all();

            // $idParalelo = Auth::user()->cursos->id;
            // Valores por defecto si no vienen en la request
            $asignaturaId = $request->id_asignatura ?? $materias->first()->id;
            $idTrimestre = $request->id_trimestre ?? $trimestres->first()->id;


            $materias = Asignatura::join('profesor_asignaturas', 'asignaturas.id', '=', 'profesor_asignaturas.id_asignatura')
                ->where('profesor_asignaturas.id_profesor', Auth::user()->id) // ID del profesor
                ->where('profesor_asignaturas.id_curso', $idParalelo)   // Curso del profesor
                ->select('asignaturas.id', 'asignaturas.nombre_asig')
                ->get();


            // Obtener las actividades filtradas por asignatura ->where('id_curso', $idParalelo)
            $criterios = Criterio::whereHas('notas', function ($q) use ($asignaturaId, $idTrimestre, $idParalelo) {
                $q->where('id_materia', $asignaturaId)->where('id_curso', $idParalelo)->where('id_trimestre', $idTrimestre)->where('id_dimencion', 4);
            })->get();


            $materiasConNotas = Nota::whereIn('id_materia', $materias->pluck('id'))
                ->where('id_trimestre', $trimestre_id ?? null)
                ->pluck('id_materia')
                ->toArray();

            $trimestres = Trimestre::where('estado', 1)->get();

            $estudiantes = Estudiante::where('id_curso', $idParalelo)
                ->with(['notas' => function ($query) use ($asignaturaId, $idTrimestre) {
                    $query->where('id_materia', $asignaturaId)
                        ->where('id_trimestre', $idTrimestre)
                        ->where('id_dimencion', 4) //dimension del SER
                        ->with('criterio'); // Para obtener la descripción del criterio
                }])
                ->get();

            //----------------------------

            return view('profesor.decidir.index', compact(


                'asignaturaId',
                'idTrimestre',
                'materias',
                'materiasConNotas',
                'trimestres',
                'estudiantes',
                'criterios'
            ));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'notas' => 'required|array',
            'notas.*' => 'required|array',
            'notas.*.*' => 'required|numeric',
            'criterios' => 'required|unique:criterios,descripcion',

        ], [
            'criterios.required' => 'La descripcion es obligatorio.',
            'criterios.unique' => 'La descripcion debe se unica.',
            'notas.required' => 'Debe ingresar notas.',
            'notas.*.*.numeric' => 'Cada nota debe ser numérica.',
        ]);
        $curso = Profesor_asignatura::where('id_profesor', Auth::user()->id)->first();

        $idParalelo = $curso ? $curso->id_curso : null;

        //$criteriosData = $request->input('criterios', []);
        $materiaId = $request->id_asignatura ?? Asignatura::first()->id;
        $trimestreId = $request->id_trimestre ?? Trimestre::first()->id;

        $criteriosData = $request->input('criterios', []);
        $notasData = $request->input('notas', []);
        //$descripcionCriterio = collect($criteriosData)->first();


        foreach ($criteriosData as $key => $descripcionCriterio) {

            // Crear o traer el criterio
            $criterio = Criterio::firstOrCreate([
                'descripcion' => $descripcionCriterio,
                'id_dimencion' => 4,
            ]);
            $criterioId = $criterio->id;

            // Iterar sobre cada estudiante y guardar la nota correspondiente a este criterio
            foreach ($notasData as $estudianteId => $notasPorCriterio) {
                if (isset($notasPorCriterio[$key])) { // Solo si existe nota para este criterio
                    Nota::updateOrCreate(
                        [
                            'id_estudiante' => $estudianteId,
                            'id_criterio' => $criterioId,
                            'id_materia' => $materiaId,
                            'id_trimestre' => $trimestreId,
                            'id_dimencion' => 4,
                            'id_curso' => $idParalelo,
                        ],
                        [
                            'nota' => $notasPorCriterio[$key],
                            'fecha' => now(),
                        ]
                    );
                }
            }
        }


        return redirect()->back()->with('success', 'Notas guardadas correctamente');
    }


    public function getCriterioData($id)
    {
        $criterio = Criterio::findOrFail($id);
        $curso = Profesor_asignatura::where('id_profesor', Auth::user()->id)->first();

        $idParalelo = $curso ? $curso->id_curso : null;

        if (!$idParalelo) {
        return response()->json([
            'criterio' => $criterio,
            'estudiantes' => []
        ]);
    }
        
        $estudiantes = Estudiante::where('id_curso', $idParalelo)
        ->with(['notas' => function ($q) use ($id, $idParalelo) {
            $q->where('id_criterio', $id)
            ->where('id_curso', $idParalelo);
        }])
        ->get();

        $estudiantesData = $estudiantes->map(function ($est) {
            return [
                'id' => $est->id,
                'nombre' => $est->nombres_es . ' ' . $est->apellidos_es,
                'nota' => $est->notas->first()->nota ?? null
            ];
        });

        return response()->json([
            'criterio' => $criterio,
            'estudiantes' => $estudiantesData
        ]);
    }

    public function update(Request $request, $id)
    {
        $criterio = Criterio::findOrFail($id);

        // 1️⃣ Actualizar nombre del criterio
        $criterio->descripcion = $request->descripcion;
        $criterio->save();

        // 2️⃣ Actualizar notas de todos los estudiantes
        foreach ($request->notas as $estudianteId => $nota) {
            Nota::updateOrCreate(
                [
                    'id_estudiante' => $estudianteId,
                    'id_criterio' => $id,
                ],
                ['nota' => $nota]
            );
        }

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        // 1. Eliminar todas las notas asociadas al criterio
        Nota::where('id_criterio', $id)->delete();

        // 2. Eliminar el criterio
        Criterio::where('id', $id)->delete();

        return response()->json(['success' => true]);
    }
}
