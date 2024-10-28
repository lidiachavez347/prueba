@extends('adminlte::page')

@section('title', 'Panel de Datos')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Chartjs', true)

@section('content_header')


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
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">

                <div class="inner">
                    <h3>{{$estudiantes}}</h3>
                    <p>Total de Estudiantes:</p>
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
                <a href="{{ route('admin.cursos.index') }}" class="small-box-footer"> mas information
                    <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>100 <sup>%</sup> </h3>
                    <p>Porsentaje de asistencia:</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <a href="#" class="small-box-footer"> mas information
                    <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="performance-reports">
        <h2><i class="fas fa-chart-bar"></i> Reportes de Rendimiento Académico</h2>
        <ul>
            <li><i class="fas fa-chart-pie"></i> Aprobación: 75%, Reprobación: 25%</li>
            <li><i class="fas fa-line-chart"></i> Progreso Mensual</li>
        </ul>
    </div>
    <div class="row">


        <section class="col-lg-7 connectedSoportable ui-sortable">
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
            </div>


            <div class="card">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title"> <i class="fas fa-chart-pie mr-1">
                        </i> datos</h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item"><a href="#" class="nav-link active">Area</a></li>
                            <li class="nav-item"><a href="#" class="nav-link">donut</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="revenue-chart-canvas" height="300" style="height:300; display:block; width:431px;" width="431" class="chartjs-render-monitor"></canvas>
                        </div>
                        <div class="chart tab-pane" id="sales-chart" style="position:relative; height: 300px;">
                            <canvas id="sales-chart-canvas" height="0" style="height: 0; display:block; width: 0;" class="chartjs-rebder-monitor" width="0"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="col-lg-5">
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
            </div>
        </section>
    </div>
</div>
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
@stop
@section('js')