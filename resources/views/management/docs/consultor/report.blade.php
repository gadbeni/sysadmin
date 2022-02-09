@extends('layouts.template-print')

@section('page_title', 'Informe')

@section('content')
    <div class="content">
        <div class="page-head">
            @php
                $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $code = str_pad($contract->code, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($contract->start));
            @endphp
            <h4 style="text-align: center">
                INFORME <br>
                INF/GAD-BENI/MC N° {{ $code }}
            </h4>
        </div>
        <div class="page-title">
            
        </div>
        <div class="page-body">
            <div>
                <table style="font-size: 12px" cellspacing="10">
                    <tr>
                        <td style="width: 100px">A</td>
                        <td style="width: 100px">:</td>
                        <td>
                            {{ setting('firma-autorizada.name') }} <br>
                            <b>RESPONSABLE DEL PROCESO DE CONTRATACIÓN DE APOYO NACIONAL A LA PRODUCCIÓN Y EMPLEO - RPA</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px">De</td>
                        <td style="width: 100px">:</td>
                        <td>
                            @forelse ($contract->workers as $item)
                                {{ str_replace('  ', ' ', Str::upper($item->NombreCompleto)) }} <br>
                                <b>{{ Str::upper($item->Cargo) }}</b> <br> <br>
                            @empty
                                <br> <br>
                                <b style="color: red">Debes Seleccionar a la comisión evaluadora</b>
                                <br> <br>
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px">REF</td>
                        <td style="width: 100px">:</td>
                        <td>
                            <b>INFORME DE EVALUACIÓN Y RECOMENDACIÓN DEL PROCESO DE CONTRATACIÓN, GAD-BENI/MC N° {{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}”</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px">FECHA</td>
                        <td style="width: 100px">:</td>
                        <td>Santísima Trinidad, {{ date('d', strtotime($contract->date_report)) }} de {{ $months[intval(date('m', strtotime($contract->date_report)))] }} de {{ date('Y', strtotime($contract->date_report)) }}</td>
                    </tr>
                </table>
                <br>
                <hr>
                <p>
                    De mi mayor consideración: <br> <br>
                    El objeto del presente informe, es la evaluación y recomendación de adjudicación o declaratoria desierta del Proceso de Contratación, en la modalidad contratación menor consultoría, <b>GAD-BENI/MC N° {{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}”</b>. <br> <br>
                    <b>1. ANTECEDENTES</b> <br>
                    El Gobierno Autónomo Departamental del Beni, a través de la Secretaría de Administración y Finanzas, cuenta con diferentes programas y para la ejecución de los mismos, mediante Resolución Administrativa de Gobernación N° 074/2021 de fecha 30 de agosto del 2021, el Gobernador del Departamento del Beni en el marco de sus funciones, designa como Responsable del Proceso de Contratación al {{ setting('firma-autorizada.name') }}, {{ setting('firma-autorizada.job') }}, en el marco del Decreto Supremo N° 0181 de fecha 28 de junio de 2009. <br> <br>
                    La {{ Str::upper($contract->unidad_administrativa->NOMBRE) }}, mediante solicitud de fecha {{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }}, requiere la contratación de un Consultor Individual de Línea, para el cargo de {{ Str::upper($contract->cargo->Descripcion) }}, con cargo al Programa: “{{ Str::upper($contract->program->name) }}”, para tal efecto adjunta al requerimiento los Términos de Referencia, Certificación Presupuestaria. <br> <br>
                    <b>2. EVALUACIÓN</b> <br>
                    La evaluación de la documentación presentada por {{ $contract->person->gender == 'masculino' ? 'el' : 'la' }} postulante <b>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</b>, para el Proceso de Contratación <b>“GAD-BENI/MC N° {{ $code }}”</b> para la prestación de servicios de un <b>CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}</b>, se detalla en el siguiente cuadro: <br> <br>
                </p>
            </div>

            <div class="saltopagina"></div>
            <div class="pt"></div>

            <div>
                <h3 style="text-align: center">CUADRO DE NIVEL DE CONSULTORÍA REQUERIDO</h3>

                {!! $contract->table_report !!}

                <br>
                <p>
                    Por lo anteriormente expuesto y en cumplimiento a lo establecido en el D.S. N° 0181 Art. 38, Parágrafo III, Inciso e), <u><i>se recomienda</i></u> al {{ setting('firma-autorizada.name') }} – Responsable del Proceso de Contratación de Apoyo Nacional a la Producción y Empleo, <b>adjudicar</b> el Proceso de Contratación <b>GAD-BENI/MC N° 190/2021 “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}”</b>, bajo el siguiente detalle:
                </p>
                <br>

                @php
                    $contract_duration = contract_duration_calculate($contract->start, $contract->finish);
                    $total = ($contract->cargo->nivel->Sueld *$contract_duration->months) + (($contract->cargo->nivel->Sueld /30) *$contract_duration->days);
                @endphp
                <table class="table-th" border="1" cellpadding="10" cellspacing="0" style="width: 100%; font-size: 11px">
                    <tr>
                        <th>POSTULANTE A SER ADJUDICADO</th>
                        <th>MONTO A SER ADJUDICADO EN Bs.-</th>
                        <th>PLAZO DE LA CONSULTORÍA</th>
                    </tr>
                    <tr>
                        <td>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</td>
                        <td>Bs.- {{ NumerosEnLetras::convertir($total, 'Bolivianos', true) }}</td>
                        <td>DEL {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }} AL {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}</td>
                    </tr>
                </table>
                
                <br>

            </div>

            <div class="saltopagina"></div>
            <div class="pt"></div>

            <div>
                <p><b>3. MONTO Y FORMA DE PAGO</b></p>
                {!! $contract->details_report !!}

                <p>Para efectos del pago de sus haberes mensuales, se, deberá de presentar Informe de Actividades mensuales, el cual deberá de estar debidamente aprobado por su inmediato superior.</p>
                
                <p>
                    En su condición de Responsable del Proceso de Contratación de Apoyo Nacional a la Producción Empleo – RPA podrá aprobar el presente informe y sus recomendaciones o solicitar su complementación o sustentación, conforme establece el Artículo 34 del D.S. 0181 Normas Básicas del Sistema de Administración de Bienes y Servicios. <br> <br>
                    Es cuanto informo, para los fines consiguientes.
                </p>

                <br>

                <table width="100%" style="text-align: center; margin: 80px 0px; margin-bottom: 50px">
                    <tr>
                        @forelse ($contract->workers as $item)
                            <td style="width: 50%">
                                ....................................................... <br>
                                {{ str_replace('  ', ' ', Str::upper($item->NombreCompleto)) }} <br>
                                <b>{{ Str::upper($item->Cargo) }}</b>
                            </td>
                        @empty
                            <td style="width: 50%">
                                <b style="color: red">Debes Seleccionar a la comisión evaluadora</b>
                                <br> <br>
                            </td>
                        @endforelse
                    </tr>
                </table>
                <hr>
                <p style="text-align: right">Santísima Trinidad, {{ date('d', strtotime($contract->date_report)) }} de {{ $months[intval(date('m', strtotime($contract->date_report)))] }} de {{ date('Y', strtotime($contract->date_report)) }}</p>
                <p style="text-align: justify">
                    En cumplimiento a lo establecido en el artículo 34 del D.S. 0181 Normas Básicas del Sistema de Administración de Bienes y Servicios y Resolución Administrativa de Gobernación N° 74/2021 de fecha 30 de agosto de 2021, en mi calidad de RPA, apruebo el presente informe Evaluación y sus recomendaciones emitido por los Responsables de Evaluación, para el proceso GAD-BENI/MC N° {{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}.
                </p>
            </div>

            <div style="margin-top: 120px">
                <p style="text-align: center; width: 100%; font-size: 12px">
                    {{ setting('firma-autorizada.name') }} <br>
                    <b>RESPONSABLE DEL PROCESO DE CONTRATACIÓN <br>
                        DE APOYO NACIONAL A LA PRODUCCIÓN Y EMPLEO – RPA <br>
                        GAD - BENI
                    </b>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-head {
            padding: 0px 34px;
            text-align: right;
            padding-top: 20px;
        }
        .page-title {
            padding: 0px 50px;
            text-align: center;
        }
        .page-body{
            padding: 0px 30px;
            padding-top: 10px;
        }
        .page-body p{
            text-align: justify;
        }
        .content {
            padding: 0px 34px;
            font-size: 13px;
        }
        .table-th th{
            background-color: #d7d7d7
        }
        table ul{
            padding-left: 20px;
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
                height: 100px;
            }
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection