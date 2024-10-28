@extends('adminlte::page')
@section('title', 'Editar')


@section('content')
    <br>
    <div class="row" style="margin: center">
        <div class="col-3">
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header text-dark alert-dark">
                    <h3>Editar</h3>
                </div>
                {!! Form::model($asignatura, [
                    'route' => ['director.asignaturas.update', $asignatura],
                    'method' => 'put',
                    'enctype' => 'multipart/form-data',
                ]) !!}
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
                <div class="card-footer alert-dark">
                    <center>
                        <a class='btn btn-danger href' href="{{ route('director.asignaturas.index') }}">Volver</a>
                        {!! Form::submit('Actualizar', ['class' => 'btn btn-dark']) !!}
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
