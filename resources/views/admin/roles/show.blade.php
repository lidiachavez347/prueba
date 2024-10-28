@extends('adminlte::page')
@section('title', 'Rol')
@section('content_header')


<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">{!! $role->name !!}</h1>
            <h7>Estado:
                @if ($role->estado == 1)
                <span class="badge badge-pill badge-success">ACTIVO</span>
                @elseif ($role->estado == 0)
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
                <li class="breadcrumb-item active"> Roles</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<br>

<div class="row" style="margin: center">
    <div class="col-2">
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <div class="left">Permisos</div>
                <div class="right"><b>Lista</b></div>
            </div>

            <div class="card-body">
                @foreach ($permissions as $permission)
                <div>
                    <label>
                        <!-- Checkbox deshabilitado porque solo se muestran los permisos asignados -->
                        {!! Form::checkbox('permissions[]', $permission->id, true, ['class'=>'mr-1', 'disabled']) !!}
                        {{$permission->description}}
                    </label>
                </div>
                @endforeach
            </div>


        </div>
        <!-- Botón para regresar a la lista de permisos -->
        <div class="mt-3">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
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