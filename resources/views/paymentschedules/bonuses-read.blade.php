@extends('voyager::master')

@section('page_title', 'Ver planilla de aguinaldo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo planilla de aguinaldo
        <a href="{{ route('bonuses.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <button class="btn btn-danger" data-toggle="modal" data-target="#print-modal"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección Administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $bonus->direccion->nombre }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Gestión</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $bonus->year }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo de planilla</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($bonus->procedure_type)
                                        {{ $bonus->procedure_type->name }}
                                    @else
                                        Todas
                                    @endif
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>PLANILLA</th>
                                        <th>NOMBRE COMPLETO</th>
                                        <th>CI</th>
                                        <th>INICIO</th>
                                        <th>FIN</th>
                                        <th>DÍAS<br>TRAB.</th>
                                        <th>MESES</th>
                                        <th>SUELDO<br>PROMEDIO</th>
                                        <th>AGUINALDO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                        $total = 0;
                                    @endphp
                                    @foreach ($bonus->details as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->procedure_type->name }}</td>
                                            <td>
                                                {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                                                @if ($item->contract->cargo)
                                                    <b>{{ $item->contract->cargo->Descripcion }}</b>
                                                @elseif($item->contract->job)
                                                    <b>{{ $item->contract->job->name }}</b>
                                                @endif
                                            </td>
                                            <td>{{ $item->contract->person->ci }}</td>
                                            <td>
                                                @if ($item->start)
                                                    {{ date('d-m-Y', strtotime($item->start)) }}
                                                @endif
                                            </td>
                                            <td>{{ $item->finish ? date('d-m-Y', strtotime($item->finish)) : '31-12-'.$bonus->year }}</td>
                                            <td class="text-right">{{ $item->days }}</td>
                                            <td>
                                                <table class="table">
                                                    <tr>
                                                        <td>{{ number_format($item->partial_salary_1 + $item->seniority_bonus_1, 2, ',', '.') }}</td>
                                                        <td>{{ number_format($item->partial_salary_2 + $item->seniority_bonus_2, 2, ',', '.') }}</td>
                                                        <td>{{ number_format($item->partial_salary_3 + $item->seniority_bonus_3, 2, ',', '.') }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $promedio = ($item->partial_salary_1 + $item->seniority_bonus_1 + $item->partial_salary_2 + $item->seniority_bonus_2 + $item->partial_salary_3 + $item->seniority_bonus_3) /3;
                                                @endphp
                                                {{ number_format($promedio, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">{{ number_format(($promedio / 360) * $item->days, 2, ',', '.') }}</td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if ($bonus->status == 2)
                                                <a title="Imprimir boleta de pago" class="btn btn-sm btn-danger" href="{{ route('bonuses.recipe', ['id' => $bonus->id, 'detail_id' => $item->id]) }}" target="_blank">
                                                    <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                            $total += ($promedio / 360) * $item->days;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="9" class="text-right"><b>TOTAL</b></td>
                                        <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- print modal --}}
    <form id="form-print" action="{{ route('bonuses.print', $bonus->id) }}" method="post" target="_blank">
        @csrf
        <div class="modal modal-danger fade" tabindex="-1" id="print-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="glypicon glypicon-print"></i> Imprimir planilla de aguinaldos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="procedure_type_id">Tipo de planilla</label>
                                <select name="procedure_type_id" class="form-control select2">
                                    <option value="1" @if($bonus->procedure_type_id == 1) selected @elseif(!$bonus->procedure_type_id) @else disabled @endif>Permanente</option>
                                    <option value="5" @if($bonus->procedure_type_id == 5) selected @elseif(!$bonus->procedure_type_id) @else disabled @endif>Eventual</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="program_id">Programa/Proyecto</label>
                                <select name="program_id" id="select-program_id" class="form-control">
                                    <option value="">--Todos--</option>
                                    @foreach ($bonus->details->groupBy('contract.program_id') as $key => $item)
                                    <option value="{{ $key }}">{{ App\Models\Program::find($key)->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="radio-inline"><input type="radio" name="type_render" value="1" checked>Generar PDF</label>
                                <label class="radio-inline"><input type="radio" name="type_render" value="2">HTML</label>
                                {{-- <label class="radio-inline"><input type="radio" name="type_render" value="3">Excel</label> --}}
                            </div>
                            <div class="form-group col-md-12 checkbox">
                                <label><input type="checkbox" name="signature_field" value="1">Imprimir el espacio donde va la firma</label>
                              </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        th{
            text-align: center
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#select-program_id').select2({
                dropdownParent: $('#print-modal')
            })
        });
    </script>
@stop
