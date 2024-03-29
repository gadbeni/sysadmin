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
                    @foreach (App\Models\City::where('states_id', 1)->where('deleted_at', NULL)->get() as $item)
                    <option @if($item->name == $contract->direccion_administrativa->city->name) selected @endif value="{{ Str::upper($item->name) }}">{{ Str::upper($item->name) }}</option>
                    @endforeach
                </select>
                <span id="label-location">SANTISIMA TRINIDAD</span>, {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}
            </p>
            </p>
            <br>
            <p style="text-align: left">
                Señor: <br>
                {{ setting('firma-autorizada.name') }} <br>
                <b>{{ setting('firma-autorizada.job-alt') }}</b> <br>
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