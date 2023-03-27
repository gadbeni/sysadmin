@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $signature = null;

    $last_contract = App\Models\Contract::where('procedure_type_id', 1)->where('person_id', $contract->person_id)->where('deleted_at', NULL)->where('status', 'concluido')->orderBy('start', 'DESC')->first();
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Personal permanente '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($contract->job->salary, 2, ',', '.').' Bs.'); !!}
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
            <div class="page-head" style="width: 100%">
                <div class="border-right">
                    <p style="position:absolute; bottom: 10px">
                        <select id="location-id">
                            @foreach (App\Models\City::where('states_id', 1)->where('deleted_at', NULL)->get() as $item)
                            <option @if($item->name == $contract->direccion_administrativa->city->name) selected @endif value="{{ Str::upper($item->name) }}">{{ Str::upper($item->name) }}</option>
                            @endforeach
                        </select>
                        <span id="label-location">SANTISIMA TRINIDAD</span>, {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}
                    </p>
                </div>
                <div class="border-left">
                    <b>DE:</b> {{ Str::upper($signature ? $signature->name : setting('firma-autorizada.name')) }} <br>
                    <b>{{ Str::upper($signature ? $signature->job : setting('firma-autorizada.job')) }}</b> <br> <br> <br>
                    <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                    <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                </div>
            </div>
            <br>
            <p style="text-align: center"><u><b>REASIGNACIÓN DE CARGO</b></u></p>
            <p>
                Mediante la presente, habiéndose designado mediante Memorándum <b>GAD-BENI DRRHH N° {{ $last_contract->code }}</b> que le fue asignado el {{ date('d', strtotime($last_contract->start)) }} de {{ $months[intval(date('m', strtotime($last_contract->start)))] }} de {{ date('Y', strtotime($last_contract->start)) }}, para que desempeñe el cargo de <b>{{ Str::upper($last_contract->job->name) }}</b>, con el Ítem N&deg; {{ $last_contract->job->item }} y nivel salarial {{ $last_contract->job->level }} de la escala salarial.
            </p>
            <p>
                Que en cumplimiento a la escala <b>APROBADA</b> mediante <b>RESOLUCION DE ASAMBLEA N° 131/2022-2023 del 28 de febrero del 2023</b>,  Comunico a usted que a partir del {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }} ha sido designado como <b>{{ Str::upper($contract->job->name) }}</b>, con el Ítem N&deg; {{ $contract->job->item }} y nivel salarial {{ $contract->job->level }} de nuestra escala salarial en vigencia, y con el haber mensual de <b>Bs. {{ NumerosEnLetras::convertir($contract->job->salary, 'Bolivianos', true) }}</b>, bajo dependencia de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>.
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
            padding: 0px 34px;
            text-align: center;
            padding-top: 100px;
        }
        .page-title {
            padding: 0px 50px;
            text-align: center;
            padding-top: 10px;
        }
        .page-body{
            padding: 0px 30px;
            padding-top: 10px;
        }
        .page-body p{
            text-align: justify;
            font-size: 14px;
        }
        .content {
            padding: 0px 34px;
            font-size: 13px;
        }
        .page-head{
            display: flex;
            flex-direction: row;
            width: 100%;
            /* height: 100px; */
            border-bottom: 2px solid #000;
        }
        .border-right{
            position: relative;
            padding: 10px;
            width: 50%;
            border-right: 1px solid black
        }
        .border-left{
            padding: 10px;
            width: 50%;
            border-left: 1px solid black
        }
        .page-body th{
            background-color: #d7d7d7
        }
        .page-body table{
            /* text-align: center; */
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