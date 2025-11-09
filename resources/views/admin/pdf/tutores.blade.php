<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tutores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
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
        <h2>Lista de Tutores</h2>
    </div>
    <h3>Listado de Tutores</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Estado</th>
                <th>Telefono</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $estudiante)
            <tr>
                <td>{{ $estudiante->id }}</td>
                <td>{{ $estudiante->nombres }}</td>
                <td>{{ $estudiante->apellidos }}</td>
                <td>@if($estudiante->estado_user == 1) Activo @else Inactivo @endif</td>
                <td>{{ $estudiante->telefono }}</td>
                <td>{{ $estudiante->direccion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>