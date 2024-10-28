<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Admin', 'estado' => 1]);
        $role2 = Role::create(['name' => 'Director', 'estado' => 1]);
        $role3 = Role::create(['name' => 'Secretaria', 'estado' => 1]);
        $role4 = Role::create(['name' => 'Profesor', 'estado' => 1]);
//administrador
        Permission::create([
            'name' => 'admin.usuarios.index',
            'description' => 'Listado de Usuarios vista Admin'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.roles.index',
            'description' => 'Listado de Roles vista Admin'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.permisos.index',
            'description' => 'Listado de Permisos vista Admin'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.profesores.index',
            'description' => 'Listado de Profesores vista Admin'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.notas.index',
            'description' => 'Listado de notas vista Admin'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'admin.estudiantes.index',
            'description' => 'Listado de Estudiantes vista Admin'
        ])->syncRoles([$role1]);
        Permission::create([
            'name' => 'admin.cursos.index',
            'description' => 'Listado de cursos vista Admin'
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'admin.asignaturas.index',
            'description' => 'Listado de Asignaturas vista Admin'
        ])->syncRoles([$role1]);
        


//director

        Permission::create([
            'name' => 'director.cursos.index',
            'description' => 'Listado cursos vista Director'
        ])->syncRoles([$role2]);
        Permission::create([
            'name' => 'director.asignaturas.index',
            'description' => 'Listado asignaturas vista Director'
        ])->syncRoles([$role2]);
        Permission::create([
            'name' => 'director.profesores.index',
            'description' => 'Listado profesores vista Director'
        ])->syncRoles([$role2]);
//secretaria

//profesor

        User::create([
            'imagen' => '1727891535.png',
            'nombres' => 'Administrador',
            'apellidos' => 'ADMIN',
            'genero' => 1,
            'direccion' => 'admin',
            'estado_user' => 1,
            'id_rol' => 1,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
        ])->assignRole('Admin');

        User::create([
            'imagen' => '1727891609.png',
            'nombres' => 'Rene',
            'apellidos' => 'Burgoa Sanchez',
            'genero' => 1,
            'direccion' => 'admin',
            'estado_user' => 1,
            'id_rol' => 2,
            'email' => 'reneburgoa@gmail.com',
            'password' => bcrypt('123'),
        ])->assignRole('Director');

        User::create([
            'imagen' => '1727891624.png',
            'nombres' => 'Patricia',
            'apellidos' => 'Pelaez Domingo',
            'genero' => 0,
            'direccion' => 'Avenida lomas altas',
            'estado_user' => 1,
            'id_rol' => 3,
            'email' => 'patricia@gmail.com',
            'password' => bcrypt('123'),
        ])->assignRole('Secretaria');
        
    }
}
