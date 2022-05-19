@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de Contratos')

@section('content')
    <div class="content">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: 0px; margin-top: 5px">
                        REPORTE DE CONTRATOS <br>
                        <small>Periodo {{ $period->name }}</small> <br>
                        @php
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        {{-- <small>RECURSOS HUMANOS</small> <br> --}}
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </h2>
                </td>
            </tr>
            <tr>
                <tr></tr>
            </tr>
        </table>
        <br><br>
        @if ($grouped)
            <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th>N&deg;</th>
                        <th>Dirección administrativa</th>
                        <th style="text-align: right">Nro personas</th>
                        <th style="text-align: right">Total días trabajados</th>
                        <th style="text-align: right">Total bono antigüedad</th>
                        <th style="text-align: right">Total ganado</th>
                        <th style="text-align: right">Riesgo común<br>(1.71%)</th>
                        <th style="text-align: right">Vivienda<br>(2%)</th>
                        <th style="text-align: right">Aporte patronal<br>(3%)</th>
                        <th style="text-align: right">Salud<br>(10%)</th>
                        <th style="text-align: right">Líquido pagable</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                    @endphp
                    @foreach ($payments->groupBy('paymentschedule.direccion_administrativa_id') as $item)
                        @php
                            $total_amount = $item->sum('partial_salary') + $item->sum('seniority_bonus_amount');
                        @endphp
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ count($item) > 0 ? $item[0]->paymentschedule->direccion_administrativa->nombre : '' }}</td>
                            <td style="text-align: right">{{ $item->count() }}</td>
                            <td style="text-align: right">{{ number_format($item->sum('partial_salary'), 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->sum('seniority_bonus_amount'), 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($total_amount, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->sum('common_risk'), 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->sum('housing_employer'), 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->sum('solidary_employer'), 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($total_amount *0.1, 2, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($item->sum('liquid_payable'), 2, ',', '.') }}</td>
                        </tr>
                        @php
                            $cont++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        @else
            <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th>N&deg;</th>
                        <th>Código</th>
                        <th>Dirección administrativa</th>
                        <th>Unidad administrativa</th>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Nombre completo</th>
                        <th>CI</th>
                        <th>Cargo</th>
                        <th>Nivel</th>
                        <th>Sueldo</th>
                        {{-- <th>Inicio</th>
                        <th>Fin</th>
                        <th>Programa</th>
                        <th>Cat. prog.</th> --}}
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $salary_total = 0;
                        $payment_total = 0;
                    @endphp
                    @forelse ($payments as $item)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ str_pad($item->paymentschedule->id, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ $item->contract->direccion_administrativa ? $item->contract->direccion_administrativa->nombre : 'No definida' }}</td>
                        <td>{{ $item->contract->unidad_administrativa ? $item->contract->unidad_administrativa->nombre : '' }}</td>
                        <td>{{ $item->contract->code }}</td>
                        <td>{{ $item->contract->type->name }}</td>
                        <td>{{ $item->contract->person->last_name }} {{ $item->contract->person->first_name }}</td>
                        <td>{{ $item->contract->person->ci }}</td>
                        <td>
                            @if ($item->contract->cargo)
                                {{ $item->contract->cargo->Descripcion }}
                            @elseif ($item->contract->job)
                                {{ $item->contract->job->name }}
                            @else
                                No definio
                            @endif
                        </td>
                        <td>
                            @if ($item->contract->cargo)
                                {{ number_format($item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.') }}
                                @php
                                    $salary_total += $item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->Sueldo;
                                @endphp
                            @elseif ($item->contract->job)
                                {{ number_format($item->contract->job->salary, 2, ',', '.') }}
                                @php
                                    $salary_total += $item->contract->job->salary;
                                @endphp
                            @else
                                0.00
                            @endif
                        </td>
                        <td>
                            @if ($item->contract->cargo)
                                {{ number_format($item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.') }}
                            @elseif ($item->contract->job)
                                {{ number_format($item->contract->job->salary, 2, ',', '.') }}
                            @else
                                0.00
                            @endif
                        </td>
                        {{-- <td>{{ date('d/m/Y', strtotime($item->contract->start)) }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->contract->finish)) }}</td>
                        <td>{{ $item->contract->program->name }}</td>
                        <td>{{ $item->contract->program->programatic_category }}</td>
                        <td>{{ $item->contract->status }}</td> --}}
                        <td>
                            @if ($item->payment)
                                <label class="label label-success">Pagada</label> <br> {{ date('d/m/Y', strtotime($item->payment->created_at)) }}
                            @endif
                        </td>
                    </tr>
                    @php
                        $cont++;
                        $payment_total += $item->payment ? $item->payment->amount : 0;
                    @endphp
                    @empty
                        <tr class="odd">
                            <td valign="top" colspan="12" class="text-center">No hay datos disponibles en la tabla</td>
                        </tr>
                    @endforelse

                    <tr>
                        <td colspan="10" class="text-right">Total</td>
                        <td>{{ number_format($salary_total, 2, ',', '.') }}</td>
                        <td>{{ number_format($payment_total, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
@endsection

@section('css')
<style>
    .content {
            padding-left: 30px;
            padding-right: 30px;
        }
    th{
        font-size: 7px !important
    }
    td{
        font-size: 8px !important
    }
    table, th, td {
        border-collapse: collapse;
    }
    @media print{
            @page {
                size: landscape;
            }
            .content {
                margin-left: 25px;
                margin-right: -20px;
            }
        }
</style>
@endsection