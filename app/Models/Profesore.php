<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesore extends Model
{
    use HasFactory;
    protected $fillable = ['id_profesor', 'id_curso','id_asignatura'];

    //foranea
    public function profesors()
    {
        return $this->belongsTo(Profesore::class, 'id_profesor');
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
