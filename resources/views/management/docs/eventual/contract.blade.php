@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Personal Eventual')

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
            $qrcode = QrCode::size(70)->generate('CONTRATO DE PRESTACIÓN DE SERVICIOS PARA PERSONAL EVENTUAL GAD-BENI-C.E- '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' al '.date('d', strtotime($contract_finish)).' de '.$months[intval(date('m', strtotime($contract_finish)))].' de '.date('Y', strtotime($contract_finish)).' con un sueldo de '.number_format($sueldo, 2, ',', '.').' Bs.');
        @endphp
        @if ($contract->files->count() > 0)
            <img src="data:image/png;base64, {!! base64_encode($qrcode) !!}">
        @else
            {!! $qrcode !!}
        @endif
    </div>
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <div class="page-head">
            <h3>CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL<br>GAD-BENI-C.E-{{ $code }}</h3>
        </div>
        <p><em>Conste por el presente contrato de prestaci&oacute;n de servicios <strong>de Personal Eventual</strong> celebrado de conformidad a las siguientes cláusulas y condiciones:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA PRIMERA .- </strong></span><strong>(PARTES ).-</strong> </em></p>
        <p><em>1.- <strong>EL GOBIERNO AUT&Oacute;NOMO DEPARTAMENTAL DE BENI</strong>, con domicilio ubicado en la Plaza Principal Mcal. Jos&eacute; Ballivi&aacute;n representado legalmente para este acto por la/el <strong> {{ $signature ? $signature->name : setting('firma-autorizada.name') }} con C.I {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}, </strong>en su calidad de <strong>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} GAD-BENI</strong><strong>,</strong> designado mediante <strong>{{ $signature ? $signature->designation_type : 'Resolución de Gobernación' }} N&deg;{{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}</strong>, que en adelante se denominará la <strong>ENTIDAD</strong>. </em></p>
        <p><em>2. {{ $contract->person->gender == 'masculino' ? 'El señor' : 'La señora' }} <b>{{ $contract->person->first_name }} {{ $contract->person->last_name }} </b> con C.I. <b>{{ $contract->person->ci }}</b>; con domicilio en {{ $contract->person->address }}, {!! $contract->person->profession ? 'de profesión <b>'.$contract->person->profession.'</b>, ' : '' !!}</em> <em>mayor de edad h&aacute;bil en toda forma de derecho que en adelante se denominará {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!}.</em></p>
        <p><em>Quienes celebran el presente CONTRATO ADMINISTRATIVO, de acuerdo a los t&eacute;rminos y condiciones siguientes:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEGUNDA</strong></span><strong>.-</strong> <strong>(ANTECEDENTES).- </strong>La Ley No 1493, Ley del Presupuesto General del Estado Gesti&oacute;n 2023 de 17 de diciembre de 2022, determina en su Disposici&oacute;n Final Segunda la vigencia entre otros de la Ley del Presupuesto General del Estado, aprobado en el marco del articulo 158 numeral 11 de la Constituci&oacute;n Pol&iacute;tica del Estado, que en su el art. 22 (Ley de presupuesto general del Estado 2010)determina; "La remuneraci&oacute;n del Personal Eventual debe establecerse considerando las funciones y la escala salarial aprobada de la <b>ENTIDAD</b>, para la cual no se requiere ning&uacute;n instrumento legal adicional".</em></p>
        <p><em>Por su parte el art&iacute;culo 13 del D.S. 4848 de 28 de diciembre de 2022, se&ntilde;ala (Nivel de remuneraci&oacute;n del personal Eventual)</em></p>
        <p><em>I. La definici&oacute;n de la remuneraci&oacute;n del personal eventual, debe estar establecida en funci&oacute;n a la escala salarial, para lo cual, las unidades administrativas de cada entidad, elaboraran el cuadro de equivalencia de funciones que ser&aacute; avalado por la Unidad Jur&iacute;dica y con Visto Bueno (Vo.Bo.) de la MAE.</em></p>
        <p><em>II. Las Entidades Territoriales Aut&oacute;nomas y universidades p&uacute;blicas podr&aacute;n contratar personal eventual para funciones administrativas, utilizando los niveles de sus respectivas escalas salariales.</em></p>
        <p><em>El Clasificador Presupuestario de la presente gesti&oacute;n en el grupo 12000, Empleados No Permanentes, se&ntilde;ala que son gastos para remunerar los servicios prestados y otros beneficios a personas sujetas a contrato en forma transitoria o eventual, para MISIONES ESPEC&Iacute;FICAS, PROGRAMAS Y PROYECTOS DE INVERSI&Oacute;N, considerando para el efecto, la equivalencia de funciones y la escala salarial de acuerdo a la normativa vigente.</em></p>
        <p><em>Al respecto en funci&oacute;n a la verificaci&oacute;n de presupuesto, equivalencia de funciones y requisitos exigidos para el cargo, se instruye la contrataci&oacute;n del personal eventual referido.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA TERCERA</strong></span><strong>.-</strong> <strong>(BASE LEGAL).- </strong></em></p>
        <p>
            <ul>
                <li><em>Constituci&oacute;n Pol&iacute;tica del Estado, de fecha 07 de febrero de 2009, en todo lo que sea aplicable.</em></li>
                <li><em>La Ley No 1178 Administraci&oacute;n y Control Gubernamentales de 20 de julio de 1990.</em></li>
                <li><em>Ley N&deg; 2027 del Estatuto del Funcionario P&uacute;blico</em></li>
                <li><em>Decreto Supremo N&deg; 26115, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</em></li>
                <li><em>Ley N° 1493 de 17 de diciembre de 2022, Presupuesto General del Estado - Gestión 2023.</em></li>
                <li><em>Decreto Supremo No 4848, de fecha 28 de diciembre de 2022, “Reglamento a la Ley1493”.</em></li>
                <li><em>Ley contra el Racismo y toda forma de Discriminación Nº 045, de 08 de octubre de 2010.</em></li>
                <li><em>Ley de Lucha contra la Corrupción Enriquecimiento Ilícito e Investigación de Fortunas “Marcelo Quiroga Santa Cruz” Nº 004, de 31 de marzo de 2010.</em></li>
                <li><em>Ley de Pensiones Nº 65, de fecha 10 de diciembre de 2010.</em></li>
                <li><em>Código de Seguridad Social, de 14 de diciembre de 1956.</em></li>
                <li><em>Decreto Supremo 23318-A, de 3 de noviembre de 1992, que aprueba el Reglamento de la Responsabilidad por la Función Pública y su Decreto Supremo, modificatorio.</em></li>
                <li><em>Reglamento Interno de Personal del Gobierno Autónomo Departamental de Beni, en todo lo que sea aplicable y compatible con la naturaleza jurídica de este contrato.</em></li>
                <li><em>Decreto supremo Nro. 12/2009, artículo 5, parágrafo II.</em></li>
            </ul>
        </p>
        <p><em>En ese marco {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} no estar&aacute; bajo el r&eacute;gimen de la Ley General del Trabajo ni el Decreto Reglamentario 224 de fecha 23 de agosto de 1943.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA CUARTA</strong></span><strong>.-</strong> <strong>(DOCUMENTOS INTEGRANTES).-</strong></em></p>
        <p><em>Son documentos integrantes del presente contrato:</em></p>
        <p><em>a) Requisitos mínimos de la Unidad Solicitante (Misión específica).</em></p>
        <p><em>b) Curr&iacute;culum Vitae {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!}</em></p>
        <p><em>c) Certificación presupuestaria POA 2023.</em></p>
        <p><em>d) Certificación POA 2023.</em></p>
        <p><em>e) Otros documentos requeridos por la Dirección de Recursos Humanos GAD BENI.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA QUINTA</strong></span><strong>.-</strong> <strong>(OBJETO).-</strong></em></p>
        <p><em>La <b>ENTIDAD</b>, contrata los servicios {!! $contract->person->gender == 'masculino' ? '<b>del CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} para desempe&ntilde;ar la funci&oacute;n de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, en dependencias de la/el <b>{{ Str::upper($contract->unidad_administrativa->nombre) }}</b> dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b> con el nivel salarial <b>{{ $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->NumNivel }}</b> con cargo a la partida presupuestaria {{ $contract->program->number }} (Personal Eventual), en los t&eacute;rminos y condiciones que se establecen en este contrato, debiendo coadyuvar y coordinar sus actividades con su inmediato superior y las dem&aacute;s &aacute;reas de la <b>ENTIDAD</b>, donde sean requeridas. El presente contrato bajo ninguna manera podr&aacute; ser motivo de transferencia, subrogaci&oacute;n, delegaci&oacute;n, total o parcialmente.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEXTA</strong></span><strong>.-</strong> <strong>(CONDICIONES).-</strong></em></p>
        <p><em>6.1. FUNCIONES {!! $contract->person->gender == 'masculino' ? 'DEL <b>CONTRATADO</b>' : 'DE LA <b>CONTRATADA</b>' !!}: Deber&aacute; cumplir las siguientes funciones que se establecen de forma enunciativa y no limitativa:</em></p>
        <p><em><strong>FUNCIONES GENERALES:</strong></em></p>
        <p><em>
            {!! $contract->details_work !!}    
        </em></p>
        <p><em><strong>FUNCIONES O MISIONES ESPEC&Iacute;FICAS Y RESPONSABILIDADES:</strong></em></p>
        <ul>
            <li><em>Confidencialidad de la informaci&oacute;n y documentaci&oacute;n del &aacute;rea de trabajo bajo su custodia, conocimiento y otros.</em></li>
            <li><em>Presentar informes mensuales, trimestrales o de la manera que su superior inmediato solicite seg&uacute;n los programas de evaluaci&oacute;n y cumplimiento de objetivos de la <b>ENTIDAD</b> en formato impreso y digital.</em></li>
            <li><em>Manejar en forma ordenada, sistematizada y archivada toda la documentaci&oacute;n bajo su cargo.</em></li>
            <li><em>Participar en otras funciones delegadas </em></li>
        </ul>
        <p><em><strong>6.2. DEPENDENCIA Y CONTROLES</strong></em></p>
        <p><em>Para el cumplimiento de sus funciones y de acuerdo a las responsabilidades {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} depende jer&aacute;rquicamente del inmediato superior y la MAE, que establecer&aacute;n adicionalmente otras funciones y &oacute;rdenes de acuerdo al objeto del presente contrato. También podrá ser sujeto a movilidad funcional según la nececidad de la institución u otras que creyera conveniente.</em></p>
        <p><em><strong>6.3. HORARIO Y DISPONIBILIDAD {{ $contract->person->gender == 'masculino' ? 'DEL CONTRATADO' : 'DE LA CONTRATADA' }}</strong></em></p>
        <p><em>{!! $contract->person->gender == 'masculino' ? 'El <b>CONTRATADO</b>' : 'La <b>CONTRATADA</b>' !!} cumplir&aacute; con el horario de trabajo de 8:00 Hrs diarias y que por razones a la latente emergencia sanitaria y por disposici&oacute;n del Ministerio de Trabajo ser&aacute; de <strong>HORARIO CONTINUO DE HRS {{ $contract->direccion_administrativa_id == 32 ? '7:30 a 13:30' : '8:00 a 16:00' }}</strong> <strong>de lunes a viernes</strong>, en el lugar que le sea asignado , sin embargo, de acuerdo a necesidades Institucionales se podr&aacute; cambiar el horario manteniendo las 8 Horas laborales, adem&aacute;s el servidor deber&aacute; prestar servicios fuera de los horarios establecidos, conforme instrucci&oacute;n verbal o escrita que reciba de sus superiores. , asimismo las inasistencias, atrasos, permisos y DESCUENTOS estar&aacute;n en sujeci&oacute;n al Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental del Beni.</em></p>
        <p><em>{!! $contract->person->gender == 'masculino' ? 'El <b>CONTRATADO</b>' : 'La <b>CONTRATADA</b>' !!} declara su plena e inmediata disponibilidad para el desempe&ntilde;o de las funciones para las cuales es {{ $contract->person->gender == 'masculino' ? 'contratado' : 'contratada' }}; con absoluta dedicaci&oacute;n, &eacute;tica y pro actividad, conducentes al logro de los objetivos de este contrato, no pudiendo realizar actividades que deterioren o menoscaben la imagen de LA INSTITUCION. En consecuencia, el servicio es de dedicaci&oacute;n exclusiva, no pudiendo prestar servicios o funciones similares y/o iguales a terceros en horarios se&ntilde;alados en el numeral.</em></p>
        @php
            $numeros_a_letras = new NumeroALetras();
        @endphp
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEPTIMA .- </strong></span><strong>(REMUNERACION ).-</strong> La <b>ENTIDAD</b> se obliga a pagar en favor {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} una remuneraci&oacute;n mensual de <b>Bs. {{ number_format($sueldo, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice($sueldo, 2, 'Bolivianos') }})</b> por mes vencido, pago que ser&aacute; en efectivo o mediante dep&oacute;sito bancario u otro procedimiento formal, conforme equivalencia de funciones y escala salarial del personal eventual de la <b>ENTIDAD</b>, monto que ser&aacute; sujeto al descuento por los aportes propios a la AFP y el r&eacute;gimen de seguridad social a corto plazo seg&uacute;n normativa vigente, as&iacute; como lo dispuesto en materia tributaria si correspondiera; el l&iacute;quido pagable final de la remuneraci&oacute;n convenida se establecer&aacute; previa deducci&oacute;n de los aportes y otras cargas definidas</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA OCTAVA .- </strong></span><strong>(DURACION Y CAR&Aacute;CTER DEFINIDO ).-</strong> En el marco legal citado en antecedentes, el presente contrato tendr&aacute; calidad de <b>CONTRATO DE PERSONAL EVENTUAL</b>, computable a partir <b>del {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }} hasta el {{ date('d', strtotime($contract_finish)) }} de {{ $months[intval(date('m', strtotime($contract_finish)))] }} de {{ date('Y', strtotime($contract_finish)) }}</b>.</em></p>
        <p><em>{!! $contract->person->gender == 'masculino' ? 'El <b>CONTRATADO</b>' : 'La <b>CONTRATADA</b>' !!} no estar&aacute; sujeto a periodo de prueba y la <b>ENTIDAD</b> podr&aacute; determinar la finalizaci&oacute;n del contrato, conforme al procedimiento dispuesto en normas complementarias y reglamentarias, si as&iacute; lo considera.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA NOVENA .- </strong></span><strong>(CAUSALES PARA RESOLUCION DEL CONTRATO)</strong></em></p>
        <p><em>El contrato se tendr&aacute; por resuelto por Cumplimiento del mismo, caso en el cual tanto la <b>ENTIDAD</b> como {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!}, dar&aacute;n por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas y cada una de las cl&aacute;usulas contenidas en el mismo sin necesidad de comunicaci&oacute;n expresa. No obstante el contrato podr&aacute; resolverse antes de la fecha de conclusi&oacute;n por las siguientes causales, en forma directa y sin necesidad requerimiento Judicial y/o administrativo alguno:</em></p>
        <p><em><strong>1. Por resoluci&oacute;n de Contrato:</strong></em></p>
        <p><em>1.1. A requerimiento de la <b>GOBERNACI&Oacute;N</b>, por causales atribuibles {!! $contract->person->gender == 'masculino' ? 'al <b>CONTRATADO</b>' : 'a la <b>CONTRATADA</b>' !!} en base a Informe:</em></p>
        <p><em><strong>a)</strong> Cuando {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} en el desempe&ntilde;o de sus funciones ocasione da&ntilde;os y perjuicios al <strong>CONTRATANTE</strong> o a terceros en raz&oacute;n de su cargo.</em></p>
        <p><em><strong>b)</strong> Por acúmulo de dos (2) llamadas de atención (graves) por incumplimiento de sus obligaciones y/o por omisión, negligencia o descuido en las mismas, durante un mismo mes.</em></p>
        <p><em><strong>c)</strong> Por pérdida, daño o merma de bienes otorgados en custodia a {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!}, a causa de su negligencia y/o impericia al momento de manipular el bien que se le sea asignado para el desarrollo de sus funciones.</em></p>
        <p><em><strong>d)</strong> Por reincidencia en asistir a su fuente laboral, bajo influencias de bebidas alcohólicas, sustancias controladas, estupefacientes y psicotrópicas o consumir las mismas en instalaciones del GAD - Beni.</em></p>
        <p><em><strong>e)</strong> Inasistencia injustificada de tres (3) días hábiles consecutivos o seis (6) días hábiles discontinuos en un (1) mes.</em></p>
        <p><em><strong>f)</strong> Abuso de confianza, robo, hurto debidamente comprobado.</em></p>
        <p><em><strong>g)</strong> Por negligencia demostrada en el cumplimiento de sus deberes que tengan como resultado el daño económico al Estado y a la Institución o el desprestigio de ésta.</em></p>
        <p><em><strong>h)</strong> Cuando {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} está comprendido(a) dentro de las incompatibilidades o prohibiciones para ejercer la función pública, por razón de parentesco hasta el cuarto grado de consanguineidad y segundo de afinidad.</em></p>
        <p><em><strong>i)</strong> Cuando se evidencie que {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} percibe dos remuneraciones en calidad de servidor público que provengan de recursos públicos, conforme a normas en vigencia o incurre en incompatibilidad por conflicto de intereses según el formulario respectivo.</em></p>
        <p><em><strong>j)</strong> Por infracción de las normas internas que rigen en la ENTIDAD (Reglamento Interno de Personal - RIP) y otras causales previstas en la normativa legal aplicable.</em></p>
        <p><em>1.2. Resolución por voluntad de {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} comunicada a la INSTITUCIÓN para aceptación de mutuo acuerdo, previo aviso escrito al CONTRATANTE solicitando Resolución de Contrato, previa entrega de informes correspondientes, activos fijos, cualquier otra documentación que está a su cargo.</em></p>
        <p><em>{!! $contract->person->gender == 'masculino' ? 'EL <b>CONTRATADO</b>' : 'LA <b>CONTRATADA</b>' !!} no podrá abandonar su fuente laboral mientras no reciba la Resolución de Contrato respectiva.</em></p>
        {{-- <p><em>En todos los casos ser&aacute; suficiente una comunicaci&oacute;n escrita con quince (15) d&iacute;as de anticipaci&oacute;n, mediante nota o memor&aacute;ndum {!! $contract->person->gender == 'masculino' ? 'al <b>CONTRATADO</b>' : 'a la <b>CONTRATADA</b>' !!} por parte del <strong>CONTRATANTE</strong> a trav&eacute;s de la Direcci&oacute;n de Recursos Humanos.</em></p> --}}
        {{-- <p><em>También opera la resolución por voluntad {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} comunicada a la INSTITUCIÓN para aceptación de mutuo acuerdo.</em></p> --}}
        <br>
        <p><em><strong>CLÁUSULA D&Eacute;CIMA: (DERECHOS Y OBLIGACIONES {{ $contract->person->gender == 'masculino' ? 'DEL CONTRATADO' : 'DE LA CONTRATADA' }})</strong></em></p>
        <p><em><b>DERECHOS.-</b></em></p>
        <ul>
            <li><p><em>Percibir mensualmente la remuneración establecida como contraprestación por sus servicios, en las condiciones señaladas en el presente contrato.</em></p></li>
            <li><p><em>Por onomástico se concederá una jornada de tolerancia de trabajo, con percepción del 100% (cien por ciento) de goce de haberes, siempre que el mismo coincida con una fecha laborable, derecho que debe ser utilizado ese mismo día y no será transferible a otro día.</em></p></li>
            <li>
                <p><em>Tendrán derecho a licencias con goce del 100% (cien por ciento) de sus haberes en los siguientes casos:</em></p>
                <p>
                    <ol>
                        <li><p><em>Por maternidad, conforme a las disposiciones del Código de Seguridad Social y otras que rigen la materia.</em></p></li>
                        <li><p><em>Asistencia a becas y cursos de capacitación, seminarios de actualización y cursos de Post Grado con o sin patrocinio institucional.</em></p></li>
                        <li><p><em>Por matrimonio, gozará de 3 (tres) días hábiles, inmediatos al acontecimiento debiendo presentar copia del Certificado de Matrimonio o del Certificado de Inscripción expedida por el Oficial de Registro Civil que acredite la fecha de realización del Matrimonio, dentro de los cinco (5) días hábiles siguientes.</em></p></li>
                        <li><p><em>Por fallecimiento de padres, cónyuge, hermanos o hijos, 3 (tres) días hábiles de licencia inmediatos al hecho; de los padres políticos, hermanos políticos y/o abuelos, 2 (dos) días hábiles inmediatos al hecho; debiendo el Servidor o Servidora pública, presentar en copias el Certificado de Defunción pertinente, dentro de los siguientes 5 (cinco) días hábiles siguientes de ocurrido el suceso.</em></p></li>
                        <li><p><em>Por enfermedad o invalidez, se otorgará las licencias respectivas según el Régimen de Seguridad Social y se justificarán con el Parte de Baja, otorgado por el ente gestor de salud respectivo. Es preciso mencionar qué, en caso de enfermedad para efectos de Control de Asistencia, no serán consideradas, ni tendrán validez alguna, Certificados o Bajas Médicas extendidas por otras instituciones o médicos particulares, solo se podrá acreditar la misma por el ente gestor de salud del GAD - Beni.</em></p></li>
                        <li><p><em>Por atención médica de la Servidora o Servidor público, en casos de emergencia o intervenciones quirúrgicas respaldados con el Parte de Baja, correspondiente del ente gestor de salud del GAD - Beni.</em></p></li>
                        <li><p><em>Las Contratadas gozarán de licencia remunerada de un (1) día hábil al año, divisible en dos medias jornadas, a objeto de someterse a un examen médico y lectura de los resultados del examen Papanicolaou, Mamografía y otros.</em></p></li>
                        <li><p><em>En el caso de nacimiento de hijos, el padre progenitor gozará de 3 (tres) días hábiles de licencia a partir del alumbramiento de la conyugue o conviviente, con la obligación de presentar el Certificado correspondiente, dentro los cinco (5) días hábiles siguientes.</em></p></li>
                    </ol>
                </p>
            </li>
            <li><p><em>En aquellos casos que no se ha determinado plazos de presentación de la documentación    respaldatoria pertinente, según corresponda, deberá ser presentada al área de Recursos Humanos en el plazo máximo de los 3 (tres) días hábiles siguientes a la licencia. En caso de no presentar la documentación respectiva en los plazos establecidos, los días utilizados como licencia serán considerados como permiso sin goce de haberes. Toda licencia deberá ser otorgada y autorizada de forma escrita por el Jefe inmediato superior y/o el superior jerárquico.</em></p></li>
        </ul>
        <p><em><b>OBLIGACIONES.-</b></em></p>
        <ul>
            <li><p><em>Cumplir lo dispuesto en la Constitución Política del Estado, Leyes, Decretos y Resoluciones Nacionales y Departamentales.</em></p></li>
            <li><p><em>Gozar del Seguro de salud para lo cual se deberá afiliar a la Caja de Seguro Social donde estuviera registrado el Gobierno Autónomo del Departamento del Beni, debiendo presentar toda la documentación.</em></p></li>
            <li><p><em>Cumplir los horarios de ingreso y salida, debiendo incorporarse a su lugar de trabajo inmediatamente después del marcado del sistema de control de asistencia bajo conminatoria de los descuentos respectivos.</em></p></li>
            <li><p><em>Preservar y cuidar los activos fijos, material, equipos de computación, documentación y todo cuanto fuere asignado para el desempeño de funciones, una vez terminado o resuelto el contrato proceder a la respectiva devolución.</em></p></li>
        </ul>
        <ul>
            <li><p><em>Garantizar y responder por la función asignada de tal forma que, de ser requerida su presencia física para cualquier aclaración posterior a la vigencia del contrato, se obliga a no negar su participación.</em></p></li>
            <li><p><em>Es su obligación cumplir y acatar las instrucciones de su inmediato superior, y otras contenidas en los manuales, reglamentos, instructivos, circulares y otros instrumentos normativos de la institución.</em></p></li>
            <li><p><em>Informar cuantas veces sea solicitado o necesario sobre sus actividades laborales y otros a fin de realizar los procesos de evaluación correspondientes a sus funciones.</em></p></li>
            <li><p><em>Desarrollar sus funciones atribuciones y deberes administrativos con puntualidad, celeridad, economía, eficiencia y probidad.</em></p></li>
            <li><p><em>Cumplir con la jornada laboral.</em></p></li>
            <li><p><em>También se hallan entre sus deberes y obligaciones el cumplimiento del Reglamento Interno de Personal, en todo lo que sea aplicable y compatible con la naturaleza jurídica de este contrato.</em></p></li>
            <li><p><em>Asumir toda la responsabilidad por el trabajo encomendado, obligándose a la preservación del material, documentos, equipos, activos y/o maquinaria que se encuentra a su cargo y a guardar reserva de información confidencial que sea de su conocimiento. El resultado de sus actividades y el contenido de ellas en cualquier medio (informe, documentos, discos magnéticos, etc.) pertenecen exclusivamente al CONTRATANTE, la divulgación de dicha información sin autorización superior, escrita y expresa implicará la violación a los principios de confiablidad e idoneidad, con las consecuencias previstas en el contrato, la Ley Nº 1178 de Administración y Control Gubernamentales y la reparación del daño civil que ocasionare.</em></p></li>
        </ul>
        <br>
        <p><em>Si durante la vigencia de este Contrato {!! $contract->person->gender == 'masculino' ? 'EL <b>CONTRATADO</b>' : 'LA <b>CONTRATADA</b>' !!} incumpliera, en todo o en parte con lo pactado, con la parte CONTRATADA podrá iniciar en su contra las acciones administrativas, judiciales, extrajudiciales que a su juicio correspondan; dada la naturaleza jurídica del presente Contrato y lo estipulado en la Ley de Administración y Control Gubernamental Nº 1178 de 20 de julio de 1990, además de sus normas reglamentarias o las que fueren aprobadas durante la vigencia de este Contrato, debiendo asumir {!! $contract->person->gender == 'masculino' ? 'EL <b>CONTRATADO</b>' : 'LA <b>CONTRATADA</b>' !!} la responsabilidad que ameriten los resultados emergentes del desempeño de sus funciones, deberes y atribuciones, así como los daños y perjuicios ocasionados.</em></p>
        <p><em>Asimismo, se hace constar, que el mecanismo de sancionar una falta administrativa cometida por {!! $contract->person->gender == 'masculino' ? 'EL <b>CONTRATADO</b>' : 'LA <b>CONTRATADA</b>' !!} será mediante Memorándum de llamada de atención, no librando de las otras responsabilidades que emerjan del hecho sancionado.</em></p>
        <br>
        <p><em><strong>CLÁUSULA D&Eacute;CIMA PRIMERA: (OBLIGACIONES DE LA ENTIDAD.)</strong></em></p>
        <p><em>La ENTIDAD se obliga a:</em></p>
        <ul>
            <li><em>A brindar los beneficios que la instituci&oacute;n establezca en base al contrato de prestaci&oacute;n de servicios incluido el pago de la remuneraci&oacute;n mensual por el servicio realizado.</em></li>
            <li><em>Al oportuno dep&oacute;sito de las retenciones realizadas por concepto de aporte al Seguro Social de Largo Plazo, as&iacute; como las que correspondan a los aportes patronales, AFP, entre otros.</em></li>
            <li><em>Al pago oportuno de los aportes patronales a la Seguridad Social de Corto Plazo.</em></li>
        </ul>
        <p><em><strong>CLÁUSULA D&Eacute;CIMA SEGUNDA: (ACEPTACI&Oacute;N)</strong></em></p>
        <p><em>En se&ntilde;al de aceptaci&oacute;n y estricto cumplimiento firman el presente Contrato en tres ejemplares de un mismo tenor y validez, la/el <b>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}</b>, en su calidad de <b>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} GAD-BENI </b>y por otra parte {{ $contract->person->gender == 'masculino' ? 'el Sr.' : 'la Sra.' }} <b>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</b>, en calidad de <b>{{ $contract->person->gender == 'masculino' ? 'CONTRATADO' : 'CONTRATADA' }}</b>.</em></p>
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
                    {{ $contract->person->gender == 'masculino' ? 'Sr.' : 'Sra.' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                    <b>{{ $contract->person->gender == 'masculino' ? 'CONTRATADO' : 'CONTRATADA' }}</b>
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('css')
    <style>
        
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection