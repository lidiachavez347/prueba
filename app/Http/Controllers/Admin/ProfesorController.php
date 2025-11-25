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
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;


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
                'email' => [
                        'required',
                        'email',
                        'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i',
                        'unique:users,email',
                    ],
                'password' => 'required',
                ], [
        // Imagen
        'imagen.image' => 'El archivo debe ser una imagen válida.',
        'imagen.mimes' => 'La imagen debe ser en formato: jpeg, png, jpg o gif.',
        'imagen.max' => 'La imagen no puede superar los 2 MB.',

        // Datos personales
        'nombres.required' => 'Los nombres son obligatorios.',
        'apellidos.required' => 'Los apellidos son obligatorios.',
        'genero.required' => 'Debe seleccionar un género.',
        'direccion.required' => 'La dirección es obligatoria.',
        'estado_user.required' => 'Debe seleccionar un estado.',
        'ci.required' => 'El número de CI es obligatorio.',
        'telefono.required' => 'El teléfono es obligatorio.',

        // Fecha nacimiento
        'fecha_nac.required' => 'La fecha de nacimiento es obligatoria.',
        'fecha_nac.date' => 'Debe ingresar una fecha válida.',

        // Email
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Debe ingresar un correo válido.',
        'email.regex' => 'El correo debe ser una cuenta Gmail (@gmail.com).',
        'email.unique' => 'El correo ya está registrado.',

        // Password
        'password.required' => 'La contraseña es obligatoria.',
    ]
        );
        //CHEQUEAR EL GMAIL COM MAILBOXLAYER
        $response = Http::get('https://apilayer.net/api/check', [
            'access_key' => env('MAILBOXLAYER_KEY'),
            'email' => $request->email,
        ]);

        $data = $response->json();
        //VALIDAR RESPUESTA
        if (!$data['format_valid'] || !$data['mx_found'] || !$data['smtp_check']) {
            return back()
                ->withErrors(['email' => 'El correo ingresado no parece existir o no es válido.'])
                ->withInput();
        }
        //fin verificar el correo
        $prueba = Role::find(2);
        // Crear usuario
        $user = new User();
        $user->nombres = strtoupper($request->nombres);
        $user->apellidos = strtoupper($request->apellidos);
        $user->genero = $request->genero;
        $user->direccion = strtoupper($request->direccion);
        $user->email = $request->email;
        $user->estado_user = $request->estado_user;

        $user->password = Hash::make($request->password);
        $user->id_rol =  $prueba->id;
        $user->ci = $request->ci;
        $user->telefono = $request->telefono;
        $user->fecha_nac = $request->fecha_nac;
        $token = Str::uuid();
        $user->qr_token = $token; //para inicio de sesion con QR
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $filename = time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images'), $filename);
            $user->imagen = $filename;
        }
        //GUARDAR USUARIO
        $user->save();
        $user->roles()->sync($request->roles);
        ///ENVIAR mensaje de verificacion de correo
        $user->sendEmailVerificationNotification();

        //eviar correo con las credenciales
        //Mail::to($user->email)->send(new SendCredentialsMail($user, $password1));

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
            $profesor = User::findorFail($id);

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
            $profesor = User::findOrFail($id);

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
                    'required',
                    'email',
                    'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],

                'estado_user' => 'required',
                'roles' => 'required',
                'genero' => 'required',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'ci' => 'required',
                'telefono' => 'required',
                'fecha_nac' => 'required|date',
                'password' => 'required|string|min:8',
            ],
                [
                    'nombres.required' => 'El campo nombres es obligatorio.',
                    'apellidos.required' => 'El campo apellidos es obligatorio.',
                    'genero.required' => 'Debe seleccionar un género.',
                    'direccion.required' => 'La dirección es obligatoria.',
                    'ci.required' => 'El número de cédula es obligatorio.',
                    'telefono.required' => 'El teléfono es obligatorio.',
                    'fecha_nac.required' => 'Debe ingresar la fecha de nacimiento.',

                    'email.required' => 'El correo electrónico es obligatorio.',
                    'email.email' => 'Debe ingresar un correo electrónico válido.',
                    'email.regex' => 'Solo se permiten correos con dominio @gmail.com.',
                    'email.unique' => 'Este correo ya está registrado.',

                    'estado_user.required' => 'Debe seleccionar un estado.',
                    'roles.required' => 'Debe asignar un rol al usuario.',

                    'imagen.image' => 'El archivo debe ser una imagen válida.',
                    'imagen.mimes' => 'La imagen debe ser JPEG, PNG, JPG o GIF.',
                    'imagen.max' => 'La imagen no debe superar los 2 MB.',

                    // Mensajes de contraseña
                    'password.required' => 'La contraseña es requerida',
                    'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
                    //'password.regex' => 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos.',
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

        $password = $request->password;
        $user->password = Hash::make($password);

        // Generar un nuevo qr_token (puedes usar cualquier lógica para generarlo)
        $token = Str::uuid();
        $user->qr_token = $token; //para inicio de sesion con QR
        //$user->qr_token = Str::random(30); // ejemplo con Str helper

        // Guardar usuario con nueva contraseña y token
        $user->update();
        $user->roles()->sync($request->roles);
        //ENVIAR MENSAJE POR WHATSAP CON EL QR Y LA CONTRASEÑA
        $user->password_visible = $request->password;
        $fileNameqr = "qr_{$user->id}.png";
        $qrPath = public_path("qr/{$fileNameqr}");
        //INICAR SESION CON QR
        $urlLogin = url("/login/qr/{$token}");
        QrCode::format('png')->size(300)->generate($urlLogin, $qrPath);
        // Enviar QR por WhatsApp
        $this->enviarQrWhatsApp($user, $qrPath);



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

            $usuarios = User::where('id_rol', 2)->get();
            // Renderizar la vista de PDF con los datos
            $pdf = PDF::loadView('admin.pdf.profesores', compact('usuarios'));
            $pdf->setPaper('letter', 'portrait'); 
            return $pdf->stream('profesores.pdf');

            // Descargar el PDF
            //return $pdf->download('usuarios.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }
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
