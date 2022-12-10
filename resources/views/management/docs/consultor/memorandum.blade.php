@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@section('content')
    <div class="content">
        @php
            $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $code = $contract->code;
            if(!in_array($contract->direccion_administrativa_id, [13, 48, 55]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4])){
                $signature = null;   
            }
        @endphp
        <div class="page-title">
            <h2>
                <span style="color: #009A2F">MEMORANDUM</span> <br>
                <small>S.D.A.F./R.E.C. N° {{ $code }}</small>
            </h2>
        </div>
        <div class="page-body">
            <div class="page-head" style="width: 100%">
                <div class="border-right">
                    <p style="position:absolute; bottom: 10px">
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
                        <span id="label-location">Santísima Trinidad</span>, {{ date('d', strtotime($contract->date_memo)) }} de {{ $months[intval(date('m', strtotime($contract->date_memo)))] }} de {{ date('Y', strtotime($contract->date_memo)) }}
                    </p>
                </div>
                <div class="border-left">
                    <b>DE:</b> {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                    <b>{{ setting('firma-autorizada.job-alt') }}</b> <br> <br>
                    @forelse ($contract->workers as $item)
                        @if (isset($item->person))
                            <b>A:</b> {{ $item->person->first_name }} {{ $item->person->last_name }}<br>
                            @if ($item->alternate_job->count() > 0)
                                <b>{{ Str::upper($item->alternate_job->last()->name) }}</b> <br> <br>
                            @else
                                <b>{{ Str::upper($item->job ? $item->job->name : $item->cargo->Descripcion) }}</b> <br> <br>    
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
                </div>
            </div>
            <br>
            <p>
                De mi consideración: <br> <br>
                En uso de las atribuciones que me confiere el Decreto Supremo 0181 y la Resolución Administrativa de Gobernación {{ $signature ? $signature->designation : setting('firma-autorizada.designation') }}, de fecha {{ $signature ? $signature->designation_date ? date('d', strtotime($signature->designation_date)).' de '.$months[intval(date('m', strtotime($signature->designation_date)))].' de '.date('Y', strtotime($signature->designation_date)) : setting('firma-autorizada.designation-date') : setting('firma-autorizada.designation-date') }}, bajo la Modalidad de Contratación Menor, transfiero a ustedes como <b>RESPONSABLE DE EVALUACIÓN</b>, el siguiente proceso de contratación: <br>
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
                        <td>GAD - BENI / MC N° {{ $code }}</td>
                        <td>CONTRATACIÓN MENOR</td>
                        <td>CONTRATACION DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO {{ Str::upper($contract->cargo->Descripcion) }}</td>
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
                    <b>{{ setting('firma-autorizada.job-alt') }}</b> <br>
                </p>
            </div>

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