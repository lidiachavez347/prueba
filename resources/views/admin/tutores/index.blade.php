@extends('adminlte::page')
@section('title', 'Tutores')
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
            <h1 class="m-0">Lista de Tutores</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Tutor</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')


<div class="modal-footer">
    <a href="{{ route('admin.tutores.create') }}" class="btn btn-dark" data-toggle="tooltip" data-placement="left" title="Adicionar">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo</a>

        <a href="{{ route('admin.pdf.tutores') }}" class="btn btn-light" data-toggle="tooltip" data-placement="left" title="Exportar">
    <i class="fa fa-upload" aria-hidden="true"></i> Reporte
</a>
</div>

<br>

<div class="card body py-2 px-1">
    <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>ID</th>
                <th>IMAGEN</th>
                <th>NOMBRES Y APELLIDOS</th>
                <TH>GENERO</TH>
                <th>TELEFONO</th>
                <TH>EMAIL</TH>
                <TH>DIRECCION</TH>
                <th>ESTADO</th>
                <th>FECHA DE REGISTRO</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)

            <tr id="usuario-{{ $usuario->id }}">

                <td>{{ $loop->iteration }}</td>  {{-- 1,2,3... --}}
                <td>
                    <img src="{{ asset('images/' . $usuario->imagen) }}" alt="Imagen de usuario" style="width: 40px; height: 40px;">
                </td>

                <td><a href="#">{{ $usuario->nombres }} {{ $usuario->apellidos }}</a></td>
                <td width="70px" style="text-align: right">
                    @if ($usuario->genero == 0)
                    <span class="badge badge-pill badge-secondary">FEMENINO</span>
                    @elseif ($usuario->genero == 1)
                    <span class="badge badge-pill badge-secondary">MASCULINO</span>
                    @else
                    <span class="badge badge-pill badge-warning">No permitido</span>
                    @endif
                </td>
                <td>{{ $usuario->telefono }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->direccion }}</td>
                <td width="70px" style="text-align: right">
                    @if ($usuario->estado_user == 1)
                    <span class="badge badge-pill badge-success ">ACTIVO</span>
                    @elseif ($usuario->estado_user == 0)
                    <span class="badge badge-pill badge-danger">NO ACTIVO</span>
                    @else
                    <span class="badge bg-warning">No permitido</span>
                    @endif
                </td>
                <td> {{ $usuario->created_at }}</td>
                <td>
                    <!--- BOTON VER--->
                    <a href="#"
                        class="btn btn-info btn-sm view-user"
                        data-id="{{ $usuario->id }}"
                        data-toggle="modal"
                        data-target="#userModal"
                        title="Ver">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                    <!--- BOTON EDITAR--->
                    <a href="javascript:void(0);" onclick="abrirModalEditar('{{$usuario->id}}')" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <!--- BOTON ELIMINAR--->
                    <button onclick="eliminarUsuario('{{ $usuario->id }}')" class="btn btn-danger btn-sm" aria-label="eliminar" data-toggle="tooltip" data-placement="top" title="Eliminar">
                        <i class="fas fa-trash-alt"></i> <span class="d-none d-sm-inline"></span>
                    </button>

                    <!-- Modal  vER-->
                    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalles del tutor</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="userDetails">
                                    <!-- Aquí se cargarán los datos del usuario -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN MODAL VER-->

                    <!-- Modal para Editar Usuario -->
                    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarUsuarioLabel">Editar tutor</h5>
                                    <a href="#" class="btn btn-link" data-bs-dismiss="modal">X</a>

                                </div>
                                <div class="modal-body" id="modal-body-content">
                                    <!-- Aquí se cargará el formulario mediante AJAX -->
                                    <div class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


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
<!-- SCRIP VER USUARO-->
<script>
    $(document).on('click', '.view-user', function(e) {
        e.preventDefault();
        var userId = $(this).data('id');

        $.ajax({
            url: '{{ route("admin.tutores.show", ":id") }}'.replace(':id', userId),
            method: 'GET',
            success: function(response) {
                $('#userDetails').html(response);
            },
            error: function() {
                alert('Error al cargar los detalles del usuario.');
            }
        });
    });
</script>
<!-- SCRIP ELIMINAR USUARIO -->
<script>
    function eliminarUsuario(userId) {
        // Mostrar confirmación antes de eliminar
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.tutores.destroy", ":id") }}'.replace(':id', userId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Mostrar notificación de éxito como Toast
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
                                    toast.addEventListener('mouseenter', Swal.stopTimer);
                                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                                }
                            });

                            Toast.fire({
                                icon: 'success',
                                title: 'Usuario eliminado exitosamente'
                            });

                            // Eliminar la fila correspondiente de la tabla
                            $('#usuario-' + userId).remove();
                        } else {
                            // Mostrar Toast de error si la eliminación falla
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                toast: true,
                                position: 'top-right',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Hubo un error al intentar eliminar el usuario',
                            toast: true,
                            position: 'top-right',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }
</script>

<!-- SCRIP EDITAR USUARIO-->
<script>
    function abrirModalEditar(userId) {
        // Abrir el modal
        $('#editarUsuarioModal').modal('show');

        // Mostrar el spinner de carga mientras se obtiene el formulario
        $('#modal-body-content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');

        // Realizar la petición AJAX para cargar el formulario
        $.ajax({
            url: '{{ route("admin.tutores.edit", ":id") }}'.replace(':id', userId),
            type: 'GET',
            success: function(response) {
                // Inyectar el formulario en el cuerpo del modal
                $('#modal-body-content').html(response);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                $('#modal-body-content').html('<div class="alert alert-danger">Hubo un error al cargar el formulario.</div>');
            }
        });
    }
</script>
<!--ACTUALLIZAR FORM-->
<script>
    $(document).on('submit', '#form-editar', function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editarUsuarioModal').modal('hide');
                location.reload(); // Recargar la página para ver los cambios (opcional)
                // Aquí puedes usar un SweetAlert para notificar al usuario
                $('#usuario-' + response.id).replaceWith(response.html);
            },
            error: function(xhr) {

            if (xhr.status === 422) {  
                // Laravel envía errores de validación
                let errores = xhr.responseJSON.errors;
                let mensaje = "";

                $.each(errores, function(campo, textos) {
                    mensaje += textos[0] + "<br>";
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Errores de Validación',
                    html: mensaje
                });

            } else {
                // Otros errores
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el tutor.'
                });
            }
        }

        });
    });
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