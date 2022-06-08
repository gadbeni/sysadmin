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
                    <td colspan="2"><b>Total ganado</b></td>
                    <td colspan="3"><b>Total aportes</b></td>
                    <td colspan="2"><b>N&deg; de personas</b></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        @if ($paymentschedule)
                            {{ $paymentschedule->direccion_administrativa->nombre }}
                        @else
                            {{ count($planilla) > 0 ? $planilla[0]->direccion_administrativa : '' }}
                        @endif
                    </td>
                    <td rowspan="2">
                        @if ($paymentschedule)
                            {{ $paymentschedule->period->name }}
                        @else
                            {{ count($planilla) > 0 ? $planilla[0]->periodo : '' }}
                        @endif
                    </td>
                    <td rowspan="2">
                        @if ($paymentschedule)
                            {{ $paymentschedule->procedure_type->name }}
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
                        @if ($paymentschedule)
                            {{ number_format($paymentschedule->details->where('contract.person.afp', 1)->sum('partial_salary') + $paymentschedule->details->where('contract.person.afp', 1)->sum('seniority_bonus_amount'), 2, ',', '.') }}
                        @else
                            {{ $planilla->where('afp', 1)->first() ? number_format($planilla->where('afp', 1)->first()->total_ganado, 2, ',', '.') : '0.00' }}
                        @endif
                    </td>
                    <td>
                        @if ($paymentschedule)
                            {{ number_format($paymentschedule->details->where('contract.person.afp', 2)->sum('partial_salary') + $paymentschedule->details->where('contract.person.afp', 2)->sum('seniority_bonus_amount'), 2, ',', '.') }}
                        @else
                            {{ $planilla->where('afp', 2)->first() ? number_format($planilla->where('afp', 2)->first()->total_ganado, 2, ',', '.') : '0.00' }}
                        @endif
                    </td>
                    <td>
                        @if ($paymentschedule)
                            {{ number_format($paymentschedule->details->where('contract.person.afp', 1)->sum('common_risk') + $paymentschedule->details->where('contract.person.afp', 1)->sum('solidary_employer') + $paymentschedule->details->where('contract.person.afp', 1)->sum('housing_employer') + $paymentschedule->details->where('contract.person.afp', 1)->sum('labor_total'), 2, ',', '.') }}
                        @else
                            {{-- {{ $planilla->where('afp', 1)->first() ? number_format($planilla->where('afp', 1)->first()->total_ganado, 2, ',', '.') : '0.00' }} --}}
                        @endif
                    </td>
                    <td>
                        @if ($paymentschedule)
                        {{ number_format($paymentschedule->details->where('contract.person.afp', 2)->sum('common_risk') + $paymentschedule->details->where('contract.person.afp', 2)->sum('solidary_employer') + $paymentschedule->details->where('contract.person.afp', 2)->sum('housing_employer') + $paymentschedule->details->where('contract.person.afp', 2)->sum('labor_total'), 2, ',', '.') }}
                        @else
                            {{-- {{ $planilla->where('afp', 2)->first() ? number_format($planilla->where('afp', 2)->first()->total_ganado, 2, ',', '.') : '0.00' }} --}}
                        @endif
                    </td>
                    <td>
                        @if ($paymentschedule)
                            {{ number_format(($paymentschedule->details->sum('partial_salary') + $paymentschedule->details->sum('seniority_bonus_amount')) *0.1, 2, ',', '.') }}
                        @else
                            {{-- {{ $planilla->where('afp', 1)->first() ? number_format($planilla->where('afp', 1)->first()->total_ganado, 2, ',', '.') : '0.00' }} --}}
                        @endif
                    </td>
                    <td>
                        @if ($paymentschedule)
                            {{ $paymentschedule->details->where('contract.person.afp', 1)->count() }}
                        @else
                            {{ $planilla->where('afp', 1)->first() ? $planilla->where('afp', 1)->first()->n_personas : 0 }}
                        @endif
                    </td>
                    <td>
                        @if ($paymentschedule)
                            {{ $paymentschedule->details->where('contract.person.afp', 2)->count() }}
                        @else
                            {{ $planilla->where('afp', 2)->first() ? $planilla->where('afp', 2)->first()->n_personas : 0 }}
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
                @if ($paymentschedule)
                    @forelse ($paymentschedule->check_payments as $check_payment)
                        @if (strpos(strtolower($check_payment->beneficiary->type->name), 'salud') === false && ($check_payment->afp == $afp || !$afp))
                            @php
                                $cont++;
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $check_payment->number }}</td>
                                <td>{{ number_format($check_payment->amount, 2, ',', '.') }}</td>
                                <td>{{ $check_payment->beneficiary->full_name }}</td>
                            </tr>
                        @endif
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
                @if ($paymentschedule)
                    @forelse ($paymentschedule->payroll_payments as $item)
                        @if ($item->afp == $afp || !$afp)
                            @php
                                $cont++;
                            @endphp
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>{{ $item->fpc_number }}</td>
                                    <td>{{ date('d/m/Y', strtotime($item->date_payment_afp)) }}</td>
                                    <td>{{ $item->afp == 1 ? 'AFP Futuro' : 'AFP Previsión' }}</td>
                                </tr>
                        @endif
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
                @if ($paymentschedule)
                    @forelse ($paymentschedule->check_payments as $check_payment)
                        @if (strpos(strtolower($check_payment->beneficiary->type->name), 'salud') !== false)
                            @php
                                $cont++;
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $check_payment->number }}</td>
                                <td>{{ number_format($check_payment->amount, 2, ',', '.') }}</td>
                                <td>{{ $check_payment->beneficiary->full_name }}</td>
                            </tr>
                        @endif
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
                @if ($paymentschedule)
                    @forelse ($paymentschedule->payroll_payments as $item)
                        @if ($item->afp == $afp || !$afp)
                            @php
                                $cont++;
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->deposit_number }}</td>
                                <td>{{ $item->date_payment_cc ? date('d/m/Y', strtotime($item->date_payment_cc)) : '' }}</td>
                                <td>{{ $item->gtc_number }}</td>
                                <td>{{ $item->recipe_number }}</td>
                                <td>{{ $item->afp == 1 ? 'AFP Futuro' : 'AFP Previsión' }}</td>
                            </tr>
                        @endif
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