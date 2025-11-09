<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\QrLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Agrupando rutas de la API con el prefijo 'api'
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // Listar usuarios
    Route::get('/{id}', [UserController::class, 'show']); // Obtener usuario por ID
    Route::post('/', [UserController::class, 'store']); // Crear nuevo usuario
    Route::put('/{id}', [UserController::class, 'update']); // Actualizar usuario
    Route::delete('/{id}', [UserController::class, 'destroy']); // Eliminar usuario
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


