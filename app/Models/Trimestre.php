<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    use HasFactory;
    protected $fillable = ['periodo', 'fecha_inicio', 'fecha_fin', 'estado','id_gestion'];
    
    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'id_gestion');
    }

}
