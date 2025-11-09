@extends('adminlte::page')
@section('title', 'Asistencias')

@section('content')
<h2>Listado de Asistencia</h2>
<a href="{{ route('admin.asistencias.create') }}" class="btn btn-primary">Registrar Asistencia</a>

<br>
<div></div>
<div class="card body py-2 px-1">
    <div class="row">
        <div class="col">
            <form method="GET" action="">
                <!--filtrar asistencias en el mes que se encuentre y filtrar dependiendo el mes que desee filtrar-->
                <div class="form-group">
                    <label for="mes">Seleccionar mes:</label>
                    <input type="month" id="mes" name="mes" class="form-control" value="{{ request()->mes ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
            <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Fecha</th>
                        <th>Descripcion</th>
                        <th>Acciones</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($asistencias as $asistencia)
                    <tr>
                        <td>{{ $asistencia->fecha }}</td>
                        <td>{{ $asistencia->descripcion }}</td>
                        <td><a href="{{ route('admin.asistencias.show', $asistencia->id) }}" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Ver">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col">

            <div id="calendar"></div>

            <!-- Modal para agregar un nuevo evento -->
            <div id="modalEvent" style="display:none;">
                <form id="eventForm">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required><br>

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea><br>

                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required><br>

                    <button type="submit">Agregar Evento</button>
                </form>
            </div>

        </div>
    </div>

</div>

@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializar el calendario
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: '{{ route('events.load') }}', // Cargar eventos desde la ruta del backend

            // Configuración para añadir eventos al hacer clic en un día
            dayClick: function(date) {
                $('#modalEvent').show();
                $('#fecha').val(date.format()); // Setear la fecha seleccionada en el formulario
            },

            // Mostrar información adicional al hacer clic en un evento
            eventClick: function(event) {
                alert('Evento: ' + event.title + '\nDescripción: ' + event.description);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#eventForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                titulo: $('#titulo').val(),
                descripcion: $('#descripcion').val(),
                fecha: $('#fecha').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('eventos.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert('Evento agregado correctamente');
                    $('#calendar').fullCalendar('refetchEvents'); // Recargar eventos en el calendario
                    $('#eventForm')[0].reset();
                    $('#modalEvent').hide();
                },
                error: function() {
                    alert('Error al agregar el evento');
                }
            });
        });
    });
</script>
@endsection