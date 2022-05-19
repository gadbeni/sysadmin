@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->finished ? $contract->finished->code : $contract->code.'-F';
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
                        <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}
                    </p>
                </div>
                <div class="border-left">
                    <b>DE:</b> {{ Str::upper(setting('firma-autorizada.name')) }} <br>
                    <b>{{ Str::upper(setting('firma-autorizada.job')) }}</b> <br> <br> <br>
                    <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                    <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                </div>
            </div>
            <br>
            <p style="text-align: center"><u><b>AGRADECIMIENTO</b></u></p>
            {{-- <p>
                Mediante el presente comunico a Usted que, a partir de la fecha y hasta el {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}, es {{ $contract->person->gender == 'masculino' ? 'desigando' : 'designada' }} para ejercer el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, bajo la dependincia de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b> con el nivel salarial <b>{{ $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->NumNivel }}</b> por el monto de <b>{{ number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 0, ',', '.') }} Bs.</b> de la <b>Planilla de Inversión (Personal Eventual)</b>, según clasificador presupuestario partida 12100. Designación que conlleva todas las implicancias y efectos de la Ley 2027 y 1178, Ley 1413 del PGE  2022 y su respectivo D.S. 4646.
            </p> --}}
            <p style="margin-top: 50px">
                De mi consideración: <br><br><br>
                A través de la presente, comunico a usted que a partir de la fecha de su recepción del presente memorándum, queda desvinculada del cargo de <b>{{ Str::upper($contract->job->name) }}</b>, dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>.
            </p>
            <p>
                Por lo cual, deberá realizar la entrega bajo inventario los documentos, los activos asignados a su cargo a la responsable de Registro y Control de Bienes Públicos a efecto que le emitan su declaración de no custodio; asimismo, deberá realizar los trámites correspondientes para su obtención de saldo no deudor y declaración de Bienes y Rentas; dicha documentación deberá hacer llegar copia a RR.HH.
            </p>
            <br>
            <p>
                Atentamente
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