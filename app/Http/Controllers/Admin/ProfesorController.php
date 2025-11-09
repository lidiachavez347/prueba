<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profesore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PDF;

class ProfesorController extends Controller
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
            // Obtener todos los usuarios que tienen rol de 'Profesor' junto con sus datos de la tabla 'profesores'
            $profesores = User::where('id_rol', 2)->get();
            return view('admin.profesores.index', compact('profesores'));
        } else {
            Auth::logout();
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()) {
            $options = Role::where('estado', '=', 1)->where('name', 'Profesor')->pluck('name', 'id')->toArray();
            $roles = [null => "SELECCIONE ROL"] + $options;

            return view('admin.profesores.create', compact('roles'));
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

        // Validación
        $request->validate(
            [
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nombres' => 'required',
                'apellidos' => 'required',
                'genero' => 'required',
                'direccion' => 'required',
                'estado_user' => 'required',
                'ci' => 'required',
                'telefono' => 'required',
                'fecha_nac' => 'required|date',
                'email' => 'required',
                'password' => 'required',
            ]
        );

        // Crear usuario
        $user = new User();
        $user->nombres = strtoupper($request->nombres);
        $user->apellidos = strtoupper($request->apellidos);
        $user->genero = $request->genero;
        $user->direccion = strtoupper($request->direccion);
        $user->email = $request->email;
        $user->estado_user = $request->estado_user;

        $user->password = Hash::make($request->password);
        $user->id_rol = 2;
        $user->ci = $request->ci;
        $user->telefono = $request->telefono;
        $user->fecha_nac = $request->fecha_nac;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $filename = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images'), $filename);
            $user->imagen = $filename;
        }


        return redirect()->route('admin.profesores.index')->with('success', 'Profesor creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (Auth::check()) {
            // Verifica si el usuario está autenticado
            // Obtener el profesor con la relación a la tabla 'profesores' y los roles
            $profesor = User::join('profesores', 'users.id', '=', 'profesores.id_user')
                ->join('roles', 'roles.id', '=', 'users.id_rol')
                ->where('roles.name', 'Profesor')
                ->where('users.id', $id) // Filtrar por el ID del profesor
                ->select(
                    'users.id',
                    'users.nombres',
                    'users.apellidos',
                    'users.ci',
                    'users.telefono',
                    'users.email',
                    'users.estado_user',
                    'profesores.nivel',
                    'users.direccion'
                )
                ->first();

            // Verifica si el profesor fue encontrado
            if (!$profesor) {
                return redirect()->route('admin.profesores.index')->with('error', 'Profesor no encontrado');
            }

            // Pasar los datos del profesor a la vista de detalles
            return view('admin.profesores.show', compact('profesor'));
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
        if (Auth::user()) {
            $profesor = Profesore::with('user')
                ->whereHas('user', function ($query) use ($id) {
                    $query->where('id', $id);
                })
                ->select(
                    'profesores.nivel',
                    'users.id',
                    'users.nombres',
                    'users.apellidos',
                    'users.ci',
                    'users.telefono',
                    'users.direccion',
                    'users.email',
                    'users.estado_user',
                    'users.imagen',
                    'users.password',
                    'users.id_rol',
                    'users.genero',
                    'users.fecha_nac'
                )
                ->join('users', 'profesores.id_user', '=', 'users.id')
                ->first();
                
            //dd($profesor);            // Obtener los roles disponibles para el dropdown
            $options = Role::where('name', 'Profesor')->pluck('name', 'id')->toArray();
            $roles = [null => "SELECCIONE ROL"] + $options;

            // Verificar si se encontró el profesor
            if (!$profesor) {
                return redirect()->route('admin.profesores.index')->with('error', 'Profesor no encontrado.');
            }

            return view('admin.profesores.edit', compact('profesor', 'roles'));
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
        //dd($request);
        $user = User::findOrFail($id);
        $request->validate(
            [
                'nombres' => 'required',
                'apellidos' => 'required',
                'genero' => 'required',
                'direccion' => 'required',
                'email' => [

                    'email',
                    Rule::unique('users')->ignore($user->id),
                ],

                'estado_user' => 'required',
                'roles' => 'required',
                'genero' => 'required',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'ci' => 'required',
                'telefono' => 'required',
                'fecha_nac' => 'required|date',
            ]
        );
        $prueba = Role::find($request->roles);
        // Actualizar datos del usuario
        $user = User::find($id);
        $user->nombres = strtoupper($request->nombres);
        $user->apellidos = strtoupper($request->apellidos);
        $user->genero = $request->genero;
        $user->direccion = strtoupper($request->direccion);
        $user->email = $request->email;
        $user->estado_user = $request->estado_user;
        $user->password = Hash::make($request->password);
        $user->id_rol = $prueba->id;
        $user->ci = $request->ci;
        $user->telefono = $request->telefono;
        $user->fecha_nac = $request->fecha_nac;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $filename = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images'), $filename);
            $user->imagen = $filename;
        }

        $user->update();

        // Actualizar datos del profesor
        $profesor = Profesore::where('id_user', $user->id)->firstOrFail();
        $profesor->nivel = $request->nivel;
        $profesor->estado_prof = $request->estado_user;
        $profesor->update();



        return redirect()->route('admin.profesores.index')->with('success', 'Profesor actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'Usuario no encontrado']);
    }
    public function profesoresPDF()
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados

            $usuarios = User::where('id_rol',2)->get();
            // Renderizar la vista de PDF con los datos
            $pdf = PDF::loadView('admin.pdf.profesores', compact('usuarios'));

            return $pdf->stream('profesores.pdf');

            // Descargar el PDF
            //return $pdf->download('usuarios.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
