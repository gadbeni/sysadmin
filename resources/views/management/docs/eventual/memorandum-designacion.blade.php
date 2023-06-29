@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $signature = $contract->signature;
    $sueldo = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;

    // Calcular finalización de contrato en caso de tener adenda
    if($contract->addendums->count() > 0) {
        $contract_finish = date('Y-m-d', strtotime($contract->addendums->first()->start." -1 days"));
    } else {
        $contract_finish = $contract->finish;
    }
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('MEMORANDUM PARA PERSONAL EVENTUAL GAD-BENI-C.E- '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($sueldo, 2, ',', '.').' Bs.');
        @endphp
        @if ($contract->files->count() > 0)
            <img src="data:image/png;base64, {!! base64_encode($qrcode) !!}">
        @else
            {!! $qrcode !!}
        @endif
    </div>
@endsection

@section('content')
    @if ($contract->files->count() > 0)
        <div class="content">
            {!! $contract->files[0]->text !!}
        </div>
    @else
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
                            <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->start)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->start)))]) }} de {{ date('Y', strtotime($contract->start)) }}
                        </td>
                        <td class="td-right">
                            <b>DE:</b>
                            {{ Str::upper($signature ? $signature->name : setting('firma-autorizada.name')) }} <br>
                            <b>{{ Str::upper($signature ? $signature->job : setting('firma-autorizada.job')) }}</b>
                            <br> <br> <br>
                            <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                            <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                        </td>
                    </tr>
                </table>
                <p style="text-align: center"><u><b>DESIGNACIÓN</b></u></p>
                <p style="margin-top: 50px">
                    Mediante el presente comunico a usted que a partir del <b>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} hasta el {{ date('d', strtotime($contract_finish)) }} de {{ $months[intval(date('m', strtotime($contract_finish)))] }} de {{ date('Y', strtotime($contract_finish)) }}</b>, es designado para ejercer el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b> bajo la dependencia de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>, con el nivel salarial <b>{{ $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->NumNivel }}</b> por el monto de <b>Bs. {{ number_format($sueldo, 0, ',', '.') }}</b>, con cargo a la Partida Presupuestaria 12100; en cumplimiento a la Constitución Política del Estado, Ley 223 General para Personas con Discapacidad, Estatuto Funcionario Público Ley 2027 Art. 6, Ley 1178, la Ley 1413 del Presupuesto General del Estado de la gestión 2022, su respectivo Decreto Reglamentario y demás normas conexas.    
                </p>
                <p>
                    Quedando establecido que se debe formalizar la contratación conforme al Decreto Supremo N° 26115, artículo 18 parágrafo II inciso e) numeral 5, Decreto Supremo 27375 artículo 5.
                </p>
                <p>
                    Al asumir las funciones para las cuales ha sido designado, me permito instarle a desempeñar sus funciones con dedicación, responsabilidad, eficacia, eficiencia y transparencia en el marco de la ley 1178.
                </p>
            </div>
        </div>
    @endif
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