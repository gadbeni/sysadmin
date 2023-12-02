@extends('voyager::master')

@section('page_title', 'Asignación de Horarios')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-calendar"></i> Asignación de Horarios &nbsp;
        <a href="{{ route('voyager.schedules.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <a href="{{ route('schedules.assignments.create', $schedule->id) }}" class="btn btn-success" style="margin-left: -15px; top: 0px">
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
                                        <th>Periodo de contrato</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($schedule->contracts_schedules as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>
                                                {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                                                <label class="label label-default">
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
                                                </label>
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($item->contract->start)) }} - {{ $item->contract->finish ? date('d/m/Y', strtotime($item->contract->finish)) : 'No definido' }}</td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if (auth()->user()->hasPermission('delete_schedules-assignments'))
                                                    <a href="#" onclick="deleteItem('{{ route('schedules.assignments.delete', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Anular" class="btn btn-sm btn-danger delete">
                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
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
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({language});
        });
    </script>
@stop
