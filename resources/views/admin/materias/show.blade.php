<div class="row">
    <div class="col-6">
        <div class="form-group">
            <b>ID:</b> {{ $materia->id}} <br>
            <b>Asignatura:</b> {{ $materia->nombre_asig}} <br>
            <b>Area:</b> {{ $materia->area->area}} <br>
        
            <strong>Estado:</strong>
            @if ($materia->estado_asig == 1)
            Activo
            @else
            No Activo
            @endif

        </div>
    </div>
</div>