@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = 'PROM-'.str_pad($contract->promotions->last()->code, 8, "0", STR_PAD_LEFT);
    $signature = null;
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('PROMOCION PERSONAL PERMANENTE '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($contract->job->salary, 2, ',', '.').' Bs.');
        @endphp
        {!! $qrcode !!}
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="page-title">
            <h2>
                <span style="color: #009A2F">MEMORANDUM</span> <br>
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
            <p style="text-align: center"><u><b>PROMOCION</b></u></p>
            <br>
            <p>De mi consideración:</p>
            <br>
            @php
                $numeros_a_letras = new NumeroALetras();
            @endphp
            <p>
                A través de la presente, en el ejercicio de las atribuciones conferidas mediante <b>Decreto de Gobernación N° 04/2023</b> 
                y conforme lo establece el <b>Decreto Supremo 26115 art.29</b> comunico a usted que a partir de la fecha se promociona a su persona al cargo de 
                <b>{{ $contract->job->name }}</b> con el <b>Ítem Nº {{ $contract->job->item }}</b>, nivel {{ $contract->job->level }} de nuestra escala salarial en vigencia conforme a la 
                <b>Resolución de Asamblea N° 036/2023-2024</b> de fecha <b>11 de octubre del 2023</b>, y con el haber mensual de <b>Bs. {{ number_format($contract->job->salary, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice($contract->job->salary, 2, 'Bolivianos') }})</b>.
            </p>
            <p>
                Así mismo, en cumplimiento al <b>Decreto Supremo 1233 en su artículo 5</b>, la <b>resolución CGE/019/2022</b>, se le recuerda la obligación de realizar la declaración jurada de bienes y rentas por actualización ante la Contraloría General del Estado Plurinacional por la promoción atribuible a su persona, cuya copia deberá ser presentada a la Dirección Departamental de Recursos Humanos del GAD-BENI.
            </p>
            <p>
                Sin otro particular motivo saludo a usted muy cordialmente.
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