<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de caja</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 1024px;
        }
        .btn-print{
            padding: 5px 10px
        }
        @media print{
            .hide-print{
                display: none
            }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE CAJAS <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
                    {{-- <small>RECURSOS HUMANOS</small> <br> --}}
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
        <tr>
            <tr></tr>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>CAJERO</th>
                <th>ESTADO</th>
                <th>FECHA DE <br> APERTURA</th>
                <th>CIERRE DE <br> CIERRE </th>
                <th style="text-align: right">APERTURA (Bs.)</th>
                <th style="text-align: right">INGRESOS (Bs.)</th>
                <th style="text-align: right">PAGOS (Bs.)</th>
                <th style="text-align: right">EGRESOS (Bs.)</th>
                <th style="text-align: right">TOTAL A <br> DEVOLVER (Bs.)</th>
                <th style="text-align: right">TOTAL<br> DEVUELTO (Bs.)</th>
                <th style="text-align: right">SOBRANTE (Bs.)</th>
                <th style="text-align: right">FALTANTE (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                $total_opening = 0;
                $total_income = 0;
                $total_payment = 0;
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
                    $expense_amount = 0;
                    $closing_amount = 0;

                    $total_opening += $opening_amount;
                    $total_payment += $payment_amount;
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
                    <td style="text-align: right"><b>{{ number_format($opening_amount, 2, ',', '.') }}</b></td>
                    @php
                        foreach($item->movements as $movement){
                            if($movement->description != 'Monto de apertura de caja.' && $movement->type == 'ingreso' && $movement->deleted_at == NULL){
                                $income_amount += $movement->amount;
                            }
                        }
                        $total_income += $income_amount;
                    @endphp
                    <td style="text-align: right"><b>{{ number_format($income_amount, 2, ',', '.') }}</b></td>
                    <td style="text-align: right"><b>{{ number_format($payment_amount, 2, ',', '.') }}</b></td>
                    @php
                        foreach($item->movements as $movement){
                            if($movement->description != 'Monto de apertura de caja.' && $movement->type == 'egreso' && $movement->deleted_at == NULL){
                                $expense_amount += $movement->amount;
                            }
                        }
                        $total_expense += $expense_amount;
                        $total_closing += ($opening_amount + $income_amount - $payment_amount - $expense_amount);
                    @endphp
                    <td style="text-align: right"><b>{{ number_format($expense_amount, 2, ',', '.') }}</b></td>
                    <td style="text-align: right"><b>{{ number_format($opening_amount + $income_amount - $payment_amount - $expense_amount, 2, ',', '.') }}</b></td>
                    @php
                        foreach($item->details as $detail){
                            if($detail->deleted_at == NULL){
                                $closing_amount += $detail->cash_value * $detail->quantity;
                            }
                        }
                        $total_returned += $closing_amount;
                    @endphp
                    <td style="text-align: right"><b>{{ number_format($closing_amount, 2, ',', '.') }}</b></td>
                    @php
                        $amount_diff = $opening_amount + $income_amount - $payment_amount - $expense_amount - $closing_amount;
                        if($amount_diff > 0){
                            $total_surplus += abs($amount_diff);
                        }else{
                            $total_missing += abs($amount_diff);
                        }
                    @endphp
                    <td style="text-align: right"><b>{{ number_format($amount_diff < 0 ? abs($amount_diff) : 0, 2, ',', '.') }}</b></td>
                    <td style="text-align: right"><b>{{ number_format($amount_diff > 0 ? abs($amount_diff) : 0, 2, ',', '.') }}</b></td>
                </tr>
            @empty
                <tr>
                    <td colspan="13"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" style="text-align: right"><b>TOTALES</b></td>
                <td style="text-align: right"><b>{{ number_format($total_opening, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_income, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_payment, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_expense, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_closing, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_returned, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_missing, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_surplus, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
    <script>
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>
</body>
</html>