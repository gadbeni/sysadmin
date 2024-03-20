@extends('layouts.template-print')

@section('page_title', 'Invitación')

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
            $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $code = $contract->code;
            $signature = $contract->signature_alt ?? $contract->signature;
            if(!in_array($contract->direccion_administrativa_id, [55, 70, 71]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4, 5])){
                $signature = null;   
            }
            $qrcode = QrCode::size(70)->generate("INVITACIÓN A PRESENTAR PROPUESTA - PROCESO DE CONTRATACIÓN GAD-BENI/".$contract->code." - CONSULTORÍA DE LÍNEA");
        @endphp
        {!! $qrcode !!}
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
                <p style="font-size: 13px">
                    <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->date_invitation)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->date_invitation)))]) }} de {{ date('Y', strtotime($contract->date_invitation)) }}<br>
                    <b>INV/GAD-BENI/{{ $code }}</b>
                </p>
                <br>
                <p style="text-align: left">
                    {{ $contract->person->gender == 'masculino' ? 'Señor' : 'Señora' }}: <br>
                    {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                    CI: {{ $contract->person->ci }} - {!! $contract->person->phone ? 'Cel. '.$contract->person->phone.'<br>' : '' !!}
                    Presente.-
                </p>
            </div>
            <div class="page-title">
                <h3><u>REF.: INVITACIÓN A PRESENTAR PROPUESTA - PROCESO DE CONTRATACIÓN GAD-BENI/{{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}”</u></h3>
            </div>
            <div class="page-body">
                @php
                    $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                    $numeros_a_letras = new NumeroALetras();
                @endphp
                <p>De mi consideración:</p>
                <p>El Gobierno Autónomo Departamental del Beni, a través de la/el {{ $signature ? $signature->direccion_administrativa->nombre : 'Secretaría de Administración y Finanzas' }}, requiere contratar los servicios de un Consultor Individual de línea para el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}</b> cargado al {{ Str::upper($contract->program->class) }} <b>“{{ Str::upper($contract->program->name) }}”</b>.</p>
                <p><b>PRECIO REFERENCIAL:</b> monto mensual de Bs. {{ number_format($salary, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice($salary, 2, 'Bolivianos') }})</p>
                <p><b>PLAZO DE PRESTACIÓN DE SERVICIO:</b> El consultor prestará el servicio a partir del dia siguiente hábil de la suscripción de la suscripción del contrato hasta el {{ date('d', strtotime($contract_finish)) }} de {{ $months[intval(date('m', strtotime($contract_finish)))] }} de {{ date('Y', strtotime($contract_finish)) }}.</p>
                <p><b>DEPENDENCIA DEL CONSULTOR:</b> el consultor estará bajo la dependencia directa de la/el {{ Str::upper($contract->unidad_administrativa->nombre) }}.</p>
                <p><b>FORMA DE PAGO:</b> la cancelación se realizará en pagos mensuales, en moneda nacional, previa presentación de informes, solicitud de pago por parte del adjudicatario y debidamente aprobado por la unidad solicitante</p>
                <p>En tal sentido, <b><u>le invito</u></b> presentar su Currículum Vitae debidamente documentado y Declaración Jurada de No Incompatibilidad, a ser presentado en la oficina de la Unidad de Contrataciones de bienes y servicios, dependiente de la/el {{ $signature ? ($signature->direccion_administrativa->direcciones_tipo_id == 1 ? 'SECRETARIA DEPARTAMENTAL DE ADMINISTRACION Y FINANZAS' : $signature->direccion_administrativa->nombre) : 'SECRETARIA DEPARTAMENTAL DE ADMINISTRACION Y FINANZAS' }}, ubicada en {{ $signature ? $signature->direccion_administrativa->direccion : 'Edificio de Gobernación en Acera Sud de la Plaza Mariscal José Ballivián' }}, hasta el día {{ date('d', strtotime($contract->date_limit_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_limit_invitation)))] }} de {{ date('Y', strtotime($contract->date_limit_invitation)) }}.</p> <br>
                <p>Adjunto Términos de Referencia y Formato de Declaración Jurada de No Incompatibilidad</p> <br>
                <p>Sin otro particular, lo saludo a usted con toda atención.</p> <br>

                <div style="margin-top: 120px">
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
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection