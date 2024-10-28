@extends('adminlte::page')
@section('title', 'Editar usuario')
@section('content_header')

@stop

@section('content')
<br>
<div class="row" style="margin: center">
    <div class="col-3">
    </div>
    <div class="col-6">
        <div class="card">

            <div class="card-header">
                <div class="left">Usuario</div>
                <div class="right"><b>Editar</b></div>
            </div>
            {!! Form::model($users,['route' => ['admin.usuarios.update',$users->id],'method'=>'put', 'enctype' => 'multipart/form-data']) !!}
            <div class="card-body">

                @include('admin.usuarios.partials.form')


            </div>
            <br>
            <div class="card-footer ">
                <center>
                    <a class='btn btn-danger  btn-sm href' href="{{ route('admin.usuarios.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
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
@endsection