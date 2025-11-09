<div class="row">
    <div class="col-6">
        <div class="form-group">
            <b>ID:</b> {{ $nivel->id}} <br>
            <b>GESTION:</b> {{ $nivel->nivel}} <br>
            <b>GESTION:</b> {{ $nivel->turno}} <br>
            <b>GESTION:</b> {{ $nivel->gestion->gestion}} <br>
            <strong>Estado:</strong>
            @if ($nivel->estado == 1)
            Activo
            @else
            No Activo
            @endif

        </div>
    </div>
</div>