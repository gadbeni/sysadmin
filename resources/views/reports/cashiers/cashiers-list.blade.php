
<div class="col-md-12 text-right">
    @if (count($cashier))
        <button type="button" onclick="report_print()" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>CAJERO</th>
                            <th>ESTADO</th>
                            <th>FECHA DE <br> APERTURA</th>
                            <th>FECHA DE <br> CIERRE </th>
                            <th class="text-right">APERTURA (Bs.)</th>
                            <th class="text-right">INGRESOS (Bs.)</th>
                            <th class="text-right">PAGOS (Bs.)</th>
                            <th class="text-right">CANTIDAD <br> DE PAGOS</th>
                            <th class="text-right">EGRESOS (Bs.)</th>
                            <th class="text-right">TOTAL A <br> DEVOLVER (Bs.)</th>
                            <th class="text-right">TOTAL<br> DEVUELTO (Bs.)</th>
                            <th class="text-right">SOBRANTE (Bs.)</th>
                            <th class="text-right">FALTANTE (Bs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                            $total_opening = 0;
                            $total_income = 0;
                            $total_payment = 0;
                            $total_payment_count = 0;
                            $total_expense = 0;
                            $total_closing = 0;
                            $total_returned = 0;
                            $total_surplus = 0;
                            $total_missing = 0;
                        @endphp
                        @forelse ($cashier as $item)
                            @php
                                $opening_amount = $item->movements->where('description', 'Monto de apertura de caja.')->where('deleted_at', NULL)->first()->amount;
                                $income_amount = 0;
                                $payment_amount = $item->payments->where('deleted_at', NULL)->sum('amount');
                                $payment_count = $item->payments->where('deleted_at', NULL)->count();
                                $expense_amount = 0;
                                $closing_amount = 0;

                                $total_opening += $opening_amount;
                                $total_payment += $payment_amount;
                                $total_payment_count += $payment_count;
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->status }} </td>
                                <td>{{ date('d', strtotime($item->created_at)).'/'.$months[intval(date('m', strtotime($item->created_at)))].'/'.date('Y', strtotime($item->created_at)) }} <br> <small>{{ date('H:i', strtotime($item->created_at)) }}</small> </td>
                                <td>
                                    @if ($item->closed_at)
                                    {{ date('d', strtotime($item->closed_at)).'/'.$months[intval(date('m', strtotime($item->closed_at)))].'/'.date('Y', strtotime($item->closed_at)) }} <br> <small>{{ date('H:i', strtotime($item->closed_at)) }}</small>
                                    @else
                                        <span class="label label-warning">No cerrado</span>
                                    @endif
                                </td>
                                <td class="text-right"><b>{{ number_format($opening_amount, 2, ',', '.') }}</b></td>
                                @php
                                    foreach($item->movements as $movement){
                                        if($movement->description != 'Monto de apertura de caja.' && $movement->type == 'ingreso' && $movement->deleted_at == NULL){
                                            $income_amount += $movement->amount;
                                        }
                                    }
                                    $total_income += $income_amount;
                                @endphp
                                <td class="text-right"><b>{{ number_format($income_amount, 2, ',', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($payment_amount, 2, ',', '.') }}</b></td>
                                <td class="text-right"><b>{{ $payment_count }}</b></td>
                                @php
                                    foreach($item->movements as $movement){
                                        if($movement->description != 'Monto de apertura de caja.' && $movement->type == 'egreso' && $movement->deleted_at == NULL){
                                            $expense_amount += $movement->amount;
                                        }
                                    }
                                    $total_expense += $expense_amount;
                                    $total_closing += ($opening_amount + $income_amount - $payment_amount - $expense_amount);
                                @endphp
                                <td class="text-right"><b>{{ number_format($expense_amount, 2, ',', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($opening_amount + $income_amount - $payment_amount - $expense_amount, 2, ',', '.') }}</b></td>
                                @php
                                    foreach($item->details as $detail){
                                        if($detail->deleted_at == NULL){
                                            $closing_amount += $detail->cash_value * $detail->quantity;
                                        }
                                    }
                                    $total_returned += $closing_amount;
                                @endphp
                                <td class="text-right"><b>{{ number_format($closing_amount, 2, ',', '.') }}</b></td>
                                @php
                                    $amount_diff = $opening_amount + $income_amount - $payment_amount - $expense_amount - $closing_amount;
                                    if($amount_diff > 0){
                                        $total_surplus += abs($amount_diff);
                                    }else{
                                        $total_missing += abs($amount_diff);
                                    }
                                @endphp
                                <td class="text-right"><b>{{ number_format($amount_diff < 0 ? abs($amount_diff) : 0, 2, ',', '.') }}</b></td>
                                <td class="text-right"><b>{{ number_format($amount_diff > 0 ? abs($amount_diff) : 0, 2, ',', '.') }}</b></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13"><h4 class="text-center">No hay resultados</h4></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="5" class="text-right"><b>TOTALES</b></td>
                            <td class="text-right"><b>{{ number_format($total_opening, 2, ',', '.') }}</b></td>
                            <td class="text-right"><b>{{ number_format($total_income, 2, ',', '.') }}</b></td>
                            <td class="text-right"><b>{{ number_format($total_payment, 2, ',', '.') }}</b></td>
                            <td class="text-right"><b>{{ $total_payment_count }}</b></td>
                            <td class="text-right"><b>{{ number_format($total_expense, 2, ',', '.') }}</b></td>
                            <td class="text-right"><b>{{ number_format($total_closing, 2, ',', '.') }}</b></td>
                            <td class="text-right"><b>{{ number_format($total_returned, 2, ',', '.') }}</b></td>
                            <td class="text-right"><b>{{ number_format($total_missing, 2, ',', '.') }}</b></td>
                            <td class="text-right"><b>{{ number_format($total_surplus, 2, ',', '.') }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    })
</script>