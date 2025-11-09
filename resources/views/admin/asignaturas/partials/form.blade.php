<div class="form-group">
    <label>Seleccionar Profesor:</label>
    <select name="id_profesor" class="form-control">
        <option value="">SELECCIONAR PROFESOR</option>
        @foreach($profesor as $profesor)
        <option value="{{ $profesor->id }}">{{ $profesor->nombres}} {{ $profesor->apellidos }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Seleccionar Curso:</label>
    <select name="id_curso" class="form-control">
        <option value="">SELECCIONAR CURSO</option>
        @foreach($cursos as $curso)
        <option value="{{ $curso->id }}">{{ $curso->nombre_curso }} - {{ $curso->paralelo }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Seleccionar Nivel:</label>
    <select name="id_nivel" class="form-control">
        <option value="">SELECCIONAR NIVEL</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->id }}">{{ $nivel->nivel }} - {{ $nivel->turno }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Asignaturas:</label><br>
    @foreach($asignaturas as $asignatura)
    <input type="checkbox" name="asignaturas[]" value="{{ $asignatura->id }}">
    {{ $asignatura->nombre_asig }}<br>
    @endforeach
</div>