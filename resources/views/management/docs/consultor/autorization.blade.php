@extends('layouts.template-print')

@section('page_title', 'Autorización')

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
            $qrcode = QrCode::size(70)->generate("AUTORIZACIÓN DE INICIO DE PROCESO DE CONTRATACIÓN ".$contract->code." - CONSULTORÍA DE LÍNEA");
            $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $days = array('', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
            $code = $contract->code;
            $signature = $contract->signature_alt ?? $contract->signature;
            if(!in_array($contract->direccion_administrativa_id, [5, 48]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4])){
                $signature = null;   
            }
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
        <div class="content" style="position: relative">
            @php
                $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $days = array('', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
                $code = $contract->code;
                $signature = $contract->signature;
                if(!in_array($contract->direccion_administrativa_id, [8, 42, 61]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4])){
                    $signature = null;   
                }
            @endphp
            <div class="page-title">
                <h3>AUTORIZACIÓN DE INICIO DE PROCESO DE CONTRATACIÓN</h3>
            </div>
            <div class="page-body">
                <p>
                    En el marco del D.S. N° 0181, Normas Básicas del Sistema de Administración de Bienes y Servicios, La Resolución Administrativa de Gobernación {{ $signature ? $signature->designation : setting('firma-autorizada.designation-alt') }}, de fecha {{ $signature ? $signature->designation_date ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date-alt') : setting('firma-autorizada.designation-date-alt') }}, que designa al RESPONSABLE DEL PROCESO DE CONTRATACIÓN DE APOYO NACIONAL A LA PRODUCCIÓN Y EMPLEO – RPA, AUTORIZÓ el inicio del proceso de contratación, de acuerdo al siguiente detalle:
                </p>

                <table width="100%" style="margin-top: 20px" cellspacing="5">
                    <tr>
                        <td width="30px">1.</td>
                        <td width="270px">Objeto de la contratación:</td>
                        <td style="border: 1px solid black; padding: 5px">
                            <b>CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Código interno del proceso de contratación:</td>
                        <td style="border: 1px solid black; padding: 5px">
                            <b>GAD-BENI/{{ $code }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Fecha de invitación:</td>
                        <td style="border: 1px solid black; padding: 5px">
                            <b>{{ $days[date('w', strtotime($contract->date_invitation))] }}, {{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Certificación POA:</td>
                        <td style="border: 1px solid black; padding: 5px">
                            <b>{!! $contract->certification_poa ?? '&nbsp;' !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Certificación PAC:</td>
                        <td style="border: 1px solid black; padding: 5px">
                            <b>{!! $contract->certification_pac ?? '&nbsp;' !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Certificación Presupuestaria:</td>
                        <td style="border: 1px solid black; padding: 5px">
                            <b>&#10003;</b>
                        </td>
                    </tr>
                    @php
                        $contract_duration = contract_duration_calculate($contract->start, $contract_finish);
                        $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                        $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                    @endphp
                    <tr>
                        <td>7.</td>
                        <td>Precio Referencial en Bs.:</td>
                        <td style="border: 1px solid black; padding: 5px">
                            @php
                                $numeros_a_letras = new NumeroALetras();
                            @endphp
                            <b>{{ number_format($total, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice(number_format($total, 2, '.', ''), 2, 'Bolivianos') }})</b>
                        </td>
                    </tr>
                </table>
                <br><br>
                <p>
                    El proceso de contratación anteriormente señalado debe llevarse en estricto cumplimiento a la normativa en vigencia. <br>
                    (*) El precio referencial corresponde al monto de la Certificación presupuestaria, mismo que puede variar al iniciarse el proceso y al elaborarse el contrato de acuerdo a los meses y días de la relación contractual a suscribirse.
                </p>

                <div style="margin-top: 120px">
                    <p style="text-align: center; width: 100%; font-size: 12px">
                        {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                        <b>{{ $signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA' }}</b>
                    </p>
                </div>
            </div>
            <p style="position: absolute; bottom: 10px; left: 0px">
                <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->date_autorization)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->date_autorization)))]) }} de {{ date('Y', strtotime($contract->date_autorization)) }} <br>
                <small>
                    <i>Cc/arch.</i> <br>
                    {{ Auth::user()->name }}
                </small>
            </p>
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