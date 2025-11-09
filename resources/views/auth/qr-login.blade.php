{{-- resources/views/qr-login.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .qr-container {
            margin-top: 10%;
            background: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>

<body>
        <div class="container text-center qr-container">
            <div class="text-center mt-5">
            <h3>Inicia sesión escaneando este QR</h3>
            <div id="qr-container" class="my-4"> aqui el qr</div>
            <p class="text-muted">Escanea con tu celular (ya logueado)</p>
        </div>
        INICIAR DE OTRA MANERA <a href="{{ route('login') }}">AQUÍ</a>
                </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
let token = null;

// Generar QR desde backend
fetch('/api/qr/generate')
    .then(res => res.json())
    .then(data => {
        token = data.token;
        new QRCode(document.getElementById("qr-container"), data.url);
        console.log("QR generado con token:", data);
        pollStatus(); // Iniciar polling
    })
    .catch(err => console.error("Error al verifical QR.:", err));

// Función de polling
function pollStatus() {
    console.log("Iniciando polling con token:", token);
    const interval = setInterval(() => {
        fetch(`/api/qr/check/${token}`)
            .then(res => res.json())
            .then(data => {
                if (data.confirmed) {
                    clearInterval(interval);
                    console.log("QR confirmado. Iniciando sesión...");

                    // 🔥 Aquí autentica la sesión en el backend
                    fetch(`/qr/login/${token}`)
                        .then(res => res.json())
                        .then(loginData => {
                            console.log("Respuesta de login:", loginData);
                            if (loginData.status === 'ok') {
                                window.location.href = 'home'; // Redirigir al dashboard HOME
                            } else {
                                alert("Error iniciando sesión: " + loginData.message);
                            }
                        });
                }
            })
            .catch(err => console.error("Error al verificar QR:", err));
    }, 2000);
}
</script>

</body>
</html>

