<!-- Vista: admin/permisos/show.blade.php -->
<div class="row">
    <div class="col-6">
        <p><strong>ID:</strong> {{ $profesor->id }}</p>
        <p><strong>NOMBRES Y APELLIDOS:</strong> {{ $profesor->nombres }} {{ $profesor->apellidos }}</p>
        <p><strong>CI:</strong> {{ $profesor->ci }}</p>
        <p><strong>TELEFONO:</strong> {{ $profesor->telefono }}</p>
        <p><strong>DIRECCION:</strong> {{ $profesor->direccion }}</p>
        <p><strong>EMAIL:</strong> {{ $profesor->email }}</p>
        <p><strong>NIVEL:</strong>{{ $profesor->nivel }}</p>
        <p><strong>ESTADO:</strong> {{ $profesor->estado_user == 1 ? 'Activo' : 'Inactivo' }}</p>
    </div>
    <div class="col-6">
        <div class="form-group">
            @if (!empty($usuario->imagen))
            <div class="grid grid-cols-1 mt-5 mx-7">
                <b>Imagen:</b> <br>
                <img id="imagenseleccionada" style="max-height: 100px" src="{{ asset('images/' . $usuario->imagen) }}" alt="Imagen de usuario">
            </div>
            @else
            <div class="grid grid-cols-1 mt-5 mx-7">
                <img id="imagenseleccionada" style="max-height: 100px" src="{{ asset('images/default.png') }}" alt="Imagen por defecto">
            </div>
            @endif
        </div>
    </div>
</div>