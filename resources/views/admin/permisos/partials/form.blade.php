<div class="form-group">
    {!! Form::label('name','Nombre') !!}
    {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el nombre del permiso']) !!}
    @error('name')
    <small class="text-danger">
        {{$message}}
    </small>
    @enderror
</div>

<div class="row">
    <div class="col">
        <div class="form group">
            {!! Form::label('description', 'Descripcion:') !!}
            {!! Form::textarea('description', null, [
            'class' => 'form-control', 'placeholder'=>'Ingrese un detalle',
            'rows' => 3, // Ajusta la altura
            'cols' => 50 // Ajusta el ancho
            ]) !!}
            @error('description')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>



<br>