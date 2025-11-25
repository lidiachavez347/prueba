<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $table = 'configuraciones';
    protected $fillable = [
        'nombre',
        'logo',
        'direccion',
        'telefono',
        'email',
        'estado',
    ];
}
