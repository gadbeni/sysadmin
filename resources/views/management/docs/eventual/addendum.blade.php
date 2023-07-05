@extends('layouts.template-print-legal')

@section('page_title', 'Ampliación de contrato')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;

    // Si es la primera adenda se obtiene la primera registrada y si no se obtienen las últimas 2 en orden descendente
    if(request('type') == 'first'){
        $addendums = $contract->addendums->where('deleted_at', NULL)->sortBy('id')->slice(0, 1);
    }else{
        $addendums = $contract->addendums->where('deleted_at', NULL)->sortByDesc('id')->slice(0, 2);
    }

    // Solo en caso de adendas firma el director de finanzas
    $signature = $addendums->first()->signature;

    $finish_contract_date = date('Y-m-d', strtotime($addendums->first()->start.' -1 days'));
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate("1ER. CONTRATO MODIFICATORIO DE PERSONAL EVENTUAL ".($signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF')." NRO ".$addendums->first()->code." RELACIONADO AL CONTRATO ".($signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF')."/GAD-BENI NRO ".$code);
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
            <div class="page-head">
                <h3>1er. CONTRATO MODIFICATORIO DE PERSONAL EVENTUAL {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }} N&deg; {{ $addendums->first()->code }} RELACIONADO AL CONTRATO {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </h3>
            </div>
            <p><b>PRIMERA. - (ANTECEDENTES)</b></p>
            <p>
                <table width="100%">
                    <tr>
                        <td style="width: 30px">1.1</td>
                        <td style="text-align: justify">
                            En fecha <b>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</b> el <b>GAD BENI</b> y {{ $contract->person->gender == 'masculino' ? 'el Señor' : 'la Señora' }} <strong> {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, suscriben un Contrato de Prestación de Servicio de Personal Eventual, consignado con el contrato <b>CONTRATO DE PERSONAL EVENTUAL N&deg; {{ $code }}</b> para el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, con una vigencia desde el <b>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</b> al <b>{{ date('d', strtotime($finish_contract_date)).' de '.$months[intval(date('m', strtotime($finish_contract_date)))].' de '.date('Y', strtotime($finish_contract_date)) }}</b>.
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30px">1.2</td>
                        <td style="text-align: justify">
                            En fecha {{ date('d', strtotime($addendums->first()->nci_date)).' de '.$months[intval(date('m', strtotime($addendums->first()->nci_date)))].' de '.date('Y', strtotime($addendums->first()->nci_date)) }} {{ $addendums->first()->applicant->person->first_name }} {{ $addendums->first()->applicant->person->last_name }}/{{ $addendums->first()->applicant->cargo ? $addendums->first()->applicant->cargo->Descripcion : $addendums->first()->applicant->job->name }}-GAD-BENI, mediante Nota de Comunicación Interna {{ $addendums->first()->nci_code }}, con la finalidad de cumplir con objetivos previstos en el POA/2023 solicita la ampliación de contratación {{ $contract->person->gender == 'masculino' ? 'del Señor' : 'de la Señora' }} <strong> {{ $contract->person->first_name }} {{ $contract->person->last_name }} - {{ Str::upper($contract->cargo->Descripcion) }}</strong>.
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30px">1.3</td>
                        <td style="text-align: justify">
                            La Certificación Presupuestaria N° {{ $addendums->first()->certification_code }}, de fecha {{ date('d-m-Y', strtotime($addendums->first()->certification_date)) }}, asevera la existencia de saldos suficientes para efectuar una adenda al contrato, bajo la Partida Presupuestaria 12100 – Personal Eventual.
                        </td>
                    </tr>
                </table>
            </p>

            <p><b>SEGUNDA. - (PARTES INTERVINIENTES)</b></p>
            <p>
                <table width="100%">
                    <tr>
                        <td style="width: 30px">2.1</td>
                        <td style="text-align: justify">
                            <b>El Gobierno Autónomo Departamental del Beni</b>, para estos fines representado legalmente por Lic. Freddy Machado Flores, Secretario Departamental, debidamente facultado para la firma del presente contrato modificatorio, en mérito a Resolución de Gobernación N°04/2023 de 28 de febrero de 2023 denominado el <b>CONTRATANTE</b>.
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30px">2.2</td>
                        <td style="text-align: justify">
                            Y {{ $contract->person->gender == 'masculino' ? 'el Señor' : 'la Señora' }} <strong> {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, con cedula de identidad <b>C.I. {{ $contract->person->ci }}</b>, con domicilio real, para fines de notificación y procesal las oficinas del GAD BENI, plaza principal Mcal. José Ballivián, denominado <b>{{ $contract->person->gender == 'masculino' ? 'EL CONTRATADO' : 'LA CONTRATADA' }}</b>.
                        </td>
                    </tr>
                </table>
            </p>

            <p><b>TERCERA. - (OBJETO DEL CONTRATO MODIFICATORIO)</b></p>
            <p>La presente ampliación tiene por objeto, modificarse la siguiente Cláusula del Contrato Principal. </p>
            <p><b><i>*Dice:</i></b></p>
            <p style="margin-left: 50px"><b><i>CLAUSULA OCTAVA. - (DURACIÓN Y CARÁCTER DEFINIDO):</i></b></p>
            <p style="margin-left: 50px">
                <i>
                    En el marco legal citado en antecedentes, el presente contrato tendrá calidad de CONTRATO DE PERSONAL EVENTUAL, computable a partir del <b>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</b> al <b>{{ date('d', strtotime($finish_contract_date)).' de '.$months[intval(date('m', strtotime($finish_contract_date)))].' de '.date('Y', strtotime($finish_contract_date)) }}</b>.
                </i>
            </p>
            <p><b><i>*Modificarse:</i></b></p>
            <p style="margin-left: 50px"><b><i>CLAUSULA OCTAVA. - (DURACIÓN Y CARÁCTER DEFINIDO):</i></b></p>
            <p style="margin-left: 50px">
                @php
                    $duracion_adenda = contract_duration_calculate($addendums->first()->start, $addendums->first()->finish);
                @endphp
                <i>
                    @php
                        $numeros_a_letras = new NumeroALetras();
                    @endphp
                    En el presente contrato de personal eventual, se amplia por un tiempo de {{ $duracion_adenda->months }} ({{ Str::lower($numeros_a_letras->toWords($duracion_adenda->months)) }}) {{ $duracion_adenda->months > 1 ? 'meses' : 'mes' }} {{ $duracion_adenda->days > 0 ? ' y '.$duracion_adenda->days.' ('.Str::lower($numeros_a_letras->toWords($duracion_adenda->days)).') días' : '' }}, computables desde el <b>{{ date('d', strtotime($addendums->first()->start)).' de '.$months[intval(date('m', strtotime($addendums->first()->start)))].' de '.date('Y', strtotime($addendums->first()->start)) }}</b> al <b>{{ date('d', strtotime($addendums->first()->finish)).' de '.$months[intval(date('m', strtotime($addendums->first()->finish)))].' de '.date('Y', strtotime($addendums->first()->finish)) }}</b>, sin lugar a tacita reconducción.
                </i>
            </p>

            <p><b>CUARTA. - (RATIFICACIÓN E INALTERABILIDAD DEL CONTRATO)</b></p>
            <p>Todas las demás cláusulas del contrato quedan en la forma original y son inalterables. </p>

            <p><b>QUINTA. - (CONFORMIDAD) </b></p>
            <p>En señal de conformidad, para su fiel y estricto cumplimiento firman la presente Adenda al contrato principal en tres ejemplares de un mismo tenor y validez.</p>
            {{-- <br> --}}
            <p style="text-align: right;">
                @php
                    $signature_date = $addendums->first()->signature_date ?? $addendums->first()->start;
                @endphp
                <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($signature_date)) }} de {{ Str::upper($months[intval(date('m', strtotime($signature_date)))]) }} de {{ date('Y', strtotime($signature_date)) }}
            </p>
            <table class="table-signature">
                <tr>
                    <td style="width: 50%">
                        {{-- ....................................................... <br>
                        {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                        <b>{{ $signature ? $signature->job : setting('firma-autorizada.job') }}</b> --}}
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
        
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('.div-details_payments span').css('font-size', '12px');
            $('.div-details_payments p').css('font-size', '12px');
        });
    </script>
@endsection