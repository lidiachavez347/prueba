<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_curso', 'estado_curso','id_colegio'];

    public function estudiante(){
        return $this->hasMany(Estudiante::class,'id');
    }
    //foranea
    public function colegio()
    {
        return $this->belongsTo(Colegio::class, 'id_colegio');
    }
    public function profesor()
    {
        return $this->hasMany(Profesore::class, 'id');
    }
}
