@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de Contratos')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE CONTRATOS <br>
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
                <th>Dirección administrativa</th>
                <th>Unidad administrativa</th>
                <th>Código</th>
                <th>Tipo</th>
                <th>Nombre(s)</th>
                <th>Apellidos</th>
                <th>CI</th>
                <th>Cargo</th>
                <th>Nivel</th>
                <th>Sueldo</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Programa</th>
                <th>Categoría programática</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($contracts as $item)
            {{-- {{ dd($item) }} --}}
            <tr>
                <td>{{ $cont }}</td>
                <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->NOMBRE : 'No definida' }}</td>
                <td>{{ $item->unidad_administrativa ? $item->unidad_administrativa->Nombre : '' }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->type->name }}</td>
                <td>{{ $item->person->first_name }} </td>
                <td>{{ $item->person->last_name }}</td>
                <td>{{ $item->person->ci }}</td>
                <td>
                    @if ($item->cargo)
                        {{ $item->cargo->Descripcion }}
                    @elseif ($item->job)
                        {{ $item->job->name }}
                    @else
                        No definio
                    @endif
                </td>
                <td>
                    @if ($item->cargo)
                        {{ $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->NumNivel }}
                    @elseif ($item->job)
                        {{ $item->job->level }}
                    @else
                        No definido
                    @endif
                </td>
                <td>
                    @if ($item->cargo)
                        {{ number_format($item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.') }}
                    @elseif ($item->job)
                        {{ number_format($item->job->salary, 2, ',', '.') }}
                    @else
                        0.00
                    @endif
                </td>
                <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->finish)) }}</td>
                <td>{{ $item->program->name }}</td>
                <td>{{ $item->program->programatic_category }}</td>
                <td>{{ $item->status }}</td>
            </tr>
            @php
                $cont++;
            @endphp
            @empty
                <tr class="odd">
                    <td valign="top" colspan="16" class="text-center">No hay datos disponibles en la tabla</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('css')
<style>
    th{
        font-size: 8px !important
    }
    td{
        font-size: 10px !important
    }
</style>
@endsection