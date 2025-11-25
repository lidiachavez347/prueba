<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
        if (Auth::user()) {
            $roles = Role::all();
            return view('admin.roles.index', compact('roles'));
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
        //
        if (Auth::user()) {
            $permissions = Permission::all();
            return view('admin.roles.create', compact('permissions'));
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
            $request->validate([
                'name' => 'required|unique:roles,name',
                'estado' => 'required',
            ], [
                'name.required' => 'El campo nombre es obligatorio.',
                'name.unique' => 'Ya existe un rol con este nombre.',
                'estado.required' => 'El campo estado es obligatorio',
            ]);

            $data = $request->all();
            $data['name'] = strtoupper($data['name']);  // Convertir a mayúsculas

            $role = Role::create($data);
            $role->permissions()->sync($request->permissions);

            return redirect()->route('admin.roles.index')->with('guardar', 'ok');
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
            $role = Role::findOrFail($id); // Encuentra el rol
            $permissions = $role->permissions; // Solo obtén los permisos asignados al rol

            return view('admin.roles.show', compact('role', 'permissions'));
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
            $permissions = Permission::all();

            $role = Role::find($id);

            return view('admin.roles.edit', compact('role', 'permissions'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        if (Auth::user()) {
            $request->validate([
                'name' => 'required|unique:roles,name,' . $role->id,
                'estado' => 'required',
            ], [
                'name.required' => 'El campo nombre es obligatorio.',
                'name.unique' => 'Ya existe un rol con este nombre.',
                'estado.required' => 'El campo estado es obligatorio.',
            ]);
            
            $data = $request->all();

            // Convertir 'name' a mayúsculas antes de actualizar
            if (isset($data['name'])) {
                $data['name'] = strtoupper($data['name']);
            }

            $role->update($data);
            $role->permissions()->sync($request->permissions ?? []);


            return redirect()->route('admin.roles.index')->with('actualizar', 'ok');
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
        $role = Role::find($id);

        if ($role) {
            $role->delete();
            return response()->json(['success' => true, 'message' => 'rol eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'rol no encontrado']);
    }
}
