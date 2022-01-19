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
                    <td colspan="2"><b>Total ganado Bs.</b></td>
                    <td colspan="2"><b>N&deg; de personas</b></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        {{ count($planilla) > 0 ? $planilla[0]->direccion_administrativa : '' }}
                    </td>
                    <td rowspan="2">
                        {{ count($planilla) > 0 ? $planilla[0]->periodo : '' }}
                    </td>
                    <td rowspan="2">
                        {{ count($planilla) > 0 ? $planilla[0]->tipo_planilla : '' }}
                    </td>
                    <td><b>Futuro</b></td>
                    <td><b>Previsión</b></td>
                    <td><b>Futuro</b></td>
                    <td><b>Previsión</b></td>
                </tr>
                    <td>{{ $planilla->where('afp', 1)->first() ? number_format($planilla->where('afp', 1)->first()->total_ganado, 2, ',', '.') : '0.00' }}</td>
                    <td>{{ $planilla->where('afp', 2)->first() ? number_format($planilla->where('afp', 2)->first()->total_ganado, 2, ',', '.') : '0.00' }}</td>
                    <td>{{ $planilla->where('afp', 1)->first() ? $planilla->where('afp', 1)->first()->n_personas : 0 }}</td>
                    <td>{{ $planilla->where('afp', 2)->first() ? $planilla->where('afp', 2)->first()->n_personas : 0 }}</td>
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
            </table>

            <br>
            <table class="table table-bordered" style="text-align: center">
                <tr>
                    <td colspan="4"><b>DETALLES DE FROMULARIOS FPC</b></td>
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
                    <td><b>N&deg: de recibo</b></td>
                    <td><b>Pertenece</b></td>
                </tr>
                @php
                    $cont = 0;
                @endphp
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