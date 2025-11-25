@extends('adminlte::page')
@section('title', 'Estudiantes')
@section('content_header')
<style>
    .accordion-button {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .list-group-item ul {
        list-style-type: disc;
        margin-left: 20px;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        line-height: 1.6;
    }

    .tema {
        margin-bottom: 20px;
    }

    .tema h2 {
        color: #333;
    }

    .tareas {
        list-style-type: none;
        padding-left: 0;
    }

    .tareas li {
        margin: 10px 0;
        padding: 10px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Contenido</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Contenido</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row pb-3 d-print-block">
    <div class="col-12">
        <section>
            <!-- INICIO card -->
            <div class="card">
                <div class="card-body">
                    <center>
                        <dd class="alert alert-light" role="alert">
                            <img width="35px" src="<?php echo e(URL::asset('images/contenido.png')); ?>"> <b>PLAN ACADÉMICO</b>
                        </dd>
                    </center>
                    <!-- div course-content -->
                    <div class="course-content">
                        <div class="topics">
                            <div class="section main clearfix">
                                <!-- INICIO CONTENT -->
                                <div class="content">
                                    @foreach ($trimestres as $item)
                                                    <center>
                                                        <dd class="alert alert-primary" role="alert">
                                                            <img width="35px" src="<?php echo e(URL::asset('images/planificacion.png')); ?>"> <b>{{ $item['trimestre']->periodo }}</b>
                                                        </dd>
                                                    </center>
                                        <h3></h3>

                                        @foreach ($item['materias'] as $m)
                                            <center>
                                                <dd class="alert alert-light" role="alert">
                                                    <img width="35px" src="<?php echo e(URL::asset('images/libro.png')); ?>"> <b>{{ $m['asignatura']->nombre_asig }}</b> 
                                                    <!-- Botón crear tema -->
                                                    <button class="btn btn-dark btn-sm btn-sm float-end"
                                                        onclick="openTemaModal({{ $m['id_asignatura'] }}, {{ $item['trimestre']->id }}, {{ $m['id_curso'] }})">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo
                                                    </button>
                                                </dd>
                                            </center>


                                            @foreach ($m['temas'] as $tema)
                                                <div class="ms-4 p-3 border rounded d-flex justify-content-between align-items-start " id = "tema-{{ $tema->id }}">

                                                    <div class="flex-grow-1">
                                                        <img width="35px" src="<?php echo e(URL::asset('images/temas.png')); ?>"> Tema:{!! $tema->titulo !!}
                                                        <p id="ckcontent">{!! $tema->detalle !!}</p>
                                                        <hr>
                                                                        @if (!empty($tema->imagen))
                                                                            <center>
                                                                                <img width="50%" src="{{ URL::asset("images/{$tema->imagen}") }}">
                                                                            </center>
                                                                        @endif
                                                                            @if ($tema->video > 0)
                                                                                <center>
                                                                                    <iframe width="900px" height="600px" frameborder="2"
                                                                                        src="{{ $tema->video }}"></iframe>
                                                                                </center>
                                                                            @endif
                                                                        @if ($tema->archivo > 0)
                                                                            <a href="" target="_blank">
                                                                                <img width="35px" src="{{ URL::asset('images/archivos.png') }}">
                                                                                {{ $tema->archivo }}
                                                                            </a>
                                                                        @endif
                                                        <hr>
                                                    </div>
                                                
                                                    {{-- ACCIONES A LA DERECHA --}}
                                                    <div class="text-end ms-3" style="width: 120px;">

                                                        {{-- CHECKBOX DE AVANCE --}}
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input"
                                                                type="checkbox"
                                                                onchange="marcarAvance({{ $tema->id }}, this.checked)"
                                                                {{ $tema->avance == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label">
                                                                Avanzado
                                                            </label>
                                                        </div>

                                                        {{-- BOTÓN EDITAR --}}
                                                        <button class="btn btn-warning btn-sm"
                                                                onclick="editarTema({{ $tema->id }})">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        {{-- BOTÓN ELIMINAR --}}
                                                        <button class="btn btn-danger btn-sm"
                                                                onclick="eliminarTema({{ $tema->id }})">
                                                            <i class="fas fa-trash-alt"></i> 
                                                        </button>

                                                    </div>
                                                </div>
                                                <br>  
                                            @endforeach
                                            
                                        @endforeach

                                    @endforeach

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Modal Crear Tema -->

<!-- Modal Crear Tema -->
<div class="modal fade" id="modalTema" tabindex="-1" aria-labelledby="crearTemaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('profesor.temas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="crearTemaLabel">Crear Tema</h5>
                    <a href="#" class="btn btn-link" data-bs-dismiss="modal">X</a>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_asignatura" id="id_asignatura">
                    <input type="hidden" name="id_trimestre" id="id_trimestre">
                    <input type="hidden" name="id_curso" id="id_curso">

                    <div class="form-group mb-3">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Detalle</label>
                        <textarea name="detalle" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Archivo PDF</label>
                            <input type="file" name="archivo" class="form-control" accept="application/pdf">
                        </div>
                        <div class="col-md-6">
                            <label>Video</label>
                            <input type="url" name="video" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!---->
<!-- Modal Editar Tema -->
<div class="modal fade" id="editarTemaModal" tabindex="-1" aria-labelledby="editarTemaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarTemaLabel">Editar Tema</h5>
                <!-- Botón de cierre estilo link como en el modal de usuario -->
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">X</button>

            </div>
            <div class="modal-body" id="editarTemaBody">
                <!-- Aquí se cargará el formulario mediante AJAX -->
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin"></i> Cargando...
                </div>
            </div>
        </div>
    </div>
</div>



@stop
@section('css')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('js')


<script>
    function openTemaModal(idAsignatura, idTrimestre, idCurso) {
        document.getElementById('id_asignatura').value = idAsignatura;
        document.getElementById('id_trimestre').value = idTrimestre;
        document.getElementById('id_curso').value = idCurso;

        var modal = new bootstrap.Modal(document.getElementById('modalTema'));
        modal.show();
    }
</script>
<script>
function marcarAvance(idTema, estado) {
    console.log("Tema:", idTema, "Avanzado:", estado);
    fetch("{{ route('tema.avance') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            id: idTema,
            estado: estado
        })
    })
    .then(res => res.json())
    .then(data => console.log("Avance guardado"))
    .catch(err => console.error(err));
}


function eliminarTema(idTema) {
    Swal.fire({
        title: "¿Seguro de eliminar este tema?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6"
    }).then((result) => {
        if (!result.isConfirmed) return;

        const url = '{{ route("profesor.temas.destroy", ":id") }}'.replace(':id', idTema);

        fetch(url, {
            method: "DELETE",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: "Eliminado",
                    text: data.message,
                    icon: "success",
                    timer: 1200,
                    showConfirmButton: false
                });

                // Eliminar el div del tema del DOM
                const temaDiv = document.getElementById('tema-' + idTema);
                if (temaDiv) temaDiv.remove();
            } else {
                Swal.fire({
                    title: "Error",
                    text: data.message,
                    icon: "error"
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                title: "Error",
                text: "Ocurrió un error al eliminar el tema",
                icon: "error"
            });
        });
    });
}
</script>


<script>
    // abrir modal y cargar partial HTML
    function editarTema(idTema) {
        const url = "{{ route('profesor.temas.edit', ':id') }}".replace(':id', idTema);
        const modalBody = document.getElementById('editarTemaBody');
        modalBody.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Cargando…</div>';

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            modalBody.innerHTML = html;

            // Después de insertar el form, atachar listener para el submit
            const form = document.getElementById('form-editar-tema');
            if (form) attachEditFormHandler(form);
            
            const modal = new bootstrap.Modal(document.getElementById('editarTemaModal'));
            modal.show();
        })
        .catch(err => {
            console.error(err);
            modalBody.innerHTML = '<div class="alert alert-danger">Error cargando el formulario.</div>';
        });
    }

    // manejar submit AJAX del formulario de edición
    function attachEditFormHandler(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = form.getAttribute('action');
            const method = form.querySelector('input[name="_method"]')?.value || 'POST';
            const formData = new FormData(form);

            fetch(action, {
                method: 'POST', // Laravel acepta POST + _method=PUT
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // cerrar modal y actualizar la UI (puedes recargar o actualizar el tema en DOM)
                    bootstrap.Modal.getInstance(document.getElementById('editarTemaModal')).hide();
                    // opcional: actualizar el bloque del tema con nueva info o recargar la página:
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo actualizar'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error al actualizar el tema.');
            });
        });
    }


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
                $('#editarTemaModal').modal('hide');
                location.reload(); // Recargar la página para ver los cambios (opcional)
                // Aquí puedes usar un SweetAlert para notificar al usuario
                $('#usuario-' + response.id).replaceWith(response.html);
            },
            error: function(xhr) {

            if (xhr.status === 422) {  
                // Laravel envía errores de validación
                let errores = xhr.responseJSON.errors;
                let mensaje = "";

                $.each(errores, function(campo, textos) {
                    mensaje += textos[0] + "<br>";
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Errores de Validación',
                    html: mensaje
                });

            } else {
                // Otros errores
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el rol.'
                });
            }
        }
        });
    });
</script>


@endsection