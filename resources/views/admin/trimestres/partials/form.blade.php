<!-- Aquí incluimos el formulario del permiso -->
<div class="form-group">
    {!! Form::label('periodo','Periodo') !!}
    {!! Form::text('periodo', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el nombre del periodo']) !!}
    @error('periodo')
    <small class="text-danger">
        {{$message}}
    </small>
    @enderror
</div>


<div class="row">
    <!-- Selector para Fecha Inicio -->
    <div class="col">
        <div class="form-group">
            {!! Form::label('fecha_inicio', 'Fecha de Inicio:') !!}
            {!! Form::date('fecha_inicio', null, [
            'class' => 'form-control',
            'placeholder' => 'Seleccione la fecha de inicio',
            ]) !!}
            @error('fecha_inicio')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Selector para Fecha Fin -->
    <div class="col">
        <div class="form-group">
            {!! Form::label('fecha_fin', 'Fecha de Fin:') !!}
            {!! Form::date('fecha_fin', null, [
            'class' => 'form-control',
            'placeholder' => 'Seleccione la fecha de fin',
            ]) !!}
            @error('fecha_fin')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
<div class="form group">
    {!! Form::label('estado', 'Estado:') !!}
    {!! Form::select('estado',[null => 'SELECCIONE ESTADO','0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
    'class' => 'form-control',
    ]) !!}
    @error('estado')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>