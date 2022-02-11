@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Personal Eventual')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = str_pad($contract->code, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($contract->start));
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Personal eventual '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' al '.date('d', strtotime($contract->finish)).' de '.$months[intval(date('m', strtotime($contract->finish)))].' de '.date('Y', strtotime($contract->finish)).' con un sueldo de '.number_format($contract->cargo->nivel->Sueldo, 2, ',', '.').' Bs.'); !!}
    </div>
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 18px">CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL <br> <small>GAD-BENI-C.E-{{ $code }}</small> </h2>
        <p><em>Conste por el presente contrato de prestaci&oacute;n de servicios </em><em><strong>de Personal Eventual</strong></em><em> celebrado de conformidad a las siguientes clausulas y condiciones:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA PRIMERA .- </strong></span></em><em><strong>(PARTES ).-</strong></em><em> </em></p>
        <p><em>1.- </em><em><strong>EL GOBIERNO AUT&Oacute;NOMO DEPARTAMENTAL DE BENI</strong></em><em>, con domicilio </em><em>ubicado en la Plaza Principal Mcal. Jos&eacute; Ballivi&aacute;n representado</em><em> legalmente para este acto por el </em><em><strong> {{ setting('firma-autorizada.name') }} con C.I {{ setting('firma-autorizada.ci') }}, </strong></em><em><strong>en su calidad de </strong></em><em><strong>{{ setting('firma-autorizada.job') }} (S.D.A.F) GAD-BENI</strong></em><em><strong>,</strong></em><em> designado mediante Decreto de </em><em><strong>Gobernaci&oacute;n </strong></em><em><strong>{{ setting('firma-autorizada.designation') }}, </strong></em><em>y designado como Autoridad responsable de Procesos de Contrataci&oacute;n - RPA, Autoridad responsable de procesos de contrataci&oacute;n de Licitaci&oacute;n Publica &ndash; RPC y contrataciones directas, que en adelante se denominara </em><em><strong>LA ENTIDAD</strong></em><em>. </em></p>
        <p><em>2. {{ $contract->person->gender == 'masculino' ? 'El Señor' : 'La Señora' }} </em><em>{{ $contract->person->first_name }} {{ $contract->person->last_name }} con C.I. {{ $contract->person->ci }}; con domicilio en {{ $contract->person->address }}, de profesi&oacute;n {{ $contract->person->profession }}</em> <em>mayor de edad h&aacute;bil en toda forma de derecho que en adelante se denominada </em><em><strong>EL CONTRATADO.</strong></em></p>
        <p><em>Quienes celebran el presente CONTRATO ADMINISTRATIVO, de acuerdo a los t&eacute;rminos y condiciones siguientes:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA SEGUNDA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(ANTECEDENTES).- </strong></em><em>La Ley No 1413 del Presupuesto General del Estado Gesti&oacute;n 2022 de 17 de diciembre de 2021, determina en su Disposici&oacute;n Final Segunda, inciso b). la vigencia entre otros de la Ley del Presupuesto General del Estado 2010, aprobado en el marco del articulo 158 numeral 11 de la Constituci&oacute;n Pol&iacute;tica del Estado, que en su el art. 22 (Ley de presupuesto general del Estado 2010)determina; "La remuneraci&oacute;n del Personal Eventual debe establecerse considerando las funciones y la escala salarial aprobada de la entidad, para la cual no se requiere ning&uacute;n instrumento legal adicional".</em></p>
        <p><em>Por su parte el art&iacute;culo 13 del D.S. 4646 de 29 de diciembre de 2021, se&ntilde;ala (Nivel de remuneraci&oacute;n del personal Eventual)</em></p>
        <p><em>I. La definici&oacute;n de la remuneraci&oacute;n del personal eventual, debe estar establecida en funci&oacute;n a la escala salarial, para lo cual, las unidades administrativas de cada entidad, elaboraran el cuadro de equivalencia de funciones que ser&aacute; avalado por la Unidad Jur&iacute;dica y con Visto Bueno (Vo.Bo.) de la MAE.</em></p>
        <p><em>II. Las Entidades Territoriales Aut&oacute;nomas y universidades p&uacute;blicas podr&aacute;n contratar personal eventual para funciones administrativas, utilizando los niveles de sus respectivas escalas salariales.</em></p>
        <p><em>El Clasificador Presupuestario de la presente gesti&oacute;n en el grupo 12000, Empleados No Permanentes, se&ntilde;ala que son gastos para remunerar los servicios prestados y otros beneficios a personas sujetas a contrato en forma transitoria o eventual, para MISIONES ESPEC&Iacute;FICAS, PROGRAMAS Y PROYECTOS DE INVERSI&Oacute;N, considerando para el efecto, la equivalencia de funciones y la escala salarial de acuerdo a la normativa vigente.</em></p>
        <p><em>Al respecto en funci&oacute;n a la verificaci&oacute;n de presupuesto, equivalencia de funciones y requisitos exigidos para el cargo, se instruye la contrataci&oacute;n del personal eventual referido.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA TERCERA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(BASE LEGAL).- </strong></em></p>
        <p>
            <ul>
                <li><em>Constituci&oacute;n Pol&iacute;tica del Estado, de fecha 07 de febrero de 2009, en todo lo que sea aplicable.</em></li>
                <li><em>La Ley No 1178 Administraci&oacute;n y Control Gubernamentales de 20 de julio de 1990.</em></li>
                <li><em>Ley N&deg; 2027 del Estatuto del Funcionario P&uacute;blico</em></li>
                <li><em>Decreto Supremo N&deg; 26115, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</em></li>
                <li><em>Ley N&deg; 1413 de 17 de diciembre de 2021, Presupuesto General del Estado - Gesti&oacute;n 2022.</em></li>
                <li><em>Decreto Supremo No 4646 de 29 de diciembre de 2021, art&iacute;culo 13.</em></li>
                <li><em>Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental de Beni, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</em></li>
            </ul>
        </p>
        <p><em>En ese marco el <strong>CONTRATADO</strong> no estar&aacute; bajo el r&eacute;gimen de la Ley General del Trabajo ni el Decreto Reglamentario 224 de fecha 23 de agosto de 1943.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA CUARTA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(DOCUMENTOS INTEGRANTES).-</strong></em></p>
        <p><em>Son documentos integrantes del presente contrato:</em></p>
        <p><em>a) Misión específica</em></p>
        <p><em>b) Curr&iacute;culum Vitae del Contratado</em></p>
        <p><em>c) Certificaci&oacute;n Presupuestaria</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA QUINTA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(OBJETO).-</strong></em></p>
        <p><em>La ENTIDAD, contrata los servicios del CONTRATADO para desempe&ntilde;ar la funci&oacute;n de </em><em>{{ Str::upper($contract->cargo->Descripcion) }}</em><em>, en dependencias de la/el </em><em>{{ Str::upper($contract->unidad_administrativa->Nombre) }}</em><em> dependiente de </em><em>la/el {{ Str::upper($contract->direccion_administrativa->NOMBRE) }}</em><em> con un </em><em>nivel Salarial {{ $contract->cargo->nivel->NumNivel }}</em><em> con cargo a la partida presupuestaria {{ $contract->program->number }} (Personal Eventual), en los t&eacute;rminos y condiciones que se establecen en este contrato, debiendo coadyuvar y coordinar sus actividades con su inmediato superior y las dem&aacute;s &aacute;reas de La ENTIDAD, donde sean requeridas. El presente contrato bajo ninguna manera podr&aacute; ser motivo de transferencia, subrogaci&oacute;n, delegaci&oacute;n, total o parcialmente.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA SEXTA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(CONDICIONES).-</strong></em></p>
        <p><em>6.1. FUNCIONES DEL CONTRATADO: Deber&aacute; cumplir las siguientes funciones que se establecen de forma enunciativa y no limitativa:</em></p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>
        
        <p><em><strong>FUNCIONES GENERALES:</strong></em></p>
        <p><em>
            {!! $contract->details_work !!}    
        </em></p>
        <p><em><strong>FUNCIONES O MISIONES ESPEC&Iacute;FICAS Y RESPONSABILIDADES:</strong></em></p>
        <ul>
            <li><em>Confidencialidad de la informaci&oacute;n y documentaci&oacute;n del &aacute;rea de trabajo bajo su custodia, conocimiento y otros.</em></li>
            <li><em>Presentar informes mensuales, trimestrales o de la manera que su superior inmediato solicite seg&uacute;n los programas de evaluaci&oacute;n y cumplimiento de objetivos de la Entidad en formato impreso y digital.</em></li>
            <li><em>Manejar en forma ordenada, sistematizada y archivada toda la documentaci&oacute;n bajo su cargo.</em></li>
            <li><em>Participar en otras funciones delegadas</em><em> </em></li>
        </ul>
        <p><em><strong>6.2. DEPENDENCIA Y CONTROLES</strong></em></p>
        <p><em>Para el cumplimiento de sus funciones y de acuerdo a las responsabilidades el CONTRATADO depende jer&aacute;rquicamente del inmediato superior y la MAE, que establecer&aacute;n adicionalmente otras funciones y &oacute;rdenes de acuerdo al objeto del presente contrato.</em></p>
        <p><em><strong>6.3. HORARIO Y DISPONIBILIDAD DEL CONTRATADO</strong></em></p>
        <p><em>El CONTRATADO cumplir&aacute; con el horario de trabajo de 8:00 Hrs diarias y que por razones a la latente emergencia sanitaria y por disposici&oacute;n del Ministerio de Trabajo ser&aacute; de </em><em><strong>HORARIO CONTIN&Uacute;O DE HRS 8:00 a 16:00</strong></em><em> </em><em><strong>de lunes a viernes</strong></em><em>, en el lugar que le sea asignado , sin embargo, de acuerdo a necesidades Institucionales se podr&aacute; cambiar el horario manteniendo las 8 Horas laborales, adem&aacute;s el servidor deber&aacute; prestar servicios fuera de los horarios establecidos, conforme instrucci&oacute;n verbal o escrita que reciba de sus superiores. </em><em>, asimismo las inasistencias, atrasos, permisos y DESCUENTOS estar&aacute;n en sujeci&oacute;n al Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental del Beni.</em></p>
        <p><em>El CONTRATADO declara su plena e inmediata disponibilidad para el desempe&ntilde;o de las funciones para las cuales es contratado; con absoluta dedicaci&oacute;n, &eacute;tica y pro actividad, conducentes al logro de los objetivos de este contrato, no pudiendo realizar actividades que deterioren o menoscaben la imagen de LA INSTITUCION. En consecuencia, el servicio es de dedicaci&oacute;n exclusiva, no pudiendo prestar servicios o funciones similares y/o iguales a terceros en horarios se&ntilde;alados en el numeral.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA SEPTIMA .- </strong></span></em><em><strong>(REMUNERACION ).-</strong></em><em> La ENTIDAD se obliga a pagar en favor del CONTRATADO una remuneraci&oacute;n mensual de </em><em>Bs.- {{ NumerosEnLetras::convertir($contract->cargo->nivel->Sueldo, 'Bolivianos', true) }}</em><em> por mes vencido, pago que ser&aacute; en efectivo o mediante dep&oacute;sito bancario u otro procedimiento formal, conforme equivalencia de funciones y escala salarial del personal eventual de la entidad, monto que ser&aacute; sujeto al descuento por los aportes propios a la AFP y el r&eacute;gimen de seguridad social a corto plazo seg&uacute;n normativa vigente, as&iacute; como lo dispuesto en materia tributaria si correspondiera; el l&iacute;quido pagable final de la remuneraci&oacute;n convenida se establecer&aacute; previa deducci&oacute;n de los aportes y otras cargas definidas</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA OCTAVA .- </strong></span></em><em><strong>(DURACION Y CAR&Aacute;CTER DEFINIDO ).-</strong></em><em> En el marco legal citado en antecedentes, el presente contrato tendr&aacute; calidad de CONTRATO DE PERSONAL EVENTUAL, computable a partir del {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }} hasta el {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}.</em></p>
        <p><em>El CONTRATADO no estar&aacute; sujeto a periodo de prueba y la ENTIDAD podr&aacute; determinar la finalizaci&oacute;n del contrato, conforme al procedimiento dispuesto en normas complementarias y reglamentarias, si as&iacute; lo considera.</em></p>
        <p><em>LA ENTIDAD, se reserva el derecho de ampliar el plazo de prestaci&oacute;n de servicio, mediante adenda por un plazo igual o Inferior al Inicialmente pactado (DENTRO DEL PERIODO FISCAL), previa aceptaci&oacute;n del CONTRATADO, instrumento accesorio que formar&aacute; parte inseparable del principal, sin que pueda tacharse o considerarse como un nuevo contrato o una nueva relaci&oacute;n laboral.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLAUSULA NOVENA .- </strong></span></em><em><strong>(CAUSALES PARA RESOLUCION DEL CONTRATO)</strong></em></p>
        <p><em>El contrato se tendr&aacute; por resuelto Por Cumplimiento del mismo, caso en el cual tanto la ENTIDAD como el CONTRATADO, dar&aacute;n por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas y cada una de las cl&aacute;usulas contenidas en el mismo sin necesidad de comunicaci&oacute;n expresa. No obstante el contrato podr&aacute; resolverse antes de la fecha de conclusi&oacute;n por las siguientes causales, en forma directa y sin necesidad requerimiento Judicial y/o administrativo alguno:</em></p>
        <p><em><strong>1. Por resoluci&oacute;n de Contrato:</strong></em></p>
        <p><em>1.1. A requerimiento de la GOBERNACI&Oacute;N, por causales atribuibles al CONTRATADO en base a Informe:</em></p>
        <p><em><strong>a)</strong></em><em> </em><em>Cuando el </em><em><strong>CONTRATADO</strong></em><em> en el desempe&ntilde;o de sus funciones ocasione da&ntilde;os y perjuicios al </em><em><strong>CONTRATANTE</strong></em><em> o a terceros en raz&oacute;n de su cargo.</em></p>
        <p><em><strong>b)</strong></em><em> Cuando el CONTRATADO, incumpla total o parcialmente los t&eacute;rminos establecidos en el presente contrato, las obligaciones propias del cargo, &oacute;rdenes superiores o demuestre negligencia, falta de inter&eacute;s en el cumplimiento de sus funciones o desarrolle labores que no contribuyan al cumplimiento de los Objetivos del &aacute;rea funcional del cual depende, en este caso el inmediato superior realizar&aacute; la evaluaci&oacute;n correspondiente, mediante la emisi&oacute;n del informe pertinente a la funci&oacute;n que desempe&ntilde;a, en base a los cuales la ENTIDAD Se reserva de manera unilateral la facultad de resolver el presente contrato.</em></p>
        <p><em><strong>c)</strong></em><em> Inasistencia injustificada de m&aacute;s de tres (3) d&iacute;as h&aacute;biles consecutivos o seis (6) d&iacute;as h&aacute;biles discontinuos en un (1) mes. </em></p>
        <p><em><strong>d)</strong></em><em> Abuso de confianza, robo, hurto debidamente comprobado.</em></p>
        <p><em><strong>e)</strong></em><em> Cuando el CONTRATADO est&aacute; comprendido dentro de las incompatibilidades o prohibiciones para ejercer la funci&oacute;n p&uacute;blica, por raz&oacute;n de parentesco hasta el cuarto grado de consanguineidad y segundo de afinidad.</em></p>
        <p><em><strong>f)</strong></em><em> Cuando se evidencie que el CONTRATADO percibe dos remuneraciones en calidad de servidor p&uacute;blico que provengan de recursos p&uacute;blicos, conforme a normas en vigencia o incurre en incompatibilidad por conflicto de intereses seg&uacute;n el formulario respectivo</em></p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>

        <p><em><strong>g)</strong></em><em> Suspender la prestaci&oacute;n de servicios por causa justificada y autorizaci&oacute;n del inmediato superior, a requerimiento de la INSTITUCION y/o a solicitud del CONTRATADO.</em></p>
        <p><em><strong>h)</strong></em><em> Por infracci&oacute;n de las normas internas que rigen en la entidad (Reglamento Interno de Personal - RIP) y otras causales previstas en la normativa legal aplicable.</em></p>
        <p><em>En todos los casos ser&aacute; suficiente una comunicaci&oacute;n escrita con quince (15) d&iacute;as de anticipaci&oacute;n, mediante nota o memor&aacute;ndum al </em><em><strong>CONTRATADO</strong></em><em> por parte del </em><em><strong>CONTRATANTE</strong></em><em> a trav&eacute;s de la Direcci&oacute;n de Recursos Humanos.</em></p>
        <p><em>Por su parte el </em><em><strong>CONTRATADO</strong></em><em> podr&aacute; renunciar de manera voluntaria, asumiendo la obligaci&oacute;n de hacer conocer la misma con quince (15) d&iacute;as calendario de anticipaci&oacute;n</em></p>
        <p><em><strong>CLAUSULA D&Eacute;CIMA: (DERECHOS Y OBLIGACIONES DEL CONTRATADO)</strong></em></p>
        <ul>
            <li><em>Cumplir lo dispuesto en la Constituci&oacute;n Pol&iacute;tica del Estado, Leyes, Decretos y Resoluciones Nacionales y Departamentales.</em></li>
            <li><em>Gozar del Seguro de salud para lo cual se deber&aacute; a</em><em>filiar a la Caja de Seguro Social donde estuviera registrado el </em><em>Gobierno Aut&oacute;nomo del Departamento del Beni</em><em>, debiendo presentar toda la documentaci&oacute;n personal y de sus familiares dependientes si los tuviera para dicha afiliaci&oacute;n. </em></li>
            <li><em>Cumplir los horarios de ingreso y salida, debiendo incorporarse a su lugar de trabajo inmediatamente despu&eacute;s del marcado de tarjeta o sistema de control de asistencia bajo conminatoria de los descuentos respectivos.</em></li>
            <li><em>Preservar y cuidar los activos fijos, material, equipos de computaci&oacute;n, documentaci&oacute;n y todo cuanto fuere asignado para el desempe&ntilde;o de funciones, una vez terminado o resuelto el contrato proceder a la respectiva devoluci&oacute;n.</em></li>
            <li><em>Garantizar y responder por la funci&oacute;n asignada de tal forma que, de ser requerida su presencia f&iacute;sica para cualquier aclaraci&oacute;n posterior a la vigencia del contrato, se obliga a no negar su participaci&oacute;n.</em></li>
            <li><em>Es su obligaci&oacute;n cumplir y acatar las instrucciones de su inmediato superior, y otras contenidas en los manuales, reglamentos, instructivos, circulares y otros instrumentos normativos de la instituci&oacute;n</em></li>
            <li><em>Informar cuantas veces sea solicitado o necesario sobre sus actividades laborales y otros a fin de realizar los procesos de evaluaci&oacute;n correspondientes a sus funciones.</em></li>
            <li><em>Desarrollar sus funciones atribuciones y deberes administrativos con puntualidad, celeridad, econom&iacute;a, eficiencia y probidad.</em></li>
            <li><em>Cumplir con la jornada laboral </em></li>
            <li><em>Tambi&eacute;n se hallan entre sus deberes y obligaciones el cumplimiento del Reglamento Interno de Personal, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato </em></li>
            <li><em>Asumir toda la responsabilidad por el trabajo encomendado, oblig&aacute;ndose a la preservaci&oacute;n del material, documentos, equipos, activos y/o maquinaria que se encuentra a su cargo y a guardar reserva de informaci&oacute;n confidencial que sea de su conocimiento. El resultado de sus actividades y el contenido de ellas en cualquier medio (informe, documentos, discos magn&eacute;ticos, etc.) pertenecen exclusivamente al CONTRATANTE, la divulgaci&oacute;n de dicha informaci&oacute;n sin autorizaci&oacute;n superior, escrita y expresa implicar&aacute; la violaci&oacute;n a los principios de confiablidad e idoneidad, con las consecuencias previstas en el contrato, la Ley N&ordm; 1178 de Administraci&oacute;n y Control Gubernamentales y la reparaci&oacute;n del da&ntilde;o civil que ocasionare.</em></li>
        </ul>
        <p><em><strong>CLAUSULA D&Eacute;CIMA PRIMERA: (OBLIGACIONES DE LA ENTIDAD.)</strong></em></p>
        <p><em>La ENTIDAD se obliga a:</em></p>
        <ul>
            <li><em>A brindar los beneficios que la instituci&oacute;n establezca en base al contrato de prestaci&oacute;n de servicios incluido el pago de la remuneraci&oacute;n mensual por el servicio realizado.</em></li>
            <li><em>Al oportuno dep&oacute;sito de las retenciones realizadas por concepto de aporte al Seguro Social de Largo Plazo, as&iacute; como las que correspondan a los aportes patronales, AFP, entre otros.</em></li>
            <li><em>Al pago oportuno de los aportes patronales a la Seguridad Social de Corto Plazo.</em></li>
        </ul>
        <p><em><strong>CL&Aacute;USULA D&Eacute;CIMA SEGUNDA.- (IMPOSIBILIDAD SOBREVINIENTE)</strong></em><em> Las partes estar&aacute;n exentas de responsabilidad cuando el incumplimiento de sus obligaciones se deba a acontecimientos imprevisibles, siempre y cuando no se deban a error, negligencia u omisi&oacute;n y est&eacute;n fuera de su control.</em></p>
        <p><em>Si la imposibilidad sobreviniente impide u obstruye el cumplimiento del contrato, la parte afectada ser&aacute; eximida de su cumplimiento solamente por el plazo que dure dicha imposibilidad previa justificaci&oacute;n y descargo. La parte que invoque la existencia de imposibilidad sobreviniente, deber&aacute; notificar a la otra por escrito, en el domicilio se&ntilde;alado en el presente contrato en el plazo de cuarenta y ocho (48) horas de iniciado el hecho, haciendo conocer cuando corresponda el tiempo estimable de duraci&oacute;n si fuera posible.</em></p>
        <p><em><strong>CLAUSULA D&Eacute;CIMA TERCERA: (ACEPTACI&Oacute;N)</strong></em></p>
        <p><em>En se&ntilde;al de aceptaci&oacute;n y estricto cumplimiento firman el presente Contrato en tres ejemplares de un mismo tenor y validez, el {{ setting('firma-autorizada.name') }}</em><em><strong>, {{ setting('firma-autorizada.job') }} </strong></em><em><strong> </strong></em><em>y por otra parte {{ $contract->person->gender == 'masculino' ? 'el Sr.' : 'la Sra.' }} </em><em>{{ $contract->person->first_name }} {{ $contract->person->last_name }}, en calidad de CONTRATADO.</em></p>
        <p style="text-align: right;">Sant&iacute;sima Trinidad, {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</p>
        <table width="100%" style="text-align: center; margin-top: 80px;">
            <tr>
                <td style="width: 50%">
                    ....................................................... <br>
                    {{ setting('firma-autorizada.name') }} <br>
                    <b>{{ Str::upper(setting('firma-autorizada.job')) }}</b>
                </td>
                <td style="width: 50%">
                    ....................................................... <br>
                    {{ $contract->person->gender == 'masculino' ? 'Sr.' : 'Sra.' }} </em><em>{{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                    <b>CONTRATADO</b>
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