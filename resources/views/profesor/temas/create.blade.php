@extends('adminlte::page')
@section('title', 'Nuevo Tema')

@section('content')
<br>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card">

            <div class="card-header">
                <div class="left">Tema</div>
                <div class="right"><b>Nuevo</b></div>
            </div>
            {!! Form::open(['route' => 'profesor.temas.store', 'enctype' => 'multipart/form-data']) !!}
            <div class="card-body">

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

                        <center>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <img id="imagenseleccionada"
                                    style="max-height: 100px">
                            </div>
                        </center>

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

            </div>

            <div class="card-footer ">
                <center>
                    <a class='btn btn-danger  btn-sm href' href="{{ route('profesor.temas.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
                        <i class="fa fa-arrow-left"></i> Cancelar
                    </a>

                    <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                </center>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
@stop
@section('css')
<style>
    .left {
        float: left;
        width: 50%;
        /* Ajusta el ancho si es necesario */

    }

    .right {
        float: right;
        width: 10%;
        /* Ajusta el ancho si es necesario */

    }
</style>
<link rel="stylesheet" href="/css/admin_custom.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
@stop
@section('js')
<script>
    document.getElementById('miFormulario').onsubmit = function() {
        // Limpia los campos del formulario al enviarlo
        this.reset();
    };
</script>
<script>
    $(document).ready(function(e) {
        // Cuando se elige una imagen
        $('#imagen').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                // Mostrar la imagen seleccionada
                $('#imagenseleccionada').attr('src', e.target.result);

                // Mostrar el botón de eliminar imagen
                $('#eliminar-imagen').removeClass('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        });

        // Cuando se haga clic en el botón de eliminar
        $('#eliminar-imagen').click(function() {
            // Eliminar la imagen cargada
            $('#imagenseleccionada').attr('src', ''); // Restaurar el área de imagen a vacío

            // Ocultar el botón de eliminar
            $(this).addClass('d-none');

            // Eliminar la imagen del campo de entrada (input)
            $('#imagen').val('');
        });
    });
</script>

@stop