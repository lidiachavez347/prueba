<?php

namespace App\Http\Controllers\Admin;

use PDF;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Estudiante_tutor;
use App\Models\Nivel;
use App\Models\Paralelo;
use App\Models\Tutor;
use App\Models\Tutore;
use App\Models\User;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
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
        if (Auth::user()) {

            $options2 = Curso::where('estado_curso', '=', 1)
                ->get()
                ->mapWithKeys(function ($curso) {
                    return [$curso->id => $curso->nombre_curso . ' - ' . $curso->paralelo];
                })
                ->toArray();
            $cursos = [null => "SELECCIONE CURSO"] + $options2;


            return view('admin.estudiantes.create', compact('cursos'));  // Pasa 'cursos' a la vista

        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    // En tu controlador, por ejemplo: TutorController.php
    public function autocomplete(Request $request)
    {
        $query = $request->get('query');  // Obtener la consulta de búsqueda

        if (!$query) {
            return response()->json([]);  // Retornar vacío si no hay consulta
        }

        // Buscar en los campos 'nombres' y 'apellidos' de la tabla 'users'
        $tutors = User::whereRaw('LOWER(nombres) LIKE ?', ['%' . strtolower($query) . '%'])
            ->orWhereRaw('LOWER(apellidos) LIKE ?', ['%' . strtolower($query) . '%'])
            ->select('id', 'nombres', 'apellidos', 'direccion', 'genero', 'telefono', 'imagen', 'ci', 'estado_user', 'email', 'fecha_nac', 'password')
            ->get();

        // Retornar los resultados como JSON
        return response()->json($tutors);
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()) {
            // Validación de los datos del estudiante y los tutores
            $request->validate([
                // Datos del estudiante
                'imagen_es' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nombres_es' => 'required|string|max:255',
                'apellidos_es' => 'required|string|max:255',
                'fecha_nac_es' => 'required',
                'genero_es' => 'required',
                'ci_es' => 'required',
                'rude_es' => 'required',
                'estado_es' => 'required',
                'id_curso' => 'required|exists:cursos,id',

                // Datos del tutor
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'genero' => 'required',
                'direccion' => 'required|string',
                'estado_user' => 'required',
                'ci' => 'required',
                'telefono' => 'required',
                'fecha_nac' => 'required',

                'password' => 'required',
                'email' => 'required',
            ]);

            // Manejo de la imagen del estudiante
            $path_estudiante = null;
            if ($request->hasFile('imagen_es')) {
                $imagen = $request->file('imagen_es');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $path_estudiante = $filename;
            }


            $estudiante = Estudiante::create([
                'imagen_es' => $path_estudiante,
                'nombres_es' => $request->nombres_es,
                'apellidos_es' => $request->apellidos_es,
                'fecha_nac_es' => $request->fecha_nac_es,
                'genero_es' => $request->genero_es,
                'ci_es' => $request->ci_es,
                'rude_es' => $request->rude_es,
                'estado_es' => $request->estado_es,
                'id_curso' => $request->id_curso,

            ]);

            // Buscar tutor existente
            $tutorex = User::where('nombres', $request->nombres)
                ->where('apellidos', $request->apellidos)
                ->where('telefono', $request->telefono)
                ->first();

            if ($tutorex) {
                // Si el tutor ya existe, crear la relación con el estudiante

                Estudiante_tutor::create([
                    'id_estudiante' => $estudiante->id,
                    'id_tutor' => $tutorex->id,
                ]);
            } else {
                // Si el tutor no existe, crearlo y asociarlo al estudiante
                $path_tutor = null;
                if ($request->hasFile('imagen')) {
                    $imagen = $request->file('imagen');
                    $filename = time() . '.' . $imagen->getClientOriginalExtension();
                    $imagen->move(public_path('images'), $filename);
                    $path_tutor = $filename;
                }

                $user = User::create([
                    'nombres' => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'telefono' => $request->telefono,
                    'genero' => $request->genero,
                    'id_rol' => 3,
                    'ci' => $request->ci,
                    'fecha_nac' => $request->fecha_nac,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'direccion' => $request->direccion,
                    'imagen' => $path_tutor,
                    'estado_user' => $request->estado_user,
                ]);

                Estudiante_tutor::create([
                    'id_estudiante' => $estudiante->id,
                    'id_tutor' => $user->id,
                ]);
            }

            // Redirigir con mensaje de éxito
            return redirect()->route('admin.estudiantes.index')->with('success', 'Estudiante y tutor guardados exitosamente.');
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
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados
            $estudiante = Estudiante::with('tutores')->findOrFail($id);
            $tutores = $estudiante->tutores;
            return view('admin.estudiantes.show', compact('estudiante', 'tutores'));
        } else {
            // Si el usuario no está autenticado, cerrar sesión y redirigir
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
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

            $options2 = Curso::where('estado_curso', '=', 1)
                ->get()
                ->mapWithKeys(function ($curso) {
                    return [$curso->id => $curso->nombre_curso . ' - ' . $curso->paralelo];
                })
                ->toArray();
            $cursos = [null => "SELECCIONE CURSO"] + $options2;



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
        //dd($request);
        if (Auth::user()) {
            // Validar los datos recibidos

            $request->validate([
                'rude_es' => 'required|numeric',
                'ci_es' => 'required|numeric',
                'fecha_nac_es' => 'required|date',
                'nombres_es' => 'required|string|max:255',
                'apellidos_es' => 'required|string|max:255',
                'genero_es' => 'required|in:1,0',
                'id_curso' => 'required|exists:cursos,id', // Validar que el curso existe
                'estado_es' => 'required|in:0,1',
                'imagen_es' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar si la imagen se sube
            ]);

            // Obtener el estudiante a actualizar
            $estudiante = Estudiante::findOrFail($id);

            // Actualizar los datos del estudiante
            $estudiante->rude_es = $request->rude_es;
            $estudiante->ci_es = $request->ci_es;
            $estudiante->fecha_nac_es = $request->fecha_nac_es;
            $estudiante->nombres_es = $request->nombres_es;
            $estudiante->apellidos_es = $request->apellidos_es;
            $estudiante->genero_es = $request->genero_es;
            $estudiante->id_curso = $request->id_curso;
            $estudiante->estado_es = $request->estado_es;


            // Verificar si se subió una nueva imagen
            if ($request->hasFile('imagen_es')) {
                // Subir la nueva imagen
                $imageName = time() . '.' . $request->imagen_es->extension();
                $request->imagen_es->move(public_path('images/'), $imageName);

                // Eliminar la imagen anterior si existe
                if ($estudiante->imagen_es && file_exists(public_path('images/' . $estudiante->imagen_es))) {
                    unlink(public_path('images/' . $estudiante->imagen_es));
                }

                // Guardar el nombre de la nueva imagen
                $estudiante->imagen_es = $imageName;
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

            return response()->json(['success' => true, 'message' => 'Estudiane eliminado correctamente']);
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
    public function exportarPDF()
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados
            $estudiante = Estudiante::with(['tutores', 'curso'])->get();

            // Renderizar la vista de PDF con los datos
            $pdf = PDF::loadView('admin.pdf.estudiantes', compact('estudiante'));
            $pdf->setPaper('A4', 'landscape');

            return $pdf->stream('estudiantes.pdf');

            //Descargar el PDF
            //return $pdf->download('estudiantes.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }
}
