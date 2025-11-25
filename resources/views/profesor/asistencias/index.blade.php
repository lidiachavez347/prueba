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
        font-size: 10px;
        vertical-align: middle;
        /* Centrar el texto */
        width: 40px;

        /* Ajusta el ancho si es necesario */
    }
/* Ajuste general de las cabeceras para que la tabla no se deforme */


</style>
<style>
    th {
        height: 70px !important;     /* Ajusta la altura */
        vertical-align: middle !important;
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }

    /* Si usas texto rotado */
    .rotate-text {
        height: 120px !important;    /* Más alto para el texto rotado */
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Asistencias de Estudiantes</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Asistencias</li>
            </ol>
        </div>
    </div>

</div>
@stop

@section('content')
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '¡Atención!',
        text: '{{ session('error') }}'
    });
</script>
@endif

<form method="GET" action="{{ route('profesor.asistencias.index') }}" class="row g-3">

    <div class="col-md-6">
        <label for="desde" class="form-label">Desde</label>
        <input type="date" id="desde" name="desde" class="form-control"
            value="{{ request('desde') }}"
            onchange="this.form.submit()">
    </div>

    <div class="col-md-6">
        <label for="hasta" class="form-label">Hasta</label>
        <input type="date" id="hasta" name="hasta" class="form-control"
            value="{{ request('hasta') }}"
            onchange="this.form.submit()">
    </div>

</form>

<div class="modal-footer">


    <a href="{{ route('profesor.asistencias.create') }}" class="btn btn-dark" data-toggle="tooltip" data-placement="left" title="Adicionar">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo
    </a>


    
    <!--              ---------------PDF
        <a href="{{ route('pdf.asistencias', ['fecha' => $desde]) }}" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Exportar a PDF"> <i class="fa fa-file-pdf"></i> PDF</a>
    <a href="{{ route('profesor.asistencias.show') }}" class="btn btn-primary">Ver Listado</a>-->


</div>

<br>


<div class="table-responsive">
    <table id="productos" class="table table-striped shadow-lg mt-4">
        <thead class="bg-dark text-white">
            <tr>
                <th>ID</th>
                <th>NOMBRES Y APELLIDOS </th>
                @foreach ($fechas as $fecha)
                
                <th class="rotate-text">{{ $fecha->format('d/m/Y') }}
                    <a href="{{ route('profesor.asistencias.edit', ['fecha' => $fecha->format('Y-m-d'), 'curso_id' => $idParalelo]) }}" 
                        class="btn btn-sm btn-primary mt-1" 
                        title="Editar asistencias">
                        <i class="fa fa-edit"></i>
                    </a>

                </th>
                @endforeach
                <th>PRESENTES</th>
                <th>AUSENTES</th>
                <th>JUSTIFICADAS</th>
                <th>TOTAL</th>
                <!-- <th>ACCIONES</th>-->
            </tr>
        </thead>
        <tbody>


            @foreach ($estudiantes as $estudiante)
            @php
            $asistencias = $estudiante->detalleAsistencias;
            $datosAsistencia = $estudiante->calcularAsistencias($asistencias);
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                
                @foreach ($fechas as $fecha)
                        @php
                        // Crear la clave para acceder a las asistencias
                        $key = $estudiante->id . '_' . $fecha->format('Y-m-d');
                        $asistencia = $asistenciasAgrupadas->get($key); // Buscar la asistencia por la clave
                        @endphp
                        <td>
                            @if ($asistencia)
                            {{ ucfirst($asistencia->first()->estado) }} <!-- Mostrar el estado de la asistencia -->
                            @else
                            ---- <!-- No hay asistencia registrada -->
                            @endif
                        </td>
                @endforeach
                <td>{{ $estudiante->total_presentes }}</td>
                <td>{{ $estudiante->total_ausentes }}</td>
                <td>{{ $estudiante->total_justificados }}</td>
                <td>{{ $estudiante->total_asistencias }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('js')
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/app.js') }}"></script>

@if (session('guardar') == 'ok')
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: 'success',
        title: 'Guardado exitosamente!'
    })
</script>
@endif

@if (session('actualizar') == 'ok')
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: 'success',
        title: 'Actualizado exitosamente!'
    })
</script>
@endif

@if (session('eliminar') == 'ok')
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: 'success',
        title: 'Eliminado exitosamente!'
    })
</script>
@endif

<script>
    $('.formulario-eliminar').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de eliminar?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            } else {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-right',
                    iconColor: 'white',
                    customClass: {
                        popup: 'colored-toast'
                    },
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'error',
                    title: 'El elemento que deseaba eliminar fue cancelado'
                })
            }
        })
    });
</script>
@endsection