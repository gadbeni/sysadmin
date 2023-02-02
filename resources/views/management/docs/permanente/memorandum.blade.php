@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    if(
        $contract->direccion_administrativa->direcciones_tipo_id != 3 &&
        $contract->direccion_administrativa->direcciones_tipo_id != 4 &&
        $contract->direccion_administrativa_id != 5 &&
        $contract->direccion_administrativa_id != 48 &&
        $contract->direccion_administrativa_id != 53
    ){
        $signature = null;
    }
@endphp

@section('qr_code')
    <div id="qr_code">
        {!! QrCode::size(80)->generate('Personal permanente '.$code.' '.$contract->person->first_name.' '.$contract->person->last_name.' con C.I. '.$contract->person->ci.', del '.date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)).' con un sueldo de '.number_format($contract->job->salary, 2, ',', '.').' Bs.'); !!}
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="page-title">
            <h2>
                <span style="color: #009A2F">MEMORANDUM</span> <br>
                <small>GAD-BENI DRRHH N° {{ $code }}</small>
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
                    <b>DE:</b> {{ Str::upper($signature ? $signature->name : setting('firma-autorizada.name')) }} <br>
                    <b>{{ Str::upper($signature ? $signature->job : setting('firma-autorizada.job')) }}</b> <br> <br> <br>
                    <b>A:</b> {{ Str::upper($contract->person->first_name.' '.$contract->person->last_name) }} <br>
                    <b>CI: {{ $contract->person->ci }}</b> <br> <br>
                </div>
            </div>
            <br>
            <p style="text-align: center"><u><b>DESIGNACIÓN</b></u></p>
            <p>
                El Gobierno Autónomo Departamental del Beni - GAD-BENI, comunica a Usted que a partir de la fecha ha sido {{ $contract->person->gender == 'masculino' ? 'designado' : 'designada' }} para ejercer el cargo de <b>{{ Str::upper($contract->job->name) }}</b>, dependiente de la/el <b>{{ Str::upper($contract->direccion_administrativa->nombre) }}</b>.
            </p>
            <p>
                Su remuneración será cancelada con cargo a la partida {{ $contract->program->number }}, con un haber mensual de <b>Bs. {{ NumerosEnLetras::convertir($contract->job->salary, 'Bolivianos', true) }}</b>, en el item N&deg; {{ $contract->job->item }} con el nivel salarial <b>{{ $contract->job->level }}</b>, debiendo coordinar con la Dirección Administrativa Financiera para la asignación de activo fijos, manuales y reglamentos que rigen en la entidad.
            </p>
            <p>
                Al desearle éxito en sus funciones, le hacemos conocer que los desarrollos de sus actividades se supeditaran al POAI, instructivos, circulares, memorándums, reglamentos específicos y manuales de procedimientos vigentes del <b>Gobierno Autónomo Departamental del Beni</b> (GAD-BENI), disposiciones con la función pública y otras funciones que fueran asignadas por su inmediato superior.
            </p>
            <p>
                Se le recuerda, efectuar su Declaración Jurada de Bienes y Rentas ante la Contraloría General del Estado de conformidad al Decreto Supremo N°1233.
            </p>
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