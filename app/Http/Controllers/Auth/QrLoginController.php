<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\QrSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Events\LoginByQrEvent;
use App\Events\QrLoginConfirmed;
use App\Models\QrLogin;
use Memcached;
use Hashids\Hashids;

class QrLoginController extends Controller
{
    // Generar un nuevo código QR
    public function generate(Request $request)
    {
        \Log::info("Método GENERATE ejecutado");

        $token = Str::random(40);
        //TABAL QR LOGIN
        QrLogin::create([
            'token' => $token,
            'confirmed' => false,
            'user_id' => null,
            'ip_address' => request()->ip(),
            'user_agent' => $request->userAgent(),
            'expires_at' => Carbon::now()->addMinutes(2),
        ]);

        $qrUrl = url("/api/qr/confirm?token=$token");
        return response()->json(['token' => $token, 'url' => $qrUrl]);
    }

    // Confirmar desde el celular


    // Confirmar desde el celular
    public function confirm(Request $request)
    {
        $token = $request->token;

        $request->validate(['token' => 'required|string']);

        $qr = QrLogin::where('token', $token)->first();

        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'Token inválido'], 404);
        }

        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        $qr->update([
            'user_id' => $user->id,
            'confirmed' => true,
        ]);

        // Redirigir a home con mensaje de éxito
        return redirect()->route('home')->with('success', '¡QR CONFIRMADO DESDE EL CELULAR!');
    }

    //AQUI INICIA SESION LA ALAPTOP
    public function check($token)
    {
        $qr = QrLogin::where('token', $token)
            ->where('confirmed', true)
            //->where('expires_at', '>', now())
            ->first();

        if (!$qr) {
            //Auth::loginUsingId($qr->user_id); //
            return response()->json(['confirmed' => false]);
        }

        /*$user = User::find($qr->user_id);

    if (!$user) {
        return response()->json(['confirmed' => false]);
    }*/

        //Auth::login($user); //
        Auth::loginUsingId($qr->user_id); //  Aquí se crea la sesión en la laptop

        return response()->json(['confirmed' => true]);
    }



    public function loginWithQr($token)
    {
        $qr = QrLogin::where('token', $token)
            ->where('confirmed', true)
            //->where('expires_at', '>', now())
            ->first();

        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'Token inválido o no confirmado'], 404);
        }

        $user = User::find($qr->user_id);

        /*  if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado'], 404);
    }*/
        // Guardar el token en la sesión

        Auth::login($user); //  Aquí se crea la sesión en la laptop
        session(['qr-token' => $token]);
        return response()->json(['status' => 'ok', 'message' => 'Sesión iniciada']);
    }





    //--------------------------------------------------------------
    //  Listar sesiones abiertas del usuario
    public function list(Request $request)
    {
        $sessions = QrLogin::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'sessions' => $sessions
        ]);
    }

    // Eliminar una sesión específica (desloguear laptop)
    public function destroy(Request $request, $id)
    {
        $session = QrLogin::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $session) {
            return response()->json(['message' => 'Sesión no encontrada'], 404);
        }

        $session->delete();

        return response()->json([
            'message' => 'Sesión eliminada / laptop deslogueada'
        ]);
    }
    // LAPTOP DETECTA SI LA SESION FUE ELIMINADA DESDE EL CELULAR
    /* public function status($token)
    {
        $session = QrLogin::where('token', $token)->first();

        if (!$session) {
            // La sesión fue eliminada desde el celular
            // Auth::logout();

            return response()->json(['logout' => true]);
        }

        return response()->json(['logout' => false]);
    }*/
}
