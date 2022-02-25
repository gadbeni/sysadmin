@extends('layouts.template-print-alt')

@section('page_title', 'Impresión de planilla - '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT))

@section('content')
    <div class="content">
        <div class="header">
            <table width="100%">
                <tr>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        $year = Str::substr($data->period->name, 0, 4);
                        $month = Str::substr($data->period->name, 4, 2);
                    @endphp
                    <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
                    <td style="text-align: right">
                        <h3 style="margin: 0px">PLANILLA DE PAGO HABERES AL PERSONAL DEPENDIENTE GAD-BENI</h3>
                        <span>CORRESPONDIENTE AL MES DE: {{ strtoupper($months[intval($month)]) }} DE {{ $year }} | AFP - {{ $afp == 1 ? 'FUTURO' : 'BBVA PREVISION' }}</span>
                        <h3 style="margin: 0px">{{ $data->direccion_administrativa->NOMBRE }}</h3>
                        <span>{{ $data->procedure_type->name }}</span>
                    </td>
                    <td style="text-align:center; width: 90px">
                        {!! QrCode::size(80)->generate('Planilla '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).' | '.$data->period->name.' | '.$data->direccion_administrativa->NOMBRE.' | '.$data->procedure_type->name); !!} <br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: center"><b>{{ str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">
                        <small style="font-size: 9px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px">
            <table class="table table-bordered table-hover table-details" border="1" cellpadding="2" cellspacing="0">
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
                        <th rowspan="3">FIRMA</th>
                        <th rowspan="3">ITEM</th>
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
                        $cont = 0;
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
                            $cont++;
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
                            <td>{{ $item->centralize_code ? $item->item : $cont }}</td>
                            <td>{{ $item->job_level }}</td>
                            <td>
                                <b>{{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }}</b> <br>
                                <small>{{ $item->job }}</small>
                            </td>
                            <td><b>{{ $item->contract->person->ci }}</b></td>
                            <td>{{ $item->contract->person->nua_cua }}</td>
                            <td>{{ $item->contract->start }}</td>
                            <td><b>{{ $item->worked_days }}</b></td>
                            <td style="text-align: right">{{ number_format($item->salary, 2, ',', '.') }}</td>
                            <td style="text-align: right"><b>{{ number_format($item->partial_salary, 2, ',', '.') }}</b></td>
                            <td style="text-align: right">{{ number_format($item->seniority_bonus_percentage, 2, ',', '.') }}%</td>
                            <td style="text-align: right">{{ number_format($item->seniority_bonus_amount, 2, ',', '.') }}</td>
                            <td style="text-align: right"><b>{{ number_format($item->partial_salary + $item->seniority_bonus_amount, 2, ',', '.') }}</b></td>
                            <td style="text-align: right">{{ number_format($item->solidary, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->afp_commission, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->retirement, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->solidary_national, 2, ',', '.') }}</td>
                            <td style="text-align: right"><b>{{ number_format($item->labor_total, 2, ',', '.') }}</b></td>
                            <td style="text-align: right">{{ number_format($item->rc_iva_amount, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->faults_quantity, floor($item->faults_quantity) < $item->faults_quantity ? 1 : 0, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->faults_amount, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->labor_total + $item->rc_iva_amount + $item->faults_amount, 2, ',', '.') }}</td>
                            <td style="text-align: right"><b>{{ number_format($item->liquid_payable, 2, ',', '.') }}</b></td>
                            <td style="width: 200px; height: 50px"></td>
                            <td>{{ $item->centralize_code ? $item->item : $cont }}</td>
                        </tr>
                    @empty
                        
                    @endforelse
                    <tr>
                        <td colspan="7" style="text-align: right"><b>TOTAL</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('salary'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_partial_salary, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ $data->details->sum('seniority_bonus_percentage') }}%</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_amount, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('solidary'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('afp_commission'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('retirement'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('solidary_national'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('labor_total'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('rc_iva_amount'), 2, ',', '.') }}</b></td>
                        <td></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('faults_amount'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('labor_total') + $data->details->sum('rc_iva_amount') + $data->details->sum('faults_amount'), 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($data->details->sum('liquid_payable'), 2, ',', '.') }}</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="saltopagina"></div>
        <div class="header-resume">
            <table width="100%">
                <tr>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        $year = Str::substr($data->period->name, 0, 4);
                        $month = Str::substr($data->period->name, 4, 2);
                    @endphp
                    <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
                    <td style="text-align: right">
                        <h3 style="margin: 0px">PLANILLA DE PAGO HABERES AL PERSONAL DEPENDIENTE GAD-BENI</h3>
                        <span>CORRESPONDIENTE AL MES DE: {{ strtoupper($months[intval($month)]) }} DE {{ $year }} | AFP - {{ $afp == 1 ? 'FUTURO' : 'BBVA PREVISION' }}</span>
                        <h3 style="margin: 0px">{{ $data->direccion_administrativa->NOMBRE }}</h3>
                        <span>{{ $data->procedure_type->name }}</span>
                    </td>
                    <td style="text-align:center; width: 90px">
                        {!! QrCode::size(80)->generate('Planilla '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).' | '.$data->period->name.' | '.$data->direccion_administrativa->NOMBRE.' | '.$data->procedure_type->name); !!} <br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: center"><b>{{ str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT) }}</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">
                        <small style="font-size: 9px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 10px">
            <table class="table-resumen" cellpadding="2" cellspacing="0" width="70%">
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
                        <td style="text-align: right"><b><u>{{ number_format($total_amount + $lactation_amount + $total_social_security, 2, ',', '.') }}</u></b></td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>12000 Servicios no Permanentes</td>
                        <td></td>
                        <td style="text-align: right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        @php
                            $total_debe += $total_amount + $lactation_amount;
                        @endphp
                        <td style="text-align: right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>11200 Categorías</td>
                        <td></td>
                        <td style="text-align: right">{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>11600 Asignaciones Familiares</td>
                        <td></td>
                        <td style="text-align: right">{{ number_format($lactation_amount, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>12100 Personal Eventual</td>
                        <td></td>
                        <td style="text-align: right">{{ number_format($total_partial_salary, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>1300 PREVISIÓN SOCIAL</b></td>
                        <td></td>
                        <td style="text-align: right"><b><u>{{ number_format($total_social_security, 2, ',', '.') }}</u></b></td>
                        {{-- ========== --}}
                        @php
                            $total_debe += $total_social_security;
                        @endphp
                        <td style="text-align: right">{{ number_format($total_social_security, 2, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>13110 Aporte Patronal Caja de Salud</td>
                        <td>10%</td>
                        <td style="text-align: right">{{ number_format($total_health, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>13120 Prima de Riesgo Profesión - Regimen de Largo Plazo</td>
                        <td>1.71%</td>
                        <td style="text-align: right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>13131 Aporte Patronal Solidario</td>
                        <td>3%</td>
                        <td style="text-align: right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>13200 Aporte Patronal A.F.P. Vivienda</td>
                        <td>2%</td>
                        <td style="text-align: right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b><u>APORTES PATRONALES - SALUD</u></b></td>
                        <td></td>
                        <td style="text-align: right"><b><u>{{ number_format($total_health, 2, ',', '.') }}</u></b></td>
                        {{-- ========== --}}
                        @php
                            $total_haber += $total_health;
                        @endphp
                        <td></td>
                        <td style="text-align: right">{{ number_format($total_health, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Caja de Salud</td>
                        <td>10%</td>
                        <td style="text-align: right">{{ number_format($total_health, 2, ',', '.') }}</td>
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
                        <td style="text-align: right">{{ number_format($total_afp, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><b><u>PREVISIÓN SOCIAL</u></b></td>
                        <td></td>
                        <td style="text-align: right"><b><u>{{ number_format($total_patronal, 2, ',', '.') }}</u></b></td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Riesgo Profesion a Largo Plazo</td>
                        <td>1.71%</td>
                        <td style="text-align: right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Aporte Patronal Solidario</td>
                        <td>3%</td>
                        <td style="text-align: right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Aporte Patronal Vivienda</td>
                        <td>2%</td>
                        <td style="text-align: right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
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
                        <td style="text-align: right"><b><u>{{ number_format($labor_total, 2, ',', '.') }}</u></b></td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Aporte Solidario</td>
                        <td>0.5%</td>
                        <td style="text-align: right">{{ number_format($total_solidary, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Riesgo Común</td>
                        <td>1.71%</td>
                        <td style="text-align: right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Comisión AFP</td>
                        <td>0.5%</td>
                        <td style="text-align: right">{{ number_format($total_afp_commission, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Aporte Jubilación</td>
                        <td>10%</td>
                        <td style="text-align: right">{{ number_format($total_retirement, 2, ',', '.') }}</td>
                        {{-- ========== --}}
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Aporte Nacional Solidario</td>
                        <td>1%</td>
                        <td style="text-align: right">{{ number_format($total_solidary_national, 2, ',', '.') }}</td>
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
                        <td style="text-align: right">{{ number_format($labor_rc_iva_amount, 2, ',', '.') }}</td>
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
                    <td style="text-align: right">{{ number_format($labor_faults_amount, 2, ',', '.') }}</td>
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
                        <td style="text-align: right">{{ number_format($labor_liquid_payable, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>TOTAL</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_debe, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_haber, 2, ',', '.') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .content {
            padding-left: 30px;
            padding-right: 30px;
            font-size: 13px;
        }
        .header{
            width: 100%;
        }
        .header-resume{
            display: none;
        }
        .table-details th{
            font-size: 7px !important
        }
        .table-details td{
            font-size: 10px !important
        }
        .table-details tfoot td{
            font-size: 11px !important
        }
        .table-resumen{
            font-size: 11px !important;
            margin-top: 100px;
            margin-bottom: 100px;
        }
        .saltopagina{
            display: none;
        }
        @page {
            size: landscape;
            margin: 10mm 0mm 30mm 0mm;
        }
        
        @media print{
            .header{
                top: 0px;
            }
            .header-resume{
                display: block;
            }
            .table-details th{
                font-size: 6px !important
            }
            .table-details td{
                font-size: 9px !important
            }
            .table-details tfoot td{
                font-size: 10px !important
            }
            .table-resumen{
                margin-top: 0px;
                margin-bottom: 0px;
            }
            .saltopagina{
                display: block;
                page-break-before: always;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection