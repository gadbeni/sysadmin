<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de pagos realizados</title>
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
        .item-delete{
            text-decoration: line-through;
            color: red !important;
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
                    REPORTE DE PAGOS REALIZADOS <br>
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
                <th>DETALLE</th>
                <th>FECHA</th>
                <th>CAJERO(A)</th>
                <th>OBSERVACIONES </th>
                <th class="text-right">MONTO (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                $total = 0;
            @endphp
            @forelse ($payments as $item)
                @php
                    if($item->deleted_at == NULL){
                        $total += $item->amount;
                    }
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td @if($item->deleted_at) class="item-delete" @endif>{{ $item->description }}</td>
                    <td>{{ date('d', strtotime($item->created_at)).'/'.$months[intval(date('m', strtotime($item->created_at)))].'/'.date('Y', strtotime($item->created_at)) }} {{ date('H:i', strtotime($item->created_at)) }} </td>
                    <td>{{ $item->cashier->user->name }} </td>
                    <td style="max-width: 150px">
                        {{ $item->observations }}
                        @if ($item->deletes)
                            <br><b>Motivo de eliminación:</b><br>
                            {{  $item->deletes->observations }}
                        @endif
                    </td>
                    <td @if($item->deleted_at) class="item-delete" @endif style="text-align:right"><b>{{ number_format($item->amount, 2, ',', '.') }}</b></td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="6"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" class="text-right"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
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