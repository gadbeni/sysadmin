@extends('layouts.template-print')

@section('page_title', 'Rotación')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = 'RT-'.str_pad($rotation->id, 4, "0", STR_PAD_LEFT);
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(70)->generate('MEMORANDUM DE ROTACIÓN '.$code.' de '.$rotation->contract->person->first_name.' '.$rotation->contract->person->last_name.' con C.I. '.$rotation->contract->person->ci.', en fecha '.date('d', strtotime($rotation->date)).' de '.$months[intval(date('m', strtotime($rotation->date)))].' de '.date('Y', strtotime($rotation->date)).' a la '.Str::upper($rotation->destiny_dependency));
        @endphp
        @if ($contract->files->count() > 0)
            <img src="data:image/png;base64, {!! base64_encode($qrcode) !!}">
        @else
            {!! $qrcode !!}
        @endif
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="page-title">
            <h2>
                <span style="color: #009A2F">MEMORANDUM</span> <br>
                <small>GAD-BENI SDAF N° {{ $code }}</small>
            </h2>
        </div>
        <div class="page-body">
            <table class="table-head" cellpadding="10">
                <tr>
                    <td class="td-left">
                        <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($rotation->date)) }} de {{ Str::upper($months[intval(date('m', strtotime($rotation->date)))]) }} de {{ date('Y', strtotime($rotation->date)) }} <br>
                    </td>
                    <td class="td-right">
                        <table>
                            <tr>
                                <td><b>DE:</b> <br> <br></td>
                                <td>
                                    {{ Str::upper($rotation->responsible->first_name.' '.$rotation->responsible->last_name) }} <br>
                                    <b>{{ Str::upper($rotation->responsible_job) }}</b>
                                </td>
                            </tr>
                            <tr><td><br> <br></td></tr>
                            <tr>
                                <td><b>A:</b> <br> <br></td>
                                <td>
                                    {{ Str::upper($rotation->contract->person->first_name.' '.$rotation->contract->person->last_name) }} <br>
                                    <b>{{ $rotation->contract->cargo ? $rotation->contract->cargo->Descripcion : $rotation->contract->job->name }} <br> {{ Str::upper($rotation->contract->direccion_administrativa->nombre) }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <p style="text-align: center"><u><b>ROTACIÓN</b></u></p>
            <br>
            <p>
                En aplicación del art. 30 del D.S. 26115 
                @if ($rotation->contract->procedure_type_id == 5)
                y cláusula sexta (condiciones) del contrato administrativo de personal eventual suscrito con la GAD BENI,    
                @endif
                 así como el  Art. 11 del Reglamento Interno de Personal: <b>ROTACIÓN Y MOVILIDAD DE PERSONAL</b>, se le comunica que a requerimiento del o la <b>{{ Str::upper($rotation->destiny_job) }}</b>, Usted a partir de la fecha está siendo rotado a la <b>{{ Str::upper($rotation->destiny_dependency) }}</b>, debiendo presentarse inmediatamente ante él o la RESPONSABLE de dicha dependencia, a objeto de recibir las respectivas instrucciones. Dejando claramente establecido que la presente rotación, no afectara su remuneración y/o nivel salarial
                @if ($rotation->contract->procedure_type_id == 5)
                 , ni las condiciones establecidas en el contrato suscrito
                @endif
                .
            </p>
            <p>
                Asimismo, hacerle conocer que la presente rotación, no conlleva el traslado mobiliario, equipo y otros enseres del Área de origen.
            </p>
            <p>
                Deseándole éxito en el desempeño de sus funciones y responsabilidades que devengan de la prestación de sus servicios, y esperando contar con su valioso aporte y participación en el logro de los objetivos del Gobierno Autónomo Departamental del Beni. 
            </p>
            <p>Saludo a usted atentamente.</p>
        </div>
    </div>
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