@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de cierres de bóveda')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    CIERRES DE BÓVEDA <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
                    {{-- <small>RECURSOS HUMANOS</small> <br> --}}
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
        <tr>
            <tr></tr>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>CERRADA POR</th>
                <th style="max-width:150px">OBSERVACIONES</th>
                <th>FECHA DE <br> CIERRE </th>
                <th class="text-right">MONTO (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
            @endphp
            @forelse ($closure as $item)
                @php
                    $total = 0;
                    foreach($item->details as $detail) {
                        $total += $detail->cash_value * $detail->quantity;
                    }
                @endphp
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->observations }} </td>
                    <td>{{ date('d', strtotime($item->created_at)).'/'.$months[intval(date('m', strtotime($item->created_at)))].'/'.date('Y', strtotime($item->created_at)) }} <br> <small>{{ date('H:i', strtotime($item->created_at)) }}</small> </td>
                    <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                </tr>
            @empty
                <tr>
                    <td colspan="13"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection