<?php

namespace App\Http\Controllers\Profesor;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\NotaDetalle;
use App\Models\Profesore;
use App\Models\Trimestre;
use App\Models\Tutore;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EstudianteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::check()) {
            // Obtener al profesor logueado
            $profesor = User::where('id', Auth::id())->first();

            if (!$profesor) {
                // Si no se encuentra al profesor, redirigir con un mensaje de error
                return redirect()->back()->with('error', 'No se encontró al profesor logueado.');
            }



            $paralelo = User::join('profesor_asignaturas', 'profesor_asignaturas.id_profesor', '=', 'users.id')
                ->where('users.id', Auth::user()->id)
                ->whereColumn('profesor_asignaturas.id_profesor', 'users.id')
                ->select('profesor_asignaturas.id_curso')
                ->first();

            // Acceder al valor de id_paralelo
            $idParalelo = $paralelo ? $paralelo->id_curso : null;

            // Verificar el valor
            //dd($idParalelo);

            $estudiantes = Estudiante::where('estudiantes.id_curso', $idParalelo)
                ->select(
                    'estudiantes.nombres_es',
                    'estudiantes.id',
                    'estudiantes.apellidos_es',
                    'estudiantes.fecha_nac_es',
                    'estudiantes.genero_es',
                    'estudiantes.estado_es',
                    'estudiantes.rude_es'
                )
                ->get();


            // Retornar vista con los estudiantes
            return view('profesor.estudiantes.index', compact('estudiantes'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
    public function show(String $id)
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados
            $estudiante = Estudiante::with('tutor')->findOrFail($id);

            // Obtener información detallada de los tutores relacionados al estudiante
            $tutores = User::join('estudiantes', 'estudiantes.id_tutor', '=', 'users.id')
                ->where('estudiantes.id', $id)
                ->select(
                    'users.nombres',
                    'users.apellidos',
                    'users.imagen',
                    'users.direccion',
                    'users.telefono',
                    'users.estado_user'
                )
                ->get();

            // Retornar la vista con los datos del estudiante y sus tutores
            return view('profesor.estudiantes.show', compact('estudiante', 'tutores'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }

    public function descargarEstudiantesPdf()
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados

            $estudiante = Estudiante::join('estudiante_tutors', 'estudiantes.id', '=', 'estudiante_tutors.id_estudiante') // Corregido: campo `id_estudiante`
                ->join('tutores', 'estudiante_tutors.id_tutor', '=', 'tutores.id')
                ->join('users', 'tutores.id_user', '=', 'users.id')
                ->where('estudiantes.id_curso', 1)
                ->select(
                    'estudiantes.nombres_es',
                    'estudiantes.id',
                    'estudiantes.apellidos_es',
                    'estudiantes.fecha_nac_es',
                    'estudiantes.genero_es',
                    'estudiantes.estado_es',
                    'estudiantes.rude_es',
                    'users.nombres',
                    'users.apellidos',
                    'users.telefono',
                    'users.direccion' // Alias para evitar confusión
                )
                ->get();

            // Renderizar la vista de PDF con los datos
            $pdf = Pdf::loadView('pdf.estudiantes', compact('estudiante'));

            return $pdf->stream('estudiantes.pdf');

            // Descargar el PDF
            //return $pdf->download('estudiantes.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }


    public function notas($id)
    {
        // 1. Obtener todas las notas del estudiante
        $notas = NotaDetalle::where('id_estudiante', $id)
            ->with(['materia', 'trimestre']) // si tienes relaciones
            ->get();

        $estudiante = Estudiante::findOrFail($id);
        $notasAgrupadas = [];

        foreach ($notas as $nota) {
            $materia = $nota->materia->nombre_asig ?? 'SIN MATERIA';
            $trimestre = $nota->trimestre->periodo ?? 'SIN TRIMESTRE';

            $notasAgrupadas[$materia][$trimestre] = $nota->promedio_materia;
        }

        // 3. Obtener todos los trimestres para armar encabezados
        $trimestres = $notas->pluck('trimestre.periodo')->unique();

        return view('profesor.estudiantes.notas', [
            'notasAgrupadas' => $notasAgrupadas,
            'trimestres' => $trimestres,
            'estudiante' => $estudiante
        ]);
    }



    public function pfboletin($id)
    {

        $notas = NotaDetalle::where('id_estudiante', $id)
            ->with(['materia', 'trimestre']) // si tienes relaciones
            ->get();

        $estudiante = Estudiante::findOrFail($id);
        $notasAgrupadas = [];

        foreach ($notas as $nota) {
            $materia = $nota->materia->nombre_asig ?? 'SIN MATERIA';
            $trimestre = $nota->trimestre->periodo ?? 'SIN TRIMESTRE';

            $notasAgrupadas[$materia][$trimestre] = $nota->promedio_materia;
        }
        $fileNameqr = "qr_{$estudiante->id}.png";
            
        $rutaQR = public_path("images/{$fileNameqr}");

       /// $ = storage_path("images/qr_{}.png");
         //$qrPath = public_path("qr/{$fileNameqr}");
        $urlLogin = url("boletin/estudiante/{$estudiante->id}/pdf");

        QrCode::format('png')->size(150)->generate($urlLogin, $rutaQR);
        // 3. Obtener todos los trimestres para armar encabezados
        $trimestres = $notas->pluck('trimestre.periodo')->unique();

        $pdf = Pdf::setPaper('a4','landscape')
        ->loadView('pdf.pdf_trimestre', [
            'estudiante' => $estudiante,
            'notasAgrupadas' => $notasAgrupadas,
            'trimestres' => $trimestres,
            'qrRuta' => $rutaQR
        ]);

        //return $pdf->stream('boletin_' . $estudiante->nombres_es . '.pdf');
$nombreArchivo = 'boletin_' . $estudiante->nombres_es . '.pdf';

return $pdf->stream($nombreArchivo, [
'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'inline; filename="'.$nombreArchivo.'"'
]);

    }
//Route::get('/boletin/estudiante/{id}/pdf', [ProfesorEstudianteController::class, 'pfboletin'])->name('pdf.pdf_trimestre');


public function boletin($id)
    {

        $notas = NotaDetalle::where('id_estudiante', $id)
            ->with(['materia', 'trimestre']) // si tienes relaciones
            ->get();

        $estudiante = Estudiante::findOrFail($id);
        $notasAgrupadas = [];

        foreach ($notas as $nota) {
            $materia = $nota->materia->nombre_asig ?? 'SIN MATERIA';
            $trimestre = $nota->trimestre->periodo ?? 'SIN TRIMESTRE';

            $notasAgrupadas[$materia][$trimestre] = $nota->promedio_materia;
        }
        $fileNameqr = "qr_{$estudiante->id}.png";
            
        $rutaQR = public_path("images/{$fileNameqr}");

       /// $ = storage_path("images/qr_{}.png");
         //$qrPath = public_path("qr/{$fileNameqr}");
        $urlLogin = url("boletin/estudiante/{$estudiante->id}/ver");

        QrCode::format('png')->size(150)->generate($urlLogin, $rutaQR);
        // 3. Obtener todos los trimestres para armar encabezados
        $trimestres = $notas->pluck('trimestre.periodo')->unique();

        $pdf = Pdf::setPaper('a4','landscape')
        ->loadView('pdf.enviar', [
            'estudiante' => $estudiante,
            'notasAgrupadas' => $notasAgrupadas,
            'trimestres' => $trimestres,
            'qrRuta' => $rutaQR
        ]);

        //return $pdf->stream('boletin_' . $estudiante->nombres_es . '.pdf');
        $nombreArchivo = 'boletin_' . $estudiante->nombres_es . '.pdf';

return $pdf->stream($nombreArchivo, [
'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'inline; filename="'.$nombreArchivo.'"'
]);
    }

public function verBoletin($id)
{
    return view('pdf.visualizar_boletin', [
        'id' => $id
    ]);
}


}
