<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table width="100%" border="1">
        <thead>
            <tr>
                <td>planilla</td>
                <td>pago ID AFP</td>
                <td>FPC</td>
                <td>AFP</td>
                <td>Fecha de pago</td>
                <td>Pago ID CC</td>
                <td>Fecha de pago CC</td>
                <td>Nro de deposito CC</td>
                <td>GTC 11</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            {{-- {{ dd($item) }} --}}
            <tr>
                <td>{{ $item['planilla'] }}</td>
                <td>{{ $item['pago_id'] }}</td>
                <td>{{ $item['fpc_number'] }}</td>
                <td>{{ $item['afp'] }}</td>
                <td>{{ $item['fecha_pago'] }}</td>
                <td>{{ $item['pago_id_cc'] }}</td>
                <td>{{ $item['fecha_pago_cc'] }}</td>
                <td>{{ $item['nro_deposito_cc'] }}</td>
                <td>{{ $item['gtc11'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>