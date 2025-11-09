<div class="row">
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('nivel', 'Nivel:') !!}
            {!! Form::text('nivel', null, [
            'class' => 'form-control',
            'placeholder' => 'Introducir una nueva nivel',
            ]) !!}
            @error('nivel')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('turno', 'Turno:') !!}
            {!! Form::text('turno', null, [
            'class' => 'form-control',
            'placeholder' => 'Introducir una nueva turno',
            ]) !!}
            @error('turno')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">

        <div class="form-group">
            {!! Form::label('id_gestion', 'Gestion:') !!}
            {!! Form::select('id_gestion', $gestiones, null, ['class' => 'form-control']) !!}
        </div>

    </div>
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('estado', 'Estado:') !!}
            {!! Form::select('estado', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
            'class' => 'form-control',
            '',
            ]) !!}
            @error('estado')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>