<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_curso', 'paralelo', 'estado_curso','id_gestion'];

    public function estudiante()
    {
        return $this->hasMany(Estudiante::class, 'id');
    }
    public function profesorAsignaturas()
    {
        return $this->hasMany(Profesor_asignatura::class, 'id_curso', 'id');
    }
    public function profesores()
    {
        return $this->belongsToMany(User::class, 'profesor_asignaturas', 'id_curso', 'id_profesor','id_gestion')
            ->withPivot('id_asignatura', 'estado');
    }
    public function asistencias()
    {
        return $this->hasMany(DetalleAsistencia::class);
    }
    public function temas()
    {
        return $this->hasMany(Tema::class,'id_curso');
    }
        public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'id_gestion', 'id');
    }
}
