@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = 'T-'.str_pad($contract->transfers->last()->id, 4, "0", STR_PAD_LEFT);
    $signature = null;
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
                <span style="color: #009A2F">TRANSFERENCIA</span> <br>
                <small>GAD-BENI N° {{ $code }}</small>
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
            {{-- <br> --}}
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