{!! Form::model($permiso,['route' => ['admin.permisos.update',$permiso->id],'method'=>'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-editar']) !!}
@csrf

<!-- Aquí incluimos el formulario del permiso -->
@include('admin.permisos.partials.form')

<!-- Campo para asignar el permiso a uno o varios roles (con checkboxes) -->

<label for="roles">Asignar a roles</label>
<div class="checkbox-group">
    @foreach($roles as $id => $role)
    <div class="form-check">
        {!! Form::checkbox('roles[]', $id, in_array($id, $permisoRoles) ? true : false, ['class' => 'form-check-input', 'id' => 'role'.$id]) !!}
        {!! Form::label('role'.$id, $role, ['class' => 'form-check-label']) !!}
    </div>
    @endforeach
</div>



<div class="card-footer ">
    <center>
        <button type="submit" class="btn btn-success btn-sm" aria-label="guardar" data-toggle="tooltip" data-placement="top" title="Guardar">
            <i class="fa fa-check"></i> Guardar
        </button>
    </center>
</div>
{!! Form::close() !!}