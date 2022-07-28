@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de pagos al seguro social')

@section('content')
    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE PAGOS DE CHEQUES AL SEGURO SOCIAL <br>
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
                <th>N°</th>
                <th>PLANILLA</th>
                <th>DIRECCIÓN ADMINISTRATIVA</th>
                <th>TIPO DE PLANILLA</th>
                <th>PERIODO</th>
                <th>N&deg; PERSONAS</th>
                <th>TOTAL GANADO</th>
                <th>N&deg; FPC</th>
                <th>N&deg; GTC-11</th>
                <th>N&deg; DEPOSITO</th>
                <th>USUARIO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            @endphp
            @forelse ($payroll_payment as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ str_pad($item->paymentschedule->id, 6, "0", STR_PAD_LEFT).($item->paymentschedule->aditional ? '-A' : '') }}</td>
                    <td>{{ $item->paymentschedule->direccion_administrativa->nombre }}</td>
                    <td>{{ $item->paymentschedule->procedure_type->name }}</td>
                    <td>{{ $item->paymentschedule->period->name }}</td>
                    <td>{{ $item->paymentschedule->details->where('afp', $item->afp)->count() }}</td>
                    <td style="text-align: right">{{ number_format($item->paymentschedule->details->where('afp', $item->afp)->sum('partial_salary') +$item->paymentschedule->details->where('afp', $item->afp)->sum('seniority_bonus_amount'), 2, ',', '.') }}</td>
                    <td>
                        @if($item->fpc_number)
                            @php
                                $date = $item->date_payment_afp ? date('d/m/Y', strtotime($item->date_payment_afp)) : 'Pendiente';
                            @endphp
                            {{ $item->fpc_number }} <br> {{ $date }}
                        @endif
                    </td>
                    <td>{{ $item->gtc_number }}</td>
                    <td>
                        @if($item->deposit_number)
                            @php
                                $date = $item->date_payment_cc ? date('d/m/Y', strtotime($item->date_payment_cc)) : 'Pendiente';
                            @endphp
                            {{ $item->deposit_number }} <br> {{ $date }}
                        @endif
                    </td>
                    <td>{{ $item->user->name }} <br> {{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td class="text-center" colspan="11">No se encontraron resulatados</td>
                </tr>
            @endforelse
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
        table, th, td {
            border-collapse: collapse;
        }
    </style>
@endsection