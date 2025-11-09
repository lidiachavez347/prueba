@extends('adminlte::page')
@section('title', 'Asignaturas')
@push('css')
<style>
    /* Toast Notifications */
    .colored-toast.swal2-icon-success { background-color: #a5dc86 !important; }
    .colored-toast.swal2-icon-error { background-color: #f27474 !important; }
    .colored-toast.swal2-icon-warning { background-color: #f8bb86 !important; }
    .colored-toast.swal2-icon-info { background-color: #3fc3ee !important; }
    .colored-toast .swal2-title,
    .colored-toast .swal2-close,
    .colored-toast .swal2-html-container { color: white; }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        h1.m-0 { font-size: 1.5rem; }
        .breadcrumb { font-size: 0.8rem; }
        .table th, .table td { padding: 0.3rem; font-size: 0.85rem; }
    }

    @media (max-width: 576px) {
        .no-mobile { display: none; }
    }

    /* Custom Styles */
    .trimestre-label {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        font-weight: bold;
        font-size: 1.1rem;
        letter-spacing: 2px;
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }

    .action-buttons .btn {
        margin: 0 2px;
    }
</style>
@endpush

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">
                <i class="fas fa-book-open"></i> Desarrollo Curricular
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right bg-transparent m-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active">Desarrollo curricular</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card shadow-sm">
    <!-- Filtros -->
    <div class="card-header bg-white border-bottom">
        <div class="row align-items-end">
            <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                <form method="GET" action="{{ route('admin.curricular.index') }}">
                    <label for="gestion_id" class="form-label fw-bold">
                        <i class="fas fa-filter"></i> Filtrar por Gestión
                    </label>
                    <div class="input-group">
                        <select name="gestion_id" id="gestion_id" class="form-select">
                            <option value="">Todas las gestiones</option>
                            @foreach($gestiones as $gestion)
                                <option value="{{ $gestion->id }}" 
                                    {{ request('gestion_id') == $gestion->id ? 'selected' : '' }}>
                                    {{ $gestion->gestion }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-md-6 ms-auto text-end">
                <a href="{{ route('admin.trimestres.index') }}" 
                   class="btn btn-dark btn-lg" 
                   data-bs-toggle="tooltip" 
                   title="Gestionar Trimestres">
                    <i class="fas fa-calendar-alt"></i> Trimestres
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de Trimestres -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th colspan="3" class="text-center py-3">
                            <i class="fas fa-graduation-cap"></i> Año Académico
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Etiqueta lateral -->
                    <tr>
                        <td rowspan="{{ count($trimestres) * 3 + 1 }}" 
                            class="text-center align-middle bg-light p-3" 
                            style="width: 50px;">
                            <div class="trimestre-label">
                                DESARROLLO CURRICULAR
                            </div>
                        </td>
                    </tr>

                    @forelse($trimestres as $trimestre)
                        <!-- Fila del Trimestre -->
                        <tr class="table-light">
                            <td colspan="2" class="py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1 fw-bold">{{ $trimestre->periodo }}</h5>
                                        @if($trimestre->estado == 1)
                                            <span class="badge bg-success badge-status">
                                                <i class="fas fa-check-circle"></i> ACTIVO
                                            </span>
                                        @else
                                            <span class="badge bg-danger badge-status">
                                                <i class="fas fa-times-circle"></i> INACTIVO
                                            </span>
                                        @endif
                                    </div>
                                    <div class="action-buttons">
                                        <button onclick="abrirModalEditar('{{ $trimestre->id }}')" 
                                                class="btn btn-warning btn-sm" 
                                                data-bs-toggle="tooltip" 
                                                title="Editar trimestre">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Encabezados de Fechas -->
                        <tr>
                            <td class="text-center bg-light fw-bold">
                                <i class="fas fa-calendar-day"></i> Fecha Inicio
                            </td>
                            <td class="text-center bg-light fw-bold">
                                <i class="fas fa-calendar-check"></i> Fecha Fin
                            </td>
                        </tr>

                        <!-- Valores de Fechas -->
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">
                                    {{ \Carbon\Carbon::parse($trimestre->fecha_inicio)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">
                                    {{ \Carbon\Carbon::parse($trimestre->fecha_fin)->format('d/m/Y') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No hay trimestres registrados para esta gestión</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Editar Trimestre -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editarUsuarioLabel">
                    <i class="fas fa-edit"></i> Editar Trimestre
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando formulario...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')

<!-- SCRIP EDITAR USUARIO-->
<script>
    function abrirModalEditar(userId) {
        // Abrir el modal
        $('#editarUsuarioModal').modal('show');

        // Mostrar el spinner de carga mientras se obtiene el formulario
        $('#modal-body-content').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');

        // Realizar la petición AJAX para cargar el formulario
        $.ajax({
            url: '{{ route("admin.trimestres.edit", ":id") }}'.replace(':id', userId),
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
              success: function(response) {
    $('#editarUsuarioModal').modal('hide');
    Swal.fire({
        icon: 'success',
        title: response.message,
        toast: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
    setTimeout(() => location.reload(), 1000);
},

            },
            error: function(xhr) {
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        let messages = '';
        $.each(errors, function(key, value) {
            messages += '<li>' + value[0] + '</li>';
        });
        Swal.fire({
            icon: 'error',
            title: 'Errores de validación',
            html: '<ul style="text-align:left;">' + messages + '</ul>'
        });
    } else {
        Swal.fire('Error', 'No se pudo actualizar el trimestre', 'error');
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