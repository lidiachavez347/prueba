<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Estudiante_tutor;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EstudianteController extends Controller
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
        if (Auth::user()) {
            $estudiantes = Estudiante::get();
            return view('admin.estudiantes.index', compact('estudiantes'));
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

            $options2 = Curso::where('estado_curso', '=', 1)->pluck('nombre_curso', 'id')->toArray();
            $cursos = [null => "SELECCIONE CURSO"] + $options2;

            return view('admin.estudiantes.create', compact('cursos'));  // Pasa 'cursos' a la vista

        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    // En tu controlador, por ejemplo: TutorController.php
    public function searchTutor(Request $request)
    {
        $search = $request->get('nombre_tutor');

        // Usamos la función LOWER() para hacer que la búsqueda no distinga entre mayúsculas y minúsculas.
        $tutors = Tutor::whereRaw('LOWER(nombres_tutor) LIKE ?', ['%' . strtolower($search) . '%'])->get();

        // Si se encuentran resultados, devolverlos como un JSON.
        if ($tutors->isNotEmpty()) {
            return response()->json($tutors);
        } else {
            return response()->json(['message' => 'No se encontraron tutores'], 404);
        }
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());
        if (Auth::user()) {
            // Validación de los datos del estudiante y los tutores
            $request->validate([
                // Datos del estudiante
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'genero' => 'required|string|max:10',
                'direccion' => 'nullable|string',
                'ci' => 'required|string|max:255',
                'rude' => 'required|string|max:255',
                'estado' => 'required|boolean',
                'id_curso' => 'required|exists:cursos,id',

                // Datos del tutor
                //'nombres_tutor' => 'required|array',
                'nombres_tutor.*' => 'required|string|max:255',
                //'apellidos_tutor' => 'required|array',
                'apellidos_tutor.*' => 'required|string|max:255',
                //'relacion' => 'required|array',
                'relacion.*' => 'required|string',
                //'telefono' => 'nullable|array',
                'telefono.*' => 'nullable|string|max:15',
                // 'imagen_tutor' => 'nullable|array',
                'imagen_tutor.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
                //'estado_tutor' => 'nullable|array',
                'estado_tutor.*' => 'nullable|boolean',
            ]);

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                // Generar un nombre único para la imagen
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                // Mover la imagen a la carpeta 'images' en el directorio público
                $imagen->move(public_path('images'), $filename);
                // Asignar el nombre del archivo al campo 'imagen' del estudiante
                $path_estudiante = $filename;
            } else {
                $path_estudiante = null;
            }

            // Crear el estudiante
            $estudiante = Estudiante::create([
                'imagen' => $path_estudiante,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'genero' => $request->genero,
                'direccion' => $request->direccion,
                'ci' => $request->ci,
                'rude' => $request->rude,
                'estado' => $request->estado,
                'id_curso' => $request->id_curso,
            ]);


            // Guardar tutores y asociarlos con el estudiante en la tabla intermedia
            foreach ($request->nombres_tutor as $key => $nombres_tutor) {
                // Buscar si el tutor ya existe en la base de datos
                $tutorExistente = Tutor::where('nombres_tutor', $nombres_tutor)
                    ->where('apellidos_tutor', $request->apellidos_tutor[$key])
                    ->where('telefono', $request->telefono[$key])
                    ->first();

                // Si el tutor ya existe, solo asociarlo al estudiante
                if ($tutorExistente) {
                    // Asociar el estudiante con el tutor existente
                    Estudiante_tutor::create([
                        'id_estudiante' => $estudiante->id,
                        'id_tutor' => $tutorExistente->id,
                    ]);
                } else {
                    if ($request->hasFile('imagen_tutor')) {
                        foreach ($request->file('imagen_tutor') as $key => $imagen) {
                            // Verifica si el archivo es válido
                            if ($imagen->isValid()) {
                                // Genera un nombre único para la imagen
                                $filename = time() . '_' . $key . '.' . $imagen->getClientOriginalExtension();
                                // Mueve la imagen a la carpeta 'images' en el directorio público
                                $imagen->move(public_path('images'), $filename);
                                // Asigna el nombre del archivo al campo 'imagen' del tutor
                                $path_tutor = $filename;
                            } else {
                                $path_tutor = null;
                            }

                            // Crear el tutor
                            $tutor = Tutor::create([
                                'nombres_tutor' => $request->nombres_tutor[$key],
                                'apellidos_tutor' => $request->apellidos_tutor[$key],
                                'relacion' => $request->relacion[$key],
                                'telefono' => isset($request->telefono[$key]) ? $request->telefono[$key] : null,
                                'imagen_tutor' => $path_tutor,
                                'estado_tutor' => isset($request->estado_tutor[$key]) ? $request->estado_tutor[$key] : 1, // Si no se especifica, se deja activo
                            ]);
                        }
                        // Asociar el estudiante con el tutor recién creado
                        Estudiante_tutor::create([
                            'id_estudiante' => $estudiante->id,
                            'id_tutor' => $tutor->id,
                        ]);
                    } else {
                        // Manejar el caso donde no se suben imágenes
                    }
                }
            }


            // Redirigir o retornar la respuesta
            return redirect()->route('admin.estudiantes.index')->with('success', 'Estudiante y tutores guardados exitosamente.');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        //
        if (Auth::user()) {
            $estudiante = Estudiante::with('tutors')->findOrFail($id); // Encuentra el rol
            //$estudiante = $role->permissions;
            return view('admin.estudiantes.show', compact('estudiante'));
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
            // Obtener el estudiante por su ID
            $estudiante = Estudiante::findOrFail($id);

            // Obtener los cursos disponibles (ejemplo)
            $cursos = Curso::pluck('nombre_curso', 'id'); // Pluck toma el nombre del curso y su ID

            // Retornar la vista de edición con los datos del estudiante y los cursos disponibles
            return view('admin.estudiantes.edit', compact('estudiante', 'cursos'));
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
            // Validar los datos recibidos
            $request->validate([
                'rude' => 'required|numeric',
                'ci' => 'required|numeric',
                'fecha_nacimiento' => 'required|date',
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'genero' => 'required|in:1,0',
                'direccion' => 'required|string',
                'id_curso' => 'required|exists:cursos,id', // Validar que el curso existe
                'estado' => 'required|in:0,1',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar si la imagen se sube
            ]);

            // Obtener el estudiante a actualizar
            $estudiante = Estudiante::findOrFail($id);

            // Actualizar los datos del estudiante
            $estudiante->rude = $request->rude;
            $estudiante->ci = $request->ci;
            $estudiante->fecha_nacimiento = $request->fecha_nacimiento;
            $estudiante->nombres = $request->nombres;
            $estudiante->apellidos = $request->apellidos;
            $estudiante->genero = $request->genero;
            $estudiante->direccion = $request->direccion;
            $estudiante->id_curso = $request->id_curso;
            $estudiante->estado = $request->estado;

            // Verificar si se subió una nueva imagen
            if ($request->hasFile('imagen')) {
                // Subir la nueva imagen
                $imageName = time() . '.' . $request->imagen->extension();
                $request->imagen->move(public_path('images/'), $imageName);

                // Eliminar la imagen anterior si existe
                if ($estudiante->imagen && file_exists(public_path('images/' . $estudiante->imagen))) {
                    unlink(public_path('images/' . $estudiante->imagen));
                }

                // Guardar el nombre de la nueva imagen
                $estudiante->imagen = $imageName;
            }

            // Guardar los cambios en la base de datos
            $estudiante->save();

            // Redirigir a la lista de estudiantes o a la vista de detalles del estudiante editado
            return redirect()->route('admin.estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
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
            $estudiante = Estudiante::find($id);
            $estudiante->delete();
            return redirect()->route('admin.estudiantes.index')->with('eliminar', 'ok');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
