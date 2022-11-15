@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Personal Eventual')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    if(
        $contract->direccion_administrativa->direcciones_tipo_id != 3 &&
        $contract->direccion_administrativa->direcciones_tipo_id != 4 &&
        $contract->direccion_administrativa_id != 5 &&
        $contract->direccion_administrativa_id != 48 &&
        $contract->direccion_administrativa_id != 53
    ){
        $signature = null;
    }
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Personal eventual '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' al '.date('d', strtotime($contract->finish)).' de '.$months[intval(date('m', strtotime($contract->finish)))].' de '.date('Y', strtotime($contract->finish)).' con un sueldo de '.number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.').' Bs.'); !!}
    </div>
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 18px">CONTRATO ADMINISTRATIVO DE PERSONAL EVENTUAL <br> <small>GAD-BENI-C.E-{{ $code }}</small> </h2>
        <p><em>Conste por el presente contrato de prestaci&oacute;n de servicios </em><em><strong>de Personal Eventual</strong></em><em> celebrado de conformidad a las siguientes cláusulas y condiciones:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA PRIMERA .- </strong></span></em><em><strong>(PARTES ).-</strong></em><em> </em></p>
        <p><em>1.- </em><em><strong>EL GOBIERNO AUT&Oacute;NOMO DEPARTAMENTAL DE BENI</strong></em><em>, con domicilio </em><em>ubicado en la Plaza Principal Mcal. Jos&eacute; Ballivi&aacute;n representado</em><em> legalmente para este acto por la/el </em><em><strong> {{ $signature ? $signature->name : setting('firma-autorizada.name') }} con C.I {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}, </strong>en su calidad de </em><em><strong>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} GAD-BENI</strong></em><em><strong>,</strong></em><em> designado mediante Resolución de </em><em><strong>Gobernaci&oacute;n </strong></em><em><strong>{!! $signature ? $signature->designation : 'N&deg; '.setting('firma-autorizada.designation') !!}</strong>, que en adelante se denominará la <strong>ENTIDAD</strong>. </em></p>
        <p><em>2. {{ $contract->person->gender == 'masculino' ? 'El señor' : 'La señora' }} <b>{{ $contract->person->first_name }} {{ $contract->person->last_name }} </b> con C.I. <b>{{ $contract->person->ci }}</b>; con domicilio en {{ $contract->person->address }}, {!! $contract->person->profession ? 'de profesión <b>'.$contract->person->profession.'</b>, ' : '' !!}</em> <em>mayor de edad h&aacute;bil en toda forma de derecho que en adelante se denominará {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!}.</em></p>
        <p><em>Quienes celebran el presente CONTRATO ADMINISTRATIVO, de acuerdo a los t&eacute;rminos y condiciones siguientes:</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEGUNDA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(ANTECEDENTES).- </strong></em><em>La Ley No 1413 del Presupuesto General del Estado Gesti&oacute;n 2022 de 17 de diciembre de 2021, determina en su Disposici&oacute;n Final Segunda, inciso b). la vigencia entre otros de la Ley del Presupuesto General del Estado 2010, aprobado en el marco del articulo 158 numeral 11 de la Constituci&oacute;n Pol&iacute;tica del Estado, que en su el art. 22 (Ley de presupuesto general del Estado 2010)determina; "La remuneraci&oacute;n del Personal Eventual debe establecerse considerando las funciones y la escala salarial aprobada de la <b>ENTIDAD</b>, para la cual no se requiere ning&uacute;n instrumento legal adicional".</em></p>
        <p><em>Por su parte el art&iacute;culo 13 del D.S. 4646 de 29 de diciembre de 2021, se&ntilde;ala (Nivel de remuneraci&oacute;n del personal Eventual)</em></p>
        <p><em>I. La definici&oacute;n de la remuneraci&oacute;n del personal eventual, debe estar establecida en funci&oacute;n a la escala salarial, para lo cual, las unidades administrativas de cada entidad, elaboraran el cuadro de equivalencia de funciones que ser&aacute; avalado por la Unidad Jur&iacute;dica y con Visto Bueno (Vo.Bo.) de la MAE.</em></p>
        <p><em>II. Las Entidades Territoriales Aut&oacute;nomas y universidades p&uacute;blicas podr&aacute;n contratar personal eventual para funciones administrativas, utilizando los niveles de sus respectivas escalas salariales.</em></p>
        <p><em>El Clasificador Presupuestario de la presente gesti&oacute;n en el grupo 12000, Empleados No Permanentes, se&ntilde;ala que son gastos para remunerar los servicios prestados y otros beneficios a personas sujetas a contrato en forma transitoria o eventual, para MISIONES ESPEC&Iacute;FICAS, PROGRAMAS Y PROYECTOS DE INVERSI&Oacute;N, considerando para el efecto, la equivalencia de funciones y la escala salarial de acuerdo a la normativa vigente.</em></p>
        <p><em>Al respecto en funci&oacute;n a la verificaci&oacute;n de presupuesto, equivalencia de funciones y requisitos exigidos para el cargo, se instruye la contrataci&oacute;n del personal eventual referido.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA TERCERA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(BASE LEGAL).- </strong></em></p>
        <p>
            <ul>
                <li><em>Constituci&oacute;n Pol&iacute;tica del Estado, de fecha 07 de febrero de 2009, en todo lo que sea aplicable.</em></li>
                <li><em>La Ley No 1178 Administraci&oacute;n y Control Gubernamentales de 20 de julio de 1990.</em></li>
                <li><em>Ley N&deg; 2027 del Estatuto del Funcionario P&uacute;blico</em></li>
                <li><em>Decreto Supremo N&deg; 26115, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</em></li>
                <li><em>Ley N&deg; 1413 de 17 de diciembre de 2021, Presupuesto General del Estado - Gesti&oacute;n 2022.</em></li>
                <li><em>Decreto Supremo No 4646 de 29 de diciembre de 2021, art&iacute;culo 13.</em></li>
                <li><em>Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental de Beni, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</em></li>
                <li><em>Decreto supremo Nro. 12/2009, artículo 5, parágrafo II.</em></li>
            </ul>
        </p>
        <p><em>En ese marco {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} no estar&aacute; bajo el r&eacute;gimen de la Ley General del Trabajo ni el Decreto Reglamentario 224 de fecha 23 de agosto de 1943.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA CUARTA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(DOCUMENTOS INTEGRANTES).-</strong></em></p>
        <p><em>Son documentos integrantes del presente contrato:</em></p>
        <p><em>a) Misión específica</em></p>
        <p><em>b) Curr&iacute;culum Vitae {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!}</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA QUINTA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(OBJETO).-</strong></em></p>
        <p><em>La <b>ENTIDAD</b>, contrata los servicios {!! $contract->person->gender == 'masculino' ? '<b>del CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} para desempe&ntilde;ar la funci&oacute;n de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, en dependencias de la/el <b>{{ Str::upper($contract->unidad_administrativa->nombre) }}</b> dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b> con el nivel salarial <b>{{ $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->NumNivel }}</b> con cargo a la partida presupuestaria {{ $contract->program->number }} (Personal Eventual), en los t&eacute;rminos y condiciones que se establecen en este contrato, debiendo coadyuvar y coordinar sus actividades con su inmediato superior y las dem&aacute;s &aacute;reas de la <b>ENTIDAD</b>, donde sean requeridas. El presente contrato bajo ninguna manera podr&aacute; ser motivo de transferencia, subrogaci&oacute;n, delegaci&oacute;n, total o parcialmente.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEXTA</strong></span></em><em><strong>.-</strong></em><em> </em><em><strong>(CONDICIONES).-</strong></em></p>
        <p><em>6.1. FUNCIONES {!! $contract->person->gender == 'masculino' ? 'DEL <b>CONTRATADO</b>' : 'DE LA <b>CONTRATADA</b>' !!}: Deber&aacute; cumplir las siguientes funciones que se establecen de forma enunciativa y no limitativa:</em></p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>
        
        <p><em><strong>FUNCIONES GENERALES:</strong></em></p>
        <p><em>
            {!! $contract->details_work !!}    
        </em></p>
        <p><em><strong>FUNCIONES O MISIONES ESPEC&Iacute;FICAS Y RESPONSABILIDADES:</strong></em></p>
        <ul>
            <li><em>Confidencialidad de la informaci&oacute;n y documentaci&oacute;n del &aacute;rea de trabajo bajo su custodia, conocimiento y otros.</em></li>
            <li><em>Presentar informes mensuales, trimestrales o de la manera que su superior inmediato solicite seg&uacute;n los programas de evaluaci&oacute;n y cumplimiento de objetivos de la <b>ENTIDAD</b> en formato impreso y digital.</em></li>
            <li><em>Manejar en forma ordenada, sistematizada y archivada toda la documentaci&oacute;n bajo su cargo.</em></li>
            <li><em>Participar en otras funciones delegadas</em><em> </em></li>
        </ul>
        <p><em><strong>6.2. DEPENDENCIA Y CONTROLES</strong></em></p>
        <p><em>Para el cumplimiento de sus funciones y de acuerdo a las responsabilidades {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} depende jer&aacute;rquicamente del inmediato superior y la MAE, que establecer&aacute;n adicionalmente otras funciones y &oacute;rdenes de acuerdo al objeto del presente contrato. También podrá ser sujeto a movilidad funcional según la nececidad de la institución u otras que creyera conveniente.</em></p>
        <p><em><strong>6.3. HORARIO Y DISPONIBILIDAD {{ $contract->person->gender == 'masculino' ? 'DEL CONTRATADO' : 'DE LA CONTRATADA' }}</strong></em></p>
        <p><em>{!! $contract->person->gender == 'masculino' ? 'El <b>CONTRATADO</b>' : 'La <b>CONTRATADA</b>' !!} cumplir&aacute; con el horario de trabajo de 8:00 Hrs diarias y que por razones a la latente emergencia sanitaria y por disposici&oacute;n del Ministerio de Trabajo ser&aacute; de </em><em><strong>HORARIO CONTINUO DE HRS {{ $contract->direccion_administrativa_id == 32 ? '7:30 a 13:30' : '8:00 a 16:00' }}</strong></em><em> </em><em><strong>de lunes a viernes</strong></em><em>, en el lugar que le sea asignado , sin embargo, de acuerdo a necesidades Institucionales se podr&aacute; cambiar el horario manteniendo las 8 Horas laborales, adem&aacute;s el servidor deber&aacute; prestar servicios fuera de los horarios establecidos, conforme instrucci&oacute;n verbal o escrita que reciba de sus superiores. </em><em>, asimismo las inasistencias, atrasos, permisos y DESCUENTOS estar&aacute;n en sujeci&oacute;n al Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental del Beni.</em></p>
        <p><em>{!! $contract->person->gender == 'masculino' ? 'El <b>CONTRATADO</b>' : 'La <b>CONTRATADA</b>' !!} declara su plena e inmediata disponibilidad para el desempe&ntilde;o de las funciones para las cuales es {{ $contract->person->gender == 'masculino' ? 'contratado' : 'contratada' }}; con absoluta dedicaci&oacute;n, &eacute;tica y pro actividad, conducentes al logro de los objetivos de este contrato, no pudiendo realizar actividades que deterioren o menoscaben la imagen de LA INSTITUCION. En consecuencia, el servicio es de dedicaci&oacute;n exclusiva, no pudiendo prestar servicios o funciones similares y/o iguales a terceros en horarios se&ntilde;alados en el numeral.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA SEPTIMA .- </strong></span></em><em><strong>(REMUNERACION ).-</strong></em><em> La <b>ENTIDAD</b> se obliga a pagar en favor {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} una remuneraci&oacute;n mensual de <b>{{ NumerosEnLetras::convertir($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 'Bolivianos', true) }}</b> por mes vencido, pago que ser&aacute; en efectivo o mediante dep&oacute;sito bancario u otro procedimiento formal, conforme equivalencia de funciones y escala salarial del personal eventual de la <b>ENTIDAD</b>, monto que ser&aacute; sujeto al descuento por los aportes propios a la AFP y el r&eacute;gimen de seguridad social a corto plazo seg&uacute;n normativa vigente, as&iacute; como lo dispuesto en materia tributaria si correspondiera; el l&iacute;quido pagable final de la remuneraci&oacute;n convenida se establecer&aacute; previa deducci&oacute;n de los aportes y otras cargas definidas</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA OCTAVA .- </strong></span></em><em><strong>(DURACION Y CAR&Aacute;CTER DEFINIDO ).-</strong></em><em> En el marco legal citado en antecedentes, el presente contrato tendr&aacute; calidad de <b>CONTRATO DE PERSONAL EVENTUAL</b>, computable a partir <b>del {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }} hasta el {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}</b>.</em></p>
        <p><em>{!! $contract->person->gender == 'masculino' ? 'El <b>CONTRATADO</b>' : 'La <b>CONTRATADA</b>' !!} no estar&aacute; sujeto a periodo de prueba y la <b>ENTIDAD</b> podr&aacute; determinar la finalizaci&oacute;n del contrato, conforme al procedimiento dispuesto en normas complementarias y reglamentarias, si as&iacute; lo considera.</em></p>
        <p><em><span style="text-decoration: underline;"><strong>CLÁUSULA NOVENA .- </strong></span></em><em><strong>(CAUSALES PARA RESOLUCION DEL CONTRATO)</strong></em></p>
        <p><em>El contrato se tendr&aacute; por resuelto por Cumplimiento del mismo, caso en el cual tanto la <b>ENTIDAD</b> como {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!}, dar&aacute;n por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas y cada una de las cl&aacute;usulas contenidas en el mismo sin necesidad de comunicaci&oacute;n expresa. No obstante el contrato podr&aacute; resolverse antes de la fecha de conclusi&oacute;n por las siguientes causales, en forma directa y sin necesidad requerimiento Judicial y/o administrativo alguno:</em></p>
        <p><em><strong>1. Por resoluci&oacute;n de Contrato:</strong></em></p>
        <p><em>1.1. A requerimiento de la <b>GOBERNACI&Oacute;N</b>, por causales atribuibles {!! $contract->person->gender == 'masculino' ? 'al <b>CONTRATADO</b>' : 'a la <b>CONTRATADA</b>' !!} en base a Informe:</em></p>
        <p><em><strong>a)</strong></em><em> </em><em>Cuando {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} en el desempe&ntilde;o de sus funciones ocasione da&ntilde;os y perjuicios al </em><em><strong>CONTRATANTE</strong></em><em> o a terceros en raz&oacute;n de su cargo.</em></p>
        <p><em><strong>b)</strong></em><em> Cuando {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!}, incumpla total o parcialmente los t&eacute;rminos establecidos en el presente contrato, las obligaciones propias del cargo, &oacute;rdenes superiores o demuestre negligencia, falta de inter&eacute;s en el cumplimiento de sus funciones o desarrolle labores que no contribuyan al cumplimiento de los Objetivos del &aacute;rea funcional del cual depende, en este caso el inmediato superior realizar&aacute; la evaluaci&oacute;n correspondiente, mediante la emisi&oacute;n del informe pertinente a la funci&oacute;n que desempe&ntilde;a, en base a los cuales la <b>ENTIDAD</b> Se reserva de manera unilateral la facultad de resolver el presente contrato.</em></p>
        <p><em><strong>c)</strong></em><em> Inasistencia injustificada de m&aacute;s de tres (3) d&iacute;as h&aacute;biles consecutivos o seis (6) d&iacute;as h&aacute;biles discontinuos en un (1) mes. </em></p>
        <p><em><strong>d)</strong></em><em> Abuso de confianza, robo, hurto debidamente comprobado.</em></p>
        <p><em><strong>e)</strong></em><em> Cuando {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} est&aacute; comprendido dentro de las incompatibilidades o prohibiciones para ejercer la funci&oacute;n p&uacute;blica, por raz&oacute;n de parentesco hasta el cuarto grado de consanguineidad y segundo de afinidad.</em></p>
        <p><em><strong>f)</strong></em><em> Cuando se evidencie que {!! $contract->person->gender == 'masculino' ? 'el <b>CONTRATADO</b>' : 'la <b>CONTRATADA</b>' !!} percibe dos remuneraciones en calidad de servidor p&uacute;blico que provengan de recursos p&uacute;blicos, conforme a normas en vigencia o incurre en incompatibilidad por conflicto de intereses seg&uacute;n el formulario respectivo</em></p>
        <p><em><strong>g)</strong></em><em> Por infracci&oacute;n de las normas internas que rigen en la <b>ENTIDAD</b> (Reglamento Interno de Personal - RIP) y otras causales previstas en la normativa legal aplicable.</em></p>
        <p><em>En todos los casos ser&aacute; suficiente una comunicaci&oacute;n escrita con quince (15) d&iacute;as de anticipaci&oacute;n, mediante nota o memor&aacute;ndum </em><em>{!! $contract->person->gender == 'masculino' ? 'al <b>CONTRATADO</b>' : 'a la <b>CONTRATADA</b>' !!}</em><em> por parte del </em><em><strong>CONTRATANTE</strong></em><em> a trav&eacute;s de la Direcci&oacute;n de Recursos Humanos.</em></p>
        <p><em>También opera la resolución por voluntad {!! $contract->person->gender == 'masculino' ? 'del <b>CONTRATADO</b>' : 'de la <b>CONTRATADA</b>' !!} comunicada a la INSTITUCIÓN para aceptación de mutuo acuerdo.</em></p>
        
        <div class="saltopagina"></div>
        <div class="pt"></div>
        
        <p><em><strong>CLÁUSULA D&Eacute;CIMA: (DERECHOS Y OBLIGACIONES {{ $contract->person->gender == 'masculino' ? 'DEL CONTRATADO' : 'DE LA CONTRATADA' }})</strong></em></p>
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
        <p><em><strong>CLÁUSULA D&Eacute;CIMA PRIMERA: (OBLIGACIONES DE LA ENTIDAD.)</strong></em></p>
        <p><em>La ENTIDAD se obliga a:</em></p>
        <ul>
            <li><em>A brindar los beneficios que la instituci&oacute;n establezca en base al contrato de prestaci&oacute;n de servicios incluido el pago de la remuneraci&oacute;n mensual por el servicio realizado.</em></li>
            <li><em>Al oportuno dep&oacute;sito de las retenciones realizadas por concepto de aporte al Seguro Social de Largo Plazo, as&iacute; como las que correspondan a los aportes patronales, AFP, entre otros.</em></li>
            <li><em>Al pago oportuno de los aportes patronales a la Seguridad Social de Corto Plazo.</em></li>
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
                    {{ $contract->person->gender == 'masculino' ? 'Sr.' : 'Sra.' }} </em><em>{{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
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