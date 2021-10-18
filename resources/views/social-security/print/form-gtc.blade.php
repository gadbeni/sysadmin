<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Formulario GTC-11</title>

    <style>
        body{
            margin: 0px auto;
            /* font-family: Arial, sans-serif; */
            font-weight: 100;
            max-width: 670px;
            padding-top: 12px
        }

        table, th, td {
            border: 1px solid black;
        }

        @media print{
            body{
                color: white
            }
            table, th, td {
                border: 1px solid white;
            }
            .text-print{
                color: black
            }
            .hide-print{
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print" style="text-align: right; padding: 10px 0px">
        <button class="btn-print" onclick="window.close()">Cerrar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()"> Imprimir <i class="fa fa-print"></i></button>
    </div>
    <table width="100%" cellspacing="0" style="padding: 3px; text-align: center">
        <tr>
            <td colspan="4" style="height: 35px"><h3 style="margin: 0px">CAJA DE SALUD CORDES</h3></td>
        </tr>
        <tr>
            <td style="width: 140px; height: 30px; font-size: 12px">FORM GTC-11 <br> Valorado Bs. 5.- </td>
            <td colspan="2"><h5 style="margin: 0px">RESUMEN MENSUAL DE LA PLANILLA DE SALARIOS</h5></td>
            <td style="width: 150px">Nro:</td>
        </tr>
        <tr>
            <td colspan="4" style="height: 3px"></td>
        </tr>
        <tr>
            <td colspan="4" style="height: 10px;font-size: 13px"><h4 style="margin: 0px">PARA USO DEL EMPLEADOR</h4></td>
        </tr>
        <tr style="font-size: 9px">
            <td colspan="2"><b>NOMBRE O RAZÓN SOCIAL DEL EMPLEADOR</b></td>
            <td width="100px"><b>N&deg; DE NIT</b></td>
            <td><b>N&deg; PATRONAL</b></td>
        </tr>
        <tr>
            <td class="text-print" colspan="2">{{ $dependence->name }}</td>
            <td class="text-print">{{ $dependence->nit }}</td>
            <td class="text-print">{{ $dependence->code }}</td>
        </tr>
        <tr style="font-size: 9px">
            <td><b>REGIONAL</b></td>
            <td><b>DOMICILIO</b></td>
            <td><b>TELÉFONO</b></td>
            <td><b>MES Y AÑO DE PLANILLA</b></td>
        </tr>
        <tr>
            <td class="text-print">{{ $dependence->location }}</td>
            <td class="text-print">{{ $dependence->address }}</td>
            <td class="text-print">{{ $dependence->phone }}</td>
            @php
                $month = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            @endphp
            <td class="text-print">{{ count($planillas) > 0 ? $month[intval($planillas[0]->month)].' '.$planillas[0]->year : '' }}</td>
        </tr>
        <tr>
            <td colspan="4" style="border: 0px">
                <table width="100%" cellspacing="0" style="text-align: center">
                    <tr style="font-size: 13px">
                        <td>N&deg; TRAB</td>
                        <td colspan="2">TOTAL SALARIOS DEL MES EN BS.</td>
                        <td style="width: 60px">TASA</td>
                        <td style="width: 148px">COTIZACIÓN</td>
                    </tr>
                    @php
                        $total_ganado = $planillas->sum('Total_Ganado');
                    @endphp
                    <tr style="height: 80px">
                        <td style="width: 70px" class="text-print">{{ count($planillas) }}</td>
                        <td class="text-print">{{ count($planillas) > 0 ? 'Planilla de '.$planillas[0]->tipo_planilla : '' }}</td>
                        <td style="width: 120px" class="text-print">{{ number_format($total_ganado, 2, ',', '.') }}</td>
                        <td class="text-print">10%</td>
                        <td class="text-print">{{ number_format($total_ganado * 0.1, 2, ',', '.') }}</td>
                    </tr>
                    <tr style="font-size: 12px; text-align: center">
                        <td colspan="5">TOTAL COTIZACIONES A PAGAR</td>
                    </tr>
                    <tr style="font-size: 9px">
                        <td colspan="5">Como representante legal de la Empresa declaro que todos los datos consignados en el presente resumen, son y representan el total de planillas pagadas en el mes</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="border: 0px">
                <table width="100%" cellspacing="0" style="text-align: center">
                    <tr style="height: 60px">
                        <td width="30%" class="text-print">{{ $dependence->legal_representative_name }}</td>
                        <td width="30%" style="text-align: right; font-size: 13px" class="text-print">
                            <br><br>
                            {{ $dependence->legal_representative_dni }}&nbsp;&nbsp;&nbsp;
                        </td>
                        <td width="30%" class="text-print"><br>{{ date('d/m/Y') }}</td>
                    </tr>
                    <tr style="font-size: 10px">
                        <td><b>Nombre del representante legal</b></td>
                        <td><b>Firma y sello de la Empresa</b></td>
                        <td><b>Lugar y fecha</b></td>
                    </tr>
                </table>
            </td>
        </tr>
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