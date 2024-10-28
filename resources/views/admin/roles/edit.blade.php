@extends('adminlte::page')
@section('title', 'Editar Rol')

@section('content')
<br>
<div class="row" style="margin: center">
    <div class="col-3">
    </div>
    <div class="col-6">
        <div class="card">

            <div class="card-header">
                <div class="left">Rol</div>
                <div class="right"><b>Editar</b></div>
            </div>
            {!! Form::model($role, ['route' => ['admin.roles.update', $role], 'method' => 'put']) !!}
            <div class="card-body">

                @include('admin.roles.partials.form')
            </div>
            <div class="card-footer">
            <center>
                    <a class='btn btn-danger  btn-sm href' href="{{ route('admin.roles.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
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
    console.log('Hi!');
</script>
@stop