<!-- Vista: profesor/trimestre/show.blade.php -->
<div class="container">
    <p><strong>ID:</strong> {{ $tema->id }}</p>
    <p><strong>Titulo:</strong> {{ $tema->titulo }}</p>
    <p><strong>Detalle:</strong> {{ $tema->detalle }}</p>
    <p><strong>Asignatura:</strong> {{ $tema->asignatura->nombre_asig }}</p>
    <li><strong>Estado:</strong> 
            @if ($tema->estado == 1)
                Activo
            @else
                No Activo
            @endif
        </li>
   

</div>