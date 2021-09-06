<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de declaraciones juradas</title>
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
                    DECLARACIÓN JURADA - BIENES Y RENTAS <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        $year = Str::substr($periodo, 0, 4);
                        $month = intval(Str::substr($periodo, 4, 2));
                        if($afp == 1){
                            $afp = '- FUTURO';
                        }elseif($afp == 2){
                            $afp = '- PREVISÓN';
                        }
                    @endphp
                    <small> {{ strtoupper($months[$month]) }} GESTIÓN {{ $year }} {{ $afp }} </small> <br>
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
                <th>ITEM</th>
                <th>NIVEL</th>
                <th>APELLIDOS Y NOMBRES / CARGO</th>
                <th>CÉDULA DE IDENTIDAD</th>
                <th>EXP</th>
                <th>N&deg; NUA/CUA</th>
                <th>FECHA INGRESO</th>
                <th>FECHA NACIMIENTO</th>
                <th>FECHA CONCLUSIÓN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($funcionarios as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->Nivel }}</td>
                    <td>{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} <br> <small>{{ $item->Cargo }}</small></td>
                    <td>{{ $item->CedulaIdentidad }}</td>
                    <td>{{ $item->Expedido }}</td>
                    <td>{{ $item->Num_Nua }}</td>
                    <td>{{ $item->Fecha_Ingreso }}</td>
                    <td>{{ $item->fechanacimiento }}</td>
                    <td></td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="9"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
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