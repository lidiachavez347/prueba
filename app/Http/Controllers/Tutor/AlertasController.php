<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use App\Models\DetalleAsistencia;
use App\Models\Estudiante;
use App\Models\NotaDetalle;
use App\Models\Profesore;
use App\Models\Promedio;
use App\Models\Tutore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AlertasController extends Controller
{
   /* public function mostrarAlertas(Request $request)
    {
        if (Auth::check()) {
            // Obtener el tutor autenticado
            $tutor = Tutore::where('id_user', Auth::user()->id)->first();
    
            if ($tutor) {
                // Obtener los estudiantes asociados al tutor
                $estudiantes = $tutor->estudiantes;
    
                // Preparar los datos para los gráficos (opcional)
                $estudiantesDatos = [];
    
                // Filtrar las alertas de los estudiantes asociados a este tutor
                $alertas = Alerta::whereIn('id_estudiante', $estudiantes->pluck('id'))->get();
    
                foreach ($estudiantes as $estudiante) {
                    // Obtener los promedios de notas de este estudiante por asignatura
                    $promedios = Promedio::where('id_estudiante', $estudiante->id)->get();
    
                    // Contar las asistencias
                    $asistenciaPresente = DetalleAsistencia::where('user_id', $estudiante->id)
                        ->where('estado', 'presente')
                        ->count();
                    $asistenciaJustificada = DetalleAsistencia::where('user_id', $estudiante->id)
                        ->where('estado', 'justificado')
                        ->count();
                    $asistenciaAusente = DetalleAsistencia::where('user_id', $estudiante->id)
                        ->where('estado', 'ausente')
                        ->count();
    
                    // Calcular la asistencia total
                    $asistenciaTotal = $asistenciaPresente + $asistenciaJustificada + $asistenciaAusente;
    
                    // Almacenar los datos para el estudiante
                    $estudiantesDatos[] = [
                        'estudiante' => $estudiante,
                        'nom' => $estudiante->nombres_es,
                        'promedios' => $promedios,
                        'asistencia' => [
                            'presente' => $asistenciaPresente,
                            'justificado' => $asistenciaJustificada,
                            'ausente' => $asistenciaAusente,
                            'total' => $asistenciaTotal
                        ]
                    ];
                }
    
                // Pasar las alertas y los datos de estudiantes a la vista
                return view('tutores.alertas.index', compact('alertas', 'estudiantesDatos'));
            } else {
                // Si el tutor no es encontrado, redirigir a la página de login
                Auth::logout();
                return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
            }
        } else {
            // Si no está autenticado, redirigir a login
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }*/
        public function index()
    {
        //listar a los estudiantes y notas del estudiantes que esta a cargo el tutor
        $tutor = Auth::user()->id;

        $estudiante = Estudiante::where('id_tutor',$tutor)->get();

        if (!$estudiante) {
        return view('tutores.notas.index', [
            'notasAgrupadas' => [],
            'trimestres' => [],
            'estudiante' => null,
            'mensaje' => 'No tienes estudiantes asignados.'
        ]);
    }
 // Obtener todos los IDs de estudiantes
    $estudiantesIds = $estudiante->pluck('id');


        // 1. Obtener todas las notas del estudiante
        $notas = NotaDetalle::whereIn('id_estudiante', $estudiantesIds)
            ->with(['materia', 'trimestre']) // si tienes relaciones
            ->get();

        
        $notasAgrupadas = [];

        foreach ($notas as $nota) {
            $idEst = $nota->id_estudiante;
            $materia = $nota->materia->nombre_asig ?? 'SIN MATERIA';
            $trimestre = $nota->trimestre->periodo ?? 'SIN TRIMESTRE';

            $notasAgrupadas[$idEst][$materia][$trimestre] = $nota->promedio_materia;
        }

        // 3. Obtener todos los trimestres para armar encabezados
        $trimestres = $notas->pluck('trimestre.periodo')->unique();

        return view('tutores.notas.index', [
            'notasAgrupadas' => $notasAgrupadas,
            'trimestres' => $trimestres,
            'estudiante' => $estudiante
        ]);
    }
    
}
