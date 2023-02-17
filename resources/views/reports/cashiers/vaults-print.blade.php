<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de bóveda</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 740px;
        }
        #watermark {
            position: absolute;
            opacity: 0.1;
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
        .show-print{
            display: none;
            padding-top: 15px
        }
        .btn-print{
            padding: 5px 10px
        }
        td{
            padding: 3px 10px
        }
        @media print{
            .hide-print, .btn-print{
                display: none
            }
            .show-print, .border-bottom{
                display: block
            }
            .border-bottom{
                border-bottom: 1px solid rgb(90, 90, 90);
                padding: 20px 0px;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    @for ($i = 0; $i < 2; $i++)
    <div style="height: 45vh" @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">
                        CAJAS - GOBERNACIÓN<br> <small>ESTADO DE BÓVEDA HASTA FECHA {{ date('d/m/Y', strtotime($date)) }} </small> <br>
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </h3>
                </td>
            </tr>
        </table>
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>

        <hr style="margin: 0px">
        <table width="100%" cellpadding="10" style="font-size: 12px">
            <thead>
                <tr>
                    <th>Corte</th>
                    <th style="text-align: right">Cantidad</th>
                    <th style="text-align: right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $cantidad_200 = 0;
                    $cantidad_100 = 0;
                    $cantidad_50 = 0;
                    $cantidad_20 = 0;
                    $cantidad_10 = 0;
                    $cantidad_5 = 0;
                    $cantidad_2 = 0;
                    $cantidad_1 = 0;
                    $cantidad_05 = 0;
                    $cantidad_02 = 0;
                    $cantidad_01 = 0;
                    foreach ($details->where('type', 'ingreso') as $ingreso) {
                        $cantidad_200 += $ingreso->cash->where('cash_value', '200.00')->sum('quantity');
                        $cantidad_100 += $ingreso->cash->where('cash_value', '100.00')->sum('quantity');
                        $cantidad_50 += $ingreso->cash->where('cash_value', '50.00')->sum('quantity');
                        $cantidad_20 += $ingreso->cash->where('cash_value', '20.00')->sum('quantity');
                        $cantidad_10 += $ingreso->cash->where('cash_value', '10.00')->sum('quantity');
                        $cantidad_5 += $ingreso->cash->where('cash_value', '5.00')->sum('quantity');
                        $cantidad_2 += $ingreso->cash->where('cash_value', '2.00')->sum('quantity');
                        $cantidad_1 += $ingreso->cash->where('cash_value', '1.00')->sum('quantity');
                        $cantidad_05 += $ingreso->cash->where('cash_value', '0.50')->sum('quantity');
                        $cantidad_02 += $ingreso->cash->where('cash_value', '0.20')->sum('quantity');
                        $cantidad_01 += $ingreso->cash->where('cash_value', '0.10')->sum('quantity');
                    }
                    foreach ($details->where('type', 'egreso') as $egreso) {
                        $cantidad_200 -= $egreso->cash->where('cash_value', '200.00')->sum('quantity');
                        $cantidad_100 -= $egreso->cash->where('cash_value', '100.00')->sum('quantity');
                        $cantidad_50 -= $egreso->cash->where('cash_value', '50.00')->sum('quantity');
                        $cantidad_20 -= $egreso->cash->where('cash_value', '20.00')->sum('quantity');
                        $cantidad_10 -= $egreso->cash->where('cash_value', '10.00')->sum('quantity');
                        $cantidad_5 -= $egreso->cash->where('cash_value', '5.00')->sum('quantity');
                        $cantidad_2 -= $egreso->cash->where('cash_value', '2.00')->sum('quantity');
                        $cantidad_1 -= $egreso->cash->where('cash_value', '1.00')->sum('quantity');
                        $cantidad_05 -= $egreso->cash->where('cash_value', '0.50')->sum('quantity');
                        $cantidad_02 -= $egreso->cash->where('cash_value', '0.20')->sum('quantity');
                        $cantidad_01 -= $egreso->cash->where('cash_value', '0.10')->sum('quantity');
                    }
                @endphp
                <tr>
                    <td><img src="{{ asset('images/cash/200.jpg') }}" alt="200 Bs." width="20px"> 200 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_200, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_200 * 200, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/100.jpg') }}" alt="100 Bs." width="20px"> 100 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_100, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_100 * 100, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/50.jpg') }}" alt="50 Bs." width="20px"> 50 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_50, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_50 * 50, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/20.jpg') }}" alt="20 Bs." width="20px"> 20 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_20, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_20 * 20, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/10.jpg') }}" alt="10 Bs." width="20px"> 10 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_10, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_10 * 10, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/5.jpg') }}" alt="5 Bs." width="20px"> 5 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_5, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_5 * 5, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/2.jpg') }}" alt="2 Bs." width="20px"> 2 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_2, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_2 * 2, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/1.jpg') }}" alt="1 Bs." width="20px"> 1 Bs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_1, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_1, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/0.5.jpg') }}" alt="50 Ctvs." width="20px"> 50 Ctvs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_05, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_05 * 0.5, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/0.2.jpg') }}" alt="20 Ctvs." width="20px"> 20 Ctvs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_02, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_02 * 0.2, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><img src="{{ asset('images/cash/0.1.jpg') }}" alt="10 Ctvs." width="20px"> 10 Ctvs.</td>
                    <td style="text-align: right">{{ number_format($cantidad_01, 0) }}</td>
                    <td style="text-align: right">{{ number_format($cantidad_01 * 0.1, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2">TOTAL</td>
                    <td style="text-align: right"><h4>Bs. {{ number_format(($cantidad_200 * 200) + ($cantidad_100 * 100) + ($cantidad_50 * 50) + ($cantidad_20 * 20) + ($cantidad_10 * 10) + ($cantidad_5 * 5) + ($cantidad_2 * 2) + $cantidad_1 + ($cantidad_05 * 0.5) + ($cantidad_02 * 0.2) + ($cantidad_01 * 0.1), 2, ',', '.') }}</h4></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endfor
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