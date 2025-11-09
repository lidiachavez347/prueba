@extends('adminlte::page')
@section('title', 'Estudiantes')
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

    .accordion-button {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .list-group-item ul {
        list-style-type: disc;
        margin-left: 20px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Tarea: {{ $actividad->nombre }}</h1>
            <p>{{ $actividad->descripcion }}</p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Calificar tarea</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')
<h2>Lista de Estudiantes</h2>
<div class="card body py-2 px-1">



    @if($actualizar == 0)
    <form action="{{ route('profesor.casa.store', $actividad->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Guardar Notas</button>
        <br>
        <br>
        <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>#</th>
                    <th>Estudiante</th>
                    <th>Nota</th>
                    <th>Observacion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($estudiantes as $estudiante)
                <tr>
                <td>{{ $loop->iteration }}</td>
                    <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                    <td>
                        <input type="hidden" name="estudiantes[{{ $estudiante->estudiante_id }}][id]" value="{{ $estudiante->estudiante_id }}">
                        <input type="number" name="estudiantes[{{ $estudiante->estudiante_id }}][nota]" value="0" min="0" max="35" class="form-control">

                    </td>
                    <td><input type="text" name="estudiantes[{{ $estudiante->estudiante_id }}][opservacion]" class="form-control"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </form>
    @else
    <form action="{{ route('profesor.casa.update', $actividad->id) }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-sm btn-success">Actualizar Notas</button>
        <br>
        <br>
        <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>#</th>
                    <th>Estudiante</th>
                    <th>Nota</th>
                    <th>Opservacion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notas as $estudiante)
                <tr>
                <td>{{ $loop->iteration }}</td>
                    <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                    <td>
                        <input type="hidden" name="estudiantes[{{ $estudiante->estudiante_id }}][id]" value="{{ $estudiante->estudiante_id }}">
                        <input type="number" name="estudiantes[{{ $estudiante->estudiante_id }}][nota]" value="{{ $estudiante->nota }}" min="0" max="35" class="form-control">
                    </td>
                    <td><input type="text" name="estudiantes[{{ $estudiante->estudiante_id }}][opservacion]" value="{{ $estudiante->opservacion }}" class="form-control"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    @endif


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
                    "first": "primero",
                    "last": "Ultimo"
                }
            }
        });
    });
</script>
@endsection