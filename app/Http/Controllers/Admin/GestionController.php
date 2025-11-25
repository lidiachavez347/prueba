<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gestion;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::user()) {

            $gestiones = Gestion::get();

            return view('admin.gestion.index', compact('gestiones'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function create()
    {
        if (Auth::user()) {

            return view('admin.gestion.create');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        if (Auth::user()) {

            // Validar los datos del formulario
            $request->validate(
                [
                    'gestion' => 'required|unique:gestiones,gestion', // Validar en la tabla gestiones y la columna 'gestion'
                    'estado' => 'required',
                ],
                [
                    'gestion.required' => 'El campo gestion es obligatorio.',
                    'gestion.unique' => 'El nombre debe ser unico',
                    'estado.required' => 'El campo estado es obligatorio.',
                ]
            );

            // Crear una nueva gestión
            $gestion = new Gestion();
            $gestion->gestion = $request->gestion;
            $gestion->estado = $request->estado;
            $gestion->save();
            // Crear los 3 trimestres automáticamente
$hoy = date('Y-m-d'); // fecha actual

for ($i = 1; $i <= 3; $i++) {

    // Nombres de trimestres
    $nombres = [
        1 => 'PRIMER TRIMESTRE',
        2 => 'SEGUNDO TRIMESTRE',
        3 => 'TERCER TRIMESTRE'
    ];

    // Obtener la gestión (año)
    //$gestionObj = Gestion::find($request->id_gestion);
   // $gestion = $gestionObj->gestion;

    $trimestre = new Trimestre();
    $trimestre->periodo = $nombres[$i] . ' ' . $gestion->gestion;
    $trimestre->estado = 1;

    // Fechas automáticas con HOY
    $trimestre->fecha_inicio = $hoy;
    $trimestre->fecha_fin = $hoy;

    $trimestre->id_gestion = $gestion->id;
    $trimestre->numero = $i;
    $trimestre->save();
}


            // Redirigir a la vista con un mensaje de éxito
            return redirect()->route('admin.gestion.index')->with('guardar', 'ok');
        } else {
            // Si no hay usuario autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->back();
        }
    }

    public function show(string $id)
    {
        if (Auth::check()) {

            $gestion = Gestion::findOrFail($id); // Encuentra la gestión por ID
            if (!$gestion) {
                return response()->json(['message' => 'Gestion no encontrado'], 404);
            }
            // Devuelve los detalles del trimestre en una vista parcial o JSON
            return view('admin.gestion.show', compact('gestion'))->render();
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }

    public function edit($id)
    {
        $gestion = Gestion::findOrFail($id);

        return view('admin.gestion.edit', compact('gestion'));
    }


    public function update(Request $request, $id)
    {
       // Validar los campos
    $request->validate(
        [
            'gestion' => 'required|unique:gestiones,gestion,' . $id,
            'estado' => 'required',
        ],
        [
            'gestion.required' => 'El campo gestión es obligatorio.',
            'gestion.unique' => 'Ya existe una gestión con ese nombre.',
            'estado.required' => 'El campo estado es obligatorio.',
        ]
    );
    
        $gestion = Gestion::findOrFail($id);
        $gestion->gestion = $request->gestion;
        $gestion->estado = $request->estado;
        $gestion->update();

        return response()->json(['success' => true, 'message' => 'Gestion actualizado correctamente']);
    }

    public function destroy(string $id)
    {
        $gestion = Gestion::find($id);
        if ($gestion) {
            $gestion->delete();
            return response()->json(['success' => true, 'message' => 'Gestion eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'Gestion no encontrado']);
    }
}
