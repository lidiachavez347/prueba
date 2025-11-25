<!-- Vista: admin/permisos/show.blade.php -->
<div class="row">
    <div class="col-6">
        <p><strong>ID:</strong> {{ $profesor->id }}</p>
        <p><strong>NOMBRES Y APELLIDOS:</strong> {{ $profesor->nombres }} {{ $profesor->apellidos }}</p>
        <p><strong>CI:</strong> {{ $profesor->ci }}</p>
        <p><strong>TELEFONO:</strong> {{ $profesor->telefono }}</p>
        <p><strong>DIRECCION:</strong> {{ $profesor->direccion }}</p>
        <p><strong>EMAIL:</strong> {{ $profesor->email }}</p>
    
        <p><strong>ESTADO:</strong><span class="badge badge-pill badge-{{ $profesor->estado_user ? 'success' : 'danger' }}">
                        {{ $profesor->estado_user ? 'ACTIVO' : 'NO ACTIVO' }}
                    </span></p>
    </div>
    <div class="col-6">
        <div class="form-group">
            @if (!empty($profesor->imagen))
            <div class="grid grid-cols-1 mt-5 mx-7">
                <b>Imagen:</b> <br>
                <img id="imagenseleccionada" style="max-height: 100px" src="{{ asset('images/' . $profesor->imagen) }}" alt="Imagen de usuario">
            </div>
            @else
            <div class="grid grid-cols-1 mt-5 mx-7">
                <img id="imagenseleccionada" style="max-height: 100px" src="{{ asset('images/default.png') }}" alt="Imagen por defecto">
            </div>
            @endif
        </div>
        <p><strong>GENERO: </strong><span class="badge badge-pill badge-{{ $profesor->genero ? 'warning' : 'info' }}">
                        {{ $profesor->genero ? 'MASCULINO' : 'FEMENINO' }}
                    </span></p>
    </div>
</div>