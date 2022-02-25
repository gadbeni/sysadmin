@extends('voyager::master')

@section('page_title', 'Ver Planilla')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-logbook"></i> Planilla - {{ str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT) }}
        <a href="{{ route('paymentschedules.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp; Volver a la lista
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
              AFP's <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="?{{ $centralize ? '&centralize=true' : '' }}">Todas</a></li>
                <li><a href="?afp=1{{ $centralize ? '&centralize=true' : '' }}">Futuro</a></li>
                <li><a href="?afp=2{{ $centralize ? '&centralize=true' : '' }}">Previsión</a></li>
            </ul>
        </div>

        {{-- Si se eligió una afp se mostrará un solo botón --}}
        @if ($afp)
            <a href="?afp={{ $afp }}{{ $centralize ? '&centralize=true' : '' }}&print=true" class="btn btn-danger" target="_blank"><i class="glyphicon glyphicon-print"></i> Imprimir</a>
        @else
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-print"></span>&nbsp; Imprimir <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="?afp=1{{ $centralize ? '&centralize=true' : '' }}&print=true" target="_blank">Futuro</a></li>
                    <li><a href="?afp=2{{ $centralize ? '&centralize=true' : '' }}&print=true" target="_blank">Previsión</a></li>
                </ul>
            </div>
        @endif
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
                                <h3 class="panel-title">Dirección administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->direccion_administrativa->NOMBRE }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Periodo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->period->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo de planilla</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->procedure_type->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cantidad de personas</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->details->count() }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if (!$afp)
                                        Todas
                                    @elseif($afp == 1)
                                        Futuro
                                    @elseif($afp == 2)
                                        Previsión
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
                                        switch ($data->status) {
                                            case 'anulada':
                                                $label = 'danger';
                                                break;
                                            case 'borrador':
                                                $label = 'default';
                                                break;
                                            case 'procesada':
                                                $label = 'info';
                                                break;
                                            case 'enviada':
                                                $label = 'primary';
                                                break;
                                            case 'habilitada':
                                                $label = 'success';
                                                break;
                                            case 'pagada':
                                                $label = 'dark';
                                                break;
                                            default:
                                                $label = 'default';
                                                break;
                                        }
                                    @endphp
                                    <label class="label label-{{ $label }}">{{ ucfirst($data->status) }}</label>
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
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-details">
                                <thead>
                                    <tr>
                                        <th rowspan="3">ITEM</th>
                                        <th rowspan="3">NIVEL</th>
                                        <th rowspan="3">APELLIDOS Y NOMBRES / CARGO</th>
                                        <th rowspan="3">CI</th>
                                        <th rowspan="3">N&deg; NUA/CUA</th>
                                        <th rowspan="3">FECHA INGRESO</th>
                                        <th rowspan="3">DÍAS TRAB.</th>
                                        <th rowspan="3">SUELDO MENSUAL</th>
                                        <th rowspan="3">SUELDO PARCIAL</th>
                                        <th rowspan="3">%</th>
                                        <th rowspan="3">BONO ANTIG.</th>
                                        <th rowspan="3">TOTAL GANADO</th>
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
                                        $total_partial_salary = 0;
                                        $total_seniority_bonus_amount = 0;
                                        $total_amount = 0;
                                        $total_health = 0;
                                        $total_common_risk = 0;
                                        $total_solidary_employer = 0;
                                        $total_housing_employer = 0;
                                        $total_solidary = 0;
                                        $total_afp_commission = 0;
                                        $total_retirement = 0;
                                        $total_solidary_national = 0;
                                        $labor_total = 0;
                                        $labor_rc_iva_amount = 0;
                                        $labor_faults_amount = 0;
                                        $labor_liquid_payable = 0;
                                    @endphp
                                    @forelse ($data->details as $item)
                                        @php
                                            $total_partial_salary += $item->partial_salary;
                                            $total_seniority_bonus_amount += $item->seniority_bonus_amount;
                                            $total_amount += $item->partial_salary + $item->seniority_bonus_amount;
                                            $total_health += $item->health;
                                            $total_common_risk += $item->common_risk;
                                            $total_solidary_employer += $item->solidary_employer;
                                            $total_housing_employer += $item->housing_employer;
                                            $total_solidary += $item->solidary;
                                            $total_afp_commission += $item->afp_commission;
                                            $total_retirement += $item->retirement;
                                            $total_solidary_national += $item->solidary_national;
                                            $labor_total += $item->labor_total;
                                            $labor_rc_iva_amount += $item->rc_iva_amount;
                                            $labor_faults_amount += $item->faults_amount;
                                            $labor_liquid_payable += $item->liquid_payable;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->item ?? $cont }}</td>
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
                                    @php
                                        
                                    @endphp
                                    <tr>
                                        <td colspan="7" class="text-right"><b>TOTAL</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('salary'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_partial_salary, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ $data->details->sum('seniority_bonus_percentage') }}%</b></td>
                                        <td class="text-right"><b>{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_amount, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('solidary'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>
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

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="5" class="text-center">RESUMEN</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-center">Descripción</th>
                                        <th class="text-center">Debe</th>
                                        <th class="text-center">Haber</th>
                                    </tr>
                                </thead>
                                @php
                                    $lactation_amount = 0;
                                    $total_social_security = $total_health + $total_common_risk + $total_solidary_employer + $total_housing_employer;
                                    $total_debe = 0;
                                    $total_haber = 0;
                                @endphp
                                <tbody>
                                    <tr>
                                        <td><b>12000 SERVICIOS NO PERMANENTES</b></td>
                                        <td></td>
                                        <td class="text-right"><b><u>{{ number_format($total_amount + $lactation_amount + $total_social_security, 2, ',', '.') }}</u></b></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>12000 Servicios no Permanentes</td>
                                        <td></td>
                                        <td class="text-right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        @php
                                            $total_debe += $total_amount + $lactation_amount;
                                        @endphp
                                        <td class="text-right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>11200 Categorías</td>
                                        <td></td>
                                        <td class="text-right">{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>11600 Asignaciones Familiares</td>
                                        <td></td>
                                        <td class="text-right">{{ number_format($lactation_amount, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>12100 Personal Eventual</td>
                                        <td></td>
                                        <td class="text-right">{{ number_format($total_partial_salary, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>1300 PREVISIÓN SOCIAL</b></td>
                                        <td></td>
                                        <td class="text-right"><b><u>{{ number_format($total_social_security, 2, ',', '.') }}</u></b></td>
                                        {{-- ========== --}}
                                        @php
                                            $total_debe += $total_social_security;
                                        @endphp
                                        <td class="text-right">{{ number_format($total_social_security, 2, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>13110 Aporte Patronal Caja de Salud</td>
                                        <td>10%</td>
                                        <td class="text-right">{{ number_format($total_health, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>13120 Prima de Riesgo Profesión - Regimen de Largo Plazo</td>
                                        <td>1.71%</td>
                                        <td class="text-right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>13131 Aporte Patronal Solidario</td>
                                        <td>3%</td>
                                        <td class="text-right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>13200 Aporte Patronal A.F.P. Vivienda</td>
                                        <td>2%</td>
                                        <td class="text-right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b><u>APORTES PATRONALES - SALUD</u></b></td>
                                        <td></td>
                                        <td class="text-right"><b><u>{{ number_format($total_health, 2, ',', '.') }}</u></b></td>
                                        {{-- ========== --}}
                                        @php
                                            $total_haber += $total_health;
                                        @endphp
                                        <td></td>
                                        <td class="text-right">{{ number_format($total_health, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Caja de Salud</td>
                                        <td>10%</td>
                                        <td class="text-right">{{ number_format($total_health, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    @php
                                        $total_patronal = $total_common_risk + $total_solidary_employer + $total_housing_employer;
                                        $total_afp = $labor_total + $total_patronal;

                                        $total_haber += $total_afp;
                                    @endphp
                                    <tr>
                                        <td><b><u>APORTES PATRONALES AFP</u></b></td>
                                        <td></td>
                                        <td></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td class="text-right">{{ number_format($total_afp, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><b><u>PREVISIÓN SOCIAL</u></b></td>
                                        <td></td>
                                        <td class="text-right"><b><u>{{ number_format($total_patronal, 2, ',', '.') }}</u></b></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Riesgo Profesion a Largo Plazo</td>
                                        <td>1.71%</td>
                                        <td class="text-right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Aporte Patronal Solidario</td>
                                        <td>3%</td>
                                        <td class="text-right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Aporte Patronal Vivienda</td>
                                        <td>2%</td>
                                        <td class="text-right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b><u>APORTES LABORAL AFP</u></b></td>
                                        <td></td>
                                        <td></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b><u>PREVISIÓN SOCIAL</u></b></td>
                                        <td></td>
                                        <td class="text-right"><b><u>{{ number_format($labor_total, 2, ',', '.') }}</u></b></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Aporte Solidario</td>
                                        <td>0.5%</td>
                                        <td class="text-right">{{ number_format($total_solidary, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Riesgo Común</td>
                                        <td>1.71%</td>
                                        <td class="text-right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Comisión AFP</td>
                                        <td>0.5%</td>
                                        <td class="text-right">{{ number_format($total_afp_commission, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Aporte Jubilación</td>
                                        <td>10%</td>
                                        <td class="text-right">{{ number_format($total_retirement, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Aporte Nacional Solidario</td>
                                        <td>1%</td>
                                        <td class="text-right">{{ number_format($total_solidary_national, 2, ',', '.') }}</td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><b>OTROS DESCUENTOS LABORALES</b></td>
                                        <td></td>
                                        <td></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>RC-IVA</td>
                                        <td></td>
                                        <td></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        @php
                                            $total_haber += $labor_rc_iva_amount;
                                        @endphp
                                        <td class="text-right">{{ number_format($labor_rc_iva_amount, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Descuento de no Ley</td>
                                        <td></td>
                                        <td></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Multas y Sanciones</td>
                                        <td></td>
                                        <td></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        @php
                                        $total_haber += $labor_faults_amount;
                                    @endphp
                                    <td class="text-right">{{ number_format($labor_faults_amount, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Líquido Pagable</td>
                                        <td></td>
                                        <td></td>
                                        {{-- ========== --}}
                                        <td></td>
                                        @php
                                            $total_haber += $labor_liquid_payable;
                                        @endphp
                                        <td class="text-right">{{ number_format($labor_liquid_payable, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><b>TOTAL</b></td>
                                        <td class="text-right"><b>{{ number_format($total_debe, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_haber, 2, ',', '.') }}</b></td>
                                    </tr>
                                </tbody>
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
        .table-details th{
            font-size: 7px !important
        }
        .table-details td{
            font-size: 10px !important
        }
        .table-details tfoot td{
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
