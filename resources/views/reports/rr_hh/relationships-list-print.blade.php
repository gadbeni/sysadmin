@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de Parentescos')

@section('content')
    <div class="content">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: 0px; margin-top: 5px">
                        REPORTE DE PARENTESCOS <br>
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
                    <th>APELLIDOS</th>
                    <th>NOMBRES</th>
                    <th>CÉDULA DE IDENTIDAD</th>
                    <th>INAMOVILIDAD</th>
                    <th>PLANILLA</th>
                    <th>CARGO</th>
                    <th>DIRECCIÓN ADMINSTRATIVA</th>
                    {{-- <th>AFP</th>
                    <th>FECHA INGRESO</th>
                    <th>FECHA NACIMIENTO</th> --}}
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                    $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                @endphp
                @forelse ($relationships as $item)
                    @foreach ($item as $person)
                        @php
                            // dd($person);
                        @endphp
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $person->last_name }}</td>
                            <td>{{ $person->first_name }}</td>
                            <td>{{ $person->ci }}</td>
                            <td>
                                @php
                                    $irremovability = $person->irremovabilities->where('start', '<=', date('Y-m-d'))->whereRaw('(finish >= "'.date('Y-m-d').'" or finish is null')->first();
                                @endphp
                                {{ $irremovability ? 'Sí' : 'No' }}
                            </td>
                            <td>{{ $person->contracts->first()->type->name }}</td>
                            <td>
                                @if ($person->contracts->first()->cargo)
                                    {{ $person->contracts->first()->cargo->Descripcion }}
                                @elseif ($person->contracts->first()->job)
                                    {{ $person->contracts->first()->job->name }}
                                @else
                                    No definido
                                @endif
                            </td>
                            <td>{{ $person->contracts->first()->direccion_administrativa->nombre }}</td>
                        </tr>
                        @php
                            $cont++;
                        @endphp
                    @endforeach
                @empty
                    <tr>
                        <td colspan="9"><h4 class="text-center">No hay resultados</h4></td>
                    </tr>
                @endforelse
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