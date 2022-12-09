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
                        <span>CORRESPONDIENTE A LA GESTIÓN {{ $bonus->year }}</span> <br>
                        <b>{{ Str::upper($bonus->direccion->nombre) }}</b>
                        <h3 style="margin: 0px">{{ Str::upper($procedure_type->name) }}</h3>
                    </td>
                    <td style="text-align:center; width: 90px">
                        @php
                            $string_qr = 'Planilla '.str_pad($bonus->id, 6, "0", STR_PAD_LEFT).' | '.$bonus->year.' | '.' | '.$procedure_type->name;
                        @endphp
                        @if ($type_render == 1)
                            @php
                                $qrcode = base64_encode(QrCode::format('svg')->size(80)->errorCorrection('H')->generate($string_qr));
                            @endphp
                            <img src="data:image/png;base64, {!! $qrcode !!}"> <br>
                        @else
                            {!! QrCode::size(80)->generate($string_qr); !!} <br>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: center">
                        <b>
                            {{ str_pad($bonus->id, 6, "0", STR_PAD_LEFT) }}
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">
                        <small style="font-size: 9px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px">
            <table width="100%" class="table-details" border="1" cellpadding="2" cellspacing="0">
                <thead>
                    <tr>
                        <th>N&deg;</th>
                        <th>Planilla</th>
                        <th>Nombre completo</th>
                        <th>CI</th>
                        <th>Sueldo promedio</th>
                        <th>Días trabajados</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $total = 0;
                    @endphp
                    @foreach ($bonus->details as $item)
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
                            <td style="text-align: right">{{ $item->salary }}</td>
                            <td style="text-align: right">{{ $item->days }}</td>
                            <td style="text-align: right">{{ $item->amount }}</td>
                        </tr>
                        @php
                            $cont++;
                            $total += $item->amount;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="6" style="text-align: right"><b>TOTAL</b></td>
                        <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
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