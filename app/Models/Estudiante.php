<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    protected $table = 'estudiantes';
    protected $fillable =
    [
        'imagen_es',
        'nombres_es',
        'apellidos_es',
        'fecha_nac_es',
        'genero_es',
        'ci_es',
        'rude_es',
        'estado_es',
        'id_curso',
    ];

   public function tutores()
{
    // Relación muchos a muchos con User, usando la tabla pivote 'estudiante_tutors'
    return $this->belongsToMany(User::class, 'estudiante_tutors', 'id_estudiante', 'id_tutor');
}

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }


    public function detalleAsistencias()
    {
        return $this->hasMany(DetalleAsistencia::class, 'user_id', 'id');
    }

    public function calcularAsistencias($asistencias)
    {
        $totales = [
            'total' => $asistencias->count(),
            'presente' => $asistencias->where('estado', 'presente')->count(),
            'justificadas' => $asistencias->where('estado', 'justificado')->count(),
            'injustificadas' => $asistencias->where('estado', 'ausente')->count(),
            'porcentaje' => 0,
        ];

        // Calcular el porcentaje de asistencias presentes
        $totales['porcentaje'] = $totales['total'] > 0 ? round(($totales['presente'] / $totales['total']) * 100, 2) : 0;

        return $totales;
    }
}
