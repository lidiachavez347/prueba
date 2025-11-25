<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    
    protected $fillable = ['area', 'estado'];

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'id_area', 'id');
    }
}
