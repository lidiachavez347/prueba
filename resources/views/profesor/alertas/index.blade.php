@extends('adminlte::page')
@section('title', 'Alertas')
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
            <h1 class="m-0">Alertas de Estudiantes</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Alertas</li>
            </ol>
        </div>
    </div>

</div>
@stop

@section('content')


@foreach ($alertas as $alerta)
@if($alerta->promedio < 16.00)
    @if($alerta->tipo == 'tarea')
    <div class="alert alert-warning">
        {{ $alerta->id }}
        {{ $alerta->nombre_estudiante }}
        tiene un promedio de {{ $alerta->promedio }} de tipo TAREA {{ $alerta->tipo }}
        en la materia de {{ $alerta->asignatura }}

        fecha alerta {{ $alerta->fecha_alerta }}
    </div>
    @else
    <div class="alert alert-danger">
        {{ $alerta->id }}
        {{ $alerta->nombre_estudiante }}
        tiene un promedio de {{ $alerta->promedio }} de tipo EVALUACIONES {{ $alerta->tipo }}
        en la materia de {{ $alerta->asignatura }}

        fecha alerta {{ $alerta->fecha_alerta }}
    </div>
    @endif
    @endif
    @endforeach


    @endsection