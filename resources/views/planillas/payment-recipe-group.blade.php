<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boletas de pago</title>
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
        #watermark-stamp {
            position: absolute;
            /* opacity: 0.9; */
            z-index:  -1000;
        }
        #watermark img{
            position: relative;
            width: 300px;
            height: 300px;
            left: 205px;
        }
        #watermark-stamp img{
            position: relative;
            width: 4cm;
            height: 4cm;
            left: 50px;
            top: 70px;
        }
        .show-print{
            display: none;
            padding-top: 15px
        }
        .btn-print{
            padding: 5px 10px
        }
        .page-break{
            height: 30px;
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
            .page-break{
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    @foreach ($paymentschedule_details as $item)
    {{-- {{ dd($item) }} --}}
        @for ($i = 0; $i < 2; $i++)
        <div @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
            <table width="100%">
                <tr>
                    <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                    <td style="text-align: right">
                        <h3 style="margin-bottom: 0px; margin-top: 5px">BOLETA DE PAGO<br> <small>RECIBO DE PAGO N&deg; {{ str_pad($item->id, 6, "0", STR_PAD_LEFT) }} </small> </h3>
                        <small>Impreso por {{ Auth::user()->name }} - {{ date('d/m/Y H:i:s') }}</small>
                        <br>
                    </td>
                </tr>
            </table>
            <hr style="margin: 0px">
            <div id="watermark">
                <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
            </div>
            <table width="100%" cellpadding="5" style="font-size: 12px">
                <tr>
                    <td><b>SECRETARÍA</b></td>
                    <td style="border: 1px solid #ddd">{{ $item->paymentschedule->direccion_administrativa->NOMBRE }}</td>
                    <td><b>TIPO DE CONTRATO</b></td>
                    <td style="border: 1px solid #ddd">{{ $item->paymentschedule->procedure_type->name }}</td>
                </tr>
                <tr>
                    <td><b>AFP</b></td>
                    <td style="border: 1px solid #ddd">{{ $item->contract->person->afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                    <td><b>ITEM</b></td>
                    <td style="border: 1px solid #ddd">{{ $item->item }}</td>
                </tr>
                <tr>
                    <td><b>NOMBRE COMPLETO</b></td>
                    <td style="border: 1px solid #ddd">{{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }}</td>
                    <td><b>CI</b></td>
                    <td style="border: 1px solid #ddd">{{ $item->contract->person->ci }}</td>
                </tr>
                <tr>
                    <td><b>FECHA DE PAGO</b></td>
                    <td style="border: 1px solid #ddd">
                        @php
                            $dias = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        {{ $dias[date('N')].', '.date('d').' de '.$months[intval(date('m'))].' de '.date('Y') }}
                    </td>
                    <td><b>HORA</b></td>
                    <td style="border: 1px solid #ddd">
                        {{ date('H:i:s') }}
                    </td>
                </tr>
            </table>
            <hr style="margin: 0px">
            <div id="watermark-stamp">
                {{-- <img src="{{ asset('images/stamp.png') }}" height="100%" width="100%" />  --}}
            </div>
            <table width="100%" cellpadding="10" style="font-size: 12px">
                <tr>
                    <td>
                        <table width="100%" cellpadding="5">
                            @php
                                $period = App\Models\Period::findOrFail($item->paymentschedule->period_id);
                                $year = Str::substr($period->name, 0, 4);
                                $month = Str::substr($period->name, 4, 2);
                            @endphp
                            <tr>
                                <td width="150px"><b>GESTIÓN</b></td>
                                <td style="border: 1px solid #ddd">{{ $year }}</td>
                                <td style="text-align: right"><b>MES</b></td>
                                <td style="border: 1px solid #ddd; text-align: right">
                                    @php
                                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                    @endphp
                                    {{ $meses[intval($month)] }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 120px"><b>DÍAS TRABAJADOS</b></td>
                                <td style="border: 1px solid #ddd">{{ $item->worked_days - $item->faults_quantity }}</td>
                                <td width="150px" style="text-align: right"><b>HABER BÁSICO</b></td>
                                <td style="border: 1px solid #ddd; text-align: right">{{ number_format($item->salary, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td width="150px" style="text-align: right"><b>BONO ANTIGÜEDAD</b></td>
                                <td style="border: 1px solid #ddd; text-align: right">{{ number_format($item->seniority_bonus_amount, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td width="150px" style="text-align: right"><b>APORTE AFP</b></td>
                                <td style="border: 1px solid #ddd; text-align: right">{{ number_format($item->labor_total, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td width="150px" style="text-align: right"><b>DESCUENTOS O MULTAS</b></td>
                                <td style="border: 1px solid #ddd; text-align: right">{{ number_format($item->faults_amount, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right"><h2 style="margin: 0px; margin-top: 20px"> <small style="font-size: 12px">LÍQUIDO PAGABLE </small> &nbsp; {{ number_format($item->liquid_payable, 2, ',', '.') }}</h2></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right">
                                    <h3 style="margin: 0px">Son: {{ NumerosEnLetras::convertir($item->liquid_payable, 'Bolivianos', true) }}</h3>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        @endfor
        <div class="page-break">
            <hr>
        </div>
    @endforeach
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