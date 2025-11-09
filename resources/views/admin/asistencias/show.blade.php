@extends('adminlte::page')
@section('title', 'Asistencias')

@section('content')
@php
use Carbon\Carbon;
@endphp

<h2>Detalle de Asistencia {{ Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</h2>

<form method="GET" action="">
<!--editar estado y observaciones-->
    <button type="submit" class="btn btn-primary">Editar</button>
</form>
<div class="card body py-2 px-1">
    <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistencias as $asistencia)
            @foreach ($asistencia->detalles as $detalle)
            <tr>
                <td>{{ $asistencia->fecha }}</td>
                <td>{{ $detalle->user->nombres }} {{ $detalle->user->apellidos }}</td>
                <td>{{ $detalle->estado }}</td>
                <td>{{ $detalle->observaciones }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection