<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class SmsController extends Controller
{
public function enviarSms()
    {
        $url = 'https://smsgateway24.com/getdata/addsms';
        $token = 'e9a04861c4596ea2beabd4fc925cb000';  // lo obtienes desde tu perfil de smsgateway24
        $deviceId = 12573;              // tu ID de dispositivo (lo ves en tu panel)
        $numero = '+59176709828';       // número boliviano
        $mensaje = 'Hola desde Laravel con SMSGateway24!';

        // Enviar petición POST
        $response = Http::asForm()->post($url, [
            'token' => $token,
            'device' => $deviceId,
            'phone' => $numero,
            'message' => $mensaje,
            'urgent' => 1, // opcional
        ]);

        // Mostrar respuesta del servidor
        if ($response->successful()) {
            return response()->json([
                'status' => 'ok',
                'data' => $response->json()
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al enviar SMS',
                'details' => $response->body()
            ], 500);
        }
    }

}
