<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\DetalleAsistencia;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asistencias = Asistencia::get();
        return view('admin.asistencias.index', compact('asistencias'));
    }
    // Obtener todos los eventos


    public function create()
    {
        $users = User::where('id_rol', 4)->get();
        return view('admin.asistencias.create', compact('users'));
    }

    public function store(Request $request)
    {
        $asistencia = Asistencia::create(['fecha' => $request->fecha, 'descripcion' => $request->descripcion]);

        foreach ($request->user_id as $index => $user_id) {
            DetalleAsistencia::create([
                'asistencia_id' => $asistencia->id,
                'user_id' => $user_id,
                'estado' => $request->estado[$index],
                'observaciones' => $request->observaciones[$index] ?? null,
            ]);
        }

        return redirect()->route('admin.asistencias.index')->with('guardar', 'ok');
    }
    public function show($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencias = Asistencia::with('detalles.user')->where('id', $id)->get();
        return view('admin.asistencias.show', compact('asistencias', 'asistencia'));
    }
}
