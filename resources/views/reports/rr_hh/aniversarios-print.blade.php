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
                    <small> MES DE {{ strtoupper($months[$month]) }}</small> <br>
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
                <th>APELLIDOS Y NOMBRES</th>
                <th>CARGO</th>
                <th>CÉDULA DE IDENTIDAD</th>
                <th>AFP</th>
                <th>FECHA NACIMIENTO</th>
                <th>INICIO DE CONTRATO</th>
                <th>FIN DE CONTRATO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            @endphp
            @forelse ($people as $item)
                @php
                    $contract = $item->contracts->last();
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->last_name }} {{ $item->first_name }}</td>
                    <td>
                        @if ($contract)
                            @if ($contract->cargo)
                                {{ $contract->cargo->Descripcion }}
                            @elseif ($contract->job)
                                {{ $contract->job->name }}
                            @else
                                No definido
                            @endif
                        @endif
                    </td>
                    <td>{{ $item->ci }}</td>
                    <td>{{ $item->afp_type->name }}</td>
                    <td>{{ date('d', strtotime($item->birthday)).'/'.$months[intval(date('m', strtotime($item->birthday)))].'/'.date('Y', strtotime($item->birthday)) }}</td>
                    <td>{{ $contract ? date('d', strtotime($contract->start)).'/'.$months[intval(date('m', strtotime($contract->start)))].'/'.date('Y', strtotime($contract->start)) : '' }}</td>
                    <td>
                        @if ($contract)
                            {{ $contract->finish ? date('d', strtotime($contract->finish)).'/'.$months[intval(date('m', strtotime($contract->finish)))].'/'.date('Y', strtotime($contract->finish)) : 'NO DEFINIDO' }}
                        @endif
                    </td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="9"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
