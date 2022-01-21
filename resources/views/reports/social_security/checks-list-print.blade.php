@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de cheques')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE CHEQUES<br>
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
    
    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Dirección administrativa</th>
                <th>Tipo de planilla</th>
                <th>Periodo</th>
                <th>N&deg; de planilla</th>
                <th>AFP</th>
                <th>N&deg; personas</th>
                <th>Total ganado</th>
                <th>Nro Cheque</th>
                <th>Fecha de impresión</th>
                <th>Monto</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total = 0;
            @endphp
            @forelse ($data as $item)
                @if ($item->planilla)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->planilla->Direccion_Administrativa }}</td>
                        <td>{{ $item->planilla->tipo_planilla }}</td>
                        <td>{{ $item->planilla->Periodo }}</td>
                        <td>{{ $item->planilla->idPlanillaprocesada }}</td>
                        <td>{{ $item->planilla->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                        <td>{{ $item->planilla->NumPersonas }}</td>
                        <td style="text-align: right">{{ number_format($item->planilla->Monto, 2, ',', '.') }}</td>
                        <td>{{ $item->number }}</td>
                        <td>{{ date('d/M/Y', strtotime($item->date_print)) }}</td>
                        <td>
                            @switch($item->status)
                                @case(1)
                                    <label class="label label-info">Pendiente</label>
                                    @break
                                @case(2)
                                    <label class="label label-success">Pagado</label>
                                    @break
                                @case(3)
                                    <label class="label label-warning">Vencido</label>
                                    @break
                                @default
                                <label class="label label-danger">Anulado</label>
                            @endswitch
                        </td>
                        <td style="text-align: right">{{ number_format($item->amount, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $cont++;
                        $total += $item->amount;
                    @endphp
                @endif
            @empty
                <tr>
                    <td colspan="12"><h5 class="text-center">No se encontraron registros</h5></td>
                </tr>
            @endforelse
            <tr>
                <td colspan="11"><b>TOTAL</b></td>
                <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>

@endsection

