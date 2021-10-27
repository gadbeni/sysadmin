
<div class="col-md-12 text-right">
    @if (count($da))
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <tbody>
                        @foreach ($da as $item)
                            <tr>
                                <th style="font-size: 15px">{{ $item->NOMBRE }}</th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="table" style="margin-bottom: 0px">
                                        <thead>
                                            <tr>
                                                <th>Periodo</th>
                                                <th>Planilla</th>
                                                <th>Tipo de planilla</th>
                                                <th>AFP</th>
                                                <th style="text-align: right">N&deg; de personas</th>
                                                <th style="text-align: right">Total ganado</th>
                                                <th style="text-align: right">N&deg; de cheque</th>
                                                <th style="text-align: right">Monto de cheque</th>
                                                <th style="text-align: right">Fecha de pago AFP</th>
                                                <th style="text-align: right">N&deg; FPC</th>
                                                <th style="text-align: right">Multa AFP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($item->planillas as $planilla)
                                                {{-- {{ dd($planilla) }} --}}
                                                <tr>
                                                    <td>{{ $planilla->Periodo }}</td>
                                                    <td>{{ $planilla->idPlanillaprocesada }}</td>
                                                    <td>{{ $planilla->tipo_planilla }}</td>
                                                    <td>{{ $planilla->afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                                    <td style="text-align: right">{{ $planilla->personas }}</td>
                                                    <td style="text-align: right">{{ number_format($planilla->total_ganado, 2, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none">
                                                            @foreach ($planilla->detalle_cheque_afp as $cheque)
                                                            <li>{{ $cheque->number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none; display: {{ count($planilla->detalle_cheque_afp) ? 'block' : 'none' }}">
                                                            @php
                                                                $total = 0;
                                                            @endphp
                                                            @foreach ($planilla->detalle_cheque_afp as $cheque)
                                                            <li>Bs. {{ number_format($cheque->amount, 2, ',', '.') }}</li>
                                                            @php
                                                                $total += $cheque->amount;
                                                            @endphp
                                                            @endforeach
                                                            <li>
                                                                <hr style="margin: 5px 0px">
                                                                <b>Bs. {{ number_format($total, 2, ',', '.') }}</b>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>{{ date('d/m/Y', strtotime($pago->date_payment_afp)) }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>{{ $pago->fpc_number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>Bs. {{ number_format($pago->penalty_payment, 2, ',', '.') }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @empty
                                            <tr>
                                                <td colspan="11"><h3 class="text-center">No hay resultados</h3></td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <tbody>
                        @foreach ($da as $item)
                            <tr>
                                <th style="font-size: 15px">{{ $item->NOMBRE }}</th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="table" style="margin-bottom: 0px">
                                        <thead>
                                            <tr>
                                                <th>Periodo</th>
                                                <th>Planilla</th>
                                                <th>Tipo de planilla</th>
                                                <th>AFP</th>
                                                <th style="text-align: right">N&deg; de personas</th>
                                                <th style="text-align: right">Total ganado</th>
                                                <th style="text-align: right">N&deg; de cheque</th>
                                                <th style="text-align: right">Monto de cheque</th>
                                                <th style="text-align: right">Fecha de pago caja de salud</th>
                                                <th style="text-align: right">N&deg; de deposito</th>
                                                <th style="text-align: right">GTC-11</th>
                                                <th style="text-align: right">N&deg; de recibo</th>
                                                <th style="text-align: right">Multa caja de salud</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($item->planillas as $planilla)
                                                {{-- {{ dd($planilla) }} --}}
                                                <tr>
                                                    <td>{{ $planilla->Periodo }}</td>
                                                    <td>{{ $planilla->idPlanillaprocesada }}</td>
                                                    <td>{{ $planilla->tipo_planilla }}</td>
                                                    <td>{{ $planilla->afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                                    <td style="text-align: right">{{ $planilla->personas }}</td>
                                                    <td style="text-align: right">{{ number_format($planilla->total_ganado, 2, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none">
                                                            @foreach ($planilla->detalle_cheque_afp as $cheque)
                                                            <li>{{ $cheque->number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none; display: {{ count($planilla->detalle_cheque_afp) ? 'block' : 'none' }}">
                                                            @php
                                                                $total = 0;
                                                            @endphp
                                                            @foreach ($planilla->detalle_cheque_cc as $cheque)
                                                            <li>Bs. {{ number_format($cheque->amount, 2, ',', '.') }}</li>
                                                            @php
                                                                $total += $cheque->amount;
                                                            @endphp
                                                            @endforeach
                                                            <li>
                                                                <hr style="margin: 5px 0px">
                                                                <b>Bs. {{ number_format($total, 2, ',', '.') }}</b>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>{{ date('d/m/Y', strtotime($pago->date_payment_cc)) }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>{{ $pago->deposit_number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>{{ $pago->gtc_number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>{{ $pago->recipe_number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none;">
                                                            @foreach ($planilla->detalle_pago as $pago)
                                                            <li>Bs. {{ number_format($pago->penalty_check, 2, ',', '.') }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @empty
                                            <tr>
                                                <td colspan="13"><h3 class="text-center">No hay resultados</h3></td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    th{
        font-size: 10px
    }
    td{
        font-size: 13px
    }
</style>

<script>
    $(document).ready(function(){

    })
</script>