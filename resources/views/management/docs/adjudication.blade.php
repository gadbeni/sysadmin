@extends('layouts.template-print')

@section('page_title', 'Invitación')

@section('content')
    <div class="content">
        <div class="page-head">
            @php
                $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $code = $contract->number.'/'.date('Y', strtotime($contract->start));
            @endphp
            <p>
                Santísima Trinidad, {{ date('d', strtotime($contract->date_note)) }} de {{ $months[intval(date('m', strtotime($contract->date_note)))] }} de {{ date('Y', strtotime($contract->date_note)) }} <br>
                <b>INV/CI/GAD BENI/MCD N° 190/2021</b>
            </p>
            <br>
            <p style="text-align: left">
                {{ $contract->person->gender == 'masculino' ? 'Señor' : 'Señora' }}: <br>
                {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                Cel. {{ $contract->person->phone }} <br>
                Presente .–
            </p>
        </div>
        <div class="page-title">
            <h3><u>REF: NOTA DE ADJUDICACIÓN</u></h3>
        </div>
        <div class="page-body">
            @php
                $start = Carbon\Carbon::parse($contract->start);
                $finish = Carbon\Carbon::parse($contract->finish);
                $count_months = 0;
                while ($start <= $finish) {
                    $count_months++;
                    $start->addMonth();
                }
                $count_months--;
                $count_days = $start->subMonth()->diffInDays($finish);
                $periodo = '';
                if($count_months > 0){
                    if($count_months == 1){
                        $periodo .= NumerosEnLetras::convertir($count_months).' mes';
                    }else{
                        $periodo .= NumerosEnLetras::convertir($count_months).' meses';
                    }
                }
                if($count_days > 0){
                    if($count_days == 1){
                        $periodo .= ' y '.NumerosEnLetras::convertir($count_days).' día';
                    }else{
                        $periodo .= ' y '.NumerosEnLetras::convertir($count_days).' días';
                    }
                }
            @endphp
            <p>
                De mi consideración: <br> <br>
                En el marco del D.S. 0181 del 28 de junio de 2009  y sus decretos modificatorios, comunico a usted que una vez concluido el proceso de Evaluación a su postulación y conformidad a los Términos de Referencia, en base a la recomendación por el Responsable de Evaluación, se ha resuelto <b>ADJUDICAR</b> a su persona para que preste los servicios de Consultor en Línea del Proceso <b>GAD-BENI/MC N° {{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}”</b>, con cargo al Programa: <b>“{{ Str::upper($contract->program->name) }}”</b>. por  un plazo de {{ $periodo }}, de acuerdo al siguiente detalle: <br>
            </p>

            <table align="center">
                <tr>
                    <td style="width: 320px;">&#9679; &nbsp; Monto mensual</td>
                    <td style="width: 180px; text-align: right">Bs.- &nbsp;&nbsp; {{ number_format($contract->salary, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>&#9679; &nbsp; Monto total adjudicado</td>
                    <td style="text-align: right">Bs.- &nbsp;&nbsp; {{ number_format(($contract->salary *$count_months) + (($contract->salary /30) *$count_days), 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>&#9679; &nbsp; Fecha de inicio de contrato</td>
                    <td style="text-align: right">{{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}</td>
                </tr>
                <tr>
                    <td>&#9679; &nbsp; Fecha de conclusión de contrato</td>
                    <td style="text-align: right">{{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}</td>
                </tr>
            </table>

            <p>
                Por otra parte, para la suscripción de contrato debe apersonarse por las oficinas de la Dirección Administrativa dependiente de la Secretaría Dptal. de Administración y Finanzas, ubicada en la Plaza Principal, hasta el día {{ date('d', strtotime($contract->date_limit_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_limit_invitation)))] }} de {{ date('Y', strtotime($contract->date_limit_invitation)) }} , deberá presentar la siguiente documentación:
            </p>

            <table align="center">
                <tr>
                    <td style="width: 500px;">
                        <b>&#10003;</b> &nbsp; Nota de predisposición para el trabajo de consultoría <br>
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

            <div style="margin-top: 80px">
                <p style="text-align: center; width: 100%; font-size: 12px">
                    Lic. Geisel Marcelo Oliva Ruiz <br>
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
            font-size: 13px;
        }
        .content {
            padding: 0px 34px;
            font-size: 13px;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection