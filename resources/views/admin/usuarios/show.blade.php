@extends('adminlte::page')
@section('title', 'Usuario')
@section('content_header')


<div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
            <h1 class="m-0">Usuario: {!! $usuario->nombres !!}</h1>
            <h7>Estado:
                @if ($usuario->estado_user == 1)
                <span class="badge badge-pill badge-success">ACTIVO</span>
                @elseif ($usuario->estado_user == 0)
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
                <li class="breadcrumb-item active"> Usuario</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')
<div class="row" style="margin: center">
    <div class="col-3"></div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <div class="left">Usuario</div>
                <div class="right"><b>Detalle</b></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            @foreach ($role as $rol)
                            
                            <b>Rol : </b>{{$rol->name}} <br>
                            <b>Nombres : </b>{{$rol->nombres}} <br> <b> Apellidos : </b>{{$rol->apellidos}}
                            <br> <b>Genero : </b> @if ($rol->genero == 1)
                            MASCULINO
                            @else
                            FEMENINO
                            @endif
                            <br>
                            <b>Email : </b> <a href="#"> {{$rol->email}}</a>
                            <br>
                            <b>Direccion : </b>{{$rol->direccion}}
                            @endforeach
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            @foreach ($role as $rol)


                            @if ($rol->imagen > 0)
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <b>Imagen : </b> <br> <img id="imagenseleccionada" src="{{ URL::asset("images/{$rol->imagen}") }}" style="max-height: 80px">
                            </div>
                            @else
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <img id="imagenseleccionada"
                                    style="max-height: 300px">
                            </div>
                            @endif<br>
                            <br>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
        </div>


    </div>
</div>

<br>


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

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/app.js') }}"></script>





@endsection