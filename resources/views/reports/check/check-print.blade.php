@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de caja')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE CHEQUES <br>
                    
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
                    <th style="text-align: right">N&deg;</th>
                    <th>TIPO</th>
                    <th>NRO CHEQUE</th>
                    <th>BENEFICIARIO</th>
                    <th style="text-align: right">NRO<br> MEM. </th>
                    <th style="text-align: right">NRO<br> PREV. </th>
                    <th style="text-align: right">NRO<br> DEV. </th>
                    <th style="text-align: right">FECHA<br> INGRESO. </th>                    
                    <th style="text-align: right">FECHA<br> CHEQUE. </th>                    
                    <th style="text-align: right">MONTO<br> (*BS). </th>                                        
                </tr>
            </thead>
            <tbody>
                        @php
                            $cont=1;
                            $total =0;
                        @endphp
                        @forelse ($detalle as $item)
                               
                            @if(json_decode($item->resumen)->deposito >= $inicio && json_decode($item->resumen)->deposito <= $fin )
                                @php
                                    $total+=json_decode($item->resumen)->monto;
                                @endphp
                                <tr>
                                    <td style="text-align: right">{{ $cont }}</td>
                                    <td>{{ $item->name}}</td>
                                    <td>{{ json_decode($item->resumen)->nrocheque }}</td>
                                    <td>{{ json_decode($item->resumen)->resumen }}</td>
                                    <td style="text-align: right">{{ json_decode($item->resumen)->nromemo }}</td>
                                    <td style="text-align: right">{{ json_decode($item->resumen)->nroprev }}</td>
                                    <td style="text-align: right">{{ $inicio }}</td>
                                    <td style="text-align: right">{{ json_decode($item->resumen)->deposito}}</td>
                                    <td style="text-align: right">{{ \Carbon\Carbon::parse(json_decode($item->resumen)->fechacheque)->format('d/m/Y') }}</td>
                                    <td style="text-align: right">{{ json_decode($item->resumen)->monto }}</td>

                                </tr>                            
                                @php
                                    $cont++;
                                @endphp
                            @endif
                        @empty
                            <tr>
                                <td colspan="13"><h4 class="text-center">No hay resultados</h4></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="9" style="text-align: right"><b>TOTAL</b></td>
                            <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                        </tr>
            </tbody>
    </table>

@endsection