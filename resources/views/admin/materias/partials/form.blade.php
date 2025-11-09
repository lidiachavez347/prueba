<div class="row">
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('nombre_asig', 'Nombre:') !!}
            {!! Form::text('nombre_asig', null, [
            'class' => 'form-control',
            'placeholder' => 'Introducir una nuevo curso',
            ]) !!}
            @error('nombre_asig')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('id_area', 'Area:') !!}
            {!! Form::select('id_area', $areas, null, ['class' => 'form-control']) !!}

            @error('id_area')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="form group">
    {!! Form::label('estado_asig', 'Estado:') !!}
    {!! Form::select('estado_asig', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
    'class' => 'form-control',
    '',
    ]) !!}
    @error('estado_asig')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>