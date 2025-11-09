<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;
    protected $fillable = ['nombre_asig', 'estado_asig','id_area'];

    public function profesor()
    {
        return $this->hasMany(User::class, 'id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }


}
