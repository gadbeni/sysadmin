<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Imprimir Ticktes</title>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            flex-wrap: wrap
        }
        .card{
            width: 8cm;
            height: 3cm;
            border-radius: 5px;
            border: 1px solid #000;
            box-shadow: 0px 0px 2px #000;
            margin: 1px;
            padding: 10px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background-image: url('{{ asset("images/card.jpeg") }}');
            /* background-position: center; */
            background-repeat: no-repeat;
            background-size: 7cm 3.5cm;
        }
        .card span{
            font-size: 45px;
            font-weight: bold;
            writing-mode: vertical-lr;
        }
    </style>
</head>
<body>
    @php
        $cont = 1
    @endphp
    @for ($i = $start; $i <= $finish; $i++)
        <div class="card" @if($cont % 14 == 0) style="margin-bottom: 20px" @endif>
            <span><small style="font-size: 15px">N&deg;</small> {{ str_pad($i, 3, "0", STR_PAD_LEFT) }}</span>
        </div>
        @php
            $cont++;
        @endphp
    @endfor
</body>
</html>