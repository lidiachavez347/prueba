@extends('adminlte::page')
@section('title', 'Estudiantes')
@section('content')
<div class="container">
    <h2 class="mb-4">Registrar Asistencia - {{ $fechaHoy }}</h2>

    <form action="{{ route('profesor.asistencias.store') }}" method="POST">
        @csrf
<div class="card-header">
                 <div class="right"> <button type="submit" class="btn btn-success mt-3">
             <i class="fa fa-check"></i> Guardar
        </button></div>
</div>
        <div class="card-body">
            
            <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
        <thead class="bg-dark text-white">
                    <tr>
                    <tr>
                        <th>#</th>
                        <th>RUDE</th>
                        <th>Nombre</th>
                        <th>Presente</th>
                        <th>Ausente</th>
                        <th>Justificado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantes as $estudiante)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $estudiante->rude_es }}</td>
                        <td class="text-start">{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                        <td>
                            <input type="radio" name="asistencias[{{ $estudiante->id }}][estado]" value="P" checked>
                        </td>
                        <td>
                            <input type="radio" name="asistencias[{{ $estudiante->id }}][estado]" value="A">
                        </td>
                        <td>
                            <input type="radio" name="asistencias[{{ $estudiante->id }}][estado]" value="J">
                        </td>

                        <input type="hidden" name="asistencias[{{ $estudiante->id }}][user_id]" value="{{ $estudiante->id }}">
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </form>
</div>

@section('css')
<style>
    /* Centrar todos los textos y mejorar espaciado */
    table.table th, table.table td {
        vertical-align: middle !important;
        padding: 12px 8px;
    }

    /* Alternar colores en filas */
    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    /* Hover en filas */
    table.table-hover tbody tr:hover {
        background-color: #e9ecef;
    }

    /* Radio buttons centrados */
    input[type="radio"] {
        transform: scale(1.2);
        cursor: pointer;
    }

    /* Encabezado más claro */
    .table-dark th {
        background-color: #343a40;
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        table.table th, table.table td {
            font-size: 14px;
            padding: 8px 5px;
        }
    }
</style>
@endsection

@endsection
