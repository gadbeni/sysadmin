@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->finished ? $contract->finished->code : $contract->code.'-F';
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('MEMORANDUM DE AGRADECIMIENTO '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->finish)).' de '.$months[intval(date('m', strtotime($contract->finish)))].' de '.date('Y', strtotime($contract->finish)).' con un sueldo de '.number_format($contract->job->salary, 2, ',', '.').' Bs.');
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
            <div class="page-title">
                <h2>
                    <span style="color: #009A2F">MEMORANDUM</span> <br>
                    <small>GAD-BENI DRRHH N° {{ $code }}</small>
                </h2>
            </div>
            <div class="page-body">
                <table class="table-head" cellpadding="10">
                    <tr>
                        <td class="td-left">
                            <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->finish)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->finish)))]) }} de {{ date('Y', strtotime($contract->finish)) }} <br>
                        </td>
                        <td class="td-right">
                            <b>DE:</b> {{ Str::upper(setting('firma-autorizada.name')) }} <br>
                            <b>{{ Str::upper(setting('firma-autorizada.job')) }}</b> <br> <br> <br>
                            <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                            <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                        </td>
                    </tr>
                </table>
                <p style="text-align: center"><u><b>AGRADECIMIENTO</b></u></p>
                <p style="margin-top: 50px">
                    De mi consideración: <br><br><br>
                    A través de la presente, comunico a usted que a partir de la fecha de su recepción del presente memorándum, queda desvinculada del cargo de <b>{{ Str::upper($contract->job->name) }}</b>, dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>.
                </p>
                <p>
                    Por lo cual, deberá realizar la entrega bajo inventario los documentos, los activos asignados a su cargo a la responsable de Registro y Control de Bienes Públicos a efecto que le emitan su declaración de no custodio; asimismo, deberá realizar los trámites correspondientes para su obtención de saldo no deudor y declaración de Bienes y Rentas; dicha documentación deberá hacer llegar copia a RR.HH.
                </p>
                <br>
                <p>
                    Atentamente
                </p>
            </div>
        </div>
    @endif
@endsection

@section('css')
    <style>
        .page-title {
            text-align: center;
        }
        .page-title h2 {
            margin: 0px
        }
        .td-left {
            width: 50%;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
            vertical-align: bottom;
        }
        .td-right {
            width: 50%;
            border-left: 1px solid black;
            border-bottom: 1px solid black
        }
        .table-head {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px
        } 
        .page-body th{
            background-color: #d7d7d7
        }
        .page-body table{
            margin: 30px 0px;
            width: 100%;
            font-size: 12px;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection