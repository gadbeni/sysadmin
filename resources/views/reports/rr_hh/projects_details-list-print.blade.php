@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de Ejecución de Programas/proyectos')

@section('content')
    <div class="content">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: 0px; margin-top: 5px">
                        REPORTE DE EJECUCIÓN DE PROGRAMAS/PROYECTOS <br>
                        {{-- <small>Periodo {{ $period->name }}</small> <br>
                        @php
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp --}}
                        {{-- <small>RECURSOS HUMANOS</small> <br> --}}
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </h2>
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
                    <th>NOMBRE</th>
                    <th>DIRECCIÓN ADMINSTRATIVA</th>
                    <th>TIPO</th>
                    <th>GESTIÓN</th>
                    <th>INVERSIÓN</th>
                    <th>MONTO EJECUTADO</th>
                    <th>PORCENTAJE</th>
                    
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                    $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    $amount_total = 0;
                    $expense_total = 0;
                @endphp
                @forelse ($program as $item)
                    @php
                        $expense_partial = 0;
                        foreach($item->contracts as $contract) {
                            foreach($contract->paymentschedules_details as $paymentschedules_detail){

                                $expense_partial += $paymentschedules_detail->partial_salary + $paymentschedules_detail->seniority_bonus_amount;
                            }
                        }
                        $amount_total += $item->amount;
                        $expense_total += $expense_partial;
                    @endphp
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->direccion_administrativa->nombre }}</td>
                        <td>{{ $item->procedure_type->name }}</td>
                        <td>{{ $item->year }}</td>
                        <td style="text-align: right">{{ number_format($item->amount, 2, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($expense_partial, 2, ',', '.') }}</td>
                        <td style="text-align: right"><span @if($item->amount < $expense_partial) style="color: red" @endif>{{ $item->amount > 0 ? number_format(($expense_partial *100) / $item->amount, 2, ',', '.') : 0 }}%</span></td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr>
                        <td colspan="9"><h4 class="text-center">No hay resultados</h4></td>
                    </tr>
                @endforelse
                <tr>
                    <td style="text-align: right" colspan="5"><b>TOTAL</b></td>
                    <td style="text-align: right"><b>{{ number_format($amount_total, 2, ',', '.') }}</b></td>
                    <td style="text-align: right"><b>{{ number_format($expense_total, 2, ',', '.') }}</b></td>
                    <td style="text-align: right"><b></b></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('css')
<style>
    .content {
            padding-left: 30px;
            padding-right: 30px;
        }
    th{
        font-size: 10px !important
    }
    td{
        font-size: 11px !important
    }
    table, th, td {
        border-collapse: collapse;
    }
    @media print{
            @page {
                size: landscape;
            }
            .content {
                margin-left: 25px;
                margin-right: -20px;
            }
        }
</style>
@endsection