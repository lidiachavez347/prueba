<?php

namespace App\Http\Controllers\Director;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    use HasRoles;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $roles = Role::all();
        if (Auth::user()) {
        $profesores = User::with('roles')->join("roles", "roles.id", "=", "users.id_rol")
            ->select("users.nombres", "users.apellidos", 'users.id', "users.direccion", 'roles.name', 'users.estado_user', 'users.genero')
            ->where("roles.name", "=", 'Profesor')
            ->get();
        return view('director.profesores.index', compact('profesores'));
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { //abrir formulario para un nuevo registro
        if (Auth::user()) {
        $options = Role::where('estado', '=', 1)->where('name', 'Profesor')->pluck('name', 'id', 'estado')->toArray();
        $roles = [null => "SELECCIONE ROL"] + $options;
        $options2 = Curso::where('estado_curso', '=', 1)->where('nombre_curso', '!=', 'TODOS')->pluck('nombre_curso', 'id')->toArray();
        $cursos = [null => "SELECCIONE CURSO"] + $options2;

        return view('director.profesores.create', compact('roles', 'cursos'));
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }

    public function store(Request $request)
    { //guardar BD los Registro
        if (Auth::user()) {
        $request->validate(
            [
                'nombres' => 'required',
                'apellidos' => 'required',
                'genero' => 'required',
                'direccion' => 'required',
                'email' => 'required',
                'password' => 'required',
                'estado_user' => 'required',
                'genero' => 'required',
                'roles' => 'required',
                'id_curso' => 'required',
               
            ]
        );
        $prueba = Role::find($request->roles);

        $profesor = new User();
        $profesor->nombres = strtoupper($request->nombres);
        $profesor->apellidos = strtoupper($request->apellidos);
        $profesor->genero = $request->genero;
        $profesor->direccion = strtoupper($request->direccion);
        $profesor->email = $request->email;
        $profesor->estado_user = $request->estado_user;
        $profesor->password = Hash::make($request->password);
        $profesor->id_rol = $prueba->id;
        $profesor->id_curso = $request->id_curso;
        $profesor->save();
        $profesor->roles()->sync($request->roles);

        return redirect()->route('director.profesores.index')->with('guardar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }

    public function edit($id)
    { // Abrir un formualrio para Edicion de un registro BD
        //MEJORARLO
        if (Auth::user()) {
        $options1 = Role::where('name', 'Profesor')->pluck('name', 'id')->toArray();;
        $roles = [null => "SELECCIONE ROL"] + $options1;

        $options2 = Curso::pluck('nombre_curso', 'id')->toArray();;
        $cursos = [null => "SELECCIONE CURSO"] + $options2;

        $profesor = User::findOrFail($id);
        $usuario  = User::find($id);
        return view('director.profesores.edit', compact('profesor', 'roles', 'cursos','usuario'));
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
    public function update(Request $request, $id)
    { //Actualizar el regsitro en el BD
        //dd($request);
        if (Auth::user()) {
        $request->validate(
            [
                'nombres' => 'required',
                'apellidos' => 'required',
                'genero' => 'required',
                'direccion' => 'required',
                'email' => 'required',
                'password' => 'required',
                'genero' => 'required',
                'roles' => 'required',
                'id_curso' => 'required',
                'estado_user' => 'required',
            ]
        );

        $prueba = Role::find($request->roles);

        $profesor = User::findOrFail($id);
        $profesor->nombre_user = strtoupper($request->input('nombres'));
        $profesor->apellido_user = strtoupper($request->input('apellidos'));
        $profesor->genero = $request->input('genero');
        $profesor->direccion = strtoupper($request->input('direccion'));
        $profesor->email = $request->input('email');
        $profesor->password = Hash::make($request->input('password'));
        //$usuario->password = Hash::make($request->password);
        $profesor->estado_user = $request->input('estado_user');
        $profesor->id_rol = $prueba->id;
        $profesor->id_curso = $request->input('id_curso');
        $profesor->update();
        $profesor->roles()->sync($request->roles);

        return redirect()->route('director.profesores.index')->with('actualizar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }

    public function destroy($id)
    {
        // Eliminar un registro BD
        if (Auth::user()) {
        $profesor = User::findOrFail($id);
        $profesor->delete();
        return redirect()->route('director.profesores.index')->with('eliminar', 'ok');
    } else {
        Auth::logout();
        return redirect()->back();
    }
    }
}
