<div class="row">
    <div class="col-6">
        <div class="form-group">
            <b>ID:</b> {{ $grado->id}} <br>
            <b>Grado:</b> {{ $grado->nombre_curso}} <br>
            <b>Paralelo:</b> {{ $grado->paralelo}} <br>
            <strong>Estado:</strong>
            <span class="badge badge-pill badge-{{ $grado->estado_curso ? 'success' : 'danger' }}">
                        {{ $grado->estado_curso ? 'ACTIVO' : 'NO ACTIVO' }}
                    </span>

        </div>
    </div>
</div>