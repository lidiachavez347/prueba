@extends('adminlte::page')
@section('title', 'Institución')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
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
            <h1 class="m-0">Institución</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Configuracion</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')
<div class="card">
    <div class="card-header">



        <h4>Datos de la Institución</h4>

    </div>
    <div class="card-body">
        <div class="card-button">

            <form action="{{ route('admin.config.edit',$institucion->id) }}" method="POST">
                @csrf
                @if($institucion)
                <!-- Si hay datos, los mostramos para actualizar -->
                <input type="hidden" name="id" value="{{ $institucion->id }}">
                @endif

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $institucion->nombre ?? '') }}" readonly required>
                            @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $institucion->direccion ?? '') }}" readonly required>
                            @error('direccion')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $institucion->telefono ?? '') }}" readonly required>
                            @error('telefono')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            
                            @if($institucion && $institucion->logo)
                            <div class="mt-2">
                                <img src="{{ asset('images/' . $institucion->logo) }}" alt="Logo Actual" width="100">
                            </div>
                            @endif
                            @error('logo')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $institucion->email ?? '') }}" readonly required>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" class="form-control"  disabled >
                                <option   disabled  value="1" {{ old('estado', $institucion->estado ?? '') == 1 ? 'selected' : '' }} >Activo</option>
                                <option   disabled  value="0" {{ old('estado', $institucion->estado ?? '') == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">Editar</button>
            </form>
        </div>
    </div>
</div>
@endsection