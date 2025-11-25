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

            {!! Form::open(['route' => 'admin.config.store', 'enctype' => 'multipart/form-data']) !!}
            @csrf


            <div class="row">

                <div class="col">
                    <div class="form-group">
                        {!! Form::label('nombre', 'Nombre:') !!}
                        {!! Form::text('nombre', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'Nombre de la institución']) !!}
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col">
                <div class="form-group">
                    {!! Form::label('direccion', 'Dirección:') !!}
                    {!! Form::text('direccion', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'Dirección de la institución']) !!}
                    @error('direccion')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                </div>

                <div class="col">
                    
                <div class="form-group">
                    {!! Form::label('telefono', 'Teléfono:') !!}
                    {!! Form::number('telefono', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'Ejemplo: 71823456']) !!}
                    @error('telefono')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
            </div>

            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        {!! Form::label('logo', 'Logo:') !!}
                        {!! Form::file('logo', ['class' => 'form-control', 'accept' => 'image/*','required' => true, // Hace obligatorio el campo
                        ]) !!}
                        @error('logo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        {!! Form::label('email', 'Correo electrónico:') !!}
                        {!! Form::email('email', null, ['class' => 'form-control', 'required' => true, 'placeholder' => 'ejemplo@gmail.com']) !!}
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        {!! Form::label('estado', 'Estado:') !!}
                        {!! Form::select('estado', [1 => 'ACTIVO', 0 => 'INACTIVO'], null, ['class' => 'form-control', 'required' => true]) !!}
                        @error('estado')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection