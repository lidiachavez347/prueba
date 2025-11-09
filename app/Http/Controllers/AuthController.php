<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
// AuthController.php


public function loginWithQr($token)
{
    $user = User::where('qr_token', $token)->first();

    if (!$user) {
        return redirect('/login')->withErrors('QR inválido o expirado');
    }

    Auth::login($user);

    return redirect('/home');
}

}
