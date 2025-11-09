<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_curso', 'paralelo','estado_curso'];

    public function estudiante(){
        return $this->hasMany(Estudiante::class,'id');
    }
    public function dimenciones()
    {
        return $this->hasMany(Dimencion::class, 'id');
    } 
}
