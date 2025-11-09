<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
        <div class="container text-center mt-5">
    <h3>Escanear código QR</h3>
    <div id="reader" style="width:300px; margin:auto;"></div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const html5QrCode = new Html5Qrcode("reader");

html5QrCode.start(
  { facingMode: "environment" },
  { fps: 10, qrbox: 250 },
  qrCodeMessage => {
    console.log("Código leído:", qrCodeMessage);
    // Accede a la URL del QR
    window.location.href = qrCodeMessage;
  }
);
</script>
</body>
</html>
