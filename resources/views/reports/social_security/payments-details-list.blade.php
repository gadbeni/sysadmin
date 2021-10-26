
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
                                            @foreach ($item->planillas as $planilla)
                                                <tr>
                                                    <td>{{ $planilla->tipo_planilla }}</td>
                                                    <td>{{ $planilla->afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                                    <td style="text-align: right">{{ $planilla->personas }}</td>
                                                    <td style="text-align: right">{{ number_format($planilla->total_ganado, 2, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none">
                                                            @foreach ($planilla->detalle_cheque as $cheque)
                                                            <li>{{ $cheque->number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <ul style="list-style: none">
                                                            @php
                                                                $total = 0;
                                                            @endphp
                                                            @foreach ($planilla->detalle_cheque as $cheque)
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
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
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