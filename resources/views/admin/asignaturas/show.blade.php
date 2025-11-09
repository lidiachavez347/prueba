@extends('adminlte::page')
@section('title', 'Asistencias')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection
@section('content')
<br>
<div class="card">
<div class="card-body">
    <p><strong>Nombre:</strong> {{ $profesor->user->nombres }}</p>
    <p><strong>Apellidos:</strong> {{ $profesor->user->apellidos }}</p>
    <p><strong>Estado:</strong> {{ $profesor->estado_prof }}</p>
</div>
</div>


<h4>Asignaciones:</h4>


<div class="card body py-2 px-1">
    <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profesor->asignaciones as $asignacion)
            <tr>
                <td>{{ $asignacion->curso->nombre_curso }}</td>
                <td>{{ $asignacion->curso->paralelo }}</td>
                <td>{{ $asignacion->asignatura->nombre_asig }}</td>
            </tr>
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
@endsection