<!-- Vista: profesor/trimestre/show.blade.php -->
<div class="container">
    <p><strong>ID:</strong> {{ $trimestre->id }}</p>
    <p><strong>Periodo:</strong> {{ $trimestre->periodo }}</p>
    <p><strong>fecha inicio:</strong> {{ $trimestre->fecha_inicio }}</p>
    <p><strong>Fecha fin:</strong> {{ $trimestre->fecha_fin }}</p>
    <li><strong>Estado:</strong> 
            @if ($trimestre->estado == 1)
                Activo
            @else
                No Activo
            @endif
        </li>
 
</div>