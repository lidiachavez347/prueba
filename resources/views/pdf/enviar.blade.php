<style>
    .fila {
        display: flex;
        justify-content: space-between;
        align-items: flex-start; /* Asegura que estén a la misma altura */
        width: 100%;
    }

    .col {
        width: 48%; /* DOMPDF respeta mejor valores fijos */
        display: block;
    }

    p {
        margin: 2px 0;
        font-size: 14px;
    }
</style>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    th, td {
        border: 1px solid #000;
        padding: 6px;
        text-align: center;
    }

    thead th {
        background: #333;
        color: white;
        font-weight: bold;
    }

    .titulo-header {
        background: #444;
        color: white;
        font-size: 14px;
        font-weight: bold;
        text-align: left;
        padding: 10px;
    }

    .materia-col {
        text-align: left;
        font-weight: bold;
        background: #f2f2f2;
    }

    tbody tr:nth-child(even) {
        background: #f7f7f7;
    }
</style>
<style>
table.ninguno, 
table.ninguno * {
    all: unset;              /* Resetea TODOS los estilos heredados */
    display: revert;         /* Restaura el comportamiento natural */
}

table.ninguno {
    width: 100%;
    border-collapse: collapse;
}

table.ninguno td {
    vertical-align: top;
    padding: 5px;
}
</style>
<div class="container-fluid">
<center>            <h1 >Libreta Escolar Electronica</h1>
            <h3>Educacion Primaria Comunitaria</h3></center>
<table class="ninguno">
    <thead>
        <td >
            <p><b>Unidad Educativa: </b>81710013 - CORONEL MIGUEL ESTENSSORO</p>  
            <p><b>Distrito escolar: </b>YABUIBA</p>
            <p><b>Turno: </b>TARDE</p></td>
           
        <td><p><b>Localidad:</b>YACUIBA - CIUDAD YACUIBA</p>
<p><b>Departamento:</b>TARIJA</p>
<p><B>Dependencia:</B> FISCAL O ESTATAL</p></td>

<td>
    
    <img src="{{ $qrRuta }}" alt="QR Boletín" style="width:50px; height:50px;">
    <p>Escanea el QR para ver el boletín en línea:</p>
</td>
    </thead>
</table>



</div>

<br>
<div class="container-fluid">
    
<div class="card body py-2 px-1">
    <table>
        <thead>
            <tr>
                <th colspan="5"><p>Codigo RUDE: {{ $estudiante->rude_es  }}  Apellidos y Nombres: {{ $estudiante->apellidos_es }} {{ $estudiante->nombres_es }} </p><p>Año de escolaridad:{{ $estudiante->curso->nombre_curso }} - {{ $estudiante->curso->paralelo }}    Gestion: {{ $estudiante->curso->gestion->gestion }}</p> </th>
            
            </tr>
            <tr>

                <th>Areas Curriculares</th>
                    @foreach($trimestres as $tri)
                        <td>{{ $tri }}</td>
                    @endforeach

                    <th> PROMEDIO ANUAL</th>

            </tr>
        </thead>

        <tbody>
            @foreach($notasAgrupadas as $materia => $detalleTrimestres)
        <tr>
            <td class="materia-col">{{ $materia }}</td>

            @php
                $suma = 0;
                $contador = 0;

                foreach ($trimestres as $tri) {
                    if (isset($detalleTrimestres[$tri])) {
                        $suma += $detalleTrimestres[$tri];
                        $contador++;
                    }
                }

                $promedioAnual = $contador > 0 ? round($suma / $contador, 1) : '-';
            @endphp

            @foreach($trimestres as $tri)
                <td>{{ $detalleTrimestres[$tri] ?? '-' }}</td>
            @endforeach

            {{-- Promedio anual --}}
            <td><b>{{ $promedioAnual }}</b></td>
        </tr>
    @endforeach
        </tbody>
    </table>
</div></div>
    
