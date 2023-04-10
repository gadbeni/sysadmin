@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de contrataciones')

@section('content')

    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE CONTRATACIONES <br>
                    @php
                        $months = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sept', 'oct', 'nov', 'dic'];
                    @endphp
                    <small> {{ strtoupper($months[$month]) }} de {{ $year }}</small> <br>
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
                <th colspan="15"><h3>ALTAS</h3></th>
            </tr>
            <tr>
                <th>N&deg;</th>
                {{-- <th>NIVEL</th> --}}
                <th>APELLIDOS Y NOMBRES / CARGO</th>
                <th>CÉDULA DE IDENTIDAD</th>
                <th>EXP</th>
                <th>NUA/CUA</th>
                <th>AFP</th>
                <th>NOVEDAD</th>
                <th>FECHA</th>
                <th>DÍAS TRAB.</th>
                <th>SUELDO MENSUAL</th>
                <th>SUELDO PARCIAL</th>
                <th>%</th>
                <th>BONO ANTIG.</th>
                <th>TOTAL GANADO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($funcionarios_ingreso as $item)
                @php
                    $paymentschedules = $item->paymentschedules_details->first();
                @endphp
                <tr>
                    <td>{{ $cont }}</td>
                    {{-- <td>{{ $item->Nivel }}</td> --}}
                    <td>
                        {{ $item->person->last_name }} {{ $item->person->first_name }} <br>
                        <small>
                            @if ($item->cargo)
                                {{ $item->cargo->Descripcion }}
                            @elseif ($item->job)
                                {{ $item->job->name }}
                            @else
                                No definido
                            @endif    
                        </small>
                    </td>
                    <td>{{ $item->person->ci }}</td>
                    <td></td>
                    <td>{{ $item->person->nua_cua }}</td>
                    <td>{{ $item->person->afp_type ? $item->person->afp_type->name : 'No defeinida' }}</td>
                    <td>I</td>
                    <td>{{ date('d', strtotime($item->start)).'/'.$months[intval(date('m', strtotime($item->start)))].'/'.date('Y', strtotime($item->start)) }}</td>
                    <td>{{ $item->Dias_Trabajado }}</td>
                    <td>
                        @php
                            $salary = 0;
                            if ($item->cargo){
                                $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                            }elseif ($item->job){
                                $salary = $item->job->salary;
                            }
                        @endphp
                        {{ number_format($salary, 2, ',', '.') }}
                    </td>
                    <td>{{ number_format($paymentschedules->partial_salary, 2, ',', '.') }}</td>
                    <td>{{ number_format($paymentschedules->seniority_bonus_percentage, 0, ',', '.') }}</td>
                    <td>{{ number_format($paymentschedules->seniority_bonus_amount, 2, ',', '.') }}</td>
                    <td>{{ number_format($paymentschedules->partial_salary + $paymentschedules->seniority_bonus_amount, 2, ',', '.') }}</td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="14"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br><br>

    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th colspan="15"><h3>BAJAS</h3></th>
            </tr>
            <tr>
                <th>N&deg;</th>
                {{-- <th>NIVEL</th> --}}
                <th>APELLIDOS Y NOMBRES / CARGO</th>
                <th>CÉDULA DE IDENTIDAD</th>
                <th>EXP</th>
                <th>NUA/CUA</th>
                <th>AFP</th>
                <th>NOVEDAD</th>
                <th>FECHA</th>
                <th>DÍAS TRAB.</th>
                <th>SUELDO MENSUAL</th>
                <th>SUELDO PARCIAL</th>
                <th>%</th>
                <th>BONO ANTIG.</th>
                <th>TOTAL GANADO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($funcionarios_egreso as $item)
                @php
                    $paymentschedules = $item->paymentschedules_details->first();
                @endphp
                <tr>
                    <tr>
                        <td>{{ $cont }}</td>
                        {{-- <td>{{ $item->Nivel }}</td> --}}
                        <td>
                            {{ $item->person->last_name }} {{ $item->person->first_name }} <br>
                            <small>
                                @if ($item->cargo)
                                    {{ $item->cargo->Descripcion }}
                                @elseif ($item->job)
                                    {{ $item->job->name }}
                                @else
                                    No definido
                                @endif    
                            </small>
                        </td>
                        <td>{{ $item->person->ci }}</td>
                        <td></td>
                        <td>{{ $item->person->nua_cua }}</td>
                        <td>{{ $item->person->afp_type ? $item->person->afp_type->name : 'No defeinida' }}</td>
                        <td>I</td>
                        <td>{{ date('d', strtotime($item->start)).'/'.$months[intval(date('m', strtotime($item->start)))].'/'.date('Y', strtotime($item->start)) }}</td>
                        <td>{{ $item->Dias_Trabajado }}</td>
                        <td>
                            @php
                                $salary = 0;
                                if ($item->cargo){
                                    $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                                }elseif ($item->job){
                                    $salary = $item->job->salary;
                                }
                            @endphp
                            {{ number_format($salary, 2, ',', '.') }}
                        </td>
                        <td>{{ number_format($paymentschedules->partial_salary, 2, ',', '.') }}</td>
                        <td>{{ number_format($paymentschedules->seniority_bonus_percentage, 0, ',', '.') }}</td>
                        <td>{{ number_format($paymentschedules->seniority_bonus_amount, 2, ',', '.') }}</td>
                        <td>{{ number_format($paymentschedules->partial_salary + $paymentschedules->seniority_bonus_amount, 2, ',', '.') }}</td>
                    </tr>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="14"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection