<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boletas de pago de aguinaldo {{ $bonus->year }}</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
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
        .border-bottom{
            border-bottom: 1px solid rgb(90, 90, 90);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    @php
        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $cont = 0;
    @endphp
    @foreach ($bonus->details as $item)
        @php
            $promedio = ($item->partial_salary_1 + $item->seniority_bonus_1 + $item->partial_salary_2 + $item->seniority_bonus_2 + $item->partial_salary_3 + $item->seniority_bonus_3) /3;
            $total_amount = ($promedio / 360) * $item->days;
        @endphp
        @for ($i = 0; $i < 2; $i++)
        <div @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
            <table width="100%">
                <tr>
                    <td><img src="{{ asset('images/icon-navidad.png') }}" alt="GADBENI" width="80px"></td>
                    <td style="text-align: right">
                        <h3 style="margin-bottom: 0px; margin-top: 5px">BOLETA DE PAGO AGUINALDO DE NAVIDAD {{ $bonus->year }}</h3>
                        <small>Impreso por {{ Auth::user()->name }} - {{ date('d/m/Y H:i:s') }}</small>
                        <br>
                    </td>
                    <td style="text-align:center; width: 80px">
                        @php
                            $string_qr = 'BOLETA DE PAGO DE AGUINALDO NAVIDEÑO '.$bonus->year.' Nº '.str_pad($item->id, 6, "0", STR_PAD_LEFT).' '.$item->contract->person->first_name.' '.$item->contract->person->last_name.' CI:'.$item->contract->person->ci.', Bs. '.number_format($total_amount, 2, ',', '.');
                            $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($string_qr));
                        @endphp
                        <img src="data:image/png;base64, {!! $qrcode !!}"> <br>
                        {{-- {!! QrCode::size(80)->generate($string_qr); !!} <br> --}}
                        <small><b>N&deg; {{ str_pad($item->id, 6, "0", STR_PAD_LEFT) }}</b></small>
                    </td>
                </tr>
            </table>
            <div id="watermark">
                <img src="{{ asset('images/icon-navidad.png') }}" height="100%" width="100%" /> 
            </div>
            <table width="100%" border="1" cellpadding="5" style="font-size: 12px">
                <tr>
                    <td><b style="font-size: 15px">{{ $item->contract->direccion_administrativa->nombre }}</b></td>
                    <td style="width: 180px"><b>ITEM:</b> {{ $item->item ?? 'S/N' }}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <b>NOMBRE: </b> {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                        <b>CI: </b> {{ $item->contract->person->ci }} <br>
                        <b>CARGO: </b> {{ $item->contract->cargo ? $item->contract->cargo->Descripcion : $item->contract->job->name }} <br>
                        {{-- <b>AFP: </b> {{ $item->contract->person->afp_type->name }} <br>
                        <b>NUA/CUA: </b> {{ $item->contract->person->nua_cua }} <br> --}}
                        {{-- <b>MODALIDAD DE CONTRATACIÓN: </b> {{ Str::upper($item->contract->type->name) }} --}}
                    </td>
                    <td valign="top">
                        <b>PERIODO: </b> AGUINALDO <br>
                        <b>DÍAS TRABAJADOS: </b> {{ $item->days }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>PROM. TOTAL GANADO 3 ULT. MESES: </b> {{ number_format($promedio, 2, ',', '.') }} <br>
                        <b>TOTAL AGUINALDO: </b> {{ number_format($total_amount, 2, ',', '.') }}
                    </td>
                </tr>
                <tr valign="bottom">
                    <td>
                        @php
                            $numeros_a_letras = new NumeroALetras();
                        @endphp
                        <b>LÍQUIDO PAGABLE: </b>Bs. {{ number_format($total_amount, 2, '.', '') }} ({{ $numeros_a_letras->toInvoice(number_format($total_amount, 2, '.', ''), 2, 'Bolivianos') }})
                        <br> <br>
                    </td>
                    <td valign="bottom" style="text-align: center; height: 100px"><b><small>SELLO Y FIRMA</small></b></td>
                </tr>
            </table>

            <div style="text-align: center; font-size: 12px">
                <br><br><br><br> <br>
                _________________________________ <br>
                RECIBI CONFORME
                <br> <br>
                {{-- <b>{{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }}</b> <br>
                <b>CI: {{ $item->contract->person->ci }}</b> --}}
            </div>
        </div>
        @endfor

        @php
            $cont++;
        @endphp

        @if ($cont < $bonus->details->count())
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>