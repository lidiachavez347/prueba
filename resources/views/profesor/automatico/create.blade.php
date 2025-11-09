@extends('adminlte::page')
@section('title', 'Nuevo Pregunta')
@section('content_header')

@stop

@section('content')


<br>
<div class="row" style="margin: center">
    <div class="col-1">

    </div>
    <div class="col-10">
        <div class="card">
            <div class="card-header text-dark alert-dark">
                <h3 style="color: white">Nueva pregunta </h3>

            </div>
            <div class="card-body">


                {{-- ------------------------- --}}

                {!! Form::open(['route' => 'profesor.automatico.store', 'id' => 'form','class'=>'form']) !!}
                @csrf

                <div class="form-group">
                    <label class="control-label">Pregunta</label>
                    <textarea class="form-control" name="descripcion" id="editor1" rows="10" cols="80" required></textarea>
                    @error('descripcion')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <!--<a href="javascritp:;" class="btn btn-dark btnAddMore">Agregar Nueva Opción </a>
--> <div id="show_ck">
                    {{-- <div style="width:80%; float:left;"> --}}

                    <div class="form group" id="ck_0">
                        <div class="row">
                            <div class="form group col-12">
                                <label class="control-label">Opción 1</label>
                                <textarea class="form-control" name="detalle[]" rows="3" ></textarea>
                            </div>

                            <div class="row">
                                <div class="form group col-10">
                                    <label class="control-label">Estado opción 1</label>
                                    <select name="estado[]" id="estado[]" class="form-control">
                                        <option value="0">Incorrecto</option>
                                        <option value="1">Correcto</option>
                                    </select>
                                </div>
                                <div class="col-1">
                                    <label class="control-label">Puntos</label>
                                    <input style="width:80px" class="form-control" type="number" name="puntos[]"
                                        value="0" id="" min='0' max='10'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form group" id="ck_0">
                        <div class="row">
                            <div class="form group col-12">
                                <label class="control-label">Opción 2</label>
                                <textarea class="form-control" name="detalle[]" rows="3" ></textarea>
                            </div>

                            <div class="row">
                                <div class="form group col-10">
                                    <label class="control-label">Estado opción 2</label>
                                    <select name="estado[]" id="estado[]" class="form-control">
                                        <option value="0">Incorrecto</option>
                                        <option value="1">Correcto</option>
                                    </select>
                                </div>
                                <div class="col-1">
                                    <label class="control-label">Puntos</label>
                                    <input style="width:80px" class="form-control" type="number" name="puntos[]"
                                        value="0" id="" min='0' max='10'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($evaluaciones as $eval)
                {!! Form::hidden('examene_id', $eval, ['class' => 'form-control']) !!}
                @endforeach



                <br>
                <center>
                    <a class='btn btn-light href' href="{{ route('profesor.evaluaciones.index') }}">CERRAR</a>

                    {!! Form::submit('GUARDAR ', ['class' => 'btn btn-success btnenviar', 'id' => 'btnenviar']) !!}
                </center>

                {!! Form::close() !!}
            </div>


        </div>
    </div>
</div>

</div>
@stop

@section('js')




<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script src="https://cdn.ckeditor.com/4.20.2/standard-all/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor1', {
        fullPage: true,
        extraPlugins: 'docprops',
        // Disable content filtering because if you use full page mode, you probably
        // want to  freely enter any HTML content in source mode without any limitations.
        allowedContent: true,
        height: 320,
        removeButtons: 'PasteFromWord'
    });
</script>

<script>
    $(document).ready(function() {
        var count = 2;
        $(document).on('click', '.btnAddMore', function() {
            var add_more = '<div class="form group" id="ck_' + count + '">' +
                '<div class="row"><div class="col-10">' +
                '<label class="control-label">Opción ' + count + '</label>' +
                '<textarea name="detalle[]" id="replace_element_' + count + '" required ></textarea>' +
                '</div><div class="col-1">' +
                '<label class="control-label">Accion</label>' +
                '<br><button name="remove" id="' + count +
                '" class="btn btn-sm btn-danger btn-remove" >Eliminar</button>' +
                ' </div>' +
                '</div>' + '<br><div class="row"><div class="form group col-8"> <label class="control-label">Estado opción ' + count + '</label>' +
                '<select name="estado[]" id="estado[]" class="form-control">' +
                ' <option value="0">Incorrecto</option>' +
                '<option value="1">Correcto</option> </select></div>' +
                '<div class="col-1">' +
                '</div><label class="control-label">Puntos: </label><br><input style="width:80px" class="form-control" type="number" name="puntos[]" value="0" min="0", max="10"  id="">' +
                '</div> </div><br>';

            $("div#show_ck").append(add_more);
            CKEDITOR.replace('replace_element_' + count);
            count++;

        });
        $(document).on('click', '.btn-remove', function() {
            var button_id = $(this).attr("id");
            $('div#ck_' + button_id + '').remove();


        });

    });
</script>

<script>
    jQuery(document).ready(function($) {
        $("#form").validate(
            rules: {
                "opcion": {
                    required: true,
                    maxlength: 32,
                    normalizer: function(value) {
                        return $.trim(value);
                    }
                },
                messages: {
                    "opcion": {
                        required: "El campo es requerido",
                        maxlength: "No se permiten mas de 32 caracteres"
                    },
                }
            });
    });
</script>



@endsection