<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Gestion;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CuricularController extends Controller
{
    public function index(Request $request)
    {
        $gestiones = Gestion::where('estado',1)->get(); // Obtén todas las gestiones para mostrarlas en el dropdown

        $gestion_id = $request->input('gestion_id');

        if ($gestion_id) {
            $trimestres = Trimestre::where('id_gestion', $gestion_id)
                ->orderBy('id', 'asc') // Orden ascendente por fecha_inicio
                ->get();
        } else {
            $trimestres = Trimestre::orderBy('id', 'asc') // Orden ascendente por fecha_inicio
                ->get();
        }

        return view('admin.curricular.index', compact('trimestres', 'gestiones'));
    }
    public function edit($id)
    {
        $trimestre = Trimestre::findOrFail($id); // Obtener el trimestre por su ID
        return view('admin.curricular.edit', compact('trimestre'));
    }
   public function update(Request $request, $id)
{
    if (Auth::user()) {
        $request->validate([
        'periodo' => 'required|unique:trimestres,periodo,' . $id,
            'estado' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            
        ], [
            'periodo.required' => 'El campo periodo es obligatorio.',
            'periodo.unique' => 'Ya existe un trimestre con este periodo.',
            'estado.required' => 'El campo estado es obligatorio.',
            'fecha_inicio.required' => 'Debe ingresar una fecha de inicio.',
            'fecha_fin.required' => 'Debe ingresar una fecha de fin.',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
        ]);

        $trimestre = Trimestre::findOrFail($id);
        $trimestre->periodo = strtoupper($request->periodo);
        $trimestre->estado = $request->estado;
        $trimestre->fecha_inicio = $request->fecha_inicio;
        $trimestre->fecha_fin = $request->fecha_fin;
       // $trimestre->id_gestion = $request->id_gestion;
        $trimestre->save(); // 👈 Usa save() en lugar de update()



        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.curricular.index')->with('success', 'Trimestre actualizado con éxito');
    }
}
}