<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Entrega de Fondos</title>
    <style>
        body{
            margin: 0px;
            font-family: Arial, sans-serif;
            font-weight: 100
        }
        #watermark {
            position: fixed;
            opacity: 0.1;
            /** 
                Establece una posición en la página para tu imagen
                Esto debería centrarlo verticalmente
            **/
            top:   4cm;
            left:     5.5cm;

            /* Cambiar las dimensiones de la imagen */
            width:    8cm;
            height:   8cm;

            /* Tu marca de agua debe estar detrás de cada contenido */
            z-index:  -1000;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
            <td style="text-align: right">
                <h2>CAJAS - GOBERNACIÓN</h2>
                <h3 style="margin-bottom: 0px">ENTREGA DE FONDOS</h3>
            </td>
        </tr>
    </table>
    <hr>
    <div id="watermark">
        <img src="{{ asset('images/icon.png') }}" height="100%" width="100%" /> 
    </div>
    <table width="100%">
        <tr>
            <td width="60%">
                <table width="100%" cellpadding="3">
                    <tr>
                        <td width="120px"><b>ID</b></td>
                        <td width="50px">:</td>
                        <td style="border: 1px solid #ddd">{{ str_pad($cashier->id, 6, "0", STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td width="120px"><b>FECHA</b></td>
                        <td width="50px">:</td>
                        <td style="border: 1px solid #ddd">{{ $cashier->id }}</td>
                    </tr>
                    <tr>
                        <td width="120px"><b>CAJERO(A)</b></td>
                        <td width="50px">:</td>
                        <td style="border: 1px solid #ddd">{{ $cashier->user->name }}</td>
                    </tr>
                    <tr>
                        <td width="120px"><b>CONCEPTO</b></td>
                        <td width="50px">:</td>
                        <td style="border: 1px solid #ddd">Apertura de caja</td>
                    </tr>
                    <tr>
                        <td width="120px"><b>MONTO</b></td>
                        <td width="50px">:</td>
                        @php
                            $amount = 0;
                            foreach($cashier->movements as $movement){
                                if($movement->description == 'Monto de apertura de caja.'){
                                    $amount += $movement->amount;
                                }
                            }
                        @endphp
                        <td style="border: 1px solid #ddd">{{ number_format($amount, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td width="120px"><b>NOTA</b></td>
                        <td width="50px">:</td>
                        <td style="border: 1px solid #ddd">{{ $cashier->observations ?? 'Ninguna' }}</td>
                    </tr>
                </table>
            </td>
            <td width="40%">
                <div>
                    <p style="text-align: center"><b>RECIBIDO POR</b></p>
                    <br>
                    <p style="text-align: center">...................................................... <br> {{ $cashier->user->name }} <br> {{ $cashier->user->ci }} <br> <b>{{ $cashier->user->role->name }}</b> </p>
                </div>
                <br>
                <div>
                    <p style="text-align: center"><b>ENTREGADO POR</b></p>
                    <br>
                    <p style="text-align: center">...................................................... <br> {{ Auth::user()->name }} <br> {{ Auth::user()->ci }} <br> <b>{{ Auth::user()->role->name }}</b> </p>
                </div>
            </td>
        </tr>
    </table>
    <hr>
</body>
</html>