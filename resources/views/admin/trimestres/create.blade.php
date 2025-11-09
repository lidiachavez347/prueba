@extends('adminlte::page')
@section('title', 'Nuevo Trimestre')

@section('content')
<br>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card">

            <div class="card-header">
                <div class="left">Trimestre</div>
                <div class="right"><b>Nuevo</b></div>
            </div>
            {!! Form::open(['route' => 'admin.trimestres.store', 'enctype' => 'multipart/form-data']) !!}
            <div class="card-body">



                <div class="row">
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('periodo', 'Periodo:') !!}
                            {!! Form::text('periodo', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Introducir una nuevo periodo',
                            ]) !!}
                            @error('periodo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('fecha_inicio', 'Fecha inicio:') !!}
                            {!! Form::date('fecha_inicio', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Introducir una nueva turno',
                            ]) !!}
                            @error('fecha_inicio')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form group">
                            {!! Form::label('fecha_fin', 'Fecha fin:') !!}
                            {!! Form::date('fecha_fin', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Introducir una nueva turno',
                            ]) !!}
                            @error('fecha_fin')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            {!! Form::label('id_gestion', 'Gestion:') !!}
                            {!! Form::select('id_gestion', $gestiones, null, ['class' => 'form-control']) !!}
                            @error('id_gestion')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="col-md-6">
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
                    <a class='btn btn-danger  btn-sm href' href="{{ route('admin.trimestres.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
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