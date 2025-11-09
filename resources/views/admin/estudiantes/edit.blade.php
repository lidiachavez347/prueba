{!! Form::model($estudiante, ['route' => ['admin.estudiantes.update', $estudiante->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}
<div class="card-body">
    <div class="row">
        <div class="col">
            <div class="form-group">
                {!! Form::label('rude_es', 'RUDE:') !!}
                {!! Form::number('rude_es', null, ['class' => 'form-control', 'placeholder' => 'Rude']) !!}
                @error('rude_es') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                {!! Form::label('ci_es', 'CI:') !!}
                {!! Form::number('ci_es', null, ['class' => 'form-control', 'placeholder' => 'Cédula de Identidad']) !!}
                @error('ci_es') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                {!! Form::label('fecha_nac_es', 'Fecha de Nacimiento:') !!}
                {!! Form::date('fecha_nac_es', null, ['class' => 'form-control', 'placeholder' => 'Fecha']) !!}
                @error('fecha_nac_es') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group">
                {!! Form::label('nombres_es', 'Nombres:') !!}
                {!! Form::text('nombres_es', null, ['class' => 'form-control', 'placeholder' => 'Nombre Completo']) !!}
                @error('nombres_es') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                {!! Form::label('apellidos_es', 'Apellidos:') !!}
                {!! Form::text('apellidos_es', null, ['class' => 'form-control', 'placeholder' => 'Apellido Completo']) !!}
                @error('apellidos_es') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                {!! Form::label('genero_es', 'Género:') !!}
                {!! Form::select('genero_es', [null => 'SELECCIONE GÉNERO', '1' => 'MASCULINO', '0' => 'FEMENINO'], null, ['class' => 'form-control']) !!}
                @error('genero_es') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">

            <div class="form-group">
                {!! Form::label('imagen_es', 'Subir Imagen') !!}
                {!! Form::file('imagen_es', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col">
            @if ($estudiante->imagen_es > 0)
            <center>
                <div class="grid grid-cols-1 mt-5 mx-7">
                    <img id="imagenseleccionada" src="{{ URL::asset("images/{$estudiante->imagen_es}") }}"
                        style="max-height: 100px">
                </div>
            </center>
            @else
            <center>
                <div class="grid grid-cols-1 mt-5 mx-7">
                    <img id="imagenseleccionada"
                        style="max-height: 100px">
                </div>
            </center>
            @endif
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
                {!! Form::label('estado_es', 'Estado:') !!}
                {!! Form::select('estado_es', [null => 'SELECCIONE ESTADO', '0' => 'NO ACTIVO', '1' => 'ACTIVO'], null, ['class' => 'form-control']) !!}
                @error('estado_es') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>


    </div>

</div>


<div class="card-footer ">
    <center>

        <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
            <i class="fa fa-check"></i> Guardar
        </button>
    </center>
</div>
{!! Form::close() !!}


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
    $(document).ready(function(e) {
        $('#imagen').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#imagenseleccionada').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);

        });
    });
</script>
@endsection