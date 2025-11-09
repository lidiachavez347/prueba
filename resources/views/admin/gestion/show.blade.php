<div class="row">
    <div class="col-6">
        <div class="form-group">
            <b>ID:</b> {{ $gestion->id}} <br>
            <b>GESTION:</b> {{ $gestion->gestion}} <br>
            <strong>Estado:</strong>
            @if ($gestion->estado == 1)
            Activo
            @else
            No Activo
            @endif

        </div>
    </div>
</div>