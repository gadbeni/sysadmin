<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hoja de ruta</title>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
</head>
<body>
    <table width="100%" style="margin-bottom: 3px">
        <tr>
            <td width="70px">
                <img src="{{ asset('images/icon.png') }}" width="68px" alt="logo">
            </td>
            <td align="right">
                <h3 style="margin: 0px; margin-bottom: 10px">GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI <br>
                
                </h3>
            </td>
        </tr>
    </table>
    <div style="margin-top: 20px"></div>
    @for ($i = 0; $i < 3; $i++)
    <div class="bordered" style="margin-top: 5px">
        <table width="100%">
            <tr>
                <td width="110px" height="50px">Fecha de salidad</td>
                <td width="100px">....../....../............</td>
                <td width="50px">Hora</td>
                <td width="80px">...... : ......</td>
                <td width="100px">Recibido por: </td>
                <td></td>
            </tr>
            <tr>
                <td>A</td>
                <td class="bordered" colspan="5" height="20px"></td>
            </tr>
            <tr>
                <td>DE</td>
                <td class="bordered" colspan="5" height="20px"></td>
            </tr>
            <tr>
                <td>REF</td>
                <td class="bordered" colspan="5" height="20px"></td>
            </tr>
            <tr>
                <td height="100px" colspan="6">
                    NOTA: <br>
                    _______________________________________________________________________________________<br>
                    _______________________________________________________________________________________<br>
                    _______________________________________________________________________________________<br>
                    _______________________________________________________________________________________<br>
                </td>
            </tr>
            <tr>
                <td width="110px" height="50px">Fecha de salidad</td>
                <td width="100px">....../....../............</td>
                <td width="50px">Plazo</td>
                <td width="100px">....../....../............</td>
                <td width="90px">Firma y sello</td>
                <td></td>
            </tr>
        </table>
    </div>
    @endfor

    {{-- Pie de página --}}
    <div style="position: fixed; bottom: 15px; right: -20px; text-align: right">
        {{-- @php
            $persona = \App\Models\Persona::where('user_id', Auth::user()->id)->first();
        @endphp --}}
        {{-- <p style="font-size: 12px">Fecha y hora de impresión: {{ date('d/m/Y H:i:s') }} <br>
            @if (!auth()->user()->hasRole('admin'))
                <small style="font-size: 11px">Por: {{ auth()->user()->name }}</small>
            @endif
        </p> --}}
    </div>

    <style>
        body{
            margin: 0px;
            padding: 0px
        }
        .bordered{
            border: 1px solid black
        }
    </style>
</body>
</html>