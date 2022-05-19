<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boleta de pago</title>
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
    @php
        if($payment->paymentschedulesdetail){
            $period = App\Models\Period::findOrFail($payment->paymentschedulesdetail->paymentschedule->period_id);
            $year = Str::substr($period->name, 0, 4);
            $month = Str::substr($period->name, 4, 2);
        }
        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    @endphp
    @for ($i = 0; $i < 2; $i++)
    <div style="height: 45vh" @if ($i == 1) class="show-print" @else class="border-bottom" @endif>
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="80px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: 0px; margin-top: 5px">BOLETA DE PAGO</h2>
                    <small>Impreso por {{ Auth::user()->name }} - {{ date('d/m/Y H:i:s') }}</small>
                    <br>
                </td>
                <td style="text-align:center; width: 80px">
                    {!! QrCode::size(70)->generate('BOLETA DE PAGO Nº '.str_pad($planilla ? $planilla->ID : $payment->id, 6, "0", STR_PAD_LEFT).' '.($planilla ? $planilla->Nombre_Empleado : $payment->paymentschedulesdetail->contract->person->first_name.' '.$payment->paymentschedulesdetail->contract->person->last_name).' CI:'.($planilla ? $planilla->CedulaIdentidad : $payment->paymentschedulesdetail->contract->person->ci).', '.($months[intval($planilla ? $planilla->Mes : $month)]).' de '.($planilla ? $planilla->Anio : $year).' Bs. '.number_format($planilla ? $planilla->Liquido_Pagable : $payment->paymentschedulesdetail->liquid_payable, 2, ',', '.')); !!} <br>
                    <small><b>N&deg; {{ str_pad($planilla ? $planilla->ID : $payment->id, 6, "0", STR_PAD_LEFT) }}</b></small>
                </td>
            </tr>
        </table>
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>
        <table width="100%" border="1" cellpadding="5" style="font-size: 12px">
            <tr>
                <td><b style="font-size: 15px">{{ $planilla ? $planilla->Direccion_Administrativa : $payment->paymentschedulesdetail->paymentschedule->direccion_administrativa->nombre }}</b></td>
                <td style="width: 180px"><b>ITEM:</b> {{ $planilla ? $planilla->ITEM : $payment->paymentschedulesdetail->item }}</td>
            </tr>
            <tr>
                <td rowspan="2">
                    <b>NOMBRE: </b> {{ $planilla ? $planilla->Nombre_Empleado : $payment->paymentschedulesdetail->contract->person->first_name.' '.$payment->paymentschedulesdetail->contract->person->last_name }} <br>
                    <b>CI: </b> {{ $planilla ? $planilla->CedulaIdentidad : $payment->paymentschedulesdetail->contract->person->ci }} <br>
                    <b>CARGO: </b> {{ $planilla ? $planilla->Cargo : ($payment->paymentschedulesdetail->contract->cargo ? $payment->paymentschedulesdetail->contract->cargo->Descripcion : $payment->paymentschedulesdetail->contract->job->name) }} <br>
                    <b>AFP: </b> {{ ($planilla ? $planilla->Afp : $payment->paymentschedulesdetail->contract->person->afp == 1) ? 'Futuro' : 'Previsión' }} <br>
                    <b>NUA/CUA: </b> {{ $planilla ? $planilla->Num_Nua : $payment->paymentschedulesdetail->contract->person->nua_cua }} <br>
                    <b>MODALIDAD DE CONTRATACIÓN: </b> {{ ucwords($planilla ? $planilla->tipo_planilla : $payment->paymentschedulesdetail->paymentschedule->procedure_type->name) }}
                </td>
                <td valign="top">
                    <b>PERIODO: </b> {{ $months[intval($planilla ? $planilla->Mes : $month)] }} de {{ $planilla ? $planilla->Anio : $year }} <br>
                    <b>DÍAS TRABAJADOS: </b> {{ $planilla ? $planilla->Dias_Trabajado : $payment->paymentschedulesdetail->worked_days - $payment->paymentschedulesdetail->faults_quantity }} <br>
                </td>
            </tr>
            <tr>
                <td>
                    <b>NIVEL SALARIAL: </b> {{ $planilla ? $planilla->Nivel : ($payment->paymentschedulesdetail->contract->cargo ? $payment->paymentschedulesdetail->contract->cargo->nivel->where('IdPlanilla', $payment->paymentschedulesdetail->contract->cargo->idPlanilla)->first()->NumNivel : $payment->paymentschedulesdetail->contract->job->level) }} <br>
                    <b>SUELDO MENSUAL: </b> {{ number_format($planilla ? $planilla->Sueldo_Mensual : $payment->paymentschedulesdetail->salary, 2 , ',', '.') }} <br>
                    <b>SUELDO PARCIAL: </b> {{ number_format($planilla ? $planilla->Sueldo_Parcial : $payment->paymentschedulesdetail->partial_salary, 2, ',', '.') }} <br>
                    <b>BONO ANTIGÜEDAD: </b> {{ number_format($planilla ? $planilla->Bono_Antiguedad : $payment->paymentschedulesdetail->seniority_bonus_amount, 2, ',', '.') }} <br>
                    <b>TOTAL GANADO: </b> {{ number_format($planilla ? $planilla->Total_Ganado : $payment->paymentschedulesdetail->partial_salary + $payment->paymentschedulesdetail->seniority_bonus_amount, 2, ',', '.') }} <br>
                </td>
            </tr>
            <tr>
                <td style="text-align: center"><b>DESCUENTOS</b></td>
                <td rowspan="3" valign="bottom" style="text-align: center"><b><small>SELLO Y FIRMA</small></b></td>
            </tr>
            <tr>
                <td>
                    <br>
                    <b>APORTE LABORAL AFP:</b> {{ number_format($planilla ? $planilla->Total_Aportes_Afp : $payment->paymentschedulesdetail->labor_total, 2, ',', '.') }} <br>
                    <b>RC IVA:</b> {{ number_format($planilla ? $planilla->RC_IVA : $payment->paymentschedulesdetail->rc_iva_amount, 2, ',', '.') }} <br>
                    <b>MULTAS:</b> {{ number_format($planilla ? $planilla->FsMultas : $payment->paymentschedulesdetail->faults_amount, 2, ',', '.') }} <br>
                    <b>TOTAL DESCUENTOS:</b> {{ number_format($planilla ? $planilla->Total_Descuento : $payment->paymentschedulesdetail->labor_total + $payment->paymentschedulesdetail->faults_amount + $payment->paymentschedulesdetail->rc_iva_amount, 2, ',', '.') }} <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <b>LÍQUIDO PAGABLE: </b> {{ $planilla ? $planilla->Liquido_Pagable.' '.$planilla->Literal : NumerosEnLetras::convertir(number_format($payment->paymentschedulesdetail->liquid_payable, 2, '.', ''), 'Bolivianos', true) }}
                    <br> <br>
                </td>
            </tr>
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