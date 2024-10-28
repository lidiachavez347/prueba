<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    use HasFactory;
    protected $fillable = ['periodo', 'fecha_inicio', 'fecha_fin', 'estado'];

    public function nota()
    {
        return $this->hasMany(Nota::class, 'id');
    }

}
