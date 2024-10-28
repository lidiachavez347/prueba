@extends('adminlte::page')
@section('title', 'Permiso')
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
            <h1 class="m-0">Permiso: {!! $permission->name !!}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active">Permisos</li>
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
                <div class="left">Detalle del Permiso</div>
            </div>

            <div class="card-body">
                <h5><b>Nombre:</b> {{ $permission->name }}</h5>
                <h5><b>Descripción:</b> {{ $permission->description }}</h5>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="left">Roles que tienen este permiso</div>
            </div>

            <div class="card-body">
                @if($roles->isEmpty())
                    <p>No hay roles asignados a este permiso.</p>
                @else
                    <ul>
                        @foreach ($roles as $role)
                            <li>{{ $role->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            
        </div>
            <!-- Botón para regresar a la lista de permisos -->
            <div class="mt-3">
            <a href="{{ route('admin.permisos.index') }}" class="btn btn-secondary">
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
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Puedes agregar algún script específico si lo necesitas
    });
</script>
@endsection
