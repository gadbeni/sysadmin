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
                <th>PLANILLA</th>
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
            @if ($type_data == 1)
                @forelse ($paymentschedules_details as $item)
                    @php
                        // dd($item);
                    @endphp
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }}</td>
                        <td>{{ $item->contract->person->ci }}</td>
                        <td>{{ $item->contract->person->nua_cua }}</td>
                        <td>{{ $item->contract->direccion_administrativa->nombre }}</td>
                        <td>{{ str_pad($item->id, 6, "0", STR_PAD_LEFT).($item->aditional ? '-A' : '') }}</td>
                        <td>{{ $item->paymentschedule->period->name }}</td>
                        <td>{{ number_format($item->partial_salary + $item->seniority_bonus_amount, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->labor_total, 2, ',', '.') }}</td>
                        @php
                            $payroll_payments = $item->paymentschedule->payroll_payments->where('afp', $item->afp);
                        @endphp
                        <td>
                            @foreach ($payroll_payments->groupBy('date_payment_afp') as $date => $payment)
                                @if ($date)
                                    {{ date('d/m/Y', strtotime($date)) }} <br>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($payroll_payments as $payment)
                                @if ($payment->date_payment_afp)
                                    {{ $payment->fpc_number }} <br>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr>
                        <td colspan="11">No hay datos registrados</td>
                    </tr>
                @endforelse
            @else
                @forelse ($planillas as $item)
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
                @empty
                    <tr>
                        <td colspan="11">No hay datos registrados</td>
                    </tr>
                @endforelse
            @endif
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