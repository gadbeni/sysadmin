@extends('layouts.template-print')

@section('page_title', 'Declaración jurada')

@section('qr_code')
    <div id="qr_code" >
        @php
            $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $code = $contract->code;
            $signature = $contract->signature_alt ?? $contract->signature;
            if(!in_array($contract->direccion_administrativa_id, [55, 70, 71]) && !in_array($contract->direccion_administrativa->direcciones_tipo_id, [3, 4, 5])){
                $signature = null;   
            }
            $qrcode = QrCode::size(70)->generate("DECLARACIÓN JURADA DE NO INCOMPATIBILIDAD LEGAL ".$contract->code." - CONSULTORÍA DE LÍNEA");
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
            <div class="page-head">
                <div class="page-title">
                    <h3>DECLARACIÓN JURADA DE NO INCOMPATIBILIDAD LEGAL</h3>
                </div>
                <br>
                <p>
                    <span>{{ Str::upper($contract->direccion_administrativa->city ? $contract->direccion_administrativa->city->name : 'Santísima Trinidad') }}</span>, {{ date('d', strtotime($contract->date_statement)) }} de {{ Str::upper($months[intval(date('m', strtotime($contract->date_statement)))]) }} de {{ date('Y', strtotime($contract->date_statement)) }}
                </p>
                <p style="text-align: left">
                    Señor(a): <br>
                    {{ $signature ? $signature->name : setting('firma-autorizada.name') }} <br>
                    <b>{{ $signature ? $signature->job : 'Responsable de Proceso de Contratación de Apoyo Nacional a la Producción y Empleo - RPA' }}</b> <br>
                    Presente.–
                </p>
            </div>
            <br>
            <div class="page-title">
                <h3><u>REF. DECLARACIÓN JURADA</u></h3>
            </div>
            <br><br>
            <div class="page-body">
                <p>
                    Dando cumplimiento a lo establecido en el D.S. 4126 “<b>REGLAMENTO A LA LEY FINANCIAL 2020</b>” de fecha 03 de enero de 2020, en su Art. 24.- (DOBLE PERCEPCIÓN), Parágrafo I y II, Declaro expresamente que no percibo ni percibiré remuneraciones provenientes de recursos públicos contra otras entidades, por el periodo de prestación del presente servicio de Consultoría.
                </p>
                <p>
                    Declaro también, que no tengo relación de parentesco directa o indirectamente hasta el cuarto grado de consanguinidad o segundo en afinidad, de acuerdo al código de familia, dentro de la Gobernación Departamental del Beni.
                </p>
                <p>
                    Y asumo la veracidad de todos los documentos que acompañan esta declaración.
                </p>
                <p>
                    Con este grato motivo saludo a usted muy atentamente,
                </p>

                <div style="margin-top: 120px;">
                    <p style="text-align: center; width: 100%">
                        {{ $contract->person->first_name }} {{ $contract->person->last_name }} <br>
                        C.I. {{ $contract->person->ci }} <br>
                        <b>POSTULANTE</b>
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('css')
    <style>
        .page-head {
            text-align: right;
            padding: 0px;
        }
        .page-title {
            text-align: center;
        }
        .page-title h3{
            margin-top: 0px
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection