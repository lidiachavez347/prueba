<?php

namespace App\Http\Controllers\Profesor;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\DetalleAsistencia;
use App\Models\Estudiante;
use App\Models\Profesore;
use App\Models\User;
use Egulias\EmailValidator\Result\Reason\DetailedReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if (Auth::check()) {


            $mesSeleccionado = $request->input('mes', Carbon::now()->format('Y-m')); // Mes actual por defecto
            $fechaInicio = Carbon::createFromFormat('Y-m', $mesSeleccionado)->startOfMonth();
            $fechaFin = Carbon::createFromFormat('Y-m', $mesSeleccionado)->endOfMonth();


            // Obtener al profesor logueado

            $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
                ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
                ->where('profesores.id_user', Auth::user()->id)
                ->whereColumn('profesor_asignaturas.id_profesor', 'profesores.id')
                ->select('profesor_asignaturas.id_curso')
                ->first();

            // Acceder al valor de id_paralelo
            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            $estudiantes = Estudiante::join('detalle_asistencias', 'estudiantes.id', '=', 'detalle_asistencias.user_id')
                ->where('estudiantes.id_curso', $idParalelo)
                ->whereBetween('detalle_asistencias.created_at', [$fechaInicio, $fechaFin])
                ->select(
                    'estudiantes.nombres_es',
                    'estudiantes.id',
                    'estudiantes.apellidos_es',
                    'estudiantes.rude_es',
                    DB::raw('COUNT(detalle_asistencias.id) as total_asistencias'),
                    DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'presente\' THEN 1 ELSE 0 END) as total_presentes'),
                    DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'ausente\' THEN 1 ELSE 0 END) as total_ausentes'),
                    DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'justificado\' THEN 1 ELSE 0 END) as total_justificados'),
                    DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'tarde\' THEN 1 ELSE 0 END) as total_tardes')
                )
                ->groupBy(
                    'estudiantes.nombres_es',
                    'estudiantes.id',
                    'estudiantes.apellidos_es',
                    'estudiantes.rude_es'
                )
                ->get();

            // Calcular las asistencias para cada estudiante
            // Calcular las asistencias para cada estudiante
            $estudiantes->map(function ($estudiante) use ($fechaInicio, $fechaFin) {
                $asistencias = $estudiante->detalleAsistencias()
                    ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                    ->get();  // Traer todas las asistencias en el mes

                $totales = $estudiante->calcularAsistencias($asistencias);  // Llamar al método de cálculo de asistencias
                $estudiante->totales_asistencias = $totales;  // Agregar el cálculo al estudiante
            });




            $meses = $this->generarMeses();

            return view('profesor.asistencias.index', compact('estudiantes', 'mesSeleccionado', 'meses'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    private function generarMeses()
    {
        $meses = [];
        for ($i = 0; $i < 12; $i++) {
            $mes = Carbon::now()->subMonths($i)->format('Y-m');
            $nombreMes = Carbon::now()->subMonths($i)->translatedFormat('F Y');
            $meses[$mes] = $nombreMes;
        }
        return array_reverse($meses);
    }
    // Mostrar formulario con los estudiantes
    public function create()
    {
        // Obtener la fecha de hoy
        $fechaHoy = Carbon::now()->toDateString();

        // Obtener dato del profesor
        $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
            ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
            ->where('profesores.id_user', Auth::user()->id)
            //->whereColumn('profesor_asignaturas.id_profesor', 'profesores.id')
            ->select('profesor_asignaturas.id_curso')
            ->first();

        // Acceder al valor de id_paralelo
        $idParalelo = $paralelo ? $paralelo->id_curso : null;

        // optener datos de los estudiantes
        // dd($idParalelo);

        $estudiantes = Estudiante::where('estudiantes.id_curso', $idParalelo)
            ->select(
                'estudiantes.nombres_es',
                'estudiantes.id',
                'estudiantes.apellidos_es',
                'estudiantes.rude_es'
            )
            ->get();

        // Pasar datos a la vista

        return view('profesor.asistencias.create', compact('fechaHoy', 'estudiantes'));
    }

    // Guardar las asistencias
    public function store(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'asistencias' => 'required|array',
            'asistencias.*.estado' => 'required|in:presente,ausente,justificado',
        ]);
        $fechaHoy = Carbon::now()->toDateString();
        // Crear el paralelo en la base de datos
        // $asisten = Asistencia::create([
        //'fecha' => $fechaHoy,
        // 'descripcion' => 'NORMAL REGULARIZADO', // Por defecto, el estado es activo
        //]);

        foreach ($request->asistencias as $asistencia) {
            DetalleAsistencia::updateOrCreate(
                [
                    'user_id' => $asistencia['user_id'],
                    'fecha' => $fechaHoy,
                    // 'asistencia_id' => $asisten->id,
                ],
                [
                    'estado' => $asistencia['estado'],
                ]
            );
        }


        return redirect()->route('profesor.asistencias.index')->with('guardar', 'ok');
    }


    public function generarReportePDF(Request $request)
    {
        // Obtener el mes seleccionado
        $mesSeleccionado = $request->input('mes', Carbon::now()->format('Y-m'));
        $fechaInicio = Carbon::createFromFormat('Y-m', $mesSeleccionado)->startOfMonth();
        $fechaFin = Carbon::createFromFormat('Y-m', $mesSeleccionado)->endOfMonth();

        // Obtener los estudiantes y sus asistencias
        $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
            ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
            ->where('profesores.id_user', Auth::user()->id)
            ->select('profesor_asignaturas.id_curso')
            ->first();

        $idParalelo = $paralelo ? $paralelo->id_curso : null;

        $estudiantes = Estudiante::
            join('detalle_asistencias', 'estudiantes.id', '=', 'detalle_asistencias.user_id')
            ->where('estudiantes.id_curso', $idParalelo)
            ->whereBetween('detalle_asistencias.created_at', [$fechaInicio, $fechaFin])
            ->select(
                'estudiantes.nombres_es',
                'estudiantes.id',
                'estudiantes.apellidos_es',
                'estudiantes.rude_es',
                DB::raw('COUNT(detalle_asistencias.id) as total_asistencias'),
                DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'presente\' THEN 1 ELSE 0 END) as total_presentes'),
                DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'ausente\' THEN 1 ELSE 0 END) as total_ausentes'),
                DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'justificado\' THEN 1 ELSE 0 END) as total_justificados'),
                DB::raw('SUM(CASE WHEN detalle_asistencias.estado = \'tarde\' THEN 1 ELSE 0 END) as total_tardes')
            )
            ->groupBy(
                'estudiantes.nombres_es',
                'estudiantes.id',
                'estudiantes.apellidos_es',
                'estudiantes.rude_es'
            )
            ->get();

        // Calcular las asistencias para cada estudiante
        $estudiantes->map(function ($estudiante) use ($fechaInicio, $fechaFin) {
            $asistencias = $estudiante->detalleAsistencias()
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->get();

            $totales = $estudiante->calcularAsistencias($asistencias);
            $estudiante->totales_asistencias = $totales;
        });

        // Generar el PDF
        $pdf = PDF::loadView('pdf.asistencias', compact('estudiantes', 'mesSeleccionado'));

        // Descargar el archivo PDF
        return $pdf->stream('reporte_asistencias_' . $mesSeleccionado . '.pdf');
        // return $pdf->download('reporte_asistencias_'.$mesSeleccionado.'.pdf');
    }

    public function show(Request $request)
    {
        if (Auth::check()) {
            $mesSeleccionado = $request->input('mes', Carbon::now()->format('Y-m')); // Mes actual por defecto
            $fechaInicio = Carbon::createFromFormat('Y-m', $mesSeleccionado)->startOfMonth();
            $fechaFin = Carbon::createFromFormat('Y-m', $mesSeleccionado)->endOfMonth();

            // Obtener al profesor logueado y el paralelo
            $paralelo = User::join('profesores', 'profesores.id_user', '=', 'users.id')
                ->join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'profesores.id')
                ->where('profesores.id_user', Auth::user()->id)
                ->select('profesor_asignaturas.id_curso')
                ->first();

            // Acceder al valor de id_paralelo
            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            // Obtener estudiantes en el paralelo seleccionado
            $estudiantes = Estudiante::where('estudiantes.id_curso', $idParalelo)
                ->select('estudiantes.id', 'estudiantes.nombres_es', 'estudiantes.apellidos_es')
                ->get();

            // Obtener las fechas de asistencia únicas para los estudiantes en el periodo seleccionado
            $fechas = DetalleAsistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->join('estudiantes', 'estudiantes.id', '=', 'detalle_asistencias.user_id')
                ->where('estudiantes.id_curso', $idParalelo)
                ->distinct('fecha')
                ->pluck('fecha')
                ->map(function ($fecha) {
                    return Carbon::parse($fecha); // Asegurarse de que cada fecha sea un objeto Carbon
                })
                ->sort()
                ->values();

            // Obtener las asistencias para todos los estudiantes en las fechas seleccionadas
            $asistencias = DetalleAsistencia::whereIn('fecha', $fechas)
                ->whereIn('user_id', $estudiantes->pluck('id'))
                ->get();

            // Asegurarse de que cada 'fecha' en $asistencias sea un objeto Carbon
            $asistencias->each(function ($item) {
                $item->fecha = Carbon::parse($item->fecha);  // Convertir 'fecha' a objeto Carbon si es necesario
            });

            // Agrupar las asistencias por el ID del estudiante y por fecha
            $asistenciasAgrupadas = $asistencias->groupBy(function ($item) {
                return $item->user_id . '_' . $item->fecha->format('Y-m-d');  // Ahora $item->fecha es un objeto Carbon
            });

            //dd($asistenciasAgrupadas);

            $meses = $this->generarMeses(); // Método para generar los meses disponibles.

            return view('profesor.asistencias.show', compact('fechas', 'asistencias', 'estudiantes', 'mesSeleccionado', 'meses', 'asistenciasAgrupadas'));
        } else {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
