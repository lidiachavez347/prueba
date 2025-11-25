<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'titulo',
        'detalle',
        'archivo',
        'imagen',
        'video',
        'estado',
        'id_asignatura',
        'id_trimestre',
        'id_curso',
        'avance'
    ];

    //foranea
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura');
    }
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'id_trimestre');
    }
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

}
