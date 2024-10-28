<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.use WithPagination;
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::user()) {
            $usuarios = User::with('roles')
                ->join("roles", "roles.id", "=", "users.id_rol")
                ->whereIn("roles.name", ['Director', 'Secretaria', 'Admin'])
                ->get();
            return view('admin.usuarios.index', compact('usuarios'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()) {
            $options = Role::where('estado', '=', 1)->whereIn('name', ['Director', 'Secretaria'])->pluck('name', 'id')->toArray();
            $roles = [null => "SELECCIONE ROL"] + $options;

            return view('admin.usuarios.create', compact('roles'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

                    'roles' => 'required',
                    'genero' => 'required',
                    'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

                ]
            );

            $prueba = Role::find($request->roles);

            $user = new User();
            $user->nombres = strtoupper($request->nombres);
            $user->apellidos = strtoupper($request->apellidos);
            $user->genero = $request->genero;
            $user->direccion = strtoupper($request->direccion);
            $user->email = $request->email;
            $user->estado_user = $request->estado_user;

            $user->password = Hash::make($request->password);
            $user->id_rol = $prueba->id;



            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $user->imagen = $filename;
            }


            $user->save();

            $user->roles()->sync($request->roles);

            return redirect()->route('admin.usuarios.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        if (Auth::user()) {

            $usuario = User::find($id);
            $role = User::with('roles')
                ->join("roles", "roles.id", "=", "users.id_rol")
                ->where("users.id_rol", $id)
                ->get();


            return view('admin.usuarios.show', compact('usuario', 'role'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        if (Auth::user()) {
            $users = User::findOrFail($id);
            $options = Role::whereIn('name', ['Director', 'Secretaria', 'Admin'])->pluck('name', 'id')->toArray();
            $roles = [null => "SELECCIONE ROL"] + $options;

            $usuario  = User::find($id);
            return view('admin.usuarios.edit', compact('users', 'roles', 'usuario'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
                    'roles' => 'required',
                    'genero' => 'required',
                    'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

                ]
            );
            $prueba = Role::find($request->roles);

            $user = User::find($id);
            $user->nombres = strtoupper($request->nombres);
            $user->apellidos = strtoupper($request->apellidos);
            $user->genero = $request->genero;
            $user->direccion = strtoupper($request->direccion);
            $user->email = $request->email;
            $user->estado_user = $request->estado_user;
            $user->password = Hash::make($request->password);
            $user->id_rol = $prueba->id;

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $user->imagen = $filename;
            }

            $user->update();

            $user->roles()->sync($request->roles);
            return redirect()->route('admin.usuarios.index')->with('actualizar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        if (Auth::user()) {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('admin.usuarios.index')->with('eliminar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
