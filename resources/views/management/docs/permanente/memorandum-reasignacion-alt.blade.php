@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $signature = null;

    $previus_job = App\Models\ContractsJob::where('contract_id', $contract->id)->where('deleted_at', NULL)->orderBy('id', 'DESC')->first();
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('MEMORANDUM DE REASIGNACIÓN DE CARGO '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($contract->job->salary, 2, ',', '.').' Bs.');
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
                <span style="color: #009A2F">MEMORANDUM</span> <br>
                <small>GAD-BENI DRRHH N° {{ $code }}</small>
            </h2>
        </div>
        <div class="page-body">
            <table class="table-head" cellpadding="10">
                <tr>
                    <td class="td-left">
                        <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($previus_job->date)) }} de {{ Str::upper($months[intval(date('m', strtotime($previus_job->date)))]) }} de {{ date('Y', strtotime($previus_job->date)) }} <br>
                    </td>
                    <td class="td-right">
                        <b>DE:</b> {{ Str::upper($signature ? $signature->name : setting('firma-autorizada.name')) }} <br>
                        <b>{{ Str::upper($signature ? $signature->job : setting('firma-autorizada.job')) }}</b> <br> <br> <br>
                        <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                        <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                    </td>
                </tr>
            </table>
            <p style="text-align: center"><u><b>REASIGNACIÓN DE CARGO</b></u></p>
            <p>
                Mediante la presente, habiéndose designado mediante Memorándum <b>GAD-BENI DRRHH N° {{ $contract->code }}</b> que le fue asignado el {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}, para que desempeñe el cargo de <b>{{ Str::upper($previus_job->previus_name) }}</b>, con el Ítem N&deg; {{ $contract->job->item }} y nivel salarial {{ $contract->job->level }} de la escala salarial.
            </p>
            <p>
                @php
                    $numeros_a_letras = new NumeroALetras();
                @endphp
                Que en cumplimiento a la escala <b>APROBADA</b> mediante <b>RESOLUCION DE ASAMBLEA N° 131/2022-2023 del 28 de febrero del 2023</b>,  Comunico a usted que a partir del {{ date('d', strtotime($previus_job->date)) }} de {{ $months[intval(date('m', strtotime($previus_job->date)))] }} de {{ date('Y', strtotime($previus_job->date)) }} ha sido designado como <b>{{ Str::upper($contract->job->name) }}</b>, con el Ítem N&deg; {{ $contract->job->item }} y nivel salarial {{ $contract->job->level }} de nuestra escala salarial en vigencia, y con el haber mensual de <b>Bs. {{ number_format($contract->job->salary, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice($contract->job->salary, 2, 'Bolivianos') }})</b>, bajo dependencia de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>.
            </p>
            <p>
                Asimismo, en cumplimiento a normativa R/CE/17 se le recuerda que debe realizar la correspondiente actualización de su cargo al momento de realizar la actualización de su declaración jurada de bienes y rentas, en el mes de su cumpleaños, cuya copia deberá ser presentada a la Dirección Departamental de Recursos Humanos del Gobierno Departamental del Beni.
            </p>
            <p>Sin otro particular motivo, saludo a usted atentamente.</p>
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