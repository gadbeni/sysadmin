@extends('layouts.template-pdf')

@section('page_title', 'Ampliación de Contrato')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;

    // Si es la primera adenda se obtiene la primera registrada y si no se obtienen las últimas 2 en orden descendente
    if(request('type') == 'first'){
        $addendums = $contract->addendums->where('deleted_at', NULL)->sortBy('id')->slice(0, 1);
    }else{
        $addendums = $contract->addendums->where('deleted_at', NULL)->sortByDesc('id')->slice(0, 2);
    }

    // Solo en caso de adendas firma el director de finanzas
    $signature = $addendums->first()->signature;
@endphp

@section('content')
<div class="content" style="text-align: justify">
        <h2 class="text-center" style="font-size: 15px">CONTRATO MODIFICATORIO DE CONSULTORIA INDIVIDUAL DE LINEA {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }} N&deg; {{ $addendums->first()->code }} RELACIONADO AL CONTRATO {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </h2>
        <p>Conste por el presente <b>CONTRATO ADMINISTRATIVO</b> ampliatorio o modificatorio, para la prestación de servicios de <b>CONSULTORÍA</b>, que celebran por una parte el Servicio Departamental de Gestión Social del Beni (SEDEGES BENI) dependiente Gobierno Autónomo Departamental del Beni, con NIT Nº 177396029, con domiciliado en Calle Manuel Limpias N° 45, de la ciudad de Trinidad, provincia Cercado del Departamento del Beni, representado legalmente por la Dra. Martha Mejia Fayer de Rosas, con Cédula de Identidad Nº 1704320, conforme a <b>Resolución Administrativa de Gobernación N°05/2023</b>, en calidad de Responsable de los Procesos de Contratación menor RPA que en adelante se denominará la ENTIDAD; y por otra parte la <b>Sra. María Teresa Alpire Vaca, con C.I  5618607-BE</b>, que en adelante se denominará el <b>CONSULTOR (A)</b>, quienes celebran y suscriben el <b>PRIMER CONTRATO ADMINISTRATIVO AMPLIATORIO O MODIFICATORIO</b>, al tenor de las siguientes clausulas:</p>
        <p><b>CLÁUSULA PRIMERA.- (ANTECEDENTES)</b> La <b>ENTIDAD</b>, en proceso realizado bajo las normas y regulaciones de contratación establecidas en el Decreto Supremo N° 0181,  de 28 de junio de 2009, de las Normas Básicas del Sistema de Administración de Bienes y Servicios (NB-SABS), sus modificaciones, para la Contratación de Servicios de Consultoría Individual de línea <b>“PROFESIONAL III- RESPONSABLE DEL ÁREA DE BIENES Y SERVICIOS DEL SEDEGES-BENI”</b>, en la Modalidad Contratación Menor, Invitó en fecha <b>01 de junio de 2023</b>, a personas naturales con capacidad de contratar con el Estado, a presentar propuestas en el proceso de contratación, en base a lo solicitado en los Términos de Referencia.</p>
        <p>Concluido el proceso de evaluación de propuestas, el Responsable del Proceso de Contratación de Apoyo Nacional a la Producción y Empleo (RPA), en base al <b>Informe de Evaluación y Recomendación de Adjudicación N° 166/2022 de fecha 02 de junio de 2023</b>, emitido por la Dra. Mercedes Suarez Chávez, <b>Responsable de Evaluación</b>, resolvió adjudicar mediante <b>Nota de Adjudicación N° 166/2023 de fecha 03 de junio del 2023</b> la contratación del Servicio de Consultoría Individual <b>nombre del funcionario</b>, con Cédula de Identidad <b>Nº de CI</b> al cumplir su propuesta con todos los requisitos solicitados por la entidad en los Términos de Referencia, contrato que es suscrito en fecha <b>06 de junio del 2022</b>.</p>
        <p>Posteriormente, mediante solicitud de fecha, 29 de agosto de 2023 emitido por el Lic.  Ernesto Guasde Mercado- Jefe a.i de la Unidad de Administración y Finanzas, solicita aprobación de la ampliación del contrato de Consultoría Individual de línea <b>"PROFESIONAL III- RESPONSABLE DEL ÁREA DE BIENES Y SERVICIOS DEL SEDEGES-BENI"</b>, estableciendo que es necesario seguir contando con esta consultoría toda vez que este personal, cuenta con amplia experiencia de conocimiento y capacidad en el área.</p>
        <p>Que de acuerdo al Informe legal emitido en fecha 01 de septiembre de 2023, el mismo que establece que de acuerdo al D.S. 0181 Art. 89 Inc b) y a la cláusula Decima Quinta del contrató principal suscrito en fecha <b>06 de junio del 2023</b> y la justificación técnicas del supervisor del contrato, mismas que demuestra la necesidad de ampliar el contrato de de Consultoría Individual de línea <b>"PROFESIONAL III- RESPONSABLE DEL ÁREA DE BIENES Y SERVICIOS DEL SEDEGES-BENI"</b>, y la existencia de presupuesto, es viable la ampliación de dicho contrato de consultoría.</p>
        <p><b>CLÁUSULA SEGUNDA. - (LEGISLACIÓN APLICABLE)</b> El presente Contrato se celebra exclusivamente al amparo de las siguientes disposiciones:</p>
        <p>
            <ul>
                <li>Constitución Política del Estado</li>
                <li>Ley Nº 1178, de 20 de julio de 1990, de Administración y Control Gubernamentales.</li>
                <li>Decreto Supremo Nº 0181, de 28 de junio de 2009, d las Normas Básicas del Sistema de Administración de Bienes y Servicios – NB-SABS y sus modificaciones.</li>
                <li>Ley del presupuesto General aprobado para la gestión 2022 y su Reglamentación.</li>
                <li>Otras disposiciones conexas.</li>
            </ul>
        </p>
        <p><b>CLÁUSULA TERCERA. - (OBJETO Y CAUSA)</b> El presente documento tiene por objeto modificar la Cláusula Novena (con relación al Plazo de Prestación de la Consultoría) y la Cláusula Decima Primera, Numeral 1.1 (con relación al Monto) del Contrato Administrativo SEDEGES BENI N° 162/2023, suscrito en fecha 06 de junio de 2023.</p>
        <p><b>CLÁUSULA CUARTA.- - (DE LA MODIFICACIÓN) </b></p>
        <p><b>4.1 Modifíquese la Cláusula Novena del Contrato Administrativo</b> SEDEGES BENI N° 162/2023, suscrito en fecha 06 de junio de 2023, quedando redando redactada de la siguiente manera:</p>
        <p><b style="margin-left: 50px">CLÁUSULA NOVENA. - (Plazo de Prestación de la Consultoría)</b> El Consultor desarrollara sus actividades de forma satisfactoria, en estricto acuerdo con el alcance del servicio, la propuesta adjudicada, los términos de Referencia, computándose el plazo a partir del día siguiente hábil de la finalización del contrato principal, es decir desde el <b>07 de septiembre hasta el 06 de diciembre 2023</b>, sin lugar a tacita reconducción.</p>
        <p>En el caso de que la finalización del a Consultoría, coincida con un día sábado, domingo o feriado, la misma será trasladada al siguiente día hábil administrativo.</p>
        <p><b>4.2. Modifíquese la Cláusula Decima Primera, Numeral 1.1 del Contrato Administrativo SEDEGES BENI N° 162/2023</b> , suscrito en fecha 06 de junio  del 2023, quedando redando redactado de la siguiente manera</p>
        <p><b style="margin-left: 50px">CLÁUSULA DÉCIMA PRIMERA. - (MONTO Y FORMA DE PAGO).- </b></p>
        @include('management.docs.consultor.partials.payment_details', ['subtitle' => '1.1. MONTO', 'contract' => $contract, 'contract_start' => $addendums->first()->start, 'contract_finish' => $addendums->first()->finish])
        <p>Queda establecido que el monto consignado en el presente Contrato incluye todos los elementos sin excepción alguna que sean necesarios para la realización y cumplimiento de la CONSULTORÍA y no se reconocerán ni procederán pagos por servicios que excedan dicho monto.</p>
        <p><b>CLÁUSULA QUINTA. - (DE LOS ANEXOS)</b> Son parte del presente contrato como anexos, el contrato principal suscrito en fecha <b>06 de junio del 2023</b>, solicitud de ampliación realizada por el Lic.   Ernesto Guasde Mercado- Jefe a.i de la Unidad de Administración y Finanzas.</p>
        <p><b>CLÁUSULA SEXTA. - (DE LA RATIFICACIÓN)</b> Se ratifican en extenso, todas y cada una de las Cláusulas del Contrato Principal, suscrito en fecha de <b>06 de junio del 2023</b>, que no hayan sido modificadas mediante el presente contrato.</p>
        <p><b>CLÁUSULA SÉPTIMA.- (CONSENTIMIENTO)</b> En señal de conformidad y para su fiel y estricto cumplimiento, firmamos el presente <b>CONTRATO</b> en cinco ejemplares de un mismo tenor y validez la Dra. Martha Mejía Fayer de Rosas, con C.I N° 1704320, en calidad de Directora del SEDEGES-BENI en representación legal de la <b>ENTIDAD</b>; y, por otra parte, la Sra. María Teresa Alpire Vaca, con Cédula de Identidad <b>Nº 5618607-BE</b> como <b>CONSULTOR (A)</b> contratado.</p>
        <p>Este documento, conforme a disposiciones legales de control fiscal vigentes, será registrado ante la Contraloría General del Estado en idioma español.</p>


        <p>&nbsp;</p>
        <p style="text-align: right;">
            @php
                $fecha_firma = $addendums->first()->start;
                if(date("N", strtotime($fecha_firma)) == 6){
                    $fecha_firma = date('Y-m-d', strtotime($fecha_firma." -1 day"));
                }
                if(date("N", strtotime($fecha_firma)) == 7){
                    $fecha_firma = date('Y-m-d', strtotime($fecha_firma." -2 day"));
                }
            @endphp
            <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($fecha_firma)) }} de {{ Str::upper($months[intval(date('m', strtotime($fecha_firma)))]) }} de {{ date('Y', strtotime($fecha_firma)) }}
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
        .content {
            padding: 50px 34px;
            font-size: 12px;
        }
        .text-center{
            text-align: center;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endsection