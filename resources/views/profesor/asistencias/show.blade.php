@extends('adminlte::page')
@section('title', 'Estudiantes')
@section('content_header')
<style>
    .colored-toast.swal2-icon-success {
        background-color: #a5dc86 !important;
    }

    .colored-toast.swal2-icon-error {
        background-color: #f27474 !important;
    }

    .colored-toast.swal2-icon-warning {
        background-color: #f8bb86 !important;
    }

    .colored-toast.swal2-icon-info {
        background-color: #3fc3ee !important;
    }

    .colored-toast.swal2-icon-question {
        background-color: #87adbd !important;
    }

    .colored-toast .swal2-title {
        color: white;
    }

    .colored-toast .swal2-close {
        color: white;
    }

    .colored-toast .swal2-html-container {
        color: white;
    }

    .rotate-text {
        transform: rotate(-90deg);
        white-space: nowrap;
        /* Para evitar que el texto se divida */
        text-align: center;
        vertical-align: middle;
        /* Centrar el texto */
        width: 40px;
        /* Ajusta el ancho si es necesario */
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Asistencias de Estudiantes</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Asistencias</li>
            </ol>
        </div>
    </div>

</div>
@stop

@section('content')
<div class="container">
    <h1>Notas de Estudiantes</h1>

    <!-- Selector de materia -->
    <form method="GET" action="{{ route('profesor.asistencias.show') }}" class="mb-3">
        <label for="mes">Seleccione el Mes:</label>
        <select id="mes" name="mes" onchange="this.form.submit()">
            @foreach ($meses as $mes => $nombre)
            <option value="{{ $mes }}" {{ $mesSeleccionado == $mes ? 'selected' : '' }}>{{ $nombre }}</option>
            @endforeach
        </select>
    </form>

    <!-- Tabla de asistencias -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                @foreach ($fechas as $fecha)
                <th class="rotate-text">{{ $fecha->format('d/m/Y') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiantes as $estudiante)
                <tr>
                    <td>{{ $estudiante->id }}</td>
                    <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                    @foreach ($fechas as $fecha)
                        @php
                        // Crear la clave para acceder a las asistencias
                        $key = $estudiante->id . '_' . $fecha->format('Y-m-d');
                        $asistencia = $asistenciasAgrupadas->get($key); // Buscar la asistencia por la clave
                        @endphp
                        <td>
                            @if ($asistencia)
                            {{ ucfirst($asistencia->first()->estado) }} <!-- Mostrar el estado de la asistencia -->
                            @else
                            0 <!-- No hay asistencia registrada -->
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>


</div>
@endsection