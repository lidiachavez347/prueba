<?php

use App\Http\Controllers\Admin\AsignaturasController;
use App\Http\Controllers\Admin\AsistenciaController;
use App\Http\Controllers\Admin\AulaController as AdminAulaController;
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
use App\Http\Controllers\SmsController;
use App\Http\Controllers\Tutor\AlertasController;
use App\Http\Controllers\Tutor\CalendarController as TutorCalendarController;
use App\Http\Controllers\WhatsAppController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\EventCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\QrLoginController;
use App\Http\Controllers\Profesor as AppHttpControllersProfesorAsistenciaController;

use App\Http\Controllers\Profesor\AsistenciaController as AsistenciaControllerAlias;
use App\Http\Controllers\Profesor\CentralizadorController;
use App\Http\Controllers\Profesor\DecidirController;
use App\Http\Controllers\Profesor\HacerController;
use App\Http\Controllers\Profesor\SaberController;
use App\Http\Controllers\Profesor\SerController;
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

Route::get('/boletin/estudiante/{id}/pdf', [ProfesorEstudianteController::class, 'pfboletin'])->name('pdf.pdf_trimestre');
Route::get('/boletin/{id}', [ProfesorEstudianteController::class, 'boletin'])->name('boletin.ver');
//Route::get('boletin/estudiante/{id}/ver', [ProfesorEstudianteController::class, 'verBoletin'])->name('pdf.pdf_trimestre');
//Route::get('boletin/estudiante/{id}/pdf', [EstudianteController::class, 'pfboletin']);



Route::get('/enviar-sms', [SmsController::class, 'enviarSms']);

Auth::routes(['verify' => true]);

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Se ha enviado un nuevo correo de verificación.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



// routes/web.php
//Route::view('/play', 'play');
//Route::get('/auth/login', [QrLoginController::class, 'showQrLogin'])->name('auth.qr-login');
//::get('/qrscanner',[QrLoginController::class,'qrscanner'])->name('qrscanner');
//Route::post('/inicio/login', [QrLoginController::class,'loginEntry']);//Check whether the login has been confirmed ,and return the token in response


Route::view('/auth/scan', 'auth.qr-scan');
//QR LOGIN DESDE LA LAPTOP
Route::get('/auth/login', function () {
    // Mostrar la vista con el código QR
    return view('auth.qr-login');
})->name('auth.qr-login');


/*Route::get('/api/qr/generate', function () {
    $token = Str::uuid()->toString();
    $url = url("/api/qr/confirm/{$token}");

    return response()->json(['token' => $token, 'url' => $url]);
});*/

//ADMIN QRLOGIN
//1º GENERAR

//2º CONFIRMAR DESDE EL CELULAR
Route::get('/api/qr/confirm', [QrLoginController::class, 'confirm']);

//Route::post('/api/qr/generate', [QrLoginController::class, 'generate']);
//3º CHECK DESDE LA LAPTOP
//Route::get('/api/qr/check/{token}', [QrLoginController::class, 'check']);
//4º LOGIN DESDE LA LAPTOP
Route::get('/api/login/{token}', [QrLoginController::class, 'loginWithQr']);
//5º LISTAR SESIONES QR
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/qr-sessions', [QrLoginController::class, 'list']);
    Route::delete('/api/qr-sessions/{id}', [QrLoginController::class, 'destroy']);
});
//ES OTRO CHECK PARA ELIMINAR LA SESION
Route::get('/api/qr/status/{token}', [QRLoginController::class, 'status']);
//Route::get('/api/qr-sessions', [QrLoginController::class, 'list']);
//Route::delete('/api/qr-sessions/{id}', [QrLoginController::class, 'destroy']);
//Route::get('/qr/status/{token}', [QrLoginController::class, 'checkStatus']);

//LOGUEA EL CELULAR CON EL QR DE WHARTSAPP
Route::get('/login/qr/{token}', [AuthController::class, 'loginWithQrCelular']);
/*/qr/login/{token}    ----- /login/qr/{token}*/


//Route::post('/qr-complete', [AuthQrController::class, 'completeLogin'])->name('qr.complete');
//require __DIR__.'/auth.php';
//   Route::get('admin/config/listar', [ConfigController::class, 'listarGestiones'])->name('admin.config.listar');
// Ruta para listar las niveles

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {

//tutor
        Route::resource('tutores/notas', AlertasController::class)->names('tutores.notas');
    //ADMIN CONFIG YA ESTA
    Route::resource('admin/config', ConfigController::class)->names('admin.config');
    Route::get('config/institucion', [ConfigController::class, 'show'])->name('admin.config.institucion');
    Route::post('config/institucion/{id}/edit', [ConfigController::class, 'edit'])->name('admin.config.edit');

    //ADMIN GESTION YA ESTA
    Route::resource('admin/gestion', GestionController::class)->names('admin.gestion');
    Route::get('/admin/gestion/{id}', [GestionController::class, 'show'])->name('admin.gestion.show');
    Route::put('/admin/gestion/{id}/edit', [GestionController::class, 'edit'])->name('admin.gestion.edit');
    Route::put('/admin/gestion/{id}', [GestionController::class, 'update'])->name('admin.gestion.update');
    Route::delete('admin/gestion/{id}', [GestionController::class, 'destroy'])->name('admin.gestion.destroy');


    //ADMIN CURSOS
    Route::resource('admin/grados', GradoController::class)->names('admin.grados');
    Route::get('/admin/grados/{id}', [GradoController::class, 'show'])->name('admin.grados.show');
    Route::put('/admin/grados/{id}/edit', [GradoController::class, 'edit'])->name('admin.grados.edit');
    Route::put('/admin/grados/{id}', [GradoController::class, 'update'])->name('admin.grados.update');
    Route::delete('admin/grados/{id}', [GradoController::class, 'destroy'])->name('admin.grados.destroy');

    //ADMIN MATERIAS
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
    //ADMIN BUSCAR TUTORES
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

    //ADMIN PERMISOS YA ESTA
    Route::resource('admin/permisos', PermissionController::class)->names('admin.permisos');
    ///CRITERIOS SER
    Route::resource('profesor/ser', SerController::class)->names('profesor.ser');

    Route::get(
        '/profesor/ser/criterio/{id}/data',
        [SerController::class, 'getCriterioData']
    );

    Route::post(
        '/profesor/ser/criterio/{id}/actualizar',
        [SerController::class, 'update']
    );

    Route::delete(
        '/profesor/ser/criterio/{id}/eliminar',
        [SerController::class, 'destroy']
    );
    //DIMENICON SABER
    Route::resource('profesor/saber', SaberController::class)->names('profesor.saber');

    Route::get(
        '/profesor/saber/criterio/{id}/data',
        [SaberController::class, 'getCriterioData']
    );

    Route::post(
        '/profesor/saber/criterio/{id}/actualizar',
        [SaberController::class, 'update']
    );

    Route::delete(
        '/profesor/saber/criterio/{id}/eliminar',
        [SaberController::class, 'destroy']
    );
    //DIMENCION HACER
    Route::resource('profesor/hacer', HacerController::class)->names('profesor.hacer');

    Route::get(
        '/profesor/hacer/criterio/{id}/data',
        [HacerController::class, 'getCriterioData']
    );

    Route::post(
        '/profesor/hacer/criterio/{id}/actualizar',
        [HacerController::class, 'update']
    );

    Route::delete(
        '/profesor/hacer/criterio/{id}/eliminar',
        [HacerController::class, 'destroy']
    );
    //DIMENCION DECIDIR
Route::resource('profesor/decidir', DecidirController::class)->names('profesor.decidir');

    Route::get(
        '/profesor/decidir/criterio/{id}/data',
        [DecidirController::class, 'getCriterioData']
    );

    Route::post(
        '/profesor/decidir/criterio/{id}/actualizar',
        [DecidirController::class, 'update']
    );

    Route::delete(
        '/profesor/decidir/criterio/{id}/eliminar',
        [DecidirController::class, 'destroy']
    );
    //centralizador
    Route::get('/profesor/centralizador/', [CentralizadorController::class, 'index'])
    ->name('profesor.centralizador.index');

    ///////////////////////////////


    Route::get('/admin/permisos/{id}', [PermissionController::class, 'show'])->name('admin.permisos.show');
    Route::get('/admin/permisos/{id}/edit', [PermissionController::class, 'edit'])->name('admin.permisos.edit');
    Route::put('/admin/permisos/{id}', [PermissionController::class, 'update'])->name('admin.permisos.update');
    Route::delete('/admin/permisos/{id}', [PermissionController::class, 'destroy'])->name('admin.permisos.destroy');

    //USUARIOS ADMIN YA ESTA
    Route::resource('admin/usuarios', UserController::class)->names('admin.usuarios');
    Route::get('/admin/usuarios/{id}', [UserController::class, 'show'])->name('admin.usuarios.show');
    Route::put('/admin/usuarios/{id}/edit', [UserController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/admin/usuarios/{id}', [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('admin/usuarios/{id}', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');
    Route::get('/admin/reportes/usuarios', [UserController::class, 'usuariosPDF'])->name('admin.pdf.usuarios');

    Route::get('/enviar-qr/{id}', [UserController::class, 'enviarQrWhatsApp']);
    Route::get('/prueba-whatsapp', [UserController::class, 'pruebaWhatsApp']);

    //ADMIN TUTORES
    Route::resource('admin/tutores', TutorController::class)->names('admin.tutores');
    Route::get('/admin/tutores/{id}', [TutorController::class, 'show'])->name('admin.tutores.show');
    Route::put('/admin/tutores/{id}/edit', [TutorController::class, 'edit'])->name('admin.tutores.edit');
    Route::put('/admin/tutores/{id}', [TutorController::class, 'update'])->name('admin.tutores.update');
    Route::delete('admin/tutores/{id}', [TutorController::class, 'destroy'])->name('admin.tutores.destroy');
    Route::get('/admin/reportes/tutores', [TutorController::class, 'tutoresPDF'])->name('admin.pdf.tutores');

    Route::delete('profesor/temas/{id}', [TemastreController::class, 'destroy'])->name('profesor.temas.destroy');
    //ya esta trimestre
    Route::resource('admin/trimestres', AdminTrimestreController::class)->names('admin.trimestres');
    Route::get('/admin/trimestres/{id}', [AdminTrimestreController::class, 'show'])->name('admin.trimestres.show');
    Route::get('/admin/trimestres/{id}/edit', [AdminTrimestreController::class, 'edit'])->name('admin.trimestres.edit');
    Route::put('/admin/trimestres/{id}', [AdminTrimestreController::class, 'update'])->name('admin.trimestres.update');
    Route::delete('/admin/trimestres/{id}', [AdminTrimestreController::class, 'destroy'])->name('admin.trimestres.destroy');

    //ya esta temas 
    Route::post('/temas/store', [TemastreController::class, 'store'])->name('profesor.temas.store');
    Route::post('/tema/avance', [TemastreController::class, 'marcarAvance'])->name('tema.avance');
    Route::get('/tema/{id}/editar', [TemastreController::class, 'editar'])->name('profesor.temas.edit');
    Route::get('/tema/{id}', [TemastreController::class, 'update'])->name('profesor.temas.update');
    Route::delete('/tema/{id}', [TemastreController::class, 'eliminar'])->name('tema.eliminar');




    Route::resource('profesor/temas', TemastreController::class)->names('profesor.temas');

    Route::get('/profesor/temas/{id}', [TemastreController::class, 'show'])->name('profesor.temas.show');
    //Route::get('/profesor/temas/{id}/edit', [TemastreController::class, 'edit'])->name('profesor.temas.edit');
    Route::put('/profesor/temas/{id}', [TemastreController::class, 'update'])->name('profesor.temas.update');
    Route::delete('/profesor/temas/{id}', [TemastreController::class, 'destroy'])->name('profesor.temas.destroy');



    Route::resource('profesor/resultadoevaluaciones', ResultadoEvalController::class)->names('profesor.resultadoevaluaciones');



    //profesor asignaturas
    Route::resource('admin/asignaturas', AsignaturasController::class)->names('admin.asignaturas');
    Route::get('/admin/asignaturas/{id}', [AsignaturasController::class, 'show'])->name('admin.asignaturas.show');
    Route::get('admin/asignaturas/{id_usuario}/{id_curso}/edit', [AsignaturasController::class, 'edit'])
        ->name('admin.asignaturas.edit');

    //Route::get('/admin/asignaturas/{id}/edit', [AsignaturasController::class, 'edit'])->name('admin.asignaturas.edit');
    Route::put('/admin/asignaturas/{id}', [AsignaturasController::class, 'update'])->name('admin.asignaturas.update');
    Route::delete('/admin/asignaturas/{id_usuario}/{id_curso}/delete', [AsignaturasController::class, 'destroy'])
        ->name('admin.asignaturas.destroy');

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







    // Route::resource('admin/asistencias', AsistenciaController::class)->names('admin.asistencias');
    //Route::get('admin/asistencias/events', [AsistenciaController::class, 'getEventos'])->name('events.load');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    // Ruta para cargar los eventos en el calendario

    // Route::get('/events/load', [AsistenciaController::class, 'getEventos'])->name('events.load')->withoutMiddleware(['auth']);
    Route::resource('admin/roles', RoleController::class)->names('admin.roles');








    Route::get('/profesor/alertas', [ResultadoTareaController::class, 'mostrarAlertas'])->name('profesor.alertas.index');

    //falta
    Route::resource('profesor/contenidos', ContenidoController::class)->names('profesor.contenidos');

    Route::get('/admin/contenido/{id}', [AdminAulaController::class, 'show'])
        ->name('admin.contenidos.show');
    //Route::get('/admin/asignaturas/{id}', [AsignaturasController::class, 'show'])->name('admin.asignaturas.show');

    Route::resource('estudiante/contenidos', EstudianteContenidoController::class)->names('estudiante.contenidos');
    Route::post('/contenidos/store', [TareaController::class, 'store'])->name('estudiante.tareas.store');


    //Route::get('admin/usuarios/exportar/excel', [EstudianteController::class, 'exportarExcel'])->name('admin.usuarios.exportar.excel');
    //Route::get('admin/usuarios/exportar/pdf', [EstudianteController::class, 'exportarPDF'])->name('admin.usuarios.exportar.plcdf');

    Route::get('/estudiantes/pdf', [ProfesorEstudianteController::class, 'descargarEstudiantesPdf'])->name('pdf.estudiantes');
    Route::get('/asitencias/pdf', [ProfesorEstudianteController::class, 'generarReportePDF'])->name('pdf.asistencias');


    Route::get('/profesor/estudiantes/{id}/notas', [ProfesorEstudianteController::class, 'notas'])->name('profesor.estudiantes.notas');




    Route::post('/admin/asignar/', [ProfesorController::class, 'asignarProfesor']);
    //Route::get('/admin/cursos/{cursoId}/paralelos', function ($cursoId) {
    // return App\Models\Paralelo::where('id_curso', $cursoId)->get();
    // });

    Route::get('admin/curricular', [CuricularController::class, 'index'])->name('admin.curricular.index');
    Route::get('admin/curricular/{id}/edit', [CuricularController::class, 'edit'])->name('admin.curricular.edit');
    Route::put('admin/curricular/{id}', [CuricularController::class, 'update'])->name('admin.curricular.update');



    Route::resource('profesor/asistencias', AsistenciaControllerAlias::class)->names('profesor.asistencias');

    Route::resource('profesor/estudiantes', ProfesorEstudianteController::class)->names('profesor.estudiantes');
    Route::get('/profesor/asistencias/show', [ProfesorEstudianteController::class, 'show'])->name('profesor.asistencias.show');
    // Ruta para mostrar el formulario de edición de asistencias por fecha y curso


    // Mostrar formulario de edición de asistencias por curso y fecha
    Route::get('/profesor/asistencias/{curso_id}/{fecha}/edit', [AsistenciaControllerAlias::class, 'edit'])
        ->name('profesor.asistencias.edit');

    // Actualizar asistencias
    Route::put('/profesor/asistencias/{curso_id}/{fecha}', [AsistenciaControllerAlias::class, 'update'])
        ->name('profesor.asistencias.update');



    Route::get('estudiante/examen/{id}', [EstudianteExamenController::class, 'show'])->name('estudiante.automatico.show');
    Route::post('estudiante/examen/store', [EstudianteExamenController::class, 'store'])->name('estudiante.automatico.store');

    Route::get('/tutores/alertas', [AlertasController::class, 'mostrarAlertas'])->name('tutores.alertas.index');
    Route::get('tutores/calendario', [TutorCalendarController::class, 'index'])->name('tutores.calendario.index');
});


Route::get('/enviar-qr/{id}', [EstudianteController::class, 'enviarQrWhatsApp']);
Route::get('/prueba-whatsapp', [EstudianteController::class, 'pruebaWhatsApp']);


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

    // Si MailboxLayer devolvió un error
    if (isset($data['success']) && $data['success'] === false) {
        return response()->json([
            'valid' => false,
            'message' => '⚠️ Error de API: ' . ($data['error']['info'] ?? 'Sin detalle')
        ]);
    }

    // Evaluar datos válidos
    if (
        empty($data['format_valid']) ||
        empty($data['mx_found']) ||
        empty($data['smtp_check'])
    ) {
        return response()->json([
            'valid' => false,
            'message' => '❌ El correo no existe o no es válido.'
        ]);
    }

    return response()->json([
        'valid' => true,
        'message' => '✅ El correo es válido y existe.'
    ]);
})->name('verificar.email');
//Route::post('/verificar-email', [UserController::class, 'verificarEmail'])
//  ->name('verificar.email');



Route::get('/login/qr/{token}', function ($token) {
    $user = User::where('qr_token', $token)->first();

    if (!$user) {
        return redirect('/login')->with('error', 'Token inválido o expirado web.');
    }

    // Loguear automáticamente
    Auth::login($user);

    // (Opcional) invalidar token tras uso
    //$user->qr_token = null;
    //$user->save();

    return redirect()->route('home')->with('success', 'Inicio de sesión exitoso con QR.');
});
