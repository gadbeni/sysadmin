@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de pagos de planillas manuales')

@section('content')
    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE PAGOS DE PLANILLAS MANUALES <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
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
                <th style="text-align: center" colspan="9">DATOS GENERALES</th>
                <th style="text-align: center; background-color: #2980B9 !important; color: #fff" colspan="5">ADMINISTRADORES DE FONDOS DE PENSIONES</th>
                <th style="text-align: center; background-color: #16A085!important; color: #fff" colspan="8">CAJA DE SALUD CORDES</th>
            </tr>
            <tr>
                <th>N&deg;</th>
                <th>HR/NCI</th>
                <th>PERIODO</th>
                <th>DIRECCIÓN ADMINISTRATIVA</th>
                <th>TIPO DE PLANILLA</th>
                <th>CÓDIGO DE PLANILLA</th>
                <th>N&deg; DE PERSONAS</th>
                <th>AFP</th>
                <th style="text-align: right">TOTAL GANADO</th>
                <th style="text-align: right">APORTE AFP</th>
                <th>N&deg; FCP</th>
                <th>FECHA DE PAGO AFP</th>
                <th>ID PAGO</th>
                <th>MULTA AFP</th>
                <th style="text-align: right">APORTE CC</th>
                <th>N&deg; DE CHEQUE</th>
                <th>N&deg; DE DEPOSITO</th>
                <th>FECHA DE PAGO CC</th>
                <th>F GTC-11</th>
                <th>ID PAGO</th>
                <th>N&deg; DE RECIBO</th>
                <th>MULTA CC</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $monts = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                // dd($payments);
            @endphp
            @forelse ($payments as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td></td>
                    <td>{{ $monts[intval($item->month)].'/'.$item->year }}</td>
                    <td>{{ $item->direccion_administrativa->nombre }}</td>
                    <td>{{ $item->tipo_planilla_id == 1 ? 'Funcionamiento' : 'Inversión' }}</td>
                    <td>{{ $item->codigo_planilla }}</td>
                    <td style="text-align: right">{{ $item->people }}</td>
                    <td>{{ $item->afp_id == 1 ? 'Futuro' : 'Previsión' }}</td>
                    <td style="text-align: right">{{ number_format($item->total, 2, ',', '.') }}</td>
                    <td></td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ $payment->fpc_number }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ date('d/M/Y', strtotime($payment->date_payment_afp)) }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ $payment->payment_id }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ number_format($payment->penalty_payment, 2, ',', '.') }} <br>
                        @endforeach
                    </td>
                    <td style="text-align: right">{{ number_format($item->total *0.1, 2, ',', '.') }}</td>
                    <td>
                        @foreach ($item->checks as $check)
                            @if (strpos(strtoupper($check->beneficiary->type->name), 'SALUD') !== false)
                                {{ $check->number }} <br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ $payment->deposit_number }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ date('d/M/Y', strtotime($payment->date_payment_cc)) }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ $payment->gtc_number }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ $payment->check_id }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ $payment->recipe_number }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($item->payments as $payment)
                            {{ number_format($payment->penalty_check, 2, ',', '.') }} <br>
                        @endforeach
                    </td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="22"><h4 class="text-center">No hay resultados</h4></td>
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