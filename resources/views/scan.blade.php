<div id="reader" style="width: 300px; height: 300px; border: 1px solid #ccc;"></div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
window.onload = function() {
  const html5QrCode = new Html5Qrcode("reader");

  html5QrCode.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    async (qrCodeMessage) => {
      alert("Código detectado: " + qrCodeMessage);

      const token = localStorage.getItem('api_token');
      const url = `${qrCodeMessage}&token=${token}`;
      console.log("📡 Enviando solicitud a:", url);

      try {
        const response = await fetch(url, {
          method: "GET",
          headers: {
            "Accept": "application/json"
          },
        });

        // Mostrar el estado HTTP
        console.log("Estado HTTP:", response.status);

        // Leer texto (aunque no sea JSON)
        const text = await response.text();
        console.log("Respuesta del servidor:", text);

        // Intentar parsear JSON si es posible
        try {
          const data = JSON.parse(text);
          alert("✅ Servidor dice: " + (data.message || "Autenticación exitosa"));
        } catch {
          alert("⚠️ Respuesta no es JSON:\n" + text.slice(0, 100));
        }

      } catch (err) {
        console.error("Error al procesar el QR:", err);
        alert("❌ Error al procesar el QR. Revisa la consola.");
      }

      await html5QrCode.stop();
    },
    (errorMessage) => console.warn("Error QR:", errorMessage)
  ).catch(err => console.error("No se pudo iniciar el escáner:", err));
};
</script>
