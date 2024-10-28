@extends('adminlte::page')
@section('title', 'Editar permiso')

@section('content')
<br>
<div class="row" style="margin: center">
    <div class="col-3">
    </div>
    <div class="col-6">
        <div class="card">

            <div class="card-header">
                <div class="left">Permiso</div>
                <div class="right"><b>Editar</b></div>
            </div>
            {!! Form::model($permiso, ['route' => ['admin.permisos.update', $permiso->id], 'method' => 'PUT']) !!}
            <div class="card-body">
                
                <!-- Aquí incluimos el formulario del permiso -->
                @include('admin.permisos.partials.form')

                <!-- Campo para asignar el permiso a uno o varios roles (con checkboxes) -->
                <div class="form-group">
                    <label for="roles">Asignar a roles</label>
                    <div class="checkbox-group">
                        @foreach($roles as $id => $role)
                            <div class="form-check">
                                {!! Form::checkbox('roles[]', $id, in_array($id, $permisoRoles) ? true : false, ['class' => 'form-check-input', 'id' => 'role'.$id]) !!}
                                {!! Form::label('role'.$id, $role, ['class' => 'form-check-label']) !!}
                            </div>
                        @endforeach
                    </div>
                </div>
                
            </div>

            <div class="card-footer ">
                <center>
                    <a class='btn btn-danger btn-sm' href="{{ route('admin.permisos.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
                        <i class="fa fa-arrow-left"></i> Cancelar
                    </a>

                    <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                </center>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .left {
        float: left;
        width: 50%;
    }

    .right {
        float: right;
        width: 10%;
    }
</style>
<link rel="stylesheet" href="/css/admin_custom.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
@stop

@section('js')
<script>
    // Aquí puedes añadir JavaScript si necesitas validar o procesar algo al enviar el formulario
</script>
@stop
