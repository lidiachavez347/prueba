<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{
    use HasFactory;
        protected $fillable = ['descripcion',];

        public function notas(){
        return $this->hasMany(Nota::class,'id');
    }
}
