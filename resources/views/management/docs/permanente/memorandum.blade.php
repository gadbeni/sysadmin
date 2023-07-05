@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $signature = $contract->signature;
    if(
        $contract->direccion_administrativa->direcciones_tipo_id != 3 &&
        $contract->direccion_administrativa->direcciones_tipo_id != 4 &&
        $contract->direccion_administrativa_id != 5 &&
        $contract->direccion_administrativa_id != 48 &&
        $contract->direccion_administrativa_id != 53
    ){
        $signature = null;
    }
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('MEMORANDUM DE PERSONAL PERMANENTE '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($contract->job->salary, 2, ',', '.').' Bs.');
        @endphp
        {!! $qrcode !!}
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
            <p style="text-align: center"><u><b>DESIGNACIÓN</b></u></p>
            <p>
                El Gobierno Autónomo Departamental del Beni - GAD-BENI, comunica a Usted que a partir de la fecha ha sido {{ $contract->person->gender == 'masculino' ? 'designado' : 'designada' }} para ejercer el cargo de <b>{{ Str::upper($contract->job->name) }}</b>, dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>.
            </p>
            <p>
                @php
                    $numeros_a_letras = new NumeroALetras();
                @endphp
                Su remuneración será cancelada con cargo a la partida {{ $contract->program->number }}, con un haber mensual de <b>Bs. {{ number_format($contract->job->salary, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice($contract->job->salary, 2, 'Bolivianos') }})</b>, en el item N&deg; {{ $contract->job->item }} con el nivel salarial <b>{{ $contract->job->level }}</b>, debiendo coordinar con la Dirección Administrativa Financiera para la asignación de activo fijos, manuales y reglamentos que rigen en la entidad.
            </p>
            <p>
                Al desearle éxito en sus funciones, le hacemos conocer que los desarrollos de sus actividades se supeditaran al POAI, instructivos, circulares, memorándums, reglamentos específicos y manuales de procedimientos vigentes del <b>Gobierno Autónomo Departamental del Beni</b> (GAD-BENI), disposiciones con la función pública y otras funciones que fueran asignadas por su inmediato superior.
            </p>
            <p>
                Se le recuerda, efectuar su Declaración Jurada de Bienes y Rentas ante la Contraloría General del Estado de conformidad al Decreto Supremo N°1233.
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