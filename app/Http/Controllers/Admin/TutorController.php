<?php

namespace App\Http\Controllers\Admin;
use PDF;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Estudiante;


class TutorController extends Controller
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
                ->where("roles.name", 'Tutor')
                ->select('users.id', 'users.nombres', 'users.apellidos', 'users.estado_user', 'users.genero', 'users.imagen','users.telefono','users.direccion')
                ->get();
            return view('admin.tutores.index', compact('usuarios'));
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
            $options = Role::where('estado', '=', 1)->where('name', 'Tutor')->pluck('name', 'id')->toArray();
            $roles = [null => "SELECCIONE ROL"] + $options;

            return view('admin.tutores.create', compact('roles'));
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
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'nombres' => 'required',
                    'apellidos' => 'required',
                    'genero' => 'required',
                    'direccion' => 'required',
                    'estado_user' => 'required',
                    'roles' => 'required',
                    'genero' => 'required',
                    'ci' => 'required',
                    'telefono' => 'required',
                    'fecha_nac' => 'required|date',
                    'email' => 'required',
                    'password' => 'required',
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
            $user->ci = $request->ci;
            $user->telefono = $request->telefono;
            $user->fecha_nac = $request->fecha_nac;

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $user->imagen = $filename;
            }


            $user->save();

            $user->roles()->sync($request->roles);

            return redirect()->route('admin.tutores.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (Auth::user()) {
            $usuario = User::with('rol')->find($id);

            if (!$usuario) {
                return response()->json(['error' => 'Tutor no encontrado'], 404);
            }

            return view('admin.tutores.show', compact('usuario'));
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
            $options = Role::where('name', 'Tutor')->pluck('name', 'id')->toArray();
            $roles = [null => "SELECCIONE ROL"] + $options;

            return view('admin.tutores.edit', compact('users', 'roles'));
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
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'ci' => 'required',
                    'telefono' => 'required',
                    'fecha_nac' => 'required|date',
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

            $user->roles()->sync($request->roles);

            return response()->json(['success' => true, 'message' => 'Tutor actualizado correctamente']);
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // Controlador (después de eliminar)
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Tutor eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'Tutor no encontrado']);
    }

    //////////////////////////////////////////
    
    public function tutoresPDF()
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados

            $usuarios = User::where('id_rol',3)->get();
            // Renderizar la vista de PDF con los datos
            $pdf = PDF::loadView('admin.pdf.tutores', compact('usuarios'));

            return $pdf->stream('tutores.pdf');

            // Descargar el PDF
            //return $pdf->download('usuarios.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
