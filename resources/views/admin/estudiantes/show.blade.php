@extends('adminlte::page')
@section('title', 'Permiso')
@section('content_header')


<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Estudiante: {!! $estudiante->nombres !!} {!! $estudiante->apellidos !!}</h1>
            <h7>Estado:
                @if ($estudiante->estado == 1)
                <span class="badge badge-pill badge-success">ACTIVO</span>
                @elseif ($estudiante->estado == 0)
                <span class="badge badge-pill badge-danger">NO ACTIVO</span>
                @else
                <span class="badge badge-pill badge-warning">No permitido</span>
                @endif
            </h7>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active">Estudiante</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<br>

<div class="row">
    <div class="col-2">
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <div class="left">Estudiante</div>
                <div class="right"><b>Detalle</b></div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>RUDE:</strong>
                        <p>{{ $estudiante->rude }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>CI:</strong>
                        <p>{{ $estudiante->ci }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong>Nombre Completo:</strong>
                        <p>{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha de Nacimiento:</strong>
                        <p>{{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->isoFormat('D [de] MMMM [de] YYYY') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong>Género:</strong>
                        <p>{{ $estudiante->genero == '1' ? 'Masculino' : 'Femenino' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Curso:</strong>
                        <p>{{ $estudiante->curso->nombre_curso }}</p> <!-- Asume que tienes la relación con el curso -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong>Dirección:</strong>
                        <p>{{ $estudiante->direccion }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Imagen:</strong>
                        @if($estudiante->imagen)
                        <img src="{{ asset('images/' . $estudiante->imagen) }}" alt="Imagen del Estudiante" style="max-height: 150px;">
                        @else
                        <p>No se ha subido una imagen</p>
                        @endif
                    </div>
                </div>

                <!-- Mostrar información de los tutores -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>Tutores:</h4>
                        @forelse ($estudiante->tutors as $tutor)
                        <div class="card mb-3">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    @if($tutor->imagen_tutor)
                                    <img src="{{ asset('images/' . $tutor->imagen_tutor) }}" class="card-img" alt="Imagen del Tutor">
                                    @else
                                    <img src="{{ asset('images/default.png') }}" class="card-img" alt="Tutor por defecto">
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><strong>Nombre completo : </strong>{{ $tutor->nombres_tutor }} {{ $tutor->apellidos_tutor }}</h5>
                                        <p class="card-text"><strong>Teléfono:</strong> {{ $tutor->telefono }}</p>
                                        <p class="card-text"><strong>Relación:</strong> {{ $tutor->relacion }}</p>
                                        <p class="card-text"><strong>Estado:</strong>
                                            @if ($tutor->estado_tutor == 1)
                                            <span class="badge badge-success">Activo</span>
                                            @else
                                            <span class="badge badge-danger">No Activo</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>No hay tutores asignados.</p>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>
        <!-- Botón para regresar a la lista de permisos -->
        <div class="mt-3">
            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
        </div>

    </div>
</div>
@stop

@section('css')
<style>
    .left {
        float: left;
        width: 50%;
        /* Ajusta el ancho si es necesario */

    }

    .right {
        float: right;
        width: 10%;
        /* Ajusta el ancho si es necesario */

    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Puedes agregar algún script específico si lo necesitas
    });
</script>
@endsection