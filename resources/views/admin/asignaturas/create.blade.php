@extends('adminlte::page')
@section('title', 'Asignar Profesor')
@section('content_header')

@stop

@section('content')
<br>
@if(session('error'))
    <div class="alert alert-warning">{{ session('error') }}</div>
@endif

<br>
<div class="row" style="margin: center">
    <div class="col-3">
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <div class="left">Asignar Profesor</div>
                <div class="right"><b>Nuevo</b></div>
            </div>
            <form action="{{ route('admin.asignaturas.store') }}" method="POST">
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                        <label>Seleccionar Profesor:</label>
                        <select name="id_profesor" class="form-control">
                            <option value="">SELECCIONAR PROFESOR</option>
                            @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id }}">{{ $profesor->nombres}} {{ $profesor->apellidos }}</option>
                            @endforeach

                        </select>
                            @error('id_profesor') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Seleccionar Curso:</label>
                        <select name="id_curso" class="form-control">
                            <option value="">SELECCIONAR CURSO</option>
                            @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre_curso }} - {{ $curso->paralelo }} - {{ $curso->gestion->gestion }}</option>
                            @endforeach
                        </select>
                            @error('id_curso') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Asignaturas:</label><br>
                        @foreach($asignaturas as $asignatura)
                        <input type="checkbox" name="asignaturas[]" value="{{ $asignatura->id }}">
                        {{ $asignatura->nombre_asig }}<br>
                        @endforeach
                        @error('asignaturas') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>
                <div class="card-footer ">
                    <center>
                        <a class='btn btn-danger  btn-sm href' href="{{ route('admin.asignaturas.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
                            <i class="fa fa-arrow-left"></i> Cancelar
                        </a>

                        <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
                            <i class="fa fa-check"></i> Asignar
                        </button>
                    </center>
                </div>
            </form>
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