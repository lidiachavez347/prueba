
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
                        <p>{{ $estudiante->genero_es == '1' ? 'MASCULINO' : 'FEMENINO' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Curso:</strong>
                        <p>{{ $estudiante->curso->nombre_curso }} {{ $estudiante->curso->paralelo }} - {{ $estudiante->curso->gestion->gestion }}</p> <!-- Asume que tienes la relación con el curso -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                    <strong>Estado:</strong>

                    <span class="badge badge-pill badge-{{ $estudiante->estado_es ? 'success' : 'danger' }}">
                        {{ $estudiante->estado_es ? 'ACTIVO' : 'NO ACTIVO' }}
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
                                        <p class="card-text"><strong>Email:</strong> {{ $tutor->email }}</p>
                                        <p class="card-text"><strong>Direccion:</strong> {{ $tutor->direccion }}</p>
                                        <p class="card-text"><strong>Estado:</strong>
                                            <span class="badge badge-pill badge-{{ $tutor->estado_user ? 'success' : 'danger' }}">
                        {{ $tutor->estado_user ? 'ACTIVO' : 'NO ACTIVO' }}
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
