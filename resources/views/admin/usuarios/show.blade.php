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
            <b>Teléfono:</b> {{ $usuario->telefono ?? 'N/A' }} <br>
            <b>Estado:</b>
            @if ($usuario->estado_user == 1)
                    <span class="badge badge-pill badge-success ">ACTIVO</span>
                    @elseif ($usuario->estado_user == 0)
                    <span class="badge badge-pill badge-danger">NO ACTIVO</span>
                    @else
                    <span class="badge bg-warning">No permitido</span>
                    @endif
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
                    <img id="imagenseleccionada" style="max-height: 300px" src="{{ asset('images/default.png') }}" alt="Imagen por defecto">
                </div>
            @endif
        </div>
    </div>
</div>
