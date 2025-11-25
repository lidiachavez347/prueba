<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_asig', 'estado_asig', 'id_area'];

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area', 'id');
    }
    public function profesorAsignaturas()
    {
        return $this->hasMany(Profesor_asignatura::class, 'id_asignatura', 'id');
    }

    public function profesores()
    {
        return $this->belongsToMany(User::class, 'profesor_asignaturas')
            ->withPivot('id_curso');
    }
    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_materia', 'id');
    }
    public function notasDetalle()
    {
        return $this->hasMany(NotaDetalle::class, 'id_materia');
    }
    
}
