<h2>Bienvenido/a {{ $user->nombres }} {{ $user->apellidos }}</h2>

<p>Se ha creado tu cuenta en el sistema.</p>

<p><strong>Correo:</strong> {{ $user->email }}</p>
<p><strong>Contraseña temporal:</strong> {{ $password }}</p>
<p><strong>Token QR:</strong> {{ $user->qr_token }}</p>

<p>Por favor, cambia tu contraseña al ingresar por primera vez.</p>

