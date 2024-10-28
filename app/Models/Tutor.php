<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;
    protected $table = 'tutores';

    protected $fillable = [
        'imagen_tutor',
        'nombres_tutor',
        'apellidos_tutor',

        'telefono',
        'relacion',
        'estado_tutor'
    ];

    public function estudiante_tutor()
    {
        return $this->hasMany(Estudiante_tutor::class, 'id');
    }
}
