@extends('adminlte::page')
@section('title', 'Lista')
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
<h1>Listar </h1>
@stop
@section('content')

<div class="card-header">
    <a href="{{ route('director.asignaturas.create') }}" class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i> Nuevo</a>
</div>
<br>

<div class="card body py-2 px-1">
    <table id="productos" class="table table striped shadow-lg mt-4">
        <thead class="bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>ESTADO</th>
                <th>FECHA</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($materias as $materia)
            <tr>
                <td>{{ $materia->id }}</td>

                <td>{{ $materia->nombre_asignatura }}</td>

                <td width="70px" style="text-align: right">
                    @if ($materia->estado_asignatura == 1)
                    <span class="badge badge-pill badge-success">DISPONIBLE</span>
                    @elseif ($materia['estado_asignatura'] == 0)
                    <span class="badge badge-pill badge-danger">NO DISPONIBLE</span>
                    @else
                    <span class="badge badge-pill badge-warning">no permitido</span>
                    @endif
                </td>

                <td>{{ \Carbon\Carbon::parse($materia->created_at)->locale('es')->isoFormat(' D \d\e MMMM \d\e\l Y') }}
                </td>
                <td>
                    <form action="{{ route('director.asignaturas.destroy', $materia) }}" method="POST" class="formulario-eliminar">

                        <a href="{{ route('director.asignaturas.edit', $materia) }}" class="btn btn-warning btn-sm">Editar</a>
                        @method('delete')
                        @csrf
                        <input type="submit" value="Eliminar" class="btn btn-danger btn-sm">
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
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
                    "first": "primero",
                    "last": "Ultimo"
                }
            }
        });
    });
</script>

@endsection