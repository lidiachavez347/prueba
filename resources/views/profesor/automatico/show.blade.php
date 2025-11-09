@extends('adminlte::page')
@section('title', 'Lista de Opciones')
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

    <h1>Pregunta {!!$preguntas->descripcion!!}</h1>



@stop
@section('content')

    {{-- @if (Auth::user()->hasrole('PROFESOR'))
    <div class="card-header">
        <a href="{{ route('profesor.preguntas.opciones',$preguntas->id) }}" class="btn btn-primary">
            <i class="fa fa-plus" aria-hidden="true"></i> Nueva Respuesta</a>

    </div>
    @endif --}}
    <div class="card-header">
<h2>Opciones</h2>

    </div>
    <br>

        <div class="card body py-2 px-1">
            <table id="productos" class="table table striped shadow-lg mt-4">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>ID</th>
                        <th>OPCIÓN</th>
                        <th>ESTADO</th>
                        <th>NOTA</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($respuestas as $respuesta)
                        <tr>
                            <td>{{ $respuesta->id }}</td>
                            <td>{!! $respuesta->opcion!!}</td>


                            <td width="70px" style="text-align: right">
                                @if ($respuesta->estado == 1)
                                    <span class="badge badge-pill badge-success">Correcta</span>
                                @elseif ($respuesta['estado'] == 0)
                                    <span class="badge badge-pill badge-danger">Incorrecta</span>
                                @else
                                    <span class="badge bg-warning">no permitido</span>
                                @endif
                            </td>
                            <td>
                                {{$respuesta->puntos}}
                            </td>


                            <td>{{ \Carbon\Carbon::parse($respuesta->created_at)->locale('es')->isoFormat(' D \d\e MMMM \d\e\l Y') }}
                            </td>

                        <!-- <td>
                                @if (Auth::user()->hasrole('PROFESOR'))
                                <form action="{{ route('profesor.respuesta.destroy', $respuesta->id) }}"  method="POST"
                                    class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('profesor.respuesta.edit', $respuesta->id) }}"
                                        class="btn btn-warning btn-sm">Editar</a>

                                    @method('delete')
                                    @csrf


                                    <input type="submit" value="Eliminar" class="btn btn-danger btn-sm">
                                </form>
                                @endif

                            </td>-->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

@endsection

@section('js')
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


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}"></script>

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
                timer: 5000,
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

    @if (session('actualizar') == 'ok')
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'white',
                customClass: {
                    popup: 'colored-toast'
                },
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'success',
                title: 'Actualizado exitosamente!'
            })
        </script>
    @endif

    @if (session('eliminar') == 'ok')
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-right',
                iconColor: 'white',
                customClass: {
                    popup: 'colored-toast'
                },
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'success',
                title: 'Eliminado exitosamente!'
            })
        </script>
    @endif

    <script>
        $('.formulario-eliminar').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Estas seguro de eliminar?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar!',
                cancelButtonText: 'Cancelar',

            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                } else {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-right',
                        iconColor: 'white',
                        customClass: {
                            popup: 'colored-toast'
                        },
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'error',
                        title: 'El elemento que deseaba eliminar fue cancelado'
                    })
                }
            })
        });
    </script>

@endsection
