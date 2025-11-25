@extends('adminlte::page')
@section('title', 'Editar Asistencias')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Asistencias - Fecha: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</h2>
    
    <form action="{{ route('profesor.asistencias.update', ['curso_id' => $curso_id, 'fecha' => $fecha]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-header">
                
                <div class="right"><b><button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i> Guardar
            </button></b></div>
            </div>

        <div class="card-body">
            
            <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
        <thead class="bg-dark text-white">
                    <tr>
                        <th>#</th>
                        <th class="text-start">Nombre</th>
                        <th>Presente</th>
                        <th>Ausente</th>
                        <th>Justificado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantes as $index => $estudiante)
                        @php
                            $estado = $asistencias[$estudiante->id][0]->estado ?? '';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-start">{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                            <td>
                                <input type="radio" name="asistencias[{{ $estudiante->id }}]" value="P" {{ $estado == 'P' ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="radio" name="asistencias[{{ $estudiante->id }}]" value="A" {{ $estado == 'A' ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="radio" name="asistencias[{{ $estudiante->id }}]" value="J" {{ $estado == 'J' ? 'checked' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </form>
</div>

@section('css')
<style>
    .left {
        float: left;
        width: 50%;
    }

    .right {
        float: right;
        width: 10%;
    }
</style>
<style>
    table.table th, table.table td {
        vertical-align: middle !important;
        padding: 12px 8px;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    table.table-hover tbody tr:hover {
        background-color: #e9ecef;
    }

    input[type="radio"] {
        transform: scale(1.2);
        cursor: pointer;
    }

    @media (max-width: 768px) {
        table.table th, table.table td {
            font-size: 14px;
            padding: 8px 5px;
        }
    }
</style>
@endsection

@stop
