<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\SendCredentialsMail;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'ADMIN', 'estado' => 1]);
        $role2 = Role::create(['name' => 'PROFESOR', 'estado' => 1]);
        $role3 = Role::create(['name' => 'TUTOR', 'estado' => 1]);
        $role4 = Role::create(['name' => 'SECRETARIA', 'estado' => 1]);
        //administrador
        Permission::create([
            'name' => 'admin.usuarios.index',
            'description' => 'Usuarios'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.roles.index',
            'description' => 'Roles'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.permisos.index',
            'description' => 'Permisos'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.config.index',
            'description' => 'Configuracion'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.grados.index',
            'description' => 'Grados'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'admin.materias.index',
            'description' => 'Materias'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.estudiantes.index',
            'description' => 'Estudiantes'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.tutores.index',
            'description' => 'Tutores'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.profesores.index',
            'description' => 'Profesores'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'admin.asignaturas.index',
            'description' => 'Asignar profesores'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'calendario.index',
            'description' => 'Calendario'
        ])->syncRoles([$role1, $role2, $role4]);


        //profesor
        Permission::create([
            'name' => 'profesor.contenidos.index',
            'description' => 'Planificacion academica'
        ])->syncRoles([$role2]);

        Permission::create([
            'name' => 'profesor.asistencias.index',
            'description' => 'Asistencias'
        ])->syncRoles([$role2]);

        Permission::create([
            'name' => 'profesor.estudiantes.index',
            'description' => 'Listado de estudiantes vista profesor'
        ])->syncRoles([$role2]);

        //asignacion de roles a diferentes registros

        $password1 = Str::random(10);
        $token1 = Str::uuid();

        $user1 = User::create([
            'imagen' => '1727891609.png',
            'nombres' => 'Rene',
            'apellidos' => 'Burgoa Sanchez',
            'genero' => 1,
            'direccion' => 'Av. San Martín N° 645, Barrio Las Delicias, Yacuiba, Tarija, Bolivia',
            'estado_user' => 1,
            'ci' => 10265847,
            'telefono' => 76829247,
            'fecha_nac' => '1961-05-10',
            'id_rol' => 1,
            'email' => 'reneburgoasanchez3@gmail.com',
            'password' => bcrypt($password1),
            'qr_token' => $token1
        ]);
        $user1->assignRole('ADMIN');

        // Enviar correo
        Mail::to($user1->email)->send(new SendCredentialsMail($user1, $password1));
    }
}
