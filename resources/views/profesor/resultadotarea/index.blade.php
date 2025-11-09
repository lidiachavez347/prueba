@extends('adminlte::page')
@section('title', 'Estudiantes')
@section('content_header')
<style>
    .colored-toast.swal2-icon-success {
        background-color: #a5dc86 !important;
    }

    .colored-toast.swal2-icon-error {
        background-color: #f27474 !important;
    }

    .colored-toast.swal2-icon-warning {
        background-color: #f8bb86 !important;
    }

    .colored-toast.swal2-icon-info {
        background-color: #3fc3ee !important;
    }

    .colored-toast.swal2-icon-question {
        background-color: #87adbd !important;
    }

    .colored-toast .swal2-title {
        color: white;
    }

    .colored-toast .swal2-close {
        color: white;
    }

    .colored-toast .swal2-html-container {
        color: white;
    }

    .rotate-text {
        transform: rotate(-90deg);
        white-space: nowrap;
        /* Para evitar que el texto se divida */
        text-align: center;
        vertical-align: middle;
        /* Centrar el texto */
        width: 40px;
        /* Ajusta el ancho si es necesario */
    }

    .bg-danger {
        background-color: #f8d7da !important;
    }

    .text-white {
        color: #ffffff !important;
    }
</style>

</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Notas de Estudiantes HACER</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Centralizador</li>
            </ol>
        </div>
    </div>

</div>
@stop

@section('content')


<!-- Formulario para seleccionar asignatura y trimestre -->
<form action="{{ route('profesor.resultadotarea.index') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="id_asignatura">Seleccionar Asignatura:</label>
                <select name="id_asignatura" id="id_asignatura" class="form-control" required>
                    @foreach ($asignaturas as $asignatura)
                    <option value="{{ $asignatura->id }}" {{ request('id_asignatura') == $asignatura->id ? 'selected' : '' }}>
                        {{ $asignatura->nombre_asig }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="id_trimestre">Seleccionar Trimestre:</label>
                <select name="id_trimestre" id="id_trimestre" class="form-control" required>
                    @foreach ($trimestres as $trimestre)
                    <option value="{{ $trimestre->id }}" {{ request('id_trimestre') == $trimestre->id ? 'selected' : '' }}>
                        {{ $trimestre->periodo }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-dark btn-sm">Filtrar</button>
        </div>
    </div>
</form>
<div class="card body py-2 px-1">
    <!-- Tabla de notas 


    <table id="productos" class="table table-striped">

        <thead>
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                @foreach ($actividades as $actividad)
                <th class="rotate-text">
                    <p>{{ $actividad->nombre }} <br>{{ $actividad->fecha }}</p>
                </th>

                @endforeach
                <th>Promedio</th>
            </tr>
        </thead>
        @php
        $alertas = collect();
        @endphp
        <tbody>
                @foreach ($estudiantes as $estudiante)
                @php
                // Inicializar variables para calcular el promedio
                $sumaNotas = 0;
                $cantidadNotas = 0;
                @endphp
                <tr>
                    <td>{{ $estudiante->id }}</td>
                    <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                    @foreach ($actividades as $actividad)
                    @php
                    // Buscar la nota de este estudiante para esta actividad
                    $nota = $estudiante->notas->where('id_actividad', $actividad->id)->first();
                    if ($nota) {
                    $sumaNotas += $nota->nota; // Sumar la nota
                    $cantidadNotas++; // Incrementar el contador de notas
                    $este = $actividad->asignatura->nombre_asig;
                    }
                    @endphp
                    <td>
                        @if ($nota)
                        {{ $nota->nota }}
                        @else
                        0 
                        @endif
                    </td>

                    @endforeach

                    @php
                    $promedio = $cantidadNotas > 0 ? $sumaNotas / $cantidadNotas : 0;

                    // Guardar en la colección de alertas si el promedio es menor a 15
                    if ($promedio < 15) {
                        $alertas->push([
                        'nombre' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
                        'promedio' => number_format($promedio, 2),
                        // Asegúrate de que la variable $materia esté disponible
                        ]);
                        }
                        @endphp
                        <td class="@if($promedio < 15) bg-danger text-dark @endif">
                            {{ number_format($promedio, 2) }} 
                        </td>
                </tr>
                @endforeach
        </tbody>
    </table>
    -->
    <table id="productos" class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                @foreach ($actividades as $actividad)
                <th class="rotate-text">
                    <p>{{ $actividad->nombre }} <br>{{ $actividad->fecha }}</p>
                </th>
                @endforeach
                <th>Promedio</th>
            </tr>
        </thead>

        <tbody>
            @php
            $alertas = collect(); // Colección para almacenar alertas, agrupadas por materia
            @endphp

            @foreach ($estudiantes as $estudiante)
            @php
            $sumaNotas = 0;
            $cantidadNotas = 0;
            $mat = '';
            @endphp

            <tr>
                <td>{{ $estudiante->id }}</td>
                <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>

                @foreach ($actividades as $actividad)
                @php
                // Buscar notas en ActividadDetalle
                $notaDetalle = $estudiante->notasActividadDetalle->where('id_actividad', $actividad->id)->first();

                // Buscar notas en NotaActividades
                $notaActividad = $estudiante->notasActividades->where('id_actividad', $actividad->id)->first();

                // Determinar la nota final para esta actividad (puedes sumar o elegir una)
                $nota = $notaDetalle?->nota ?? $notaActividad?->nota;

                // Si hay una nota, acumular para el promedio
                if ($nota !== null) {
                $sumaNotas += $nota;
                $cantidadNotas++;
                $mat = $actividad->asignatura->nombre_asig;
                }
                @endphp

                <td>
                    {{ $nota !== null ? $nota : 0 }} <!-- Mostrar la nota o 0 -->
                </td>
                @endforeach

                @php
                // Calcular el promedio del estudiante
                $promedio = $cantidadNotas > 0 ? $sumaNotas / $cantidadNotas : 0;

                // Agregar alerta si el promedio es menor a 15
                if ($promedio < 15) {
                    // Asumir que la asignatura es parte de la actividad (se debe ajustar según tus relaciones)
                    $alertas->push([
                    'nombre' => $estudiante->nombres_es . ' ' . $estudiante->apellidos_es,
                    'promedio' => number_format($promedio, 2),
                    'asignatura' => $mat, // Asignatura relacionada con la actividad
                    ]);
                    }
                    @endphp

                    <td class="@if($promedio < 15) bg-danger text-dark @endif">
                        {{ number_format($promedio, 2) }}
                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($alertas->isNotEmpty())
    <div class="alert alert-danger">
        <h4>Alertas:</h4>

        @php
        // Agrupar alertas por asignatura
        $alertasAgrupadas = $alertas->groupBy('asignatura');
        @endphp

        @foreach ($alertasAgrupadas as $asignatura => $alertasPorMateria)
        <h5> {{ $asignatura}}</h5>

        <ul>
            @foreach ($alertasPorMateria as $alerta)
            <li>{{ $alerta['nombre'] }} tiene un promedio bajo: {{ $alerta['promedio'] }}</li>
            @endforeach
        </ul>
        @endforeach
    </div>
    @endif


</div>


@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productos').DataTable({
            "language": {
                "search": "Buscar",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "lengthMenu": "Mostrar _MENU_ registros por pagina",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "Ningún dato disponible en esta tabla",
                "processing": "Procesando...",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "first": "Primero",
                    "last": "Último"
                }
            }
        });
    });

    document.getElementById('mes').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endsection