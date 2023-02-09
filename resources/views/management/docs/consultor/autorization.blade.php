@extends('layouts.template-print')

@section('page_title', 'Autorización')

@section('content')
    <div class="content">
        @php
            $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $days = array('', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
            $code = $contract->code;
            if(!in_array($contract->direccion_administrativa_id, [13, 48, 55]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4])){
                $signature = null;   
            }
        @endphp
        <div class="page-title">
            <h2>AUTORIZACIÓN DE INICIO DE PROCESO DE CONTRATACIÓN</h2>
        </div>
        <div class="page-body">
            <p>
                En el marco del D.S. N° 0181, Normas Básicas del Sistema de Administración de Bienes y Servicios, La Resolución Administrativa de Gobernación {{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}, de fecha {{ $signature ? $signature->designation_date ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date') : setting('firma-autorizada.designation-date') }}, que designa al RESPONSABLE DEL PROCESO DE CONTRATACIÓN DE APOYO NACIONAL A LA PRODUCCIÓN Y EMPLEO – RPA, AUTORIZÓ el inicio del proceso de contratación, de acuerdo al siguiente detalle:
            </p>

            <table width="100%" style="margin-top: 20px" cellspacing="5">
                <tr>
                    <td width="30px">1.</td>
                    <td width="270px">Objeto de la contratación:</td>
                    <td style="border: 1px solid black; padding: 5px">
                        <b>CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}</b>
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Código interno del proceso de contratación:</td>
                    <td style="border: 1px solid black; padding: 5px">
                        <b>GAD-BENI/MCD N&deg; {{ $code }}</b>
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
                    $contract_duration = contract_duration_calculate($contract->start, $contract->finish);
                    $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                    $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                @endphp
                <tr>
                    <td>7.</td>
                    <td>Precio Referencial en Bs.:</td>
                    <td style="border: 1px solid black; padding: 5px">
                        <b>Bs. {{ NumerosEnLetras::convertir(number_format($total, 2, '.', ''), 'Bolivianos', true) }}</b>
                    </td>
                </tr>
            </table>
            <br><br>
            <p>
                El proceso de contratación anteriormente señalado debe llevarse en estricto cumplimiento a la normativa en vigencia. <br>
                (*) El precio referencial corresponde al monto de la Certificación presupuestaria, mismo que puede variar al iniciarse el proceso y al elaborarse el contrato de acuerdo a los meses y días de la relación contractual a suscribirse.
            </p>

            <div style="margin-top: 100px">
                <p style="text-align: center; width: 100%; font-size: 12px">
                    {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                    <b>{{ setting('firma-autorizada.job-alt') }}</b>
                </p>
            </div>

            <p style="margin-top: 80px">
                <select id="location-id">
                    @foreach (App\Models\City::where('states_id', 1)->where('deleted_at', NULL)->get() as $item)
                    <option @if($item->name == $contract->direccion_administrativa->city->name) selected @endif value="{{ Str::upper($item->name) }}">{{ Str::upper($item->name) }}</option>
                    @endforeach
                </select>
                <span id="label-location">SANTISIMA TRINIDAD</span>, {{ date('d', strtotime($contract->date_autorization)) }} de {{ $months[intval(date('m', strtotime($contract->date_autorization)))] }} de {{ date('Y', strtotime($contract->date_autorization)) }} <br>
                <small><i>Cc/arch.</i></small>
            </p>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-title {
            padding-top: 60px;
            text-align: center
        }
        .page-body{
            padding: 0px 30px;
            padding-top: 10px;
        }
        .page-body p{
            text-align: justify;
            font-size: 13px;
        }
        .content {
            padding: 0px 34px;
            font-size: 12px;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection