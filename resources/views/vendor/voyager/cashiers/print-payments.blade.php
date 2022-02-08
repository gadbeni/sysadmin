<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagos de caja</title>
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
            max-width: 740px;
        }
        .table td, .table th{
            padding: 2px;
            border-bottom: 1px solid #000;
        }
        #watermark {
            margin-top: 200px;
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
    {{-- @for ($i = 0; $i < 2; $i++) --}}
    <div style="height: 45vh">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h3 style="margin-bottom: 0px; margin-top: 5px">CAJAS - GOBERNACIÓN<br> <small>PAGOS REALIZADOS {{ $cashier->created_at->format('d/m/Y') }}</small> </h3>
                    <br>
                    <small>Impreso por {{ Auth::user()->name }} - {{ date('d/m/Y H:i:s') }}</small>
                </td>
            </tr>
        </table>
        <hr style="margin: 0px">
        <div id="watermark">
            <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
        </div>
        {{-- {{ dd($cashier) }} --}}
        <table class="table" width="100%" cellspacing="0" style="font-size: 13px">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>Nombre completo</th>
                    <th>CI</th>
                    <th>Planilla</th>
                    <th>Fecha de pago</th>
                    <th style="text-align: right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 0;
                    $total = 0;
                @endphp
                @foreach ($cashier->payments as $payment)
                    @php
                        $data = DB::connection('mysqlgobe')->table('planillahaberes')->where('id', $payment->planilla_haber_id)->first();
                        $cont++;
                        if(!$payment->deleted_at){
                            $total += $payment->amount;
                        }
                        $months = [
                            '01' => 'Enero',
                            '02' => 'Febrero',
                            '03' => 'Marzo',
                            '04' => 'Abril',
                            '05' => 'Mayo',
                            '06' => 'Junio',
                            '07' => 'Julio',
                            '08' => 'Agosto',
                            '09' => 'Septiembre',
                            '10' => 'Octubre',
                            '11' => 'Noviembre',
                            '12' => 'Diciembre'
                        ];
                        // dd($data);
                    @endphp
                    <tr @if($payment->deleted_at) style="text-decoration:line-through;color: red" @endif>
                        <td>{{ $cont }}</td>
                        <td>
                            @if ($payment->planilla_haber_id)
                                {{ $data->Nombre_Empleado  }} <br> <small>{{ $data->Direccion_Administrativa }}</small>
                            @elseif($payment->aguinaldo_id)
                                {{ $payment->aguinaldo->funcionario }}
                            @elseif($payment->stipend_id)
                                {{ $payment->stipend->funcionario }}
                            @endif
                            <br>
                            @if ($payment->deleted_at)
                                <label class="label label-danger">Anulado</label>
                            @endif
                        </td>
                        <td>
                            @if ($payment->planilla_haber_id)
                                {{ $data->CedulaIdentidad }}
                            @elseif($payment->aguinaldo_id)
                                {{ $payment->aguinaldo->ci }}
                            @elseif($payment->stipend_id)
                                {{ $payment->stipend->ci }}
                            @endif
                        </td>
                        <td>
                            @if ($payment->planilla_haber_id)
                                {{ $months[$data->Mes].'/'.$data->Anio }}
                            @elseif($payment->aguinaldo_id)
                                Aguinaldo
                            @elseif($payment->stipend_id)
                                Estipendio
                            @endif
                        </td>
                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align: right">{{ number_format($payment->amount, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right"><b>TOTAL</b></td>
                    <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                </tr>
            </tbody>
        </table>
        <div style="display: flex; justify-content: space-around; margin-top: 50px">
            <div>
                <p style="text-align: center; margin-top: 0px"><b><small>EMITIDO POR</small></b></p>
                <br>
                <p style="text-align: center">.............................................. <br> <small>{{ strtoupper($cashier->user->name) }}</small> <br> <small>{{ $cashier->user->ci }}</small> <br> <b>{{ strtoupper($cashier->user->role->display_name) }}</b> </p>
            </div>
            <div>
                <p style="text-align: center; margin-top: 0px"><b><small>APROBADO POR</small></b></p>
                <br>
                <p style="text-align: center">.............................................. <br> <small>{{ strtoupper(Auth::user()->name) }}</small> <br> <small>{{ Auth::user()->ci }}</small> <br> <b>{{ strtoupper(Auth::user()->role->display_name) }}</b> </p>
            </div>
        </div>
    </div>
    {{-- @endfor --}}

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