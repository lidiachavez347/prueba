@extends('adminlte::page')
@section('title', 'Lista de Profesores')
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

<h1>Listar Profesores</h1>


@stop
@section('content')

@if (Auth::user()->hasrole('Director'))
<div class="card-header">

    <a href="{{ route('director.profesores.create') }}" class="btn btn-outline-success"> <i class="fa fa-plus" aria-hidden="true"></i></a>

</div>


@endif
<br>

<div class="card body py-2 px-1">
    <table id="productos" class="table table striped shadow-lg mt-4">
        <thead class="bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>APELLIDO</th>
                <th>GENERO</th>
                <th>ROL</th>
                <TH>CURSO</TH>
                <th>DIRECCION</th>
                <th>ESTADO</th>
                <th>REGISTRADO</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profesores as $profesor)
            <tr>
                <td>{{ $profesor->id }}</td>
                <td><a href="#">{{ $profesor->nombres }}</a></td>
                <td>{{ $profesor->apellidos }}</td>
                <td width="70px" style="text-align: right">
                    @if ($profesor->genero == 'F')
                    <span class="badge badge-pill badge-secondary">FEMENINO</span>
                    @elseif ($profesor->genero == 'M')
                    <span class="badge badge-pill badge-secondary">MASCULINO</span>
                    @else
                    <span class="badge badge-pill badge-warning">No permitido</span>
                    @endif
                </td>
                <td>
                    @if (!empty($profesor->getRoleNames()))
                    @foreach ($profesor->getRoleNames() as $rolName)
                    {{ $rolName }}
                    @endforeach
                    @endif
                </td>

                
                <td>{{ $profesor->direccion }}</td>

                <td width="70px" style="text-align: right">
                    @if ($profesor->estado_user == 1)
                    <span class="badge badge-pill badge-success">ACTIVO</span>
                    @elseif ($profesor->estado_user == 0)
                    <span class="badge badge-pill badge-danger">NO ACTIVO</span>
                    @else
                    <span class="badge badge-pill badge-warning">No permitido</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($profesor->created_at)->locale('es')->isoFormat(' D \d\e MMMM \d\e\l Y') }}
                </td>
                <td>
                    @if (Auth::user()->hasrole('Director'))
                    <form action="{{ route('director.profesores.destroy', $profesor->id) }}" method="POST"
                        class="formulario-eliminar">
                        @csrf
                        @method('DELETE')
                        <a href="{{ route('director.profesores.edit', $profesor->id) }}"
                            class="btn btn-warning btn-sm">Editar</a>

                        @method('delete')
                        @csrf
                        <input type="submit" value="Eliminar" class="btn btn-danger btn-sm">
                    </form>
                    @endif
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