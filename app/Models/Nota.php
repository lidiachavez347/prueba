<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;
    protected $fillable = [
        'nota',
        'id_estudiante',
        'id_criterio',
        'id_materia',
        'id_trimestre',
        'id_dimencion',
        'detalle',
        'fecha',
        'id_curso'
    ];

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
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    protected static function booted()
    {
        static::created(function ($nota) {
            self::actualizarResumen($nota);
        });

        static::updated(function ($nota) {
            self::actualizarResumen($nota);
        });
    }

    public static function actualizarResumen($nota)
    {
        // 1. Recuperar todas las notas del estudiante para esa materia y trimestre
        $notas = self::where('id_estudiante', $nota->id_estudiante)
            ->where('id_materia', $nota->id_materia)
            ->where('id_trimestre', $nota->id_trimestre)
            ->where('id_curso', $nota->id_curso)
            ->get();

        // 2. Calcular promedios por dimensión
        $nota_ser     = $notas->where('id_dimencion', 1)->avg('nota') ?? 0;
        $nota_saber   = $notas->where('id_dimencion', 2)->avg('nota') ?? 0;
        $nota_hacer   = $notas->where('id_dimencion', 3)->avg('nota') ?? 0;
        $nota_decidir = $notas->where('id_dimencion', 4)->avg('nota') ?? 0;

        // 3. Promedio general de la materia
        $promedio_materia = ($nota_ser + $nota_saber + $nota_hacer + $nota_decidir);

        // 4. Guardar en nota_detalle
        \App\Models\NotaDetalle::updateOrCreate(
            [
                'id_estudiante' => $nota->id_estudiante,
                'id_materia'    => $nota->id_materia,
                'id_trimestre'  => $nota->id_trimestre,
                'id_curso'      => $nota->id_curso,
            ],
            [
                'nota_ser'         => $nota_ser,
                'nota_saber'       => $nota_saber,
                'nota_hacer'       => $nota_hacer,
                'nota_decidir'     => $nota_decidir,
                'promedio_materia' => $promedio_materia,
            ]
        );
    }
}
