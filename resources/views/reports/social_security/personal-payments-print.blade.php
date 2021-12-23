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
                    REPORTE INDIVIDUAL DE PAGOS AL SEGURO SOCIAL <br>
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
                <th>N&deg;</th>
                <th>NOMBRE COMPLETO</th>
                <th>CARNET DE<br>IDENTIDAD</th>
                <th>NUA/CUA</th>
                <th>DIRECCIÓN ADMINISTRATIVA</th>
                <th>ID PLANILLA</th>
                <th>PERIODO</th>
                <th>TOTAL<br>GANADO</th>
                <th>APORTE AFP</th>
                <th>FECHA DE PAGO</th>
                <th>N&deg; DE FPC</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($planillas as $item)
            {{-- {{ dd($item) }} --}}
            <tr>
                <td>{{ $cont }}</td>
                <td>{{ $item->empleado }}</td>
                <td>{{ $item->ci }}</td>
                <td>{{ $item->nua_cua }}</td>
                <td>{{ $item->direccion_administrativa }}</td>
                <td>{{ $item->planilla_procesada }}</td>
                <td>{{ $item->periodo }}</td>
                <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                <td>{{ number_format($item->total_afp, 2, ',', '.') }}</td>
                <td>
                    @foreach ($item->payments as $payment)
                        {{ $payment->date_payment_afp }}
                    @endforeach
                </td>
                <td>
                    @foreach ($item->payments as $payment)
                        {{ $payment->fpc_number }}
                    @endforeach
                </td>
            </tr>
            @php
                $cont++;
            @endphp
            @endforeach
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