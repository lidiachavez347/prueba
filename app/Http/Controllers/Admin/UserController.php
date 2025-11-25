<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Estudiante;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use PDF;
use App\Http\Controllers\WhatsAppController;
use App\Mail\SendCredentialsMail;
use Illuminate\Validation\Rule;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.use WithPagination;
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    //GENERAR LA CONTRASEÑA ALEATORIA
    private function generarPassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function index()
    {
        if (Auth::user()) {
            $usuarios = User::with('rol')
                ->whereHas('rol', function ($query) {
                    $query->whereIn('name', ['DIRECTOR', 'SECRETARIA']);
                })
                ->get();

            return view('admin.usuarios.index', compact('usuarios'));
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
        if (Auth::check()) {
            $options = Role::where('estado', 1)
                ->whereIn('name', ['DIRECTOR', 'SECRETARIA'])
                ->pluck('name', 'id')
                ->toArray();

            $roles = collect($options)->prepend('SELECCIONE ROL', null)->toArray();

            return view('admin.usuarios.create', compact('roles'));
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
        //dd($request->all());
        if (Auth::user()) {
            $request->validate(
                [
                    'nombres' => 'required|string|max:255',
                    'apellidos' => 'required|string|max:255',
                    'genero' => 'required',
                    'direccion' => 'required|string|max:255',
                    'ci' => 'required|string|max:20',
                    'telefono' => 'required|string|max:15',
                    'fecha_nac' => 'required|date',
                    'email' => [
                        'required',
                        'email',
                        'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i',
                        'unique:users,email',
                    ],
                    'estado_user' => 'required',
                    'roles' => 'required',
                    'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                    'fecha_nac.date' => 'La fecha de nacimiento debe tener un formato válido.',
                    'email.required' => 'El correo electrónico es obligatorio.',
                    'email.email' => 'Debe ingresar un correo electrónico válido.',
                    'email.regex' => 'Solo se permiten correos con dominio @gmail.com.',
                    'email.unique' => 'Este correo ya está registrado.',
                    'estado_user.required' => 'Debe seleccionar un estado.',
                    'roles.required' => 'Debe asignar un rol al usuario.',
                    'imagen.required' => 'Debe subir una imagen.',
                    'imagen.image' => 'El archivo debe ser una imagen válida.',
                    'imagen.mimes' => 'La imagen debe ser de tipo JPEG, PNG, JPG o GIF.',
                    'imagen.max' => 'La imagen no debe superar los 2 MB.',
                    'password.required' => 'La contraseña es requerida'
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
            $prueba = Role::find($request->roles);

            //CREAR USUARIO
            $user = new User();
            $user->nombres = strtoupper($request->nombres);
            $user->apellidos = strtoupper($request->apellidos);
            $user->genero = $request->genero;
            $user->direccion = strtoupper($request->direccion);
            $user->email = $request->email;
            $user->estado_user = $request->estado_user;

            //GENERA CONTRASEÑA
            $password = $this->generarPassword(8);
            $user->password = bcrypt($password);
            //$password1 = $request->password;
            $user->id_rol = $prueba->id;
            $user->ci = $request->ci;
            $user->telefono = $request->telefono;
            $user->fecha_nac = $request->fecha_nac;

            $token = Str::uuid();
            $user->qr_token = $token; //para inicio de sesion con QR

            //GUARDAR IMAGEN EN EL ARCHIVO IMAGEN
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
            //$this->enviarContrasenaWhatsApp($user->telefono, $password);

            return redirect()->route('admin.usuarios.index')
                ->with('guardar', 'ok')
                ->with('mensaje', 'Usuario creado correctamente. Se envió un correo de verificación.');
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }




    public function show($id)
    {
        if (Auth::user()) {
            $usuario = User::with('rol')->find($id);

            if (!$usuario) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            return view('admin.usuarios.show', compact('usuario'));
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
            $users = User::findOrFail($id);

            $options = Role::whereIn('name', ['SECRETARIA', 'DIRECTOR'])
                ->pluck('name', 'id')
                ->toArray();

            $roles = collect($options)->prepend('SELECCIONE ROL', null)->toArray();

            return view('admin.usuarios.edit', compact('users', 'roles'));
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
        // Primero obtener al usuario

        if (Auth::user()) {
            $user = User::findOrFail($id);
            $request->validate(
                [
                    'nombres' => 'required|string|max:255',
                    'apellidos' => 'required|string|max:255',
                    'genero' => 'required',
                    'direccion' => 'required|string|max:255',
                    'ci' => 'required|string|max:20',
                    'telefono' => 'required|string|max:15',
                    'fecha_nac' => 'required|date',

                    // EMAIL (permite mantener el mismo)
                    'email' => [
                        'required',
                        'email',
                        'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i',
                        Rule::unique('users', 'email')->ignore($user->id),
                    ],

                    'estado_user' => 'required',
                    'roles' => 'required',
                    // Imagen opcional
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    // Contraseña opcional pero si se llena DEBE ser segura
                    'password' => [
                        'required'

                    ],
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
                    'password.regex' => 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos.',
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

            // Verificar si la contraseña fue modificada y no está vacía
            if ($request->filled('password')) {
                $password = $request->password;
                $user->password = Hash::make($password);

                // Generar un nuevo qr_token (puedes usar cualquier lógica para generarlo)
                $token = Str::uuid();
                $user->qr_token = $token; //para inicio de sesion con QR
                //$user->qr_token = Str::random(30); // ejemplo con Str helper

                // Guardar usuario con nueva contraseña y token
                $user->save();
                
                $user->password_visible = $request->password;

                $fileNameqr = "qr_{$user->id}.png";
                //ruta
                $qrPath = public_path("qr/{$fileNameqr}");
                //INICAR SESION CON QR
                $urlLogin = url("/login/qr/{$token}");
                QrCode::format('png')->size(300)->generate($urlLogin, $qrPath);
                // Enviar QR por WhatsApp
                $this->enviarQrWhatsApp($user, $qrPath);

                // Generar QR con el nuevo token y guardarlo localmente
                //$fileNameqr = "qr_{$user->id}.png";
                //$qrPath = public_path("qr/{$fileNameqr}");
                // QrCode::format('png')->size(300)->generate($user->qr_token, $qrPath);

                // Enviar QR por WhatsApp (define esta función para que mande el archivo)
                //$this->enviarQrWhatsApp($user, $qrPath);

                // Enviar contraseña por WhatsApp (define esta función para enviar texto)
                //$this->enviarContrasenaWhatsApp($user->telefono, $password);
            } else {
                // Si no modificó contraseña, solo actualiza otros datos
                $user->save();
            }


            $user->roles()->sync($request->roles);


            return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente']);
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
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        }
        return response()->json(['success' => false, 'message' => 'Usuario no encontrado']);
    }



    public function usuariosPDF()
    {
        if (Auth::check()) {
            // Obtener el estudiante con sus tutores relacionados

            $usuarios = User::whereIn('id_rol', [1, 4])->get();
            // Renderizar la vista de PDF con los datos
            $pdf = PDF::loadView('admin.pdf.usuarios', [
                'usuarios' => $usuarios
            ])->setPaper('letter', 'landscape');

            return $pdf->stream('usuarios.pdf');
        } else {
            // Si no está autenticado
            Auth::logout();
            return redirect()
                ->route('login')
                ->with('error', 'Debe iniciar sesión.');
        }
    }



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
            //actualizar el qr_url despues de guardar
            $user = User::find($user->id);
            $user->qr_url = $qrUrl;
            $user->save();
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
