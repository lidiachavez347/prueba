@extends('adminlte::page')
@section('title', 'Asignar profesor')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection
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
            <h1 class="m-0">Lista de Profesores asignados</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Asignar Profesor</li>
            </ol>
        </div>
    </div>

</div>
@stop

@section('content')
<div class="modal-footer">
    <a href="{{ route('admin.asignaturas.create') }}" class="btn btn-dark" data-toggle="tooltip" data-placement="left" title="Adicionar">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo
    </a>

</div>

<br>

<div class="card body py-2 px-1">
    <table id="productos" class="table table-striped shadow-lg mt-4">
        <thead class="bg-dark text-white">
            <tr>
                <th>ID</th>
                <th>IMAGEN</th>
                <th>NOMBRES Y APELLIDOS</th>
                <th>GENERO</th>
                <th>ESTADO</th>
                <th>FECHA DE REGISTRO</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profesores as $profesor)
            <tr>
                <td>
                    <a>
                        {{ $loop->iteration }} 
                    </a>
                </td>
                <td>
                    <img src="{{ asset('images/' . $profesor->imagen) }}" alt="Descripción de la imagen" style="width: 40px; height: 40px;">
                </td>
                <td>
                    <a href="{{ route('admin.asignaturas.show', $profesor->id) }}">
                        {{ $profesor->nombres }}
                        {{ $profesor->apellidos }}
                    </a>
                </td>
                                <td width="70px" style="text-align: right">
                    @if ($profesor->genero == 0)
                    <span class="badge badge-pill badge-secondary">FEMENINO</span>
                    @elseif ($profesor->genero == 1)
                    <span class="badge badge-pill badge-secondary">MASCULINO</span>
                    @else
                    <span class="badge badge-pill badge-warning">No permitido</span>
                    @endif
                </td>
                <td width="70px" style="text-align: right">
                    @if ($profesor->estado_user == 1)
                    <span class="badge badge-pill badge-success ">ACTIVO</span>
                    @elseif ($profesor->estado_user == 0)
                    <span class="badge badge-pill badge-danger">NO ACTIVO</span>
                    @else
                    <span class="badge bg-warning">No permitido</span>
                    @endif
                </td>
                <td>{{ $profesor->created_at }}</td>
                <td>
                    <a class="btn btn-info btn-sm" href="{{ route('admin.asignaturas.show', $profesor->id) }}">
                        <i class="fa fa-eye"
                            aria-hidden="true"></i>
                    </a>
                </td>
            </tr>

            @endforeach


        </tbody>
    </table>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</script>

<script>
    function eliminarRegistro(id) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('eliminar') == 'ok')
<script>
    Swal.fire({
        icon: 'success',
        title: 'Eliminado exitosamente',
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
</script>
@endif

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


@endsection