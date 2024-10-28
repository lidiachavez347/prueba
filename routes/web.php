<?php

use App\Http\Controllers\Admin\AsignaturasController;
use App\Http\Controllers\Admin\CursosController as AdminCursosController;
use App\Http\Controllers\Admin\EstudianteController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfesorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Director\CursosController;
use App\Http\Controllers\Director\AsignaturaController;
use App\Http\Controllers\Director\UserController as DirectorUserController;



use App\Http\Controllers\PredictController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




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

Route::resource('/predict', PredictController::class)->names('predict');

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('admin/permisos', PermissionController::class)->names('admin.permisos');
    Route::resource('admin/roles', RoleController::class)->names('admin.roles');
    Route::resource('admin/usuarios', UserController::class)->names('admin.usuarios');
    Route::resource('admin/estudiantes', EstudianteController::class)->names('admin.estudiantes');
    Route::resource('admin/profesores', ProfesorController::class)->names('admin.profesores');
    Route::resource('admin/cursos', AdminCursosController::class)->names('admin.cursos');
    Route::resource('admin/asignaturas', AsignaturasController::class)->names('admin.asignaturas');
// Ruta para la búsqueda de tutores
Route::get('/search-tutor', [EstudianteController::class, 'searchTutor']);


    Route::resource('director/cursos', CursosController::class)->names('director.cursos');
    Route::resource('director/asignaturas', AsignaturaController::class)->names('director.asignaturas');
    Route::resource('director/profesores', DirectorUserController::class)->names('director.profesores');
    

});
