<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimencion extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'ponderacion'];


    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_dimencion', 'id');
    }
}
