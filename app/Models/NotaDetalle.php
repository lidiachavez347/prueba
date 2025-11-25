<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaDetalle extends Model
{protected $table = 'nota_detalle';
    use HasFactory;
    protected $fillable = ['nota_ser', 'nota_saber', 'nota_hacer', 'nota_decidir', 'id_trimestre',
'id_materia','id_estudiante','promedio_materia','id_curso'];

public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    // 🔵 Relación: NotaDetalle pertenece a una Materia
    public function materia()
    {
        return $this->belongsTo(Asignatura::class, 'id_materia');
    }

    // 🔵 Relación: NotaDetalle pertenece a un Trimestre
    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'id_trimestre');
    }

    // 🔵 Relación: NotaDetalle pertenece a la tabla notas (si existe)
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

}
