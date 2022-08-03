@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
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
                            <option value="Santísima Trinidad">Santísima Trinidad</option>
                            <option value="Guayaramerín">Guayaramerín</option>
                            <option value="Riberalta">Riberalta</option>
                            <option value="Santa Rosa">Santa Rosa</option>
                            <option value="Reyes">Reyes</option>
                            <option value="Rurrenabaque">Rurrenabaque</option>
                            <option value="Yucumo">Yucumo</option>
                            <option value="San Borja">San Borja</option>
                            <option value="San Ignacio">San Ignacio</option>
                            <option value="San Ramón">San Ramón</option>
                            <option value="San Joaquín">San Joaquín</option>
                            <option value="Puerto Siles">Puerto Siles</option>
                            <option value="Santa Ana">Santa Ana</option>
                            <option value="Magdalena">Magdalena</option>
                            <option value="Baures">Baures</option>
                            <option value="Huacaraje">Huacaraje</option>
                            <option value="Exaltación">Exaltación</option>
                            <option value="San Javier">San Javier</option>
                            <option value="Loreto">Loreto</option>
                            <option value="San Andrés">San Andrés</option>
                        </select>
                        <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}
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
            <p style="text-align: center"><u><b>DESIGNACIÓN</b></u></p>
            <p>
                Mediante el presente comunico a Usted que, a partir de la fecha, es {{ $contract->person->gender == 'masculino' ? 'desigando' : 'designada' }} para ejercer el cargo de <b>{{ Str::upper($contract->job->name) }}</b>, dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b> con el nivel salarial <b>{{ $contract->job->level }}</b> item <b>{{ $contract->job->item }}</b> de <b>PERSONAL PERMANENTE</b>.
            </p>
            <p>
                Así mismo, se debe presentar SIPPASE, REJAB, Solvencia Fiscal y Declaración Jurada Bienes y Rentas, en la Dirección Departamental de Recursos Humanos dependiente del Gobierno Autónomo del Beni.
            </p>
            <p>
                Deseándole éxito en sus funciones y responsabilidades que devengan de la prestación de sus servicios, de conformidad del art. 28 de la ley Nº 1178 y esperando contar con su valioso aporte y participación en el logro de los objetivos del Gobierno Autónomo Departamental del Beni, saludo a usted.
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