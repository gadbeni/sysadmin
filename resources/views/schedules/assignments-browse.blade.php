@extends('voyager::master')

@section('page_title', 'Asignación de Horarios')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-calendar"></i> Asignación de Horarios &nbsp;
        <a href="{{ route('voyager.schedules.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <a href="{{ route('schedules.assignments.create', $schedule->id) }}" class="btn btn-success">
            <span class="voyager-plus"></span>&nbsp;
            Agregar
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Nombre</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $schedule->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $schedule->description ?? 'Sin descripción' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        {{-- <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Detalle</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                @php
                                    $days = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                @endphp
                                <table class="table">
                                    @foreach ($schedule->details->groupBy('day') as $key => $item)
                                        <tr>
                                            <td>{{ $days[$key] }}</td>
                                            <td>{{ date('H:i', strtotime($item[0]->entry)) }} a {{ date('H:i', strtotime($item[0]->exit)) }} {{ count($item) > 1 ? ' - '.date('H:i', strtotime($item[1]->entry)).' a '.date('H:i', strtotime($item[1]->exit)) : '' }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <hr style="margin:0;">
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Funcionario</th>
                                        <th>Inicio de contrato</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ([] as $item)
                                        
                                    @empty
                                        <tr>
                                            <td colspan="4">No hay funcionario asignados</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            
        });
    </script>
@stop
