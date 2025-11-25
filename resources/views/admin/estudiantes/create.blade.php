@extends('adminlte::page')
@section('title', 'Nuevo Estudiante')

@section('content')
<br>
<div class="row" style="margin: center">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <div class="left">Estudiante</div>
                <div class="right"><b>Nuevo</b></div>
            </div>
            {!! Form::open(['route' => 'admin.estudiantes.store', 'enctype' => 'multipart/form-data']) !!}
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('rude_es', 'RUDE:') !!}
                            {!! Form::number('rude_es', null, ['class' => 'form-control', 'placeholder' => 'Rude']) !!}
                            @error('rude_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('ci_es', 'CI: ') !!}
                            {!! Form::number('ci_es', null, ['class' => 'form-control', 'placeholder' => 'Cedula de identidad']) !!}
                            @error('ci_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('fecha_nac_es', 'Fecha Nacimiento:') !!}
                            {!! Form::date('fecha_nac_es', null, ['class' => 'form-control', 'placeholder' => 'Fecha']) !!}
                            @error('fecha_nac_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('nombres_es', 'Nombres:') !!}
                            {!! Form::text('nombres_es', null, ['class' => 'form-control', 'placeholder' => 'Nombre Completo del Usuario']) !!}
                            @error('nombres_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('apellidos_es', 'Apellidos:') !!}
                            {!! Form::text('apellidos_es', null, ['class' => 'form-control', 'placeholder' => 'Apellido Completo del Usuario']) !!}
                            @error('apellidos_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('genero_es', 'Genero:') !!}
                            {!! Form::select('genero_es', [null => 'SELECCIONE GENERO', '1' => ' MASCULINO', '0' => 'FEMENINO'], null, ['class' => 'form-control']) !!}
                            @error('genero_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <center>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <img id="imagenseleccionada" style="max-height: 100px">
                            </div>
                        </center>
                        <div class="form-group">
                            {!! Form::label('imagen_es', 'Subir Imagen') !!}
                            {!! Form::file('imagen_es', ['class' => 'form-control']) !!}
                            @error('imagen_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="button" id="eliminar-imagen" class="btn btn-danger btn-sm d-none">Eliminar</button>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('id_curso', 'Curso:') !!}
                            {!! Form::select('id_curso', $cursos, null, ['class' => 'form-control']) !!}
                            @error('id_curso')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('estado_es', 'Estado:') !!}
                            {!! Form::select('estado_es', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, ['class' => 'form-control']) !!}
                            @error('estado_es') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="card-header">
                    <div class="left"><b>Tutor</b></div>
                    <div class="right" hidden>
                        <a href="javascript:;" class="btn btn-dark btnAddMore"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo</a>
                    </div>
                </div>
                <div class="card-body" id="show_ck">

                    <div class="form-group" id="ck_0">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">


                                    <label for="nombres_0"> Tutor:</label>
                                    <!-- Campo para buscar el nombre del tutor -->
                                    <input type="text" id="nombres_0" name="nombres" class="form-control" onkeyup="searchTutor(0)" placeholder="Ingrese nombre del tutor" autocomplete="off">

                                    <!-- Contenedor de sugerencias -->
                                    <div id="tutorSuggestions_0" class="autocomplete-suggestions" style="display:none;"></div>

                                    @error('nombres') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">

                                    <label class="control-label" id="apellidos">Apellidos:</label>
                                    <input class="form-control" type="text" name="apellidos" id="apellidos_0" placeholder="Apellido completo del Tutor">
                                    @error('apellidos') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('fecha_nac', 'Fecha Nacimiento:') !!}
                                    <input type="date" id="fecha_nac_0" name="fecha_nac" class="form-control" placeholder="Fecha de Nacimiento">
                                    @error('fecha_nac') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('ci', 'CI: ') !!}
                                    <input type="number" id="ci_0" name="ci" class="form-control" placeholder="CI">
                                    @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form group">
                                    {!! Form::label('email', 'Email:') !!}
                                    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email','id'=>'email_0']) !!}
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form group">
                                    {!! Form::label('password', 'Contraseña: ') !!}

                                    {!! Form::password('password', ['id' => 'password-hidden', 'class' => 'form-control', 'placeholder' => 'Contraseña','readonly' => 'readonly']) !!}
                                    @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('direccion', 'Direccion:') !!}
                                    <textarea placeholder="Direccion del Usuario" class="form-control" name="direccion" rows="3" style="resize: none;" id="direccion_0"></textarea>
                                    @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
 
                            <div class="col">
                                <div class="form-group">

                                    <label class="control-label" id="telefono">Teléfono</label>
                                    <input class="form-control" type="tel" name="telefono" id="telefono_0" placeholder="Numero de telefono">
                                    @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                                <center>
                                    <div class="grid grid-cols-1 mt-5 mx-7">
                                        <img id="seleccionada_0" style="max-height: 100px">
                                    </div>
                                </center>

                                <div class="form-group">
                                    <label class="control-label" for="imagen_0">Subir Imagen</label>
                                    <div id="nombre_imagen_0"></div>

                                    <input type="file" id="imagen_0" name="imagen" multiple accept="image/*" class="form-control">
                                </div>
                                <button type="button" id="eliminar-i" class="btn btn-danger btn-sm d-none">Eliminar</button>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    {!! Form::label('genero', 'Genero:') !!}
                                    {!! Form::select('genero', [null => 'SELECCIONE GENERO', '1' => ' MASCULINO', '0' => 'FEMENINO'], null, ['class' => 'form-control','id'=>'genero_0']) !!}
                                    @error('genero') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">

                                    <label class="control-label">Estado</label>
                                    <select name="estado_user" class="form-control" id="estado_0">
                                        <option value="0">NO ACTIVO</option>
                                        <option value="1">ACTIVO</option>
                                    </select>
                                    @error('estado_user') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <center>
                    <a class='btn btn-danger btn-sm' href="{{ route('admin.estudiantes.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
                        <i class="fa fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success btn-sm" aria-label="eliminar" data-toggle="tooltip" data-placement="top" title="Guardar">
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
    }

    .right {
        float: right;
        width: 10%;
    }

    .autocomplete-suggestions {
        border: 1px solid #ddd;
        max-height: 200px;
        overflow-y: auto;
        background: #fff;
        position: absolute;
        width: 100%;
        z-index: 1000;
    }

    .suggestion-item {
        padding: 8px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>
<link rel="stylesheet" href="/css/admin_custom.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    // Función para buscar y mostrar sugerencias
    function searchTutor(contador) {
        let query = $('#nombres_0').val().trim(); // Obtener la consulta de búsqueda

        // Realizar la búsqueda solo si hay más de 2 caracteres
        if (query.length > 2) {
            $.ajax({
                url: "{{ route('autocomplete') }}", // Ruta a la que se hará la solicitud AJAX
                type: 'GET',
                data: {
                    query: query
                }, // Enviar la consulta de búsqueda
                success: function(response) {
                    console.log(response); // Verifica la respuesta en la consola

                    let suggestions = '';
                    if (response.length > 0) {
                        response.forEach(tutor => {
                            suggestions += `
                                <div class="suggestion-item" 
                                data-id="${tutor.id}" 
                                data-nombres="${tutor.nombres}" 
                                data-apellidos="${tutor.apellidos}" 
                                data-estado="${tutor.estado_user}" 
                                data-genero="${tutor.genero}" 
                                data-telefono="${tutor.telefono}" 
                                data-direccion="${tutor.direccion}" 
                                data-ci="${tutor.ci}" 
                                data-fecha_nac="${tutor.fecha_nac}"
                                data-email="${tutor.email}"
                                data-password="${tutor.password}" 
                                data-imagen="${tutor.imagen}">
                                ${tutor.nombres} ${tutor.apellidos}
                                </div>`;
                        });
                        $('#tutorSuggestions_' + contador).html(suggestions).show();
                    } else {
                        $('#tutorSuggestions_' + contador).hide(); // Ocultar si no hay resultados
                    }
                },
                error: function() {
                    console.error('Error en la búsqueda');
                    $('#tutorSuggestions_' + contador).hide();
                }
            });
        } else {
            $('#tutorSuggestions_' + contador).hide(); // Ocultar si no hay suficientes caracteres
        }
    }

    // Evento para seleccionar una sugerencia y autocompletar campos
    $(document).on('click', '.suggestion-item', function() {
        const nombres = $(this).data('nombres');
        const apellidos = $(this).data('apellidos');
        const estado = $(this).data('estado');
        const genero = $(this).data('genero');
        const imagen = $(this).data('imagen');
        const telefono = $(this).data('telefono');
        const direccion = $(this).data('direccion');
        const ci = $(this).data('ci');
        const fecha_nac = $(this).data('fecha_nac');
        const email = $(this).data('email');
        const password = $(this).data('password');

        // Autocompletar los campos
        $('#nombres_0').val(`${nombres}`);
        $('#apellidos_0').val(`${apellidos}`);
        $('#estado_0').val(`${estado}`);
        $('#genero_0').val(`${genero}`);
        $('#telefono_0').val(`${telefono}`);
        $('#direccion_0').val(`${direccion}`);
        $('#ci_0').val(`${ci}`);
        $('#fecha_nac_0').val(`${fecha_nac}`);
        $('#password_0').val(`${password}`);
        $('#email_0').val(`${email}`);

        // Mostrar la imagen si existe
        if (imagen) {
            $('#seleccionada_0').attr('src', '/images/' + imagen);
        } else {
            $('#seleccionada_0').attr('src', '/images/default.png'); // Imagen por defecto en caso de que no haya
        }

        // Ocultar las sugerencias después de seleccionar
        $('#tutorSuggestions_0').hide();
    });
</script>


<script>
    $(document).ready(function(e) {
        // Cuando se elige una imagen
        $('#imagen_es').change(function() {
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
            $('#imagen_es').val('');
        });
    });


    $(document).ready(function(e) {
        // Cuando se elige una imagen
        $('#imagen_0').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                // Mostrar la imagen seleccionada
                $('#seleccionada_0').attr('src', e.target.result);

                // Mostrar el botón de eliminar imagen
                $('#eliminar-i').removeClass('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        });

        // Cuando se haga clic en el botón de eliminar
        $('#eliminar-i').click(function() {
            // Eliminar la imagen cargada
            $('#seleccionada_0').attr('src', ''); // Restaurar el área de imagen a vacío

            // Ocultar el botón de eliminar
            $(this).addClass('d-none');

            // Eliminar la imagen del campo de entrada (input)
            $('#imagen_0').val('');
        });
    });


</script>

<script>
    $(document).ready(function(e) {
        // Usa el evento de cambio para cada input de imagen
        $(document).on('change', 'input[type="file"]', function() {
            let reader = new FileReader();
            let fileInputId = $(this).attr('id'); // Obtiene el ID del input que se cambió
            let imgId = 'seleccionada_' + fileInputId.split('_')[2]; // Construye el ID de la imagen

            reader.onload = (e) => {
                $('#' + imgId).attr('src', e.target.result); // Muestra la imagen
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>

<script>
    // Inicializar el contador
    let contador = 1;



    // Función para manejar la carga de la imagen del tutor
    function previewImageTutor(contador, event) {
        let reader = new FileReader();
        reader.onload = (e) => {
            // Mostrar la imagen previa en un div con un id único para cada tutor
            let imagePreview = '<img id="imagenseleccionad_' + contador + '" style="max-height: 100px" src="' + e.target.result + '">';
            $('#ck_' + contador).find('.form-group').last().prepend(imagePreview); // Agregar la imagen a la vista
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Agregar un nuevo tutor
    $(document).on('click', '.btnAddMore', function() {
        var add_more = `
            <div class="form-group" id="ck_${contador}"> 
                <hr>
                <label class="control-label">Tutor ${contador + 1}</label>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label class="control-label">Nombres</label>
                            <input class="form-control" type="text" id="nombres_tutor_${contador}" name="nombres_tutor[]" placeholder="Nombre completo del Tutor" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label class="control-label">Apellidos</label>
                            <input class="form-control" type="text" name="apellidos_tutor[]" id="apellidos_tutor_${contador}" placeholder="Apellido completo del Tutor" required>
                        </div>
                    </div>

                </div>

                

                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input class="form-control" type="tel" name="telefono[]" id="telefono_${contador}" placeholder="Número de teléfono">
                        </div>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col">
                        <center>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <img id="imagenseleccionad_${contador}" style="max-height: 100px">
                            </div>
                        </center>
                        <div class="form-group">
                            <label for="imagen_tutor">Subir Imagen:</label>
                            <input type="file" name="imagen_tutor[]" accept="image/*"  multiple class="form-control" id="imagen_tutor${contador}" onchange="previewImageTutor(${contador}, event)">
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="estado_tutor">Estado:</label>
                            <select name="estado_tutor[]" id="estado_${contador}" class="form-control">
                                <option value="0">NO ACTIVO</option>
                                <option value="1">ACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botón de eliminar tutor -->
                <button type="button" class="btn btn-danger btn-sm mt-3" onclick="removeTutor(${contador})">Eliminar</button>
            </div>
        `;

        // Añadir el nuevo tutor al DOM
        $("div#show_ck").append(add_more);

        // Incrementar el contador para el siguiente tutor
        contador++;
    });

    // Función para eliminar un tutor
    function removeTutor(contador) {
        $('#ck_' + contador).remove(); // Eliminar el tutor con el id correspondiente
    }


    // Función para manejar la lógica de mostrar el campo "Otro"
    //onchange="checkOtherRelation(${contador})"
    /* <div class="row" id="otherRelationDiv_${contador}" style="display:none;">
                    <div class="col">
                        <div class="form-group">
                            <label class="control-label">Especificar Parentezco</label>
                            <input class="form-control" type="text" name="relacion_otro[]" id="relacion_otro${contador}" placeholder="Especificar relación" required>
                        </div>
                    </div>
                </div>*/
    /* function checkOtherRelation(contador) {
            var select = document.getElementById("relacion_" + contador);
            var otherDiv = document.getElementById("otherRelationDiv_" + contador);

            // Si se selecciona "Otro", mostrar el campo de texto
            if (select.value === "Otro") {
                otherDiv.style.display = "block";
            } else {
                otherDiv.style.display = "none";
            }
        }*/
</script>

@stop