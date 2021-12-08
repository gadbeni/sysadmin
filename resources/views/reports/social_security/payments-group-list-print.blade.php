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
                    REPORTE DE PAGOS AL SEGURO SOCIAL <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
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
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th style="text-align: center" colspan="9">DATOS GENERALES</th>
                <th style="text-align: center; background-color: #2980B9 !important; color: #fff" colspan="5">ADMINISTRADORES DE FONDOS DE PENSIONES</th>
                <th style="text-align: center; background-color: #16A085 !important; color: #fff" colspan="8">CAJA DE SALUD CORDES</th>
            </tr>
            <tr>
                <th>N&deg;</th>
                <th>HR/NCI</th>
                <th>PERIODO</th>
                <th>DIRECCIÓN ADMINISTRATIVA</th>
                <th>TIPO DE PLANILLA</th>
                <th>CÓDIGO DE PLANILLA</th>
                <th>N&deg; DE PERSONAS</th>
                <th>AFP</th>
                <th style="text-align: right">TOTAL GANADO</th>
                <th style="text-align: right">APORTE AFP</th>
                <th>N&deg; FCP</th>
                <th>FECHA DE PAGO AFP</th>
                <th>ID PAGO</th>
                <th>MULTA AFP</th>
                <th style="text-align: right">APORTE CC</th>
                <th>N&deg; DE CHEQUE</th>
                <th>N&deg; DE DEPOSITO</th>
                <th>FECHA DE PAGO CC</th>
                <th>F GTC-11</th>
                <th>ID PAGO</th>
                <th>N&deg; DE RECIBO</th>
                <th>MULTA CC</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total_personas = 0;
                $total_ganado = 0;
                $total_afp = 0;
                $total_multa_afp = 0;
                $total_cc = 0;
                $total_multa_cc = 0;
            @endphp
            @forelse ($planillas as $item)
                @php
                    $aporte_patronal = ($item->total_ganado * 0.05) + $item->riesgo_comun;
                    $aporte_caja_cordes = $item->total_ganado * 0.1;
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    {{-- <td></td> --}}
                    <td>{{ $item->certificacion ? $item->certificacion->HojaRuta_NCI : '' }}</td>
                    <td>{{ $item->Periodo }}</td>
                    <td>{{ $item->Direccion_Administrativa }}</td>
                    <td>{{ $item->tipo_planilla }} {{ $item->certificacion ? ' - '.$item->certificacion->nombre_planilla : '' }}</td>
                    <td>{{ $item->idPlanillaprocesada }}</td>
                    <td style="text-align: right">{{ $item->cantidad_personas }}</td>
                    <td>{{ $item->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                    <td style="text-align: right">{{ number_format($item->total_ganado, 2, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->Total_Aportes_Afp + $aporte_patronal, 2, ',', '.') }}</td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->fpc_number }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->date_payment_afp ? date('d/m/Y', strtotime($pago->date_payment_afp)) : '' }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->payment_id }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ number_format($pago->penalty_payment, 2, ',', '.') }}<br>
                        @endforeach
                    </td>
                    <td style="text-align: right">{{ number_format($aporte_caja_cordes, 2, ',', '.') }}</td>
                    <td>
                        @foreach ($item->detalle_cheque as $cheque)
                            {{ $cheque->number }}<br>        
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->deposit_number }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->date_payment_cc ? date('d/m/Y', strtotime($pago->date_payment_cc)) : '' }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->gtc_number }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->check_id }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ $pago->recipe_number }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->detalle_pago as $pago)
                            {{ number_format($pago->penalty_check, 2, ',', '.') }}<br>
                        @endforeach
                    </td>
                </tr>
                @php
                    $cont++;
                    $total_personas += $item->cantidad_personas;
                    $total_ganado += $item->total_ganado;
                    $total_afp += $item->Total_Aportes_Afp + $aporte_patronal;
                    $total_multa_afp += count($item->detalle_pago) > 0 ? $item->detalle_pago[0]->penalty_payment : 0;
                    $total_cc += $aporte_caja_cordes;
                    $total_multa_cc += count($item->detalle_pago) > 0 ? $item->detalle_pago[0]->penalty_check : 0;
                @endphp
            @empty
                
            @endforelse
            <tr>
                <td colspan="6"><b>TOTAL</b></td>
                <td style="text-align: right"><b>{{ $total_personas }}</b></td>
                <td></td>
                <td style="text-align: right"><b>{{ number_format($total_ganado, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_afp, 2, ',', '.') }}</b></td>
                <td colspan="3"></td>
                <td style="text-align: right"><b>{{ number_format($total_multa_afp, 2, ',', '.') }}</b></td>
                <td style="text-align: right"><b>{{ number_format($total_cc, 2, ',', '.') }}</b></td>
                <td colspan="6"></td>
                <td style="text-align: right"><b>{{ number_format($total_multa_cc, 2, ',', '.') }}</b></td>
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