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
    public function generate()
    {
        $token = Str::random(40);
        //TABAL QR LOGIN
        QrLogin::create(['token' => $token]);

        $qrUrl = url("/api/qr/confirm?token=$token");
        return response()->json(['token' => $token, 'url' => $qrUrl]);
    }

    // Confirmar desde el celular
    /*public function confirm(Request $request)
    {
        $token = $request->token;
        $qr = QrLogin::where('token', $token)->first();

        if (!$qr) {
            return response()->json(['status' => 'error', 'message' => 'Token inválido']);
        }

        $qr->update([
            'confirmed' => true,
            'user_id' => auth()->id(), // el celular debe estar logueado
        ]);

        return response()->json(['status' => 'ok']);
    }

    // Laptop pregunta si ya fue confirmado
    public function check($token)
    {
        $qr = QrLogin::where('token', $token)->first();

        if (!$qr) {
            return response()->json(['confirmed' => false]);
        }

        return response()->json(['confirmed' => $qr->confirmed, 'user_id' => $qr->user_id]);
    }*/
        // QrController.php
public function confirm(Request $request)
{
    $request->validate(['token' => 'required|string']);

    $qr = QrLogin::where('token', $request->token)->first();

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
    return redirect()->route('home')->with('success', '¡Inicio de sesión exitoso!');

}


public function check($token)
{
$qr = QrLogin::where('token', $token)
        ->where('confirmed', true)
        ->first();

    if (!$qr) {
        return response()->json(['confirmed' => false]);
    }

    $user = User::find($qr->user_id);

    if (!$user) {
        return response()->json(['confirmed' => false]);
    }

    Auth::login($user); //

    return response()->json(['confirmed' => true]);
}
public function loginWithQr($token)
{
    $qr = QrLogin::where('token', $token)
        ->where('confirmed', true)
        ->first();

    if (!$qr) {
        return response()->json(['status' => 'error', 'message' => 'Token inválido o no confirmado'], 404);
    }

    $user = User::find($qr->user_id);

    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'Usuario no encontrado'], 404);
    }

    Auth::login($user); //  Aquí se crea la sesión en la laptop

    return response()->json(['status' => 'ok', 'message' => 'Sesión iniciada']);
}


}
