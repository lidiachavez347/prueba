<!-- Aquí incluimos el formulario del permiso -->
<div class="form-group">
    {!! Form::label('titulo','Titulo') !!}
    {!! Form::text('titulo', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el titulo']) !!}
    @error('titulo')
    <small class="text-danger">
        {{$message}}
    </small>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('detalle','Detalle') !!}
    {!! Form::text('detalle', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el Detalle']) !!}
    @error('detalle')
    <small class="text-danger">
        {{$message}}
    </small>
    @enderror
</div>


<div class="row">
    <div class="col-md-6">
        @if ($tema->archivo > 0)
        Archivo subido
        @else
        Ningun archivo
        @endif
        <div class="form-group">
            {!! Form::label('archivo', 'Subir archivo') !!}
            {!! Form::file('archivo', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('video','Video') !!}
            {!! Form::text('video', null, ['class'=>'form-control', 'placeholder'=>'Ingrese una URL']) !!}
            @error('video')
            <small class="text-danger">
                {{$message}}
            </small>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @if ($tema->imagen > 0)
        <center>
            <div class="grid grid-cols-1 mt-5 mx-7">
                <img id="imagenseleccionada" src="{{ URL::asset("images/{$tema->imagen}") }}"
                    style="max-height: 100px">
            </div>
        </center>
        @else
        <center>
            <div class="grid grid-cols-1 mt-5 mx-7">
                <img id="imagenseleccionada"
                    style="max-height: 100px">
            </div>
        </center>
        @endif
        <div class="form-group">
            {!! Form::label('imagen', 'Subir Imagen') !!}
            {!! Form::file('imagen', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('id_asignatura', 'Asignatura:') !!}
            {!! Form::select('id_asignatura', $asignatura, null, ['class' => 'form-control']) !!}
        </div>
    </div>

</div>
<div class="row">
    <div class="col">

        <div class="form-group">
            {!! Form::label('id_trimestre', 'Trimestre') !!}
            {!! Form::select('id_trimestre', $trimestre, null, ['class' => 'form-control']) !!}
        </div>

    </div>
    <div class="col">
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