@extends('adminlte::page')
@section('title', 'Usuarios')

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
            <h1 class="m-0">Lista de Usuarios</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Usuarios</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')

<div class="card-header">

    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Usuario</a>
</div>
<br>

<div class="card body py-2 px-1">
    <table id="buscar" class="table table striped shadow-lg mt-4">
        <thead class="tabla-header bg-primary text-white">
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>APELLIDO</th>
                <TH>GENERO</TH>
                <th>ROL</th>
                {{-- <th>ROL PERMISOS</th> --}}
                <th>ESTADO</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($usuarios as $usuario)

            <tr>

                <td>{{ $usuario->id }}</td>
                <td><a href="#">{{ $usuario->nombres }}</a></td>
                <td>{{ $usuario->apellidos }}</td>
                <td width="70px" style="text-align: right">
                    @if ($usuario->genero == 0)
                    <span class="badge badge-pill badge-secondary">FEMENINO</span>
                    @elseif ($usuario->genero == 1)
                    <span class="badge badge-pill badge-secondary">MASCULINO</span>
                    @else
                    <span class="badge badge-pill badge-warning">No permitido</span>
                    @endif
                </td>


                <td> @if (!empty($usuario->getRoleNames()))
                    @foreach($usuario->getRoleNames() as $rolName)
                    {{$rolName}}
                    @endforeach
                    @endif
                </td>


                <td width="70px" style="text-align: right">
                    @if ($usuario->estado_user == 1)
                    <span class="badge badge-pill badge-success ">ACTIVO</span>
                    @elseif ($usuario->estado_user == 0)
                    <span class="badge badge-pill badge-danger">NO ACTIVO</span>
                    @else
                    <span class="badge bg-warning">No permitido</span>
                    @endif
                </td>

                <td>

                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" class="formulario-eliminar">
                        @csrf
                        @method('DELETE')
                        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                            class="btn btn-warning btn-sm">Editar</a>

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
        $('#buscar').DataTable({
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/app.js') }}"></script>
@endsection