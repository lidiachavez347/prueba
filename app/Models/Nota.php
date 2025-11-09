<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;
    
  protected $fillable = ['nota', 'id_estudiante','id_criterio','id_materia','id_trimestre','detalle','id_dimencion','fecha'];

    //foranea
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }
        public function criterio()
    {
        return $this->belongsTo(Criterio::class, 'id_criterio');
    }
        public function materia()
    {
        return $this->belongsTo(Asignatura::class, 'id_materia');
    }
        public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'id_trimestre');
    }
        public function dimencion()
    {
        return $this->belongsTo(Dimencion::class, 'id_dimencion');
    }

}
