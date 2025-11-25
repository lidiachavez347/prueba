<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrLogin extends Model
{
    use HasFactory;
    protected $fillable = ['token','user_id','confirmed', 'expires_at','ip_address', 'user_agent'];

}
