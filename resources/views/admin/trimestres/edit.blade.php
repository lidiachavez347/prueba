{!! Form::model($trimestre,['route' => ['admin.trimestres.update',$trimestre->id],'method'=>'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-editar']) !!}

   @csrf
    @method('PUT')
@include('admin.trimestres.partials.form')
<br>
<div class="card-footer ">
    <center>
        <!--<a class='btn btn-danger  btn-sm href' href="{{ route('admin.trimestres.index') }}" data-toggle="tooltip" data-placement="top" title="Cancelar">
            <i class="fa fa-arrow-left"></i> Cancelar
        </a>-->

        <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
            <i class="fa fa-check"></i> Guardar
        </button>
    </center>
</div>

{!! Form::close() !!}
