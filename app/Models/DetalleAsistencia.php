<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAsistencia extends Model
{
    use HasFactory;

    protected $table = 'detalle_asistencias';
    

    protected $fillable = [
        'user_id',
        'estado',
        'fecha',
        'curso_id',
    ];

    public function user()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class,'curso_id');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'user_id');
    }

    // Relación para acceder al tutor directamente
    public function tutor()
    {
        return $this->estudiante->tutor ?? null;
    }
}
