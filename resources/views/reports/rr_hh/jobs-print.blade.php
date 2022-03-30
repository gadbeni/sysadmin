@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de declaraciones juradas')

@section('content')
    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE CARGOS <br>
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
                <th>CARGO</th>
                <th>SUELDO</th>
                <th>DIRECCIÓN ADMINISTRATIVA</th>
                <th>CÓDIGO</th>
                <th>FUNCIONARIO</th>
                <th>INICIO</th>
                <th>FIN</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jobs as $item)
                <tr>
                    <td>{{ $item->item }}</td>
                    <td>{{ $item->name }}</td>
                    <td style="text-align: right">{{ number_format($item->salary, 2, ',', '.') }}</td>
                    <td>{{ $item->direccion_administrativa->NOMBRE }}</td>
                    <td>{!! $item->contract ? $item->contract->code : '<b style="color: red">Acéfalo</b>' !!}</td>
                    <td>{{ $item->contract ? $item->contract->person->last_name.' '.$item->contract->person->first_name : '' }}</td>
                    <td>{{ $item->contract ? date('d/m/Y', strtotime($item->contract->start)) : '' }}</td>
                    <td>{{ $item->contract ? $item->contract->finish ? date('d/m/Y', strtotime($item->contract->finish)) : '' : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection