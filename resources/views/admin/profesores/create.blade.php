@extends('adminlte::page')
@section('title', 'Nuevo Profesor')

@section('content')
<br>
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card">

            <div class="card-header">
                <div class="left">Profesor</div>
                <div class="right"><b>Nuevo</b></div>
            </div>
            {!! Form::open(['route' => 'admin.profesores.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

            <div class="card-body">

                <div class="row">
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <div class="form-group">

                            <label class="control-label" id="telefono">Teléfono</label>
                            <input class="form-control" type="tel" name="telefono" placeholder="Numero de telefono">
                            @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
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
                            {!! Form::password('password', ['id' => 'password-hidden', 'class' => 'form-control', 'placeholder' => 'Contraseña','readonly' => 'readonly']) !!}
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>

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
                                @error('imagen') <span class="text-danger">{{ $message }}</span> @enderror
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
            <br>
            <div class="card-footer ">
                <center>
                    <a class='btn btn-danger  btn-sm href' href="{{ route('admin.profesores.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
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
document.querySelector('input[name="telefono"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, ''); // elimina todo lo que no sea número
});



    function generarPassword(longitud = 8) {
    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let pass = "";
    for (let i = 0; i < longitud; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return pass;
}

$(document).ready(function() {
    const emailInput = $('input[name="email"]');
    const feedback = $('<small id="email-feedback"></small>');
    emailInput.after(feedback);

    let typingTimer;
    const delay = 1000; // milisegundos después de dejar de escribir

    emailInput.on('keyup', function() {
        clearTimeout(typingTimer);
        const email = $(this).val();

        if (email.length > 5) {
            feedback.text('Verificando correo... 🔄').css('color', 'gray');
            typingTimer = setTimeout(() => {
                verificarEmail(email);
                // Generar contraseña automática y asignar al campo oculto
                const nuevaPass = generarPassword(8);
                $('#password-hidden').val(nuevaPass);
                console.log('Contraseña generada:', nuevaPass);
            
            } , delay);
        } else {
            feedback.text('');
            $('#password-hidden').val('');
        }
    });

    function verificarEmail(email) {
        $.ajax({
            url: '{{ route('verificar.email') }}',
            type: 'POST',
            data: {
                email: email,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.valid) {
                    feedback.text(response.message).css('color', 'green');
                } else {
                    feedback.text(response.message).css('color', 'red');
                    $('#password-hidden').val('');
                }
            },
            error: function() {
                feedback.text('⚠️ Error al verificar el correo.').css('color', 'orange');
            }
        });
    }
});
$('form').on('submit', function(e) {
    const mensaje = $('#email-feedback').text();
    if (mensaje.includes('no existe') || mensaje.includes('Error')) {
        e.preventDefault();
        alert('Por favor, ingrese un correo válido antes de guardar.');
    }
});

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