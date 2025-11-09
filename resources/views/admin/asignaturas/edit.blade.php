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
                <div class="right"><b>Actualizar</b></div>
            </div>
            <form action="{{ route('admin.asignaturas.update', $profesorAsignado->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Profesor:</label>
                        <select name="id_profesor" class="form-control" disabled>
                            @foreach($profesores as $profesor)
                                <option value="{{ $profesor->id }}" 
                                    {{ $profesorAsignado->id == $profesor->id ? 'selected' : '' }}>
                                    {{ $profesor->nombres }} {{ $profesor->apellidos }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Seleccionar Curso:</label>
                        <select name="id_curso" class="form-control">
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}" 
                                    {{ $curso->id == $profesorAsignado->curso_id ? 'selected' : '' }}>
                                    {{ $curso->nombre_curso }} - {{ $curso->paralelo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Seleccionar Nivel:</label>
                        <select name="id_nivel" class="form-control">
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id }}" 
                                    {{ $nivel->id == $profesorAsignado->nivel_id ? 'selected' : '' }}>
                                    {{ $nivel->nivel }} - {{ $nivel->turno }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Asignaturas:</label><br>
                        @foreach($asignaturas as $asignatura)
                            <input type="checkbox" name="asignaturas[]" value="{{ $asignatura->id }}" 
                                {{ in_array($asignatura->id, $asignacionesActuales) ? 'checked' : '' }}>
                            {{ $asignatura->nombre_asig }}<br>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.asignaturas.index') }}" class="btn btn-danger btn-sm">
                        <i class="fa fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-check"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
