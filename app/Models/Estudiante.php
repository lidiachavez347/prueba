<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'imagen',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'genero',
        'direccion',
        'ci',
        'rude',
        'estado',
        'id_curso'
    ];
    //foranea
    public function estudiante_tutor()
    {
        return $this->hasMany(Estudiante_tutor::class, 'id');
    }
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }
    public function nota()
    {
        return $this->hasMany(Nota::class, 'id');
    }
    public function tutors()
{
    return $this->belongsToMany(Tutor::class, 'estudiante_tutors', 'id_estudiante', 'id_tutor');
}

}
