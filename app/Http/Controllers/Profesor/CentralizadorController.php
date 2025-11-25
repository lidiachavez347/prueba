<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Asignatura;
use App\Models\Estudiante;
use App\Models\NotaDetalle;
use App\Models\Profesor_asignatura;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CentralizadorController extends Controller
{
    /*public function XXX()
    {
    
    $estudiantes = Estudiante::with('notas')->get();
    $asignaturas = Asignatura::orderBy('id')->get();

    $listado = [];

    foreach ($estudiantes as $est) {

        $fila = [
            'estudiante' => $est->apellidos . ' ' . $est->nombres,
            'materias' => []
        ];

        foreach ($asignaturas as $asig) {

            // Promedios por trimestre
            $t1 = $est->notas
                ->where('id_materia', $asig->id)
                ->where('id_trimestre', 1)
                ->avg('nota');

            $t2 = $est->notas
                ->where('id_materia', $asig->id)
                ->where('id_trimestre', 2)
                ->avg('nota');

            $t3 = $est->notas
                ->where('id_materia', $asig->id)
                ->where('id_trimestre', 3)
                ->avg('nota');

            // Promedio anual
            $validos = collect([$t1, $t2, $t3])->filter();
            $anual = $validos->count() > 0 ? round($validos->avg(), 2) : null;

            $fila['materias'][$asig->nombre_asig] = [
                't1' => $t1 ? round($t1, 2) : null,
                't2' => $t2 ? round($t2, 2) : null,
                't3' => $t3 ? round($t3, 2) : null,
                'anual' => $anual,
                'asigID' => $asig->id,
            ];
        }

        $listado[] = $fila;
    }
    return view('profesor.centralizador.index', compact( 'asignaturas','listado'));
    }*/

    public function index()
    {

        $asignaturas = Asignatura::orderBy('id')->get();

$gestionactual = date('Y');

// OBTENER SOLO EL PRIMER TRIMESTRE
$pTrimestre = Trimestre::join('gestiones', 'gestiones.id', '=', 'trimestres.id_gestion')
    ->where('gestiones.gestion', $gestionactual)
    ->select('trimestres.*')
    ->orderBy('trimestres.id', 'ASC')
    ->first();
// Identificamos cada uno
$trimestre1 = $pTrimestre->firstWhere('numero', 1);
$trimestre2 = $pTrimestre->firstWhere('numero', 2);
$trimestre3 = $pTrimestre->firstWhere('numero', 3);

        $curso = Profesor_asignatura::where('id_profesor', Auth::user()->id)->first();
        $idParalelo = $curso ? $curso->id_curso : null;

        $estudiantes = Estudiante::where('id_curso', $idParalelo)->get();
    
        $listado = [];

        foreach ($estudiantes as $est) {

            $fila = [
                'estudiante' => $est->apellidos_es . ' ' . $est->nombres_es,
                'materias' => []
            ];

            foreach ($asignaturas as $asig) {

                // BUSCAMOS DIRECTAMENTE EN NOTA_DETALLE

                
               // Trimestre 1
    $t1 = NotaDetalle::join('trimestres', 'trimestres.id', '=', 'nota_detalle.id_trimestre')
        ->join('gestiones', 'gestiones.id', '=', 'trimestres.id_gestion')
        ->where('gestiones.gestion', $gestionactual)
        ->where('nota_detalle.id_estudiante', $est->id)
        ->where('nota_detalle.id_materia', $asig->id)
        ->where('nota_detalle.id_trimestre', $trimestre1->id)
        ->where('nota_detalle.id_curso', $idParalelo)
        ->value('promedio_materia');

    // Trimestre 2
    $t2 = NotaDetalle::join('trimestres', 'trimestres.id', '=', 'nota_detalle.id_trimestre')
        ->join('gestiones', 'gestiones.id', '=', 'trimestres.id_gestion')
        ->where('gestiones.gestion', $gestionactual)
        ->where('nota_detalle.id_estudiante', $est->id)
        ->where('nota_detalle.id_materia', $asig->id)
        ->where('nota_detalle.id_trimestre', $trimestre2->id)
        ->where('nota_detalle.id_curso', $idParalelo)
        ->value('promedio_materia');

    // Trimestre 3
    $t3 = NotaDetalle::join('trimestres', 'trimestres.id', '=', 'nota_detalle.id_trimestre')
        ->join('gestiones', 'gestiones.id', '=', 'trimestres.id_gestion')
        ->where('gestiones.gestion', $gestionactual)
        ->where('nota_detalle.id_estudiante', $est->id)
        ->where('nota_detalle.id_materia', $asig->id)
        ->where('nota_detalle.id_trimestre', $trimestre3->id)
        ->where('nota_detalle.id_curso', $idParalelo)
        ->value('promedio_materia');

                // PROMEDIO ANUAL
                $validos = collect([$t1, $t2, $t3])->filter();
                $anual = $validos->count() > 0 ? round($validos->avg(), 2) : null;


                $fila['materias'][$asig->nombre_asig] = [
                    't1' => $t1 ? round($t1, 2) : null,
                    't2' => $t2 ? round($t2, 2) : null,
                    't3' => $t3 ? round($t3, 2) : null,
                    'anual' => $anual ? round($anual, 2) : null,
                    'asigID' => $asig->id,
                ];
            }

            $listado[] = $fila;
        }
        //dd($fila['estudiante']);
        return view('profesor.centralizador.index', compact('asignaturas', 'listado'));
    }
}
