@extends('adminlte::page')
@section('title', 'Dimencion Decidir')
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
            <h1 class="m-0">Notas Dimension Decidir 15</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Dimension Decidir</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')
@if ($errors->any())
<div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
</div>
@endif
<!-- Formulario para seleccionar asignatura y trimestre -->
<form action="{{ route('profesor.decidir.index') }}" method="GET" class="mb-4" id="prueba">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="id_asignatura">Seleccionar Materia:</label>
                <select class="form-control" name="id_asignatura" id="id_asignatura">
    @foreach($materias as $materia)
        <option 
            value="{{ $materia->id }}" 
            {{ request('id_asignatura', $materias->first()->id) == $materia->id ? 'selected' : '' }}
        >
            {{ $materia->nombre_asig }}
        </option>
    @endforeach
</select>


            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="id_trimestre">Seleccionar Trimestre:</label>
                <select name="id_trimestre" id="id_trimestre" class="form-control">
    @foreach ($trimestres as $trimestre)
        <option value="{{ $trimestre->id }}" {{ request('id_trimestre', $trimestres->first()->id) == $trimestre->id ? 'selected' : '' }}>
            {{ $trimestre->periodo }}
        </option>
    @endforeach
</select>
            </div>
        </div>

        <div>
        <button type="submit" class="btn btn-light btn-sm"> <i class="fa fa-filter"></i> Filtrar</button>
            <button type="button" class="btn btn-dark btn-sm" onclick="addCriterio()"><i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo </button>
        </div>
                
            
    </div>
</form>

<form method="POST" action="{{ route('profesor.decidir.store') }}">
    @csrf
    <div class="header">
    <button type="submit" class="btn btn-success btn-sm"> <i class="fa fa-check"></i> Guardar</button>
    </div>
    
    <br>
<!-- PARÁMETROS IMPORTANTES PARA GUARDAR -->
    <input type="hidden" name="id_asignatura" value="{{ request('id_asignatura') }}">
    <input type="hidden" name="id_trimestre" value="{{ request('id_trimestre') }}">

<div class="card body py-2 px-1">
    <table  class="table table-striped shadow-lg mt-4" id="notasTable" >
        <thead class="bg-dark text-white">
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                @foreach ($criterios as $criterio)
                    <th class="rotate-text">
                        {{ $criterio->descripcion }} <!-- Aquí solo mostramos la descripción del criterio -->
                    <br>

        <!-- BOTÓN EDITAR -->

<button type="button" class="btn btn-warning btn-sm"
    onclick="editarCriterioCompleto({{ $criterio->id }})">
    <i class="fas fa-edit"></i>
</button>


        <!-- BOTÓN ELIMINAR -->
        <button type="button" class="btn btn-danger btn-sm"
            onclick="eliminarCriterio({{ $criterio->id }})">
            <i class="fas fa-trash-alt"></i>
        </button>
                    </th>
                    
                @endforeach

                <th>Promedio</th>
            </tr>
        </thead>

            <tbody>
            @foreach ($estudiantes as $index => $estudiante)
                @php
                    $sumaNotas = 0;
                    $cantidadNotas = 0;
                @endphp
                <tr  data-id="{{ $estudiante->id }}">

                    <td>{{ $index + 1 }}</td>
                    <input type="hidden" name="estudiantes[{{ $estudiante->id }}][id_estudiante]" 
                    value="{{ $estudiante->id }}">
                    <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>

                    @foreach ($criterios as $criterio)
                        @php
                            // Buscar la nota que corresponda a este criterio
                            $notaObj = $estudiante->notas->where('id_criterio', $criterio->id)->first();
                            $valorNota = $notaObj->nota ?? 0;

                            $sumaNotas += $valorNota;
                            $cantidadNotas++;
                        @endphp
                        <td>{{ $valorNota }}</td>
                    @endforeach

                    @php
                        $promedio = $cantidadNotas > 0 ? $sumaNotas / $cantidadNotas : 0;
                        //$promedio =$promedio * 0.15;
                    @endphp
                
                    <td class="@if($promedio < 15)  @endif">
                        {{ number_format($promedio, 2) }}
                    </td>
                </tr>
            @endforeach
            </tbody>

    </table>
    
</div>
</form>
<!-- Modal Bootstrap -->
<div class="modal fade" id="modalEditarCriterio" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">

        <div class="modal-header">
        <h5 class="modal-title">Editar notas</h5>
        
        <a href="#" class="btn btn-link" data-bs-dismiss="modal">X</a>
        </div>

        <div class="modal-body">
        <form id="formEditarCriterio">
        <div class="form-group">
            <div class="row align-items-center mb-2">
                <div class="col-6 text-start">
                <label class="form-label">Criterio:</label>
                </div>
                <div class="col-6">
                <input id="criterio_nombre" class="form-control">
                </div>
            </div>
            @error('criterio_nombre')
            <small class="text-danger">
                {{$message}}
            </small>
            @enderror
        </div>
            

            <hr>

            <h5 class="mb-3">Notas por estudiante</h5>

            <div id="contenedorNotas"></div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

        </form>
        </div>

         <center>
<div class="modal-footer">
        <button class="btn btn-success" onclick="guardarCriterio()"> <i class="fa fa-check"></i> Guardar</button>
        <button class="btn btn-danger" data-bs-dismiss="modal">  <i class="fa fa-arrow-left"></i> Cancelar</button>
        </div>
</center>

    </div>
</div>
</div>



@stop


@section('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Obtener el formulario
    const form = document.getElementById('prueba');

    // Cada vez que cambie la materia o el trimestre, enviar el formulario
    document.getElementById('id_asignatura').addEventListener('change', () => form.submit());
    document.getElementById('id_trimestre').addEventListener('change', () => form.submit());
</script>
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
<script>
function eliminarCriterio(id) {

    Swal.fire({
        title: "¿Eliminar criterio?",
        text: "Esta acción borrará sus notas asociadas.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            fetch(`/profesor/decidir/criterio/${id}/eliminar`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {

                    Swal.fire({
                        title: "Eliminado",
                        text: "El criterio fue eliminado correctamente.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => location.reload(), 1500);

                } else {
                    Swal.fire("Error", "No se pudo eliminar el criterio", "error");
                }

            })
            .catch(error => {
                console.error('Error:', error);

                Swal.fire("Error", "Ocurrió un error al eliminar", "error");
            });
        }
    });
}
</script>

<script>
    function addCriterio() {
    const table = document.getElementById('notasTable');
    const tbodyRows = table.tBodies[0].rows;
    const theadRow = table.tHead.rows[0];

    const criterioIndex = theadRow.cells.length - 3;

    // Nuevo criterio (TH)
    const newTh = document.createElement('th');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = `criterios[nuevo_${criterioIndex}]`;
    input.className = 'form-control';
    newTh.appendChild(input);

    theadRow.insertBefore(newTh, theadRow.cells[theadRow.cells.length - 1]);

    // Inputs de notas por estudiante
    for (let row of tbodyRows) {

        const estudianteId = row.dataset.id; // <-- ID REAL DEL ESTUDIANTE

        const newTd = document.createElement('td');
        const inputNota = document.createElement('input');
        inputNota.type = 'number';
        inputNota.name = `notas[${estudianteId}][nuevo_${criterioIndex}]`;
        inputNota.className = 'form-control';
        inputNota.min = 0;
        inputNota.max = 15;

        newTd.appendChild(inputNota);
        row.insertBefore(newTd, row.cells[row.cells.length - 1]);
    }
}




function editarCriterioCompleto(id) {

    $.get(`/profesor/decidir/criterio/${id}/data`, function(response) {

        // Llenar el nombre del criterio
        $('#criterio_nombre').val(response.criterio.descripcion);

        // Limpiar contenedor
        $('#contenedorNotas').html("");

        // Insertar inputs de notas
        response.estudiantes.forEach(est => {
            const nota = est.nota ?? "";
            $('#contenedorNotas').append(`

            <div class="row align-items-center mb-2">
                <div class="col-6 text-start">
                    <label class="">${est.nombre}</label>
                </div>

                <div class="col-6">
                    <input type="number" id="nota_${est.id}" 
                        class="form-control"
                        value="${nota}" min="0" max="15">
                </div>
            </div>

            `);
        });

        // Guardar ID del criterio para actualizar
        $('#modalEditarCriterio').data('id', id);

        // Mostrar modal
        let modal = new bootstrap.Modal(document.getElementById('modalEditarCriterio'));
        modal.show();
    });
}

function guardarCriterio() {

    let id = $('#modalEditarCriterio').data('id');

    let datos = {
        descripcion: $('#criterio_nombre').val(),
        notas: {},
        _token: "{{ csrf_token() }}"
    };

    // Recorrer inputs
    $('#contenedorNotas input').each(function () {
        let estId = this.id.replace('nota_', '');
        datos.notas[estId] = $(this).val();
    });

    // Enviar
    $.post(`/profesor/decidir/criterio/${id}/actualizar`, datos, function() {
        location.reload();
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