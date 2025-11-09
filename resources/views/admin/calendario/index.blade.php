@extends('adminlte::page')
@section('title', 'Calendario')
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

    .fc-event {
        width: 140px;
        height: 40px;
        display: flex;
        flex-wrap: wrap;
        align-content: center;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Calendario academico</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Calendario</li>
            </ol>
        </div>
    </div>

</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
@stop

@section('content')

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Modal de detalles de evento -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Detalles del Evento</h5>
                    <a href="#" class="btn btn-link" data-bs-dismiss="modal">X</a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="eventTitle">Título:</label>
                        <input type="text" id="eventTitle" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="eventStart">Fecha de inicio:</label>
                        <input type="date" id="eventStart" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="eventEnd">Fecha de finalización:</label>
                        <input type="date" id="eventEnd" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Color del evento:</label>
                        <input type="color" id="eventColor" class="form-control" value="#000000">
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" id="updateBtn" class="btn btn-primary">Actualizar</button>
                    <button type="button" id="deleteBtn" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Título del evento:</label>
                        <input type="text" class="form-control" id="title">
                        <span id="titleError" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="saveBtn" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">

                        <div id="calendar">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale/es.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // Configuración de CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });


        // Cargar eventos desde el backend
        var booking = @json($events);

        // Inicializar el calendario
        $('#calendar').fullCalendar({
            locale: 'es',
            header: {
                left: 'prev, next today',
                center: 'title',
                right: 'month, agendaWeek, agendaDay',
            },
            events: booking,
            selectable: true,
            selectHelper: true,

            select: function(start, end) {
                $('#title').val('');
                $('#eventColor').val('#000000');

                // Asegúrate de que las fechas están en formato adecuado para el input[type="date"]
                $('#eventStart').val(moment(start).format('YYYY-MM-DD'));
                $('#eventEnd').val(moment(end).format('YYYY-MM-DD'));

                $('#bookingModal').modal('show');

                $('#saveBtn').off('click').click(function() {

                    let title = $('#title').val();
                    let start_date = $('#eventStart').val(); // Asegúrate de que esté en formato YYYY-MM-DD
                    let end_date = $('#eventEnd').val(); // Asegúrate de que esté en formato YYYY-MM-DD
                    let color = $('#eventColor').val();

                    // Validar campos antes de enviar
                    if (!title || !start_date || !end_date) {
                        swal('Error', 'Por favor, complete todos los campos.', 'error');
                        return;
                    }
                    // Verificar que las fechas estén correctas
                    console.log('Fecha de inicio:', start_date);
                    console.log('Fecha de finalización:', end_date);

                    $.ajax({
                        url: "{{ route('admin.calendario.store') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            title: title,
                            start_date: start_date,
                            end_date: end_date,
                            color: color
                        },
                        success: function(response) {
                            console.log('Respuesta del servidor (success):', response); // Debug aquí
                            $('#calendar').fullCalendar('renderEvent', {
                                id: response.id,
                                title: response.title,
                                start: response.start, // Debe ser una fecha válida
                                end: response.end, // Debe ser una fecha válida
                                color: response.color,
                            });
                            $('#bookingModal').modal('hide');
                            swal('¡Buen trabajo!', 'Evento guardado con éxito.', 'success');
                        },
                        error: function(error) {
                            if (error.responseJSON.errors) {
                                $('#titleError').html(error.responseJSON.errors.title || '');
                                swal('Error', 'Por favor corrige los errores en el formulario.', 'error');
                            }
                        }
                    });
                });
            },

            editable: true,
            eventDrop: function(event) {
                let id = event.id;
                let start_date = moment(event.start).format('YYYY-MM-DD'); // Formato válido para el backend
                let end_date = event.end ? moment(event.end).format('YYYY-MM-DD') : start_date; // Usar start si no hay end

                // Opción predeterminada de color si no está definido
                let color = event.color || '#3788d8';

                // Llamada AJAX para actualizar en el servidor
                $.ajax({
                    url: "{{ route('admin.calendario.update', '') }}/" + id,
                    type: 'PATCH',
                    dataType: 'json',
                    data: {
                        start_date: start_date,
                        end_date: end_date,
                        color: color
                    },
                    success: function(response) {
                        console.log('Evento actualizado:', response); // Debugging
                        // Reflejar cambios en el calendario
                        $('#calendar').fullCalendar('updateEvent', event);
                        swal('¡Éxito!', 'Evento actualizado correctamente.', 'success');
                    },
                    error: function(error) {
                        console.error('Error al actualizar el evento:', error); // Debugging
                        swal('Error', 'No se pudo actualizar el evento.', 'error');
                    }
                });
            },


            eventClick: function(event) {
                let id = event.id;

                // Prellenar los campos del modal con la información del evento
                $('#eventTitle').val(event.title);
                $('#eventStart').val(moment(event.start).format('YYYY-MM-DD'));
                $('#eventEnd').val(event.end ? moment(event.end).format('YYYY-MM-DD') : '');
                $('#eventColor').val(event.color || '#3788d8'); // Color por defecto si no existe

                $('#eventModal').modal('show');

                // Modificar evento
                $('#updateBtn').off('click').click(function() {
                    let title = $('#eventTitle').val();
                    let start_date = $('#eventStart').val();
                    let end_date = $('#eventEnd').val() || start_date; // Fecha de fin opcional
                    let color = $('#eventColor').val();

                    $.ajax({
                        url: "{{ route('admin.calendario.update', '') }}/" + id,
                        type: 'PATCH',
                        dataType: 'json',
                        data: {
                            title,
                            start_date,
                            end_date,
                            color
                        },
                        success: function() {
                            event.title = title;
                            event.start = moment(start_date);
                            event.end = moment(end_date);
                            event.color = color;

                            // Reflejar los cambios en el calendario
                            $('#calendar').fullCalendar('updateEvent', event);

                            // Cerrar el modal y mostrar mensaje de éxito
                            $('#eventModal').modal('hide');
                            swal('¡Éxito!', 'Evento actualizado correctamente.', 'success');
                        },
                    });
                });

                // Eliminar evento
                $('#deleteBtn').off('click').click(function() {
                    swal({
                        title: '¿Estás seguro?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        buttons: ['Cancelar', 'Eliminar'],
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "{{ route('admin.calendario.destroy', '') }}/" + id,
                                type: 'DELETE',
                                success: function() {
                                    $('#calendar').fullCalendar('removeEvents', id);
                                    $('#eventModal').modal('hide');
                                    swal('¡Eliminado!', 'Evento eliminado con éxito.', 'success');
                                },
                            });
                        }
                    });
                });
            },
        });
    });
</script>


@endsection