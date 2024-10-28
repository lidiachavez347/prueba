<div class="form group">
    {!! Form::label('nombre_curso', 'Nombre:') !!}
    {!! Form::text('nombre_curso', null, ['class' => 'form-control', 'placeholder' => 'Nombre Curso']) !!}
    @error('nombre_curso')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="form group">
    {!! Form::label('estado_curso', 'Estado:') !!}
    {!! Form::select('estado_curso', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
    'class' => 'form-control',
    ]) !!}

    @error('estado_curso')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>