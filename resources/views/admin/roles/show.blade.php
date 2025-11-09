<!-- Vista: admin/permisos/show.blade.php -->
<div class="container">

    <p><strong>ID:</strong> {{ $role->id }}</p>
    <p><strong>Nombre:</strong> {!! $role->name !!}</p>
    <h7>Estado:
        @if ($role->estado == 1)
        <span class="badge badge-pill badge-success">ACTIVO</span>
        @elseif ($role->estado == 0)
        <span class="badge badge-pill badge-danger">NO ACTIVO</span>
        @else
        <span class="badge badge-pill badge-warning">No permitido</span>
        @endif
    </h7>
    <hr>
    <h5>Permisos Asociados:</h5>
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



