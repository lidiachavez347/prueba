<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Lista de Estudiantes</h2>
    </div>
    <h3>Listado de Estudiantes</h3>
    <table class="table">
        <thead>
            <tr>
                <th>RUDE</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Curso</th>
                <th>Paralelo</th>
                <th>Estado</th>
                <th>Tutor</th>
                <th>Telefono</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiante as $estudiante)
                <tr>
                    <td>{{ $estudiante->rude_es }}</td>
                    <td>{{ $estudiante->nombres_es }}</td>
                    <td>{{ $estudiante->apellidos_es }}</td>
                    <td>{{ $estudiante->curso->nombre_curso }} {{ $estudiante->curso->paralelo }} </td>
                    <td>@if($estudiante->estado_es == 1) Activo @else Inactivo @endif</td>
                    <td>{{ $estudiante->tutores->nombres }}{{ $estudiante->tutores->apellidos }}</td>
                    <td>{{ $estudiante->tutores->telefono }}</td>
                    <td>{{ $estudiante->tutores->direccion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
