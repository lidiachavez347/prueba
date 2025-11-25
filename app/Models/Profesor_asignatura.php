<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor_asignatura extends Model
{
    use HasFactory;
    protected $table = 'profesor_asignaturas';

    protected $fillable = ['id_profesor', 'id_curso','id_asignatura','estado'];

    //foranea
    public function profesor()
    {
        return $this->belongsTo(User::class, 'id_profesor');
    }
    public function profesore()
    {
        return $this->belongsTo(User::class, 'id_profesor');
    }
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura');
    }

}
