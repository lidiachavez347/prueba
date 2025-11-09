{!! Form::model($materia,['route' => ['admin.materias.update',$materia->id],'method'=>'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-editar']) !!}
@csrf

@method('PUT')

@include('admin.materias.partials.form')

<div class="card-footer ">
    <center>
        <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
            <i class="fa fa-check"></i> Guardar
        </button>
    </center>
</div>
{!! Form::close() !!}