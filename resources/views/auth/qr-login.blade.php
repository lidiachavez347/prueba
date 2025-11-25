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
    <div class="container text-center qr-container" style="max-width: 400px;">


    <div class="mt-2">
    <img src="{{ asset('images/logo.jpg') }}" 
        alt="Logo U.E." 
        style="width: 50px; height: auto; margin-bottom: 5px;"><b>U.E. Cnl.</b> Miguel Estenssoro T.T.
        <h3>Inicia sesión</h3>

        <!-- Contenedor flexible que se adapta al QR -->
        <div class="my-4 text-center">
            <div id="qr-wrapper" style="display: inline-block;">
                <div id="qr-container"></div>
            </div>
        </div>

        <p class="text-muted">Escanea con tu celular (ya logueado)</p>
    </div>

    <div class="mt-3">
        INICIAR DE OTRA MANERA 
        <a href="{{ route('login') }}">AQUÍ</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
let token = null;

// Generar QR desde backend
fetch('/api/qr/generate',{
    method: 'POST', headers:{"Accept":"application/json"}})
    .then(res => res.json())
    .then(data => {
        token = data.token;

        localStorage.setItem("qr_token", token);
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

                    // quí autentica la sesión en el backend /login/{token}
                    fetch(`/api/login/${token}`)
                        .then(res => res.json())
                        .then(loginData => {
                            console.log("Respuesta de login:", loginData);
                            if (loginData.status === 'ok') {
                               // Iniciar POLLING
                                window.location.href = '/home'; // Redirigir al dashboard HOME
                            } else {
                                alert("Error iniciando sesión: " + loginData.message);
                            }
                        });
                }
            })
            .catch(err => console.error("Error al verificar QR:", err));
    }, 2000);
}


//let token = $token; // el token que guardas al generar el QR
/*
setInterval(() => {
    fetch(`/api/qr/status/${token}`)
        .then(response => response.json())
        .then(data => {
            if (data.logout === true) {
                window.location.href = "/logout"; // cerrar sesión en laptop
            }
        });
}, 3000); // cada 3 segundos*/
</script>

</body>
</html>

