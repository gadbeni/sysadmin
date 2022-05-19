@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Personal eventual '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.').' Bs.'); !!}
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
                    <b>DE:</b> {{ Str::upper(setting('firma-autorizada.name')) }} <br>
                    <b>{{ Str::upper(setting('firma-autorizada.job')) }}</b> <br> <br> <br>
                    <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                    <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                </div>
            </div>
            <br>
            <p style="text-align: center"><u><b>DESIGNACIÓN</b></u></p>
            {{-- <p>
                Mediante el presente comunico a Usted que, a partir de la fecha y hasta el {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}, es {{ $contract->person->gender == 'masculino' ? 'desigando' : 'designada' }} para ejercer el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, bajo la dependincia de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b> con el nivel salarial <b>{{ $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->NumNivel }}</b> por el monto de <b>{{ number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 0, ',', '.') }} Bs.</b> de la <b>Planilla de Inversión (Personal Eventual)</b>, según clasificador presupuestario partida 12100. Designación que conlleva todas las implicancias y efectos de la Ley 2027 y 1178, Ley 1413 del PGE  2022 y su respectivo D.S. 4646.
            </p> --}}
            <p style="margin-top: 50px">
                Mediante el presente comunico a usted que a partir del <b>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} hasta el {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}</b>, es designado para ejercer el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b> bajo la dependencia de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>, con el nivel salarial <b>{{ $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->NumNivel }}</b> por el monto de <b>Bs. {{ number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 0, ',', '.') }}</b>, con cargo a la Partida Presupuestaria 12100; en cumplimiento a la Constitución Política del Estado, Ley 223 General para Personas con Discapacidad, Estatuto Funcionario Público Ley 2027 Art. 6, Ley 1178, la Ley 1413 del Presupuesto General del Estado de la gestión 2022, su respectivo Decreto Reglamentario y demás normas conexas.    
            </p>
            <p>
                Quedando establecido que se debe formalizar la contratación conforme al Decreto Supremo N° 26115, artículo 18 parágrafo II inciso e) numeral 5, Decreto Supremo 27375 artículo 5.
            </p>
            <p>
                Al asumir las funciones para las cuales ha sido designado, me permito instarle a desempeñar sus funciones con dedicación, responsabilidad, eficacia, eficiencia y transparencia en el marco de la ley 1178.
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