@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Consultor de Línea')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = str_pad($contract->code, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($contract->start));
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Consultor de línea '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' al '.date('d', strtotime($contract->finish)).' de '.$months[intval(date('m', strtotime($contract->finish)))].' de '.date('Y', strtotime($contract->finish)).' con un sueldo de '.number_format($contract->cargo->nivel->Sueldo, 2, ',', '.').' Bs.'); !!}
    </div>
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 17px">CONTRATO ADMINISTRATIVO DE SERVICIO DE CONSULTORÍA DE LÍNEA <br> SECRETARÍA DEPARTAMENTAL DE ADMINSTRACIÓN Y FINANZAS <br> <small>UJ/SDAF/GAD-BENI N&deg; {{ $code }}</small> </h2>
        <p>Conste por el presente Contrato Administrativo de prestaci&oacute;n de servicios de consultor&iacute;a de l&iacute;nea<em><strong>, </strong></em>que celebran por una parte la Gobernaci&oacute;n del Departamento Aut&oacute;nomo del Beni, a trav&eacute;s de su Secretar&iacute;a Departamental de Administraci&oacute;n y Finanzas, con <strong>NIT N&ordm; 177396029</strong>, con domicilio en el edificio de Gobernaci&oacute;n en Acera Sud de la Plaza Mariscal Jos&eacute; Ballivi&aacute;n, en la ciudad de la Sant&iacute;sima Trinidad, Provincia Cercado del Departamento del Beni, representado legalmente por el <strong>{{ setting('firma-autorizada.name') }}</strong>, con Cédula de Identidad <strong>N&deg; {{ setting('firma-autorizada.ci') }}</strong>, quien es RPC y RPA., conforme <strong>Resoluci&oacute;n Administrativa de Gobernaci&oacute;n {{ setting('firma-autorizada.designation') }},</strong> en calidad de {{ setting('firma-autorizada.job') }}, que en adelante se denominar&aacute; la <strong>ENTIDAD</strong>; y de la otra parte, {{ $contract->person->gender == 'masculino' ? 'El Señor' : 'La Señora' }} </strong><strong>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, con c&eacute;dula de Identidad <strong>N&deg; {{ $contract->person->ci }}</strong>, que en adelante se denominar&aacute; <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, quienes celebran y suscriben el presente Contrato Administrativo, de acuerdo a los t&eacute;rminos y condiciones siguientes:</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA PRIMERA</strong></span><strong>. - (ANTECEDENTES) </strong></p>
        <p>La <strong>ENTIDAD</strong>, mediante la modalidad de contrataci&oacute;n menor,<strong> </strong>en proceso realizado bajo las normas y regulaciones de contrataci&oacute;n establecidas.</p>
        <p>En el Decreto Supremo N&deg; 0181, de 28 de junio de 2009, de las Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios NB-SABS y los T&eacute;rminos de Referencia (TDR), invito a {{ $contract->person->gender == 'masculino' ? 'el Señor' : 'la Señora' }} </strong><strong>{{ $contract->person->first_name }} {{ $contract->person->last_name }},</strong> para que preste los servicios de Consultor&iacute;a Individual de L&iacute;nea para el cargo de <strong>&ldquo;{{ $contract->cargo->Descripcion }}&rdquo;</strong>, con cargo al Programa <strong>&ldquo;{{ Str::upper($contract->program->name) }}&rdquo;</strong>, de la/el <strong>{{ Str::upper($contract->unidad_administrativa->Nombre) }} </strong>dependiente de la/el <strong>{{ Str::upper($contract->direccion_administrativa->NOMBRE) }},</strong><strong> </strong>quien en adelante se denominara <strong>LA CONSULTORIA</strong>, provistos por <strong>LA CONSULTOR/A</strong> de conformidad a t&eacute;rminos de referencia, con estricta y absoluta sujeci&oacute;n a este contrato.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA SEGUNDA</strong></span><strong>. - (LEGISLACI&Oacute;N APLICABLE) </strong></p>
        <p>El presente Contrato se celebra exclusivamente al amparo de las siguientes disposiciones:</p>
        <ul>
        <li>
        <p>Constituci&oacute;n Pol&iacute;tica del Estado.</p>
        </li>
        <li>
        <p>Ley N&ordm; 1178, de 20 de julio de 1990 de Administraci&oacute;n y Control Gubernamentales.</p>
        </li>
        <li>
        <p>Decreto Supremo N&ordm; 0181, de 28 de junio de 2009, de las Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios NB-SABS.</p>
        </li>
        <li>
        <p>Ley del presupuesto General aprobado para la gesti&oacute;n.</p>
        </li>
        <li>
        <p>Ley N&deg; 2341, Ley del Procedimiento Administrativo.</p>
        </li>
        <li>
        <p>Decreto Supremo N&ordm; 27113, de 23 de julio de 2003, Reglamento a la Ley de Procedimiento Administrativo.</p>
        </li>
        <li>
        <p>Las dem&aacute;s disposiciones relacionadas directamente con las normas anteriormente mencionadas.</p>
        </li>
        </ul>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA TERCERA</strong></span><strong>. - (OBJETO Y CAUSA)</strong></p>
        <p>El objeto y causa del presente contrato es contratar los servicios de un consultor Individual de L&iacute;nea en el cargo de <strong>&ldquo;TECNICO IV&rdquo;</strong> para dar apoyo a la <strong>SECCI&Oacute;N DE MEMORIA INSTITUCIONAL</strong><strong> </strong>dependiente de la <strong>SECRETARIA DEPARTAMENTAL DE ADMINISTRACI&Oacute;N Y FINANZAS GAD-BENI.</strong></p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>

        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA CUARTA</strong></span><strong>. - (OBLIGACIONES DEL CONSULTOR) </strong></p>
        <p><strong>LA CONSULTORA</strong><strong> </strong>se compromete y obliga a efectuar la prestaci&oacute;n del CONSULTOR/A, objeto del presente contrato de acuerdo a las especificaciones t&eacute;cnicas, caracter&iacute;sticas, cantidades, plazo y lugar se&ntilde;alado en los T&eacute;rminos de Referencia, condiciones generales de su propuesta que forma parte del presente documento, as&iacute; mismo deber&aacute; registrarse en el Reloj Biom&eacute;trico a objeto de llevar el respectivo control de asistencia, conforme a los t&eacute;rminos y condiciones de este contrato entre otros, los siguiente:</p>
        <p><strong>1. EL CONSULTOR/A</strong>, se compromete y obliga a realizar el marcado de entrada y salida en el reloj biom&eacute;trico, teniendo en cuenta y aceptando que, de no hacerlo, se proceder&aacute; a realizar descuentos por atrasos, de acuerdo a lo que se establece en los T&eacute;rminos de Referencia (TDR.), los mismos que forman parte indisoluble del presente contrato.</p>
        <p><strong>2. </strong><strong>EL CONSULTOR/A</strong>, se compromete y obliga a efectuar la prestaci&oacute;n del servicio, objeto del siguiente contrato en los plazos y lugar se&ntilde;alado en los t&eacute;rminos de referencia, TDR.</p>
        <p><strong>3.</strong> <strong>EL CONSULTOR/A</strong>, prestara un informe mensual sobre el avance de las tareas en ejecuci&oacute;n o los t&eacute;rminos de referencia</p>
        <p><strong>4.</strong> <strong>EL CONSULTOR/A</strong>, se compromete y obliga a presentar los servicios descritos en los t&eacute;rminos de referencia con diligencia, eficiencia, &eacute;tica e integridad profesional, tomando en cuenta la naturaleza y el prop&oacute;sito del contrato y la confidencialidad de informaci&oacute;n y documentaci&oacute;n que se maneja.</p>
        <p><strong>5.</strong> <strong>EL CONSULTOR/A</strong>, asumir&aacute; total responsabilidad en la ejecuci&oacute;n del trabajo efectuado, oblig&aacute;ndose a la preservaci&oacute;n de la documentaci&oacute;n activa y pasiva, as&iacute; como de los equipos y materiales que se le hubiesen confiado y devolverlos en igual condici&oacute;n.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA QUINTA</strong></span><strong>. - (DOCUMENTOS INTEGRANTES DEL CONTRATO) </strong></p>
        <p>Para cumplimiento del presente Contrato, forman parte del mismo los siguientes documentos:</p>
        <ul>
            <li>
                <p>T&eacute;rminos de Referencia.</p>
            </li>
            <li>
                <p>Certificado de no adeudo emitido por la Unidad de Cierre y Cargo del Gobierno Aut&oacute;nomo Departamental del Beni.</p>
            </li>
            <li>
                <p>Certificaci&oacute;n Presupuestaria.</p>
            </li>
            <li>
                <p>Rupe</p>
            </li>
            <li>
                <p>pac</p>
            </li>
            <li>
                <p>Fotocopia de Numero de Identificaci&oacute;n Tributaria (NIT)</p>
            </li>
            <li>
                <p>Invitaci&oacute;n</p>
            </li>
            <li>
                <p>Respuesta</p>
            </li>
            <li>
                <p>Curriculum Vitae.</p>
            </li>
        </ul>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA SEXTA. -</strong></span><strong> (VIGENCIA Y </strong><strong>TIEMPO DE PRESTACI&Oacute;N DEL SERVICIO</strong><strong>) </strong></p>
        <p>El contrato, entrar&aacute; en vigencia desde el d&iacute;a siguiente h&aacute;bil de su suscripci&oacute;n, por ambas partes, hasta que las mismas hayan dado cumplimento a todas las cl&aacute;usulas contenidas en el presente contrato y el tiempo de la prestaci&oacute;n del servicio, se extender&aacute; desde el <strong>02 de diciembre hasta el 31 del 2021.</strong></p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>
        
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA SEPTIMA</strong></span><strong>. - (LUGAR DE PRESTACI&Oacute;N DE SERVICIOS). </strong></p>
        <p><strong>EL CONSULTOR/A</strong> realizar&aacute; la <strong>CONSULTOR&Iacute;A</strong>, objeto del presente contrato en las oficinas de la <strong>SECCI&Oacute;N DE MEMORIA INSTITUCIONAL </strong>dependiente de la <strong>SECRETARIA DEPARTAMENTAL DE ADMINISTRACI&Oacute;N Y FINANZAS GAD-BENI, </strong>ubicada en el edificio central del GAD-BENI frente de la plaza principal.<strong> </strong></p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA OCTAVA</strong></span><strong>. - (DEL MONTO Y FORMA DE PAGO)</strong></p>
        <p>El monto total del contrato es de<strong> </strong><strong>Bs. 3.866, 66.- (Tres Mil Ochocientos Sesenta y Seis con 66/100 bolivianos)</strong>, el mismo que ser&aacute; cancelado en <strong>una (01)</strong> cuotas.</p>
        <p>El pago se efectuar&aacute; de la siguiente manera en una sola cuota correspondiente a <strong>veintinueve (29) </strong>d&iacute;as del mes de <strong>diciembre </strong>por un monto <strong>Bs. 3.866, 66.- (Tres Mil Ochocientos Sesenta y Seis con 66/100 bolivianos).</strong><strong> </strong></p>
        <p>Para efectos del pago de sus haberes mensuales, <strong>EL CONSULTOR/A</strong><strong>, </strong>deber&aacute; de presentar Informe de Actividades mensuales, el cual deber&aacute; de estar debidamente aprobado por su inmediato superior.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA NOVENA</strong></span><strong>.- (PASAJES Y VIATICOS)</strong></p>
        <p>Los gastos por pasajes, vi&aacute;ticos que se requieran para el desarrollo de las actividades del consultor en las provincias y/o capitales de Bolivia, ser&aacute;n proporcionados por el Gobierno Departamental Aut&oacute;nomo del Beni.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA</strong></span><strong>. - (PAGO DE AFP.) </strong></p>
        <p>Correr&aacute; por cuenta del <strong>EL CONSULTOR/A</strong> el pago de la su AFP y al momento de solicitar la cancelaci&oacute;n de sus haberes mensuales deber&aacute; adjuntar el formulario mediante el cual se evidencie la cancelaci&oacute;n del mismo.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA PRIMERA</strong></span><strong>. - (DE LAS ESTIPULACIONES SOBRE IMPUESTOS) </strong></p>
        <p>Correr&aacute; por cuenta del <strong>EL CONSULTOR/A</strong><strong> </strong>el pago de todos los impuestos vigentes en el pa&iacute;s.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA SEGUNDA</strong></span><strong>. - (PREVISI&Oacute;N) </strong></p>
        <p>El Contrato s&oacute;lo podr&aacute; modificarse previa aprobaci&oacute;n de la MAE., las causas modificatorias deber&aacute;n ser sustentadas por informe t&eacute;cnico y legal que establezcan la viabilidad t&eacute;cnica y de financiamiento</p>
        <p>&nbsp;</p>
        <p>La Referida Modificaci&oacute;n se realizar&aacute; mediante un Contrato Modificatorio, establecido en el Art&iacute;culo 89 del Decreto Supremo N&deg; 0181, de 28 de junio de 2009, de las Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios &ndash; NB-SABS.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA TERCERA</strong></span><strong>. - (CESI&Oacute;N) </strong></p>
        <p>EL CONSULTOR/A<strong>, </strong>no podr&aacute; transferir parcial, ni totalmente las obligaciones contra&iacute;das en el presente Contrato, siendo de su entera responsabilidad la ejecuci&oacute;n y cumplimiento de las obligaciones establecidas en el mismo.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA CUARTA</strong></span><strong>. - (CONFIDENCIALIDAD) </strong></p>
        <p>Los materiales producidos por <strong>EL CONSULTOR/A</strong>,<strong> </strong>as&iacute; como la informaci&oacute;n a la que esta tuviere acceso, durante o despu&eacute;s de la ejecuci&oacute;n del presente contrato tendr&aacute; car&aacute;cter confidencial, quedando expresamente prohibida su divulgaci&oacute;n a terceros, excepto a la Entidad, a menos que cuente con un pronunciamiento escrito por parte de la <strong>ENTIDAD </strong>en sentido contrario.</p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>
        
        <p>As&iacute; mismo <strong>EL CONSULTOR/A</strong> reconoce que la <strong>ENTIDAD </strong>es el &uacute;nico propietario de los productos y documentos producidos por <strong>EL CONSULTOR/A</strong><strong>, </strong>producto del presente Contrato.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA QUINTA</strong></span><strong>. - (EXONERACI&Oacute;N A LA ENTIDAD DE RESPONSABILIDADES POR DA&Ntilde;OS A TERCEROS) </strong></p>
        <p><strong>EL CONSULTOR/A</strong> se obliga a tomar todas las previsiones que pudiesen surgir por da&ntilde;o a terceros, exonerando de estas obligaciones a la <strong>ENTIDAD. </strong></p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA SEXTA</strong></span><strong>. - (EXTINCI&Oacute;N DEL CONTRATO) </strong></p>
        <p>Se dar&aacute; por terminado el v&iacute;nculo contractual por una de las siguientes modalidades:</p>
        <p><strong>1. Por Cumplimiento de Contrato: </strong></p>
        <p>Tanto la <strong>ENTIDAD </strong>como <strong>EL CONSULTOR/A</strong> dar&aacute;n por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas y cada una de las clausulas contenidas en el mismo, lo cual se har&aacute; constar por escrito.</p>
        <p><strong>2. Por Resoluci&oacute;n del contrato: </strong></p>
        <p><strong>2.1 A requerimiento de la ENTIDAD, por causales atribuibles al</strong><strong> CONSULTOR/A</strong><strong>: </strong></p>
        <p>a) Por incumplimiento en la realizaci&oacute;n de la <strong>CONSULTOR&Iacute;A </strong>en el plazo establecido.</p>
        <p>b) Por disoluci&oacute;n <strong>DEL</strong><strong> CONSULTOR/A</strong>.</p>
        <p>c) Por falta de cumplimiento a los TDR.</p>
        <p><strong>2.2 A requerimiento del</strong><strong> CONSULTOR/A</strong><strong>, por causales atribuibles a la ENTIDAD: </strong></p>
        <p>a) Si apart&aacute;ndose de los t&eacute;rminos del Contrato, la <strong>ENTIDAD </strong>pretende efectuar modificaciones a las especificaciones T&eacute;cnicas.</p>
        <p>b) Por incumplimiento injustificado en los pagos contra entregas parciales, por m&aacute;s de noventa (90) d&iacute;as calendario computados a partir de la fecha de entrega de los productos establecidos en los T&eacute;rminos de Referencia.</p>
        <p><strong>2.3 Por causas de fuerza mayor o caso fortuito que afecten a la ENTIDAD o:</strong></p>
        <p>Si se presentaran situaciones de fuerza mayor o caso fortuito que imposibiliten la prestaci&oacute;n del servicio o vayan contra los intereses del Estado, se resolver&aacute; el Contrato total o parcialmente.</p>
        <p>Cuando se efect&uacute;e la Resoluci&oacute;n del Contrato se proceder&aacute; a una liquidaci&oacute;n de saldos deudores y acreedores de ambas partes, efectu&aacute;ndose los pagos a que hubiere lugar, conforme la evaluaci&oacute;n del grado de cumplimiento de los t&eacute;rminos de referencia.</p>
        <p><strong>2.4 Por acuerdo entre partes:</strong> Proceder&aacute; cuando ambas partes otorguen su consentimiento con el objetivo de terminar con la Relaci&oacute;n contractual, la cual deber&aacute;n comunicar con anticipaci&oacute;n y oportunamente dicha intenci&oacute;n de manera escrita.</p>
        <p>Cuando se efect&uacute;e la Resoluci&oacute;n del Contrato se proceder&aacute; a una liquidaci&oacute;n de saldos deudores y acreedores de ambas partes, efectu&aacute;ndose los pagos a que hubiere lugar, conforme la evaluaci&oacute;n del grado de cumplimiento de los t&eacute;rminos de referencia.</p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA SEPTIMA</strong></span><strong>. - (SOLUCI&Oacute;N DE CONTROVERSIAS) </strong></p>
        <p>En caso surgir dudas sobre los derechos y obligaciones de las partes durante la ejecuci&oacute;n del presente contrato, las partes acudir&aacute;n a los t&eacute;rminos y condiciones del contrato, T&eacute;rminos de Referencia, propuesta adjudicada, sometidas a la Jurisdicci&oacute;n Coactiva Fiscal.</p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>
        
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA OCTAVA</strong></span><strong>. - (EL VINCULO). - </strong>Por la Naturaleza de la relaci&oacute;n contractual eventual y espec&iacute;fica queda establecido que <strong>EL CONSULTOR/A</strong> no recibir&aacute; beneficio adicional alguno por parte de la <strong>ENTIDAD.</strong></p>
        <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA NOVENA</strong></span><strong>. - (CONSENTIMIENTO) </strong></p>
        <p>En se&ntilde;al de conformidad y para su fiel y estricto cumplimiento, firmamos el presente Contrato en cuatro ejemplares de un mismo tenor y validez el <strong>{{ setting('firma-autorizada.name') }}</strong>, Secretario Departamental de Administraci&oacute;n y Finanzas, en representaci&oacute;n legal de la <strong>ENTIDAD</strong>, y la se&ntilde;ora <strong>Se&ntilde;or/a. Marcela Escobar M&eacute;ndez</strong>, como <strong>EL CONSULTOR/A</strong>.</p>
        <p>Este documento, conforme a disposiciones legales de control fiscal vigentes, ser&aacute; registrado ante la Contralor&iacute;a General del Estado en idioma espa&ntilde;ol.</p>
        <p style="text-align: right;">Sant&iacute;sima Trinidad, 01 de diciembre del 2021.</p>
        
        <br>

        <table width="100%" style="text-align: center; margin: 80px 0px; margin-bottom: 50px">
            <tr>
                <td style="width: 50%">
                    ....................................................... <br>
                    {{ setting('firma-autorizada.name') }} <br>
                    <b>{{ setting('firma-autorizada.job') }}</b>
                </td>
                <td style="width: 50%">
                    ....................................................... <br>
                    Sr. Jose Perez Perez <br>
                    <b>contratado</b>
                </td>
            </tr>
        </table>

    </div>
@endsection

@section('css')
    <style>
        .content {
            padding: 50px 34px;
            font-size: 15px;
        }
        .text-center{
            text-align: center;
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
                height: 100px;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection