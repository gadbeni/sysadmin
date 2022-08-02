@extends('layouts.template-print-legal')

@section('page_title', 'Adenda')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $addendum = $contract->addendums->where('status', 'firmado')->first();
    $addendum_first = $contract->addendums->where('status', 'concluido')->first();
@endphp

@section('content')
    <div class="page-head">
        <p><strong>CONTRATO MODIFICATORIO DE CONSULTORIA INDIVIDUAL DE LINEA {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }} N&deg; {{ str_pad($addendum->id, 2, "0", STR_PAD_LEFT) }}/2022 RELACIONADO AL CONTRATO {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </strong></p>
    </div>
    <div class="content">
        <p>&nbsp;</p>
        <p>Conste por el presente Contrato Modificatorio de Consultor&iacute;a Individual de L&iacute;nea, que tiene como contrato principal <strong>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</strong> el cual data del <strong>{{ date('d/m/Y', strtotime($contract->start)) }}</strong> hasta el <strong>{{ date('d/m/Y', strtotime($addendum_first->start.' -1 days')) }}</strong>, </strong>y un <strong>Primer Contrato Modificatorio {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }} N&deg; {{ str_pad($addendum_first->id, 2, "0", STR_PAD_LEFT) }}/2022 </strong> el cual data del <strong>{{ date('d/m/Y', strtotime($addendum_first->start)) }}</strong> hasta el <strong>{{ date('d/m/Y', strtotime($addendum_first->finish)) }}</strong>.</p>
        <p>El Gobierno Aut&oacute;nomo Departamental del Beni, a trav&eacute;s de su Secretar&iacute;a Departamental de Administraci&oacute;n y Finanzas, con <strong>NIT</strong> N.&ordm; <strong>177396029</strong>, con domicilio legal en el Edificio Central de la Gobernaci&oacute;n del Beni, Acera Sur de la Plaza Mariscal Jos&eacute; Ballivi&aacute;n, en la ciudad de la Sant&iacute;sima&nbsp; Trinidad, Provincia Cercado, del Departamento del Beni, representado legalmente por <strong>GEISEL MARCELO OLIVA RU&Iacute;Z</strong>, conforme <strong>Resoluci&oacute;n Administrativa de Gobernaci&oacute;n N&deg; </strong><strong>04</strong><strong>/202</strong><strong>2</strong><strong>, de fecha </strong><strong>01</strong><strong> de </strong><strong>febrero</strong><strong> de 202</strong><strong>2</strong> en calidad de <strong>Secretario Departamental de </strong><strong>Administraci&oacute;n y Finanzas</strong>, que en adelante se denominar&aacute; la <strong>ENTIDAD</strong>; y de la otra parte el/la <strong>Sr/Sra. </strong><strong>ADOLFO BALCAZAR BARBERY</strong><strong><em>,</em></strong> mayor de edad, h&aacute;bil en toda forma de derecho, con C&eacute;dula de Identidad <strong>N.&deg; </strong><strong>5604715</strong>, vecina de esta Ciudad, quien en adelante se la denominar&aacute; <strong>EL/LA CONSULTOR(A/O),</strong> quienes celebran y suscriben el <strong>Segundo</strong> <strong><em>Contrato Modificatorio</em></strong>, de acuerdo a los t&eacute;rminos y condiciones siguientes:</p>
        <p>&nbsp;</p>
        <p><strong>CLAUSULA PRIMERA. -</strong><strong> (Antecedentes)</strong></p>
        <p><strong>&nbsp;LA ENTIDAD</strong>, mediante un proceso en la modalidad de <strong>Contrataci&oacute;n Menor</strong>, realizado bajo las normas y regulaciones de contrataci&oacute;n establecidas en el Decreto Supremo N.&ordm; 0181 de fecha 28 de junio del 2009, Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios (NB-SABS) y t&eacute;rminos de referencia, se firm&oacute; el contrato <strong>UJ/SDAF/GAD-BENI N&deg; </strong><strong>57</strong><strong>/202</strong><strong>2</strong><strong> </strong>de fecha <strong>01/02</strong><strong>/202</strong><strong>2</strong><strong> hasta el </strong><strong>21</strong><strong> de </strong><strong>abril</strong><strong>, </strong>y<strong> </strong>&nbsp;un <strong>Primer</strong> <strong>Contrato Modificatorio UJ-SDAF/CM/GADB N&deg; </strong><strong>01</strong><strong>/202</strong><strong>2</strong> el cual data del <strong>22</strong><strong> de </strong><strong>abril</strong><strong> de 202</strong><strong>2</strong><strong> hasta el </strong><strong>12</strong><strong> de julio de 202</strong><strong>2</strong>.&nbsp;</p>
        <p>En atenci&oacute;n al requerimiento de la unidad solicitante, el Informe T&eacute;cnico y legal adem&aacute;s de los documentos adjunto y posterior a ello la aprobaci&oacute;n del responsable de procesos de contrataci&oacute;n se procedi&oacute; a elaborar <strong>el Segundo Contrato Modificatorio</strong> para la prestaci&oacute;n del servicios de consultor&iacute;a individual de l&iacute;nea con relaci&oacute;n al contrato<strong> UJ/SDAF/GAD-BENI N&deg; </strong><strong>57</strong><strong>/202</strong><strong>2</strong><strong> </strong>de fecha <strong>01/02</strong><strong>/202</strong><strong>2</strong><strong> hasta el </strong><strong>21</strong><strong> de </strong><strong>abril</strong><strong>, </strong>y<strong> </strong>&nbsp;un <strong>Primer</strong> <strong>Contrato Modificatorio UJ-SDAF/CM/GADB N&deg; </strong><strong>01</strong><strong>/202</strong><strong>2</strong> el cual data del <strong>22</strong><strong> de </strong><strong>abril</strong><strong> de 202</strong><strong>2</strong><strong> hasta el </strong><strong>12</strong><strong> de </strong><strong>julio</strong><strong> de 202</strong><strong>2</strong>, con cargo al Programa/Proyecto de <strong>&ldquo;</strong><strong>IMPLEMENTACI&Oacute;N DEL SISTEMA DE INTERACCI&Oacute;N SOCIAL&rdquo;</strong>, Categor&iacute;a Program&aacute;tica <strong>16-0-095</strong><strong>, </strong>Partida Presupuestaria <strong>25220</strong>.&nbsp;</p>
        <p>&nbsp;</p>
        <p><strong>CLAUSULA SEGUNDA. -</strong><strong> (Legislaci&oacute;n Aplicable)</strong></p>
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
        <p><strong>CLAUSULA TERCERA. -</strong><strong> (Objeto y Causa)</strong></p>
        <p>El objeto y causa del presente contrato <strong>(SEGUNDO MODIFICATORIO)</strong> es la modificaci&oacute;n del contrato <strong>UJ/SDAF/GAD-BENI N&deg; </strong><strong>57</strong><strong>/202</strong><strong>2</strong>, el cual tiene una vigencia desde el <strong>01</strong><strong> de </strong><strong>febrero</strong> hasta el <strong>21</strong><strong> de </strong><strong>abril</strong><strong> del 202</strong><strong>2</strong><strong>, </strong>y<strong> </strong>&nbsp;un <strong>Primer</strong> <strong>Contrato Modificatorio UJ-SDAF/CM/GADB N&deg; </strong><strong>01</strong><strong>/202</strong><strong>2</strong> el cual data del <strong>22</strong><strong> de </strong><strong>abril</strong><strong> de 202</strong><strong>2</strong><strong> hasta el </strong><strong>12</strong><strong> de </strong><strong>julio</strong><strong> de 202</strong><strong>2</strong>, por consiguiente y en concordancia con lo manifestado en la Cl&aacute;usula Primera del presente documento, se ampl&iacute;a y modifica el contrato <strong>UJ/SDAF/GAD-BENI N&deg; </strong><strong>57</strong><strong>/202</strong><strong>2</strong>, de la siguiente manera:</p>
        <p><strong><em>VIGENCIA. &ndash;</em></strong></p>
        <p>&nbsp;<em>El presente Segundo Contrato Modificatorio entrara en vigencia desde </em><strong><em>el </em></strong><strong><em>13</em></strong><strong><em> de </em></strong><strong><em>julio</em></strong><strong><em> al </em></strong><strong><em>12</em></strong><strong><em> de </em></strong><strong><em>agosto</em></strong><strong><em> del 202</em></strong><strong><em>2</em></strong><strong><em>.</em></strong></p>
        <p><strong><em>MONTO. - </em></strong><em>El monto total del presente contrato modificatorio ser&aacute; por la suma de </em><strong><em>Bs.- </em></strong><strong><em>6000,00</em></strong><em> (Seis Mil 00</em><em>/100 Bolivianos), el pago de esta consultor&iacute;a ser&aacute; de la siguiente manera: En </em><em>dos (02)</em><em> cuotas mensuales, </em>La primera cuota correspondiente a dieciocho (18) d&iacute;as del mes de julio por un monto de <strong>Bs. </strong><strong>3.600,00.- </strong>(Tres Mil Seiscientos 00/100 Bolivianos), la<em> segunda </em><em>y</em><em> </em><em>&uacute;ltima</em><em> cuota correspondiente a </em><em>doce (12) d&iacute;as</em><em> del mes de </em><em>agosto</em><em> por un monto de </em><strong><em>Bs. </em></strong><strong><em>2.400,00.- </em></strong><em>(Dos Mil Cuatrocientos 00</em><em>/100 Bolivianos). La cancelaci&oacute;n por los servicios prestados se realizar&aacute; previa presentaci&oacute;n y aprobaci&oacute;n de informe de actividades de acuerdo a T&eacute;rminos de Referencia, aprobado por el/la </em><em>Director Dptal. De Interacci&oacute;n Social del GAD-BENI.</em></p>
        <p>&nbsp;</p>
        <p><strong>CLAUSULA CUARTA. -</strong><strong> (Documentos que Integran)</strong></p>
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
        <p>Contrato Administrativo de servicio de Consultor&iacute;a de L&iacute;nea <strong>UJ/SDAF/GAD-BENI N&deg; </strong><strong>57</strong><strong>/202</strong><strong>2</strong><strong> </strong>de fecha <strong>01/</strong><strong>02/202</strong><strong>2</strong>.</p>
        </li>
        <li>
        <p>Contrato Modificatorio N&deg; 01/2021.</p>
        </li>
        </ul>
        <p>&nbsp;<strong>CLAUSULA QUINTA. -</strong><strong> (De las Cl&aacute;usulas del Contrato Principal)</strong></p>
        <p><strong>&nbsp;</strong>Quedan subsistentes y con plena validez, las dem&aacute;s cl&aacute;usulas contenidas en el Contrato <strong>UJ/SDAF/GAD-BENI N&deg; </strong><strong>57</strong><strong>/2022 </strong>de fecha <strong>01/02</strong><strong>/2022 hasta el </strong><strong>21/04</strong><strong>/202</strong><strong>2</strong><strong>, </strong>un <strong>Primer</strong> <strong>Contrato Modificatorio UJ-SDAF/CM/GADB N&deg; </strong><strong>01</strong><strong>/202</strong><strong>2</strong> el cual data del <strong>22</strong><strong> de </strong><strong>abril</strong><strong> de 202</strong><strong>2</strong><strong> hasta el </strong><strong>12</strong><strong> de </strong><strong>julio</strong><strong> de 202</strong><strong>2</strong>, y un <strong>Segundo Contrato Modificatorio UJ-SDAF/CM/GADB N&deg; </strong><strong>23</strong><strong>/202</strong><strong>2</strong> el cual data del <strong>13</strong><strong> de </strong><strong>abril</strong><strong> de 202</strong><strong>2</strong><strong> hasta el </strong><strong>12</strong><strong> de </strong><strong>agosto</strong><strong> de 202</strong><strong>2</strong><strong>,</strong> excepto la Cl&aacute;usula Sexta y octava que est&aacute; siendo modificada mediante el presente contrato modificatorio y que se refiere a la <strong>vigencia y monto de la prestaci&oacute;n del servicio.</strong></p>
        <p><strong>CLAUSULA SEXTA. -</strong><strong> (Conformidad)</strong></p>
        <p>En se&ntilde;al de aceptaci&oacute;n y conformidad con todas y cada una de las cl&aacute;usulas establecidas en el presente <strong>CONTRATO</strong> <strong>MODIFICATORIO</strong>, firman las partes manifestando su entero consentimiento y conformidad con todas y cada una de las cl&aacute;usulas del presente documento oblig&aacute;ndose a su fiel y escrito cumplimiento, en cuya se&ntilde;al suscriben en cuatro ejemplares de un mismo tenor.&nbsp;</p>
        <p>Este documento, conforme a disposiciones legales de control fiscal vigente, ser&aacute; registrado ante la contralor&iacute;a general del Estado.</p>
        <p>&nbsp;</p>
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
            text-align: justify;
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

    </script>
@endsection