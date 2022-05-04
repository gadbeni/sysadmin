@extends('layouts.template-print-legal')

@section('page_title', 'Contrato Personal Eventual')

@php
    $signature = \App\Models\Signature::where('direccion_administrativa_id', $contract->direccion_administrativa_id)->where('deleted_at', NULL)->first();
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
        <h2 class="text-center" style="font-size: 18px">CONTRATO DE PRESTACI&Oacute;N DE SERVICIOS PARA PERSONAL EVENTUAL <br> <small>GAD-BENI-C.E-{{ $code }}</small></h2>
        <p>&nbsp;</p>
        <p><em>Conste por el presente contrato de prestaci&oacute;n de servicios&nbsp;</em><strong><em>de Personal Eventual</em></strong><em>&nbsp;celebrado de conformidad a las siguientes cl&aacute;usulas y condiciones:</em></p>
        <p><strong><em>CL&Aacute;USULA PRIMERA.-&nbsp;</em></strong><strong><em>(PARTES).-</em></strong></p>
        <p><em>1.-&nbsp;</em><strong><em>EL GOBIERNO AUT&Oacute;NOMO DEPARTAMENTAL DE BENI</em></strong><em>, con domicilio&nbsp;ubicado en la Plaza Principal Mcal. Jos&eacute; Ballivi&aacute;n representado&nbsp;legalmente para este acto por la/el&nbsp;</em><strong><em>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}, con C.I {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }},&nbsp;</em></strong><em>en su calidad de&nbsp;</em><strong><em>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} GAD-BENI,</em></strong><em>&nbsp;designado mediante</em> Resoluci&oacute;n de Gobernaci&oacute;n {{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}; as&iacute; mismo, mediante Resoluci&oacute;n Administrativa de Gobernaci&oacute;n No. 08/2021 del 11 de mayo del 2021, el se&ntilde;or Gobernador del Gobierno Aut&oacute;nomo del Beni, Dr. Jos&eacute; Alejandro Unzueta Shiriqui, lo designa como Responsable de los Procesos de Contrataci&oacute;n Apoyo Nacional a la Producci&oacute;n y Empleo RPA, en las modalidades&nbsp; de Contrataciones Menor, Apoyo Nacional a la Producci&oacute;n y Empleo (ANPE)<em>, que en adelante se denominar&aacute; la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>.</em></p>
        <p><em>2. {{ $contract->person->gender == 'masculino' ? 'El señor' : 'La señora' }} </em><strong><em>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</em></strong><em> con C.I. <strong><em>{{ $contract->person->ci }}</em></strong>; con domicilio en {{ $contract->person->address }}, mayor de edad h&aacute;bil en toda forma de derecho que en adelante se denominar&aacute; el&nbsp;</em><strong><em>CONTRATADO</em></strong><em>.</em></p>
        <p><em>Quienes celebran el presente CONTRATO ADMINISTRATIVO, de acuerdo a los t&eacute;rminos y condiciones siguientes:</em></p>
        <p><strong><em>CL&Aacute;USULA SEGUNDA</em></strong><strong><em>.-</em></strong><em>&nbsp;</em><strong><em>(ANTECEDENTES).-&nbsp;</em></strong><em>La Ley No 1413 del Presupuesto General del Estado Gesti&oacute;n 2022 de 17 de diciembre de 2021, determina en su Disposici&oacute;n Final Segunda, inciso b). la vigencia entre otros de la Ley del Presupuesto General del Estado 2010, aprobado en el marco del art&iacute;culo 158 numeral 11 de la Constituci&oacute;n Pol&iacute;tica del Estado, que en su art. 22 (Ley de presupuesto general del Estado 2010)determina; "La remuneraci&oacute;n del Personal Eventual debe establecerse considerando las funciones y la escala salarial aprobada de la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>, para la cual no se requiere ning&uacute;n instrumento legal adicional".</em></p>
        <p><em>Por su parte el art&iacute;culo 13 del D.S. 4646 de 29 de diciembre de 2021, se&ntilde;ala (Nivel de remuneraci&oacute;n del personal Eventual)</em></p>
        <p><em>I. La definici&oacute;n de la remuneraci&oacute;n del personal eventual, debe estar establecida en funci&oacute;n a la escala salarial, para lo cual, las unidades administrativas de cada entidad, elaborar&aacute;n el cuadro de equivalencia de funciones que ser&aacute; avalado por la Unidad Jur&iacute;dica y con Visto Bueno (Vo.Bo.) de la MAE.</em></p>
        <p><em>II. Las Entidades Territoriales Aut&oacute;nomas y universidades p&uacute;blicas podr&aacute;n contratar personal eventual para funciones administrativas, utilizando los niveles de sus respectivas escalas salariales.</em></p>
        <p><em>El Clasificador Presupuestario de la presente gesti&oacute;n, aprobado mediante Resoluci&oacute;n Ministerial No. 268 de 28 de julio del 2021, en el grupo 12000, Empleados No Permanentes, se&ntilde;ala que son gastos para remunerar los servicios prestados y otros beneficios a personas sujetas a contrato en forma transitoria o eventual, para MISIONES ESPEC&Iacute;FICAS, PROGRAMAS Y PROYECTOS DE INVERSI&Oacute;N, considerando para el efecto, la equivalencia de funciones y la escala salarial de acuerdo a la normativa vigente.</em></p>
        <p><em>Al respecto en funci&oacute;n a la verificaci&oacute;n de presupuesto, equivalencia de funciones y requisitos exigidos para el cargo, se instruye la contrataci&oacute;n del personal eventual referido.</em></p>
        <p><strong><em>CL&Aacute;USULA TERCERA</em></strong><strong><em>.-</em></strong><em>&nbsp;</em><strong><em>(BASE LEGAL).-</em></strong></p>
        <ul>
        <li>
        <p><em>Constituci&oacute;n Pol&iacute;tica del Estado, de fecha 07 de febrero de 2009, en todo lo que sea aplicable.</em></p>
        </li>
        <li>
        <p><em>La Ley No 1178 Administraci&oacute;n y Control Gubernamentales de 20 de julio de 1990.</em></p>
        </li>
        <li>
        <p><em>Ley N&deg; 2027 del Estatuto del Funcionario P&uacute;blico</em></p>
        </li>
        <li>
        <p><em>Ley N&deg; 1413 de 17 de diciembre de 2021, Presupuesto General del Estado - Gesti&oacute;n 2022.</em></p>
        </li>
        <li>
        <p><em>Decreto Supremo No 4646 de 29 de diciembre de 2021, art&iacute;culo 13.</em></p>
        </li>
        <li>
        <p><em>Decreto Supremo N&deg; 26115, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</em></p>
        </li>
        <li>
        <p><em>Decreto Supremo N&deg; 23318-A de 03 de noviembre de 1992, reglamento de la responsabilidad por la Funci&oacute;n P&uacute;blica, modificado por el D.S. 26237 de 29 de junio de 2001.</em></p>
        </li>
        <li>
        <p><em>Reglamento Interno de Personal del Servicio Departamental de Salud SEDES BENI.</em></p>
        </li>
        <li>
        <p><em>Manual de funciones, aprobado mediante Resoluci&oacute;n Administrativa No. 160/2019 del 31 de diciembre de 2019 y dem&aacute;s normas internas de la instituci&oacute;n.</em></p>
        </li>
        </ul>
        <p><em>En consecuencia, la naturaleza del presente contrato es exclusivamente administrativo y no reconoce relaci&oacute;n laboral de dependencia alguna respecto de la Ley General del Trabajo ni el Decreto Reglamentario 224 de fecha 23 de agosto de 1943; por lo que no reconoce ning&uacute;n beneficio adicional derivado de &eacute;ste contrato bajo cualquier denominaci&oacute;n, salvo el derecho al pago de AGUINALDO de acuerdo a las disposiciones que emita el Ministerio de Econom&iacute;a y Finanzas P&uacute;blicas y la reglamentaci&oacute;n emitida por la entidad competente.</em></p>
        <p><strong><em>CL&Aacute;USULA CUARTA</em></strong><strong><em>.-</em></strong><em>&nbsp;</em><strong><em>(DOCUMENTOS INTEGRANTES).-</em></strong></p>
        <p><em>Son documentos integrantes del presente contrato:</em></p>
        <p><em>a) Misi&oacute;n espec&iacute;fica</em></p>
        <p><em>b) Curr&iacute;culum Vitae del&nbsp;</em><strong><em>CONTRATADO</em></strong></p>

        <div class="saltopagina"></div>
        <div class="pt"></div>

        <p><strong><em>CL&Aacute;USULA QUINTA</em></strong><strong><em>.-</em></strong><em>&nbsp;</em><strong><em>(OBJETO y CAUSA).-</em></strong></p>
        <p><em>El objeto y causa del presente contrato es la prestaci&oacute;n de servicios de un&nbsp; PERSONAL EVENTUAL por parte del (A) </em><strong><em>CONTRATADO (A), </em></strong><em>para desempe&ntilde;ar la funci&oacute;n de&nbsp;</em><strong><em>{{ Str::upper($contract->cargo->Descripcion) }}</em></strong><em>, solicitado por&hellip;&hellip;.., bajo dependencias del Servicio Departamental de Salud </em><strong><em>SEDES BENI, </em></strong>en el marco de las cl&aacute;usulas contenidas en el presente contrato y Manual de Funciones de la instituci&oacute;n; nombramiento que a mayor abundamiento.</p>
        <p>En cumplimiento de sus fines y objetivos de contratar Recursos Humanos para la oficina central, as&iacute; como para las 9 Redes de Salud, las cuales se hallan insertas en el POA para la gesti&oacute;n 2022 y dentro de la Categor&iacute;a Program&aacute;tica {{ $contract->program->programatic_category }}<em>, Programadas en la Partida Presupuestaria habilitada 12100 PERSONAL EVENTUAL, con fuente de financiamiento 20-220.</em></p>
        <p><strong><em>CL&Aacute;USULA SEXTA</em></strong><strong><em>.-</em></strong><em>&nbsp;</em><strong><em>(CONDICIONES).-</em></strong></p>
        <p><em>6.1. FUNCIONES DEL&nbsp;</em><strong><em>CONTRATADO</em></strong><em>: Deber&aacute; cumplir las siguientes funciones que se establecen de forma enunciativa y no limitativa:</em></p>
        <p><strong><em>FUNCIONES GENERALES:</em></strong></p>
        <ul>
        <li>
        <p><em>Elaborar y verificar la informaci&oacute;n del flujo de caja a trav&eacute;s de operaciones efectivas.</em></p>
        </li>
        <li>
        <p><em>Verificar y controlar la recaudaci&oacute;n de recursos relativos a los dep&oacute;sitos por venta de servicios en las tres cajas</em></p>
        </li>
        <li>
        <p><em>Controlar y verificar el gasto de las diferentes donaciones, transferencias del Sector p&uacute;blico u otras Instituciones no gubernamentales.</em></p>
        </li>
        <li>
        <p><em>Programar el flujo financiero de un periodo determinado de tiempo, con el objeto de compatibilizar los recursos disponibles y prever el cumplimiento de las obligaciones de la Instituci&oacute;n con terceros.</em></p>
        </li>
        <li>
        <p><em>Realiza la ejecuci&oacute;n del gasto mediante la emisi&oacute;n de cheques previa aprobaci&oacute;n presupuestaria y verificaci&oacute;n efectiva de los recursos de Cooperaci&oacute;n internacional a la Instituci&oacute;n.</em></p>
        </li>
        <li>
        <p><em>Elaborar informes mensuales, trimestrales, semestrales, y anuales tanto de ingresos, gastos por fondos propios y gastos ejecutados por las diferentes cuentas de la Instituci&oacute;n.</em></p>
        </li>
        <li>
        <p><em>Elaborar conciliaciones bancarias mensuales por cuenta corriente.</em></p>
        </li>
        <li>
        <p><em>Revisar, registrar y foliar comprobantes de egreso de las diferentes cuentas del SEDES</em></p>
        </li>
        <li>
        <p><em>Desglosar y entregar de los comprobantes y otros a las &aacute;reas correspondientes.</em></p>
        </li>
        <li>
        <p><em>Registrar documentaci&oacute;n tanto interna como externa (Recibida y Enviada)</em></p>
        </li>
        <li>
        <p><em>Registrar ingresos y gastos en la Libreta Bancaria</em></p>
        </li>
        <li>
        <p><em>Supervisar y pagar gastos operativos en las actividades como ser talleres, cursos y otras actividades realizadas por las diferentes Unidades, &Aacute;reas, Coordinaciones de Red y dem&aacute;s dependientes del SEDES Beni.</em></p>
        </li>
        <li>
        <p><em>Actualizar los Aranceles ante la Gobernaci&oacute;n.</em></p>
        </li>
        <li>
        <p><em>Conciliar saldos del Fondo Social.</em></p>
        </li>
        <li>
        <p><em>Conciliar peri&oacute;dicamente saldos con las Unidades de Contabilidad y Presupuestos</em></p>
        </li>
        <li>
        <p><em>Elaborar el POA del &aacute;rea, seguimiento y control de forma mensual, trimestral e informes requeridos por la Jefatura de Unidad.</em></p>
        </li>
        <li>
        <p><em>Realizar otras funciones que asigne su inmediato superior, acorde a la naturaleza del &Aacute;rea.</em></p>
        </li>
        </ul>
        <p><strong><em>FUNCIONES O MISIONES ESPEC&Iacute;FICAS Y RESPONSABILIDADES:</em></strong></p>
        <ul>
        <li>
        <p><em>Confidencialidad de la informaci&oacute;n y documentaci&oacute;n del &aacute;rea de trabajo bajo su custodia, conocimiento y otros.</em></p>
        </li>
        <li>
        <p><em>Presentar informes mensuales, trimestrales o de la manera que su superior inmediato solicite seg&uacute;n los programas de evaluaci&oacute;n y cumplimiento de objetivos de la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>&nbsp;en formato impreso y digital.</em></p>
        </li>
        <li>
        <p><em>Manejar en forma ordenada, sistematizada y archivada toda la documentaci&oacute;n bajo su cargo.</em></p>
        </li>
        <li>
        <p><em>Participar en otras funciones delegadas</em></p>
        </li>
        </ul>
        <p><strong><em>6.2. DEPENDENCIA Y CONTROLES</em></strong></p>
        <p><em>Para el cumplimiento de sus funciones y de acuerdo a las responsabilidades del&nbsp;</em><strong><em>CONTRATADO</em></strong><em>&nbsp;depende jer&aacute;rquicamente del inmediato superior y la MAE, que establecer&aacute;n adicionalmente otras funciones y &oacute;rdenes de acuerdo al objeto del presente contrato. Tambi&eacute;n podr&aacute; ser sujeto a movilidad funcional seg&uacute;n la necesidad de la instituci&oacute;n u otras que creyera conveniente.</em></p>
        <p><strong><em>6.3. HORARIO Y DISPONIBILIDAD DEL CONTRATADO</em></strong></p>
        <p><em>El&nbsp;</em><strong><em>CONTRATADO (A), </em></strong>acepta de manera expresa someterse a los horarios de trabajo establecidos por la ENTIDAD, el cual se regir&aacute; a Reglamento Interno de Personal del SEDES BENI.</p>
        <ul>
        <li>
            <p>Hora de Ingreso: <strong><em>7:30, </em></strong>con tolerancia de diez (10) minutos y horario de salida de<strong><em> 13:30</em></strong><em>&nbsp;</em><strong><em>de lunes a viernes</em></strong><em>, en el lugar que le sea asignado, mismo que ser&aacute; controlado por la Jefatura de RRHH del Sedes Beni; horario que ser&aacute; aplicado para el personal administrativo;&nbsp; sin embargo, de acuerdo a necesidades Institucionales se podr&aacute; cambiar el horario manteniendo las 6 Horas laborales, adem&aacute;s el CONTRATADO (a) deber&aacute; prestar servicios fuera de los horarios establecidos, conforme instrucci&oacute;n verbal o escrita que reciba de sus superiores, asimismo, las inasistencias, atrasos, permisos y DESCUENTOS estar&aacute;n en sujeci&oacute;n al Reglamento Interno de Personal del Sedes Beni.</em></p>
            <div class="saltopagina"></div>
            <div class="pt"></div>
        </li>
        <li>
        <p><em>Con referencia al personal Operativo asignado a los Establecimientos de Salud, el turno para el desempe&ntilde;o de funciones ser&aacute; establecido por el Coordinador de la Red de Salud a la que pertenezca el contratado (a), de acuerdo a la necesidad del servicio y roles de turnos designados de cada Red, es decir, TURNO MA&Ntilde;ANA&nbsp; de Hrs. 7:30 a 13:30 y el&nbsp; TURNO TARDE de Hrs. 13:00 hasta las 19:00; en caso de faltas injustificadas o atrasos se proceder&aacute; al descuento correspondiente de su haber mensual conforme al Reglamento de personal.</em></p>
        </li>
        </ul>
        <p><em>El&nbsp;</em><strong><em>CONTRATADO</em></strong><em>&nbsp;(A) declara su plena e inmediata disponibilidad para el desempe&ntilde;o de las funciones para las cuales es contratado; con absoluta dedicaci&oacute;n, &eacute;tica y pro actividad, conducentes al logro de los objetivos de este contrato, no pudiendo realizar actividades que deterioren o menoscaben la imagen de LA INSTITUCI&Oacute;N. En consecuencia, el servicio es de dedicaci&oacute;n exclusiva, no pudiendo prestar servicios o funciones similares y/o iguales a terceros en horarios se&ntilde;alados en el numeral.</em></p>
        <p><strong><em>CL&Aacute;USULA SEPTIMA .-&nbsp;</em></strong><strong><em>(REMUNERACI&Oacute;N ).-</em></strong><em>&nbsp;La&nbsp;</em><strong><em>ENTIDAD</em></strong><em>&nbsp;se obliga a pagar en favor del&nbsp;</em><strong><em>CONTRATADO</em></strong><em>&nbsp;una remuneraci&oacute;n mensual de&nbsp; <b>{{ NumerosEnLetras::convertir($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 'Bolivianos', true) }}</b> &nbsp;por mes vencido, pago que ser&aacute; en efectivo o mediante dep&oacute;sito bancario u otro procedimiento formal, conforme equivalencia de funciones y escala salarial del personal eventual de la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>, monto que ser&aacute; sujeto al descuento por los aportes propios a la AFP y el r&eacute;gimen de seguridad social a corto plazo seg&uacute;n normativa vigente, as&iacute; como lo dispuesto en materia tributaria si correspondiera; el l&iacute;quido pagable final de la remuneraci&oacute;n convenida se establecer&aacute; previa deducci&oacute;n de los aportes y otras cargas definidas.</em></p>
        <p><strong><em>CL&Aacute;USULA OCTAVA.-&nbsp;</em></strong><strong><em>(DURACI&Oacute;N Y CAR&Aacute;CTER DEFINIDO).-</em></strong><em>&nbsp;En el marco legal citado en antecedentes, el presente contrato tendr&aacute; calidad de&nbsp;</em><strong><em>CONTRATO DE PERSONAL EVENTUAL</em></strong><em>, computable a partir&nbsp;</em><strong><em>del 03 de Enero de 2022 hasta el 31 de diciembre de 2022</em></strong><em>.</em></p>
        <p><em>El&nbsp;</em><strong><em>CONTRATADO</em></strong><em>&nbsp;no estar&aacute; sujeto a periodo de prueba y la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>&nbsp;podr&aacute; determinar la finalizaci&oacute;n del contrato, conforme al procedimiento dispuesto en normas complementarias y reglamentarias, si as&iacute; lo considera.</em></p>
        <p><strong><em>CL&Aacute;USULA NOVENA .-&nbsp;</em></strong><strong><em>(CAUSALES PARA RESOLUCI&Oacute;N DEL CONTRATO)</em></strong></p>
        <p><em>El contrato se tendr&aacute; por resuelto por Cumplimiento del mismo, caso en el cual tanto la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>&nbsp;como el&nbsp;</em><strong><em>CONTRATADO (A)</em></strong><em>, dar&aacute;n por terminado el presente Contrato, una vez que ambas partes hayan dado cumplimiento a todas y cada una de las cl&aacute;usulas contenidas en el mismo sin necesidad de comunicaci&oacute;n expresa. No obstante el contrato podr&aacute; resolverse antes de la fecha de conclusi&oacute;n por las siguientes causales, en forma directa y sin necesidad requerimiento Judicial y/o administrativo alguno:</em></p>
        <p><strong><em>1. Por resoluci&oacute;n de Contrato:</em></strong></p>
        <p><em>1.1. A requerimiento de la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>, por causales atribuibles al&nbsp;(a) </em><strong><em>CONTRATADO</em></strong><em> (A) en base a Informe:</em></p>
        <p><strong><em>a)</em></strong><em>&nbsp;Cuando el&nbsp;(a) </em><strong><em>CONTRATADO</em></strong><em>&nbsp;(A) en el desempe&ntilde;o de sus funciones ocasione da&ntilde;os y perjuicios al&nbsp;</em><strong><em>CONTRATANTE</em></strong><em>&nbsp;o a terceros en raz&oacute;n de su cargo.</em></p>
        <p><strong><em>b)</em></strong><em>&nbsp;Cuando el (a) &nbsp;</em><strong><em>CONTRATADO (A)</em></strong><em>, incumpla total o parcialmente los t&eacute;rminos establecidos en el presente contrato, las obligaciones propias del cargo, &oacute;rdenes superiores o demuestre negligencia, falta de inter&eacute;s en el cumplimiento de sus funciones o desarrolle labores que no contribuyan al cumplimiento de los Objetivos del &aacute;rea funcional del cual depende, en este caso el inmediato superior realizar&aacute; la evaluaci&oacute;n correspondiente, mediante la emisi&oacute;n del informe pertinente a la funci&oacute;n que desempe&ntilde;a, en base a los cuales la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>&nbsp;Se reserva de manera unilateral la facultad de resolver el presente contrato.</em></p>
        <p><strong><em>c)</em></strong><em>&nbsp;Inasistencia injustificada de m&aacute;s de tres (3) d&iacute;as h&aacute;biles consecutivos o seis (6) d&iacute;as h&aacute;biles discontinuos en un (1) mes.</em></p>
        <p><strong><em>d)</em></strong><em>&nbsp;Abuso de confianza, robo, hurto debidamente comprobado.</em></p>
        <p><strong><em>e)</em></strong><em>&nbsp;Cuando el (la)&nbsp;</em><strong><em>CONTRATADO</em></strong><em> (A) est&aacute; comprendido dentro de las incompatibilidades o prohibiciones para ejercer la funci&oacute;n p&uacute;blica, por raz&oacute;n de parentesco hasta el cuarto grado de consanguinidad y segundo de afinidad.</em></p>
        <p><strong><em>f)</em></strong><em>&nbsp;Cuando se evidencie que el&nbsp;(la) </em><strong><em>CONTRATADO (A) </em></strong><em>percibe dos remuneraciones en calidad de servidor p&uacute;blico que provengan de recursos p&uacute;blicos, conforme a normas en vigencia o incurre en incompatibilidad por conflicto de intereses seg&uacute;n el formulario respectivo</em></p>
        <p><strong><em>g)</em></strong><em>&nbsp;Por infracci&oacute;n de las normas internas que rigen en la&nbsp;</em><strong><em>ENTIDAD</em></strong><em>&nbsp;(Reglamento Interno de Personal SEDES BENI) y otras causales previstas en la normativa legal aplicable.</em></p>
        <p><em>En todos los casos ser&aacute; suficiente una comunicaci&oacute;n escrita con quince (15) d&iacute;as de anticipaci&oacute;n, mediante nota o memor&aacute;ndum&nbsp;al&nbsp;</em><strong><em>CONTRATADO (A)</em></strong><em>&nbsp;por parte del&nbsp;</em><strong><em>CONTRATANTE</em></strong><em>&nbsp;a trav&eacute;s de la Direcci&oacute;n de Recursos Humanos.</em></p>
        <p><em>Tambi&eacute;n opera la resoluci&oacute;n por voluntad del&nbsp;</em><strong><em>CONTRATADO</em></strong><em>&nbsp;comunicada a la INSTITUCI&Oacute;N para aceptaci&oacute;n de mutuo acuerdo.</em></p>
        <p><strong><em>CL&Aacute;USULA D&Eacute;CIMA: (DERECHOS Y OBLIGACIONES DEL CONTRATADO)</em></strong></p>
        <ul>
        <li>
        <p><em>Cumplir lo dispuesto en la Constituci&oacute;n Pol&iacute;tica del Estado, Leyes, Decretos y Resoluciones Nacionales y Departamentales.</em></p>
        </li>
        <li>
        <p><em>Gozar del Seguro de salud para lo cual se deber&aacute; afiliar a la Caja de Seguro Social donde estuviera registrado el&nbsp;Servicio Departamental de Salud Sedes Beni, debiendo presentar toda la documentaci&oacute;n personal y de sus familiares dependientes si los tuviera para dicha afiliaci&oacute;n.</em></p>
        </li>
        <li>
        <p><em>Cumplir los horarios de ingreso y salida, debiendo incorporarse a su lugar de trabajo inmediatamente despu&eacute;s del marcado de tarjeta o sistema de control de asistencia bajo conminatoria de los descuentos respectivos.</em></p>
        </li>
        <li>
        <p><em>Preservar y cuidar los activos fijos, material, equipos de computaci&oacute;n, documentaci&oacute;n y todo cuanto fuere asignado para el desempe&ntilde;o de funciones, una vez terminado o resuelto el contrato proceder a la respectiva devoluci&oacute;n.</em></p>
        </li>
        <li>
        <p><em>Garantizar y responder por la funci&oacute;n asignada de tal forma que, de ser requerida su presencia f&iacute;sica para cualquier aclaraci&oacute;n posterior a la vigencia del contrato, se obliga a no negar su participaci&oacute;n.</em></p>
        </li>
        <li>
        <p><em>Es su obligaci&oacute;n cumplir y acatar las instrucciones de su inmediato superior, y otras contenidas en los manuales, reglamentos, instructivos, circulares y otros instrumentos normativos de la instituci&oacute;n</em></p>
        </li>
        <li>
        <p><em>Informar cuantas veces sea solicitado o necesario sobre sus actividades laborales y otros a fin de realizar los procesos de evaluaci&oacute;n correspondientes a sus funciones.</em></p>
        </li>
        <li>
            <p><em>Desarrollar sus funciones, atribuciones y deberes administrativos con puntualidad, celeridad, econom&iacute;a, eficiencia y probidad.</em></p>
            <div class="saltopagina"></div>
            <div class="pt"></div>
        </li>
        <li>
        <p><em>Cumplir con la jornada laboral</em></p>
        </li>
        <li>
        <p><em>Tambi&eacute;n se hallan entre sus deberes y obligaciones el cumplimiento del Reglamento Interno de Personal, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato</em></p>
        </li>
        <li>
        <p><em>Asumir toda la responsabilidad por el trabajo encomendado, oblig&aacute;ndose a la preservaci&oacute;n del material, documentos, equipos, activos y/o maquinaria que se encuentra a su cargo y a guardar reserva de informaci&oacute;n confidencial que sea de su conocimiento. El resultado de sus actividades y el contenido de ellas en cualquier medio (informe, documentos, discos magn&eacute;ticos, etc.) pertenecen exclusivamente al CONTRATANTE, la divulgaci&oacute;n de dicha informaci&oacute;n sin autorizaci&oacute;n superior, escrita y expresa implicar&aacute; la violaci&oacute;n a los principios de confiablidad e idoneidad, con las consecuencias previstas en el contrato, la Ley N&ordm; 1178 de Administraci&oacute;n y Control Gubernamentales y la reparaci&oacute;n del da&ntilde;o civil que ocasionare.</em></p>
        </li>
        </ul>
        <p><strong><em>CL&Aacute;USULA D&Eacute;CIMA PRIMERA: (OBLIGACIONES DE LA ENTIDAD)</em></strong></p>
        <p><em>La ENTIDAD se obliga a:</em></p>
        <ul>
        <li>
        <p><em>A brindar los beneficios que la instituci&oacute;n establezca en base al contrato de prestaci&oacute;n de servicios incluido el pago de la remuneraci&oacute;n mensual por el servicio realizado.</em></p>
        </li>
        <li>
        <p><em>Al oportuno dep&oacute;sito de las retenciones realizadas por concepto de aporte al Seguro Social de Largo Plazo, as&iacute; como las que correspondan a los aportes patronales, AFP, entre otros.</em></p>
        </li>
        <li>
        <p><em>El pago oportuno de los aportes patronales a la Seguridad Social de Corto Plazo.</em></p>
        </li>
        </ul>
        <p><strong><em>CL&Aacute;USULA D&Eacute;CIMA SEGUNDA: (ACEPTACI&Oacute;N)</em></strong></p>
        <p><em>En se&ntilde;al de aceptaci&oacute;n y estricto cumplimiento firman el presente Contrato en tres ejemplares de un mismo tenor y validez, la/el&nbsp;</em><strong><em>{{ $signature ? $signature->name : setting('firma-autorizada.name') }}</em></strong><em>, en su calidad de&nbsp;</em><strong><em>{{ $signature ? $signature->job : setting('firma-autorizada.job') }} GAD-BENI&nbsp;</em></strong><em>y por otra parte {{ $contract->person->gender == 'masculino' ? 'del señor' : 'de la señora' }} &nbsp;</em><strong><em>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</em></strong><em>, en calidad de&nbsp;</em><strong><em>CONTRATADO (A)</em></strong><em>.</em></p>
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