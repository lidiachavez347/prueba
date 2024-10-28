<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
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
            $permisos = Permission::all();
            return view('admin.permisos.index', compact('permisos'));
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
            $roles = Role::pluck('name', 'id'); // Esto obtiene una lista de roles con su ID y nombre
            return view('admin.permisos.create', compact('roles'));
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
        //
        //
        if (Auth::user()) {
            // Crear el permiso
            $permiso = Permission::create($request->all());

            // Asignar roles al permiso
            if ($request->has('roles')) {
                $permiso->roles()->sync($request->roles); // Asigna los roles seleccionados
            }

            return redirect()->route('admin.permisos.index')
                ->with('success', 'Permiso creado y asignado a roles correctamente.');
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
        if (Auth::user()){
            $permission = Permission::findOrFail($id); // Encuentra el permiso
            $roles = $permission->roles; // Obtén los roles que tienen este permiso
        
            return view('admin.permisos.show', compact('permission', 'roles'));
        }else{
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

            $permiso = Permission::findOrFail($id);
            $roles = Role::pluck('name', 'id'); // Obtener todos los roles
            $permisoRoles = $permiso->roles->pluck('id')->toArray(); // Obtener los IDs de los roles ya asignados al permiso

            return view('admin.permisos.edit', compact('permiso', 'roles', 'permisoRoles'));
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
            $permiso = Permission::findOrFail($id);
            $permiso->update($request->all());
        
            // Sincronizar roles
            if ($request->has('roles')) {
                $permiso->roles()->sync($request->roles); // Sincroniza los roles seleccionados
            } else {
                $permiso->roles()->sync([]); // Si no seleccionaron ningún rol, quita todos
            }
        
            return redirect()->route('admin.permisos.index')
                ->with('success', 'Permiso actualizado correctamente.');
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
            $permisos = Permission::find($id);
            $permisos->delete();
            return redirect()->route('admin.permisos.index')->with('eliminar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
