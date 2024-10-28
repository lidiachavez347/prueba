@extends('adminlte::page')
@section('title', 'Editar estudiante')

@section('content')
<br>
<div class="row" style="margin: center">
    <div class="col-2">
    </div>
    <div class="col-8">
        <div class="card">

            <div class="card-header">
                <div class="left">Estudiante</div>
                <div class="right"><b>Editar</b></div>
            </div>
            {!! Form::model($estudiante, ['route' => ['admin.estudiantes.update', $estudiante->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('rude', 'RUDE:') !!}
                            {!! Form::number('rude', null, ['class' => 'form-control', 'placeholder' => 'Rude']) !!}
                            @error('rude') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('ci', 'CI:') !!}
                            {!! Form::number('ci', null, ['class' => 'form-control', 'placeholder' => 'Cédula de Identidad']) !!}
                            @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('fecha_nacimiento', 'Fecha de Nacimiento:') !!}
                            {!! Form::date('fecha_nacimiento', null, ['class' => 'form-control', 'placeholder' => 'Fecha']) !!}
                            @error('fecha_nacimiento') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('nombres', 'Nombres:') !!}
                            {!! Form::text('nombres', null, ['class' => 'form-control', 'placeholder' => 'Nombre Completo']) !!}
                            @error('nombres') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('apellidos', 'Apellidos:') !!}
                            {!! Form::text('apellidos', null, ['class' => 'form-control', 'placeholder' => 'Apellido Completo']) !!}
                            @error('apellidos') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('genero', 'Género:') !!}
                            {!! Form::select('genero', [null => 'SELECCIONE GÉNERO', '1' => 'MASCULINO', '0' => 'FEMENINO'], null, ['class' => 'form-control']) !!}
                            @error('genero') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <center>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <img id="imagenseleccionada" style="max-height: 100px" src="{{ asset('images/' . $estudiante->imagen) }}" alt="Imagen actual">
                            </div>
                        </center>
                        <div class="form-group">
                            {!! Form::label('imagen', 'Subir Imagen') !!}
                            {!! Form::file('imagen', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('id_curso', 'Curso:') !!}
                            {!! Form::select('id_curso', $cursos, null, ['class' => 'form-control']) !!}
                            @error('id_curso')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('direccion', 'Dirección:') !!}
                            {!! Form::textarea('direccion', null, ['class' => 'form-control', 'placeholder' => 'Dirección del Usuario', 'rows' => 3, 'style' => 'resize: none;']) !!}
                            @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {!! Form::label('estado', 'Estado:') !!}
                            {!! Form::select('estado', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, ['class' => 'form-control']) !!}
                            @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            </div>


            <div class="card-footer ">
                <center>
                    <a class='btn btn-danger btn-sm' href="{{ route('admin.estudiantes.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
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