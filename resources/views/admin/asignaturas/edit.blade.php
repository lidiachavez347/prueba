@extends('adminlte::page')
@section('title', 'Editar Asignación')
@section('content')
<br>
<div class="row">
    <div class="col-3"></div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <div class="left">Editar Asignación</div>
                <div class="right"><b>Editar</b></div>
            </div>
            <form action="{{ route('admin.asignaturas.update', $profesor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="form-group">
                        <label>Profesor:</label>
                        {{ $profesor->nombres }}  {{ $profesor->apellidos }}
                    </div>

                    <div class="form-group">
                        <label>Curso:</label>
                        <input type="hidden" name="id_curso" value="{{ $cursoActual->id }}">
                        {{ $cursoActual->nombre_curso }} - {{ $cursoActual->paralelo }}
                            @error('id_curso') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Asignaturas:</label><br>
                            @foreach($asignaturas as $asignatura)
                                <input type="checkbox" name="asignaturas[]" value="{{ $asignatura->id }}"
                                    {{ in_array($asignatura->id, $asignacionesActuales) ? 'checked' : '' }}>
                                {{ $asignatura->nombre_asig }}<br>
                            @endforeach
                            @error('asignaturas') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="card-footer text-center">
                    <a href="{{ route('admin.asignaturas.index') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-check"></i> Guardar
                    </button>
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