<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Estudiantes</title>
<style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
</style>
<style>
    
    body {
        font-family: sans-serif;
    }

    footer {
        position: fixed;
        bottom: -40px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 12px;
    }

    footer hr {
        border: 0;
        border-top: 1px solid #000;
        margin-bottom: 5px;
    }
</style>
<style>
    @page {
        margin: 20px 30px 80px 30px;

        /* marco en cada página */
    @frame border_frame {
            -pdf-frame-border: 2px solid #000; 
            left: 20pt; 
            width: 572pt; 
            top: 20pt; 
            height: 742pt;
        }
    }
</style>
</head>
<body>
    <div style="position: relative; width: 100%;">

    <!-- Imagen alineada a la derecha -->
    <img src="/public/images/logo.jpg" 
        alt="" 
        style="width: 50px; height: 50px; position: absolute; right: 0; top: 0;">

    <!-- Título centrado -->
    <h2 style="text-align: center;">REPORTES DE ESTUDIANTES</h2>

    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>RUDE</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <TH>Genero</TH>
                <th>Curso</th>
                
                <th>Estado</th>
                <th>Tutor</th>
                <th>Telefono</th>
                <th>Dirección</th>
                <TH>Fecha de registro</TH>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiante as $estudiante)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $estudiante->rude_es }}</td>
                    <td>{{ $estudiante->nombres_es }}</td>
                    <td>{{ $estudiante->apellidos_es }}</td>
                    <td>{{ $estudiante->genero_es ? 'MASCULINO' : 'FEMENINO' }}</td>
                    <td>{{ $estudiante->curso->nombre_curso }} {{ $estudiante->curso->paralelo }} </td>
                    <td>@if($estudiante->estado_es == 1) ACTIVO @else INACTIVO @endif</td>
                    <td>{{ $estudiante->tutor->nombres }}{{ $estudiante->tutor->apellidos }}</td>
                    <td>{{ $estudiante->tutor->telefono }}</td>
                    <td>{{ $estudiante->tutor->direccion }}</td>
                    <td>{{ $estudiante->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
        <footer>
    <hr>
    <h4>Unidad Educativa "Cnel. Miguel Estenssoro Tarde"</h4>
    <p>Dirección: Esquina Campero E/ Av. Santa Cruz y C. Ballivián</p>
</footer>

</body>
</html>
