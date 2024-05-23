@extends('voyager::master')

@section('page_title', 'Ver Permiso')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-certificate"></i> Ver Permiso
    </h1>
@stop

@php
    $months = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    $days = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
@endphp

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $attendance_permits->type ? $attendance_permits->type->name : 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Categoría</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @switch($attendance_permits->category)
                                        @case(1)
                                            Licencia
                                            @break
                                        @case(2)
                                            Comisión
                                            @break
                                        @case(3)
                                            Permiso especial
                                            @break
                                        @default
                                            
                                    @endswitch
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($attendance_permits->start == $attendance_permits->finish)
                                        {{ $days[date('N', strtotime($attendance_permits->start))].' '.date('d/', strtotime($attendance_permits->start)).$months[intval(date('m', strtotime($attendance_permits->start)))].date('/Y', strtotime($attendance_permits->start)) }}
                                        @if ($attendance_permits->time_start && $attendance_permits->time_finish)
                                            <br>
                                            De {{ date('H:i', strtotime($attendance_permits->time_start)) }} a {{ date('H:i', strtotime($attendance_permits->time_finish)) }}
                                        @endif
                                    @else
                                        {{ $days[date('N', strtotime($attendance_permits->start))].' '.date('d/', strtotime($attendance_permits->start)).$months[intval(date('m', strtotime($attendance_permits->start)))] }} al {{ $days[date('N', strtotime($attendance_permits->finish))].' '.date('d/', strtotime($attendance_permits->finish)).$months[intval(date('m', strtotime($attendance_permits->finish)))] }} de {{ date('Y', strtotime($attendance_permits->start)) }}
                                    @endif
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @php
                                        switch ($attendance_permits->status) {
                                            case 'elaborado':
                                                $label = 'default';
                                                break;
                                            default:
                                                $label = 'default';
                                                break;
                                        }
                                    @endphp
                                    <label class="label label-{{ $label }}">{{ ucfirst($attendance_permits->status) }}</label>
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Funcionarios</h3>
                            </div>
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>N&deg;</th>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Dirección administrativa</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cont = 1;
                                        @endphp
                                        @foreach ($attendance_permits->details as $item)
                                            <tr>
                                                <td>{{ $cont }}</td>
                                                <td>{{ str_pad($item->id, 4, "0", STR_PAD_LEFT) }}</td>
                                                <td>
                                                    {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                                                    <small>
                                                        @if ($item->contract->cargo)
                                                            {{ $item->contract->cargo->Descripcion }}
                                                        @elseif ($item->contract->job)
                                                            {{ $item->contract->job->name }}
                                                        @else
                                                            @if ($item->contract->job_description)
                                                                {{ $item->contract->job_description }}
                                                            @else
                                                                No definido
                                                            @endif
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>{{ $item->contract->direccion_administrativa ? $item->contract->direccion_administrativa->nombre : 'No definida' }}</td>
                                                <td></td>
                                            </tr>
                                            @php
                                                $cont++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>

    </style>
@endsection

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function () {

        });
    </script>
@stop
