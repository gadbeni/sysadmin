@extends('layouts.template-print')

@section('page_title', 'Presentaciòn de documentos')

@section('content')
    <div class="content">
        <div class="page-head">
            @php
                $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $code = $contract->code;
            @endphp
            <p>
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
                <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($contract->date_presentation)) }} de {{ $months[intval(date('m', strtotime($contract->date_presentation)))] }} de {{ date('Y', strtotime($contract->date_presentation)) }} <br>
            </p>
            <br>
            <p style="text-align: left">
                Señor: <br>
                {{ setting('firma-autorizada.name') }} <br>
                <b>RESPONSABLE DEL PROCESO DE CONTRATACIÓN DE APOYO NACIONAL A LA PRODUCCIÓN Y EMPLEO - RPA</b> <br>
                Presente. –
            </p>
        </div>
        <div class="page-title">
            <h3><u>REF: PRESENTACIÓN DE DOCUMENTOS PARA FORMALIZAR LA CONTRATACIÓN</u></h3>
        </div>
        <div class="page-body">
            <p>
                De mi consideración: <br> <br>
                En respuesta a nota NOT_CI/GAD BENI/MC N° {{ $code }} de {{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }} tengo a bien manifestar mi predisposición para el trabajo de Consultoría Individual de Línea para el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b>, del Proceso de Contratación <b>GAD-BENI/MC N° {{ $code }}</b>. <br> <br>
                Por lo que adjunto la siguiente documentación para la suscripción de contrato:
            </p>

            <table align="center">
                <tr>
                    <td style="width: 530px; font-size: 13px">
                        <b>&#10003;</b> &nbsp; Fotocopia de Cédula de Identidad vigente <br>
                        <b>&#10003;</b> &nbsp; NIT <br>
                        <b>&#10003;</b> &nbsp; Número de CUA o NUA y nombre de la Entidad donde realiza sus aportes de AFPs <br>
                        <b>&#10003;</b> &nbsp; Documentación original de respaldo (con fines de verificación) <br>
                    </td>
                </tr>
            </table>

            <p>
                Sin otro particular, lo saludo a usted con toda atención. <br> <br>
                Atentamente,
            </p>

            <div style="margin-top: 120px">
                <p style="text-align: center; width: 100%;">
                    {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                    C.I. {{ $contract->person->ci }}
                </p>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-head {
            text-align: right;
            padding-top: 10px;
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
            font-size: 14px;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection