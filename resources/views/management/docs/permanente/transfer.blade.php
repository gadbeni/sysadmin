@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = 'T-'.str_pad($contract->transfers->last()->id, 4, "0", STR_PAD_LEFT);
    $signature = null;
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('TRANSFERENCIA PERSONAL PERMANENTE '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($contract->job->salary, 2, ',', '.').' Bs.');
        @endphp
        @if ($contract->files->count() > 0)
            <img src="data:image/png;base64, {!! base64_encode($qrcode) !!}">
        @else
            {!! $qrcode !!}
        @endif
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="page-title">
            <h2>
                <span style="color: #009A2F">TRANSFERENCIA</span> <br>
                <small>GAD-BENI N° {{ $code }}</small>
            </h2>
        </div>
        <div class="page-body">
            <table class="table-head" cellpadding="10">
                <tr>
                    <td class="td-left">
                        <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->start)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->start)))]) }} de {{ date('Y', strtotime($contract->start)) }} <br>
                    </td>
                    <td class="td-right">
                        <b>DE:</b> {{ Str::upper($signature ? $signature->name : setting('firma-autorizada.name')) }} <br>
                        <b>{{ Str::upper($signature ? $signature->job : setting('firma-autorizada.job')) }}</b> <br> <br> <br>
                        <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                        <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                    </td>
                </tr>
            </table>
            <p>
                @php
                    $numeros_a_letras = new NumeroALetras();
                @endphp
                Conforme a las atribuciones conferidas y en aplicación del art. 31 del D.S. 26115 Normas Básicas del Sistema de Administración de Personal, comunico a usted que, a partir de la fecha, su persona ha sido transferido al Cargo de <b>{{ Str::upper($contract->job->name) }}</b>, con el Ítem Nº <b>{{ $contract->job->item }}</b>, con el nivel salarial Nº <b>{{ $contract->job->level }}</b>, con una remuneración mensual de <b>Bs. {{ number_format($contract->job->salary, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice($contract->job->salary, 2, 'Bolivianos') }})</b>, bajo la dependencia de <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b> y ésta a su vez del <b>GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI</b>.
            </p>
            <p>
                Así mismo, hacerle conocer que la transferencia, no conlleva el traslado mobiliario. equipos y otros enseres del Área de Origen, se agradece a usted que toda la documentación referente a las funciones que ha venido desempeñando, pueda dejar al día y en completo orden.
            </p>
            <p>
                Se le recuerda, efectuar su Declaración Jurada de Bienes y Rentas ante la Contraloría General del Estado de conformidad al Decreto Supremo Nº 1233, por actualización de acuerdo a su fecha de nacimiento en la gestión.
            </p>
            <p>
                Deseándole éxitos en el desempeño de sus funciones y que las mismas sean cumplidas con honestidad, responsabilidad, eficiencia e integridad, saludo a usted.
            </p>
            <p>
                Atentamente.
            </p>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-title {
            text-align: center;
        }
        .page-title h2 {
            margin: 0px
        }
        .td-left {
            width: 50%;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
            vertical-align: bottom;
        }
        .td-right {
            width: 50%;
            border-left: 1px solid black;
            border-bottom: 1px solid black
        }
        .table-head {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px
        } 
        .page-body th{
            background-color: #d7d7d7
        }
        .page-body table{
            margin: 30px 0px;
            width: 100%;
            font-size: 12px;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection