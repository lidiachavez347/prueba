<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{
    use HasFactory;
    protected $fillable = ['descripcion','id_dimencion'];

    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_criterio', 'id');
    }
    public function dimencion()
    {
        return $this->hasMany(Dimencion::class, 'id_dimencion', 'id');
    }
}
