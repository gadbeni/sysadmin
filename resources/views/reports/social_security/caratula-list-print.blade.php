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
            max-width: 1024px;
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
                    CARÁTULA DE PLANILLA N&deg; {{ $planilla_id }}<br>
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
    
    <div class="panel-body">
        <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
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
        <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
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
        <table width="100%" border="1" cellpadding="5" cellspacing="0" style="text-align: center">
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
                    <td colspan="4">No hay resultados</td>
                </tr>
            @endforelse
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