<div class="col-md-12 text-right">
    <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <table class="table table-bordered" style="text-align: center">
                <tr>
                    <td><b>Dirección administrativa</b></td>
                    <td><b>Periodo</b></td>
                    <td><b>Planilla</b></td>
                    <td colspan="3"><b>Total ganado</b></td>
                    <td colspan="4"><b>Total aportes</b></td>
                    <td colspan="3"><b>N&deg; de personas</b></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        @if (count($paymentschedules) > 0)
                            @if (str_contains(strtolower($planilla_id), '-c'))
                                Administración Central
                            @else
                                {{ $paymentschedules[0]->direccion_administrativa->nombre }}
                            @endif
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
                    <td><b>Gestora</b></td>
                    <td><b>Futuro</b></td>
                    <td><b>Previsión</b></td>
                    <td><b>Gestora</b></td>
                    <td><b>Salud</b></td>
                    <td><b>Futuro</b></td>
                    <td><b>Previsión</b></td>
                    <td><b>Gestora</b></td>
                </tr>
                    <td>
                        @if (count($paymentschedules) > 0)
                            @php
                                $total_ganado_futuro = 0;
                                $total_ganado_prevision = 0;
                                $total_ganado_gestora = 0;
                                $total_aporte_futuro = 0;
                                $total_aporte_prevision = 0;
                                $total_aporte_gestora = 0;
                                $total_aporte_cc = 0;
                                $total_personas_futuro = 0;
                                $total_personas_prevision = 0;
                                $total_personas_gestora = 0;

                                $check_payment_afp = collect();
                                $check_payment_cc = collect();
                                $payroll_payments_afp = collect();
                                $payroll_payments_cc = collect();

                                foreach($paymentschedules as $paymentschedule){
                                    $total_ganado_futuro += $paymentschedule->details->where('afp', 1)->sum('partial_salary') + $paymentschedule->details->where('afp', 1)->sum('seniority_bonus_amount');
                                    $total_ganado_prevision += $paymentschedule->details->where('afp', 2)->sum('partial_salary') + $paymentschedule->details->where('afp', 2)->sum('seniority_bonus_amount');
                                    $total_ganado_gestora += $paymentschedule->details->where('afp', 3)->sum('partial_salary') + $paymentschedule->details->where('afp', 3)->sum('seniority_bonus_amount');
                                    $total_aporte_futuro += $paymentschedule->details->where('afp', 1)->sum('common_risk') + $paymentschedule->details->where('afp', 1)->sum('solidary_employer') + $paymentschedule->details->where('afp', 1)->sum('housing_employer') + $paymentschedule->details->where('afp', 1)->sum('labor_total');
                                    $total_aporte_prevision += $paymentschedule->details->where('afp', 2)->sum('common_risk') + $paymentschedule->details->where('afp', 2)->sum('solidary_employer') + $paymentschedule->details->where('afp', 2)->sum('housing_employer') + $paymentschedule->details->where('afp', 2)->sum('labor_total');
                                    $total_aporte_gestora += $paymentschedule->details->where('afp', 3)->sum('common_risk') + $paymentschedule->details->where('afp', 3)->sum('solidary_employer') + $paymentschedule->details->where('afp', 3)->sum('housing_employer') + $paymentschedule->details->where('afp', 3)->sum('labor_total');
                                    $total_aporte_cc += ($paymentschedule->details->sum('partial_salary') + $paymentschedule->details->sum('seniority_bonus_amount')) *0.1;
                                    $total_personas_futuro += $paymentschedule->details->where('afp', 1)->count();
                                    $total_personas_prevision += $paymentschedule->details->where('afp', 2)->count();
                                    $total_personas_gestora += $paymentschedule->details->where('afp', 3)->count();

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
                                                'afp' => 'AFP '.$item->afp_details->name
                                            ]);
                                        }

                                        if ($item->afp == $afp || !$afp){
                                            $payroll_payments_cc->push([
                                                'deposit_number' => $item->deposit_number,
                                                'date_payment_cc' => $item->date_payment_cc ? date('d/m/Y', strtotime($item->date_payment_cc)) : '',
                                                'gtc_number' => $item->gtc_number,
                                                'recipe_number' => $item->recipe_number,
                                                'afp' => 'AFP '.$item->afp_details->name
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
                            {{ number_format($total_ganado_gestora, 2, ',', '.') }}
                        @else
                            0.00
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
                            {{ number_format($total_aporte_gestora, 2, ',', '.') }}
                        @else
                            0.00
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
                    <td>
                        @if (count($paymentschedules) > 0)
                            {{ $total_personas_gestora }}
                        @else
                            0.00
                        @endif
                    </td>
                </tr>
            </table>

            <br>
            <table class="table table-bordered" style="text-align: center">
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
                    @php
                        $check_payment_afp = $check_payment_afp->groupBy('number');
                    @endphp
                    @forelse ($check_payment_afp as $check_payment)
                        @foreach ($check_payment->groupBy('beneficiary') as $item)
                            @php
                                $cont++;
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item[0]['number'] }}</td>
                                <td>{{ number_format($item->sum('amount'), 2, ',', '.') }}</td>
                                <td>{{ $item[0]['beneficiary'] }}</td>
                            </tr>
                        @endforeach
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
            <table class="table table-bordered" style="text-align: center">
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
            <table class="table table-bordered" style="text-align: center">
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
                    @php
                        $check_payment_cc = $check_payment_cc->groupBy('number');
                    @endphp
                    @forelse ($check_payment_cc as $check_payment)
                        @foreach ($check_payment->groupBy('beneficiary') as $item)
                            @php
                                $cont++;
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item[0]['number'] }}</td>
                                <td>{{ number_format($item->sum('amount'), 2, ',', '.') }}</td>
                                <td>{{ $item[0]['beneficiary'] }}</td>
                            </tr>
                        @endforeach
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
            <table class="table table-bordered" style="text-align: center">
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
        </div>
    </div>
</div>

<style>
    td b{
        font-size: 11px;
        font-weight: bold !important
    }
</style>