<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = ['nota', 'estado_notas', 'id_estudiante', 'id_asignatura', 'id_trimestre'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura');
    }
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'id_trimestre');
    }
}
