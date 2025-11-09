
                <div class="row">
                    <div class="col-md-6">
                        <strong>RUDE:</strong>
                        <p>{{ $estudiante->rude_es }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>CI:</strong>
                        <p>{{ $estudiante->ci_es }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong>Nombre Completo:</strong>
                        <p>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha de Nacimiento:</strong>
                        <p>{{ \Carbon\Carbon::parse($estudiante->fecha_nac_es)->isoFormat('D [de] MMMM [de] YYYY') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong>Género:</strong>
                        <p>{{ $estudiante->genero_es == '1' ? 'Masculino' : 'Femenino' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Curso:</strong>
                        <p>{{ $estudiante->curso->nombre_curso }}</p> <!-- Asume que tienes la relación con el curso -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                    <strong>Estado:</strong>
                   
                    <span class="badge badge-{{ $grado->estado_es ? 'success' : 'danger' }}">
                        {{ $grado->estado_es ? 'ACTIVO' : 'NO ACTIVO' }}
                    </span>
           
                    </div>
                    <div class="col-md-6">
                        <strong>Imagen:</strong>
                        @if($estudiante->imagen_es)
                        <img src="{{ asset('images/' . $estudiante->imagen_es) }}" alt="Imagen del Estudiante" style="max-height: 100px;">
                        @else
                        <p>No se ha subido una imagen</p>
                        @endif
                    </div>
                </div>

                <!-- Mostrar información de los tutores -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>Tutores:</h4>
                        @forelse ($tutores as $tutor)
                        <div class="card mb-3">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    @if($tutor->imagen)
                                    <img src="{{ asset('images/' . $tutor->imagen) }}" class="card-img" alt="Imagen del Tutor" >
                                    @else
                                    <img src="{{ asset('images/default.png') }}" class="card-img" alt="Tutor por defecto" >
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><strong>Nombre completo : </strong>{{ $tutor->nombres }} {{ $tutor->apellidos }}</h5>
                                        <p class="card-text"><strong>Teléfono:</strong> {{ $tutor->telefono }}</p>
                                        <p class="card-text"><strong>Direccion:</strong> {{ $tutor->direccion }}</p>
                                        <p class="card-text"><strong>Estado:</strong>
                                            <span class="badge badge-{{ $grado->estado_user ? 'success' : 'danger' }}">
                                                {{ $grado->estado_user ? 'ACTIVO' : 'NO ACTIVO' }}
                                            </span>
           
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p>No hay tutores asignados.</p>
                        @endforelse
                    </div>
                </div>



@section('css')
<style>
    .left {
        float: left;
        width: 50%;
        /* Ajusta el ancho si es necesario */

    }

    .right {
        float: right;
        width: 10%;
        /* Ajusta el ancho si es necesario */

    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Puedes agregar algún script específico si lo necesitas
    });
</script>
@endsection