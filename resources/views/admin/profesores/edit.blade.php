
{!! Form::model($profesor,['route' => ['admin.profesores.update',$profesor->id],'method'=>'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-editar']) !!}

@csrf
@method('PUT')
@include('admin.profesores.partials.form')

<div class="card-footer ">
    <center>
        <!--<a class='btn btn-danger  btn-sm href' href="{{ route('admin.usuarios.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
            <i class="fa fa-arrow-left"></i> Cancelar
        </a>-->

        <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
            <i class="fa fa-check"></i> Guardar
        </button>
    </center>
</div>
{!! Form::close() !!}


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