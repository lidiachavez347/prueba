@extends('adminlte::page')
@section('title', 'Nuevo Tutor')

@section('content')
<br>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card">

            <div class="card-header">
                <div class="left">Tutor</div>
                <div class="right"><b>Nuevo</b></div>
            </div>
            {!! Form::open(['route' => 'admin.tutores.store', 'enctype' => 'multipart/form-data']) !!}
            <div class="card-body">


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('roles', 'Rol:') !!}
                            {!! Form::select('roles', $roles, null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('ci', 'CI: ') !!}
                            {!! Form::number('ci', null, ['class' => 'form-control', 'placeholder' => 'Cedula de identidad']) !!}
                            @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('fecha_nac', 'Fecha Nacimiento:') !!}
                            {!! Form::date('fecha_nac', null, ['class' => 'form-control', 'placeholder' => 'Fecha']) !!}
                            @error('fecha_nac') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('nombres', 'Nombres:') !!}
                            {!! Form::text('nombres', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Nombre Completo del Usuario',
                            ]) !!}
                            @error('nombres')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('apellidos', 'Apellidos:') !!}
                            {!! Form::text('apellidos', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Apellido Completo del Usuario',
                            ]) !!}
                            @error('apellidos')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('direccion', 'Direccion:') !!}
                            {!! Form::textarea('direccion', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Direccion del Usuario',
                            'rows' => '3',
                            'cols' => '40',
                            'style' => 'resize: none',
                            ]) !!}
                            @error('direccion')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('genero', 'Genero:') !!}
                            {!! Form::select('genero', [null => 'SELECCIONE GENERO', '1' => ' MASCULINO', '0' => 'FEMENINO'], null, [
                            'class' => 'form-control',
                            ]) !!}
                            @error('genero')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('email', 'Email:') !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('password', 'Contraseña: ') !!}
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 d-none">

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">

                            <label class="control-label" id="telefono">Teléfono</label>
                            <input class="form-control" type="tel" name="telefono" placeholder="Numero de telefono">
                            @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <button type="button" id="eliminar-imagen" class="btn btn-danger btn-sm d-none">Eliminar</button>
                    </div>
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('estado_user', 'Estado:') !!}
                            {!! Form::select('estado_user', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, [
                            'class' => 'form-control',
                            '',
                            ]) !!}
                            @error('estado_user')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer ">
                <center>
                    <a class='btn btn-danger  btn-sm href' href="{{ route('admin.tutores.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
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