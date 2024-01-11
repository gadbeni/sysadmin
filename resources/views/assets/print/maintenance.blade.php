@extends('layouts.template-print')

@section('page_title', 'ACTA DE TRABAJO')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    
@endphp

@section('title')
    <h3 style="text-align: center;">
        ACTA DE TRABAJO
    </h3>
@endsection

@section('qr_code')
    <img src="{{ asset('images/logo-ust.png') }}" height="60px" />
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <div class="page-body">
            <div class="panel">
                <div class="panel-header">
                    <b style="padding: 10px 20px;">TECNICO Y LUGAR DEL SERVICIO</b>
                </div>
                <br>
                <div style="padding: 5px 10px">
                    <table width="100%">
                        <tr>
                            <td><b>TECNICO RESPONSABLE</b></td>
                            <td>{{ $maintenance->technical ? $maintenance->technical->person->first_name.' '.$maintenance->technical->person->last_name : 'No definido' }}</td>
                            <td><b>FECHA DE INGRESO</b></td>
                            <td>{{ date('d', strtotime($maintenance->date_start)) }} de {{ ucfirst($months[intval(date('m', strtotime($maintenance->date_start)))]) }} de {{ date('Y', strtotime($maintenance->date_start)) }}</td>
                        </tr>
                        <tr>
                            <td><b>LUGAR DE TRABAJO</b></td>
                            <td>{{ $maintenance->work_place == 1 ? 'Unidad de sistemas' : 'Oficina del funcionario' }}</td>
                            <td><b>FECHA DE SALIDA</b></td>
                            <td>{{ date('d', strtotime($maintenance->date_finish)) }} de {{ ucfirst($months[intval(date('m', strtotime($maintenance->date_finish)))]) }} de {{ date('Y', strtotime($maintenance->date_finish)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <b style="padding: 10px 20px;">ESPECIFICACIONES DEL EQUIPO</b>
                </div>
                <br>
                <div style="padding: 5px 10px">
                    <ul>
                        <li><b>{{ $maintenance->asset->code }} - {{ $maintenance->asset->subcategory->name }}</b></li>
                        @if ($maintenance->asset->code_internal)
                        <li><b>{{ $maintenance->asset->code_internal }}</b></li>
                        @endif
                        <li><b>ESTADO {{ Str::upper($maintenance->asset->status) }}</b></li>
                    </ul>
                    <p>{{ $maintenance->asset->description }}</p>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <b style="padding: 10px 20px;">MANTENIMIENTO</b>
                </div>
                <br>
                <div style="padding: 5px 10px">
                    <b>TIPO DE MANTENIMIENTO</b> : {{ ucfirst($maintenance->type) }}
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <b style="padding: 10px 20px;">DESCRIPCION DE SOPORTE</b>
                </div>
                <br>
                <div style="padding: 5px 10px">
                    <div>
                        <ul>
                            @foreach ($maintenance->details->groupBy('type.category') as $category => $details)
                            <li>{{ Str::upper($category) }}</li>
                            <ul>
                                @foreach ($details as $detail)
                                @if ($detail->type)
                                    <li>{{ Str::upper($detail->type->name) }}</li>                                                        
                                @else
                                    <li>{{ $detail->observations }}</li>
                                @endif
                                @endforeach
                            </ul>
                            @endforeach
                        </ul>
                    </div>
                    <br>
                    <div>
                        <b>OBSERVACIONES</b>
                        <p>{{ $maintenance->observations ?? 'Ninguna' }}</p>
                    </div>
                </div>
            </div>
            <br>
            <table width="100%" border="1" cellpadding="5px">
                <tr style="height: 120px">
                    <td width="50%" align="center" style="vertical-align: bottom;">
                        <b>--------------------------------------------------------- <br>
                            {{ $maintenance->technical ? $maintenance->technical->person->first_name.' '.$maintenance->technical->person->last_name : ' ' }} <br>
                            TECNICO RESPONSABLE
                        </b>
                    </td>
                    <td width="50%">

                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .content {
            font-size: 12px
        }
        .panel {
            position: relative;
            border: 1px  solid #828E99;
            margin-top: 20px;
            border-radius: 2px;
        }
        .panel-header {
            position: absolute;
            border: 1px  solid #828E99;
            border-radius: 5px;
            background-color: #00812C;
            color: white;
            top: -10px;
            left: 10px;
            padding: 3px 2px;
            width: 300px;
            text-align: center
        }
        table {
            border-collapse: collapse;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection