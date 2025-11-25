<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;
    protected $table = 'gestiones';
    protected $fillable =
    [
        'gestion',
        'estado',
    ];
    public function trimestres()
    {
        return $this->hasMany(Trimestre::class, 'id_gestion', 'id');
    }
        public function cursos()
    {
        return $this->hasMany(Curso::class, 'id_gestion', 'id');
    }
}
