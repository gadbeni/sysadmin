@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de Contratos')

@section('content')
    <div class="content">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: -20px; margin-top: 10px">Reporte de Contratos</h2>
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
                    <th>NUA/CUA</th>
                    <th>Cargo</th>
                    <th>Nivel</th>
                    <th>Sueldo</th>
                    <th>Inicio contrato</th>
                    <th>Fin contrato</th>
                    <th>Fin adenda</th>
                    <th>Duración contrato</th>
                    <th>Duración contrato + adenda</th>
                    <th>Monto contrato</th>
                    <th>Monto contrato + adenda</th>
                    <th>Programa/Proyecto</th>
                    <th>Cat. prog.</th>
                    <th>Estado</th>
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @forelse ($contracts as $item)
                    @php
                        $salary = 0;
                        $total = 0;
                        $duracion = 'Indefinido';
                        if ($item->cargo) {
                            $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                        }
                        if($item->job){
                            $salary = $item->job->salary;
                        }

                        // Calcular finalización de contrato en caso de tener adenda
                        if($item->addendums->count() > 0) {
                            $contract_finish = date('Y-m-d', strtotime($item->addendums->first()->start." -1 days"));
                        } else {
                            $contract_finish = $item->finish;
                        }

                        $duracion_adenda = '';
                        $total_adenda = '';
                        if($item->start && $contract_finish){
                            $contract_duration = contract_duration_calculate($item->start, $contract_finish);
                            $duracion = ($contract_duration->months *30) + $contract_duration->days;
                            $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                            if($contract_finish != $item->finish){
                                $contract_total_duration = contract_duration_calculate($item->start, $item->finish);
                                $duracion_adenda = ($contract_total_duration->months *30) + $contract_total_duration->days;
                                $total_adenda = ($salary *$contract_total_duration->months) + (number_format($salary /30, 5) *$contract_total_duration->days);
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->nombre : 'No definida' }}</td>
                        <td>{{ $item->unidad_administrativa ? $item->unidad_administrativa->nombre : '' }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->type->name }}</td>
                        <td>{{ $item->person->first_name }} </td>
                        <td>{{ $item->person->last_name }}</td>
                        <td>{{ $item->person->ci }}</td>
                        <td>{{ $item->person->gender }}</td>
                        <td>{{ $item->person->nua_cua }}</td>
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
                        <td>{{ number_format($salary, 2, ',', '.') }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                        <td>{{ $contract_finish ? date('d/m/Y', strtotime($contract_finish)) : '' }}</td>
                        <td>{{ $contract_finish != $item->finish ? date('d/m/Y', strtotime($item->finish)) : '' }}</td>
                        <td>{{ $duracion }}</td>
                        <td>{{ $duracion_adenda }}</td>
                        <td>{{ $total ? number_format($total, 2, ',', '.') : 'No definido' }}</td>
                        <td>{{ $total_adenda ? number_format($total_adenda, 2, ',', '.') : '' }}</td>
                        <td>{{ $item->program ? $item->program->name : 'No definido' }}</td>
                        <td>{{ $item->program ? $item->program->programatic_category : 'No definida' }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                        </td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="24">No hay datos disponibles en la tabla</td>
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