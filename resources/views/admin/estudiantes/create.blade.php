@extends('adminlte::page')
@section('title', 'Nuevo Estudiante')

@section('content')
<br>
<div class="row" style="margin: center">
    <div class="col">
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
                            {!! Form::label('rude', 'RUDE:') !!}
                            {!! Form::number('rude', null, ['class' => 'form-control', 'placeholder' => 'Rude']) !!}
                            @error('rude') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('ci', 'CI: ') !!}
                            {!! Form::number('ci', null, ['class' => 'form-control', 'placeholder' => 'Cedula de identidad']) !!}
                            @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('fecha_nacimiento', 'Fecha Nacimiento:') !!}
                            {!! Form::date('fecha_nacimiento', null, ['class' => 'form-control', 'placeholder' => 'Fecha']) !!}
                            @error('fecha_nacimiento') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('nombres', 'Nombres:') !!}
                            {!! Form::text('nombres', null, ['class' => 'form-control', 'placeholder' => 'Nombre Completo del Usuario']) !!}
                            @error('nombres') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('apellidos', 'Apellidos:') !!}
                            {!! Form::text('apellidos', null, ['class' => 'form-control', 'placeholder' => 'Apellido Completo del Usuario']) !!}
                            @error('apellidos') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('genero', 'Genero:') !!}
                            {!! Form::select('genero', [null => 'SELECCIONE GENERO', '1' => ' MASCULINO', '0' => 'FEMENINO'], null, ['class' => 'form-control']) !!}
                            @error('genero') <span class="text-danger">{{ $message }}</span> @enderror
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
                            {!! Form::label('imagen', 'Subir Imagen') !!}
                            {!! Form::file('imagen', ['class' => 'form-control']) !!}
                        </div>
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
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('direccion', 'Direccion:') !!}
                            <textarea placeholder="Direccion del Usuario" class="form-control" name="direccion" rows="3" style="resize: none;"></textarea>
                            @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('estado', 'Estado:') !!}
                            {!! Form::select('estado', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, ['class' => 'form-control']) !!}
                            @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="card-header">
                    <div class="left"><b>Tutor</b></div>
                    <div class="right">
                        <a href="javascript:;" class="btn btn-dark btnAddMore"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo</a>
                    </div>
                </div>
                <div class="card-body" id="show_ck">

                    <div class="form-group" id="ck_0">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">

                                    <label class="control-label" id="nombres_tutor">Nombres:</label>
                                    <input class="form-control" name="nombres_tutor[]" type="text" id="nombres_tutor_0" onkeyup="searchTutor(0)" placeholder="Nombre del tutor">
                                    <div id="tutorSuggestions_0" class="suggestions-box" style="display:none;"></div>

                                    @error('nombres_tutor') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>

                            <div class="col">
                                <div class="form-group">

                                    <label class="control-label" id="apellidos_tutor">Apellidos:</label>
                                    <input class="form-control" type="text" name="apellidos_tutor[]" id="apellidos_tutor_0" placeholder="Apellido completo del Tutor">
                                    @error('apellidos_tutor') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-2">

                                <div class="form-group">

                                    <label class="control-label" id="relacion">Parentezco:</label>
                                    <select class="form-control" name="relacion[]" id="relacion_0" onchange="checkOtherRelation(0)">
                                        <option value="">Seleccione Parentezco</option>
                                        <option value="Padre">Padre</option>
                                        <option value="Madre">Madre</option>
                                        <option value="Tio">Tío</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="otherRelationDiv_0" style="display:none;">
                            <div class="col">
                                <div class="form-group">

                                    <label class="control-label" id="relacion_otro">Especificar Parentezco:</label>
                                    <input class="form-control" type="text" name="relacion_otro[]" id="relacion_otro_0" placeholder="Especificar relación">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col">
                                <div class="form-group">
                                    {!! Form::label('direccion', 'Direccion:') !!}
                                    <textarea placeholder="Direccion del Usuario" class="form-control" name="direccion[]" rows="3" style="resize: none;"></textarea>
                                    @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        </div>-->
                            <div class="col-3">
                                <div class="form-group">

                                    <label class="control-label" id="telefono">Teléfono</label>
                                    <input class="form-control" type="tel" name="telefono[]" id="telefono_0" placeholder="Numero de telefono">
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
                                    <label class="control-label" for="imagen_tutor_0">Subir Imagen</label>
                                    <div id="nombre_imagen_tutor_0"></div>

                                    <input type="file" id="imagen_tutor_0" name="imagen_tutor[]"  multiple accept="image/*" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">

                                    <label class="control-label">Estado</label>
                                    <select name="estado_tutor[]" class="form-control" id="estado_0">
                                        <option value="0">NO ACTIVO</option>
                                        <option value="1">ACTIVO</option>
                                    </select>
                                    @error('estado_tutor') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <center>
                        {!! Form::submit('Guardar Respuesta', ['class' => 'btn btn-dark']) !!}
                    </center>
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

    .suggestions-box {
        position: absolute;
        background-color: white;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
        z-index: 9999;
    }

    .suggestion-item {
        padding: 10px;
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

<script>
    // Función para buscar y autocompletar los campos del tutor
    function searchTutor(contador) {
        let nombre_tutor = document.getElementById('nombres_tutor_' + contador).value;

        // Mostrar el cuadro de sugerencias solo si el nombre tiene más de 2 caracteres.
        if (nombre_tutor.length > 2) {
            $.ajax({
                url: '/search-tutor', // La ruta de búsqueda
                type: 'GET',
                data: {
                    nombre_tutor: nombre_tutor
                },
                success: function(response) {
                    if (response && response.length > 0) {
                        // Mostrar las sugerencias
                        let suggestions = '';
                        response.forEach(tutor => {
                            suggestions += `
                            <div class="suggestion-item" 
                                data-id="${tutor.id}" 
                                data-nombres="${tutor.nombres_tutor}" 
                                data-apellidos="${tutor.apellidos_tutor}" 
                                data-telefono="${tutor.telefono}" 
                                data-relacion="${tutor.relacion}"
                                data-estado="${tutor.estado_tutor}"
                                data-imagen="${tutor.imagen_tutor}">
                                ${tutor.nombres_tutor} ${tutor.apellidos_tutor}
                            </div>
                        `;
                        });
                        $('#tutorSuggestions_' + contador).html(suggestions).show();
                    } else {
                        $('#tutorSuggestions_' + contador).hide();
                    }
                },
                error: function(error) {
                    console.log('Error en la búsqueda');
                }
            });
        } else {
            $('#tutorSuggestions_' + contador).hide(); // Si no hay suficientes caracteres, ocultar las sugerencias.
        }
    }

    // Evento de selección de una sugerencia
    $(document).on('click', '.suggestion-item', function() {
        const id = $(this).data('id');
        const nombres = $(this).data('nombres');
        const apellidos = $(this).data('apellidos');
        const telefono = $(this).data('telefono');
        const relacion = $(this).data('relacion');
        const estado = $(this).data('estado');
        const imagen = $(this).data('imagen');

        // Autocompletar los campos con la información seleccionada
        $('#nombres_tutor_0').val(nombres);
        $('#apellidos_tutor_0').val(apellidos);
        $('#telefono_0').val(telefono);
        $('#relacion_0').val(relacion);
        $('#estado_0').val(estado);

        // Si hay una imagen asociada, mostrarla
        if (imagen) {
            $('#seleccionada_0').attr('src', '/images/' + imagen);
            $('#nombre_imagen_tutor_0').text(imagen);
        }

        // Ocultar las sugerencias después de seleccionar una
        $('#tutorSuggestions_0').hide();
    });





    // document.getElementById('miFormulario').onsubmit = function() {
    //  this.reset();
    ///};
</script>

<script>
    $(document).ready(function(e) {
        $('#imagen').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#imagenseleccionada').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
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
                    <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">Parentezco</label>
                            <select class="form-control" name="relacion[]" id="relacion_${contador}" >
                                <option value="">Seleccione Parentezco</option>
                                <option value="Padre">Padre</option>
                                <option value="Madre">Madre</option>
                                <option value="Tio">Tío</option>
                                <option value="Otro">Otro</option>
                            </select>
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