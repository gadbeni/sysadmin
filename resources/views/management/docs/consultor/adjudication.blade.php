@extends('layouts.template-print')

@section('page_title', 'Nota de adjudicación')

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
            $qrcode = QrCode::size(70)->generate("NOTA DE ADJUDICACIÓN ".$contract->code." - CONSULTORÍA DE LÍNEA");
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
                @php
                    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                    $code = $contract->code;
                    $signature = $contract->signature;
                    if(!in_array($contract->direccion_administrativa_id, [8, 42, 61]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4])){
                        $signature = null;   
                    }
                @endphp
                <p style="text-align: right; margin: 0px">
                    <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->date_note)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->date_note)))]) }} de {{ date('Y', strtotime($contract->date_note)) }} <br>
                    <b>NA/GAD-BENI/{{ $code }}</b>
                </p>
                <p style="text-align: left; ; margin: 0px">
                    {{ $contract->person->gender == 'masculino' ? 'Señor' : 'Señora' }}: <br>
                    {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                    CI: {{ $contract->person->ci }} - {!! $contract->person->phone ? 'Cel. '.$contract->person->phone.'<br>' : '' !!}
                    Presente .–
                </p>
            </div>
            <div class="page-title">
                <h3><u>REF: NOTA DE ADJUDICACIÓN</u></h3>
            </div>
            <div class="page-body">
                @php
                    $contract_duration = contract_duration_calculate($contract->start, $contract_finish);
                    $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                    $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                    $periodo = '';
                    $numeros_a_letras = new NumeroALetras();
                    if($contract_duration->months > 0){
                        if($contract_duration->months == 1){
                            $periodo .= Str::lower($numeros_a_letras->toWords($contract_duration->months)).' mes';
                        }else{
                            $periodo .= Str::lower($numeros_a_letras->toWords($contract_duration->months)).' meses';
                        }
                    }
                    if($contract_duration->days > 0){
                        if($contract_duration->days == 1){
                            $periodo .= ' y '.Str::lower($numeros_a_letras->toWords($contract_duration->days)).' día';
                        }else{
                            $periodo .= ' y '.Str::lower($numeros_a_letras->toWords($contract_duration->days)).' días';
                        }
                    }
                @endphp
                <p>
                    De mi consideración: <br> <br>
                    En el marco del D.S. 0181 del 28 de junio de 2009  y sus decretos modificatorios, comunico a usted que una vez concluido el proceso de Evaluación a su postulación y conformidad a los Términos de Referencia, en base a la recomendación por el Responsable de Evaluación, se ha resuelto <b>ADJUDICAR</b> a su persona para que preste los servicios de Consultor en Línea del Proceso <b>GAD-BENI/{{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}”</b>, con cargo al {{ Str::upper($contract->program->class) }}: <b>“{{ Str::upper($contract->program->name) }}”</b>. por  un plazo de {{ $periodo }}, de acuerdo al siguiente detalle: <br>
                </p>

                <table align="center">
                    <tr>
                        <td style="width: 320px;">&#9679; &nbsp; Monto mensual</td>
                        @php
                            $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                        @endphp
                        <td style="width: 180px; text-align: right">Bs.- &nbsp;&nbsp; {{ number_format($salary, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>&#9679; &nbsp; Monto total adjudicado</td>
                        <td style="text-align: right">Bs.- &nbsp;&nbsp; {{ number_format($total, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>&#9679; &nbsp; Fecha de inicio de contrato</td>
                        <td style="text-align: right">{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</td>
                    </tr>
                    <tr>
                        <td>&#9679; &nbsp; Fecha de conclusión de contrato</td>
                        <td style="text-align: right">{{ date('d', strtotime($contract_finish)) }} de {{ $months[intval(date('m', strtotime($contract_finish)))] }} de {{ date('Y', strtotime($contract_finish)) }}</td>
                    </tr>
                </table>

                <p>
                    Por otra parte, para la suscripción de contrato debe apersonarse por las oficinas de la Unidad Jurídica de la/el {{ $signature ? $signature->direccion_administrativa->nombre : 'Unidad de Contrataciones de Bienes y Servicios dependiente de la Secretaría Departamental Administrativa' }}, ubicada en {{ $signature ? $signature->direccion_administrativa->direccion : 'Edificio de Gobernación en Acera Sud de la Plaza Mariscal José Ballivián' }}, con un plazo no mayor a 48 horas a partir la fecha, debiendo presentar la siguiente documentación:
                </p>

                <table align="center">
                    <tr>
                        <td style="width: 500px;">
                            <table>
                                @if ($contract->certification_pac != "" && $contract->certification_pac != "N/C")
                                <tr>
                                    <td>&#10003;</td>
                                    <td>Certificado RUPE</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>&#10003;</td>
                                    <td>Certificado No Deudor a la Gobernación (original).</td>
                                </tr>
                                <tr>
                                    <td>&#10003;</td>
                                    <td>Fotocopia de C.I. legible.</td>
                                </tr>
                                <tr>
                                    <td>&#10003;</td>
                                    <td>Certificado de inscripción del NIT, con la actividad correspondiente a la consultoría</td>
                                </tr>
                                <tr>
                                    <td>&#10003;</td>
                                    <td>Registro AFP (CUA o NUA)</td>
                                </tr>
                                <tr>
                                    <td>&#10003;</td>
                                    <td>Certificado de No violencia (Emitido por la Magistratura)</td>
                                </tr>
                                <tr>
                                    <td>&#10003;</td>
                                    <td>Certificado de Antecedentes Penales - REJAP (Emitido por la Magistratura)</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <p>
                    Sin otro particular, lo saludo a usted con toda atención. <br> <br>
                    Atentamente,
                </p>
                <div class="table-signature">
                    <p style="text-align: center; width: 100%; font-size: 12px">
                        {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                        <b>{{ $signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA' }}</b>
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