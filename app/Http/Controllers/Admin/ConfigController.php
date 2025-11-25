<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::user()) {

            $institucion = Config::count();
            $id = Config::first()->id ?? null;
            return view('admin.config.index', compact('institucion', 'id'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function show($id)
    {
        // Verifica si hay datos existentes en la tabla 'config'
        $institucion = Config::find($id);
        // Si hay datos, los pasamos al formulario, de lo contrario, creamos una nueva instancia vacía
        return view('admin.config.institucion', compact('institucion'));
    }
    public function create()
    {
        // Si hay datos, los pasamos al formulario, de lo contrario, creamos una nueva instancia vacía
        return view('admin.config.create');
    }
    public function store(Request $request)
    {
        if (Auth::user()) {

            // ✅ VALIDACIÓN DE DATOS
            $request->validate([
                'nombre' => 'required|string|max:255|unique:configuraciones,nombre',
                'estado' => 'required|in:0,1',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser un texto válido.',
                'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
                'nombre.unique' => 'El nombre ya está registrado en el sistema.',

                'estado.required' => 'Debe seleccionar un estado.',
                'estado.in' => 'El estado seleccionado no es válido.',

                'direccion.required' => 'La dirección es obligatoria.',
                'direccion.string' => 'La dirección debe ser un texto válido.',
                'direccion.max' => 'La dirección no puede superar los 255 caracteres.',

                'telefono.required' => 'El número de teléfono es obligatorio.',
                'telefono.string' => 'El teléfono debe ser un texto válido.',
                'telefono.max' => 'El teléfono no puede superar los 20 caracteres.',

                'email.required' => 'El email es obligatorio.',
                'email.email' => 'Debe ingresar un email válido.',
                'email.max' => 'El email no puede superar los 255 caracteres.',

                'logo.required' => 'Debe subir un archivo de logo.',
                'logo.image' => 'El archivo debe ser una imagen.',
                'logo.mimes' => 'El logo solo puede ser en formato JPEG, PNG, JPG o GIF.',
                'logo.max' => 'El archivo del logo no puede superar los 2 MB.',
            ]);


            $config = new Config();
            $config->nombre = strtoupper($request->nombre);
            $config->estado = $request->estado;
            $config->direccion = strtoupper($request->direccion);
            $config->telefono = $request->telefono;
            $config->email = $request->email;

            if ($request->hasFile('logo')) {
                $imagen = $request->file('logo');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $config->logo = $filename;
            }

            $config->save();

            return redirect()->route('admin.config.index')->with('guardar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        $institucion = Config::find($id);
        // Si hay datos, los pasamos al formulario, de lo contrario, creamos una nueva instancia vacía
        return view('admin.config.edit', compact('institucion'));
    }
    public function update(Request $request, $id)
    {
    

        if (Auth::user()) {

            $config = Config::findOrFail($id);
            $config->nombre = strtoupper($request->nombre);
            $config->estado = $request->estado;
            $config->direccion = strtoupper($request->direccion);
            $config->telefono = $request->telefono;
            $config->email = $request->email;

            if ($request->hasFile('logo')) {
                $imagen = $request->file('logo');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $config->logo = $filename;
            }


            $config->update();

            return redirect()->route('admin.config.index')->with('actualizar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
