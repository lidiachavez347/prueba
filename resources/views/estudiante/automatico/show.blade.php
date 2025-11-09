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


@stop

@section('content')
<div class="container">
    <div class="header">
        <h1>{{ $examen->nombre }}</h1>
        <p>{{ $examen->descripcion }}</p>
    </div>

    @if($resultado)
    <!-- Si ya respondió el examen, mostrar un mensaje o redirigir -->
    <div class="alert alert-info">
        <p>Ya has respondido este examen. No puedes enviarlo nuevamente.</p>
    </div>
    @else
    <!-- Formulario para enviar respuestas -->
    {!! Form::open(['route' => 'estudiante.automatico.store', 'method' => 'POST']) !!}

    @foreach ($preguntas as $pregunta)
    <div class="question">
        <h3>{{ $loop->iteration }}. {!! $pregunta->descripcion !!}</h3>

        <!-- Mostrar las opciones de respuesta -->
        <div class="answers">
            @foreach ($pregunta->answers as $opcion)
            <label>
                <input type="radio" name="pregunta_{{ $pregunta->id }}" value="{{ $opcion->id }}">
                {!! $opcion->opcion !!}
            </label><br>
            @endforeach
        </div>
    </div>
    @endforeach
    <input type="number" name="examen" hidden value="{{ $examen->id }}">

    <!-- Botones de navegación -->
    <div class="navigation">
        <button type="submit" class="btn btn-primary">Enviar Respuestas</button>
        <a href="{{ route('estudiante.contenidos.index') }}" class="btn btn-danger">Cancelar</a>
    </div>

    {!! Form::close() !!}
    @endif
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        margin: 0;
        color: #343a40;
    }

    .question {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .question img {
        max-width: 100%;
        height: auto;
        margin: 10px 0;
        border-radius: 8px;
    }

    .answers {
        margin-top: 10px;
    }

    .answers input {
        margin-right: 10px;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        text-decoration: none;
        text-align: center;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-danger:hover {
        background-color: #a71d2a;
    }
</style>
@endsection