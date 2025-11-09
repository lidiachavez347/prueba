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

        if (Auth::user()) {
            $request->validate(
                [
                    'name' => 'required|unique:permissions,name',
                    'description' => 'required',
                ],
                [
                    'name.required' => 'El campo nombre es obligatorio.',
                    'description.required' => 'El campo descripcion es obligatorio.',
                ]
            );
            // Convertir a mayúsculas
            $data = $request->all();
            $data['name'] = $data['name'];
            $data['description'] = strtoupper($data['description']);

            // Crear el permiso con datos en mayúsculas
            $permiso = Permission::create($data);

            // Asignar roles al permiso
            if ($request->has('roles')) {
                $permiso->roles()->sync($request->roles);
            }

            return redirect()->route('admin.permisos.index')
                ->with('guardar', 'ok');
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
        if (Auth::check()) { // Verifica si el usuario está autenticado
            $permission = Permission::findOrFail($id); // Encuentra el permiso o lanza 404
            $roles = $permission->roles; // Obtén los roles que tienen este permiso

            return view('admin.permisos.show', compact('permission', 'roles'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()){
        $permiso = Permission::findOrFail($id);
        $roles = Role::pluck('name', 'id'); // Obtener todos los roles
        $permisoRoles = $permiso->roles->pluck('id')->toArray(); // Obtener los IDs de los roles asignados al permiso

        return view('admin.permisos.edit', compact('permiso', 'roles', 'permisoRoles'));
          } else {
        Auth::logout();
        return redirect()->back();
    }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()){
               $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'description' => 'required|string|max:255',
            'roles' => 'nullable|array'
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.unique' => 'Ya existe un permiso con este nombre.',
            'description.required' => 'La descripción es obligatoria.',
        ]);
        $permiso = Permission::findOrFail($id);

        $permiso->update([
            'name' => $request->name,
            'description' => strtoupper($request->description)
        ]);

        // Sincronizar roles
        if ($request->has('roles')) {
            $permiso->roles()->sync($request->roles);
        } else {
            $permiso->roles()->sync([]);
        }

        return response()->json(['success' => true, 'message' => 'Permiso actualizado correctamente']);
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
            return response()->json(['success' => true, 'message' => 'Permiso eliminado correctamente']);
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
