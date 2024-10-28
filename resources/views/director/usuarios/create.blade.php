@extends('adminlte::page')
@section('title', 'Nuevo Usuario')


@section('content')
<br>
<div class="row" style="margin: center">
    <div class="col-2">
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-header text-dark alert-dark">
                <h3>Nuevo Usuario</h3>
            </div>
            {!! Form::open(['route' => 'admin.usuarios.store']) !!}

            <div class="card-body">
            @include('admin.usuarios.partials.form')
            </div>
                <br>
                <div class="card-footer alert-dark">
                    <center>
                        <a class='btn btn-danger href' href="{{ route('admin.usuarios.index') }}">Volver</a>
                        {!! Form::submit('Guardar', ['class' => 'btn btn-dark']) !!}
                    </center>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
@stop
@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
@stop