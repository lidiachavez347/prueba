@extends('adminlte::page')

@section('title', 'Panel de Datos')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Chartjs', true)

@section('content_header')
<script src="https://code.highcharts.com/highcharts.js"></script>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Panel de Datos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"> Panel de Datos</li>
            </ol>
        </div>
    </div>

</div>
@stop
@section('content')

<?php if (auth()->user()->hasRole('ADMIN')) { ?>

    <div class="container-fluid mt-3 d-block d-md-none">
    <div class="row justify-content-center">
        <div class="col-11">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2 text-primary">
                        <a href="#" class="text-decoration-none">
                        INICIAR SESION CON CODIGO QR
                        SI YA ESTA INICIADA LA SESSION DAR OPCION A DESTRUIRLA PARA CERRAR SESRT
                        MOSTRARLA SESION INICIADAACTUALMENTE
                        </a>
                    </h5>
                    <p class="card-text text-muted mb-3">
                        Escanear el código QR para iniciar sesión.
                    </p>
                    <a href="/auth/scan" class="btn btn-primary w-100">
                        Escanear QR
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="container-fluid">
        
        <div class="row">
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">

                    <div class="inner">
                        <h3>{{$estudiantes}}</h3>
                        <p>Total de Estudiantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <a href="{{ route('admin.estudiantes.index') }}" class="small-box-footer"> mas information
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{$profesores}}</h3>
                        <p>Total de Profesores</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <a href="{{ route('admin.profesores.index') }}" class="small-box-footer"> mas information
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$cursos}}</h3>
                        <p>Clases activas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('admin.grados.index') }}" class="small-box-footer"> mas information
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ round($porcentajeAsistencia, 2) }} <sup>%</sup></h3>
                        <p>Porcentaje de asistencia</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        <i class="fas fa-arrow-circle-"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-5 connectedSoportable ui-sortable">
                <!--
            <div class="card">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="ion ion-clipboard"></i>
                        Lista
                    </h3>
                    <div class="card-tools">
                        <ul class="pagination pagination-sm">
                            <li class="page-item"> <a href="" class="page-item">
                                    <<
                                        </li>
                            <li class="page-item"><a href="" class="page-link">1</a></li>
                            <li class="page-item"><a href="" class="page-link">2</a></li>
                            <li class="page-item"><a href="" class="page-link">3</a></li>
                            <li class="page-item"><a href="" class="page-item">>></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="todo-list ui-sortable" data-widget="todo-list">
                        <li class="">
                            <span class="handle ui-sortable-handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value name="todo1" id="todocheck1">
                                <label for="todocheck1"></label>
                                <span class="text">tarea 1</span>
                                <small class="badge badge-danger">
                                    <i class="far fa-clok"></i>
                                    2 min
                                </small>
                                <div class="tools">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash-o"></i>
                                </div>
                            </div>
                        </li>
                        <li class="">
                            <span class="handle ui-sortable-handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value name="todo1" id="todocheck1">
                                <label for="todocheck1"></label>
                                <span class="text">tarea 1</span>
                                <small class="badge badge-info">
                                    <i class="far fa-clok"></i>
                                    2 horas
                                </small>
                                <div class="tools">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash-o"></i>
                                </div>
                            </div>
                        </li>
                        <li class="">
                            <span class="handle ui-sortable-handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value name="todo1" id="todocheck1">
                                <label for="todocheck1"></label>
                                <span class="text">tarea 3</span>
                                <small class="badge badge-warning">
                                    <i class="far fa-clok"></i>
                                    2 dias
                                </small>
                                <div class="tools">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash-o"></i>
                                </div>
                            </div>
                        </li>
                        <li class="">
                            <span class="handle ui-sortable-handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value name="todo1" id="todocheck1">
                                <label for="todocheck1"></label>
                                <span class="text">tarea 1</span>
                                <small class="badge badge-success">
                                    <i class="far fa-clok"></i>
                                    5 dias
                                </small>
                                <div class="tools">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash-o"></i>
                                </div>
                            </div>
                        </li>
                        <li class="">
                            <span class="handle ui-sortable-handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value name="todo1" id="todocheck1">
                                <label for="todocheck1"></label>
                                <span class="text">tarea 1</span>
                                <small class="badge badge-primary">
                                    <i class="far fa-clok"></i>
                                    2 semana
                                </small>
                                <div class="tools">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash-o"></i>
                                </div>
                            </div>
                        </li>
                        <li class=""> <span class="handle ui-sortable-handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value name="todo1" id="todocheck1">
                                <label for="todocheck1"></label>
                                <span class="text">tarea 1</span>
                                <small class="badge badge-black">
                                    <i class="far fa-clok"></i>
                                    2 min
                                </small>
                                <div class="tools">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash-o"></i>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer clearfix">
                    <button type="button" class="btn btn-primary float-right">
                        <i class="fas fa-plus"> </i> Agregar tarea
                    </button>
                </div>
            </div>-->

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title"> <i class="fas fa-chart-pie mr-1">
                                    </i> datos</h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">

                                        <li class="nav-item"><a href="#" class="nav-link">donut</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 280px;">
                                        <div class="chartjs-size-monitor">
                                            <div id="container" style="width:100%; height:300px;"></div>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    Highcharts.chart('container', {
                                                        chart: {
                                                            type: 'pie'
                                                        },
                                                        title: {
                                                            text: 'Estudiantes Registrados por Curso'
                                                        },
                                                        tooltip: {
                                                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                                        },
                                                        plotOptions: {
                                                            pie: {
                                                                allowPointSelect: true,
                                                                cursor: 'pointer',
                                                                dataLabels: {
                                                                    enabled: true,
                                                                    format: '<b>{point.name}</b>: {point.y} estudiantes'
                                                                }
                                                            }
                                                        },
                                                        series: [{
                                                            name: 'Porcentaje',
                                                            colorByPoint: true,
                                                            data: @json($data)
                                                        }]
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <canvas id="revenue-chart-canvas" height="200" style="height:200; display:block; width:300px;" width="200" class="chartjs-render-monitor"></canvas>
                                    </div>
                                    <div class="chart tab-pane" id="sales-chart" style="position:relative; height: 300px;">
                                        <canvas id="sales-chart-canvas" height="0" style="height: 0; display:block; width: 0;" class="chartjs-rebder-monitor" width="0"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title"> <i class="fas fa-chart-pie mr-1">
                                    </i> datos</h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item"><a href="#" class="nav-link">donut</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 280px;">
                                        <div class="chartjs-size-monitor">


                                            <div id="container-genero" style="width:100%; height:300px;"></div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    Highcharts.chart('container-genero', {
                                                        chart: {
                                                            type: 'pie'
                                                        },
                                                        title: {
                                                            text: 'Estudiantes Registrados por Género'
                                                        },
                                                        tooltip: {
                                                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                                        },
                                                        plotOptions: {
                                                            pie: {
                                                                allowPointSelect: true,
                                                                cursor: 'pointer',
                                                                dataLabels: {
                                                                    enabled: true,
                                                                    format: '<b>{point.name}</b>: {point.y} estudiantes'
                                                                }
                                                            }
                                                        },
                                                        series: [{
                                                            name: 'Porcentaje',
                                                            colorByPoint: true,
                                                            data: @json($dataGenero)
                                                        }]
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <canvas id="revenue-chart-canvas" height="200" style="height:300; display:block; width:400px;" width="400" class="chartjs-render-monitor"></canvas>
                                    </div>
                                    <div class="chart tab-pane" id="sales-chart" style="position:relative; height: 400px;">
                                        <canvas id="sales-chart-canvas" height="0" style="height: 0; display:block; width: 0;" class="chartjs-rebder-monitor" width="0"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-lg-7">
                <div class="card bg-gradient-sucess">
                    <div class="card-header border-0 ui-sortable-handle" style="cursor: move;"></div>
                    <div class="card-body pt-0" style="display: block;">
                        <div id="container-asistencia" style="width:100%; height:330px;"></div>

                        <script src="https://code.highcharts.com/highcharts.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Highcharts.chart('container-asistencia', {
                                    chart: {
                                        type: 'line'
                                    },
                                    title: {
                                        text: 'Asistencia de Estudiantes por Día'
                                    },
                                    xAxis: {
                                        categories: @json($fechas),
                                        title: {
                                            text: 'Fecha'
                                        }
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Cantidad de Asistencias'
                                        }
                                    },
                                    tooltip: {
                                        shared: true,
                                        valueSuffix: ' asistencias'
                                    },
                                    series: [{
                                            name: 'Presente',
                                            data: @json($presentes),
                                            color: '#28a745' // Verde
                                        },
                                        {
                                            name: 'Ausente',
                                            data: @json($ausentes),
                                            color: '#dc3545' // Rojo
                                        },
                                        {
                                            name: 'Tarde',
                                            data: @json($tarde),
                                            color: '#ffc107' // Amarillo
                                        },
                                        {
                                            name: 'Justificado',
                                            data: @json($justificados),
                                            color: '#17a2b8' // Azul
                                        }
                                    ]
                                });
                            });
                        </script>

                    </div>
                </div><!--
                <div class="card bg-gradient-sucess">
                    <div class="card-header border-0 ui-sortable-handle" style="cursor: move;"></div>
                    <div class="card-body pt-0" style="display: block;">
                        <div id="calendar" style="width: 100%;">
                            <div class="bootstrap-datetimepicker-widget usetwentyfour">
                                <ul class="list-unstyled">
                                    <li class="show">
                                        <div class="datepicker">
                                            <div class="datepicker-days">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th class="prev" data-action="previous">
                                                                <span class="fa fa-chevron-left" title="mes anterior"></span>
                                                            </th>
                                                            <th class="picker-switch" data-action="pickerSwitch" colspan="5" title="seleccione mes">
                                                                septiembre 2024
                                                            </th>
                                                            <th class="next" data-action="next">
                                                                <span class="fa fa-chevron-right" title="siguente mes"></span>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th class="dow">lunes</th>
                                                            <th class="dow">martes</th>
                                                            <th class="dow">miercoles</th>
                                                            <th class="dow">jueves</th>
                                                            <th class="dow">viernes</th>
                                                            <th class="dow">sabado</th>
                                                            <th class="dow">domingo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="datepicker-months" style="display: none;"></div>
                                            <div class="datepicker"></div>
                                            <div class="datepicker"></div>
                                        </div>
                                    </li>
                                    <li class="picker-switch accordion-toggle"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>-->
            </section>
        </div>
    </div>
<?php } ?>
<?php if (auth()->user()->hasRole('PROFESOR')) {
    //dd(auth()->user()); // Verifica el usuario autenticado
?>
    <!-- Código para el profesor -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">

                    <div class="inner">
                        <h3>{{$estudiantes}}</h3>
                        <p>Total de Estudiantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <a href="{{ route('profesor.estudiantes.index') }}" class="small-box-footer"> mas information
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$cargo}}</h3>
                        <p>Materias acargo</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        <i class="fas fa-arrow-circle-"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3> <sup>{{$porcentajeAsistencia}}</sup></h3>
                        <p>Porcentaje de asistencia</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <a href="{{ route('profesor.asistencias.index') }}" class="small-box-footer">Más información
                        <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <section class="col-lg-5 connectedSoportable ui-sortable">

                <div class="row">

                    <div class="col">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title"> <i class="fas fa-chart-pie mr-1">
                                    </i> datos</h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item"><a href="#" class="nav-link">donut</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 280px;">
                                        <div class="chartjs-size-monitor">


                                            <div id="container-genero" style="width:100%; height:300px;"></div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    Highcharts.chart('container-genero', {
                                                        chart: {
                                                            type: 'pie'
                                                        },
                                                        title: {
                                                            text: 'Estudiantes Registrados por Género'
                                                        },
                                                        tooltip: {
                                                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                                        },
                                                        plotOptions: {
                                                            pie: {
                                                                allowPointSelect: true,
                                                                cursor: 'pointer',
                                                                dataLabels: {
                                                                    enabled: true,
                                                                    format: '<b>{point.name}</b>: {point.y} estudiantes'
                                                                }
                                                            }
                                                        },
                                                        series: [{
                                                            name: 'Porcentaje',
                                                            colorByPoint: true,
                                                            data: @json($dataGenero)
                                                        }]
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <canvas id="revenue-chart-canvas" height="200" style="height:300; display:block; width:400px;" width="400" class="chartjs-render-monitor"></canvas>
                                    </div>
                                    <div class="chart tab-pane" id="sales-chart" style="position:relative; height: 400px;">
                                        <canvas id="sales-chart-canvas" height="0" style="height: 0; display:block; width: 0;" class="chartjs-rebder-monitor" width="0"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- asistencia mensual de estudiantes-->
            <section class="col-lg-7">
                <div class="card bg-gradient-sucess">
                    <div class="card-header border-0 ui-sortable-handle" style="cursor: move;"></div>
                    <div class="card-body pt-0" style="display: block;">
                        <div id="container-asistencia" style="width:100%; height:330px;"></div>

                        <script src="https://code.highcharts.com/highcharts.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Convertir las fechas a su formato de mes
                                const fechasConMes = @json($fechas).map(fecha => {
                                    const opciones = {
                                        month: 'long',
                                        day: 'numeric'
                                    }; // Formato: mes día
                                    return new Date(fecha).toLocaleDateString('es-ES', opciones);
                                });

                                Highcharts.chart('container-asistencia', {
                                    chart: {
                                        type: 'line'
                                    },
                                    title: {
                                        text: 'Asistencia mensual'
                                    },
                                    xAxis: {
                                        categories: fechasConMes,
                                        title: {
                                            text: 'Fecha'
                                        }
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Cantidad de Asistencias'
                                        }
                                    },
                                    tooltip: {
                                        shared: true,
                                        valueSuffix: ' asistencias'
                                    },
                                    series: [{
                                            name: 'Presente',
                                            data: @json($presentes),
                                            color: '#28a745' // Verde
                                        },
                                        {
                                            name: 'Ausente',
                                            data: @json($ausentes),
                                            color: '#dc3545' // Rojo
                                        },
                                        {
                                            name: 'Tarde',
                                            data: @json($tarde),
                                            color: '#ffc107' // Amarillo
                                        },
                                        {
                                            name: 'Justificado',
                                            data: @json($justificados),
                                            color: '#17a2b8' // Azul
                                        }
                                    ]
                                });
                            });
                        </script>


                    </div>
                </div>
            </section>
        </div>

        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $presentess }}</h3>
                        <p>Presente</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        <i class="fas fa-arrow-circle-"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $ausentess }}</h3>
                        <p>Ausente</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        <i class="fas fa-arrow-circle-"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-linth">
                    <div class="inner">
                        <h3>{{ $justificadoss }}</h3>
                        <p>Justificado</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        <i class="fas fa-arrow-circle-"></i>
                    </a>
                </div>
            </div>
        </div>


    </div>
<?php } ?>
<?php if (auth()->user()->hasRole('TUTOR')) {
    //dd(auth()->user()); // Verifica el usuario autenticado
XXXX
?>
@if($usuario->email_verified_at)
    <span class="badge badge-success">✔ Verificado</span>
@else
    <span class="badge badge-warning">Pendiente</span>
@endif

SDFSDFSDF

    <div class="row">
@foreach ($estudiantesDatos as $datos)

        <!-- Gráfico de Rendimiento Académico y Asistencia para el Estudiante -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Rendimiento de {{ $datos['nom']}} {{ $datos['apellidos_es']}} </h3>
                </div>
                <div class="card-body">
                    <!-- Gráfico de Notas -->
                    <h4>Promedio de Notas:</h4>
                    <div class="chart">
                        <canvas id="chartNotas{{ $datos['estudiante']->id }}"></canvas>
                    </div>
                    <!-- Gráfico de Asistencia -->
                    <h4 class="mt-4">Asistencia:</h4>
                    <div class="chart">
                        <canvas id="chartAsistencia{{ $datos['estudiante']->id }}"></canvas>
                    </div>
                </div>
            </div>
        </div>


    <script>
        // Gráfico de notas para el estudiante
        var ctxNotas{{ $datos['estudiante']->id }} = document.getElementById('chartNotas{{ $datos['estudiante']->id }}').getContext('2d');
        var chartNotas{{ $datos['estudiante']->id }} = new Chart(ctxNotas{{ $datos['estudiante']->id }}, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($datos['promedios'] as $promedio)
                        '{{ $promedio->asignatura }}', // Nombre de la asignatura
                    @endforeach
                ],
                datasets: [{
                    label: 'Promedio de Notas',
                    data: [
                        @foreach ($datos['promedios'] as $promedio)
                            {{ $promedio->promedio }},
                        @endforeach
                    ], // Promedio por materia
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'], // Colores por materia
                    borderColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10
                    }
                }
            }
        });

        // Gráfico de asistencia para el estudiante
        var ctxAsistencia{{ $datos['estudiante']->id }} = document.getElementById('chartAsistencia{{ $datos['estudiante']->id }}').getContext('2d');
        var chartAsistencia{{ $datos['estudiante']->id }} = new Chart(ctxAsistencia{{ $datos['estudiante']->id }}, {
            type: 'pie',
            data: {
                labels: ['Presente', 'Justificado', 'Ausente'],
                datasets: [{
                    label: 'Asistencia',
                    data: [
                        {{ $datos['asistencia']['presente'] }},
                        {{ $datos['asistencia']['justificado'] }},
                        {{ $datos['asistencia']['ausente'] }}
                    ], // Datos de asistencia por estado
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endforeach
</div>

<?php } ?>

<?php if (auth()->user()->hasRole('SECRETARIA')): ?>
     @if(auth()->user()->email_verified_at)
        <span class="badge badge-success">✔ Verificado</span>
    @else
        <span class="badge badge-warning">Pendiente</span>

        <!-- Botón para reenviar el correo de verificación -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Reenviar correo de verificación</button>
        </form>
    @endif
<?php endif; ?>


@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style>
    .left {
        float: left;
        width: 50%;
        /* Ajusta el ancho si es necesario */

    }

    .right {
        float: right;
        width: 10%;
        /* Ajusta el ancho si es necesario */

    }
</style>

<!-- Cargar los estilos de AdminLTE y Chart.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">


<!-- Cargar Chart.js para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de notas para el estudiante 1 (Juan Pérez)
    var ctx1 = document.getElementById('chartNotas1').getContext('2d');
    var chartNotas1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Materia 1', 'Materia 2', 'Materia 3', 'Materia 4'], // Materias
            datasets: [{
                label: 'Promedio de Notas',
                data: [7.5, 6.8, 8.2, 5.9], // Notas promedio por materia
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'], // Colores por nota
                borderColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });

    // Gráfico de asistencia para el estudiante 1 (Juan Pérez)
    var ctx2 = document.getElementById('chartAsistencia1').getContext('2d');
    var chartAsistencia1 = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Asistencias', 'Inasistencias'],
            datasets: [{
                label: 'Asistencia',
                data: [85, 15], // Porcentaje de asistencia vs inasistencia
                backgroundColor: ['#28a745', '#dc3545'],
                borderColor: ['#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    // Gráfico de notas para el estudiante 2 (María Gómez)
    var ctx3 = document.getElementById('chartNotas2').getContext('2d');
    var chartNotas2 = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: ['Materia 1', 'Materia 2', 'Materia 3', 'Materia 4'], // Materias
            datasets: [{
                label: 'Promedio de Notas',
                data: [6.5, 7.2, 5.8, 7.9], // Notas promedio por materia
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                borderColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });

    // Gráfico de asistencia para el estudiante 2 (María Gómez)
    var ctx4 = document.getElementById('chartAsistencia2').getContext('2d');
    var chartAsistencia2 = new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: ['Asistencias', 'Inasistencias'],
            datasets: [{
                label: 'Asistencia',
                data: [70, 30], // Porcentaje de asistencia vs inasistencia
                backgroundColor: ['#28a745', '#dc3545'],
                borderColor: ['#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

@stop
@section('js')