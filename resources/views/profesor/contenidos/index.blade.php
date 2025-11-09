@extends('adminlte::page')
@section('title', 'Estudiantes')
@section('content_header')
<style>
    .accordion-button {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .list-group-item ul {
        list-style-type: disc;
        margin-left: 20px;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        line-height: 1.6;
    }

    .tema {
        margin-bottom: 20px;
    }

    .tema h2 {
        color: #333;
    }

    .tareas {
        list-style-type: none;
        padding-left: 0;
    }

    .tareas li {
        margin: 10px 0;
        padding: 10px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Contenido</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Contenido</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row pb-3 d-print-block">
    <div class="col-12">
        <section>
            <!-- INICIO card -->
            <div class="card">
                <div class="card-body">
                    <center>
                        <dd class="alert alert-light" role="alert">
                            <img width="35px" src="<?php echo e(URL::asset('images/contenido.png')); ?>"> <b>PLAN ACADÉMICO</b>
                        </dd>
                    </center>
                    <!-- div course-content -->
                    <div class="course-content">
                        <div class="topics">
                            <div class="section main clearfix">
                                <!-- INICIO CONTENT -->
                                <div class="content">
                                    @foreach ($nivel as $niveles)
                                    <center>
                                        <dd class="alert alert-primary" role="alert">
                                            <img width="35px" src="<?php echo e(URL::asset('images/planificacion.png')); ?>"> <b>{{ $niveles->trimestre->periodo }}</b>
                                        </dd>
                                    </center>
                                    @if ($niveles->trimestre->estado == 1)
                                    @foreach ($materias as $materia)
                                    @if ($materia->estado_asig == 1)
                                    <center>
                                        <dd class="alert alert-light" role="alert">
                                            <img width="35px" src="<?php echo e(URL::asset('images/libro.png')); ?>"> <b>{{ $materia->nombre_asig }}</b>
                                        </dd>
                                    </center>
                                    <div class="tema">
                                        @foreach ($temas as $tema)
                                        @if ($tema->id_trimestre == $niveles->id_trimestre && $tema->id_asignatura == $materia->id)

                                       <img width="35px" src="<?php echo e(URL::asset('images/temas.png')); ?>"> Tema: {!! $tema->titulo !!}
                                        <p id="ckcontent">{!! $tema->detalle !!}</p>
                                        <hr>
                                        @if (!empty($tema->imagen))
                                        <center>
                                            <img width="50%" src="{{ URL::asset("images/{$tema->imagen}") }}">
                                        </center>
                                        @endif
                                        @if ($tema->video > 0)
                                        <center>
                                            <iframe width="900px" height="600px" frameborder="2"
                                                src="{{ $tema->video }}"></iframe>
                                        </center>
                                        @endif
                                        @if ($tema->archivo > 0)

                                        <a href="" target="_blank">
                                            <img width="35px" src="{{ URL::asset('archivo/archivo.png') }}">
                                            {{ $tema->archivo }}
                                        </a>
                                        @endif
                                        <ul class="tareas">
                                            @foreach ($actividades as $actividad)
                                            @if ($actividad->tema->id == $tema->id)
                                            @if ($actividad->tipo == 'CASA')
                                            <li>
                                                <a>
                                                    <img width="35px" src="<?php echo e(URL::asset('images/tarea.png')); ?>">
                                                    {{ $actividad->nombre }}
                                                </a>
                                                <p>{!! $actividad->descripcion !!}</p>
                                                <br>
                                            </li>
                                            @elseif($actividad->tipo == 'AULA')
                                            <li>
                                                <a href="{{ route('profesor.contenidos.index', $actividad->id) }}">
                                                    <img width="35px" src="<?php echo e(URL::asset('images/tarea.png')); ?>">
                                                    {{ $actividad->nombre }}
                                                </a>
                                                <p>{!! $actividad->descripcion !!}</p>
                                                <br>
                                            </li>
                                            @endif
                                            @endif
                                            @endforeach
                                        </ul>
                                        <ul class="tareas">
                                            @foreach ($evaluaciones as $evaluacion)
                                            @if ($evaluacion->tema->id == $tema->id)
                                            @if ($evaluacion->tipo == 'ESCRITO')
                                            <li>
                                                <a>
                                                    <img width="35px" src="<?php echo e(URL::asset('images/examen.png')); ?>">
                                                    {{ $evaluacion->nombre }}
                                                </a>

                                                <br>
                                            </li>
                                            @elseif($evaluacion->tipo == 'AUTOMATICO')
                                            <li>

                                                <a href="{{ route('profesor.contenidos.index', $evaluacion->id) }}">
                                                    <img width="35px" src="<?php echo e(URL::asset('images/examen.png')); ?>">
                                                    {{ $evaluacion->nombre }}
                                                </a>

                                                <br>
                                            </li>
                                            @endif
                                            @endif
                                            @endforeach
                                        </ul>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


@stop
@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

@endsection