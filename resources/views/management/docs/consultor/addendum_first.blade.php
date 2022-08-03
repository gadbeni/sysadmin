@extends('layouts.template-print-legal')

@section('page_title', 'Adenda')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $addendum = $contract->addendums->where('status', 'firmado')->first();
@endphp

@section('content')
    <div class="content">
        <div class="page-head">
            <p><strong>CONTRATO MODIFICATORIO DE CONSULTORIA INDIVIDUAL DE LINEA {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }} N&deg; {{ str_pad($addendum->id, 2, "0", STR_PAD_LEFT) }}/2022 RELACIONADO AL CONTRATO {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong></p>
        </div>
        <p>&nbsp;</p>
        <p>Conste por el presente Contrato Modificatorio de Consultor&iacute;a Individual de L&iacute;nea, que tiene como contrato principal <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</strong> el cual data del <strong>{{ date('d/m/Y', strtotime($contract->start)) }}</strong>.</p>
        <p>El Gobierno Aut&oacute;nomo Departamental del Beni, a trav&eacute;s de su <strong>{{ Str::upper($contract->direccion_administrativa->nombre) }} ({{ Str::upper($contract->direccion_administrativa->sigla) }})</strong>, con <strong>NIT</strong> N.&ordm; <strong>177396029</strong>, con domicilio legal en el Edificio Central de la Gobernaci&oacute;n del Beni, Acera Sur de la Plaza Mariscal Jos&eacute; Ballivi&aacute;n, en la Ciudad de la Sant&iacute;sima&nbsp; Trinidad, Provincia Cercado, del Departamento del Beni, representado legalmente por el/la <strong>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}</strong>, conforme <strong>Resoluci&oacute;n Administrativa de Gobernaci&oacute;n {{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}, de fecha 12 de julio del 2022</strong> en calidad de <strong>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} ({{ Str::upper($contract->direccion_administrativa->sigla) }})</strong><strong> &ndash; Unidad Solicitante</strong>, que en adelante se denominar&aacute; la <strong>ENTIDAD</strong>; y de la otra parte {{ $contract->person->gender == 'masculino' ? 'El Señor' : 'La Señora' }} <strong> {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong><strong><em>,</em></strong> mayor de edad, h&aacute;bil en toda forma de derecho, con C&eacute;dula de Identidad <strong>N.&deg; </strong><strong>{{ $contract->person->ci }}</strong>, vecina de esta Ciudad, quien en adelante se la/lo denominar&aacute; <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }},</strong> quienes celebran y suscriben el presente <strong><em>Contrato Modificatorio</em></strong>, de acuerdo a los t&eacute;rminos y condiciones siguientes:</p>
        <p>&nbsp;</p>
        <p><strong>CL&Aacute;USULA PRIMERA. -</strong><strong> (Antecedentes)</strong></p>
        <p><strong>&nbsp;LA ENTIDAD</strong>, mediante un proceso en la modalidad de <strong>Contrataci&oacute;n Menor</strong>, realizado bajo las normas y regulaciones de contrataci&oacute;n establecidas en el Decreto Supremo N.&ordm; 0181 de fecha 28 de junio del 2009, Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios (NB-SABS) y t&eacute;rminos de referencia, se firm&oacute; el contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d/m/Y', strtotime($contract->start)) }}</strong>.</p>
        <p>En atenci&oacute;n al requerimiento de la unidad solicitante, el Informe T&eacute;cnico y legal adem&aacute;s de los documentos adjunto y posterior a ello la aprobaci&oacute;n del responsable de procesos de contrataci&oacute;n se procedi&oacute; a elaborar <strong>el Primer Contrato Modificatorio</strong> para la prestaci&oacute;n del servicio de consultor&iacute;a individual de l&iacute;nea con relaci&oacute;n al contrato<strong> {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d/m/Y', strtotime($contract->start)) }}</strong>, con cargo al Programa/Proyecto <strong>&ldquo;{{ Str::upper($contract->program->name) }}&rdquo;</strong>, Categor&iacute;a Program&aacute;tica {{ Str::upper($contract->program->programatic_category) }}<strong>, </strong>Partida Presupuestaria {{ Str::upper($contract->program->number) }}.&nbsp;</p>
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
        <p>El objeto y causa del presente contrato <strong>(PRIMER MODIFICATORIO)</strong> es la modificaci&oacute;n del contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</strong>, el cual tiene una vigencia desde el <strong>{{ date('d', strtotime($contract->start)) }}</strong> de <strong>{{ $months[intval(date('m', strtotime($contract->start)))] }}</strong> de <strong>{{ date('Y', strtotime($contract->start)) }}</strong> hasta el <strong>{{ date('d', strtotime($addendum->start."- 1 days")) }}</strong> de <strong>{{ $months[intval(date('m', strtotime($addendum->start."- 1 days")))] }}</strong> del <strong> {{ date('Y', strtotime($addendum->start)) }}</strong>, por consiguiente y en concordancia con lo manifestado en la Cl&aacute;usula Primera del presente documento, se ampl&iacute;a y modifica el contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</strong>, de la siguiente manera:</p>        
        <p><strong><em>VIGENCIA. &ndash;</em></strong></p>
        <p>&nbsp;<em>El presente Primer Contrato Modificatorio entrar&aacute; en vigencia desde </em><strong><em>{{ date('d', strtotime($addendum->start)) }} de {{ $months[intval(date('m', strtotime($addendum->start)))] }} al {{ date('d', strtotime($addendum->finish)) }} de {{ $months[intval(date('m', strtotime($addendum->finish)))] }} de {{ date('Y', strtotime($addendum->start)) }}.</em></strong></p>
        <div class="div-details_payments">{!! $addendum->details_payments !!}</div>
        
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
        <p>Contrato Administrativo de servicio de Consultor&iacute;a de L&iacute;nea <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d/m/Y', strtotime($contract->start)) }}</strong>.</p>
        </li>
        </ul>
        <p>&nbsp;</p>
        <p>&nbsp;<strong>CL&Aacute;USULA QUINTA. -</strong><strong> (De las Cl&aacute;usulas del Contrato Principal)</strong></p>
        <p><strong>&nbsp;</strong>Quedan subsistentes y con plena validez, las dem&aacute;s cl&aacute;usulas contenidas en el Contrato <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong>de fecha <strong>{{ date('d/m/Y', strtotime($contract->start)) }}</strong> y <strong>Contrato Modificatorio N&deg; {{ str_pad($addendum->id, 2, "0", STR_PAD_LEFT) }}/2022</strong> de fecha <strong>{{ date('d', strtotime($addendum->start)) }}</strong> de <strong>{{ $months[intval(date('m', strtotime($addendum->start)))] }}</strong> de <strong>{{ date('Y', strtotime($addendum->start)) }}</strong> al <strong>{{ date('d', strtotime($addendum->finish)) }}</strong> de <strong>{{ $months[intval(date('m', strtotime($addendum->finish)))] }}</strong> de <strong>{{ date('Y', strtotime($addendum->finish)) }} </strong>, excepto la Cl&aacute;usula Sexta y octava que est&aacute; siendo modificada mediante el presente contrato modificatorio y que se refiere a la <strong>vigencia y monto de la prestaci&oacute;n del servicio.</strong></p>
        <p><strong>CL&Aacute;USULA SEXTA. -</strong><strong> (Conformidad)</strong></p>
        <p>En se&ntilde;al de aceptaci&oacute;n y conformidad con todas y cada una de las cl&aacute;usulas establecidas en el presente <strong>CONTRATO</strong> <strong>MODIFICATORIO</strong>, firman las partes manifestando su entero consentimiento y conformidad con todas y cada una de las cl&aacute;usulas del presente documento oblig&aacute;ndose a su fiel y estricto cumplimiento, en cuya se&ntilde;al suscriben en cuatro ejemplares de un mismo tenor.&nbsp;</p>
        <p>Este documento, conforme a disposiciones legales de control fiscal vigente, ser&aacute; registrado ante la Contralor&iacute;a General del Estado.</p>
        <p>&nbsp;</p>
        <p style="text-align: right;">
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
            <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($addendum->start)) }} de {{ $months[intval(date('m', strtotime($addendum->start)))] }} de {{ date('Y', strtotime($addendum->start)) }}
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