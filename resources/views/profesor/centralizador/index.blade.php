@extends('adminlte::page')

@section('content')
<br>
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white text-center py-2">
            <h4 class="mb-0">CONSOLIDADO DE NOTAS</h4>
        </div>

        <div class="card-body p-2">

            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="3">N°</th>
                            <th rowspan="3" class="text-left">APELLIDOS Y NOMBRES</th>

                            <th colspan="10">PRIMER TRIMESTRE</th>
                            <th colspan="10">SEGUNDO TRIMESTRE</th>
                            <th colspan="10">TERCER TRIMESTRE</th>

                            <th rowspan="3">PROMEDIO ANUAL</th>
                        </tr>

                        <tr>
                            @for ($i = 1; $i <= 3; $i++)
                                @foreach($asignaturas as $asig)
                                    <th>{{ $asig->nombre_asig }}</th>
                                @endforeach
                                <th>PROM</th>
                            @endfor
                        </tr>

                        <tr>
                            @for ($i = 1; $i <= 3; $i++)
                                @foreach($asignaturas as $asig)
                                    <th>{{ $loop->iteration }}</th>
                                @endforeach
                                <th>PR</th>
                            @endfor
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($listado as $index => $fila)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-left">{{ $fila['estudiante'] }}</td>

                                {{-- PRIMER TRIMESTRE --}}
                                @php $promT1 = []; @endphp
                                @foreach($fila['materias'] as $mat)
                                    <td>{{ $mat['t1'] ?? '-' }}</td>
                                    @php if ($mat['t1']) $promT1[] = $mat['t1']; @endphp
                                @endforeach
                                <td><b>{{ count($promT1) ? round(array_sum($promT1)/count($promT1),2) : '-' }}</b></td>

                                {{-- SEGUNDO TRIMESTRE --}}
                                @php $promT2 = []; @endphp
                                @foreach($fila['materias'] as $mat)
                                    <td>{{ $mat['t2'] ?? '-' }}</td>
                                    @php if ($mat['t2']) $promT2[] = $mat['t2']; @endphp
                                @endforeach
                                <td><b>{{ count($promT2) ? round(array_sum($promT2)/count($promT2),2) : '-' }}</b></td>

                                {{-- TERCER TRIMESTRE --}}
                                @php $promT3 = []; @endphp
                                @foreach($fila['materias'] as $mat)
                                    <td>{{ $mat['t3'] ?? '-' }}</td>
                                    @php if ($mat['t3']) $promT3[] = $mat['t3']; @endphp
                                @endforeach
                                <td><b>{{ count($promT3) ? round(array_sum($promT3)/count($promT3),2) : '-' }}</b></td>

                                {{-- PROMEDIO ANUAL --}}
                                @php
                                    $anuales = [];
                                    foreach($fila['materias'] as $mat){
                                        if($mat['anual']) $anuales[] = $mat['anual'];
                                    }
                                @endphp
                                <td><b>{{ count($anuales) ? round(array_sum($anuales)/count($anuales),2) : '-' }}</b></td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection
