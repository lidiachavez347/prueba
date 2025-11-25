<!-- Aquí incluimos el formulario del permiso -->
<div class="form-group">
    {!! Form::label('titulo','Titulo') !!}
    {!! Form::text('titulo', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el titulo']) !!}
    @error('titulo')
    <small class="text-danger">
        {{$message}}
    </small>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('detalle','Detalle') !!}
    {!! Form::text('detalle', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el Detalle']) !!}
    @error('detalle')
    <small class="text-danger">
        {{$message}}
    </small>
    @enderror
</div>

<input type="hidden" name="id_asignatura" value="{{ $tema->id_asignatura }}">
    <input type="hidden" name="id_trimestre" value="{{ $tema->id_trimestre }}">
    <input type="hidden" name="id_curso" value="{{ $tema->id_curso }}">

<div class="row">
    <div class="col-md-6">
        @if ($tema->archivo > 0)
        Archivo subido
        @else
        Ningun archivo
        @endif
        <div class="form-group">
            {!! Form::label('archivo', 'Subir archivo') !!}
            {!! Form::file('archivo', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form group">
            {!! Form::label('video','Video') !!}
            {!! Form::text('video', null, ['class'=>'form-control', 'placeholder'=>'Ingrese una URL']) !!}
            @error('video')
            <small class="text-danger">
                {{$message}}
            </small>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        @if ($tema->imagen > 0)
        <center>
            <div class="grid grid-cols-1 mt-5 mx-7">
                <img id="imagenseleccionada" src="{{ URL::asset("images/{$tema->imagen}") }}"
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
        <div class="form-group">
            {!! Form::label('imagen', 'Subir Imagen') !!}
            {!! Form::file('imagen', ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
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