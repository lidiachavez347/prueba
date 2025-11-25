@extends('adminlte::page')
@section('title', 'Asistencias')
@section('css')
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
<br>
<div class="card">
<div class="card-body">

    <p><strong>PROFESOR(A):</strong> {{ $profesor->nombres }}{{ $profesor->apellidos }}</p>
    <p><strong>ESTADO:</strong> 
    <span class="badge badge-pill badge-{{ $profesor->profesorAsignaturas->first()->estado ? 'success' : 'danger' }}">
                        {{ $profesor->profesorAsignaturas->first()->estado ? 'ACTIVO' : 'NO ACTIVO' }}
                    </span>
    </p>
</div>
</div>


<h4>Asignaciones:</h4>


<div class="card body py-2 px-1">
    <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>CURSO</th>
                <th>MATERIAS</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
        @php
    $asignacionesPorCurso = $profesor->profesorAsignaturas->groupBy('id_curso');
@endphp

                @foreach($asignacionesPorCurso as $cursoId => $asignaciones)
                    <tr>
                        <td rowspan="{{ $asignaciones->count() }}">
                            {{ $asignaciones->first()->curso->nombre_curso }}
                            ({{ $asignaciones->first()->curso->paralelo }})
                        </td>

                        {{-- Primera asignatura --}}
                        <td>{{ $asignaciones->first()->asignatura->nombre_asig }}</td>
                        {{-- Botones solo una vez por curso --}}
                        <td rowspan="{{ $asignaciones->count() }}">
                        
                            <a class="btn btn-warning btn-sm" 
                                href="{{ route('admin.asignaturas.edit', ['id_usuario' => $profesor->id, 'id_curso' => $asignaciones->first()->curso->id]) }}">
                                    <i class="fa fa-edit"></i>
                                </a>


                            <a href="#" class="btn btn-danger btn-sm" onclick="eliminarRegistro({{ $profesor->id }})">
                                <i class="fa fa-trash"></i>
                            </a>

                            <form id="delete-form-{{ $profesor->id }}" action="{{ route('admin.asignaturas.destroy', ['id_usuario' => $profesor->id, 'id_curso' => $asignaciones->first()->curso->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>

{{-- Asignaturas restantes --}}
                    @foreach($asignaciones->skip(1) as $asignacion)
                        <tr>
                            <td>{{ $asignacion->asignatura->nombre_asig }}</td>
                        </tr>
                    @endforeach

                @endforeach
        </tbody>
    </table>
</div>

@endsection
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