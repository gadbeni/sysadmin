<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de pagos al seguro social</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            /* max-width: 1024px; */
        }
        .btn-print{
            padding: 5px 10px
        }
        th{
            font-size: 7px
        }
        td{
            font-size: 10px
        }
        @media all {
            div.saltopagina{
                display: none;
            }
            .header-page{
                display: none
            }
        }
        @media print{
            .hide-print{
                display: none
            }
            div.saltopagina{
                display:block;
                page-break-before:always;
            }
            .header-page{
                display: block
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
                    REPORTE DE PAGOS AL SEGURO SOCIAL <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
                    <small>DIRECCIÓN DE BIENESTAR LABORAL Y PREVISIÓN SOCIAL</small> <br>
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br><br>
    <div class="">
        <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th style="font-size: 18px">AFP</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($da as $item)
                    <tr>
                        <th style="font-size: 15px; text-align: left">{{ $item->NOMBRE }}</th>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
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

    <div class="saltopagina"></div>

    <div class="header-page">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">
                        REPORTE DE PAGOS AL SEGURO SOCIAL <br>
                        @php
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        <small>DIRECCIÓN DE BIENESTAR LABORAL Y PREVISIÓN SOCIAL</small> <br>
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </h3>
                </td>
            </tr>
        </table>
    </div>
    <br><br>
    
    <div class="">
        <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th style="font-size: 18px">Caja de Salud</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($da as $item)
                    <tr>
                        <th style="font-size: 15px; text-align: left">{{ $item->NOMBRE }}</th>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
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