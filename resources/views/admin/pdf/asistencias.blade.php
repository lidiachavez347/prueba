<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias</title>
</head>
<body>
    <h1>Reporte de Asistencias - {{ \Carbon\Carbon::parse($mesSeleccionado)->translatedFormat('F Y') }}</h1>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Rude</th>
                <th>Total Presentes</th>
                <th>Total Ausentes</th>
                <th>Total Justificados</th>
                <th>Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiantes as $estudiante)
                <tr>
                <td>{{ $estudiante->id }}</td>

                <td>{{ $estudiante->nombres_es }} {{ $estudiante->apellidos_es }}</td>
                <td>{{ $estudiante->total_presentes }}</td>
                <td>{{ $estudiante->total_ausentes }}</td>
                <td>{{ $estudiante->total_justificados }}</td>



                <td>{{ $estudiante->totales_asistencias['porcentaje'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
