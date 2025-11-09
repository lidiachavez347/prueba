<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante_tutor extends Model
{
    use HasFactory;
    protected $table = 'estudiante_tutors';
    protected $fillable = ['id_estudiante','id_tutor'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }
    public function tutors()
    {
        return $this->belongsTo(Estudiante_tutor::class, 'id_tutor');
    }
}
