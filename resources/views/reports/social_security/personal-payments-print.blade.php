@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de pagos al seguro social')

@section('content')
    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE INDIVIDUAL DE PAGOS AL SEGURO SOCIAL <br>
                    <small>DIRECCIÓN DE BIENESTAR LABORAL Y PREVISIÓN SOCIAL</small> <br>
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
                <th>N&deg;</th>
                <th>NOMBRE COMPLETO</th>
                <th>CARNET DE<br>IDENTIDAD</th>
                <th>NUA/CUA</th>
                <th>DIRECCIÓN ADMINISTRATIVA</th>
                <th>ID PLANILLA</th>
                <th>PERIODO</th>
                <th>TOTAL<br>GANADO</th>
                <th>APORTE AFP</th>
                <th>FECHA DE PAGO</th>
                <th>N&deg; DE FPC</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($planillas as $item)
            {{-- {{ dd($item) }} --}}
            <tr>
                <td>{{ $cont }}</td>
                <td>{{ $item->empleado }}</td>
                <td>{{ $item->ci }}</td>
                <td>{{ $item->nua_cua }}</td>
                <td>{{ $item->direccion_administrativa }}</td>
                <td>{{ $item->planilla_procesada }}</td>
                <td>{{ $item->periodo }}</td>
                <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                <td>{{ number_format($item->total_afp, 2, ',', '.') }}</td>
                <td>
                    @foreach ($item->payments as $payment)
                        {{ $payment->date_payment_afp }}
                    @endforeach
                </td>
                <td>
                    @foreach ($item->payments as $payment)
                        {{ $payment->fpc_number }}
                    @endforeach
                </td>
            </tr>
            @php
                $cont++;
            @endphp
            @endforeach
        </tbody>
    </table>
@endsection

@section('css')
    <style>
        th{
            font-size: 11px
        }
        td{
            font-size: 12px
        }
    </style>
@endsection