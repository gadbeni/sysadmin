@extends('layouts.template-print-legal')

@section('page_title', 'Adenda')

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
@endphp

@section('content')
    <div class="content">
        <div class="page-head">
            <p><strong>CONTRATO MODIFICATORIO DE CONSULTORIA INDIVIDUAL DE LINEA {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }} N&deg; {{ str_pad($addendums->first()->id, 2, "0", STR_PAD_LEFT) }}/2022 RELACIONADO AL CONTRATO {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong></p>
        </div>
        <p>&nbsp;</p>
        <p>
            Conste por el presente Contrato Modificatorio de Consultor&iacute;a Individual de L&iacute;nea, que tiene como <strong>Contrato Principal {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</strong> el cual data del <strong>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->start.' -1 days')).' de '.$months[intval(date('m', strtotime($addendums->last()->start.' -1 days')))].' de '.date('Y', strtotime($addendums->last()->start.' -1 days')) }}</strong>@if (count($addendums) > 1), y un <strong>Primer Contrato Modificatorio N&deg; {{ str_pad($addendums->last()->id, 2, "0", STR_PAD_LEFT) }}/2022</strong> el cual data del <strong>{{ date('d', strtotime($addendums->last()->start)).' de '.$months[intval(date('m', strtotime($addendums->last()->start)))].' de '.date('Y', strtotime($addendums->last()->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->finish)).' de '.$months[intval(date('m', strtotime($addendums->last()->finish)))].' de '.date('Y', strtotime($addendums->last()->finish)) }}</strong>@endif.
        </p>
        <p>El Gobierno Aut&oacute;nomo Departamental del Beni, a trav&eacute;s de su <strong>{{ $signature ? $signature->direccion_administrativa->nombre.' ('.$signature->direccion_administrativa->sigla.')' : 'Secretaría Departamental de Administraciòn y Finanzas (SDAF)' }}</strong>, con <strong>NIT</strong> N.&ordm; <strong>177396029</strong>, con domicilio legal en el Edificio Central de la Gobernaci&oacute;n del Beni, Acera Sur de la Plaza Mariscal Jos&eacute; Ballivi&aacute;n, en la Ciudad de la Sant&iacute;sima&nbsp; Trinidad, Provincia Cercado, del Departamento del Beni, representado legalmente por el/la <strong>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}</strong>, conforme <strong>Resoluci&oacute;n Administrativa de Gobernaci&oacute;n {{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}, de fecha {{ $signature ? $signature->designation_date ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date') : setting('firma-autorizada.designation-date') }}</strong> en calidad de <strong>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} ({{ Str::upper($contract->direccion_administrativa->sigla) }})</strong><strong> &ndash; Unidad Solicitante</strong>, que en adelante se denominar&aacute; la <strong>ENTIDAD</strong>; y de la otra parte {{ $contract->person->gender == 'masculino' ? 'El Señor' : 'La Señora' }} <strong> {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong><strong><em>,</em></strong> mayor de edad, h&aacute;bil en toda forma de derecho, con C&eacute;dula de Identidad <strong>N.&deg; </strong><strong>{{ $contract->person->ci }}</strong>, vecina de esta Ciudad, quien en adelante se la/lo denominar&aacute; <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }},</strong> quienes celebran y suscriben el presente <strong><em>Contrato Modificatorio</em></strong>, de acuerdo a los t&eacute;rminos y condiciones siguientes:</p>
        <p>&nbsp;</p>
        <p><strong>CL&Aacute;USULA PRIMERA. -</strong><strong> (Antecedentes)</strong></p>
        <p><strong>LA ENTIDAD</strong>, mediante un proceso en la modalidad de <strong>Contrataci&oacute;n Menor</strong>, realizado bajo las normas y regulaciones de contrataci&oacute;n establecidas en el Decreto Supremo N.&ordm; 0181 de fecha 28 de junio del 2009, Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios (NB-SABS) y t&eacute;rminos de referencia, se firm&oacute; el contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->start.' -1 days')).' de '.$months[intval(date('m', strtotime($addendums->last()->start.' -1 days')))].' de '.date('Y', strtotime($addendums->last()->start.' -1 days')) }}</strong>@if (count($addendums) > 1), y un <strong>Primer Contrato Modificatorio N&deg; {{ str_pad($addendums->last()->id, 2, "0", STR_PAD_LEFT) }}/2022</strong> el cual data del <strong>{{ date('d', strtotime($addendums->last()->start)).' de '.$months[intval(date('m', strtotime($addendums->last()->start)))].' de '.date('Y', strtotime($addendums->last()->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->finish)).' de '.$months[intval(date('m', strtotime($addendums->last()->finish)))].' de '.date('Y', strtotime($addendums->last()->finish)) }}</strong>@endif.</p>
        <p>En atenci&oacute;n al requerimiento de la unidad solicitante, el Informe T&eacute;cnico y legal adem&aacute;s de los documentos adjunto y posterior a ello la aprobaci&oacute;n del responsable de procesos de contrataci&oacute;n se procedi&oacute; a elaborar <strong>el {{ request('type') == 'first' ? 'Primer' : 'Segundo'  }} Contrato Modificatorio</strong> para la prestaci&oacute;n del servicio de consultor&iacute;a individual de l&iacute;nea con relaci&oacute;n al contrato<strong> {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->start.' -1 days')).' de '.$months[intval(date('m', strtotime($addendums->last()->start.' -1 days')))].' de '.date('Y', strtotime($addendums->last()->start.' -1 days')) }}</strong>@if (count($addendums) > 1), y un <strong>Primer Contrato Modificatorio N&deg; {{ str_pad($addendums->last()->id, 2, "0", STR_PAD_LEFT) }}/2022</strong> el cual data del <strong>{{ date('d', strtotime($addendums->last()->start)).' de '.$months[intval(date('m', strtotime($addendums->last()->start)))].' de '.date('Y', strtotime($addendums->last()->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->finish)).' de '.$months[intval(date('m', strtotime($addendums->last()->finish)))].' de '.date('Y', strtotime($addendums->last()->finish)) }}</strong>@endif, con cargo al {{ Str::upper($contract->program->class) }} <strong>&ldquo;{{ Str::upper($contract->program->name) }}&rdquo;</strong>, Categor&iacute;a Program&aacute;tica {{ Str::upper($contract->program->programatic_category) }}<strong>, </strong>Partida Presupuestaria {{ Str::upper($contract->program->number) }}.&nbsp;</p>
        <p>&nbsp;</p>
        <p><strong>CL&Aacute;USULA SEGUNDA. -</strong><strong> (Legislaci&oacute;n Aplicable)</strong></p>
        <p><strong>&nbsp;</strong>El presente contrato se celebra exclusivamente al amparo de las siguientes disposiciones:</p>
        <ul>
        <li>
        <p>Constituci&oacute;n Pol&iacute;tica del Estado.&nbsp;</p>
        </li>
        <li>
        <p>Ley N&ordm; 1178, de 20 de julio de 1990 de Administraci&oacute;n y Control Gubernamentales.&nbsp;</p>
        </li>
        <li>
        <p>Decreto Supremo N&ordm; 0181, de 28 de junio de 2009, de las Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios NB-SABS.&nbsp;</p>
        </li>
        <li>
        <p>Ley del presupuesto General aprobado para la gesti&oacute;n 2021.&nbsp;</p>
        </li>
        <li>
        <p>Ley N&deg; 2341, Ley del Procedimiento Administrativo.&nbsp;</p>
        </li>
        <li>
        <p>Decreto Supremo N&ordm; 27113, de 23 de julio de 2003, Reglamento a la Ley de Procedimiento Administrativo.&nbsp;</p>
        </li>
        <li>
        <p>Las dem&aacute;s disposiciones relacionadas directamente con las normas anteriormente mencionadas.</p>
        </li>
        </ul>
        <p><strong>CL&Aacute;USULA TERCERA. -</strong><strong> (Objeto y Causa)</strong></p>
        <p>El objeto y causa del presente contrato <strong>({{ request('type') == 'first' ? 'PRIMER' : 'SEGUNDO'  }} MODIFICATORIO)</strong> es la modificaci&oacute;n del contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</strong>, el cual tiene una vigencia desde el <strong>{{ date('d', strtotime($contract->start)) }}</strong> de <strong>{{ $months[intval(date('m', strtotime($contract->start)))] }}</strong> de <strong>{{ date('Y', strtotime($contract->start)) }}</strong>  hasta el <strong>{{ date('d', strtotime($addendums->last()->start.' -1 days')).' de '.$months[intval(date('m', strtotime($addendums->last()->start.' -1 days')))].' de '.date('Y', strtotime($addendums->last()->start.' -1 days')) }}</strong>@if (count($addendums) > 1), y un <strong>Primer Contrato Modificatorio N&deg; {{ str_pad($addendums->last()->id, 2, "0", STR_PAD_LEFT) }}/2022</strong> el cual data del <strong>{{ date('d', strtotime($addendums->last()->start)).' de '.$months[intval(date('m', strtotime($addendums->last()->start)))].' de '.date('Y', strtotime($addendums->last()->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->finish)).' de '.$months[intval(date('m', strtotime($addendums->last()->finish)))].' de '.date('Y', strtotime($addendums->last()->finish)) }}</strong>@endif, por consiguiente y en concordancia con lo manifestado en la Cl&aacute;usula Primera del presente documento, se ampl&iacute;a y modifica el contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</strong>, de la siguiente manera:</p>        
        <p><strong><em>VIGENCIA. &ndash;</em></strong></p>
        <p><em>El presente {{ request('type') == 'first' ? 'Primer' : 'Segundo'  }} Contrato Modificatorio entrar&aacute; en vigencia desde </em><strong><em>{{ date('d', strtotime($addendums->first()->start)) }} de {{ $months[intval(date('m', strtotime($addendums->first()->start)))] }} al {{ date('d', strtotime($addendums->first()->finish)) }} de {{ $months[intval(date('m', strtotime($addendums->first()->finish)))] }} de {{ date('Y', strtotime($addendums->first()->start)) }}.</em></strong></p>
        <div class="div-details_payments">{!! $addendums->first()->details_payments !!}</div>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>

        <p><strong>CL&Aacute;USULA CUARTA. -</strong><strong> (Documentos que Integran)</strong></p>
        <p>Para el cumplimiento del presente Contrato Modificatorio, forman parte del mismo los siguientes documentos:</p>
        <ul>
        <li>
        <p>Certificaci&oacute;n Presupuestaria.&nbsp;</p>
        </li>
        <li>
        <p>C&eacute;dula de identidad de <strong>EL CONSULTOR.</strong></p>
        </li>
        <li>
        <p>Informe T&eacute;cnico-Legal.</p>
        </li>
        <li>
        <p>Contrato Administrativo de servicio de Consultor&iacute;a de L&iacute;nea <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->start.' -1 days')).' de '.$months[intval(date('m', strtotime($addendums->last()->start.' -1 days')))].' de '.date('Y', strtotime($addendums->last()->start.' -1 days')) }}</strong>.</p>
        </li>
        </ul>
        <p>&nbsp;</p>
        <p><strong>CL&Aacute;USULA QUINTA. -</strong><strong> (De las Cl&aacute;usulas del Contrato Principal)</strong></p>
        <p>Quedan subsistentes y con plena validez, las dem&aacute;s cl&aacute;usulas contenidas en el Contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->start.' -1 days')).' de '.$months[intval(date('m', strtotime($addendums->last()->start.' -1 days')))].' de '.date('Y', strtotime($addendums->last()->start.' -1 days')) }}</strong>@if (count($addendums) > 1), un <strong>Primer Contrato Modificatorio N&deg; {{ str_pad($addendums->last()->id, 2, "0", STR_PAD_LEFT) }}/2022</strong> el cual data del <strong>{{ date('d', strtotime($addendums->last()->start)).' de '.$months[intval(date('m', strtotime($addendums->last()->start)))].' de '.date('Y', strtotime($addendums->last()->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendums->last()->finish)).' de '.$months[intval(date('m', strtotime($addendums->last()->finish)))].' de '.date('Y', strtotime($addendums->last()->finish)) }}</strong>@endif y @if (count($addendums) > 1) un <strong>Segundo Contrato</strong>  @else <strong>Contrato</strong> @endif <strong>Modificatorio N&deg; {{ str_pad($addendums->first()->id, 2, "0", STR_PAD_LEFT) }}/2022</strong> de fecha <strong>{{ date('d', strtotime($addendums->first()->start)) }}</strong> de <strong>{{ $months[intval(date('m', strtotime($addendums->first()->start)))] }}</strong> de <strong>{{ date('Y', strtotime($addendums->first()->start)) }}</strong> al <strong>{{ date('d', strtotime($addendums->first()->finish)) }}</strong> de <strong>{{ $months[intval(date('m', strtotime($addendums->first()->finish)))] }}</strong> de <strong>{{ date('Y', strtotime($addendums->first()->finish)) }} </strong>, excepto la Cl&aacute;usula Sexta y octava que est&aacute; siendo modificada mediante el presente contrato modificatorio y que se refiere a la <strong>vigencia y monto de la prestaci&oacute;n del servicio.</strong></p>
        <p><strong>CL&Aacute;USULA SEXTA. -</strong><strong> (Conformidad)</strong></p>
        <p>En se&ntilde;al de aceptaci&oacute;n y conformidad con todas y cada una de las cl&aacute;usulas establecidas en el presente <strong>CONTRATO</strong> <strong>MODIFICATORIO</strong>, firman las partes manifestando su entero consentimiento y conformidad con todas y cada una de las cl&aacute;usulas del presente documento oblig&aacute;ndose a su fiel y estricto cumplimiento, en cuya se&ntilde;al suscriben en cuatro ejemplares de un mismo tenor.&nbsp;</p>
        <p>Este documento, conforme a disposiciones legales de control fiscal vigente, ser&aacute; registrado ante la Contralor&iacute;a General del Estado.</p>
        <p>&nbsp;</p>
        <p style="text-align: right;">
            <select id="location-id">
                @foreach (App\Models\City::where('states_id', 1)->where('deleted_at', NULL)->get() as $item)
                <option @if($item->name == $contract->direccion_administrativa->city->name) selected @endif value="{{ Str::upper($item->name) }}">{{ Str::upper($item->name) }}</option>
                @endforeach
            </select>
            <span id="label-location">SANTISIMA TRINIDAD</span>, {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}
        </p>

        <br>

        <table width="100%" style="text-align: center; margin: 100px 0px;">
            <tr>
                <td style="width: 50%">
                    ....................................................... <br>
                    {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                    <b>{{ $signature ? $signature->job : setting('firma-autorizada.job') }}</b>
                </td>
                <td style="width: 50%">
                    ....................................................... <br>
                    {{ $contract->person->gender == 'masculino' ? 'Sr.' : 'Sra.' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                    <b>{{ $contract->person->gender == 'masculino' ? 'CONTRATADO' : 'CONTRATADA' }}</b>
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('css')
    <style>
        .page-head {
            text-align: center;
            padding-top: 50px;
        }
        .content {
            padding: 0px 34px;
            font-size: 12px;
        }
        .saltopagina{
            display: none;
        }
        @media print{
            .saltopagina{
                display: block;
                page-break-before: always;
            }
            .pt{
                height: 90px;
            }
        }
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