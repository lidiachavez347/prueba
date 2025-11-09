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
    <!-- Modal para mostrar la información del evento -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Detalles del Evento</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para mostrar los detalles del evento -->
                <form>
                    <div class="form-group">
                        <label for="eventTitle">Título</label>
                        <input type="text" class="form-control" id="eventTitle" readonly>
                    </div>
                    <div class="form-group">
                        <label for="eventStart">Fecha de inicio</label>
                        <input type="text" class="form-control" id="eventStart" readonly>
                    </div>
                    <div class="form-group">
                        <label for="eventEnd">Fecha de fin</label>
                        <input type="text" class="form-control" id="eventEnd" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

                });
            },


            eventClick: function(event) {
                let id = event.id;

                // Prellenar los campos del modal con la información del evento
                $('#eventTitle').val(event.title); // Título del evento
                $('#eventStart').val(moment(event.start).format('YYYY-MM-DD')); // Fecha de inicio
                $('#eventEnd').val(event.end ? moment(event.end).format('YYYY-MM-DD') : ''); // Fecha de finalización (si existe)
                $('#eventColor').val(event.color || '#3788d8'); // Color del evento (si existe)

                // Mostrar el modal con la información cargada
                $('#eventModal').modal('show');
            }
        });
    });
</script>


@endsection