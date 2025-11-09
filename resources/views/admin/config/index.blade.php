@extends('adminlte::page')
@section('title', 'Configuraciones')
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

    .card-button {
        width: 400px;
        height: 120px;
        background-color: #ffffff;
        /* Fondo blanco */
        border: 1px solid #ddd;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    }

    .icon-container {
        font-size: 40px;
        color: #007bff;
        /* Color azul para el ícono */
        margin-bottom: 10px;
    }

    h4 {
        font-size: 16px;
        margin-bottom: 10px;
        color: #333;
    }

    .configure-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 5px 15px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .configure-button:hover {
        background-color: #0056b3;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Configuracion del sistema</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Configuracion</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')


<div style="display: flex; gap: 20px; justify-content: center; margin-top: 20px;">
    <div class="card-button">
        <div class="icon-container">
            <i class="fas fa-building"></i>
        </div>
        <h4>Datos de la Institución</h4>
        @if($institucion > 0)
        <a href="{{ route('admin.config.show',$id) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="Adicionar">
            Configurar</a>
        @else
        <a href="{{ route('admin.config.create') }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="Adicionar">
            Configurar</a>
        @endif
    </div>


    <div class="card-button">
        <div class="icon-container">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <h4>Gestión educativa</h4>
        <a href="{{ route('admin.gestion.index') }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="Adicionar">
            Configurar</a>
    </div>

    <div class="card-button">
        <div class="icon-container">
            <i class="fas fa-book"></i> <!-- Cambié el ícono aquí -->
        </div>
        <h4>Desarrollo curricular</h4>
        <a href="{{ route('admin.curricular.index') }}" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="Adicionar">
            Configurar
        </a>
    </div>

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
            url: '{{ route("admin.usuarios.show", ":id") }}'.replace(':id', userId),
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
                    url: '{{ route("admin.usuarios.destroy", ":id") }}'.replace(':id', userId),
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
            url: '{{ route("admin.usuarios.edit", ":id") }}'.replace(':id', userId),
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
                console.error(xhr.responseText);
                Swal.fire('Error', 'No se pudo actualizar el usuario', 'error');
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