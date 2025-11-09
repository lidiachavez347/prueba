<!-- Aquí incluimos el formulario del permiso -->
<div class="row">
    <div class="col">
        <div class="form-group">
            {!! Form::label('nombre','Nombre') !!}
            {!! Form::text('nombre', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el nombre']) !!}
            @error('nombre')
            <small class="text-danger">
                {{$message}}
            </small>
            @enderror
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            {!! Form::label('fecha', 'Fecha') !!}
            {!! Form::date('fecha', null, ['class' => 'form-control']) !!}
            @error('fecha')
            <small class="text-danger">
                {{$message}}
            </small>
            @enderror
        </div>

    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group">
            {!! Form::label('evaluar','Evaluar') !!}
            {!! Form::text('evaluar', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el nombre']) !!}
            @error('evaluar')
            <small class="text-danger">
                {{$message}}
            </small>
            @enderror
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            {!! Form::label('descripcion','Detalle') !!}
            {!! Form::text('descripcion', null, ['class'=>'form-control', 'placeholder'=>'Ingrese una descripcion']) !!}
            @error('descripcion')
            <small class="text-danger">
                {{$message}}
            </small>
            @enderror
        </div>
    </div>


</div>


<div class="row">
    <div class="col-md-6">
        @if ($tarea->archivo > 0)
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
        @if ($tarea->imagen > 0)
        <center>
            <div class="grid grid-cols-1 mt-5 mx-7">
                <img id="imagenseleccionada" src="{{ URL::asset("images/{$tarea->imagen}") }}"
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
            {!! Form::label('id_tema', 'Tema') !!}
            {!! Form::select('id_tema', $tema, null, ['class' => 'form-control']) !!}
        </div>

    </div>
    <div class="col">
        <div class="form-group">
            {!! Form::label('id_criterio', 'Criterio') !!}
            {!! Form::select('id_criterio', $criterio, null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
<div class="col">
                        <div class="form group">
                            {!! Form::label('tipo', 'Tipo:') !!}
                            {!! Form::select('tipo', [null => 'SELECCIONE TIPO', 'AULA' => 'EN AULA', 'CASA' => 'EN CASA'], null, [
                            'class' => 'form-control',
                            '',
                            ]) !!}
                            @error('tipo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
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