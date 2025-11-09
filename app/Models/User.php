<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;
use App\Http\Controllers\Admin\ProfesorController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'imagen',
        'nombres',
        'apellidos',
        'genero',
        'direccion',
        'estado_user',
        'ci',
        'telefono',
        'fecha_nac',
        'id_rol',
        'email',
        'password',
        'qr_token',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function adminlte_image()
    {
        $img = $this->imagen;

        if ($img) {
            return asset('images/' . $img);
        }

        return asset('images/usuario.png');
    }

    public function adminlte_desc()
    {
        return $this->nombres . " " . $this->apellidos;
    }


    //llave foranea
    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

    public function asignaciones()
    {
        return $this->hasMany(Profesor_asignatura::class, 'id_profesor');
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
