@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Consultor de Línea')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $signature = $contract->signature;

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
            $qrcode = QrCode::size(70)->generate('CONTRATO ADMINISTRATIVO DE SERVICIO DE CONSULTORÍA DE LÍNEA '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' al '.date('d', strtotime($contract_finish)).' de '.$months[intval(date('m', strtotime($contract_finish)))].' de '.date('Y', strtotime($contract_finish)).' con un sueldo de '.number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.').' Bs.');
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
            <div class="page-head">
                <h3>CONTRATO ADMINISTRATIVO DE SERVICIO DE CONSULTORÍA DE LÍNEA <br> {{ $signature ? $signature->direccion_administrativa->nombre : 'SECRETARÍA DEPARTAMENTAL DE ADMINSTRACIÓN Y FINANZAS' }} <br>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</h3>
            </div>
            <p>Conste por el presente Contrato Administrativo de prestaci&oacute;n de servicios de consultor&iacute;a de l&iacute;nea<em><strong>, </strong></em>que celebran por una parte la Gobernaci&oacute;n Autónoma del Departamento del Beni, a trav&eacute;s de su {!! $signature ? $signature->direccion_administrativa->nombre : 'Secretar&iacute;a Departamental de Administraci&oacute;n y Finanzas' !!}, con <strong>NIT N&ordm; {{ $contract->direccion_administrativa->nit ?? '177396029' }}</strong>, con domicilio ubicado en {{ $contract->direccion_administrativa->direccion ?? 'el edificio de Gobernación en Acera Sud de la Plaza Mariscal José Ballivián' }}, en la Ciudad/Localidad de {{ $contract->direccion_administrativa->city->name ?? 'la SANTISIMA TRINIDAD' }}, Provincia {{ $contract->direccion_administrativa->city->province ?? 'CERCADO' }} del Departamento del BENI, representado legalmente por <strong>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}</strong>, con Cédula de Identidad <strong>N&deg; {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}</strong>, conforme a <strong>{{ $signature ? $signature->designation_type : 'Resolución de Gobernación' }} N&deg; {{ $signature ? $signature->designation : setting('firma-autorizada.designation-alt') }}</strong>, en calidad de {{ $signature ? $signature->job : setting('firma-autorizada.job-alt') }}, que en adelante se denominar&aacute; <strong>LA ENTIDAD</strong>; y de la otra parte, {{ $contract->person->gender == 'masculino' ? 'El Señor' : 'La Señora' }} </strong><strong>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, con c&eacute;dula de Identidad <strong>N&deg; {{ $contract->person->ci }}</strong>, que en adelante se denominar&aacute; <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, quienes celebran y suscriben el presente Contrato Administrativo, de acuerdo a los t&eacute;rminos y condiciones siguientes:</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA PRIMERA</strong></span><strong>. - (ANTECEDENTES) </strong></p>
            <p><strong>LA ENTIDAD</strong>, mediante la modalidad de contrataci&oacute;n menor,<strong> </strong>en proceso realizado bajo las normas y regulaciones de contrataci&oacute;n establecidas.</p>
            <p>En el Decreto Supremo N&deg; 0181, de 28 de junio de 2009, de las Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios NB-SABS y los T&eacute;rminos de Referencia (TDR), invito {{ $contract->person->gender == 'masculino' ? 'al Señor' : 'a la Señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, para que preste los servicios de Consultor&iacute;a Individual de L&iacute;nea para el cargo de <strong>&ldquo;{{ Str::upper($contract->cargo->Descripcion) }}&rdquo;</strong>, con cargo al {{ ucfirst($contract->program->class) }} <strong>&ldquo;{{ Str::upper($contract->program->name) }}&rdquo;</strong>, de la/el <strong>{{ Str::upper($contract->unidad_administrativa->nombre) }} </strong>dependiente de la/el <strong>{{ Str::upper($contract->direccion_administrativa->nombre) }},</strong><strong> </strong>quien en adelante se denominara <strong>LA CONSULTORIA</strong>, provistos por <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong> de conformidad a t&eacute;rminos de referencia, con estricta y absoluta sujeci&oacute;n a este contrato.</p>
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
            <p>El objeto y causa del presente contrato es contratar los servicios de un consultor Individual de L&iacute;nea en el cargo de <strong>&ldquo;{{ Str::upper($contract->cargo->Descripcion) }}&rdquo;</strong> para dar apoyo a la/el <strong>{{ Str::upper($contract->unidad_administrativa->nombre) }}</strong><strong> </strong>dependiente de la <strong>{{ Str::upper($contract->direccion_administrativa->nombre) }}.</strong></p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA CUARTA</strong></span><strong>. - (OBLIGACIONES {{ $contract->person->gender == 'masculino' ? 'DEL CONSULTOR' : 'DE LA CONSULTORA' }}) </strong></p>
            <p><strong>LA CONSULTORA</strong><strong> </strong>se compromete y obliga a efectuar la prestaci&oacute;n de {{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}, objeto del presente contrato de acuerdo a las especificaciones t&eacute;cnicas, caracter&iacute;sticas, cantidades, plazo y lugar se&ntilde;alado en los T&eacute;rminos de Referencia, condiciones generales de su propuesta que forma parte del presente documento, as&iacute; mismo deber&aacute; registrarse en el Reloj Biom&eacute;trico - Planilla de asistencia manual a objeto de llevar el respectivo control de asistencia, conforme a los t&eacute;rminos y condiciones de este contrato entre otros, los siguiente:</p>
            <p><strong>1. {{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, se compromete y obliga a realizar el marcado de entrada y salida en el reloj biom&eacute;trico, teniendo en cuenta y aceptando que, de no hacerlo, se proceder&aacute; a realizar descuentos por atrasos, de acuerdo a lo que se establece en los T&eacute;rminos de Referencia (TDR.), los mismos que forman parte indisoluble del presente contrato.</p>
            <p><strong>2. </strong><strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, se compromete y obliga a efectuar la prestaci&oacute;n del servicio, objeto del siguiente contrato en los plazos y lugar se&ntilde;alado en los t&eacute;rminos de referencia, TDR.</p>
            <p><strong>3.</strong> <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, prestara un informe mensual sobre el avance de las tareas en ejecuci&oacute;n o los t&eacute;rminos de referencia</p>
            <p><strong>4.</strong> <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, se compromete y obliga a presentar los servicios descritos en los t&eacute;rminos de referencia con diligencia, eficiencia, &eacute;tica e integridad profesional, tomando en cuenta la naturaleza y el prop&oacute;sito del contrato y la confidencialidad de informaci&oacute;n y documentaci&oacute;n que se maneja.</p>
            <p><strong>5.</strong> <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, asumir&aacute; total responsabilidad en la ejecuci&oacute;n del trabajo efectuado, oblig&aacute;ndose a la preservaci&oacute;n de la documentaci&oacute;n activa y pasiva, as&iacute; como de los equipos y materiales que se le hubiesen confiado y devolverlos en igual condici&oacute;n.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA QUINTA</strong></span><strong>. - (DOCUMENTOS INTEGRANTES DEL CONTRATO) </strong></p>
            <p>Para cumplimiento del presente Contrato, forman parte del mismo los siguientes documentos:</p>
            <ul>
                <li>
                    <p>T&eacute;rminos de Referencia.</p>
                </li>
                <li>
                    <p>Certificado de no adeudo emitido por la Unidad de Cierre y Cargo del Gobierno Aut&oacute;nomo Departamental del Beni, si corresponde.</p>
                </li>
                <li>
                    <p>Certificaci&oacute;n Presupuestaria.</p>
                </li>
                <li>
                    <p>Rupe</p>
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
            <p>El contrato, entrar&aacute; en vigencia desde la suscripci&oacute;n, por ambas partes, hasta que las mismas hayan dado cumplimento a todas las cl&aacute;usulas contenidas en el presente contrato y el tiempo de la prestaci&oacute;n del servicio, se extender&aacute; desde el <strong>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} al {{ date('d', strtotime($contract_finish)) }} de {{ $months[intval(date('m', strtotime($contract_finish)))] }} de {{ date('Y', strtotime($contract->start)) }}.</strong></p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA SEPTIMA</strong></span><strong>. - (LUGAR DE PRESTACI&Oacute;N DE SERVICIOS). </strong></p>
            <p><strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong> realizar&aacute; la <strong>CONSULTOR&Iacute;A</strong>, objeto del presente contrato en las oficinas de la/el <strong>{{ Str::upper($contract->unidad_administrativa->nombre) }} </strong>dependiente de la/el <strong>{{ Str::upper($contract->direccion_administrativa->nombre) }}. </strong>{{--ubicada en el edificio central del GAD-BENI frente de la plaza principal.--}}</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA OCTAVA</strong></span><strong>. - (DEL MONTO Y FORMA DE PAGO)</strong></p>
            @include('management.docs.consultor.partials.payment_details', ['subtitle' => '', 'contract' => $contract, 'contract_start' => $contract->start, 'contract_finish' => $contract_finish])
            <p>Para efectos del pago de sus haberes mensuales, <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>, deber&aacute; de presentar Informe de Actividades mensuales, el cual deber&aacute; de estar debidamente aprobado por su inmediato superior.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA NOVENA</strong></span><strong>.- (PASAJES Y VIATICOS)</strong></p>
            <p>Los gastos por pasajes, vi&aacute;ticos que se requieran para el desarrollo de las actividades {{ $contract->person->gender == 'masculino' ? 'del consultor' : 'de la consultora' }} en las provincias y/o capitales de Bolivia, ser&aacute;n proporcionados por el Gobierno Aut&oacute;nomo Departamental del Beni.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA</strong></span><strong>. - (PAGO DE AFP.) </strong></p>
            <p>Correr&aacute; por cuenta del <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong> el pago de la su AFP y al momento de solicitar la cancelaci&oacute;n de sus haberes mensuales deber&aacute; adjuntar el formulario mediante el cual se evidencie la cancelaci&oacute;n del mismo.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA PRIMERA</strong></span><strong>. - (DE LAS ESTIPULACIONES SOBRE IMPUESTOS) </strong></p>
            <p>Correr&aacute; por cuenta del <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>el pago de todos los impuestos vigentes en el pa&iacute;s.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA SEGUNDA</strong></span><strong>. - (PREVISI&Oacute;N) </strong></p>
            <p>El Contrato s&oacute;lo podr&aacute; modificarse previa aprobaci&oacute;n de la MAE., las causas modificatorias deber&aacute;n ser sustentadas por informe t&eacute;cnico y legal que establezcan la viabilidad t&eacute;cnica y de financiamiento</p>
            <p>La Referida Modificaci&oacute;n se realizar&aacute; mediante un Contrato Modificatorio, establecido en el Art&iacute;culo 89 del Decreto Supremo N&deg; 0181, de 28 de junio de 2009, de las Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios &ndash; NB-SABS.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA TERCERA</strong></span><strong>. - (CESI&Oacute;N) </strong></p>
            <p>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}<strong>, </strong>no podr&aacute; transferir parcial, ni totalmente las obligaciones contra&iacute;das en el presente Contrato, siendo de su entera responsabilidad la ejecuci&oacute;n y cumplimiento de las obligaciones establecidas en el mismo.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA CUARTA</strong></span><strong>. - (CONFIDENCIALIDAD) </strong></p>
            <p>Los materiales producidos por <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>,<strong> </strong>as&iacute; como la informaci&oacute;n a la que esta tuviere acceso, durante o despu&eacute;s de la ejecuci&oacute;n del presente contrato tendr&aacute; car&aacute;cter confidencial, quedando expresamente prohibida su divulgaci&oacute;n a terceros, excepto a LA ENTIDAD, a menos que cuente con un pronunciamiento escrito por parte de <strong>LA ENTIDAD </strong>en sentido contrario.</p>
            <p>As&iacute; mismo <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong> reconoce que <strong>LA ENTIDAD </strong>es el &uacute;nico propietario de los productos y documentos producidos por <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong><strong>, </strong>producto del presente Contrato.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA QUINTA</strong></span><strong>. - (EXONERACI&Oacute;N A LA ENTIDAD DE RESPONSABILIDADES POR DA&Ntilde;OS A TERCEROS) </strong></p>
            <p><strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong> se obliga a tomar todas las previsiones que pudiesen surgir por da&ntilde;o a terceros, exonerando de estas obligaciones a <strong>LA ENTIDAD. </strong></p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA SEXTA</strong></span><strong>. - (EXTINCI&Oacute;N DEL CONTRATO) </strong></p>
            <p>Se dar&aacute; por terminado el v&iacute;nculo contractual por una de las siguientes modalidades:</p>
            <p><strong>1. Por Cumplimiento de Contrato: </strong></p>
            <p>Tanto <strong>LA ENTIDAD </strong>como <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong> dar&aacute;n por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas y cada una de las clausulas contenidas en el mismo, lo cual se har&aacute; constar por escrito.</p>
            <p><strong>2. Por Resoluci&oacute;n del contrato: </strong></p>
            <p><strong>2.1 A requerimiento de LA ENTIDAD, por causales atribuibles al</strong><strong> {{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong><strong>: </strong></p>
            <p>a) Por incumplimiento en la realizaci&oacute;n de la <strong>CONSULTOR&Iacute;A </strong>en el plazo establecido.</p>
            <p>b) Por disoluci&oacute;n<strong> {{ $contract->person->gender == 'masculino' ? 'DEL CONSULTOR' : 'DE LA CONSULTORA' }}</strong>.</p>
            <p>c) Por falta de cumplimiento a los TDR.</p>
            <p><strong>2.2 A requerimiento del</strong><strong> {{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong><strong>, por causales atribuibles a LA ENTIDAD: </strong></p>
            <p>a) Si apart&aacute;ndose de los t&eacute;rminos del Contrato, <strong>LA ENTIDAD </strong>pretende efectuar modificaciones a las especificaciones T&eacute;cnicas.</p>
            <p>b) Por incumplimiento injustificado en los pagos contra entregas parciales, por m&aacute;s de noventa (90) d&iacute;as calendario computados a partir de la fecha de entrega de los productos establecidos en los T&eacute;rminos de Referencia.</p>
            <p><strong>2.3 Por causas de fuerza mayor o caso fortuito que afecten a LA ENTIDAD o:</strong></p>
            <p>Si se presentaran situaciones de fuerza mayor o caso fortuito que imposibiliten la prestaci&oacute;n del servicio o vayan contra los intereses del Estado, se resolver&aacute; el Contrato total o parcialmente.</p>
            <p>Cuando se efect&uacute;e la Resoluci&oacute;n del Contrato se proceder&aacute; a una liquidaci&oacute;n de saldos deudores y acreedores de ambas partes, efectu&aacute;ndose los pagos a que hubiere lugar, conforme la evaluaci&oacute;n del grado de cumplimiento de los t&eacute;rminos de referencia.</p>
            <p><strong>2.4 Por acuerdo entre partes:</strong> Proceder&aacute; cuando ambas partes otorguen su consentimiento con el objetivo de terminar con la Relaci&oacute;n contractual, la cual deber&aacute;n comunicar con anticipaci&oacute;n y oportunamente dicha intenci&oacute;n de manera escrita.</p>
            <p>Cuando se efect&uacute;e la Resoluci&oacute;n del Contrato se proceder&aacute; a una liquidaci&oacute;n de saldos deudores y acreedores de ambas partes, efectu&aacute;ndose los pagos a que hubiere lugar, conforme la evaluaci&oacute;n del grado de cumplimiento de los t&eacute;rminos de referencia.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA SEPTIMA</strong></span><strong>. - (SOLUCI&Oacute;N DE CONTROVERSIAS) </strong></p>
            <p>En caso surgir dudas sobre los derechos y obligaciones de las partes durante la ejecuci&oacute;n del presente contrato, las partes acudir&aacute;n a los t&eacute;rminos y condiciones del contrato, T&eacute;rminos de Referencia, propuesta adjudicada, sometidas a la Jurisdicci&oacute;n Coactiva Fiscal.</p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA OCTAVA</strong></span><strong>. - (EL VINCULO). - </strong>Por la Naturaleza de la relaci&oacute;n contractual eventual y espec&iacute;fica queda establecido que <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong> no recibir&aacute; beneficio adicional alguno por parte de <strong>LA ENTIDAD.</strong></p>
            <p><span style="text-decoration: underline;"><strong>CL&Aacute;USULA D&Eacute;CIMA NOVENA</strong></span><strong>. - (CONSENTIMIENTO) </strong></p>
            <p>En se&ntilde;al de conformidad y para su fiel y estricto cumplimiento, firmamos el presente Contrato en cuatro ejemplares de un mismo tenor y validez el <strong>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}</strong>, {{ $signature ? $signature->job : setting('firma-autorizada.job') }}, en representaci&oacute;n legal de <strong>LA ENTIDAD</strong>, y <strong>{{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, como <strong>{{ $contract->person->gender == 'masculino' ? 'EL CONSULTOR' : 'LA CONSULTORA' }}</strong>.</p>
            <p>Este documento, conforme a disposiciones legales de control fiscal vigentes, ser&aacute; registrado ante la Contralor&iacute;a General del Estado en idioma espa&ntilde;ol.</p>
            <br>
            <p style="text-align: right;">
                <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->start)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->start)))]) }} de {{ date('Y', strtotime($contract->start)) }}
            </p>
            <table class="table-signature">
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
    @endif
@endsection

@section('css')
    <style>
        
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endsection