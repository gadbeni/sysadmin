@extends('layouts.template-print')

@section('page_title', 'Rotación')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = 'RT-'.str_pad($rotation->id, 4, "0", STR_PAD_LEFT);
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Rotación '.$code.' de '.$rotation->contract->person->first_name.' '.$rotation->contract->person->last_name.' con C.I. '.$rotation->contract->person->ci.', en fecha '.date('d', strtotime($rotation->date)).' de '.$months[intval(date('m', strtotime($rotation->date)))].' de '.date('Y', strtotime($rotation->date)).' a la '.Str::upper($rotation->destiny_dependency)); !!}
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
            <div class="page-head" style="width: 100%">
                <div class="border-right">
                    <p style="position:absolute; bottom: 10px">
                        <select id="location-id">
                            @foreach (App\Models\City::where('states_id', 1)->where('deleted_at', NULL)->get() as $item)
                            <option @if($item->name == $contract->direccion_administrativa->city->name) selected @endif value="{{ Str::upper($item->name) }}">{{ Str::upper($item->name) }}</option>
                            @endforeach
                        </select>
                        <span id="label-location">SANTISIMA TRINIDAD</span>, {{ date('d', strtotime($contract->start)) }} de {{ $months[intval(date('m', strtotime($contract->start)))] }} de {{ date('Y', strtotime($contract->start)) }}
                    </p>
                </div>
                <div class="border-left">
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
                </div>
            </div>
            <br>
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
            padding: 0px 34px;
            text-align: center;
            padding-top: 100px;
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
            font-size: 14px;
        }
        .content {
            padding: 0px 34px;
            font-size: 13px;
        }
        .page-head{
            display: flex;
            flex-direction: row;
            width: 100%;
            /* height: 100px; */
            border-bottom: 2px solid #000;
        }
        .border-right{
            position: relative;
            padding: 10px;
            width: 50%;
            border-right: 1px solid black
        }
        .border-left{
            padding: 10px;
            width: 50%;
            border-left: 1px solid black
        }
        .page-body th{
            background-color: #d7d7d7
        }
        .page-body table{
            /* text-align: center; */
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