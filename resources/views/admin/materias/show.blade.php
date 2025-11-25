<div class="row">
    <div class="col-6">
        <div class="form-group">
            <b>ID:</b> {{ $materia->id}} <br>
            <b>Asignatura:</b> {{ $materia->nombre_asig}} <br>
            <b>Area:</b> {{ $materia->area->area}} <br>
        
            <strong>Estado:</strong>
            <span class="badge badge-pill badge-{{ $materia->estado_asig ? 'success' : 'danger' }}">
                        {{ $materia->estado_asig ? 'ACTIVO' : 'NO ACTIVO' }}
                    </span>

        </div>
    </div>
</div>