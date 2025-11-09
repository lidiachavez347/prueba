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
<div class="modal-footer">


    <a href="{{ route('profesor.asistencias.create') }}" class="btn btn-dark" data-toggle="tooltip" data-placement="left" title="Adicionar">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo
    </a>


    <form method="GET" action="{{ route('profesor.asistencias.index') }}">
        <label for="mes" class="form-label">Seleccione el Mes</label>
        <select id="mes" name="mes" class="form-select" onchange="this.form.submit()">
            @foreach ($meses as $mes => $nombre)
            <option value="{{ $mes }}" {{ $mesSeleccionado == $mes ? 'selected' : '' }}>
                {{ $nombre }}
            </option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('pdf.asistencias', ['mes' => $mesSeleccionado]) }}" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Exportar a PDF"> <i class="fa fa-file-pdf"></i> PDF</a>
    <a href="{{ route('profesor.asistencias.show') }}" class="btn btn-primary">Ver Listado</a>


</div>

<br>


<div class="card body py-2 px-1">
    <table id="productos" class="table table-striped shadow-lg mt-4">
        <thead class="bg-dark text-white">
            <tr>
                <th>ID</th>
                <th>NOMBRE Y APELLIDO </th>
                <th>TOTAL ASISTENCIA</th>
                <th>AUSENCIAS JUSTIFICADAS</th>
                <th>AUSENCIAS INJUSTIFICADAS</th>
                <th>% ASISTENCIA</th>
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
                <td>{{ $estudiante->id }}</td>

                <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                <td>{{ $estudiante->total_presentes }}</td>
                <td>{{ $estudiante->total_ausentes }}</td>
                <td>{{ $estudiante->total_justificados }}</td>
                <!-- <td>{{ $estudiante->total_tardes }}</td>
                <td>{{ $estudiante->total_asistencias }}</td>-->

                <td>{{ $estudiante->totales_asistencias['porcentaje'] }}%</td>
                 <!-- <td>
                    <a href="{{ route('profesor.estudiantes.show', $estudiante->id) }}" class="btn btn-info btn-sm">
                        Asistencias
                    </a>
                </td>-->
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