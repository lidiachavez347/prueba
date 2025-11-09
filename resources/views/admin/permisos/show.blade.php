<!-- Vista: admin/permisos/show.blade.php -->
<div class="container">

    <p><strong>ID:</strong> {{ $permission->id }}</p>
    <p><strong>Nombre:</strong> {{ $permission->name }}</p>
    <p><strong>Descripción:</strong> {{ $permission->description }}</p>
    <p><strong>Fecha de Creación:</strong> {{ $permission->created_at->format('d/m/Y') }}</p>
    <hr>
    <h5>Roles Asociados:</h5>
    @if($roles->isEmpty())
        <p>No hay roles asociados a este permiso.</p>
    @else
        <ul>
            @foreach($roles as $role)
                <li>{{ $role->name }}</li>
            @endforeach
        </ul>
    @endif
</div>


