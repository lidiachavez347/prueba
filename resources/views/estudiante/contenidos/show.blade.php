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
            <h1 class="m-0">Tarea en aula</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Tarea</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container">
    <h1>Detalles de la Actividad</h1>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3>{{ $actividad->nombre }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Descripción:</strong> {{ $actividad->descripcion }}</p>

        </div>
    </div>

    <div class="card">

        @if ($detalle->estado == 'pendiente')

        {!! Form::open(['route' => 'estudiante.tareas.store', 'method' => 'POST','enctype' => 'multipart/form-data']) !!}
        <div class="card-header text-center bg-dark text-white">
            <h4><b>Enviar Tarea</b></h4>
        </div>

        <div class="card-body">
            {{-- Campo para subir archivo --}}
            <div class="form-group">
                {!! Form::label('archivo', 'Subir Archivo') !!}
                <input type="file" name="archivo" class="form-control" accept=".pdf,.doc,.docx,.zip,.rar">
                @error('archivo')
                <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            {{-- Campo para subir imagen --}}
            <div class="form-group">
                {!! Form::label('imagen', 'Subir Imagen') !!}
                <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*">
                <div class="mt-2 text-center">
                    <img id="imagenseleccionada" src="" alt="Imagen seleccionada" style="max-width: 100%; height: auto; display: none;" />
                    <button id="eliminar-imagen" type="button" class="btn btn-danger btn-sm mt-2 d-none">Eliminar Imagen</button>
                </div>
            </div>

            {{-- Campos ocultos --}}
            <input type="hidden" name="id_estudiante" type="number" value="{{ auth()->user()->id }}">
            <input type="hidden" name="id_actividad" type="number" value="{{ $detalle->actividad->id }}">
        </div>

        <div class="card-footer text-center">
            <a href="{{ route('estudiante.contenidos.index') }}" class="btn btn-danger btn-sm">
                <i class="fa fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-check"></i> Enviar Tarea
            </button>
        </div>
        {!! Form::close() !!}
        @else
        <div class="card-header bg-secondary text-white">
            <h4>Mi Envío</h4>
        </div>
        <div class="card-body">
            @if ($detalle->archivo)
            <p><strong>Archivo Subido:</strong> <a href="{{ asset('storage/' . $detalle->archivo) }}" target="_blank">Ver Archivo</a></p>
            @else
            <p>No se ha subido ningún archivo.</p>
            @endif

            @if ($detalle->imagen)
            <p><strong>Imagen Subida:</strong></p>
            <img width="100px" src="{{ asset('storage/' . $detalle->imagen) }}" alt="Imagen subida" class="img-fluid">
            @else
            <p>No se ha subido ninguna imagen.</p>
            @endif

            <p><strong>Estado:</strong> {{ $detalle->estado }}</p>
            <p><strong>Observación del Docente:</strong> {{ $detalle->observacion ?? 'Sin observaciones' }}</p>
            <p><strong>Nota:</strong> {{ $detalle->nota ?? 'Sin calificación' }}</p>
        </div>
        @endif
    </div>
</div>
<script>
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagenseleccionada');
        const deleteButton = document.getElementById('eliminar-imagen');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                deleteButton.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('eliminar-imagen').addEventListener('click', function() {
        const fileInput = document.getElementById('imagen');
        const preview = document.getElementById('imagenseleccionada');

        fileInput.value = '';
        preview.src = '';
        preview.style.display = 'none';
        this.classList.add('d-none');
    });
</script>
@endsection