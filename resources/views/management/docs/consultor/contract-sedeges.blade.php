@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Consultoría de Línea')

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
                <h3>CONTRATO ADMINISTRATIVO N&deg; {{ $code }} <br> "CONSULTOR INDIVIDUAL DE LÍNEA {{ Str::upper($contract->cargo->Descripcion).($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} PARA LA/EL {{ Str::upper($contract->work_location ?? $contract->unidad_administrativa->nombre) }} DEL SEDEGES-BENI" </h3>
            </div>
            
            <p><span >Conste por el presente Contrato Administrativo de Consultor&iacute;a Individual de L&iacute;nea<strong ><em>,</em></strong> que celebran por una parte el Servicio Departamental de Gesti&oacute;n Social del Beni (SEDEGES-Beni), con NIT N&ordm; 177396029, con domicilio en la Calle Manuel Limpias N&ordm; 45, de la ciudad de la Sant&iacute;sima Trinidad, Provincia Cercado del Departamento del Beni, representado legalmente por la/el<strong > {{ $signature ? $signature->name : setting('firma-autorizada.name') }}, <span>&nbsp;</span></strong>con<strong > C.I. N&ordm; {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}</strong>, conforme a <strong >{{ $signature ? $signature->designation_type : 'Resolución de Gobernación' }} N&deg; {{ $signature ? $signature->designation : setting('firma-autorizada.designation-alt') }}</strong>, en calidad de responsable de los procesos de contrataci&oacute;n menor RPA, que en adelante se denominar&aacute; la <strong >ENTIDAD</strong>; y por otra parte, {{ $contract->person->gender == 'masculino' ? 'al Señor' : 'a la Señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}<strong >, </strong>con <strong >C.I. N&ordm; {{ $contract->person->ci }}</strong> que en adelante se denominar&aacute; <strong >{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</strong>, quienes celebran y suscriben el presente Contrato Administrativo, al tenor de las siguientes Cl&aacute;usulas:</span></p>
            <p><strong ><span >CL&Aacute;USULA PRIMERA.- (ANTECEDENTES) </span></strong><span >La<strong ><span>&nbsp; </span>ENTIDAD</strong>, en proceso realizado bajo las normas y regulaciones de contrataci&oacute;n establecidas en el Decreto Supremo N&deg; 0181 de 28 de junio de 2009, de las Normas B&aacute;sicas del Sistema de Administraci&oacute;n de Bienes y Servicios (NB-SABS), sus modificaciones y T&eacute;rminos de Referencia, para la Contrataci&oacute;n de Servicios de Consultor&iacute;a Individual de L&iacute;nea, en la <strong>Modalidad de Contrataci&oacute;n Menor </strong>invit&oacute; en <strong>fecha {{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }}</strong> a presentar propuestas para el <strong >Servicio de</strong> </span><strong ><span>&ldquo;</span></strong><strong ><span>CONSULTOR INDIVIDUAL DE L&Iacute;NEA<span>&nbsp; </span>{{ Str::upper($contract->cargo->Descripcion).($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} PARA LA/EL {{ Str::upper($contract->work_location ?? $contract->unidad_administrativa->nombre) }} DEL SEDEGES-BENI&rdquo;,</span></strong><span > en base a los T&eacute;rminos de Referencia.</span></p>
            <p><span >Concluido el Proceso de Evaluaci&oacute;n de Propuesta, el Responsable del Proceso de Contrataci&oacute;n de Apoyo Nacional a la Producci&oacute;n y Empleo (RPA), en base al <strong >Informe de Evaluaci&oacute;n y Recomendaci&oacute;n<span>&nbsp; </span>de Adjudicaci&oacute;n <b>{{ $contract->code }}</b> de fecha {{ date('d', strtotime($contract->date_report)) }} de {{ $months[intval(date('m', strtotime($contract->date_report)))] }} de {{ date('Y', strtotime($contract->date_report)) }}</strong> emitido por la Dra. Mercedes Suarez Ch&aacute;vez, Responsable de Evaluaci&oacute;n, resolvi&oacute; adjudicar, mediante <strong >Nota <span>de Adjudicaci&oacute;n {{ $contract->code }}</span></strong> de fecha <strong>{{ date('d', strtotime($contract->date_note)) }} de {{ $months[intval(date('m', strtotime($contract->date_note)))] }} de {{ date('Y', strtotime($contract->date_note)) }}</strong> la contrataci&oacute;n del Consultor Individual de L&iacute;nea <strong>{{ $contract->person->gender == 'masculino' ? 'al Señor' : 'a la Señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, al cumplir su propuesta con todos los requisitos establecidos en los T&eacute;rminos de Referencia.</span></p>
            <p><strong><span>CL&Aacute;USULA SEGUNDA.- (LEGISLACI&Oacute;N APLICABLE)</span></strong><span> El presente Contrato se celebra al amparo de las siguientes disposiciones:</span></p>
            <p>
                <ul>
                    <li><p>Constitución Política del Estado.</p></li>
                    <li><p>Ley Nº 1178, de 20 de julio de 1990, de Administración y Control Gubernamentales.</p></li>
                    <li><p>Decreto Supremo Nº 0181, de 28 de junio de 2009, de las Normas Básicas del Sistema de Administración de Bienes y Servicios – NB-SABS y sus modificaciones.</p></li>
                    <li><p>Ley del Presupuesto General del Estado aprobado para la Gestión 2023 y su Reglamentación.</p></li>
                    <li><p>Otras disposiciones conexas.</p></li>
                </ul>
            </p>
            <p><b>CLÁUSULA TERCERA.- (OBJETO Y CAUSA)</b> El objeto y causa del presente Contrato es la prestación del servicio de "<b>CONSULTOR INDIVIDUAL DE LÍNEA {{ Str::upper($contract->cargo->Descripcion).($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} PARA LA/EL {{ Str::upper($contract->work_location ?? $contract->unidad_administrativa->nombre) }} DEL SEDEGES-BENI</b>", que en adelante se denominará la <b>CONSULTORÍA</b>, provisto por <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> de conformidad con los Términos de Referencia, con estricta y absoluta sujeción al presente Contrato.</p>
            <p><b>CLÁUSULA CUARTA.- (DOCUMENTOS INTEGRANTES DEL CONTRATO)</b> Forman parte del presente Contrato, los siguientes documentos:</p>
            {!! $contract->documents_contract ?? '<ol style="color: #000000; font-family: Arial, sans-serif; font-size: 15px; text-align: justify;" type="a"><li><p>Declaraci&oacute;n Jurada de No Doble Percepci&oacute;n.</p></li><li><p>T&eacute;rminos de Referencia.</p></li><li><p>Informe de Evaluaci&oacute;n y Recomendaci&oacute;n del proceso de contrataci&oacute;n N&deg; 123.</p></li><li><p>Certificaci&oacute;n Presupuestaria (Preventivo N&deg; 123)</p></li><li><p>Certificaci&oacute;n Poa N&deg; 123.</p></li><li><p>Declaraci&oacute;n Jurada de No Incompatibilidad Legal.</p></li><li><p>Fotocopia de C&eacute;dula de Identidad.</p></li><li><p>Certificaci&oacute;n de Programaci&oacute;n Operativa Anual (P.O.A.)</p></li><li><p>Curriculum Vitae</p></li><li><p>Certificado de Antecedentes Penales (REJAP)</p></li><li><p>Certificado de No Violencia (Ley 1153)</p></li><li><p>Certificado de Inscripci&oacute;n NIT</p></li><li><p>Certificaci&oacute;n de No Adeudo (AFP)</p></li><li><p>Certificado del RUPE N&deg; 123.( cuando corresponda).</p></li></ol>' !!}
            <p><b>CLÁUSULA QUINTA.- (OBLIGACIONES DE LAS PARTES)</b> Las partes contratantes se comprometen y obligan a dar cumplimiento a todas y cada una de las Cláusulas del presente Contrato.</p>
            <p>
                <ol>
                    <li><p>Realizar la prestación del servicio de CONSULTORÍA objeto del presente Contrato, de acuerdo con lo establecido en los Términos de Referencia, así como las condiciones de su propuesta.</p></li>
                    <li><p>Cumplir cada una de las Cláusulas del presente Contrato.</p></li>
                    <li><p>Asumir directa e íntegramente la responsabilidad sobre el debido uso de la documentación asignada a su persona, bajo causal de Resolución del Contrato.</p></li>
                    <li><p>A cumplir lo establecido en las normativas vigentes.</p></li>
                    <li><p>No comprometerse en actividades incompatibles con los propósitos y principios del presente Contrato, disposiciones legales vigentes y del servicio a ser prestado.</p></li>
                    <li><p>Asumir la responsabilidad sobre los bienes y/o activos que le sean asignados para el desempeño de sus funciones, no pudiendo disponer y/o trasladarlos, sin expresa autorización de la Jefatura de Bienes Patrimoniales.</p></li>
                    <li><p>Al momento de la finalización del Contrato, cual fuera la forma de extinción del mismo, de manera obligatoria deberá presentar a su inmediato superior un Informe Final de Actividades y entregar toda la documentación que se encuentre a su cargo, así como toda la documentación emitida en el ejercicio del mismo.</p></li>
                    <li><p>Guardar el debido respeto, en el ejercicio de las funciones asignadas.</p></li>
                    <li><p>No incurrir en actos de racismo y/o discriminación.</p></li>
                    <li><p>No prestar servicios en estado de ebriedad u otro estado inconveniente.</p></li>
                    <li><p>No transferir o subrogar total o parcialmente el presente Contrato.</p></li>
                    <li><p><b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b> podrá solicitar permiso sin goce de remuneración o compensar horas de trabajo en igual proporción al tiempo otorgado, con la debida justificación y Visto Bueno del Titular de la dependencia donde presta servicios, cuya aprobación será puesta a consideración y valoración de su inmediato Superior.</p></li>
                </ol>
            </p>
            <p>Por su parte, la <b>ENTIDAD</b> se compromete a cumplir con las siguientes obligaciones:</p>
            <p>
                <ol>
                    <li><p>Apoyar <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b> proporcionando la información necesaria, apoyo logístico y todas las condiciones de trabajo e insumos para el desarrollo de la CONSULTORÍA. </p></li>
                    <li><p>Dar conformidad al servicio de <b>CONSULTORÍA</b>, en un plazo no mayor de cinco (5) días hábiles computables a partir de la recepción de informe. </p></li>
                    <li><p>Realizar el pago de la <b>CONSULTORÍA</b> en un plazo no mayor de diez (10) días hábiles computables a partir de la emisión de la conformidad a favor del Consultor. </p></li>
                    <li><p>Cumplir cada una de las Cláusulas del presente Contrato.</p></li>
                </ol>
            </p>
            <p><strong><span>CL&Aacute;USULA SEXTA.- (VIGENCIA) </span></strong><span>El Contrato, entrar&aacute; en vigencia desde el momento de su suscripci&oacute;n, por ambas partes, hasta que las mismas hayan dado cumplimiento a todas las Cl&aacute;usulas contenidas en el presente Contrato.</span></p>
            <p><strong><span>CL&Aacute;USULA S&Eacute;PTIMA.- (COMPROMISO POR GARANT&Iacute;A) </span></strong><span>A la suscripci&oacute;n del Contrato, <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b>  se compromete al fiel cumplimiento del mismo en todas sus partes.<span>&nbsp; </span></span></p>
            <p><span><b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  no est&aacute; obligado a presentar una Garant&iacute;a de Cumplimiento de Contrato, ni la Entidad a realizar la retenci&oacute;n de los pagos parciales por concepto de Garant&iacute;a de Cumplimiento de Contrato; sin embargo, en caso de que <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> , incurriere en alg&uacute;n tipo de incumplimiento contractual, se tendr&aacute; al mismo como impedido de participar en los procesos de contrataciones del Estado, en el marco del Art&iacute;culo 43&deg; del Decreto Supremo N&ordm; 0181, por lo que para el efecto se procesar&aacute; la Resoluci&oacute;n de Contrato por causas atribuibles <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b> . </span></p>
            <p><strong><span>CL&Aacute;USULA OCTAVA.- (ANTICIPO) En el presente Contrato no se otorgar&aacute; anticipo.</span></strong></p>
            <p><strong><span>CLAUSULA NOVENA.- (PLAZO DE PRESTACI&Oacute;N DE LA CONSULTOR&Iacute;A)</span></strong><span> <b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  desarrollar&aacute; sus actividades de forma satisfactoria, en estricto acuerdo con el alcance del servicio, la propuesta adjudicada, los T&eacute;rminos de Referencia y tendr&aacute; una duraci&oacute;n desde <strong>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} al {{ date('d', strtotime($contract_finish)) }} de {{ $months[intval(date('m', strtotime($contract_finish)))] }} de {{ date('Y', strtotime($contract->start)) }}.</strong></span></p>
            <p><span>En el caso de que la finalizaci&oacute;n de la CONSULTOR&Iacute;A, coincida con un d&iacute;a s&aacute;bado, domingo o feriado, la misma ser&aacute; trasladada al siguiente d&iacute;a h&aacute;bil administrativo.</span></p>
            <p><strong><span>CL&Aacute;USULA D&Eacute;CIMA.- (LUGAR DE PRESTACI&Oacute;N DE SERVICIOS)</span></strong> <span><b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  realizar&aacute; la <strong>CONSULTOR&Iacute;A</strong>, objeto del presente Contrato en<strong> LA/EL {{ Str::upper($contract->work_location ?? $contract->unidad_administrativa->nombre) }} </strong>, <span>cumpliendo con los horarios establecidos del SEDEGES - BENI.</p>
            <p><span><strong><span>CL&Aacute;USULA </span></strong></span><strong><span>D&Eacute;CIMA PRIMERA.</span></strong><span>-</span><strong><span> (MONTO Y FORMA DE PAGO) </span></strong></p>
            @include('management.docs.consultor.partials.payment_details', ['subtitle' => '', 'contract' => $contract, 'contract_start' => $contract->start, 'contract_finish' => $contract_finish])
            <p><strong><span>EL SUPERVISOR</span></strong><span>, una vez recibidos los Informes, revisar&aacute; cada uno de &eacute;stos de forma completa, as&iacute; como otros documentos que emanen de la CONSULTOR&Iacute;A y har&aacute; conocer <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b>  la aprobaci&oacute;n de los mismos o en su defecto comunicar&aacute; sus observaciones. En ambos casos <strong>EL SUPERVISOR</strong> deber&aacute; comunicar su decisi&oacute;n respecto al Informe en el plazo m&aacute;ximo de cinco (5) d&iacute;as calendarios computados a partir de la fecha de su presentaci&oacute;n. Si dentro del plazo se&ntilde;alado precedentemente, <strong>EL SUPERVISOR </strong>no se pronunciara respecto al Informe, se aplicar&aacute; el silencio administrativo positivo, consider&aacute;ndose a los Informes como aprobados. </span></p>
            <p><span>El Informe peri&oacute;dico, aprobado por <strong>EL SUPERVISOR,</strong> ser&aacute; remitido a la dependencia que corresponda de la <strong>ENTIDAD</strong> en el plazo m&aacute;ximo de tres (3) d&iacute;as h&aacute;biles computables desde su recepci&oacute;n, para que se procese el pago correspondiente. La entidad deber&aacute; exigir la presentaci&oacute;n del Comprobante del Pago de Contribuciones al Sistema Integral de Pensiones (SIP), antes de efectuar el o los pagos por la prestaci&oacute;n del Servicio de CONSULTOR&Iacute;A.&nbsp;&nbsp;&nbsp; </span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>D&Eacute;CIMA SEGUNDA.- (ESTIPULACI&Oacute;N SOBRE IMPUESTO</span></strong><span>S) Correr&aacute; por cuenta d<b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> , en el marco de la relaci&oacute;n contractual, el pago de todos los impuestos vigentes en el pa&iacute;s a la fecha de suscripci&oacute;n del presente Contrato. </span></p>
            <p><span>En caso de que posteriormente, el Estado Plurinacional de Bolivia, implantara impuestos adicionales, disminuyera o incrementara los vigentes, mediante disposici&oacute;n legal expresa, <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> deber&aacute; acogerse a su cumplimiento desde la fecha de vigencia de dicha normativa. </span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>D&Eacute;CIMA TERCERA.- (DERECHOS DEL CONSULTOR) </span></strong><span><b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b> , tiene derecho a plantear los reclamos que considere correctos, por cualquier omisi&oacute;n de la <strong>ENTIDAD</strong>, por falta de pago del servicio prestado o por cualquier otro aspecto consignado en el presente Contrato.&nbsp; </span></p>
            <p><span>Tales reclamos deber&aacute;n ser planteados por escrito con el respaldo correspondiente al <strong>SUPERVISOR,</strong> hasta veinte (20) d&iacute;as h&aacute;biles posteriores al suceso. </span></p>
            <p><strong><span>EL SUPERVISOR</span></strong><span>, dentro del plazo impostergable de cinco (5) d&iacute;as h&aacute;biles, tomar&aacute; conocimiento, analizar&aacute; el reclamo y emitir&aacute; su respuesta de forma sustentada <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b>  aceptando o rechazando el reclamo. Dentro de este plazo, <strong>EL SUPERVISOR</strong> podr&aacute; solicitar las aclaraciones respectivas <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b> , para sustentar su decisi&oacute;n.&nbsp; </span></p>
            <p><span>En los casos que as&iacute; corresponda por la complejidad del reclamo, <strong>EL SUPERVISOR</strong> podr&aacute; solicitar en el plazo de cinco (5) d&iacute;as adicionales, la emisi&oacute;n de Informe a las dependencias T&eacute;cnica, Financiera y/o Legal de la <strong>ENTIDAD</strong>, seg&uacute;n corresponda, a objeto de fundamentar la respuesta que se deba emitir para responder <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b> . </span></p>
            <p><span>Todo proceso de respuesta a reclamos, no deber&aacute; exceder los diez (10) d&iacute;as h&aacute;biles computables desde la recepci&oacute;n del reclamo documentado por <strong>EL SUPERVISOR</strong>. En caso de que no se d&eacute; respuesta dentro del plazo se&ntilde;alado precedentemente, se entender&aacute; la plena aceptaci&oacute;n de la solicitud d<b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b>  considerando para el efecto el Silencio Administrativo Positivo.&nbsp; </span></p>
            <p><span>EL SUPERVISOR y la <strong>ENTIDAD</strong>, no atender&aacute;n reclamos presentados fuera del plazo establecido en &eacute;sta Cl&aacute;usula. &nbsp;</span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>D&Eacute;CIMA CUARTA. - (FACTURACI&Oacute;N)</span></strong><span> <b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  debe regirse de acuerdo a lo estipulado en Impuestos Nacionales.</span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>D&Eacute;CIMA QUINTA.- (MODIFICACIONES AL CONTRATO)</span></strong><span> La modificaci&oacute;n al Contrato podr&aacute; realizarse hasta un m&aacute;ximo de dos (2) veces, no debiendo exceder el plazo de cada modificaci&oacute;n al establecido en el presente Contrato, de acuerdo con lo establecido en el Art&iacute;culo 89&deg; del Decreto Supremo N&deg; 0181. </span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>D&Eacute;CIMA SEXTA.- (CESI&Oacute;N)</span></strong><span> <b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  no podr&aacute; transferir parcial ni totalmente las obligaciones contra&iacute;das en el presente Contrato, siendo de su entera responsabilidad la ejecuci&oacute;n y cumplimiento de las obligaciones establecidas en el mismo. </span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>D&Eacute;CIMA S&Eacute;PTIMA.- (MULTAS</span></strong><span>) <strong>NO APLICA MULTAS AL PRESENTE CONTRATO.</strong> </span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>D&Eacute;CIMA OCTAVA</span></strong><strong><span>.- (CONFIDENCIALIDAD)</span></strong><span> Los materiales producidos por <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> , as&iacute; como la informaci&oacute;n a la que este tuviere acceso, durante o despu&eacute;s de la ejecuci&oacute;n del presente Contrato, tendr&aacute; car&aacute;cter confidencial, quedando expresamente prohibida su divulgaci&oacute;n a terceros, exceptuando los casos en que la <strong>ENTIDAD</strong> emita un pronunciamiento escrito estableciendo lo contrario. </span></p>
            <p><span>Asimismo, <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b>  reconoce que la <strong>ENTIDAD</strong> es el &uacute;nico propietario de los productos y documentos producidos en la CONSULTOR&Iacute;A. </span></p>
            <p><strong><span>CL&Aacute;USULA D&Eacute;CIMA NOVENA.- (EXONERACI&Oacute;N A LA ENTIDAD DE RESPONSABILIDADES POR DA&Ntilde;O A TERCEROS)</span></strong><span> <b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  se obliga a tomar todas las previsiones que pudiesen surgir por da&ntilde;o a terceros, exonerando de estas obligaciones a la <strong>ENTIDAD</strong>.</span></p>
            <p><strong><span>CL&Aacute;USULA VIG&Eacute;SIMA</span></strong><strong><span>.- (APORTES A LAS AFPs)&nbsp; </span></strong><span> <b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  Individual de L&iacute;nea tiene la obligaci&oacute;n de la contribuci&oacute;n al Sistema Integral de Pensiones a Largo Plazo del porcentaje cotizable, administrativos por las AFPs, tal como lo establece la Ley N&deg; 065 del 10 de diciembre del 2010 y el Decreto Supremo N&deg; 0778 de 26 de enero del 2011.</span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>VIG&Eacute;SIMA PRIMERA.- (DE LOS PASAJES Y VI&Aacute;TICOS) </span></strong><span>En caso de que se requiera la presencia d<b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b>  en un lugar distinto al lugar de la realizaci&oacute;n de la consultor&iacute;a, la <strong>ENTIDAD </strong>correr&aacute; con los gastos de pasajes y vi&aacute;ticos <b>{{ $contract->person->gender == 'masculino' ? 'del CONSULTOR' : 'de la CONSULTORA' }}</b>  contratado, tal como lo establece la Ley N&deg; 614 en su Art&iacute;culo 14&deg;, Par&aacute;grafo III, Inc. e).</span></p>
            <p><strong><span>CL&Aacute;USULA </span></strong><strong><span>VIG&Eacute;SIMA SEGUNDA.- (DEL HORARIO DE ASISTENCIA) </span></strong><span><b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b> acepta de manera expresa someterse a los horarios de trabajo establecidos en la <strong>ENTIDAD,</strong> con exclusiva dedicaci&oacute;n; para ello, deber&aacute; registrar su ingreso y salida en los diferentes turnos de la jornada laboral, a efectos de control de su asistencia; en caso de faltas injustificadas o atrasos se proceder&aacute; al descuentos correspondiente de su haber mensual, disponiendo las sanciones correspondientes. </span></p>
            <p><strong><span>CL&Aacute;USULA </span><span>VIG&Eacute;SIMA TERCERA.- (TERMINACI&Oacute;N DEL CONTRATO</span></strong><span>) El presente Contrato concluir&aacute; por una de las siguientes causas:</span></p>
            <p>
                <ol style="padding-left: 30px">
                    <li>
                        <p><b>Por Cumplimiento del Objeto de Contrato:</b></p>
                        <p>Forma ordinaria de cumplimiento, donde la <b>ENTIDAD</b> como <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b>  da por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas las condiciones y estipulaciones contenidas en el mismo, lo cual se hará constar en el Certificado de Cumplimiento de Contrato, emitido por la <b>ENTIDAD</b>.</p>
                    </li>
                    <li>
                        <p><b>Por Resolución del Contrato:</b></p>
                        <p>Es la forma extraordinaria de Terminación del Contrato que procederá únicamente por las siguientes causales:</p>
                        <ol style="list-style: none; padding-left: 20px">
                            <li>
                                <p>2.1. <b>A Requerimiento de la ENTIDAD, por Causa Atribuible al CONSULTOR:</b></p>
                                <ol type="a" style="padding-left: 30px">
                                    <li><p>Por incumplimiento en la realización de la CONSULTORÍA en el plazo establecido.</p></li>
                                    <li><p>Por incumplimiento en la iniciación del servicio.</p></li>
                                    <li><p>Por negligencia reiterada (3 veces) en el cumplimiento de los Términos de Referencia, u otras especificaciones o instrucciones escritas de la <b>ENTIDAD</b>.</p></li>
                                    <li><p>Por incumplimiento total o parcial a lo dispuesto en el Contrato suscrito.</p></li>
                                    <li><p>Por inasistencia o abandono injustificado del desempeño de sus tareas específicas por un periodo de tres (3) días continuos o seis (6) días discontinuos en el transcurso del periodo de Contratación.</p></li>
                                    <li><p>Por estar dentro del plazo (3 años) establecidos como sanción por Resolución de Contrato con una Entidad Estatal. </p></li>
                                    <li><p>Por incumplimiento del objeto de contratación en lo referente a los Términos de Referencia.</p></li>
                                    <li><p>Por pérdidas y/o daño a bienes otorgados en custodio a él o la CONTRATADO (A), a causa de su negligencia, así como también cualquier otro bien que se le sea asignado para el desarrollo de sus tareas específicas.</p></li>
                                    <li><p>Consumir bebidas alcohólicas en instalaciones de la ENTIDAD o por asistir a su fuente laboral en estado de ebriedad.</p></li>
                                    <li><p>Por acumulación de tres (3) llamadas de atención escritas por malos tratos a sus colegas.</p></li>
                                    <li><p>Por acumulación de tres (3) llamadas de atención escritas por el mismo hecho o incumplimiento. </p></li>
                                    <li><p>Por denuncias comprobadas de haber recibido coimas o sobornos para el cumplimiento del objeto del presente Contrato.</p></li>
                                </ol>
                            </li>
                            <li>
                                <p>2.2. <b>A Requerimiento del CONSULTOR, por Causales Atribuibles a la ENTIDAD:</b></p>
                                <ol type="a" style="padding-left: 30px">
                                    <li><p>Si apartándose del objeto del Contrato, la <b>ENTIDAD</b> pretende efectuar modificaciones en relación a la prestación de los servicios objeto del presente Contrato.</p></li>
                                    <li><p>Por incumplimiento en los pagos por más de sesenta (60) días calendario computados a partir de la fecha en la que debía efectivizarse el pago. </p></li>
                                    <li><p>Por instrucciones injustificadas emanadas por la ENTIDAD para la suspensión del servicio por más de treinta (30) días calendario. </p></li>
                                </ol>
                            </li>
                            <li>
                                <p>2.3. <b>Procedimiento de Resolución por Causas Atribuibles a las Partes:</b></p>
                                <ul style="list-style: none; padding-left: 20px">
                                    <li>
                                        <p>De acuerdo a las causales de Resolución de Contrato señaladas precedentemente; y, considerando la naturaleza de las prestaciones del presente Contrato que implica la realización de prestaciones continuas, periódicas o sujetas a cronograma, su terminación solo afectará a las prestaciones futuras, debiendo considerarse cumplidas las prestaciones ya realizadas por ambas partes.</p>
                                        <p>Para procesar la Resolución del Contrato por cualquiera de las causales señaladas, la <b>ENTIDAD</b> o el <b>CONSULTOR</b>, según corresponda, dará aviso escrito mediante carta, a la otra parte, de su intención de resolver el Contrato, estableciendo claramente la causal que se aduce.</p>
                                        <p>Si dentro de los tres (3) días hábiles siguientes de la fecha de notificación, se enmendaran las fallas, se normalizará el desarrollo de las prestaciones del servicio, se tomarán las medidas necesarias para continuar normalmente con las estipulaciones del Contrato y el requirente de la resolución expresará por escrito su conformidad a la solución, el aviso de intensión de resolución será retirado.</p>
                                        <p>Caso contrario, si al vencimiento del término de los tres (3) días hábiles no existiese ninguna respuesta, el proceso de resolución continuará, a cuyo fin la <b>ENTIDAD</b> o el <b>CONSULTOR</b>, según quien haya requerido la Resolución del Contrato, notificará mediante carta a la otra parte, indicando que la Resolución del Contrato se ha hecho efectiva. </p>
                                        <p>Cuando se efectúe la Resolución del Contrato se procederá a una liquidación de saldos deudores y acreedores de ambas partes, efectuándose los pagos a que hubiere lugar, conforme la evaluación del grado de cumplimiento de la <b>CONSULTORÍA</b>.</p>
                                        <p>En caso de haberse realizado el aviso escrito mediante carta, por cualquiera de las causales establecidas la Cláusula Vigésima Tercera, numeral 2.1, si el Consultor, luego de que se enmendaran las fallas hubiera incurrido nuevamente en cualquiera de las causales señaladas, la <b>ENTIDAD</b> notificará por escrito mediante una nueva carta o Memorándum, comunicando <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b>  que el Contrato ha quedado resuelto de forma efectiva y definitiva. </p>
                                    </li>
                                </ul>
                            </li>
                        </ol>
                    </li>
                    <li>
                        <p><b>Por Acuerdo entre Partes:</b></p>
                        <p>Procederá cuando ambas partes otorguen su consentimiento con el objetivo de terminar con la Relación contractual, bajo las siguientes condiciones:</p>
                        <ol type="a">
                            <li><p>Que las partes manifiesten de manera expresa su voluntad de dar por terminada la relación contractual por muto acuerdo;</p></li>
                            <li><p>Que no exista causa de resolución imputable al Consultor; </p></li>
                            <li><p>Que la terminación de la relación contractual no afecte el interés público o que la continuidad de la misma sea innecesaria o inconveniente. </p></li>
                        </ol>
                        <ul style="list-style: none">
                            <li>
                                <p><b>Procedimiento de Resolución por Mutuo Acuerdo.</b></p>
                                <p>Considerando la naturaleza de las prestaciones del presente Contrato que implica la realización de prestaciones continuas, periódicas o sujetas a cronograma, su terminación sólo afectará a las prestaciones futuras, debiendo considerarse cumplidas las prestaciones ya realizadas por ambas partes. Cuando se efectúe la Resolución por Mutuo Acuerdo, ambas partes deberán suscribir un documento de Resolución de Contrato, el cual deberá contener la siguiente información: Partes suscribientes, antecedentes, condiciones para la Resolución de Contrato por acuerdo, alcances de la Resolución, inexistencia de obligación y conformidad de las partes. </p>
                                <p>Realizada la Resolución del Contrato se procederá a efectuar la liquidación de saldos deudores y acreedores de ambas partes, efectuándose los pagos a que hubiere lugar, conforme la evaluación del grado de cumplimiento de los Términos de Referencia. Asimismo, no procederá la ejecución de garantía de cumplimiento de Contrato, ni la ejecución de las retenciones por concepto de garantía de cumplimiento de Contrato, tampoco procederá la publicación <b>{{ $contract->person->gender == 'masculino' ? 'del CONSULTOR' : 'de la CONSULTORA' }}</b>  en el SICOES como impedido de participar en procesos de contratación. </p>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <p><b>Resolución por Causas de Fuerza Mayor o Caso Fortuito o en Resguardo de los Intereses del Estado:</b></p>
                        <p>Considerando la naturaleza de las prestaciones del Contrato que implica la realización de prestaciones continuas, periódicas o sujetas a cronograma, su terminación sólo afectará a las prestaciones futuras, debiendo considerarse cumplidas las prestaciones ya realizadas por ambas partes.</p>
                        <p><span>Si en cualquier momento, antes de la terminaci&oacute;n de la prestaci&oacute;n del servicio objeto del presente Contrato, <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> , se encontrase con situaciones no atribuibles a su voluntad, por causas de fuerza mayor, caso fortuito u otras causas debidamente justificadas, que imposibilite la prestaci&oacute;n del servicio, comunicar&aacute; por escrito su intenci&oacute;n de Resolver el Contrato, justificando la causa. </span></p>
                        <p><span>La <strong>ENTIDAD</strong>, previa evaluaci&oacute;n y aceptaci&oacute;n de la solicitud, mediante carta notariada dirigida <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b> , suspender&aacute; la ejecuci&oacute;n del servicio y resolver&aacute; el Contrato. A la entrega de dicha comunicaci&oacute;n oficial de resoluci&oacute;n, <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b>  suspender&aacute; la ejecuci&oacute;n del servicio de acuerdo a las instrucciones escritas que al efecto emita la <strong>ENTIDAD</strong>.&nbsp; </span></p>
                        <p><span>Asimismo, si la <strong>ENTIDAD</strong> se encontrase con situaciones no atribuibles a su voluntad, por causas de fuerza mayor, caso fortuito o considera que la continuidad de la relaci&oacute;n contractual va en contra los intereses del Estado, comunicar&aacute; por escrito la suspensi&oacute;n de la ejecuci&oacute;n del servicio y resolver&aacute; el CONTRATO. </span></p>
                        <p><span>Una vez efectivizada la Resoluci&oacute;n del Contrato, las partes proceder&aacute;n a realizar la liquidaci&oacute;n del Contrato donde establecer&aacute;n los saldos en favor o en contra para su respectivo pago y/o cobro, seg&uacute;n corresponda. </span></p>
                    </li>
                </ol>
            </p>
            <p><strong><span>CL&Aacute;USULA VIG&Eacute;SIMA CUARTA.- (SOLUCI&Oacute;N DE CONTROVERSIAS</span></strong><span>) En caso de surgir controversias sobre los derechos y obligaciones u otros aspectos propios de la ejecuci&oacute;n del presente Contrato, las partes acudir&aacute;n a la jurisdicci&oacute;n prevista en el ordenamiento jur&iacute;dico para los Contratos Administrativos. </span></p>
            <p><strong><span>CL&Aacute;USULA VIG&Eacute;SIMA QUINTA.- (CERTIFICADO DE LIQUIDACI&Oacute;N FINAL)</span></strong><span> Dentro de los diez (10) d&iacute;as calendario, siguientes a la fecha de conclusi&oacute;n de la consultor&iacute;a o a la terminaci&oacute;n del Contrato por resoluci&oacute;n, <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> , elaborar&aacute; y presentar&aacute; el Certificado o Informe Final de Liquidaci&oacute;n Final del servicio de CONSULTOR&Iacute;A, con fecha y la firma <b>{{ $contract->person->gender == 'masculino' ? 'del CONSULTOR' : 'de la CONSULTORA' }}</b>  y del SUPERVISOR para su aprobaci&oacute;n. La <strong>ENTIDAD</strong> a trav&eacute;s del SUPERVISOR se reserva el derecho de realizar los ajustes que considere pertinentes previa a la aprobaci&oacute;n del Certificado de Liquidaci&oacute;n Final.&nbsp;&nbsp; </span></p>
            <p><span>En caso de que <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b> , no presente al SUPERVISOR el Certificado de Liquidaci&oacute;n Final dentro del plazo previsto, EL SUPERVISOR deber&aacute; elaborar y aprobar el Certificado de Liquidaci&oacute;n Final, el cual ser&aacute; notificado <b>{{ $contract->person->gender == 'masculino' ? 'al CONSULTOR' : 'a la CONSULTORA' }}</b> . </span></p>
            <p><span>En la liquidaci&oacute;n del Contrato se establecer&aacute;n los saldos a favor o en contra y todo otro aspecto que implique la liquidaci&oacute;n de deudas y acrecencias entre las partes por terminaci&oacute;n del Contrato por cumplimiento o resoluci&oacute;n del mismo. </span></p>
            <p><span>El cierre de Contrato deber&aacute; ser acreditado con un Certificado de Cumplimiento de Contrato, otorgado por la autoridad competente de la <strong>ENTIDAD</strong> luego de concluido el tr&aacute;mite precedentemente especificado. </span></p>
            <p><span>Asimismo, <b>{{ $contract->person->gender == 'masculino' ? 'el CONSULTOR' : 'la CONSULTORA' }}</b>  podr&aacute; establecer el importe de los pagos a los cuales considere tener derecho. </span></p>
            <p><span>Preparado as&iacute; el Certificado de Liquidaci&oacute;n Final y debidamente aprobado por EL SUPERVISOR, &eacute;sta lo remitir&aacute; a la dependencia de la <strong>ENTIDAD</strong> que realiza el seguimiento del servicio, para su conocimiento, quien en su caso requerir&aacute; las aclaraciones que considere pertinentes; de no existir observaci&oacute;n alguna para el procesamiento del pago, autorizar&aacute; el mismo.</span></p>
            <p><strong><span>CL&Aacute;USULA VIG&Eacute;SIMA SEXTA.- (DE LA EXONERACI&Oacute;N DE LAS CARGAS LABORALES Y SOCIALES AL CONTRATANTE) </span></strong><span><b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b>  corre con las obligaciones que emerjan del objeto del presente Contrato, r</span><span>especto a las cargas laborales y sociales, se exonera de estas obligaciones a la <strong>ENTIDAD.</strong></span></p>
            <p><span><b>{{ $contract->person->gender == 'masculino' ? 'El CONSULTOR' : 'La CONSULTORA' }}</b> no tendr&aacute; derecho a recibir por parte de la <strong>ENTIDAD</strong>, subsidios, indemnizaciones, seguros, vacaciones, aguinaldos, bonos y cualquier otro beneficio que no se encuentre establecido en el presente Contrato.</span></p>
            <p><strong><span>CL&Aacute;USULA VIG&Eacute;SIMA S&Eacute;PTIMA.- (CONSENTIMIENT</span></strong><span>O) En se&ntilde;al de conformidad y para su fiel y estricto cumplimiento, firmamos el presente Contrato en cuatro ejemplares de un mismo tenor y validez la</span> <strong><span>Dra. Martha Mejia Fayer de Rosas,&nbsp; </span></strong><span>con<strong> C.I. N&ordm; 1704320</strong></span><span>, en calidad de Directora del SEDEGES-BENI, en representaci&oacute;n legal de la </span><strong><span>ENTIDAD</span></strong>; y, por otra parte <strong>{{ $contract->person->gender == 'masculino' ? 'al Señor' : 'a la Señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}</strong>, con <strong>C.I. N&ordm; {{ $contract->person->ci }}</strong> &nbsp;como <b>{{ $contract->person->gender == 'masculino' ? 'CONSULTOR' : 'CONSULTORA' }}</b> contratado.</span></p>
            <p><span>Este documento, conforme a disposiciones legales de control fiscal vigentes, ser&aacute; registrado ante la Contralor&iacute;a General del Estado en idioma espa&ntilde;ol.</span></p>
            <br>
            <p style="text-align: right;">
                <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->start)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->start)))]) }} de {{ date('Y', strtotime($contract->start)) }}
            </p>
            <table class="table-signature">
                <tr>
                    <td style="width: 50%">
                        {{-- ....................................................... <br>
                        {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                        <b>{{ Str::upper($signature ? $signature->job : setting('firma-autorizada.job')) }}</b> --}}
                    </td>
                    <td style="width: 50%">
                        ....................................................... <br>
                        {{ $contract->person->gender == 'masculino' ? 'Sr.' : 'Sra.' }} </em><em>{{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
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