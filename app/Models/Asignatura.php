<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_asignatura', 'estado_asignatura'];

    public function profesor()
    {
        return $this->hasMany(Profesore::class, 'id');
    }
    public function nota()
    {
        return $this->hasMany(Nota::class, 'id');
    }
}
