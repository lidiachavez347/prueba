@extends('adminlte::page')
@section('title', 'Estudiantes')
@section('content')
<div class="container">
    <h2>Registrar Asistencia - {{ $fechaHoy }}</h2>
    <form action="{{ route('profesor.asistencias.store') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>RUDE</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estudiantes as $estudiante)
                <tr>
                    <td>{{ $estudiante->id }}</td>
                    <td>{{ $estudiante->rude_es }}</td>
                    <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                    <td>
                        <select name="asistencias[{{ $loop->index }}][estado]" class="form-control">
                            <option value="presente">Presente</option>
                            <option value="ausente">Ausente</option>
                            <option value="justificado">Justificado</option>
                        </select>
                        <input type="hidden" name="asistencias[{{ $loop->index }}][user_id]" value="{{ $estudiante->id }}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Guardar Asistencias</button>
    </form>
</div>
@endsection