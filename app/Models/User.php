<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
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
        'id_rol',

        'email',
        'password',
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
        $img = Auth::user()->imagen;

        if ($img) {
            return asset('images/' . $img); // Ruta completa de la imagen
        }

        // Retornar una imagen predeterminada si el usuario no tiene una imagen asignada
        return asset('images/usuario.png');
    }
    public function adminlte_desc()
    {
        $nombres = Auth::user()->nombres;
        $apellidos =  Auth::user()->apellidos;

        return   $nombres . " " . $apellidos . " ";
        //return "role";
    }

    //llave foranea
    public function rol()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

    public function profesors()
    {
        return $this->hasMany(Profesore::class, 'id');
    }
    //public function curso()
    //{
    //     return $this->belongsTo(Curso::class, 'id_curso');
    // }
    ///public function asignatura()
    // {
    //    return $this->belongsTo(Asignatura::class, 'id_asignatura');
    //}


    /**public function materia()
    {
        return $this->hasMany(Materia::class, 'id');
    }*/
}
