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
                height: 0px;
                display: block;
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    @php
        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    @endphp
    @foreach ($paymentschedule_details as $item)
        @php
            // dd($item);
            $period = App\Models\Period::findOrFail($item->paymentschedule->period_id);
            $year = Str::substr($period->name, 0, 4);
            $month = Str::substr($period->name, 4, 2);
        @endphp
        @for ($i = 0; $i < 2; $i++)
        <div @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
            <table width="100%">
                <tr>
                    <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                    <td style="text-align: right">
                        <h2 style="margin-bottom: 0px; margin-top: 5px">BOLETA DE PAGO</h2>
                        <small>Impreso por {{ Auth::user()->name }} - {{ date('d/m/Y H:i:s') }}</small>
                        <br>
                    </td>
                    <td style="text-align:center; width: 80px">
                        {!! QrCode::size(70)->generate('BOLETA DE PAGO Nº '.str_pad($item->id, 6, "0", STR_PAD_LEFT).' '.$item->contract->person->first_name.' '.$item->contract->person->last_name.' CI:'.$item->contract->person->ci.', '.$months[intval($month)].' de '.$year.' Bs. '.number_format($item->liquid_payable, 2, ',', '.')); !!} <br>
                        <small><b>N&deg; {{ str_pad($item->id, 6, "0", STR_PAD_LEFT) }}</b></small>
                    </td>
                </tr>
            </table>
            <div id="watermark">
                <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
            </div>
            <table width="100%" border="1" cellpadding="5" style="font-size: 12px">
                <tr>
                    <td><b style="font-size: 15px">{{ $item->paymentschedule->direccion_administrativa->nombre }}</b></td>
                    <td style="width: 180px"><b>ITEM:</b> {{ $item->item }}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <b>NOMBRE: </b> {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                        <b>CI: </b> {{ $item->contract->person->ci }} <br>
                        <b>CARGO: </b> {{ $item->contract->cargo ? $item->contract->cargo->Descripcion : $item->contract->job->name }} <br>
                        <b>AFP: </b> {{ $item->afp == 1 ? 'Futuro' : 'Previsión' }} <br>
                        <b>NUA/CUA: </b> {{ $item->contract->person->nua_cua }} <br>
                        <b>MODALIDAD DE CONTRATACIÓN: </b> {{ ucwords($item->paymentschedule->procedure_type->name) }}
                    </td>
                    <td valign="top">
                        <b>PERIODO: </b> {{ $months[intval($month)] }} de {{ $year }} <br>
                        <b>DÍAS TRABAJADOS: </b> {{ $item->worked_days - $item->faults_quantity }} <br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>NIVEL SALARIAL: </b> {{ $item->contract->cargo ? $item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->NumNivel : $item->contract->job->level }} <br>
                        <b>SUELDO MENSUAL: </b> {{ number_format($item->salary, 2 , ',', '.') }} <br>
                        <b>SUELDO PARCIAL: </b> {{ number_format($item->partial_salary, 2, ',', '.') }} <br>
                        <b>BONO ANTIGÜEDAD: </b> {{ number_format($item->seniority_bonus_amount, 2, ',', '.') }} <br>
                        <b>TOTAL GANADO: </b> {{ number_format($item->partial_salary + $item->seniority_bonus_amount, 2, ',', '.') }} <br>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center"><b>DESCUENTOS</b></td>
                    <td rowspan="3" valign="bottom" style="text-align: center"><b><small>SELLO Y FIRMA</small></b></td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <b>APORTE LABORAL AFP:</b> {{ number_format($item->labor_total, 2, ',', '.') }} <br>
                        <b>RC IVA:</b> {{ number_format($item->rc_iva_amount, 2, ',', '.') }} <br>
                        <b>MULTAS:</b> {{ number_format($item->faults_amount, 2, ',', '.') }} <br>
                        <b>TOTAL DESCUENTOS:</b> {{ number_format($item->labor_total + $item->rc_iva_amount + $item->faults_amount, 2, ',', '.') }} <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <b>LÍQUIDO PAGABLE: </b> {{ NumerosEnLetras::convertir(number_format($item->liquid_payable, 2, '.', ''), 'Bolivianos', true) }}
                        <br> <br>
                    </td>
                </tr>
            </table>
        </div>
        @endfor
        <div class="page-break">
            {{-- <hr> --}}
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