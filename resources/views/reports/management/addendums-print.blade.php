@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de Adendas')

@section('content')
    <div class="content">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: -20px; margin-top: 10px">Reporte de Adendas</h2>
                    <small>
                         <br>
                        @php
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        {{-- <small>RECURSOS HUMANOS</small> <br> --}}
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </small>
                </td>
            </tr>
            <tr>
                <tr></tr>
            </tr>
        </table>
        <br><br>
        <table class="table-data" style="width: 100%;" border="1" cellspacing="0" cellpadding="5">
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
                    <th>Género</th>
                    <th>Cargo</th>
                    <th>Nivel</th>
                    <th>Sueldo</th>
                    <th>Duración contrato principal</th>
                    <th>Inicio de adenda</th>
                    <th>Fin de adenda</th>
                    <th>Monto adenda</th>
                    <th>Monto contrato adenda</th>
                    <th>Estado</th>
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @forelse ($addendums as $item)
                    @php
                        $salary = 0;
                        $total = 0;
                        if ($item->contract->cargo) {
                            $salary = $item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->Sueldo;
                        }
                        if($item->contract->job){
                            $salary = $item->contract->job->salary;
                        }

                        $contract_duration = contract_duration_calculate($item->contract->start, $item->contract->finish);
                        $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);

                        $adenda_duration = contract_duration_calculate($item->start, $item->finish);
                        $total_adenda = ($salary *$adenda_duration->months) + (number_format($salary /30, 5) *$adenda_duration->days);
                    @endphp
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->contract->direccion_administrativa ? $item->contract->direccion_administrativa->nombre : 'No definida' }}</td>
                        <td>{{ $item->contract->unidad_administrativa ? $item->contract->unidad_administrativa->nombre : '' }}</td>
                        <td>{{ $item->contract->code }}</td>
                        <td>{{ $item->contract->type->name }}</td>
                        <td>{{ $item->contract->person->first_name }} </td>
                        <td>{{ $item->contract->person->last_name }}</td>
                        <td>{{ $item->contract->person->ci }}</td>
                        <td>{{ $item->contract->person->gender }}</td>
                        <td>
                            @if ($item->contract->cargo)
                                {{ $item->contract->cargo->Descripcion }}
                            @elseif ($item->contract->job)
                                {{ $item->contract->job->name }}
                            @else
                                No definio
                            @endif
                        </td>
                        <td>
                            @if ($item->contract->cargo)
                                {{ $item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->NumNivel }}
                            @elseif ($item->contract->job)
                                {{ $item->contract->job->level }}
                            @else
                                No definido
                            @endif
                        </td>
                        <td>{{ number_format($salary, 2, ',', '.') }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->contract->start)) }} <br> {{ date('d/m/Y', strtotime($item->contract->finish))}}</td>
                        <td>{{ number_format($total, 2, ',', '.') }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->finish))}}</td>
                        <td>{{ number_format($total_adenda, 2, ',', '.') }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            @if ($item->user)
                                {{ $item->user->name }} <br>
                            @endif
                            <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                        </td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="19" class="text-center">No hay datos disponibles en la tabla</td>
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
    .table-data th{
        font-size: 9px !important
    }
    .table-data td{
        font-size: 10px !important
    }
    table, th, td {
        border-collapse: collapse;
    }
    @page {
        margin: 10mm 40mm 10mm 0mm;
    }
    @media print {
        .table-data th{
            font-size: 7px !important
        }
        .table-data td{
            font-size: 8px !important
        }
    }
</style>
@endsection