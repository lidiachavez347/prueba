<div class="row">
    <div class="col-6">
        <div class="form-group">
            <b>ID:</b> {{ $grado->id}} <br>
            <b>Grado:</b> {{ $grado->nombre_curso}} <br>
            <b>paralelo:</b> {{ $grado->paralelo}} <br>
            <strong>Estado:</strong>
            @if ($grado->estado_curso == 1)
            Activo
            @else
            No Activo
            @endif

        </div>
    </div>
</div>