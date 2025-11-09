@extends('adminlte::page')
@section('title', 'Asistencias')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

@endsection
@section('content')

<h2>Registrar Asistencia</h2>
<form action="{{ route('admin.asistencias.store') }}" method="POST">
    @csrf
    <div class="modal-footer">
        <label>Fecha:</label>
        <input type="date" name="fecha" required><br><br>
        <label >Descripcion</label>
        <input type="text" name="descripcion" >
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('admin.profesores.create') }}" class="btn btn-light" data-toggle="tooltip" data-placement="left" title="Exportar">
            <i class="fa fa-upload" aria-hidden="true"></i> </a>
    </div>

    <br>


    <div class="card body py-2 px-1">
        <table id="productos" class="table table striped shadow-lg mt-4table table-striped">
            <thead class="bg-dark text-white">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->nombres }} {{ $user->apellidos }}</td>
                    <td>
                        <select name="estado[]">
                            <option value="presente">Presente</option>
                            <option value="ausente">Ausente</option>
                            <option value="tarde">Tarde</option>
                            <option value="justificado">Justificado</option>
                        </select>
                    </td>
                    <td><input type="text" name="observaciones[]"></td>
                    <input type="hidden" name="user_id[]" value="{{ $user->id }}">
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</form>
@endsection