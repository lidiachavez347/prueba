<?php

namespace App\Console\Commands;

use App\Models\Estudiante;
use App\Models\NotaDetalle;
use App\Models\Trimestre;
use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnviarNotasWhatsApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enviar-notas-whats-app';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    //protected $signature = 'notas:enviar-whatsapp';
    protected $description = 'Enviar boletines PDF del trimestre a tutores por WhatsApp';

    public function handle()
    {
        $hoy = now('America/La_Paz')->toDateString();


        // 1. Buscar trimestres que terminan hoy
        $trimestres = Trimestre::whereDate('fecha_fin', $hoy)->get();
        //dd($trimestres);
        if ($trimestres->isEmpty()) {
            $this->info('No hay trimestres que terminen hoy.');
            return;
        }
        //dd($trimestres);
        foreach ($trimestres as $trimestre) {
            $this->info("Procesando {$trimestre->periodo}...");

            // 2. Obtener todos los estudiantes
            $estudiantes = Estudiante::with('tutor')->get();

            foreach ($estudiantes as $estudiante) {
                // 3. Obtener notas del estudiante para este trimestre
                $notas = NotaDetalle::where('id_estudiante', $estudiante->id)
                    ->where('id_trimestre', $trimestre->id)
                    ->with(['materia'])
                    ->get();

                // 4. Agrupar por materia
                $notasAgrupadas = [];

                foreach ($notas as $nota) {
                    $materia = $nota->materia->nombre_asig ?? 'SIN MATERIA';
                    $periodo = $nota->trimestre->periodo ?? 'SIN TRIMESTRE';

                    $notasAgrupadas[$materia][$periodo] = $nota->promedio_materia;
                }
                //nombre del archibo
                //$fileNameqr = "qr_{$estudiante->id}.png"; //qr que va dentro del pdf
                //ruta donde se guarda la imagen de arrivva 75

                //$qrPathLocal = public_path("qr/qr_{$ntutor->id}.png");
                //$rutaQR = $uploadedFile->getSecurePath();
                //$rutaQR = public_path("images/{$ntutor->token}");

                //$urlboletin = url("boletin/estudiante/{$estudiante->id}/pdf");
                //$urlLogin = url("/login/qr/{$token}");
                //QrCode::format('png')->size(150)->generate($urlboletin, $rutaQR);
                $fechaHoy = now()->format('Y-m-d');
                $rutaPDF = public_path("archivos/boletines_{$fechaHoy}_{$estudiante->id}_{$trimestre->id}.pdf");
                $trim = $notas->pluck('trimestre.periodo')->unique();
                // 6. Generar PDF incluyendo el QR
                $user = $estudiante->tutor;
                $ntutor = public_path("qr/qr_{$user->id}.png");
                $pdf = Pdf::setPaper('a4', 'landscape')
                    ->loadView('pdf.enviar', [
                        'estudiante' => $estudiante,
                        'notasAgrupadas' => $notasAgrupadas,
                        'trimestres' => $trim,
                        'qrRuta' => $ntutor,
                    ]);

                //dd($urlboletin);
                $pdf->save($rutaPDF);
                /// $urlPDF = $this->subirPdf($rutaPDF);

                // 6. Enviar PDF a cada tutor
                $tutor = $estudiante->tutor;
                if ($tutor) {
                    //dd($tutor->telefono);
                    $telefono = $tutor->telefono;
                    //$telefono = $tutor->telefono; // Debe estar en formato internacional
                    $this->enviarWhatsApp($telefono, $rutaPDF, $estudiante, $trimestre);
                } else {
                    $this->info("El estudiante {$estudiante->id} no tiene tutor asignado.");
                }

                $this->info("Boletín enviado a tutores de {$estudiante->nombres_es} {$estudiante->apellidos_es}");
            }
        }
    }
    private function subirPdf($path)
    {
        $uploadedFile = Cloudinary::upload($path, [
            'folder' => 'qr_usuarios',
            'overwrite' => true,
            'resource_type' => 'auto', //auto o image si funciona
            'type' => 'upload',
        ]);

        return $uploadedFile->getSecurePath(); // URL HTTPS pública

    }
    private function enviarWhatsApp($telefono, $estudiante, $trimestre)
    {
        try {
            $tokenBot = env('TEXMEBOT_API_TOKEN'); // Tu API key
            $telefono = preg_replace('/[^0-9]/', '', $telefono); // Solo números
            $telefono = '+591' . ltrim($telefono, '0');
            // dd($telefono);
            $mensaje = "Estimado padre de familia,\n\n" .
                "Se ha culminado el trimestre académico.\n" .
                "Adjuntamos la libreta electrónica del estudiante " .
                "{$estudiante->nombres_es} {$estudiante->apellidos_es}.\n\n" .
                "Atentamente,\nSistema de Gestión Académica.";
            $fechaHoy = now()->format('Y-m-d');
            $qrPathLocal = public_path("archivos/boletines_{$fechaHoy}_{$estudiante->id}_{$trimestre->id}.pdf");
            $qrUrl = "https://adminlte.ueestenssoro.com/{$qrPathLocal}";
            // Codificar mensaje para URL
            $mensajeUrl = urlencode($mensaje);
            // http://api.textmebot.com/send.php?recipient=[phone number]&apikey=[your premium apikey]&text=[text to send]
            
            /*https://api.textmebot.com/send.php?recipient=[phone number]&apikey=[your apikey]&document=[url of document]
You can optionally add the following parameters:

&test=[Text to show below the document]
&filename=[Filename to show in the whatsapp message]
&json=[yes/no] to receive the response in json format

'filename' => "boletin_{$estudiante->nombres_es}.pdf",*/

$filename = urlencode("boletin_{$estudiante->nombres_es}.pdf");

//$url = "https://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&document={$qrUrl}&json=yes";
$this->info("URL del PDF : $qrUrl");
//$url = "https://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&document={$qrUrl}&filename={$filename}&json=yes";
$url = "https://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&document={$qrUrl}&test={$mensajeUrl}&filename={$filename}&json=yes";
//$url = "http://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&text={$mensajeUrl}&json=yes";
//$url = "https://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&document={$qrUrl}&test={$mensajeUrl}&filename={$filename}&json=yes";

//$url = "https://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&test={$mensajeUrl}&document={$qrUrl}&filename={$filename}.pdf}&json=yes";
           // $url = "https://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&text={$mensajeUrl}&json=yes";

            //$url2 = "http://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&text={$mensajeUrl}&file={$qrUrl}&json=yes";
            // URL GET
            // $url = "http://api.textmebot.com/send.php?recipient={$telefono}&apikey={$tokenBot}&text={$mensajeUrl}&json=yes";

            // Hacer la petición GET
            $response = Http::get($url);
//\Log::info("Respuesta TextMeBot: " . $response->body());
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
