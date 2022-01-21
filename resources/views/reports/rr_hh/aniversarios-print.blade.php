@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de aniversarios')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE ANIVERSARIOS <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
                    <small> MES DE {{ strtoupper($months[$mes]) }}</small> <br>
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
                <th>ITEM</th>
                <th>NIVEL</th>
                <th>APELLIDOS Y NOMBRES / CARGO</th>
                <th>CÉDULA DE IDENTIDAD</th>
                <th>EXP</th>
                <th>AFP</th>
                <th>FECHA INGRESO</th>
                <th>FECHA NACIMIENTO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($funcionarios as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->Nivel }}</td>
                    <td>{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} <br> <small>{{ $item->Cargo }}</small></td>
                    <td>{{ $item->CedulaIdentidad }}</td>
                    <td>{{ $item->Expedido }}</td>
                    <td>{{ $item->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                    <td>{{ date('d', strtotime($item->Fecha_Ingreso)).'/'.$months[intval(date('m', strtotime($item->Fecha_Ingreso)))].'/'.date('Y', strtotime($item->Fecha_Ingreso)) }}</td>
                    <td>{{ date('d', strtotime($item->fechanacimiento)).'/'.$months[intval(date('m', strtotime($item->fechanacimiento)))].'/'.date('Y', strtotime($item->fechanacimiento)) }}</td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="8"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
