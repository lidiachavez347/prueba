@extends('adminlte::page')
@section('title', 'Nuevo Asignatura')
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
                <div class="left">Asignatura</div>
                <div class="right"><b>Nuevo</b></div>
            </div>
                {!! Form::open(['route' => 'admin.asignaturas.store', 'enctype' => 'multipart/form-data']) !!}

                @csrf
                <div class="card-body">
                    <div class="form group">
                        {!! Form::label('nombre_asignatura', 'Materia:') !!}
                        {!! Form::text('nombre_asignatura', null, ['class' => 'form-control', 'placeholder' => 'Materia']) !!}
                        @error('nombre_asignatura')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form group">
                        {!! Form::label('estado_asignatura', 'Estado:') !!}
                        {!! Form::select(
                            'estado_asignatura',
                            [null => 'SELECCIONE ESTADO', '0' => ' NO DISPONIBLE', '1' => 'DISPONIBLE'],
                            null,
                            [
                                'class' => 'form-control',
                            ],
                        ) !!}
                        @error('estado_asignatura')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="card-footer ">
                <center>
                    <a class='btn btn-danger  btn-sm href' href="{{ route('admin.asignaturas.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
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
