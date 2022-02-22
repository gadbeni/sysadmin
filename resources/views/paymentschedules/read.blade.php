@extends('voyager::master')

@section('page_title', 'Ver Palnilla')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-logbook"></i> Planilla
        <a href="#" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp; Volver a la lista
        </a>
        <a href="#" class="btn btn-danger">
            <span class="glyphicon glyphicon-print"></span>&nbsp; Imprimir
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="3">ITEM</th>
                                        <th rowspan="3">NIVEL</th>
                                        <th rowspan="3">APELLIDOS Y NOMBRES / CARGO</th>
                                        <th rowspan="3">CI</th>
                                        <th rowspan="3">N&deg; NUA/CUA</th>
                                        <th rowspan="3">FECHA INGRESO</th>
                                        <th rowspan="3">DÍAS TRAB.</th>
                                        <th style="text-align: right" rowspan="3">SUELDO MENSUAL</th>
                                        <th style="text-align: right" rowspan="3">SUELDO PARCIAL</th>
                                        <th rowspan="3">%</th>
                                        <th style="text-align: right" rowspan="3">BONO ANTIG.</th>
                                        <th style="text-align: right" rowspan="3">TOTAL GANADO</th>
                                        <th style="text-align: center" colspan="5">APORTES LABORALES</th>
                                        <th rowspan="3">TOTAL APORTES AFP</th>
                                        <th rowspan="3">RC-IVA</th>
                                        <th colspan="2">FONDO SOCIAL</th>
                                        <th rowspan="3">TOTAL DESC.</th>
                                        <th rowspan="3">LÍQUIDO PAGABLE</th>
                                    </tr>
                                    <tr>
                                        <th>APORTE SOLIDARIO</th>
                                        <th>RIESGO COMÚN</th>
                                        <th>COMISIÓN AFP</th>
                                        <th>APORTE JUBILACIÓN</th>
                                        <th>APORTE NACIONAL SOLIDARIO</th>
                                        <th rowspan="2">DÍAS</th>
                                        <th rowspan="2">MULTAS</th>
                                    </tr>
                                    <tr>
                                        <th>0.5%</th>
                                        <th>1.71%</th>
                                        <th>0.5%</th>
                                        <th>10%</th>
                                        <th>1%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($data->details as $item)
                                        @php

                                        @endphp
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->job_level }}</td>
                                            <td>
                                                <b>{{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }}</b> <br>
                                                <small>{{ $item->job }}</small>
                                            </td>
                                            <td><b>{{ $item->contract->person->ci }}</b></td>
                                            <td>{{ $item->contract->person->nua_cua }}</td>
                                            <td>{{ $item->contract->start }}</td>
                                            <td><b>{{ $item->worked_days }}</b></td>
                                            <td class="text-right">{{ number_format($item->salary, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($item->partial_salary, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ $item->seniority_bonus_percentage }}%</td>
                                            <td class="text-right">{{ number_format($item->seniority_bonus_amount, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($item->partial_salary + $item->seniority_bonus_amount, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ number_format($item->solidary, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->afp_commission, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->retirement, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->solidary_national, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($item->labor_total, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ number_format($item->rc_iva_amount, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->faults_quantity, floor($item->faults_quantity) < $item->faults_quantity ? 1 : 0, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->faults_amount, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->labor_total + $item->rc_iva_amount + $item->faults_amount, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($item->liquid_payable, 2, ',', '.') }}</b></td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-right"><b>TOTAL</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('salary'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('partial_salary'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ $data->details->sum('seniority_bonus_percentage') }}%</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('seniority_bonus_amount'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('partial_salary') + $data->details->sum('seniority_bonus_amount'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('solidary'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('common_risk'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('afp_commission'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('retirement'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('solidary_national'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('labor_total'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('rc_iva_amount'), 2, ',', '.') }}</b></td>
                                        <td></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('faults_amount'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('labor_total') + $data->details->sum('rc_iva_amount') + $data->details->sum('faults_amount'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('liquid_payable'), 2, ',', '.') }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        th{
            font-size: 7px !important
        }
        td{
            font-size: 10px !important
        }
        tfoot td{
            font-size: 11px !important
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            
        });
    </script>
@stop
