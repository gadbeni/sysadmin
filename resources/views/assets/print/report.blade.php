@extends('layouts.template-print')

@section('page_title', 'Informe Técnico')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(80)->generate('INFORME TECNICO Nro '.$maintenance->code.' del técnico '.($maintenance->technical ? $maintenance->technical->person->first_name.' '.$maintenance->technical->person->last_name : 'S/N').' dirigida a '.$maintenance->destiny->person->first_name.' '.$maintenance->destiny->person->last_name.' en fecha '.date('d/m/Y', strtotime($maintenance->date)));
        @endphp
        {!! $qrcode !!}
    </div>
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <div class="page-head" style="margin-top: -30px !important">
            <h4 style="text-align: center; margin: 0px; margin-bottom: 10px">
                INFORME TECNICO N&deg;{{ $maintenance->code }}
            </h4>
        </div>
        <div class="page-body">
            <div style="padding: 0px 20px">
                <table style="font-size: 12px" cellspacing="10">
                    <tr>
                        <td>A</td>
                        <td style="width: 20px; text-align: center">:</td>
                        <td>
                            {{ $maintenance->destiny->person->first_name }} {{ $maintenance->destiny->person->last_name }}<br>
                            <b>{{ $maintenance->destiny->cargo_id ? $maintenance->destiny->cargo->Descripcion : $maintenance->destiny->job->name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>VIA</td>
                        <td style="width: 20px; text-align: center">:</td>
                        <td>
                            {{ $maintenance->supervisor->person->first_name }} {{ $maintenance->supervisor->person->last_name }} <br>
                            <b>{{ $maintenance->supervisor->cargo_id ? $maintenance->supervisor->cargo->Descripcion : $maintenance->supervisor->job->name }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>De</td>
                        <td style="width: 20px; text-align: center">:</td>
                        <td>
                            @if ($maintenance->technical)
                            {{ $maintenance->technical->person->first_name }} {{ $maintenance->technical->person->last_name }} <br>
                            <b>{{ $maintenance->technical->cargo_id ? $maintenance->technical->cargo->Descripcion : $maintenance->technical->job->name }}</b>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>FECHA</td>
                        <td style="width: 20px; text-align: center">:</td>
                        <td>
                            <span>Santísima Trinidad</span>, {{ date('d', strtotime($maintenance->date)) }} de {{ Str::upper($months[intval(date('m', strtotime($maintenance->date)))]) }} de {{ date('Y', strtotime($maintenance->date)) }}
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
            <div style="text-align: center">
                <b><u>REF.: {{ Str::upper($maintenance->reference) }}</u></b>
            </div>
            <br>
            @php
                $assignment = $maintenance->asset->assignments->where('active', 1);
                $direccion_administrativa = $assignment->count() ? $assignment[0]->person_asset->contract->direccion_administrativa->nombre : null;
            @endphp
            <p>
                Mediante la presente le informo que el activo: <br>
                {{ $maintenance->asset->description }} con código <b>{{ $maintenance->asset->code }}</b>{{ $direccion_administrativa ? ', perteneciente a '.$direccion_administrativa : '' }}, ha sido revisada por el personal del área de <b>Soporte Técnico de la Unidad de Sistemas y Telecomunicaciones</b>. Como resultado de la revisión le informa lo siguiente:
            </p>
            <p>{{ ucfirst($maintenance->report) }}</p>
            <br>
            @if ($maintenance->observations)
                <b>OBSERVACIONES:</b>
                <p>{{ $maintenance->observations }}</p>
            @endif
            <br><br><br>
            <p>Sin otro particular me despido de usted con las consideraciones más distinguidas.</p>

            <div style="margin-top: 100px">
                <p style="text-align: center; width: 100%; font-size: 12px">
                    @if ($maintenance->technical)
                        {{ $maintenance->technical->person->first_name }} {{ $maintenance->technical->person->last_name }} <br>
                        <b>{{ $maintenance->technical->cargo_id ? $maintenance->technical->cargo->Descripcion : $maintenance->technical->job->name }}</b>
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .table-description{
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .table-description th{
            text-align: center
        }
        .table-signature{
            font-size: 11px
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection