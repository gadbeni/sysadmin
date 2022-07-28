@extends('layouts.template-print-alt')

@section('page_title', 'Carátula')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    CARÁTULA DE PLANILLA N&deg; {{ count($paymentschedules) > 0 ? str_pad($paymentschedules[0]->id, 6, "0", STR_PAD_LEFT).($paymentschedules[0]->aditional ? '-A' : '') : $planilla_id }}<br>
                    <small>DIRECCIÓN DE BIENESTAR LABORAL Y PREVISIÓN SOCIAL</small> <br>
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
        <tr>
            <tr></tr>
        </tr>
    </table>
    
    <br><br>

    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
        <tr>
            <td><b>Dirección administrativa</b></td>
            <td><b>Periodo</b></td>
            <td><b>Planilla</b></td>
            <td colspan="2"><b>Total ganado</b></td>
            <td colspan="3"><b>Total aportes</b></td>
            <td colspan="2"><b>N&deg; de personas</b></td>
        </tr>
        <tr>
            <td rowspan="2">
                @if (count($paymentschedules) > 0)
                    {{ $paymentschedules[0]->direccion_administrativa->nombre }}
                @else
                    {{ count($planilla) > 0 ? $planilla[0]->direccion_administrativa : '' }}
                @endif
            </td>
            <td rowspan="2">
                @if (count($paymentschedules) > 0)
                    {{ $paymentschedules[0]->period->name }}
                @else
                    {{ count($planilla) > 0 ? $planilla[0]->periodo : '' }}
                @endif
            </td>
            <td rowspan="2">
                @if (count($paymentschedules) > 0)
                    {{ $paymentschedules[0]->procedure_type->name }}
                @else
                    {{ count($planilla) > 0 ? $planilla[0]->tipo_planilla : '' }}
                @endif
            </td>
            <td><b>Futuro</b></td>
            <td><b>Previsión</b></td>
            <td><b>Futuro</b></td>
            <td><b>Previsión</b></td>
            <td><b>Salud</b></td>
            <td><b>Futuro</b></td>
            <td><b>Previsión</b></td>
        </tr>
            <td>
                @if (count($paymentschedules) > 0)
                    @php
                        $total_ganado_futuro = 0;
                        $total_ganado_prevision = 0;
                        $total_aporte_futuro = 0;
                        $total_aporte_prevision = 0;
                        $total_aporte_cc = 0;
                        $total_personas_futuro = 0;
                        $total_personas_prevision = 0;

                        $check_payment_afp = collect();
                        $check_payment_cc = collect();
                        $payroll_payments_afp = collect();
                        $payroll_payments_cc = collect();

                        foreach($paymentschedules as $paymentschedule){
                            $total_ganado_futuro += $paymentschedule->details->where('afp', 1)->sum('partial_salary') + $paymentschedule->details->where('afp', 1)->sum('seniority_bonus_amount');
                            $total_ganado_prevision += $paymentschedule->details->where('afp', 2)->sum('partial_salary') + $paymentschedule->details->where('afp', 2)->sum('seniority_bonus_amount');
                            $total_aporte_futuro += $paymentschedule->details->where('afp', 1)->sum('common_risk') + $paymentschedule->details->where('afp', 1)->sum('solidary_employer') + $paymentschedule->details->where('afp', 1)->sum('housing_employer') + $paymentschedule->details->where('afp', 1)->sum('labor_total');
                            $total_aporte_prevision += $paymentschedule->details->where('afp', 2)->sum('common_risk') + $paymentschedule->details->where('afp', 2)->sum('solidary_employer') + $paymentschedule->details->where('afp', 2)->sum('housing_employer') + $paymentschedule->details->where('afp', 2)->sum('labor_total');
                            $total_aporte_cc += ($paymentschedule->details->sum('partial_salary') + $paymentschedule->details->sum('seniority_bonus_amount')) *0.1;
                            $total_personas_futuro += $paymentschedule->details->where('afp', 1)->count();
                            $total_personas_prevision += $paymentschedule->details->where('afp', 2)->count();

                            // Pagos de cheques afp
                            foreach ($paymentschedule->check_payments as $check_payment){
                                if(strpos(strtolower($check_payment->beneficiary->type->name), 'salud') === false && ($check_payment->afp == $afp || !$afp)){
                                    $check_payment_afp->push([
                                        'number' => $check_payment->number,
                                        'amount' => $check_payment->amount,
                                        'beneficiary' => $check_payment->beneficiary->full_name
                                    ]);
                                }

                                if(strpos(strtolower($check_payment->beneficiary->type->name), 'salud') !== false){
                                    $check_payment_cc->push([
                                        'number' => $check_payment->number,
                                        'amount' => $check_payment->amount,
                                        'beneficiary' => $check_payment->beneficiary->full_name
                                    ]);
                                }
                            }

                            // Formularios FPC
                            foreach($paymentschedule->payroll_payments as $item){
                                if(($item->afp == $afp || !$afp) && $item->fpc_number){
                                    $payroll_payments_afp->push([
                                        'fpc_number' => $item->fpc_number,
                                        'date_payment_afp' => date('d/m/Y', strtotime($item->date_payment_afp)),
                                        'afp' => $item->afp == 1 ? 'AFP Futuro' : 'AFP Previsión'
                                    ]);
                                }

                                if ($item->afp == $afp || !$afp){
                                    $payroll_payments_cc->push([
                                        'deposit_number' => $item->deposit_number,
                                        'date_payment_cc' => $item->date_payment_cc ? date('d/m/Y', strtotime($item->date_payment_cc)) : '',
                                        'gtc_number' => $item->gtc_number,
                                        'recipe_number' => $item->recipe_number,
                                        'afp' => $item->afp == 1 ? 'AFP Futuro' : 'AFP Previsión'
                                    ]);
                                }
                            }
                        }
                    @endphp
                    {{ number_format($total_ganado_futuro, 2, ',', '.') }}
                @else
                    {{ $planilla->where('afp', 1)->first() ? number_format($planilla->where('afp', 1)->first()->total_ganado, 2, ',', '.') : '0.00' }}
                @endif
            </td>
            <td>
                @if (count($paymentschedules) > 0)
                    {{ number_format($total_ganado_prevision, 2, ',', '.') }}
                @else
                    {{ $planilla->where('afp', 2)->first() ? number_format($planilla->where('afp', 2)->first()->total_ganado, 2, ',', '.') : '0.00' }}
                @endif
            </td>
            <td>
                @if (count($paymentschedules) > 0)
                    {{ number_format($total_aporte_futuro, 2, ',', '.') }}
                @else
                    {{ $planilla->where('afp', 1)->first() ? number_format($planilla->where('afp', 1)->first()->total_ganado, 2, ',', '.') : '0.00' }}
                @endif
            </td>
            <td>
                @if (count($paymentschedules) > 0)
                    {{ number_format($total_aporte_prevision, 2, ',', '.') }}
                @else
                    {{ $planilla->where('afp', 2)->first() ? number_format($planilla->where('afp', 2)->first()->total_ganado, 2, ',', '.') : '0.00' }}
                @endif
            </td>
            <td>
                @if (count($paymentschedules) > 0)
                    {{ number_format($total_aporte_cc, 2, ',', '.') }}
                @else
                    {{ $planilla->where('afp', 1)->first() ? number_format($planilla->where('afp', 1)->first()->total_ganado, 2, ',', '.') : '0.00' }}
                @endif
            </td>
            <td>
                @if (count($paymentschedules) > 0)
                    {{ $total_personas_futuro }}
                @else
                    {{ $planilla->where('afp', 1)->first() ? $planilla->where('afp', 1)->first()->n_personas : 0 }}
                @endif
            </td>
            <td>
                @if (count($paymentschedules) > 0)
                    {{ $total_personas_prevision }}
                @else
                    {{ $planilla->where('afp', 2)->first() ? $planilla->where('afp', 2)->first()->n_personas : 0 }}
                @endif
            </td>
        </tr>
    </table>

    <br>
    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
        <tr>
            <td colspan="4"><b>DETALLES DE CHEQUES AFP</b></td>
        </tr>
        <tr>
            <td><b>N&deg;</b></td>
            <td><b>Número</b></td>
            <td><b>Monto Bs.</b></td>
            <td><b>Pertenece</b></td>
        </tr>
        @php
            $cont = 0;
        @endphp
        @if (count($paymentschedules) > 0)
            @forelse ($check_payment_afp->unique() as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item['number'] }}</td>
                    <td>{{ number_format($item['amount'], 2, ',', '.') }}</td>
                    <td>{{ $item['beneficiary'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay resultados</td>
                </tr>
            @endforelse
        @else
            @forelse ($cheques_afp as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->number }}</td>
                    <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                    <td>
                        @php
                            $planilla_haber = $planillahaberes->where('ID', $item->planilla_haber_id)->first()
                        @endphp
                        {{ $planilla_haber->Afp == 1 ? 'AFP Futuro' : 'AFP Previsión' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay resultados</td>
                </tr>
            @endforelse
        @endif
    </table>

    <br>
    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
        <tr>
            <td colspan="4"><b>DETALLES DE FORMULARIOS FPC</b></td>
        </tr>
        <tr>
            <td><b>N&deg;</b></td>
            <td><b>Formulario</b></td>
            <td><b>Fecha de pago</b></td>
            <td><b>Pertenece</b></td>
        </tr>
        @php
            $cont = 0;
        @endphp
        @if (count($paymentschedules) > 0)
            @forelse ($payroll_payments_afp->unique() as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item['fpc_number'] }}</td>
                    <td>{{ $item['date_payment_afp'] }}</td>
                    <td>{{ $item['afp'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay resultados</td>
                </tr>
            @endforelse
        @else
            @forelse ($pagos as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->fpc_number }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->date_payment_afp)) }}</td>
                    <td>
                        @php
                            $planilla_haber = $planillahaberes->where('ID', $item->planilla_haber_id)->first()
                        @endphp
                        {{ $planilla_haber->Afp == 1 ? 'AFP Futuro' : 'AFP Previsión' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay resultados</td>
                </tr>
            @endforelse
        @endif
    </table>

    <br>
    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
        <tr>
            <td colspan="4"><b>DETALLES DE CHEQUES CAJA DE SALUD</b></td>
        </tr>
        <tr>
            <td><b>N&deg;</b></td>
            <td><b>Número</b></td>
            <td><b>Monto Bs.</b></td>
            <td><b>Pertenece</b></td>
        </tr>
        @php
            $cont = 0;
        @endphp
        @if (count($paymentschedules) > 0)
            @forelse ($check_payment_cc->unique() as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item['number'] }}</td>
                    <td>{{ number_format($item['amount'], 2, ',', '.') }}</td>
                    <td>{{ $item['beneficiary'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay resultados</td>
                </tr>
            @endforelse
        @else
            @forelse ($cheques_salud as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->number }}</td>
                    <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                    <td>
                        @php
                            $planilla_haber = $planillahaberes->where('ID', $item->planilla_haber_id)->first()
                        @endphp
                        {{ $planilla_haber->Afp == 1 ? 'AFP Futuro' : 'AFP Previsión' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay resultados</td>
                </tr>
            @endforelse
        @endif
    </table>

    <br>
    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
        <tr>
            <td colspan="6"><b>DETALLES DE PAGOS DE CAJA DE SALUD</b></td>
        </tr>
        <tr>
            <td><b>N&deg;</b></td>
            <td><b>N&deg; de deposito</b></td>
            <td><b>Fecha de pago</b></td>
            <td><b>GTC-11</b></td>
            <td><b>N&deg; de recibo</b></td>
            <td><b>Pertenece</b></td>
        </tr>
        @php
            $cont = 0;
        @endphp
        @if (count($paymentschedules) > 0)
            @forelse ($payroll_payments_cc->unique() as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item['deposit_number'] }}</td>
                    <td>{{ $item['date_payment_cc'] }}</td>
                    <td>{{ $item['gtc_number'] }}</td>
                    <td>{{ $item['recipe_number'] }}</td>
                    <td>{{ $item['afp'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay resultados</td>
                </tr>
            @endforelse
        @else
            @forelse ($pagos as $item)
                @php
                    $cont++;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->deposit_number }}</td>
                    <td>{{ $item->date_payment_cc ? date('d/m/Y', strtotime($item->date_payment_cc)) : '' }}</td>
                    <td>{{ $item->gtc_number }}</td>
                    <td>{{ $item->recipe_number }}</td>
                    <td>
                        @php
                            $planilla_haber = $planillahaberes->where('ID', $item->planilla_haber_id)->first()
                        @endphp
                        {{ $planilla_haber->Afp == 1 ? 'AFP Futuro' : 'AFP Previsión' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay resultados</td>
                </tr>
            @endforelse
        @endif
    </table>

    <br><br>
    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="height: 100px">
        <tr>
            <td valign=bottom><b style="font-size: 11px">RECIBIDO POR:________________________________________</b></td>
            <td valign=bottom><b style="font-size: 11px">FIRMA:________________________________________</b></td>
            <td valign=bottom><b style="font-size: 11px">FECHA:____________/_____________/____________</b></td>
        </tr>
    </table>
@endsection

