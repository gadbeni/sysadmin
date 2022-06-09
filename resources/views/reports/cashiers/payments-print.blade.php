@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de pagos realizados')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE PAGOS REALIZADOS <br>
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
                <th>N&deg;</th>
                <th>DIRECCIÓN ADMINISTRATIVA</th>
                <th>DETALLE</th>
                <th>CI</th>
                <th>TIPO</th>
                <th>PERIODO</th>
                <th>AFP</th>
                <th>FECHA PAGO</th>
                <th>CAJERO(A)</th>
                <th>OBSERVACIONES </th>
                <th class="text-right">MONTO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                $total = 0;
            @endphp
            @forelse ($payments as $item)
                @php
                    if(!$item->deleted_at){
                        $total += $item->amount;
                    }
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>
                        @if ($item->planilla)

                        @elseif($item->paymentschedulesdetail)
                            {{ $item->paymentschedulesdetail->paymentschedule->direccion_administrativa->nombre }}
                        @endif
                    </td>
                    <td @if($item->deleted_at) class="item-delete" @endif>{{ $item->description }}</td>
                    <td>
                        @if ($item->planilla)
                            {{ $item->planilla->CedulaIdentidad }}
                        @elseif($item->aguinaldo)
                            {{ $item->aguinaldo->ci }}
                        @elseif($item->stipend)
                            {{ $item->stipend->ci }}
                        @elseif($item->paymentschedulesdetail)
                            {{ $item->paymentschedulesdetail->contract->person->ci }}
                        @endif
                    </td>
                    <td>
                        @if ($item->planilla)

                        @elseif($item->paymentschedulesdetail)
                            {{ $item->paymentschedulesdetail->contract->type->name }}
                        @endif
                    </td>
                    <td>
                        @if ($item->planilla)
                            {{ $item->planilla->Periodo }}
                        @elseif($item->paymentschedulesdetail)
                            {{ $item->paymentschedulesdetail->paymentschedule->period->name }}
                        @endif
                    </td>
                    <td>
                        @if ($item->planilla)

                        @elseif($item->paymentschedulesdetail)
                            {{ $item->paymentschedulesdetail->contract->person->afp == 1 ? 'FUTURO' : 'PREVISIÓN' }}
                        @endif
                    </td>
                    <td>{{ date('d', strtotime($item->created_at)).'/'.$months[intval(date('m', strtotime($item->created_at)))].'/'.date('Y', strtotime($item->created_at)) }} <br> <small>{{ date('H:i', strtotime($item->created_at)) }}</small> </td>
                    <td>{{ $item->cashier->user->name }} </td>
                    <td style="max-width: 150px">
                        {{ $item->observations }}
                        @if ($item->deletes)
                            <br><b>Motivo de eliminación:</b><br>
                            {{  $item->deletes->observations }}
                        @endif
                    </td>
                    <td @if($item->deleted_at) class="item-delete" @endif style="text-align:right"><b>{{ number_format($item->amount, 2, ',', '.') }}</b></td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="8"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="7" class="text-right"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>

@endsection

@section('css')
    <style>
        .item-delete{
            text-decoration: line-through;
            color: red !important;
        }
    </style>
@endsection