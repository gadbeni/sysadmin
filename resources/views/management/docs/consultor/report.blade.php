@extends('layouts.template-print')

@section('page_title', 'Informe')

@php
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
            $qrcode = QrCode::size(70)->generate("INFORME ".$contract->code." - CONSULTORÍA DE LÍNEA");
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
            <div class="page-head" style="margin-top: -40px !important">
                @php
                    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                    $code = $contract->code;
                    $signature = $contract->signature;
                    if(!in_array($contract->direccion_administrativa_id, [8, 42, 61]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4])){
                        $signature = null;   
                    }
                @endphp
                <h4 style="text-align: center; margin: 0px; margin-bottom: 10px">
                    INFORME <br>
                    INF/GAD-BENI/{{ $code }}
                </h4>
            </div>
            <div class="page-body">
                <div style="padding: 0px 20px">
                    <table style="font-size: 12px" cellspacing="10">
                        <tr>
                            <td>A</td>
                            <td style="width: 20px; text-align: center">:</td>
                            <td>
                                {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                                <b>{{ $signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA' }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>De</td>
                            <td style="width: 20px; text-align: center">:</td>
                            <td>
                                @forelse ($contract->workers as $item)
                                    @if (isset($item->person))
                                        {{ $item->person->first_name }} {{ $item->person->last_name }}<br>
                                        @if ($item->alternate_job->count() > 0)
                                            <b>{{ Str::upper($item->alternate_job->last()->name) }}</b> <br> <br>
                                        @else
                                            <b>{{ Str::upper($item->job ? $item->job->name : $item->cargo->Descripcion) }}</b> <br> <br>
                                        @endif
                                    @else
                                        {{ str_replace('  ', ' ', $item->NombreCompleto) }} <br>
                                        <b>{{ $item->Cargo }}</b> <br>
                                    @endif
                                @empty
                                    <br> <br>
                                    <b style="color: red">Debes Seleccionar a la comisión evaluadora</b>
                                    <br> <br>
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <td>REF</td>
                            <td style="width: 20px; text-align: center">:</td>
                            <td>
                                <b>INFORME DE EVALUACIÓN Y RECOMENDACIÓN DEL PROCESO DE CONTRATACIÓN, GAD-BENI/{{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}”</b>
                            </td>
                        </tr>
                        <tr>
                            <td>FECHA</td>
                            <td style="width: 20px; text-align: center">:</td>
                            <td>
                                <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->date_report)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->date_report)))]) }} de {{ date('Y', strtotime($contract->date_report)) }}
                            </td>
                        </tr>
                    </table>
                </div>
                <hr>
                <p>
                    De mi mayor consideración: <br> <br>
                    El objeto del presente informe, es la evaluación y recomendación de adjudicación o declaratoria desierta del Proceso de Contratación, en la modalidad contratación menor consultoría, <b>GAD-BENI/{{ $code }} "CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}"</b>. <br> <br>
                    <b>1. ANTECEDENTES</b> <br>
                    El Gobierno Autónomo Departamental del Beni, a través de la/el {{ $signature ? $signature->direccion_administrativa->nombre : 'Secretaría de Administración y Finanzas' }}, cuenta con diferentes programas y para la ejecución de los mismos, mediante Resolución Administrativa de Gobernación {{ $signature ? $signature->designation : setting('firma-autorizada.designation-alt') }}, de fecha {{ $signature ? $signature->designation_date ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date-alt') : setting('firma-autorizada.designation-date-alt') }}, el Gobernador del Departamento del Beni en el marco de sus funciones, designa como Responsable del Proceso de Contratación al {{ $signature ? $signature->name : setting('firma-autorizada.name') }}, {{ $signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA' }}, en el marco del Decreto Supremo N° 0181 de fecha 28 de junio de 2009. <br> <br>
                    LA/El {{ Str::upper($contract->unidad_administrativa->nombre) }}, mediante solicitud de fecha {{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }}, requiere la contratación de un Consultor Individual de Línea, para el cargo de {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}, con cargo al {{ Str::upper($contract->program->class) }}: “{{ Str::upper($contract->program->name) }}”, para tal efecto adjunta al requerimiento los Términos de Referencia, Certificación Presupuestaria. <br> <br>
                    <b>2. EVALUACIÓN</b> <br>
                    La evaluación de la documentación presentada por {{ $contract->person->gender == 'masculino' ? 'el' : 'la' }} postulante <b>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</b>, para el Proceso de Contratación <b>“GAD-BENI/{{ $code }}”</b> para la prestación de servicios de un <b>CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}</b>, se detalla en el siguiente cuadro:
                </p>
                <div>
                    <h3 style="text-align: center; margin: 10px 0px">CUADRO DE NIVEL DE CONSULTORÍA REQUERIDO</h3>

                    {!! $contract->table_report !!}

                    <p>
                        Por lo anteriormente expuesto y en cumplimiento a lo establecido en el D.S. N° 0181 Art. 38, Parágrafo III, Inciso e), <u><i>se recomienda</i></u> al/a la {{ $signature ? $signature->name : setting('firma-autorizada.name') }} – {{ $signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA' }}, <b>adjudicar</b> el Proceso de Contratación <b>{{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}”</b>, bajo el siguiente detalle:
                    </p>

                    @php
                        $contract_duration = contract_duration_calculate($contract->start, $contract_finish);
                        $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                        $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                    @endphp
                    <table class="table-th" border="1" cellpadding="10" cellspacing="0" style="width: 100%; font-size: 10px">
                        <tr>
                            <th style="font-size: 9px">POSTULANTE A SER ADJUDICADO</th>
                            <th style="font-size: 9px">MONTO A SER ADJUDICADO EN Bs.-</th>
                            <th style="font-size: 9px">PLAZO DE LA CONSULTORÍA</th>
                        </tr>
                        <tr>
                            <td>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</td>
                            @php
                                $numeros_a_letras = new NumeroALetras();
                            @endphp
                            <td>{{ number_format($total, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice(number_format($total, 2, '.', ''), 2, 'Bolivianos') }})</td>
                            <td>DEL {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }} AL {{ date('d', strtotime($contract_finish)) }} de {{ $months[intval(date('m', strtotime($contract_finish)))] }} de {{ date('Y', strtotime($contract_finish)) }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <p><b>3. MONTO Y FORMA DE PAGO</b></p>

                    @include('management.docs.consultor.partials.payment_details', ['subtitle' => '', 'contract' => $contract, 'contract_start' => $contract->start, 'contract_finish' => $contract_finish])

                    <p>Para efectos del pago de sus haberes mensuales, se, deberá de presentar Informe de Actividades mensuales, el cual deberá de estar debidamente aprobado por su inmediato superior.</p>
                    
                    <p>
                        En su condición de Responsable del Proceso de Contratación de Apoyo Nacional a la Producción Empleo – RPA podrá aprobar el presente informe y sus recomendaciones o solicitar su complementación o sustentación, conforme establece el Artículo 34 del D.S. 0181 Normas Básicas del Sistema de Administración de Bienes y Servicios.
                        Es cuanto informo, para los fines consiguientes.
                    </p>
                    <table width="100%" style="text-align: center; margin: 60px 0px; margin-bottom: 10px">
                        <tr>
                            @forelse ($contract->workers as $item)
                                <td style="width: 50%">
                                    ....................................................... <br>
                                    @if (isset($item->person))
                                        <b>A:</b> {{ $item->person->first_name }} {{ $item->person->last_name }}<br>
                                        @if ($item->alternate_job->count() > 0)
                                            <b>{{ Str::upper($item->alternate_job->last()->name) }}</b>
                                        @else
                                            <b>{{ Str::upper($item->job ? $item->job->name : $item->cargo->Descripcion) }}</b>
                                        @endif
                                    @else
                                        <b>A:</b> {{ str_replace('  ', ' ', $item->NombreCompleto) }} <br>
                                        <b>{{ $item->Cargo }}</b>
                                    @endif
                                </td>
                            @empty
                                <td style="width: 50%">
                                    <b style="color: red">Debes Seleccionar a la comisión evaluadora</b>
                                </td>
                            @endforelse
                        </tr>
                    </table>
                    <hr>
                    <p style="text-align: justify">
                        En cumplimiento a lo establecido en el artículo 34 del D.S. 0181 Normas Básicas del Sistema de Administración de Bienes y Servicios y Resolución Administrativa de Gobernación {{ $signature ? $signature->designation : setting('firma-autorizada.designation-alt') }}, de fecha {{ $signature ? $signature->designation_date ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date-alt') : setting('firma-autorizada.designation-date-alt') }}, en mi calidad de RPA, apruebo el presente informe Evaluación y sus recomendaciones emitido por los Responsables de Evaluación, para el proceso GAD-BENI/{{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO  {{ Str::upper($contract->cargo->Descripcion.($contract->name_job_alt ? ' - '.$contract->name_job_alt : '')) }}.
                    </p>
                </div>

                <div style="margin-top: 60px">
                    <p style="text-align: center; width: 100%; font-size: 12px">
                        {{ Str::upper($signature ? $signature->name : setting('firma-autorizada.name')) }} <br>
                        <b>{{ Str::upper($signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA') }}</b>
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('css')
    <style>
        .page-head {
            text-align: right;
            padding: 0px;
        }
        .page-title {
            text-align: center;
        }
        .page-title h3{
            margin-top: 0px
        }
        .content table {
            width: 100% !important
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection