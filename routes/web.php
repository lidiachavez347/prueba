<?php

use App\Http\Controllers\Admin\AsignaturasController;
use App\Http\Controllers\Admin\AsistenciaController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\CuricularController;
use App\Http\Controllers\Admin\CursosController as AdminCursosController;
use App\Http\Controllers\Admin\EstudianteController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\GradoController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\NivelController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfesorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TrimestreController as AdminTrimestreController;
use App\Http\Controllers\Admin\TutorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Director\CursosController;
use App\Http\Controllers\Director\AsignaturaController;
use App\Http\Controllers\Director\UserController as DirectorUserController;
use App\Http\Controllers\Estudiante\ContenidoController as EstudianteContenidoController;
use App\Http\Controllers\Estudiante\ExamenController as EstudianteExamenController;
use App\Http\Controllers\Estudiante\TareaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PredictController;
use App\Http\Controllers\Profesor\AsistenciaController as ProfesorAsistenciaController;
use App\Http\Controllers\Profesor\AulaController;
use App\Http\Controllers\Profesor\AutomaticoController;
use App\Http\Controllers\Profesor\CalendarController as ProfesorCalendarController;
use App\Http\Controllers\Profesor\CasaController;
use App\Http\Controllers\Profesor\ContenidoController;
use App\Http\Controllers\Profesor\EscritoController;
use App\Http\Controllers\Profesor\EstudianteController as ProfesorEstudianteController;
use App\Http\Controllers\Profesor\ExamenController;
use App\Http\Controllers\Profesor\NotasController;
use App\Http\Controllers\Profesor\ResultadoEvalController;
use App\Http\Controllers\Profesor\ResultadoTareaController;
use App\Http\Controllers\Profesor\TareasController;
use App\Http\Controllers\Profesor\TemastreController;
use App\Http\Controllers\Profesor\TrimestreController;
use App\Http\Controllers\Tutor\AlertasController;
use App\Http\Controllers\Tutor\CalendarController as TutorCalendarController;
use App\Http\Controllers\WhatsAppController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\EventCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;



use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|Route::put('/predict', [PredictController::class, 'predict']);
*/
Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Se ha enviado un nuevo correo de verificación.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



// routes/web.php
use App\Http\Controllers\Auth\QrLoginController;

//Route::view('/play', 'play');
Route::view('/auth/scan', 'auth.qr-scan');
//Route::get('/auth/login', [QrLoginController::class, 'showQrLogin'])->name('auth.qr-login');

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');

Route::get('/auth/login', function () {
    return view('auth.qr-login');
})->name('auth.qr-login');
//::get('/qrscanner',[QrLoginController::class,'qrscanner'])->name('qrscanner');
//Route::post('/inicio/login', [QrLoginController::class,'loginEntry']);//Check whether the login has been confirmed ,and return the token in response
Route::get('/qr/generate', function () {
    $token = Str::uuid()->toString();
    $url = url("/api/qr/confirm/{$token}");

    return response()->json([
        'token' => $token,
        'url' => $url,
    ]);
});

Route::get('/api/qr/generate', [QrLoginController::class, 'generate']);
Route::get('/api/qr/confirm', [QrLoginController::class, 'confirm']);
Route::get('/api/qr/check/{token}', [QrLoginController::class, 'check']);
Route::get('/qr/login/{token}', [QrLoginController::class, 'loginWithQr']);

//Route::post('/qr-complete', [AuthQrController::class, 'completeLogin'])->name('qr.complete');

//require __DIR__.'/auth.php';
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {

    //ya esta configuracion
    Route::resource('admin/config', ConfigController::class)->names('admin.config');
    Route::get('config/institucion', [ConfigController::class, 'show'])->name('admin.config.institucion');
    Route::post('config/institucion/{id}/edit', [ConfigController::class, 'edit'])->name('admin.config.edit');

    // Ruta para listar las gestiones
    Route::resource('admin/gestion', GestionController::class)->names('admin.gestion');
    Route::get('/admin/gestion/{id}', [GestionController::class, 'show'])->name('admin.gestion.show');
    Route::put('/admin/gestion/{id}/edit', [GestionController::class, 'edit'])->name('admin.gestion.edit');
    Route::put('/admin/gestion/{id}', [GestionController::class, 'update'])->name('admin.gestion.update');
    Route::delete('admin/gestion/{id}', [GestionController::class, 'destroy'])->name('admin.gestion.destroy');
    //   Route::get('admin/config/listar', [ConfigController::class, 'listarGestiones'])->name('admin.config.listar');

    // Ruta para listar las niveles


    // Ruta para listar las grados
    Route::resource('admin/grados', GradoController::class)->names('admin.grados');
    Route::get('/admin/grados/{id}', [GradoController::class, 'show'])->name('admin.grados.show');
    Route::put('/admin/grados/{id}/edit', [GradoController::class, 'edit'])->name('admin.grados.edit');
    Route::put('/admin/grados/{id}', [GradoController::class, 'update'])->name('admin.grados.update');
    Route::delete('admin/grados/{id}', [GradoController::class, 'destroy'])->name('admin.grados.destroy');

    // Ruta para listar las materias
    Route::resource('admin/materias', MateriaController::class)->names('admin.materias');
    Route::get('/admin/materias/{id}', [MateriaController::class, 'show'])->name('admin.materias.show');
    Route::put('/admin/materias/{id}/edit', [MateriaController::class, 'edit'])->name('admin.materias.edit');
    Route::put('/admin/materias/{id}', [MateriaController::class, 'update'])->name('admin.materias.update');
    Route::delete('admin/materias/{id}', [MateriaController::class, 'destroy'])->name('admin.materias.destroy');


    //estudiantes ya esta falta ver tutores e imprimir pdf los botones
    Route::resource('admin/estudiantes', EstudianteController::class)->names('admin.estudiantes');
    Route::get('/admin/estudiantes/{id}', [EstudianteController::class, 'show'])->name('admin.estudiantes.show');
    Route::put('/admin/estudiantes/{id}/edit', [EstudianteController::class, 'edit'])->name('admin.estudiantes.edit');
    Route::put('/admin/estudiantes/{id}', [EstudianteController::class, 'update'])->name('admin.estudiantes.update');
    Route::delete('admin/estudiantes/{id}', [EstudianteController::class, 'destroy'])->name('admin.estudiantes.destroy');
    // Ruta para la búsqueda de tutores
    Route::get('/autocomplete', [EstudianteController::class, 'autocomplete'])->name('autocomplete');
    Route::get('/admin/reportes/estudiantes', [EstudianteController::class, 'exportarPDF'])->name('admin.pdf.estudiantes');
    // Route::get('profesor/reportes/pdf/{id}', [ProfesorUsernameController::class,'pdf'])->name('profesor.reportes.index');

    //ya esta profesores
    Route::resource('admin/profesores', ProfesorController::class)->names('admin.profesores');
    Route::get('/admin/profesores/{id}', [ProfesorController::class, 'show'])->name('admin.profesores.show');
    Route::put('/admin/profesores/{id}/edit', [ProfesorController::class, 'edit'])->name('admin.profesores.edit');
    Route::put('/admin/profesores/{id}', [ProfesorController::class, 'update'])->name('admin.profesores.update');
    Route::delete('admin/profesores/{id}', [ProfesorController::class, 'destroy'])->name('admin.profesores.destroy');
    Route::get('/admin/reportes/profesores', [ProfesorController::class, 'profesoresPDF'])->name('admin.pdf.profesores');


    //ya esta permisos
    Route::resource('admin/permisos', PermissionController::class)->names('admin.permisos');
    Route::get('/admin/permisos/{id}', [PermissionController::class, 'show'])->name('admin.permisos.show');
    Route::get('/admin/permisos/{id}/edit', [PermissionController::class, 'edit'])->name('admin.permisos.edit');
    Route::put('/admin/permisos/{id}', [PermissionController::class, 'update'])->name('admin.permisos.update');
    Route::delete('/admin/permisos/{id}', [PermissionController::class, 'destroy'])->name('admin.permisos.destroy');

    //ya esta usuarios
    Route::resource('admin/usuarios', UserController::class)->names('admin.usuarios');
    Route::get('/admin/usuarios/{id}', [UserController::class, 'show'])->name('admin.usuarios.show');
    Route::put('/admin/usuarios/{id}/edit', [UserController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{id}', [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('admin/usuarios/{id}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');
    Route::get('/admin/reportes/usuarios', [UserController::class, 'usuariosPDF'])->name('admin.pdf.usuarios');


    Route::resource('admin/tutores', TutorController::class)->names('admin.tutores');
    Route::get('/admin/tutores/{id}', [TutorController::class, 'show'])->name('admin.tutores.show');
    Route::put('/admin/tutores/{id}/edit', [TutorController::class, 'edit'])->name('admin.tutores.edit');
    Route::put('/admin/tutores/{id}', [TutorController::class, 'update'])->name('admin.tutores.update');
    Route::delete('admin/tutores/{id}', [TutorController::class, 'destroy'])->name('admin.tutores.destroy');
    Route::get('/admin/reportes/tutores', [TutorController::class, 'tutoresPDF'])->name('admin.pdf.tutores');

    //ya esta trimestre
    Route::resource('admin/trimestres', AdminTrimestreController::class)->names('admin.trimestres');
    Route::get('/admin/trimestres/{id}', [AdminTrimestreController::class, 'show'])->name('admin.trimestres.show');
    Route::get('/admin/trimestres/{id}/edit', [AdminTrimestreController::class, 'edit'])->name('admin.trimestres.edit');
    Route::put('/admin/trimestres/{id}', [AdminTrimestreController::class, 'update'])->name('admin.trimestres.update');
    Route::delete('/admin/trimestres/{id}', [AdminTrimestreController::class, 'destroy'])->name('admin.trimestres.destroy');

    //ya esta temas 
    Route::resource('profesor/temas', TemastreController::class)->names('profesor.temas');
    Route::get('/profesor/temas/{id}', [TemastreController::class, 'show'])->name('profesor.temas.show');
    Route::get('/profesor/temas/{id}/edit', [TemastreController::class, 'edit'])->name('profesor.temas.edit');
    Route::put('/profesor/temas/{id}', [TemastreController::class, 'update'])->name('profesor.temas.update');
    Route::delete('/profesor/temas/{id}', [TemastreController::class, 'destroy'])->name('profesor.temas.destroy');

    //parece que falta
    Route::resource('profesor/tareas', TareasController::class)->names('profesor.tareas');
    Route::get('/profesor/tareas/{id}', [TareasController::class, 'show'])->name('profesor.tareas.show');
    Route::get('/profesor/tareas/{id}/edit', [TareasController::class, 'edit'])->name('profesor.tareas.edit');
    Route::put('/profesor/tareas/{id}', [TareasController::class, 'update'])->name('profesor.tareas.update');
    Route::delete('/profesor/tareas/{id}', [TareasController::class, 'destroy'])->name('profesor.tareas.destroy');
    Route::get('temas/{id}', [TareasController::class, 'obtenerTemasPorAsignatura'])->name('temas.por.asignatura');

    Route::get('/profesor/casa/{id}', [CasaController::class, 'show'])->name('profesor.casa.show');
    Route::put('/profesor/casa/{id}', [CasaController::class, 'update'])->name('profesor.casa.update');
    Route::post('/profesor/casa/{id}', [CasaController::class, 'store'])->name('profesor.casa.store');

    Route::get('/profesor/aula/{id}', [AulaController::class, 'show'])->name('profesor.aula.show');
    Route::put('/profesor/aula/{id}', [AulaController::class, 'update'])->name('profesor.aula.update');


    //PROFESRO EVALUACIONES
    Route::resource('profesor/evaluaciones', ExamenController::class)->names('profesor.evaluaciones');
    Route::get('/profesor/evaluaciones/{id}', [ExamenController::class, 'show'])->name('profesor.evaluaciones.show');
    Route::get('/profesor/evaluaciones/{id}/edit', [ExamenController::class, 'edit'])->name('profesor.evaluaciones.edit');
    Route::put('/profesor/evaluaciones/{id}', [ExamenController::class, 'update'])->name('profesor.evaluaciones.update');
    Route::delete('/profesor/evaluaciones/{id}', [ExamenController::class, 'destroy'])->name('profesor.evaluaciones.destroy');
    //Route::get('evaluaciones/{id}', [ExamenController::class, 'obtenerTemasPorAsignatura'])->name('evaluaciones.por.asignatura');
    Route::get('/profesor/escrito/{id}', [EscritoController::class, 'show'])->name('profesor.escrito.show');
    Route::put('/profesor/escrito/{id}', [EscritoController::class, 'update'])->name('profesor.escrito.update');
    Route::post('/profesor/escrito/{id}', [EscritoController::class, 'store'])->name('profesor.escrito.store');

    Route::get('/profesor/automatico/{id}', [AutomaticoController::class, 'show'])->name('profesor.automatico.show');
    Route::put('/profesor/automatico/{id}', [AutomaticoController::class, 'update'])->name('profesor.automatico.update');
    Route::get('/profesor/automatico/create/{examen}', [AutomaticoController::class, 'crear'])->name('profesor.automatico.create');
    Route::get('/profesor/automatico/{id}/edit', [AutomaticoController::class, 'edit'])->name('profesor.automatico.edit');
    Route::put('/profesor/automatico/{id}', [AutomaticoController::class, 'update'])->name('profesor.automatico.update');
    Route::delete('/profesor/automatico/{id}', [AutomaticoController::class, 'destroy'])->name('profesor.automatico.destroy');
    Route::get('profesor/evaluaciones/{id}/preguntas', [AutomaticoController::class, 'create'])->name('profesor.automatico.create');
    Route::post('profesor/automatico', [AutomaticoController::class, 'store'])->name('profesor.automatico.store');

    Route::resource('profesor/resultadoevaluaciones', ResultadoEvalController::class)->names('profesor.resultadoevaluaciones');



    //profesor asignaturas
    Route::resource('admin/asignaturas', AsignaturasController::class)->names('admin.asignaturas');
    Route::get('/admin/asignaturas/{id}', [AsignaturasController::class, 'show'])->name('admin.asignaturas.show');
    Route::get('/admin/asignaturas/{id}/edit', [AsignaturasController::class, 'edit'])->name('admin.asignaturas.edit');
    Route::put('/admin/asignaturas/{id}', [AsignaturasController::class, 'update'])->name('admin.asignaturas.update');
    Route::delete('/admin/asignaturas/{id}', [AsignaturasController::class, 'destroy'])->name('admin.asignaturas.destroy');

    // Calendar routes
    // Mostrar el calendario
    Route::get('admin/calendario', [CalendarController::class, 'index'])->name('admin.calendario.index');
    Route::post('admin/calendario', [CalendarController::class, 'store'])->name('admin.calendario.store');
    Route::patch('admin/calendario/update/{id}', [CalendarController::class, 'update'])->name('admin.calendario.update');
    Route::delete('admin/calendario/destroy/{id}', [CalendarController::class, 'destroy'])->name('admin.calendario.destroy');
    Route::post('/admin/calendario', [CalendarController::class, 'store'])->name('admin.calendario.store');
    Route::patch('/admin/calendario/update/{id}', [CalendarController::class, 'update'])->name('admin.calendario.update');



    Route::get('admin/books', [BookController::class, 'index'])->name('admin.books.index');
    Route::post('admin/books', [BookController::class, 'store'])->name('admin.books.store');

    Route::get('profesor/calendario', [ProfesorCalendarController::class, 'index'])->name('profesor.calendario.index');
    //Route::get('admin/estudiantes/pdf', [UserController::class, 'generarReportePDF'])->name('admin.pdf.asistencias');







    Route::resource('admin/asistencias', AsistenciaController::class)->names('admin.asistencias');
    Route::get('admin/asistencias/events', [AsistenciaController::class, 'getEventos'])->name('events.load');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    // Ruta para cargar los eventos en el calendario

    Route::get('/events/load', [AsistenciaController::class, 'getEventos'])->name('events.load')->withoutMiddleware(['auth']);
    Route::resource('admin/roles', RoleController::class)->names('admin.roles');








    //falta
    Route::resource('profesor/resultadotarea', ResultadoTareaController::class)->names('profesor.resultadotarea');
    Route::get('/profesor/resultadotarea/{id}', [ResultadoTareaController::class, 'show'])->name('profesor.resultadotarea.show');
    Route::get('/profesor/resultadotarea/{id}/edit', [ResultadoTareaController::class, 'edit'])->name('profesor.resultadotarea.edit');
    Route::put('/profesor/resultadotarea/{id}', [ResultadoTareaController::class, 'update'])->name('profesor.resultadotarea.update');
    Route::delete('/profesor/resultadotarea/{id}', [ResultadoTareaController::class, 'destroy'])->name('profesor.resultadotarea.destroy');

    Route::get('/profesor/alertas', [ResultadoTareaController::class, 'mostrarAlertas'])->name('profesor.alertas.index');

    //falta
    Route::resource('profesor/contenidos', ContenidoController::class)->names('profesor.contenidos');
    Route::resource('estudiante/contenidos', EstudianteContenidoController::class)->names('estudiante.contenidos');
    Route::post('/contenidos/store', [TareaController::class, 'store'])->name('estudiante.tareas.store');


    //Route::get('admin/usuarios/exportar/excel', [EstudianteController::class, 'exportarExcel'])->name('admin.usuarios.exportar.excel');
    //Route::get('admin/usuarios/exportar/pdf', [EstudianteController::class, 'exportarPDF'])->name('admin.usuarios.exportar.plcdf');

    Route::get('/estudiantes/pdf', [ProfesorEstudianteController::class, 'descargarEstudiantesPdf'])->name('pdf.estudiantes');
    Route::get('/asitencias/pdf', [ProfesorAsistenciaController::class, 'generarReportePDF'])->name('pdf.asistencias');






    Route::post('/admin/asignar/', [ProfesorController::class, 'asignarProfesor']);
    //Route::get('/admin/cursos/{cursoId}/paralelos', function ($cursoId) {
    // return App\Models\Paralelo::where('id_curso', $cursoId)->get();
    // });

    Route::get('admin/curricular', [CuricularController::class, 'index'])->name('admin.curricular.index');
    Route::get('admin/curricular/{id}/edit', [CuricularController::class, 'edit'])->name('admin.curricular.edit');
    Route::put('admin/curricular/{id}', [CuricularController::class, 'update'])->name('admin.curricular.update');



    Route::resource('profesor/asistencias', ProfesorAsistenciaController::class)->names('profesor.asistencias');
    Route::resource('profesor/estudiantes', ProfesorEstudianteController::class)->names('profesor.estudiantes');
    Route::get('/profesor/asistencias/show', [AsistenciaController::class, 'show'])->name('profesor.asistencias.show');

    Route::get('estudiante/examen/{id}', [EstudianteExamenController::class, 'show'])->name('estudiante.automatico.show');
    Route::post('estudiante/examen/store', [EstudianteExamenController::class, 'store'])->name('estudiante.automatico.store');

    Route::get('/tutores/alertas', [AlertasController::class, 'mostrarAlertas'])->name('tutores.alertas.index');
    Route::get('tutores/calendario', [TutorCalendarController::class, 'index'])->name('tutores.calendario.index');
});
Route::get('/login/qr/{token}', [AuthController::class, 'loginWithQr']);

Route::get('/enviar-qr/{id}', [UserController::class, 'enviarQrWhatsApp']);
Route::get('/prueba-whatsapp', [UserController::class, 'pruebaWhatsApp']);



Route::post('/verificar-email', function (Request $request) {
    $email = $request->email;

    if (!$email) {
        return response()->json(['valid' => false, 'message' => 'No se proporcionó un correo']);
    }

    $response = Http::get('https://apilayer.net/api/check', [
        'access_key' => config('services.mailboxlayer.key'),
        'email' => $email,
    ]);

    $data = $response->json();

    // Evaluar respuesta
    if (!$data['format_valid'] || !$data['mx_found'] || !$data['smtp_check']) {
        return response()->json(['valid' => false, 'message' => '❌ El correo no existe o no es válido.']);
    }

    return response()->json(['valid' => true, 'message' => '✅ El correo es válido y existe.']);
})->name('verificar.email');


Route::get('/login/qr/{token}', function ($token) {
    $user = User::where('qr_token', $token)->first();

    if (!$user) {
        return redirect('/login')->with('error', 'Token inválido o expirado.');
    }

    // Loguear automáticamente
    Auth::login($user);

    // (Opcional) invalidar token tras uso
    //$user->qr_token = null;
    //$user->save();

    return redirect()->route('home')->with('success', 'Inicio de sesión exitoso con QR.');
});



