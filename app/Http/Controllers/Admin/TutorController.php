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
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
            $usuarios = User::whereHas('rol', function ($query) {
                $query->where('name', 'Tutor');
            })
                ->select('id', 'nombres', 'apellidos', 'estado_user', 'genero', 'imagen', 'telefono', 'direccion', 'email','created_at')
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
                    'email' => [
                        'required',
                        'email',
                        'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i',
                        'unique:users,email',
                    ],
                    'password' => 'required',
                ], [
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

            $prueba = Role::find($request->roles);

            $user = new User();
            $user->nombres = strtoupper($request->nombres);
            $user->apellidos = strtoupper($request->apellidos);
            $user->genero = $request->genero;
            $user->direccion = strtoupper($request->direccion);
            $user->email = $request->email;
            $user->estado_user = $request->estado_user;

            $user->password = bcrypt($request->password);
            $user->id_rol = $prueba->id;
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


            $user->save();

            $user->roles()->sync($request->roles);
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
                    'password' => 'nullable|min:6',
                    'estado_user' => 'required',
                    'roles' => 'required',
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'ci' => 'required',
                    'telefono' => 'required',
                    'fecha_nac' => 'required|date',
                    'password' => 'required',
                ], [
        'nombres.required' => 'El campo Nombres es obligatorio. Por favor ingrese los nombres del usuario.',
        'apellidos.required' => 'El campo Apellidos es obligatorio. Por favor ingrese los apellidos del usuario.',
        'genero.required' => 'Debe seleccionar un Género para el usuario.',
        'direccion.required' => 'El campo Dirección es obligatorio. Ingrese una dirección válida.',
        'email.required' => 'El campo Correo electrónico es obligatorio. Ingrese un email válido.',
        'password.required' => 'El campo Contraseña es obligatorio. Ingrese una contraseña.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        'estado_user.required' => 'Debe seleccionar un Estado para el usuario.',
        'roles.required' => 'Debe asignar al menos un Rol al usuario.',
        'imagen.image' => 'El archivo seleccionado debe ser una imagen.',
        'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg o gif.',
        'imagen.max' => 'La imagen no debe superar los 2 MB.',
        'ci.required' => 'El campo C.I. es obligatorio. Ingrese el número de cédula.',
        'telefono.required' => 'El campo Teléfono es obligatorio. Ingrese un número de teléfono válido.',
        'fecha_nac.required' => 'El campo Fecha de Nacimiento es obligatorio.',
        'fecha_nac.date' => 'La fecha de nacimiento debe ser una fecha válida.',
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
            $token = Str::uuid();
            $user->qr_token = $token;
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $filename = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('images'), $filename);
                $user->imagen = $filename;
            }

            $user->update();

            $user->roles()->sync($request->roles);

            $user->password_visible = $request->password;
            $fileNameqr = "qr_{$user->id}.png";
            $qrPath = public_path("qr/{$fileNameqr}");
            //INICAR SESION CON QR
            $urlLogin = url("/login/qr/{$token}");
            QrCode::format('png')->size(300)->generate($urlLogin, $qrPath);
            // Enviar QR por WhatsApp
            $this->enviarQrWhatsApp($user, $qrPath);

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

            $usuarios = User::where('id_rol', 3)->get();
            // Renderizar la vista de PDF con los datos
            $pdf = PDF::loadView('admin.pdf.tutores', compact('usuarios'));
                $pdf->setPaper('letter', 'portrait'); 
            return $pdf->stream('tutores.pdf');

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
