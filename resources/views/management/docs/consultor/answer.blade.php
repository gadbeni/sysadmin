@extends('layouts.template-print')

@section('page_title', 'Respuesta')

@section('content')
    <div class="content">
        <div class="page-head">
            @php
                $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $code = $contract->code;
            @endphp
            <p style="font-size: 13px">
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
                <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($contract->date_response)) }} de {{ $months[intval(date('m', strtotime($contract->date_response)))] }} de {{ date('Y', strtotime($contract->date_response)) }}
            </p>
            <br>
            <p style="text-align: left; font-size: 13px">
                Señor: <br>
                {{ setting('firma-autorizada.name') }} <br>
                <b>{{ setting('firma-autorizada.job-alt') }}</b> <br>
                Presente. –
            </p>
        </div>
        <div class="page-title">
            <h3><u>REF.: RESPUESTA A INVITACIÓN</u></h3>
        </div>
        <div class="page-body">
            <p>
                De mi consideración: <br> <br>
                Dando respuesta a su oficio <b>INV/CI/GAD-BENI/MC N° {{ $code }}</b> del {{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }}, donde su autoridad me invita a presentar Currículum Vitae, para optar al servicio de un Consultor Individual de línea cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, con cargo al Programa: <b>“{{ Str::upper($contract->program->name) }}”</b>. En tal sentido, hago llegar a su Autoridad mi correspondiente Currículum Vitae debidamente respaldado y Declaración Jurada de No Incompatibilidad, mismo que remito dentro del plazo establecido para tal efecto. <br> <br>
                No dudando de su gentil deferencia, saludo a usted con las mayores consideraciones de respeto. <br> <br>
                Fraternalmente,
            </p>

            <div style="margin-top: 120px">
                <p style="text-align: center; width: 100%; font-size: 12px">
                    {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                    C.I. {{ $contract->person->ci }} <br>
                    <b>POSTULANTE</b>
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
            padding-top: 10px;
        }
        .page-title {
            padding: 0px 50px;
            text-align: center;
            padding-top: 10px;
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