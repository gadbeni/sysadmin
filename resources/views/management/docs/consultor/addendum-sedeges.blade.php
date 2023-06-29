@extends('layouts.template-print-legal')

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

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate("CONTRATO MODIFICATORIO DE CONSULTORIA INDIVIDUAL DE LINEA ".($signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF')." NRO ".($addendums->first()->code)." RELACIONADO AL CONTRATO ".($signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF')."/GAD-BENI NRO ".$code);
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
                <h3>CONTRATO MODIFICATORIO DE CONSULTORIA INDIVIDUAL DE LINEA {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }} N&deg; {{ $addendums->first()->code }} RELACIONADO AL CONTRATO {{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }} </h3>
            </div>
            <p>Conste por el presente <b>CONTRATO ADMINISTRATIVO</b> ampliatorio o modificatorio, para la prestación de servicios de <b>CONSULTORÍA</b>, que celebran por una parte el Servicio Departamental de Gestión Social del Beni (SEDEGES BENI) dependiente Gobierno Autónomo Departamental del Beni, con NIT Nº 177396029, con domiciliado en Calle Manuel Limpias N° 45, de la ciudad de Trinidad, provincia Cercado del Departamento del Beni, representado legalmente por la/el {{ $signature ? $signature->name : setting('firma-autorizada.name') }}, con Cédula de Identidad Nº {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}, conforme a <b>{{ $signature ? $signature->designation_type : 'Resolución de Gobernación' }} N&deg; {{ $signature ? $signature->designation : setting('firma-autorizada.designation-alt') }}</b>, en calidad de {{ $signature ? $signature->job : setting('firma-autorizada.job') }} que en adelante se denominará la ENTIDAD; y por otra parte <b>{{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}, con C.I {{ $contract->person->ci }}</b>, que en adelante se denominará el <b>{{ $contract->person->gender == 'masculino' ? 'CONSULTOR' : 'CONSULTORA' }}</b>, quienes celebran y suscriben el <b>PRIMER CONTRATO ADMINISTRATIVO AMPLIATORIO O MODIFICATORIO</b>, al tenor de las siguientes clausulas:</p>
            <p><b>CLÁUSULA PRIMERA.- (ANTECEDENTES)</b> La <b>ENTIDAD</b>, en proceso realizado bajo las normas y regulaciones de contratación establecidas en el Decreto Supremo N° 0181,  de 28 de junio de 2009, de las Normas Básicas del Sistema de Administración de Bienes y Servicios (NB-SABS), sus modificaciones, para la Contratación de Servicios de Consultoría Individual de línea <b>"{{ Str::upper($contract->cargo->Descripcion).($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} PARA LA/EL {{ Str::upper($contract->work_location ?? $contract->unidad_administrativa->nombre) }} DEL SEDEGES-BENI"</b>, en la Modalidad Contratación Menor, Invitó en fecha <b>{{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }}</b>, a personas naturales con capacidad de contratar con el Estado, a presentar propuestas en el proceso de contratación, en base a lo solicitado en los Términos de Referencia.</p>
            <p>Concluido el proceso de evaluación de propuestas, el Responsable del Proceso de Contratación de Apoyo Nacional a la Producción y Empleo (RPA), en base al <b>Informe de Evaluación y Recomendación de Adjudicación N° INF/GAD-BENI/{{ $code }} de fecha {{ date('d', strtotime($contract->date_report)) }} de {{ $months[intval(date('m', strtotime($contract->date_report)))] }} de {{ date('Y', strtotime($contract->date_report)) }}</b>, emitido por 
                @forelse ($contract->workers as $item)
                    @if (isset($item->person))
                        {{ $item->person->first_name }} {{ $item->person->last_name }} 
                    @else
                        {{ str_replace('  ', ' ', $item->NombreCompleto) }} 
                    @endif
                    ,
                @empty
                    Mercedes Suarez Chávez, 
                @endforelse
                <b>Responsable(s) de Evaluación</b>, resolvió adjudicar mediante <b>Nota de Adjudicación NA/GAD-BENI/{{ $code }} de fecha {{ date('d', strtotime($contract->date_note)) }} de {{ $months[intval(date('m', strtotime($contract->date_note)))] }} de {{ date('Y', strtotime($contract->date_note)) }}</b> la contratación del Servicio de Consultoría Individual <b>{{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}</b>, con Cédula de Identidad <b>Nº {{ $contract->person->ci }}</b> al cumplir su propuesta con todos los requisitos solicitados por la entidad en los Términos de Referencia, contrato que es suscrito en fecha <b>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</b>.</p>
            <p>Posteriormente, mediante solicitud de fecha, {{ date('d', strtotime($addendums->first()->request_date)) }} de {{ $months[intval(date('m', strtotime($addendums->first()->request_date)))] }} de {{ date('Y', strtotime($addendums->first()->request_date)) }} emitido por {{ $addendums->first()->applicant->person->first_name }} {{ $addendums->first()->applicant->person->last_name }}/{{ $addendums->first()->applicant->cargo ? $addendums->first()->applicant->cargo->Descripcion : $addendums->first()->applicant->job->name }}, solicita aprobación de la ampliación del contrato de Consultoría Individual de línea <b>"{{ Str::upper($contract->cargo->Descripcion).($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} PARA LA/EL {{ Str::upper($contract->work_location ?? $contract->unidad_administrativa->nombre) }} DEL SEDEGES-BENI"</b>, estableciendo que es necesario seguir contando con esta consultoría toda vez que este personal, cuenta con amplia experiencia de conocimiento y capacidad en el área.</p>
            <p>Que de acuerdo al Informe legal emitido en fecha {{ date('d', strtotime($addendums->first()->legal_report_date)) }} de {{ $months[intval(date('m', strtotime($addendums->first()->legal_report_date)))] }} de {{ date('Y', strtotime($addendums->first()->legal_report_date)) }}, el mismo que establece que de acuerdo al D.S. 0181 Art. 89 Inc b) y a la cláusula Decima Quinta del contrató principal suscrito en fecha <b>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</b> y la justificación técnicas del supervisor del contrato, mismas que demuestra la necesidad de ampliar el contrato de de Consultoría Individual de línea <b>"{{ Str::upper($contract->cargo->Descripcion).($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} PARA LA/EL {{ Str::upper($contract->work_location ?? $contract->unidad_administrativa->nombre) }} DEL SEDEGES-BENI"</b>, y la existencia de presupuesto, es viable la ampliación de dicho contrato de consultoría.</p>
            <p><b>CLÁUSULA SEGUNDA. - (LEGISLACIÓN APLICABLE)</b> El presente Contrato se celebra exclusivamente al amparo de las siguientes disposiciones:</p>
            <p>
                <ul>
                    <li>Constitución Política del Estado</li>
                    <li>Ley Nº 1178, de 20 de julio de 1990, de Administración y Control Gubernamentales.</li>
                    <li>Decreto Supremo Nº 0181, de 28 de junio de 2009, d las Normas Básicas del Sistema de Administración de Bienes y Servicios – NB-SABS y sus modificaciones.</li>
                    <li>Ley del presupuesto General aprobado para la gestión 2023 y su Reglamentación.</li>
                    <li>Otras disposiciones conexas.</li>
                </ul>
            </p>
            <p><b>CLÁUSULA TERCERA. - (OBJETO Y CAUSA)</b> El presente documento tiene por objeto modificar la Cláusula Novena (con relación al Plazo de Prestación de la Consultoría) y la Cláusula Decima Primera, Numeral 1.1 (con relación al Monto) del Contrato Administrativo {{ $contract->code }}, suscrito en fecha {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}.</p>
            <p><b>CLÁUSULA CUARTA.- - (DE LA MODIFICACIÓN) </b></p>
            <p><b>4.1 Modifíquese la Cláusula Novena del Contrato Administrativo </b>{{ $contract->code }}, suscrito en fecha {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}, quedando redando redactada de la siguiente manera:</p>
            <p><b style="margin-left: 50px">CLÁUSULA NOVENA. - (Plazo de Prestación de la Consultoría)</b> El Consultor desarrollara sus actividades de forma satisfactoria, en estricto acuerdo con el alcance del servicio, la propuesta adjudicada, los términos de Referencia, computándose el plazo a partir del día siguiente hábil de la finalización del contrato principal, es decir desde el <b>{{ date('d', strtotime($addendums->first()->start)) }} de {{ $months[intval(date('m', strtotime($addendums->first()->start)))] }} de {{ date('Y', strtotime($addendums->first()->start)) }}</b> al <b>{{ date('d', strtotime($addendums->first()->finish)) }} de {{ $months[intval(date('m', strtotime($addendums->first()->finish)))] }} de {{ date('Y', strtotime($addendums->first()->finish)) }}</b>, sin lugar a tacita reconducción.</p>
            <p>En el caso de que la finalización del a Consultoría, coincida con un día sábado, domingo o feriado, la misma será trasladada al siguiente día hábil administrativo.</p>
            <p><b>4.2. Modifíquese la Cláusula Decima Primera, Numeral 1.1 del Contrato Administrativo {{ $code }}</b> , suscrito en fecha {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}, quedando redando redactado de la siguiente manera</p>
            <p><b style="margin-left: 50px">CLÁUSULA DÉCIMA PRIMERA. - (MONTO Y FORMA DE PAGO).- </b></p>
            @include('management.docs.consultor.partials.payment_details', ['subtitle' => '1.1. MONTO', 'contract' => $contract, 'contract_start' => $addendums->first()->start, 'contract_finish' => $addendums->first()->finish])
            <p>Queda establecido que el monto consignado en el presente Contrato incluye todos los elementos sin excepción alguna que sean necesarios para la realización y cumplimiento de la CONSULTORÍA y no se reconocerán ni procederán pagos por servicios que excedan dicho monto.</p>
            <p><b>CLÁUSULA QUINTA. - (DE LOS ANEXOS)</b> Son parte del presente contrato como anexos, el contrato principal suscrito en fecha <b>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</b>, solicitud de ampliación realizada por {{ $addendums->first()->applicant->person->first_name }} {{ $addendums->first()->applicant->person->last_name }}/{{ $addendums->first()->applicant->cargo ? $addendums->first()->applicant->cargo->Descripcion : $addendums->first()->applicant->job->name }}.</p>
            <p><b>CLÁUSULA SEXTA. - (DE LA RATIFICACIÓN)</b> Se ratifican en extenso, todas y cada una de las Cláusulas del Contrato Principal, suscrito en fecha de <b>{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</b>, que no hayan sido modificadas mediante el presente contrato.</p>
            <p><b>CLÁUSULA SÉPTIMA.- (CONSENTIMIENTO)</b> En señal de conformidad y para su fiel y estricto cumplimiento, firmamos el presente <b>CONTRATO</b> en cinco ejemplares de un mismo tenor y validez la/el {{ $signature ? $signature->name : setting('firma-autorizada.name') }}, con Cédula de Identidad Nº {{ $signature ? $signature->ci : setting('firma-autorizada.ci') }}, en calidad de {{ $signature ? $signature->job : setting('firma-autorizada.job') }} del SEDEGES-BENI en representación legal de la <b>ENTIDAD</b>; y, por otra parte, {{ $contract->person->gender == 'masculino' ? 'el señor' : 'la señora' }} {{ $contract->person->first_name }} {{ $contract->person->last_name }}, con Cédula de Identidad <b>Nº {{ $contract->person->ci }}</b> como <b>{{ $contract->person->gender == 'masculino' ? 'CONSULTOR' : 'CONSULTORA' }}</b> contratado.</p>
            <p>Este documento, conforme a disposiciones legales de control fiscal vigentes, será registrado ante la Contraloría General del Estado en idioma español.</p>
            <br>
            <p style="text-align: right;">
                @php
                    $signature_date = $addendums->first()->signature_date ?? $addendums->first()->start;
                @endphp
                <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($signature_date)) }} de {{ Str::upper($months[intval(date('m', strtotime($signature_date)))]) }} de {{ date('Y', strtotime($signature_date)) }}
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
        @media print{
            .content {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            
        });
    </script>
@endsection