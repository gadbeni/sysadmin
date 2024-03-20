@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@section('qr_code')
    <div id="qr_code" >
        @php
            $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $code = $contract->code;
            $signature = $contract->signature_alt ?? $contract->signature;
            if(!in_array($contract->direccion_administrativa_id, [55, 70, 71]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4, 5])){
                $signature = null;   
            }
            $qrcode = QrCode::size(70)->generate("MEMORANDUM DE PROCESO DE CONTRATACIÓN ".$contract->code." - CONSULTORÍA DE LÍNEA");
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
                    <small>N° {{ $code }}</small>
                </h2>
            </div>
            <div class="page-body">
                <table class="table-head" cellpadding="10">
                    <tr>
                        <td class="td-left">
                            <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->date_memo)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->date_memo)))]) }} de {{ date('Y', strtotime($contract->date_memo)) }}
                        </td>
                        <td class="td-right">
                            <b>DE:</b> {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                            <b>{{ setting('firma-autorizada.job-alt') }}</b> <br> <br>
                            @forelse ($contract->workers as $item)
                                @if (isset($item->person))
                                    <b>A:</b> {{ $item->person->first_name }} {{ $item->person->last_name }}<br>
                                    @if ($item->alternate_job->count() > 0)
                                        <b>{{ Str::upper($item->alternate_job->last()->name) }}</b> <br> <br>
                                    @else
                                        <b>{{ Str::upper($item->job ? $item->job->name : ($item->cargo ? $item->cargo->Descripcion : $item->job_description)) }}</b> <br> <br>    
                                    @endif
                                @else
                                    <b>A:</b> {{ str_replace('  ', ' ', $item->NombreCompleto) }} <br>
                                    <b>{{ $item->Cargo }}</b> <br> <br>
                                @endif
                            @empty
                                <b style="color: red">Debes Seleccionar a la comisión evaluadora</b>
                                <br> <br>
                            @endforelse
                            <b>REF.:</b> Designación Responsable de Evaluación
                        </td>
                    </tr>
                </table>
                <p>
                    De mi consideración: <br> <br>
                    En uso de las atribuciones que me confiere el Decreto Supremo 0181 y la Resolución Administrativa de Gobernación {{ $signature ? $signature->designation : setting('firma-autorizada.designation-alt') }}, de fecha {{ $signature ? $signature->designation_date ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date-alt') : setting('firma-autorizada.designation-date-alt') }}, bajo la Modalidad de Contratación Menor, designo a usted(es) como <b>RESPONSABLE(S) DE EVALUACIÓN</b>, el siguiente proceso de contratación: <br>
                </p>
                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Modalidad</th>
                            <th>Objeto</th>
                            <th>Programa/Proyecto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>GAD-BENI/{{ $code }}</td>
                            <td>CONTRATACIÓN MENOR</td>
                            <td>CONTRATACION DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }} {{ ($contract->name_job_alt ? ' - '.$contract->name_job_alt : '') }} {{ ($contract->work_location  ? ' PARA LA/EL '.$contract->work_location  : '') }}</td>
                            <td>{{ Str::upper($contract->program->name) }}</td>
                        </tr>
                    </tbody>
                </table>
                
                @if ($contract->direccion_administrativa->direcciones_tipo_id <= 2 )
                    <p>
                    La recepción de documentos se realizará en oficinas de la Unidad de Contrataciones de Bienes y Servicios dependiente de la Secretaría Departamental de Administración y Finanzas.    
                    </p>
                @endif

                <p>
                    Como Responsable de Evaluación, deberá cumplir las funciones establecidas en el artículo 38 del D.S. 0181, con dedicación exclusiva y no podrá delegar sus funciones ni excusarse, salvo en los casos de conflictos de intereses, impedimento físico o por las causales de excusa establecidas en el artículo 41 del mencionado decreto. <br> <br>
                    Atentamente,
                </p>

                <div style="margin-top: 80px">
                    <p style="text-align: center; width: 100%; font-size: 12px">
                        {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                        <b>{{ $signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA' }}</b> <br>
                    </p>
                </div>

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