@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Personal Eventual')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Personal eventual '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' al '.date('d', strtotime($contract->finish)).' de '.$months[intval(date('m', strtotime($contract->finish)))].' de '.date('Y', strtotime($contract->finish)).' con un sueldo de '.number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.').' Bs.'); !!}
    </div>
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 18px">CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL <br> <small>GAD-BENI-C.E-{{ $code }}</small> </h2>
        <p><em>Conste por el presente contrato de prestaci&oacute;n de servicios <strong>de Personal Eventual</strong> celebrado de conformidad a las siguientes cláusulas y condiciones:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA PRIMERA .- </strong></span><strong>(PARTES )</strong> </em></p>
        <p><em>1.- <strong>EL GOBIERNO AUT&Oacute;NOMO DEPARTAMENTAL DE BENI</strong>, con domicilio ubicado en la Plaza Principal Mcal. Jos&eacute; Ballivi&aacute;n representado legalmente para este acto por la/el <strong> {{ $signature ? $signature->name : setting('firma-autorizada.name') }} con C.I {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}, </strong>en su calidad de <strong>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} GAD-BENI</strong><strong>,</strong> designado mediante Decreto de <strong>Gobernaci&oacute;n </strong><strong>{{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}</strong>, que en adelante se denominará la <strong>ENTIDAD</strong>. </em></p>
        <p><em>2. {{ $contract->person->gender == 'masculino' ? 'El señor' : 'La señora' }} <b>{{ $contract->person->first_name }} {{ $contract->person->last_name }} </b> con C.I. <b>{{ $contract->person->ci }}</b>; con domicilio en {{ $contract->person->address }}, {!! $contract->person->profession ? 'de profesión <b>'.$contract->person->profession.'</b>, ' : '' !!}</em> <em>mayor de edad h&aacute;bil en toda forma de derecho que en adelante se denominará {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!}.</em></p>
        <p><em>Quienes celebran el presente CONTRATO ADMINISTRATIVO, de acuerdo a los t&eacute;rminos y condiciones siguientes:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEGUNDA</strong></span><strong>.-</strong> <strong>(ANTECEDENTES) </strong>La Ley No 1413 del Presupuesto General del Estado Gesti&oacute;n 2022 de 17 de diciembre de 2021, determina en su Disposici&oacute;n Final Segunda, inciso b). la vigencia entre otros de la Ley del Presupuesto General del Estado 2010, aprobado en el marco del articulo 158 numeral 11 de la Constituci&oacute;n Pol&iacute;tica del Estado, que en su el art. 22 (Ley de presupuesto general del Estado 2010)determina; "La remuneraci&oacute;n del Personal Eventual debe establecerse considerando las funciones y la escala salarial aprobada de la <b>ENTIDAD</b>, para la cual no se requiere ning&uacute;n instrumento legal adicional".</em></p>
        <p><em>Por su parte el art&iacute;culo 13 del D.S. 4646 de 29 de diciembre de 2021, se&ntilde;ala (Nivel de remuneraci&oacute;n del personal Eventual)</em></p>
        <p><em>I. La definici&oacute;n de la remuneraci&oacute;n del personal eventual, debe estar establecida en funci&oacute;n a la escala salarial, para lo cual, las unidades administrativas de cada entidad, elaboraran el cuadro de equivalencia de funciones que ser&aacute; avalado por la Unidad Jur&iacute;dica y con Visto Bueno (Vo.Bo.) de la MAE.</em></p>
        <p><em>II. Las Entidades Territoriales Aut&oacute;nomas y universidades p&uacute;blicas podr&aacute;n contratar personal eventual para funciones administrativas, utilizando los niveles de sus respectivas escalas salariales.</em></p>
        <p><em>El Clasificador Presupuestario de la presente gesti&oacute;n en el grupo 12000, Empleados No Permanentes, se&ntilde;ala que son gastos para remunerar los servicios prestados y otros beneficios a personas sujetas a contrato en forma transitoria o eventual, para MISIONES ESPEC&Iacute;FICAS, PROGRAMAS Y PROYECTOS DE INVERSI&Oacute;N, considerando para el efecto, la equivalencia de funciones y la escala salarial de acuerdo a la normativa vigente.</em></p>
        <p><em>Al respecto en funci&oacute;n a la verificaci&oacute;n de presupuesto, equivalencia de funciones y requisitos exigidos para el cargo, se instruye la contrataci&oacute;n del personal eventual referido.</em></p>
        <p ><strong><em><span style="text-decoration: underline;">CL&Aacute;USULA TERCERA</span></em></strong><strong><em><span>.- (BASE LEGAL)</span></em></strong></p>
        <ul style="margin-top: 0px; margin-bottom: 0px; padding-inline-start: 35px;">
            <li >
            <p ><em><span >Constituci&oacute;n Pol&iacute;tica del Estado, de fecha 07 de febrero de 2009, en todo lo que sea aplicable.</span></em></p>
            </li>
            <li >
            <p ><em><span >La Ley No 1178 Administraci&oacute;n y Control Gubernamentales de 20 de julio de 1990.</span></em></p>
            </li>
            <li >
            <p ><em><span >Ley N&deg; 2027 del Estatuto del Funcionario P&uacute;blico</span></em></p>
            </li>
            <li >
            <p ><em><span >Decreto Supremo N&deg; 26115, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</span></em></p>
            </li>
            <li >
            <p ><em><span >Ley N&deg; 1413 de 17 de diciembre de 2021, Presupuesto General del Estado - Gesti&oacute;n 2022.</span></em></p>
            </li>
            <li >
            <p ><em><span >Ley N&ordm; 223 Ley General para Personas con Discapacidad.</span></em></p>
            </li>
            <li >
            <p ><em><span >Ley N&ordm; 977 Ley de inserci&oacute;n laboral y de ayuda econ&oacute;mica para personas con discapacidad.</span></em></p>
            </li>
            <li >
            <p ><em><span >Decreto Supremo No 1893 del 12 de febrero de 2014.&nbsp;</span></em></p>
            </li>
            <li >
            <p ><em><span >Decreto Supremo N&ordm; 3437 del 20 de diciembre de 2017.</span></em></p>
            </li>
            <li >
            <p ><em><span >Decreto Supremo N&ordm; 4646 del 29 de diciembre de 2021, art&iacute;culo 13.</span></em></p>
            </li>
            <li >
            <p ><em><span >Decreto Supremo N&ordm; 4646 del 29 de diciembre de 2021 &ndash; Reglamento del PGE 2022.</span></em></p>
            </li>
            <li >
            <p ><em><span >Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental de Beni, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</span></em></p>
            </li>
            <li >
            <p ><em><span >Resoluci&oacute;n Administrativa de Gobernaci&oacute;n GAD BENI N&ordm; 01/2019.&nbsp;</span></em></p>
            </li>
        </ul>
        <p><strong><em><span style="text-decoration: underline;">CL&Aacute;USULA CUARTA</span></em></strong><strong><em><span>.- (DOCUMENTOS INTEGRANTES)</span></em></strong></p>
        <p ><em><span>Son documentos integrantes del presente contrato:</span></em></p>
        <ol style="margin-top: 0px; margin-bottom: 0px; padding-inline-start: 35px;">
            <li >
            <p ><em><span>Misi&oacute;n espec&iacute;fica</span></em></p>
            </li>
            <li >
                <p><em><span>Tutor&iacute;a Judicial cuando corresponda</span></em></p>
                <div class="saltopagina"></div>
                <div class="pt"></div>
            </li>
            <li >
            <p><em><span>Carnet de Discapacidad emitido por el Ministerio de Salud, y registrado en el SIPRUN-PCD (Sistema &Uacute;nico de Registro de Personas con Discapacidad). </span></em></p>
            </li>
            <li >
            <p ><em><span>Curr&iacute;culum Vitae del </span></em><strong><em><span>CONTRATADO</span></em></strong></p>
            </li>
            <li >
            <p ><em><span>Fotocopia de C&eacute;dula de Identidad.</span></em></p>
            </li>
            <li >
            <p ><em><span>Certificaci&oacute;n Presupuestaria POA 2022.</span></em></p>
            </li>
            <li >
            <p ><em><span>Certificaci&oacute;n POA 2022.</span></em></p>
            </li>
            <li >
            <p ><em><span>Otros documentos requeridos por la Direcci&oacute;n de Recursos Humanos del GAD BENI.</span></em></p>
            </li>
        </ol>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA QUINTA</strong></span><strong>.-</strong> <strong>(OBJETO)</strong></em></p>
        <p><em>La <b>ENTIDAD</b>, contrata los servicios {!! $contract->person->gender == 'masculino' ? '<b>del CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} para desempe&ntilde;ar la funci&oacute;n de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, en dependencias de la/el <b>{{ Str::upper($contract->unidad_administrativa->nombre) }}</b> dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b> con el nivel salarial <b>{{ $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->NumNivel }}</b> con cargo a la partida presupuestaria {{ $contract->program->number }} (Personal Eventual), en los t&eacute;rminos y condiciones que se establecen en este contrato, debiendo coadyuvar y coordinar sus actividades con su inmediato superior y las dem&aacute;s &aacute;reas de la <b>ENTIDAD</b>, donde sean requeridas. El presente contrato bajo ninguna manera podr&aacute; ser motivo de transferencia, subrogaci&oacute;n, delegaci&oacute;n, total o parcialmente.</em></p>
        <p><strong><em><span style="text-decoration: underline;">CL&Aacute;USULA SEXTA</span></em></strong><strong><em><span>.- (CONDICIONES)</span></em></strong></p>
        <p><strong> <em><span>6.1. FUNCIONES DEL CONTRATADO</span></em></strong></p>
        <p><em><span>Deber&aacute; cumplir las siguientes funciones que se establecen de forma enunciativa y no limitativa:</span></em></p>
        <p style=" margin-top: 0pt; margin-bottom: 7.95pt;"><strong><em><span>FUNCIONES GENERALES:</span></em></strong></p>
        <ul style="margin-top: 0px; margin-bottom: 0px; padding-inline-start: 35px;">
            <li >
                <p ><em><span >Coordinar la planificaci&oacute;n, ejecuci&oacute;n y seguimiento de las actividades del &aacute;rea.</span></em></p>
            </li>
            <li >
                <p ><em><span >Proporcionar apoyo t&eacute;cnico en la ejecuci&oacute;n de pol&iacute;ticas y objetivos de la Entidad.</span></em></p>
            </li>
            <li >
                <p ><em><span >Informar, recomendar y emitir criterios t&eacute;cnico-administrativos a su inmediato superior en lo que corresponde al &aacute;rea de su especialidad.</span></em></p>
            </li>
        </ul>
        <p style=" margin-top: 0pt; margin-bottom: 7.95pt;"><strong><em><span>FUNCIONES O MISIONES ESPEC&Iacute;FICAS Y RESPONSABILIDADES:</span></em></strong></p>
        <ul style="margin-top: 0px; margin-bottom: 0px; padding-inline-start: 35px;">
        <li >
        <p ><em><span >Confidencialidad de la informaci&oacute;n y documentaci&oacute;n del &aacute;rea de trabajo bajo su custodia, conocimiento y otros.</span></em></p>
        </li>
        <li >
        <p ><em><span >Presentar informes mensuales, trimestrales o de la manera que su superior inmediato solicite seg&uacute;n los programas de evaluaci&oacute;n y cumplimiento de objetivos de la </span></em><strong><em><span >ENTIDAD</span></em></strong><em><span > en formato impreso y digital.</span></em></p>
        </li>
        <li >
        <p ><em><span >Manejar en forma ordenada, sistematizada y archivada toda la documentaci&oacute;n bajo su cargo.</span></em></p>
        </li>
        <li >
        <p ><em><span >Participar en otras funciones delegadas afines al cargo designado.</span></em></p>
        </li>
        <li >
        <p ><em><span >En caso de necesidad/emergencia se podr&aacute; delegar otras funciones.</span></em></p>
        </li>
        </ul>
        <p>&nbsp;</p>
        <p><strong><em><span>6.2. DEPENDENCIA Y CONTROLES</span></em></strong></p>
        <p ><em><span>Para el cumplimiento de sus funciones y de acuerdo a las responsabilidades del </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> depende jer&aacute;rquicamente del inmediato superior y la MAE, que establecer&aacute;n adicionalmente otras funciones y &oacute;rdenes de acuerdo al objeto del presente contrato.&nbsp;</span></em></p>
        <p><strong><em><span>6.3. HORARIO Y DISPONIBILIDAD DEL CONTRATADO</span></em></strong></p>
        <p ><em><span>El </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> cumplir&aacute; con el horario de trabajo de 8:00 Hrs diarias y que por razones a la latente emergencia sanitaria y por disposici&oacute;n del Ministerio de Trabajo, Empleo y Previsi&oacute;n Social, ser&aacute; de </span></em><strong><em><span>HORARIO CONTINUO DE HRS 8:00 a 16:00 de lunes a viernes</span></em></strong><em><span>, en el lugar que le sea asignado , sin embargo, de acuerdo a necesidades Institucionales se podr&aacute; cambiar el horario manteniendo las 8 Horas laborales, adem&aacute;s el servidor deber&aacute; prestar servicios fuera de los horarios establecidos, conforme instrucci&oacute;n verbal o escrita que reciba de sus superiores, asimismo las inasistencias, atrasos, permisos y DESCUENTOS estar&aacute;n en sujeci&oacute;n al Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental del Beni.</span></em></p>
        <p ><em><span>El </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> declara su plena e inmediata disponibilidad para el desempe&ntilde;o de las funciones para las cuales es contratado; con absoluta dedicaci&oacute;n, &eacute;tica y pro actividad, conducentes al logro de los objetivos de este contrato, no pudiendo realizar actividades que deterioren o menoscaben la imagen de LA INSTITUCI&Oacute;N. En consecuencia, el servicio es de dedicaci&oacute;n exclusiva, no pudiendo prestar servicios o funciones similares y/o iguales a terceros en horarios se&ntilde;alados en el numeral.</span></em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEPTIMA .- </strong></span><strong>(REMUNERACION )</strong> La <b>ENTIDAD</b> se obliga a pagar en favor {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} una remuneraci&oacute;n mensual de <b>Bs.- {{ NumerosEnLetras::convertir($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 'Bolivianos', true) }}</b> por mes vencido, pago que ser&aacute; en efectivo o mediante dep&oacute;sito bancario u otro procedimiento formal, conforme equivalencia de funciones y escala salarial del personal eventual de la <b>ENTIDAD</b>, monto que ser&aacute; sujeto al descuento por los aportes propios a la AFP y el r&eacute;gimen de seguridad social a corto plazo seg&uacute;n normativa vigente, as&iacute; como lo dispuesto en materia tributaria si correspondiera; el l&iacute;quido pagable final de la remuneraci&oacute;n convenida se establecer&aacute; previa deducci&oacute;n de los aportes.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA OCTAVA .- </strong></span><strong>(DURACION Y CAR&Aacute;CTER DEFINIDO )</strong> En el marco legal citado en antecedentes, el presente contrato tendr&aacute; calidad de <b>CONTRATO DE PERSONAL EVENTUAL</b>, computable a partir <b>del {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }} hasta el {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}</b>.</em></p>
        <p ><em><span>El </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> no estar&aacute; sujeto a periodo de prueba y la </span></em><strong><em><span>ENTIDAD</span></em></strong><em><span> podr&aacute; determinar la finalizaci&oacute;n del contrato, conforme al procedimiento dispuesto en normas complementarias y reglamentarias, si as&iacute; lo considera.</span></em></p>

        <div class="saltopagina"></div>
        <div class="pt"></div>

        <p><strong><em><span style="text-decoration: underline; text-decoration-skip-ink: none;">CL&Aacute;USULA NOVENA.- </span></em></strong><strong><em><span>(CAUSALES PARA RESOLUCI&Oacute;N DEL CONTRATO)</span></em></strong></p>
        <p ><em><span>El contrato se tendr&aacute; por resuelto por Cumplimiento del mismo, caso en el cual tanto la </span></em><strong><em><span>ENTIDAD</span></em></strong><em><span> como el </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span>, dar&aacute;n por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas y cada una de las cl&aacute;usulas contenidas en el mismo sin necesidad de comunicaci&oacute;n expresa. No obstante, el contrato podr&aacute; resolverse antes de la fecha de conclusi&oacute;n por las siguientes causales, en forma directa y sin necesidad de requerimiento Judicial y/o administrativo alguno:</span></em></p>
        <ol>
            <li style="font-weight: bold; font-style: italic; margin-left: -18pt;">
                <p><strong><em><span>Por resoluci&oacute;n de Contrato:</span></em></strong></p>
            </li>
        </ol>
        <p ><em><span>1.1. A requerimiento de la </span></em><strong><em><span>GOBERNACI&Oacute;N</span></em></strong><em><span>, por causales atribuibles al </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> en base a Informe:</span></em></p>
        <ol style="margin-top: 0px; margin-bottom: 0px; padding-inline-start: 35px;">
            <li>
                <p><em><span>Cuando el </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> en el desempe&ntilde;o de sus funciones ocasione da&ntilde;os y perjuicios al </span></em><strong><em><span>CONTRATANTE</span></em></strong><em><span> o a terceros en raz&oacute;n de su cargo.</span></em></p>
            </li>
            <li>
                <p><em><span>Cuando el </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span>, incumpla total o parcialmente los t&eacute;rminos establecidos en el presente contrato, las obligaciones propias del cargo, &oacute;rdenes superiores o demuestre negligencia, falta de inter&eacute;s en el cumplimiento de sus funciones o desarrolle labores que no contribuyan al cumplimiento de los Objetivos del &aacute;rea funcional del cual depende, en este caso el inmediato superior realizar&aacute; la evaluaci&oacute;n correspondiente, mediante la emisi&oacute;n del informe pertinente a la funci&oacute;n que desempe&ntilde;a, en base a los cuales la </span></em><strong><em><span>ENTIDAD </span></em></strong><em><span>Se reserva de manera unilateral la facultad de resolver el presente contrato.</span></em></p>
            </li>
            <li>
                <p><em><span>Inasistencia injustificada de m&aacute;s de tres (3) d&iacute;as h&aacute;biles consecutivos o seis (6) d&iacute;as h&aacute;biles discontinuos en un (1) mes.</span></em></p>
            </li>
            <li>
                <p><em><span>Abuso de confianza, robo, hurto debidamente comprobado y sentencia ejecutoriada.</span></em></p>
            </li>
            <li>
                <p><em><span>Cuando el </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> est&aacute; comprendido dentro de las incompatibilidades o prohibiciones para ejercer la funci&oacute;n p&uacute;blica, por raz&oacute;n de parentesco hasta el cuarto grado de consanguinidad y segundo de afinidad.</span></em></p>
            </li>
            <li>
                <p><em><span>Cuando se evidencie que el </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> percibe dos remuneraciones en calidad de servidor p&uacute;blico que provengan de recursos p&uacute;blicos, conforme a normas en vigencia o incurre en incompatibilidad por conflicto de intereses seg&uacute;n el formulario respectivo</span></em></p>
            </li>
            <li>
                <p><em><span>Por infracci&oacute;n de las normas internas que rigen en la </span></em><strong><em><span>ENTIDAD</span></em></strong><em><span> (Reglamento Interno de Personal - RIP) y otras causales previstas en la normativa legal aplicable.</span></em></p>
            </li>
        </ol>
        <p ><em><span>En todos los casos ser&aacute; suficiente una comunicaci&oacute;n escrita con quince (15) d&iacute;as de anticipaci&oacute;n, mediante nota o memor&aacute;ndum al </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> por parte del </span></em><strong><em><span>CONTRATANTE</span></em></strong><em><span> a trav&eacute;s de la Direcci&oacute;n de Recursos Humanos.</span></em></p>
        <p ><em><span>Tambi&eacute;n opera la resoluci&oacute;n por voluntad del </span></em><strong><em><span>CONTRATADO</span></em></strong><em><span> comunicada a la INSTITUCI&Oacute;N para aceptaci&oacute;n de mutuo acuerdo.</span></em></p>
        <p><strong><em><span>CL&Aacute;USULA D&Eacute;CIMA: (DERECHOS Y OBLIGACIONES DEL CONTRATADO)</span></em></strong></p>
        <ul>
        <li >
        <p ><em><span >Cumplir lo dispuesto en la Constituci&oacute;n Pol&iacute;tica del Estado, Leyes, Decretos y Resoluciones Nacionales y Departamentales.</span></em></p>
        </li>
        <li >
        <p ><em><span >Gozar del Seguro de salud para lo cual se deber&aacute; afiliar a la Caja de Seguro Social donde estuviera registrado el Gobierno Aut&oacute;nomo del Departamento del Beni, debiendo presentar toda la documentaci&oacute;n personal y de sus familiares dependientes si los tuviera para dicha afiliaci&oacute;n.</span></em></p>
        </li>
        <li >
        <p ><em><span >Cumplir los horarios de ingreso y salida, debiendo incorporarse a su lugar de trabajo inmediatamente despu&eacute;s del marcado de tarjeta o sistema de control de asistencia bajo conminatoria de los descuentos respectivos.</span></em></p>
        </li>
        <li >
        <p ><em><span >Preservar y cuidar los activos fijos, material, equipos de computaci&oacute;n, documentaci&oacute;n y todo cuanto fuere asignado para el desempe&ntilde;o de funciones, una vez terminado o resuelto el contrato proceder a la respectiva devoluci&oacute;n.</span></em></p>
        </li>
        <li >
        <p ><em><span >Garantizar y responder por la funci&oacute;n asignada de tal forma que, de ser requerida su presencia f&iacute;sica para cualquier aclaraci&oacute;n posterior a la vigencia del contrato, se obliga a no negar su participaci&oacute;n.</span></em></p>
        </li>
        <li >
        <p ><em><span >Es su obligaci&oacute;n cumplir y acatar las instrucciones de su inmediato superior, y otras contenidas en los manuales, reglamentos, instructivos, circulares y otros instrumentos normativos de la instituci&oacute;n</span></em></p>
        </li>
        <li >
        <p ><em><span >Informar cuantas veces sea solicitado o necesario sobre sus actividades laborales y otros a fin de realizar los procesos de evaluaci&oacute;n correspondientes a sus funciones.</span></em></p>
        </li>
        <li >
        <p ><em><span >Desarrollar sus funciones, atribuciones y deberes administrativos con puntualidad, celeridad, econom&iacute;a, eficiencia y probidad.</span></em></p>
        </li>
        <li >
        <p ><em><span >Cumplir con la jornada laboral</span></em></p>
        </li>
        <li >
        <p ><em><span >Tambi&eacute;n se hallan entre sus deberes y obligaciones el cumplimiento del Reglamento Interno de Personal, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato</span></em></p>
        </li>
        <li >
        <p style="line-height: 1.505; text-align: justify; margin-top: 0pt; margin-bottom: 6.1pt;"><em><span >Asumir toda la responsabilidad por el trabajo encomendado, oblig&aacute;ndose a la preservaci&oacute;n del material, documentos, equipos, activos y/o maquinaria que se encuentra a su cargo y a guardar reserva de informaci&oacute;n confidencial que sea de su conocimiento. El resultado de sus actividades y el contenido de ellas en cualquier medio (informe, documentos, discos magn&eacute;ticos, etc.) pertenecen exclusivamente al CONTRATANTE, la divulgaci&oacute;n de dicha informaci&oacute;n sin autorizaci&oacute;n superior, escrita y expresa implicar&aacute; la violaci&oacute;n a los principios de confiablidad e idoneidad, con las consecuencias previstas en el contrato, la Ley N&ordm; 1178 de Administraci&oacute;n y Control Gubernamentales y la reparaci&oacute;n del da&ntilde;o civil que ocasionare.</span></em></p>
        </li>
        </ul>

        <div class="saltopagina"></div>
        <div class="pt"></div>

        <p style="line-height: 1.505; text-align: justify; margin-top: 0pt; margin-bottom: 6.1pt;"><strong><em><span>CL&Aacute;USULA D&Eacute;CIMA PRIMERA: (OBLIGACIONES DE LA ENTIDAD.) </span></em></strong><em><span>La ENTIDAD se obliga a:</span></em></p>
        <ul>
        <li >
        <p ><em><span >A brindar los beneficios que la instituci&oacute;n establezca en base al contrato de prestaci&oacute;n de servicios incluido el pago de la remuneraci&oacute;n mensual por el servicio realizado.</span></em></p>
        </li>
        <li >
        <p ><em><span >Al oportuno dep&oacute;sito de las retenciones realizadas por concepto de aporte al Seguro Social de Largo Plazo, as&iacute; como las que correspondan a los aportes patronales, AFP, entre otros.</span></em></p>
        </li>
        <li >
        <p ><em><span >A pagar oportunamente los aportes patronales a la Seguridad Social de Corto Plazo.</span></em></p>
        </li>
        </ul>
        <p><em><strong>CLÁUSULA D&Eacute;CIMA SEGUNDA: (ACEPTACI&Oacute;N)</strong></em></p>
        <p><em>En se&ntilde;al de aceptaci&oacute;n y estricto cumplimiento firman el presente Contrato en tres ejemplares de un mismo tenor y validez, la/el <b>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}</b>, en su calidad de <b>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} GAD-BENI </b>y por otra parte {{ $contract->person->gender == 'masculino' ? 'el Sr.' : 'la Sra.' }} <b>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</b>, en calidad de <b>{{ $contract->person->gender == 'masculino' ? 'CONTRATADO' : 'CONTRATADA' }}</b>.</em></p>
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
            <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</p>
        <table width="100%" style="text-align: center; margin-top: 120px;">
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
        .content {
            padding: 50px 34px;
            font-size: 11px;
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
                height: 90px;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection