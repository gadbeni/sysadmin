@extends('layouts.template-print')

@section('page_title', 'Invitación')
{{-- {{ $contract }} --}}
@section('content')
    <div class="content">
        <div class="page-head">
            @php
                $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                $code = str_pad($contract->code, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($contract->start));
            @endphp
            <p style="font-size: 13px">
                Santísima Trinidad, {{ date('d', strtotime($contract->date_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_invitation)))] }} de {{ date('Y', strtotime($contract->date_invitation)) }} <br>
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
            <h3><u>REF.: INVITACIÓN A PRESENTAR PROPUESTA - PROCESO DE CONTRATACIÓN GAD-BENI/MC N° {{ $code }} “CONTRATACIÓN DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}”</u></h3>
        </div>
        <div class="page-body">
            <p>
                De mi consideración: <br>
                El Gobierno Autónomo Departamental del Beni, a través de la Secretaria Departamental de Administración y Finanzas, requiere contratar los servicios de un Consultor Individual de línea para el cargo de <b>{{ Str::upper($contract->cargo->Descripcion) }}</b> cargado al programa <b>“{{ Str::upper($contract->program->name) }}”</b>. <br>
                <b>PRECIO REFERENCIAL:</b> monto mensual de Bs.- {{ NumerosEnLetras::convertir($contract->cargo->nivel->Sueldo, 'Bolivianos', true) }} <br>
                <b>PLAZO DE PRESTACIÓN DE SERVICIO:</b> El consultor prestará el servicio a partir del siguiente día hábil de la firma de contrato hasta el {{ date('d', strtotime($contract->finish)) }} de {{ $months[intval(date('m', strtotime($contract->finish)))] }} de {{ date('Y', strtotime($contract->finish)) }}. <br>
                <b>DEPENDENCIA DEL CONSULTOR:</b> el consultor estará bajo la dependencia directa de la/el {{ Str::upper($contract->unidad_administrativa->NOMBRE) }}. <br>
                <b>FORMA DE PAGO:</b> la cancelación se realizará en pagos mensuales, en moneda nacional, previa presentación de informes, solicitud de pago por parte del adjudicatario y debidamente aprobado por la unidad solicitante. <br>
                En tal sentido, <b><u>le invito</u></b> presentar su Currículum Vitae debidamente documentado y Declaración Jurada de No Incompatibilidad, a ser presentado en la oficina de la Dirección Administrativa, dependiente de la Secretaria Dptal de Administración Finanzas, ubicada en el Edificio Central, Plaza Principal “Mcal. José Ballivián” Acera Sur, hasta el día {{ date('d', strtotime($contract->date_limit_invitation)) }} de {{ $months[intval(date('m', strtotime($contract->date_limit_invitation)))] }} de {{ date('Y', strtotime($contract->date_limit_invitation)) }}. <br><br>
                Adjunto Términos de Referencia y Formato de Declaración Jurada de No Incompatibilidad <br><br>
                Sin otro particular, lo saludo a usted con toda atención.
            </p>

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
            text-align: right;
            padding-top: 10px;
        }
        .page-title {
            padding: 0px 50px;
            text-align: justify;
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