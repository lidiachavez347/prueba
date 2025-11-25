@extends('adminlte::page') {{-- si usas AdminLTE --}}
@section('title', 'Notas del Estudiante')

@section('css')

@endsection
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
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Notas de estudiante</h1>
            <br>


        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Estudiantes</li>
            </ol>
        </div>
    </div>

</div>
@stop
@if(isset($mensaje))
    <div class="alert alert-warning">
        {{ $mensaje }}
    </div>
@endif

@section('content')
<br>
<div class="container-fluid">




@foreach($estudiante as $estudiante)
<div class="modal-footer">


        <a href="{{ route('pdf.pdf_trimestre', $estudiante->id) }}" class="btn btn-light" data-toggle="tooltip" data-placement="left" title="Exportar">
    <i class="fa fa-upload" aria-hidden="true"></i> Reporte
</a>
</div>
    <div class="container-fluid mb-5">

        

        <h4 class="mt-3">
            {{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}
        </h4>

        <p>RUDE: {{ $estudiante->rude_es ?? 'N/A' }}</p>

        <div class="card body py-2 px-1">
            <table id="productos" class="table table-striped shadow-lg mt-4">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Areas Curriculares</th>
                        @foreach($trimestres as $tri)
                            <th>{{ $tri }}</th>
                        @endforeach
                        <th> PROMEDIO ANUAL</th>
                    </tr>
                </thead>

                <tbody>

                @php
                    $notasEst = $notasAgrupadas[$estudiante->id] ?? [];
                @endphp

                @foreach($notasEst as $materia => $detalleTrimestres)

                    <tr>
                        <td class="materia-col">{{ $materia }}</td>

@php
                $suma = 0;
                $contador = 0;

                foreach ($trimestres as $tri) {
                    if (isset($detalleTrimestres[$tri])) {
                        $suma += $detalleTrimestres[$tri];
                        $contador++;
                    }
                }

                $promedioAnual = $contador > 0 ? round($suma / $contador, 1) : '-';
            @endphp

            @foreach($trimestres as $tri)
                <td>{{ $detalleTrimestres[$tri] ?? '-' }}</td>
            @endforeach

            {{-- Promedio anual --}}
            <td><b>{{ $promedioAnual }}</b></td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>
    </div>

@endforeach
</div>
@endsection
