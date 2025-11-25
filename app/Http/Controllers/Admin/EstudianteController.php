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
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Http;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


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
                    return [$curso->id => $curso->nombre_curso . ' - ' . $curso->paralelo . ' - ' . $curso->gestion->gestion];
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
            //dd($request);
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
            ],[
                 // ------- Estudiante -------
    'imagen_es.image' => 'La imagen del estudiante debe ser un archivo válido.',
    //'imagen_es.mimes' => 'La imagen del estudiante debe ser JPG, JPEG, PNG o GIF.',
    'nombres_es.required' => 'El nombre del estudiante es obligatorio.',
    'apellidos_es.required' => 'El apellido del estudiante es obligatorio.',
    'fecha_nac_es.required' => 'La fecha de nacimiento del estudiante es obligatoria.',
    'fecha_nac_es.date' => 'La fecha de nacimiento del estudiante no es válida.',
    'genero_es.required' => 'Debe seleccionar el género del estudiante.',
    'ci_es.required' => 'El CI del estudiante es obligatorio.',
    'rude_es.required' => 'El RUDE del estudiante es obligatorio.',
    'estado_es.required' => 'Debe seleccionar el estado del estudiante.',
    'id_curso.required' => 'Debe seleccionar un curso.',
    //'id_curso.exists' => 'El curso seleccionado no existe.',

    // ------- Tutor -------
    'imagen.image' => 'La imagen del tutor debe ser un archivo válido.',
    //'imagen.mimes' => 'La imagen del tutor debe ser JPG, JPEG, PNG o GIF.',
    'nombres.required' => 'El nombre del tutor es obligatorio.',
    'apellidos.required' => 'El apellido del tutor es obligatorio.',
    'genero.required' => 'Debe seleccionar el género del tutor.',
    'direccion.required' => 'La dirección del tutor es obligatoria.',
    'estado_user.required' => 'Debe seleccionar el estado del tutor.',
    'ci.required' => 'El CI del tutor es obligatorio.',
    'telefono.required' => 'El número de teléfono del tutor es obligatorio.',
    'fecha_nac.required' => 'La fecha de nacimiento del tutor es obligatoria.',
    'fecha_nac.date' => 'La fecha de nacimiento del tutor no es válida.',
    'password.required' => 'La contraseña es obligatoria.',
    'email.required' => 'El correo electrónico es obligatorio.',
    'email.email' => 'Debe ingresar un correo válido.',
    'email.unique' => 'El correo electrónico ya está registrado.',

            ]);

            // Manejo de la imagen del estudiante
            $path_estudiante = null;

            if ($request->hasFile('imagen_es')) {
                $imagen = $request->file('imagen_es');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $path_estudiante = $filename;
            }

            $tutorex = User::where('nombres', $request->nombres)
                ->where('apellidos', $request->apellidos)
                ->where('telefono', $request->telefono)
                ->first();

            // Buscar tutor existente
            if ($tutorex) {

                $request->validate([

    //'password' => 'required|min:6',
    'nombres' => 'required',
    'apellidos' => 'required',
    // otros campos...
]);

                // Si el tutor ya existe, crear la relación con el estudiante
                $estudiante = Estudiante::create([
                    'imagen_es' => $path_estudiante,
                    'nombres_es' => strtoupper($request->nombres_es),
                    'apellidos_es' => strtoupper($request->apellidos_es),
                    'fecha_nac_es' => $request->fecha_nac_es,
                    'genero_es' => $request->genero_es,
                    'ci_es' => $request->ci_es,
                    'rude_es' => $request->rude_es,
                    'estado_es' => $request->estado_es,
                    'id_curso' => $request->id_curso,
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
                $prueba = Role::where('id', 3)->first();

                $token = Str::uuid();

                $user = new User();
                $user->nombres = strtoupper($request->nombres);
                $user->apellidos = strtoupper($request->apellidos);
                $user->telefono = $request->telefono;
                $user->genero = $request->genero;
                $user->id_rol = $prueba->id;
                $user->ci = $request->ci;
                $user->fecha_nac = $request->fecha_nac;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->direccion = strtoupper($request->direccion);
                $user->imagen = $path_tutor;
                $user->estado_user = $request->estado_user;
                $user->qr_token = $token; //para inicio de sesion con QR

                $user->save();
                $user->roles()->sync($request->roles);


                // Enviar notificación de verificación de correo electrónico
                $user->sendEmailVerificationNotification();

                //ENVIAR MENSAJE POR WHATSAP CON EL QR Y LA CONTRASEÑA
                $user->password_visible = $request->password;

                // Generar QR local
                $fileNameqr = "qr_{$user->id}.png";
                $qrPath = public_path("qr/{$fileNameqr}");

                //INICAR SESION CON QR
                $urlLogin = url("/login/qr/{$token}");
                QrCode::format('png')->size(300)->generate($urlLogin, $qrPath);

                // Enviar QR por WhatsApp
                $this->enviarQrWhatsApp($user, $qrPath);


                Estudiante::create([
                    'imagen_es' => $path_estudiante,
                    'nombres_es' => strtoupper($request->nombres_es),
                    'apellidos_es' => strtoupper($request->apellidos_es),
                    'fecha_nac_es' => $request->fecha_nac_es,
                    'genero_es' => $request->genero_es,
                    'ci_es' => $request->ci_es,
                    'rude_es' => $request->rude_es,
                    'estado_es' => $request->estado_es,
                    'id_curso' => $request->id_curso,
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
            $estudiante = Estudiante::with('tutor', 'curso')->findOrFail($id);

        // Convertimos el tutor a un array para el @forelse de la vista
        $tutores = $estudiante->tutor ? [$estudiante->tutor] : [];

            return view('admin.estudiantes.show', compact('estudiante','tutores'));
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
            ],[
    'rude_es.required'      => 'El RUDE es obligatorio.',
    //'rude_es.numeric'       => 'El RUDE debe contener solo números.',

    'ci_es.required'        => 'El CI es obligatorio.',
   // 'ci_es.numeric'         => 'El CI debe contener solo números.',

    'fecha_nac_es.required' => 'La fecha de nacimiento es obligatoria.',
    'fecha_nac_es.date'     => 'La fecha de nacimiento no es válida.',

    'nombres_es.required'   => 'Los nombres son obligatorios.',
    //'nombres_es.string'     => 'Los nombres deben ser texto.',
   // 'nombres_es.max'        => 'Los nombres no pueden exceder 255 caracteres.',

    'apellidos_es.required' => 'Los apellidos son obligatorios.',
    //'apellidos_es.string'   => 'Los apellidos deben ser texto.',
    //'apellidos_es.max'      => 'Los apellidos no pueden exceder 255 caracteres.',

    'genero_es.required'    => 'El género es obligatorio.',
    //'genero_es.in'          => 'El género seleccionado no es válido.',

    'id_curso.required'     => 'Debe seleccionar un curso.',
    //'id_curso.exists'       => 'El curso seleccionado no existe.',

    'estado_es.required'    => 'Debe seleccionar un estado.',
    //'estado_es.in'          => 'El estado seleccionado no es válido.',

    'imagen_es.image'       => 'El archivo subido debe ser una imagen.',
    //'imagen_es.mimes'       => 'Las imágenes permitidas son: jpeg, png, jpg, gif, svg.',
    //'imagen_es.max'         => 'La imagen no debe superar los 2MB.',
]);

            // Obtener el estudiante a actualizar
            $estudiante = Estudiante::findOrFail($id);
            $estudiante->nombres_es = $request->nombres_es;
            $estudiante->apellidos_es = $request->apellidos_es;
            $estudiante->fecha_nac_es = $request->fecha_nac_es;
            $estudiante->genero_es = $request->genero_es;
            $estudiante->ci_es = $request->ci_es;
            $estudiante->rude_es = $request->rude_es;
            $estudiante->estado_es = $request->estado_es;
            $estudiante->id_curso = $request->id_curso;

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
            return response()->json(['success' => true, 'message' => 'Estudiante actualizado correctamente']);
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
            $estudiante = Estudiante::with('tutor', 'curso')->get();
            // Renderizar la vista de PDF con los datos
            $pdf = PDF::loadView('admin.pdf.estudiantes', compact('estudiante'));
            $pdf->setPaper('letter', 'landscape');


            return $pdf->stream('estudiantes.pdf');

            //Descargar el PDF
            //return $pdf->download('estudiantes.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
    }



    ///////////////////////////////////////////////////////////////////

    public function verificarEmail(Request $request)
    {
        $email = $request->email;

        // Validar formato
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'valid' => false,
                'message' => 'Formato de email inválido ❌'
            ]);
        }

        // Buscar si ya existe
        $existe = User::where('email', $email)->where('id', '!=', $request->id)->exists();

        if ($existe) {
            return response()->json([
                'valid' => false,
                'message' => 'El correo ya está registrado ❌'
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Correo disponible ✔️'
        ]);
    }
    private function subirQr($qrPath)
    {
        $uploadedFile = Cloudinary::upload($qrPath, [
            'folder' => 'qr_usuarios',
            'overwrite' => true,
            'resource_type' => 'image'
        ]);

        $url = $uploadedFile->getSecurePath(); // URL HTTPS pública
        return $url;
    }
    private function enviarQrWhatsApp($user)
    {
        try {
            $tokenBot = env('TEXMEBOT_API_TOKEN'); // Tu API key
            $telefono = preg_replace('/[^0-9]/', '', $user->telefono); // Solo números
            $telefono = '+591' . ltrim($telefono, '0');
            // dd($telefono);
            $mensaje = "👋 Bienvenido/a {$user->nombres}!\n\n" .
                "Has sido registrado correctamente en el sistema académico 📚\n\n" .
                "🔑 Contraseña: {$user->password_visible}\n" .
                "📧 Correo: {$user->email}\n\n" .
                "Atentamente,\nSistema de Gestión Académica";

            //URL PUBLICA
            // Subir QR a Cloudinary y obtener URL pública
            $qrPathLocal = public_path("qr/qr_{$user->id}.png");
            $qrUrl = $this->subirQr($qrPathLocal);
            // Codificar mensaje para URL
            $mensajeUrl = urlencode($mensaje);
            // http://api.textmebot.com/send.php?recipient=[phone number]&apikey=[your premium apikey]&text=[text to send]

            $url = "http://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&text={$mensajeUrl}&file={$qrUrl}&json=yes";
            // URL GET
            // $url = "http://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&text={$mensajeUrl}&json=yes";

            // Hacer la petición GET
            $response = Http::get($url);

            // Revisar respuesta
            if ($response->failed()) {
                \Log::error('❌ Error al enviar WhatsApp: ' . $response->body());
            } else {
                \Log::info("✅ Mensaje enviado correctamente a {$telefono}");
            }
        } catch (\Exception $e) {
            \Log::error('⚠️ Excepción al enviar WhatsApp: ' . $e->getMessage());
        }
    }
}
