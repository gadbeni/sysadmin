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
    @if ($contract->files->count() > 0)
        <div class="content">
            {!! $contract->files[0]->text !!}
        </div>
    @else
        <div class="content">
            <div class="page-head">
                <h3>CONTRATO DE PRESTACIÓN DE SERVICIOS PARA PERSONAL EVENTUAL<br>GAD-BENI-C.E-{{ $code }}</h3>
            </div>
            <p>Conste por el presente Contrato de Prestaci&oacute;n de Servicio de Personal EVENTUAL celebrado de conformidad a las siguientes cl&aacute;usulas y condiciones</p>
            <p><strong>CL&Aacute;USULA PRIMERA - (PARTES)</strong></p>
            <ol>
                <li>
                    <p>EL GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI, con domicilio ubicado en {{ $contract->direccion_administrativa->direccion ?? 'el edificio de Gobernación en Acera Sud de la Plaza Mariscal José Ballivián' }}, representado legalmente para este acto por el ciudadano(a) {{ $signature->name }} con c&eacute;dula de identidad No. {{ $signature->ci }}, electo como {{ $signature->job }}, conforme se acredita por "Acta N&deg; 001/2020-2022 de Posesi&oacute;n y Juramento del Gobernador, Subgobernadoras y Subgobernadores, Corregidoras y Corregidores del Departamento del Beni, electos para el periodo Constitucional 2021-2026" emanado de la Asamblea Legislativa Departamental del Beni, en estricta conformidad con la Ley Departamental del Beni N&ordm; 001/2010 de fecha 09/Junio/2010, y con domicilio legal situado sobre la calle Benito Ruiz N&ordm; 146 Zona Pompeya, quien en adelante se denominar&aacute; la "<strong>ENTIDAD".</strong></p>
                </li>
            </ol>
            <ol start="2">
                <li>
                    <p>{{ $contract->person->gender == 'masculino' ? 'El señor' : 'La señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}, mayor de edad, h&aacute;bil por Ley, con C&eacute;dula de Identidad {{ $contract->person->ci }}, con domicilio ubicado en {{ $contract->person->address }}, que en lo sucesivo se denominar&aacute; el/la <strong>CONTRATADO (A).</strong></p>
                </li>
            </ol>
            <p><strong>CL&Aacute;USULA SEGUNDA. - (ANTECEDENTES)</strong>.</p>
            <p>La Ley N&ordm; 1413 del Presupuesto General del Estado Gesti&oacute;n 2022, de 17 de diciembre del 2021, determina en su Disposici&oacute;n Final Segunda, inciso b). la vigencia entre otros de la Ley del Presupuesto General del Estado 2010, aprobado en el del art&iacute;culo 158 numeral 11 de la Constituci&oacute;n Pol&iacute;tica del Estado, que en su el art 22 (Ley de Presupuesto General del Estado 2010) determina: "La remuneraci&oacute;n del Personal Eventual debe establecerse considerando las funciones y la escala salarial aprobada de la ENTIDAD, para la cual no se requiere ning&uacute;n instrumento legal adicional".</p>
            <p>Por su parte el art&iacute;culo 13 del D.S. 4646 de 29 de diciembre de 2021, se&ntilde;ala (Nivel de remuneraci&oacute;n del personal eventual).&nbsp;</p>
            <ol>
                <li style="list-style-type: upper-roman;">
                <p>La definici&oacute;n de la remuneraci&oacute;n del personal eventual, debe estar establecida en funci&oacute;n a la escala salarial, para lo cual, las unidades administrativas de cada entidad, elaborar&aacute;n el cuadro de equivalencia de funciones que ser&aacute; avalado por la Unidad Jur&iacute;dica y con Visto Bueno (Vo.Bo.) de la MAE.</p>
                </li>
            </ol>
            <ol start="2">
                <li style="list-style-type: upper-roman;">
                    <p>Las Entidades Territoriales Aut&oacute;nomas y Universidades P&uacute;blicas podr&aacute;n contratar personal eventual para funciones administrativas, utilizando los niveles de sus respectivas escalas salariales.</p>
                </li>
            </ol>
            <p>El Clasificador Presupuestario de la presente gesti&oacute;n en el grupo 10000 (servicios personales), partida presupuestarias 12100 (personal eventual), se&ntilde;ala que son gastos para remunerar los servicios prestados y otros beneficios a personas sujetas a contrato en forma transitoria o eventual, para MISIONES ESPEC&Iacute;FICAS, PROGRAMAS Y PROYECTOS DE INVERSIÓN, considerando para el efecto, la equivalencia de funciones y la escala salarial de acuerdo a la normativa vigente.</p>
            <p>Al respecto en funci&oacute;n a la verificaci&oacute;n de presupuesto, equivalencia de funciones y requisitos exigidos para el cargo, se instruye la contrataci&oacute;n del personal eventual referido.</p>
            <p>La Ley Departamental del Beni N&ordm; 001/2010 de fecha 09/06/2010, en su Art. 1 inciso II) establece y define las atribuciones transitorias de Subgobernadores (as) y Corregidores (as) electos. En su art. 4 par&aacute;grafo 1. Atribuciones de los Subgobernadores (as) establece: Inciso b) Designar y remover el personal de la Sub Gobernaci&oacute;n, en el Par&aacute;grafo II determina las atribuciones de los Corregidores (as): Inciso b) Designar y remover el personal del Corregimiento.</p>
            <p>El Decreto Departamental N&ordm; 09/2011 de fecha 05/09/2011, en su art&iacute;culo 1ro. reconoce el car&aacute;cter descentralizado de las Sub Gobernaciones y Corregimientos, para que ejecuten bajo su responsabilidad las contrataciones, manejos de gestiones pertinentes ante las entidades financieras, tributos, seguro social obligatorio y otras que se consideren necesarias y que est&eacute;n contempladas en la Ley Vigente</p>
            <p>La Ley Departamental N&deg; 99 de fecha 08/05/2020 "LEY DE LA ORGANIZACIÓN B&Aacute;SICA DEL ÓRGANO EJECUTIVO DEL GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI", en su art&iacute;culo 4to. (Estructura Organizacional y Administrativa del Órgano Ejecutivo) determina: 1. El Órgano Ejecutivo Departamental tendr&aacute;, como m&iacute;nimo, la siguiente estructura organizacional y administrativa: 5.- NIVEL DESCONCENTRADO: 5.1. Servicio Departamental Desconcentrados, Direcciones Desconcentradas, 5.3.-Sub Gobernaciones, 5.4 Corregimientos.</p>
            <p><strong>CLAUSULA TERCERA (BASE LEGAL)</strong></p>
            <ul>
                <li>
                    <p>Constituci&oacute;n Pol&iacute;tica del Estado, de fecha 07 de febrero de 2009, en todo lo que sea aplicable.</p>
                </li>
                <li>
                    <p>La Ley No 1178 Administraci&oacute;n y Control Gubernamentales de 20 de julio de 1990</p>
                </li>
                <li>
                    <p>Ley N&deg; 2027 del Estatuto del Funcionario P&uacute;blico</p>
                </li>
                <li>
                    <p>Decreto Supremo N&deg; 26115, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica del contrato.&nbsp;</p>
                </li>
                <li>
                    <p>Ley N&deg; 1413 de 17 de diciembre de 2021, Presupuesto General del Estado-Gesti&oacute;n 2022</p>
                </li>
                <li>
                    <p>Ley del Presupuesto General del Estado aprobado para la gesti&oacute;n y su reglamentaci&oacute;n.</p>
                </li>
                <li>
                    <p>Decreto Supremo No 4646 de 29 diciembre de 2021, Art&iacute;culo 13.</p>
                </li>
                <li>
                    <p>Ley N&deg; 2341, Ley del Procedimiento Administrativo.&nbsp;</p>
                </li>
                <li>
                    <p>Reglamento Interno de Personal del Gobierno Aut&oacute;nomo Departamental de Beni, en todo lo que sea aplicable y compatible con la naturaleza jur&iacute;dica de este contrato.</p>
                </li>
                <li>
                    <p>Ley N&ordm; 99 de fecha 08/05/2020 LEY DE LA ORGANIZACIÓN B&Aacute;SICA DEL ÓRGANO EJECUTIVO DEL GOBIERNO AUTONÓMO DEPARTAMENTAL DEL BENI</p>
                </li>
                <li>
                    <p>Ley Departamental del Beni N&ordm; 001/2010 de fecha 09/06/2010</p>
                </li>
            </ul>
            <p>Las dem&aacute;s disposiciones relacionadas directamente con las normas anteriormente mencionadas,</p>
            <p>En ese marco el CONTRATADO no estar&aacute; bajo el r&eacute;gimen de la Ley General del Trabajo ni el Decreto Reglamentario 224 de fecha 23 de agosto de 1943.</p>
            <p><strong>CL&Aacute;USULA CUARTA. - (Objeto): </strong>El objeto del presente documento es contratar los servicios de un <strong>&ldquo;<em>{{ Str::upper($contract->cargo->Descripcion) }}</em>&rdquo;, </strong>en el <strong><em> {{ $contract->program->class }} {{ $contract->program->name }}</em> </strong>y establecer los t&eacute;rminos y condiciones dentro de los cuales se desenvolver&aacute; el/la <strong>CONTRATADO (A) </strong>en la ejecuci&oacute;n del presente contrato.</p>
            <p><strong>CL&Aacute;USULA QUINTA. - (Alcance del Servicio): </strong>La amplitud del servicio y las obligaciones del/de la <strong>CONTRATADO (A) </strong>estar&aacute;n fijadas en los T&eacute;rminos de Referencia. El/La <strong>CONTRATADO (A) </strong>cumplir&aacute; las funciones y los alcances del servicio a satisfacci&oacute;n del <strong>CONTRATANTE</strong>, siendo estas funciones de car&aacute;cter <strong>ENUNCIATIVO Y NO LIMITATIVO</strong>, debiendo tambi&eacute;n realizar aquellas tareas que le sean asignadas a requerimiento del <strong>CONTRATANTE</strong>.</p>
            <p><strong>CL&Aacute;USULA SEXTA. - (Vigencia del Contrato): </strong>El presente contrato ser&aacute; computable a partir <strong>del {{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' al '.date('d', strtotime($contract_finish)).' de '.$months[intval(date('m', strtotime($contract_finish)))].' de '.date('Y', strtotime($contract_finish)) }}</strong>; debiendo evaluarse el rendimiento y la responsabilidad del/de la <strong>CONTRATADO (A) </strong>al cabo de cada gesti&oacute;n para efectos de evaluaci&oacute;n.</p>
            @php
                $numeros_a_letras = new NumeroALetras();
            @endphp
            <p><strong>CL&Aacute;USULA S&Eacute;PTIMA. - (De la Retribuci&oacute;n): </strong>Por la prestaci&oacute;n del <strong>SERVICIO </strong>contratado, el <strong>CONTRATANTE </strong>se obliga a cancelar a favor del/de la <strong>CONTRATADO (A), </strong>una retribuci&oacute;n econ&oacute;mica <strong>mensual de Bs. {{ number_format($sueldo, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice($sueldo, 2, 'Bolivianos') }}), </strong>con cargo a la <strong>Partida Presupuestaria N&deg; 12100, </strong>previa presentaci&oacute;n de planilla por el Responsable del Proyecto y/o Programa y la Direcci&oacute;n o Secretar&iacute;a bajo la cual &eacute;ste se encuentre.</p>
            <p>A partir del d&iacute;a diez (10) hasta el d&iacute;a quince (15) de cada mes, el/la <strong>CONTRATADO (A)</strong>, deber&aacute; presentar el formulario de descargo impositivo exigible de acuerdo a ley, correspondiendo &eacute;ste al Formulario N&deg; 110 conforme a la normativa vigente, caso contrario le ser&aacute; retenida la al&iacute;cuota parte que corresponda de acuerdo al R&eacute;gimen Complementario al Impuesto al Valor Agregado (RC-IVA) a los fines de pago de dicho gravamen tributario.</p>
            <p>El/la <strong>CONTRATADO (A) </strong>deber&aacute; aportar de su total ganado mensual el 10% a su cuenta individual a la AFP que le corresponda. A estos descuentos se sumar&aacute; el 0.5% de comisi&oacute;n a la AFP, el 0.5% del aporte solidario y el 1.71% correspondiente al riesgo com&uacute;n, haciendo un total de 12.71% de descuento por concepto de seguridad a largo plazo. El presente precepto estar&aacute; sujeto o podr&aacute; ser modificado en base a la normativa vigente que regule el Sistema Nacional de Pensiones.</p>
            <p><strong>CL&Aacute;USULA OCTAVA. - (Otras Obligaciones del Contratante): </strong>El <strong>CONTRATANTE </strong>se obliga a proporcionar al/a la <strong>CONTRATADO (A) </strong>el material y/o equipo necesario para realizar sus labores, as&iacute; como los recursos que correspondan. Tambi&eacute;n deber&aacute; prestar al/a la <strong>CONTRATADO (A) </strong>el Seguro de Salud a Corto Plazo.</p>
            <p>Asimismo, realizar&aacute; los aportes patronales que establecen las directrices presupuestarias emanadas del Ministerio de Econom&iacute;a y Finanzas P&uacute;blicas. Los aportes de la Seguridad Social a largo plazo, efectuados a la entidad gestora p&uacute;blica de la seguridad social a largo plazo, se descontar&aacute;n de los pagos mensuales en las proporciones que se establezcan en la normativa correspondiente.</p>
            <p><strong>CL&Aacute;USULA NOVENA. - (Obligaciones del Contratado): </strong>El/La <strong>CONTRATADO (A) </strong>se obliga a:</p>
            <ol>
                <li>
                    <p>Ejecutar el trabajo para el cual ha sido contratado (a), con responsabilidad, eficacia y bajo la Supervisi&oacute;n del superior jer&aacute;rquico que se establezca en los T&eacute;rminos de Referencia y/o Alcance de Servicio, documento que forma parte inherente del presente contrato y que el <strong>CONTRATADO (A) </strong>declara conocer en su integridad.</p>
                </li>
            </ol>
            <ol start="2">
                <li>
                    <p>Cumplir las obligaciones contra&iacute;das y se sujeta a las condiciones que el <strong>CONTRATANTE </strong>establezca en lo relativo a horarios, trabajos de campo, tareas de emergencias y otras labores relativas a los compromisos nacionales, internacionales, municipales e interinstitucionales que demanden su concurso, sin requerir por esto pagos adicionales u horas extras.</p>
                </li>
            </ol>

            <ol start="3">
                <li>
                    <p>Cuidar y manejar los equipos y bienes a su cargo, debiendo manejarlos en el marco de lo estipulado en sus manuales y normas de buen uso. En caso de da&ntilde;o, se har&aacute; responsable de su reparaci&oacute;n y/o reemplazo, seg&uacute;n corresponda.</p>
                </li>
            </ol>
            <p><strong>9.4.</strong><strong> </strong>Mantener en reserva y confidencialidad el trabajo que realiza con respecto a terceras personas, quedando establecido que la misma pertenece a la instituci&oacute;n.</p>
            <p><strong>CL&Aacute;USULA D&Eacute;CIMA. - (De la Prohibici&oacute;n de la Transferencia del Contrato): </strong>El/La <strong>CONTRATADO(A) </strong>no podr&aacute; ceder ni transferir derechos, reclamos u obligaciones respecto a este contrato o parte de &eacute;l.</p>
            <p><strong>CL&Aacute;USULA D&Eacute;CIMA PRIMERA. - (Causales de Resoluci&oacute;n):</strong></p>
            <ol>
                <li>
                    <p><strong>POR TERMINACIÓN DEL CONTRATO: </strong>El contrato se extinguir&aacute; y dejar&aacute; de surtir sus efectos legales una vez vencido el t&eacute;rmino previsto en la cl&aacute;usula sexta. No habiendo necesidad de pronunciamiento verbal o escrito alguno.</p>
                </li>
            </ol>
            <ol start="2">
                <li>
                    <p><strong>POR INCUMPLIMIENTO PARCIAL O TOTAL: </strong>El contrato se resolver&aacute; cuando el <strong>CONTRATADO </strong>incumpliera total o parcialmente los t&eacute;rminos del presente contrato, los T&eacute;rminos de Referencia o Alcance de Servicio o el Reglamento del Personal Eventual.</p>
                </li>
            </ol>
            <ol start="3">
                <li>
                    <p><strong>POR LIBRE CONVENIO ENTRE PARTES: </strong>De ser conveniente para ambas partes suspender la relaci&oacute;n contractual, se declarar&aacute; terminado el presente contrato, previa entrega al <strong>CONTRATANTE </strong>de todos los informes, equipos y otros activos a cargo del/de la <strong>CONTRATADO (A)</strong>, mediante la firma del documento de conformidad correspondiente.</p>
                </li>
            </ol>
            <ol start="4">
                <li>
                    <p><strong>POR DECISIÓN UNILATERAL: </strong>Por ser de inter&eacute;s a cada parte, se podr&aacute; rescindir el contrato previo aviso de m&iacute;nimo quince (15) d&iacute;as por parte del <strong>CONTRATANTE </strong>y quince (15) d&iacute;as por parte del/de la <strong>CONTRATADO (A)</strong>, debiendo en todo caso el/la <strong>CONTRATADO (A) </strong>cumplir con la entrega de documentos, activos, informes, antes del cumplimiento de los d&iacute;as de aviso previo.</p>
                </li>
            </ol>
            <p>En caso de demora por parte del/de la <strong>CONTRATADO (A)</strong>, el <strong>CONTRATANTE </strong>retendr&aacute; los pagos que se le adeuden sin perjuicio de iniciar acciones legales que vea conveniente.</p>
            <ol start="5">
                <li>
                    <p><strong>POR CASO FORTUITO O FUERZA MAYOR: </strong>Cuando medien condiciones extraordinarias al margen de la voluntad y dominio de ambas partes, se declarar&aacute; resuelto el presente contrato sin reclamo posterior ya sea ante instancias administrativas o jurisdiccionales.</p>
                </li>
            </ol>
            <ol start="6">
                <li>
                    <p><strong>POR CIERRE DEL PROYECTO: </strong>Si debido a condiciones financieras o t&eacute;cnicas del Proyecto y que este debiera cerrar, las condiciones de terminaci&oacute;n del contrato se sujetar&aacute;n a lo expuesto en los dos puntos anteriores.</p>
                </li>
            </ol>
            <ol start="7">
                <li>
                    <p><strong>POR DESIGNACIÓN O INCORPORACIÓN DEL/DE LA CONTRATADO (A) como funcionario (a) de planta dentro de la entidad contratante: </strong>En caso de requerir <strong>EL CONTRATANTE </strong>la incorporaci&oacute;n del/de la <strong>CONTRATADO (A) </strong>como funcionario de planta, el mismo deber&aacute; previamente renunciar al contrato, enmarcando su decisi&oacute;n en el presente numeral.</p>
                </li>
            </ol>
            <p><strong>CL&Aacute;USULA DÉCIMA SEGUNDA (Reglas Aplicables para la Resoluci&oacute;n).-</strong> Para proceder a la Resoluci&oacute;n del Contrato por <strong>causales se&ntilde;aladas en los numerales 11.2, 11.3, 11.4 y 11.5 EL CONTRATANTE </strong>o <strong>EL/LA CONTRATADO (A) </strong>dar&aacute;n aviso escrito mediante <strong>CARTA a la otra parte </strong>que la resoluci&oacute;n se ha hecho efectiva, <strong>estableciendo claramente la causal que se aduce.</strong></p>
            <p>Cuando se efect&uacute;e la resoluci&oacute;n del contrato se proceder&aacute; a una liquidaci&oacute;n de saldos deudores y acreedores de ambas partes, efectu&aacute;ndose los pagos a que hubiere lugar, conforme la evaluaci&oacute;n del grado de cumplimiento de los T&eacute;rminos de Referencia y/o Alcance de Servicio.</p>
            <p><strong>Para el caso del numeral 11.7 </strong>se notificar&aacute; a/la <strong>CONTRATADO(A), </strong>mediante <strong>CARTA </strong>que el contrato se ha resuelto por la causal establecida en el mencionado numeral de la cl&aacute;usula D&eacute;cima Primera, no requiri&eacute;ndose para el efecto Informe Legal alguno por parte de la Unidad Jur&iacute;dica.</p>
            <p><strong>CL&Aacute;USULA D&Eacute;CIMA TERCERA. - (Soluci&oacute;n de Controversias).- </strong>En caso de surgir controversias entre el <strong>CONTRATANTE </strong>y <strong>CONTRATADO(A) </strong>durante la ejecuci&oacute;n del presente contrato, las partes acudir&aacute;n a los t&eacute;rminos y condiciones del contrato establecidos en los T&eacute;rminos de Referencia.&nbsp;</p>
            <p><strong>CL&Aacute;USULA D&Eacute;CIMA CUARTA. - (De la Conformidad): </strong>El <strong>CONTRATANTE </strong>a trav&eacute;s de su personero legal mencionado en la cl&aacute;usula primera y {{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }} </strong>en su condici&oacute;n de <strong>CONTRATADO (A) </strong>declaran su plena conformidad con las cl&aacute;usulas del presente contrato y se comprometen a su fiel y estricto cumplimiento, firmando para su constancia al pie del mismo.</p>
            <p>Este documento, conforme a disposici&oacute;n legal de control fiscal vigente, ser&aacute; registrado ante la Contralor&iacute;a General del Estado en idioma espa&ntilde;ol.</p>
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
        @media print{
            .content {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection