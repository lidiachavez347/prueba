<!-- Vista: profesor/trimestre/show.blade.php -->
<div class="container">
    <p><strong>ID:</strong> {{ $tarea->id }}</p>
    <p><strong>Nombre:</strong> {{ $tarea->nombre }}</p>
    <p><strong>Detalle:</strong> {{ $tarea->descripcion }}</p>
    <p><strong>Archivo:</strong> {{ $tarea->archivo }}</p>
    <p><strong>Imagen:</strong> {{ $tarea->imagen }}</p>
    <p><strong>Video:</strong> {{ $tarea->video }}</p>
    <p><strong>Evaluar:</strong> {{ $tarea->evaluar }}</p>
    <p><strong>Fecha:</strong> {{ $tarea->fecha }}</p>
    <p><strong>Tema:</strong> {{ $tarea->tema->titulo }}</p>
    <p><strong>Trimestre:</strong> {{ $tarea->trimestre->periodo }}</p>
    <p><strong>Criterio:</strong> {{ $tarea->criterio->nombres }}</p>
    <p><strong>Asignatura:</strong> {{ $tarea->asignatura->nombre_asig }}</p>
    <li><strong>Estado:</strong> 
            @if ($tarea->estado == 1)
                Activo
            @else
                No Activo
            @endif
        </li>


</div>