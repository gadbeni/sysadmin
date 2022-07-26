@extends('layouts.template-print')

@section('page_title', 'Nota de adjudicación')

@section('content')
    <div class="content">
        <div class="page-head">
            @php
                $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $code = $contract->code;
                if($contract->direccion_administrativa_id != 55 && $contract->direccion_administrativa_id != 13 && $contract->direccion_administrativa->direcciones_tipo_id != 3 && $contract->direccion_administrativa->direcciones_tipo_id != 4){
                    $signature = null;   
                }
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
                <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($contract->date_note)) }} de {{ $months[intval(date('m', strtotime($contract->date_note)))] }} de {{ date('Y', strtotime($contract->date_note)) }} <br>
                <b>INV/CI/GAD BENI/MCD N° {{ $code }}</b>
            </p>
            <br>
            <p style="text-align: left">
                {{ $contract->person->gender == 'masculino' ? 'Señor' : 'Señora' }}: <br>
                {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                {!! $contract->person->phone ? 'Cel. '.$contract->person->phone.'<br>' : '' !!}
                Presente .–
            </p>
        </div>
        <div class="page-title">
            <h3><u>REF: NOTA DE ADJUDICACIÓN</u></h3>
        </div>
        <div class="page-body">
            @php
                $contract_duration = contract_duration_calculate($contract->start, $contract->finish);
                $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                $periodo = '';
                if($contract_duration->months > 0){
                    if($contract_duration->months == 1){
                        $periodo .= NumerosEnLetras::convertir($contract_duration->months).' mes';
                    }else{
                        $periodo .= NumerosEnLetras::convertir($contract_duration->months).' meses';
                    }
                }
                if($contract_duration->days > 0){
                    if($contract_duration->days == 1){
                        $periodo .= ' y '.NumerosEnLetras::convertir($contract_duration->days).' día';
                    }else{
                        $periodo .= ' y '.NumerosEnLetras::convertir($contract_duration->days).' días';
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
                    <td style="text-align: right">{{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}</td>
                </tr>
            </table>

            <p>
                Por otra parte, para la suscripción de contrato debe apersonarse por las oficinas de la Unidad de Contrataciones de Bienes y Servicios dependiente de la Secretaría Departamental Administrativa, ubicada en edificio ex CORDEBENI Calle Cochabamba y Joaquín de Sierra, con un plazo no mayor a 48 horas a partir la fecha, debiendo presentar la siguiente documentación:
            </p>

            <table align="center">
                <tr>
                    <td style="width: 500px;">
                        <table>
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
                            <tr>
                                <td>&#10003;</td>
                                <td>Libreta de Servicio Militar (para varones)</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <p>
                Sin otro particular, lo saludo a usted con toda atención. <br> <br>
                Atentamente,
            </p>

            <div style="margin-top: 80px">
                <p style="text-align: center; width: 100%; font-size: 12px">
                    {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                    <b>{{ setting('firma-autorizada.job-alt') }}</b>
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