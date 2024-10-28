<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    use HasFactory;
    protected $fillable = [
        'departamento',
        'distrito',
        'dependencia',
        'turno',
        'getion',
        'nivel',
        'unidad_educativa',
        'logo',
        'direccion',
        'telefono',
        'correo',
    ];

    public function curso()
    {
        return $this->hasMany(Curso::class, 'id');
    }
}
