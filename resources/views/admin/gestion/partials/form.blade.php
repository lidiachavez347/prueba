<div class="row">
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('gestion', 'Nombres:') !!}
            {!! Form::text('gestion', null, [
            'class' => 'form-control',
            'placeholder' => 'Introducir una nueva gestion',
            ]) !!}
            @error('gestion')
            <span class="text-danger">{{ $message }}</span>
            @enderror
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
<br>
</div>