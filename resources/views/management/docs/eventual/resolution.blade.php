@extends('layouts.template-print')

@section('page_title', 'Resolución de Contrato Personal Eventual')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $signature = NULL;
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('RESOLUCIÓN DEL CONTRATO  '.$code);
        @endphp
        {!! $qrcode !!}
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
                <h3>RESOLUCIÓN DE CONTRATO N° {{ $contract->direccion_administrativa->sigla }} {{ $contract->finished->code }}, CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL GAD-BENI {{ $code }}, SUSCRITO ENTRE EL GAD BENI Y {{ $contract->person->gender == 'masculino' ? 'EL SEÑOR' : 'LA SEÑORA' }} {{ Str::ucfirst($contract->person->first_name) }} {{ Str::ucfirst($contract->person->last_name) }}</h3>
            </div>
            <p>Conste por el presente documento, la Resolución de CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL GAD-BENI {{ $code }} suscrito en fecha {{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}, Suscrito entre el Gobierno Autónomo Departamental del Beni y {{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}, al tenor de las siguientes clausulas:</p>
            <p><b>CLAUSULA PRIMERA. - (ANTECEDENTES)</b> Recomendando se dirá que en fecha {{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))] }}, el Gobierno Autónomo Departamental del Beni, representada legalmente por el/la {{ $signature ? $signature->name : setting('firma-autorizada.name') }} designado mediante <b>{{ $signature ? $signature->designation_type : 'Resolución de Gobernación' }} N° {{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}</b>,  de {{ $signature ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date') }}, en su calidad de <b>{{ Str::ucfirst($signature ? $signature->job : setting('firma-autorizada.job')) }}</b>, Con Cedula de Identidad N° {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}, suscribió un contrato de prestación de servicios de personal eventual Con <b>{{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}</b>, con cedula de identidad <b>N° {{ $contract->person->ci }}</b>,  con el objeto de que el Contratado desempeñe funciones como <b>{{ Str::upper($contract->cargo->Descripcion) }}</b> en dependencia de <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>, Contratación realizada en el marco de la Ley 1178, las normas básicas de la administración de personal, Ley 2027, Ley 1493, Ley del presupuesto General del Estado, además de lo estipulado en el presente contrato, mismo que tiene una vigencia desde el {{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))] }} hasta el {{ date('d', strtotime($contract->finished->previus_date)).' de '.$months[intval(date('m', strtotime($contract->finished->previus_date)))].' de '.date('Y', strtotime($contract->finished->previus_date)) }}.</p>
            <p>Recomendando el <b>INFORME TECNICO</b> {{ $contract->finished->technical_report }}</p>
            <p>Que, la <b>Nota de Comunicación Interna</b> {{ $contract->finished->nci }}</p>
            <p>Que, de acuerdo al <b>INFORME D.RR.HH. LEGAL</b> {{ $contract->finished->legal_report }}</p>
            <p><b>CLAUSULA SEGUNDA. - DE LA RESOLUCIÓN</b></p>
            <p>Que, el Gobierno Autónomo Departamental del Beni representada legalmente por el/la {{ $signature ? $signature->name : setting('firma-autorizada.name') }} - {{ $signature ? $signature->job : setting('firma-autorizada.job') }}, designado mediante {{ $signature ? $signature->designation_type : 'Resolución de Gobernación' }} N&deg; {{ $signature ? $signature->designation : setting('firma-autorizada.designation') }} de {{ $signature ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date') }} con la facultad conferida y considerando que el contratado se enmarca en lo establecido en la Clausula Novena .- (Causales de Resolución de contrato) <b>{{ $contract->finished->details }}</b>, por lo tanto se <b>RESUELVE</b> el <b>CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL GAD-BENI {{ $code }}</b> suscrito el <b>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))] }} hasta el {{ date('d', strtotime($contract->finished->previus_date)).' de '.$months[intval(date('m', strtotime($contract->finished->previus_date)))].' de '.date('Y', strtotime($contract->finished->previus_date)) }}</b>, entre las partes ut supra mencionadas, debiendo {{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}, presentar documentación y descargos correspondientes, en estricto cumplimiento al Reglamento de Personal Interno RIP, considerando que solo se deben pagarse los días trabajados según los respaldos de asistencia de los días efectivamente trabajados.</p>
            <p><b>CLAUSULA TERCERA. – NOTIFICACIÓN</b></p>
            <p>Notifíquese la presente Resolución del CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL GAD-BENI {{ $code }} por alguno de los medios legales permitidos, en el marco de la Ley 2341 de Procedimientos Administrativos de 23 de abril de 2002 y las reglas generales establecidas en el articulo 82 de la Ley N° 439 de 19 de noviembre de 2013.</p>
            <p>La presente Resolución se encuentra al amparo de los artículos 519 y 569 del Código Civil Boliviano, es decir que el contrato tiene fuerza de Ley entre las partes contratantes. No pudiendo ser disuelto el contrato, sino por conocimiento mutuo o por las causas autorizadas por la Ley y la clausula Novena del contrato mencionado.</p>
            @php
                $numeros_a_letras = new NumeroALetras();
            @endphp
            <p>Es dado en la Ciudad de {{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}, a los {{ Str::lower($numeros_a_letras->toWords(date('d', strtotime($contract->finish)))) }} días del mes de {{ Str::lower($months[intval(date('m', strtotime($contract->finish)))]) }} del año {{ $numeros_a_letras->toWords(date('Y', strtotime($contract->finish))) }}. </p>
            <table class="table-signature">
                <tr>
                    <td style="width: 50%">
                    </td>
                    <td style="width: 50%">
                        ....................................................... <br>
                        {{ $contract->person->gender == 'masculino' ? 'Sr.' : 'Sra.' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                        <b>{{ $contract->person->gender == 'masculino' ? 'CONTRATADO' : 'CONTRATADA' }}</b>
                    </td>
                </tr>
            </table>
        </div>
    @endif
@endsection

@section('css')
    <style>
        .page-head {
            text-align: right;
            padding: 0px;
        }
        .page-title {
            text-align: center;
        }
        .page-title h3{
            margin-top: 0px
        }
        @media print{
            .content {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection