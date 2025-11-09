<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    use HasFactory;
    protected $fillable = ['token','user_id','confirmed','expires_at'];

    protected $casts = [
        'confirmed' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isExpired()
    {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }
}
