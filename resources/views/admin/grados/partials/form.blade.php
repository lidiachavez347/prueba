<div class="row">
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('nombre_curso', 'Nombre:') !!}
            {!! Form::text('nombre_curso', null, [
            'class' => 'form-control',
            'placeholder' => 'Introducir una nuevo curso',
            ]) !!}
            @error('nombre_curso')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('paralelo', 'Paralelo:') !!}
            {!! Form::text('paralelo', null, [
            'class' => 'form-control',
            'placeholder' => 'Introducir paralelo',
            ]) !!}
            @error('paralelo')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>


</div>
<div class="row">
    <div class="col-md-6">
        <div class="form group">

            {!! Form::label('id_nivel', 'Nivel:') !!}
            {!! Form::select('id_nivel', $niveles, null, ['class' => 'form-control']) !!}

            @error('id_nivel')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('estado_curso', 'Estado:') !!}
            {!! Form::select('estado_curso', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
            'class' => 'form-control',
            '',
            ]) !!}
            @error('estado_curso')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>