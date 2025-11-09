<form action="{{ route('admin.curricular.update', $trimestre->id) }}" method="POST">
   @csrf
    @method('PUT')

    <div class="form-group">
        <label for="periodo">Periodo</label>
        <input type="text" name="periodo" id="periodo" class="form-control" value="{{ old('periodo', $trimestre->periodo) }}">
    </div>

    <div class="form-group">
        <label for="fecha_inicio">Fecha Inicio</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $trimestre->fecha_inicio) }}">
    </div>

    <div class="form-group">
        <label for="fecha_fin">Fecha Fin</label>
        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ old('fecha_fin', $trimestre->fecha_fin) }}">
    </div>

    <div class="form-group">
        <label for="estado">Estado</label>
        <input type="number" name="estado" id="estado" class="form-control" value="{{ old('estado', $trimestre->estado) }}">
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
