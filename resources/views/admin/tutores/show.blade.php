<div class="row">
    <div class="col-6">
        <div class="form-group">
            <b>Rol:</b> {{ $usuario->rol?->name ?? 'Sin rol asignado' }} <br>
            <b>Nombres:</b> {{ $usuario->nombres ?? 'N/A' }} <br>
            <b>Apellidos:</b> {{ $usuario->apellidos ?? 'N/A' }} <br>
            <b>Género:</b>
            @if ($usuario->genero == 1)
                MASCULINO
            @else
                FEMENINO
            @endif
            <br>
            <b>Email:</b> <a href="mailto:{{ $usuario->email }}">{{ $usuario->email ?? 'N/A' }}</a> <br>
            <b>Dirección:</b> {{ $usuario->direccion ?? 'N/A' }} <br>
            <b>Teléfono:</b> {{ $usuario->telefono ?? 'N/A' }}
        </div>
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
