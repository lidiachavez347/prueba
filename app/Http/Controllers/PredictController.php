<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PredictController extends Controller
{
    //
    public function index(Request $request)
    {
    }
    public function predict(Request $request)
    {
      
        // Recibir los datos de entrada (ejemplo: número de días de asistencia de un estudiante)
        $input = $request->input('data');
    
        // Ejecutar el script Python y pasar el input
        $command = escapeshellcmd('python3 ' . storage_path('app/python/predict.py') . ' ' . $input);
        $output = shell_exec($command);
    
      // Usar dd para depurar el output
      // Esto detendrá la ejecución y mostrará el resultado
    
        // Devolver la predicción como respuesta JSON
        return response()->json([
            'prediction' => $output
        ]);
    }
    
}
