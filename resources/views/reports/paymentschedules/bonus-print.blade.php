@extends('layouts.template-print-alt', compact('type_render'))

@section('page_title', 'Planilla de aguinaldos')

@section('content')
    <div class="content">
        <div class="header" >
            <table width="100%">
                <tr>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    @endphp
                    <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
                    <td style="text-align: right">
                        <h3 style="margin: 0px">PLANILLA DE AGUINALDOS AL PERSONAL DEPENDIENTE GAD-BENI</h3>
                        <span>CORRESPONDIENTE A LA GESTIÓN {{ $year }}</span> <br>
                        @if ($procedure_type_id)
                            <b>PLANILLA {{ strtoupper(App\Models\ProcedureType::find($procedure_type_id)->name) }}</b> <br>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px">
            <table width="100%" class="table-details" border="1" cellpadding="2" cellspacing="0">
                <thead>
                    <tr>
                        <th>N&deg;</th>
                        <th>PLANILLA</th>
                        <th>NOMBRE COMPLETO</th>
                        <th>CI</th>
                        <th>INICIO</th>
                        <th>FIN</th>
                        <th>DÍAS<br>TRAB.</th>
                        <th>MESES</th>
                        <th>SUELDO<br>PROMEDIO</th>
                        <th>AGUINALDO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $total = 0;
                    @endphp
                    @foreach ($bonuses as $item)
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $item->procedure_type->name }}</td>
                            <td>
                                {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                                @if ($item->contract->cargo)
                                    <b>{{ $item->contract->cargo->Descripcion }}</b>
                                @elseif($item->contract->job)
                                    <b>{{ $item->contract->job->name }}</b>
                                @endif
                            </td>
                            <td>{{ $item->contract->person->ci }}</td>
                            <td>
                                @if ($item->start)
                                    {{ date('d-m-Y', strtotime($item->start)) }}
                                @endif
                            </td>
                            <td>{{ $item->finish ? date('d-m-Y', strtotime($item->finish)) : '31-12-'.$year }}</td>
                            <td style="text-align:center">{{ $item->days }}</td>
                            <td>
                                <table class="table">
                                    <tr>
                                        <td>{{ number_format($item->partial_salary_1 + $item->seniority_bonus_1, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->partial_salary_2 + $item->seniority_bonus_2, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->partial_salary_3 + $item->seniority_bonus_3, 2, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="text-align:center">
                                @php
                                    $promedio = ($item->partial_salary_1 + $item->seniority_bonus_1 + $item->partial_salary_2 + $item->seniority_bonus_2 + $item->partial_salary_3 + $item->seniority_bonus_3) /3;
                                @endphp
                                {{ number_format($promedio, 2, ',', '.') }}
                            </td>
                            <td style="text-align:center">{{ number_format(($promedio / 360) * $item->days, 2, ',', '.') }}</td>
                        </tr>
                        @php
                            $cont++;
                            $total += ($promedio / 360) * $item->days;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="9" style="text-align:center"><b>TOTAL</b></td>
                        <td style="text-align:center"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .content {
            font-size: 12px;
        }
        .header{
            width: 100%;
        }
        .table-details th{
            font-size: 9px !important
        }
        .table-details td{
            font-size: 11px !important
        }
        .table-details tfoot td{
            font-size: 11px !important
        }
        .table-resumen{
            font-size: 10px !important;
            margin-top: 40px;
        }
        table, th, td {
            border-collapse: collapse;
        }
        .saltopagina{
            display: block;
            page-break-before: always;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection