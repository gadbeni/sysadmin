@extends('layouts.template-print')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = str_pad($memo->code, 9, "0", STR_PAD_LEFT);
    // dd($memo->additional_person);
@endphp

@section('page_title', 'Memorámdum '.$code)

@section('qr_code')
    <div id="qr_code" >
        {!! QrCode::size(70)->generate('MEMORANDUM '.$code.' de fecha '.date('d/m/Y', strtotime($memo->date)).', de '.$memo->origin->person->first_name.' '.$memo->origin->person->last_name.' a '.$memo->destiny->person->first_name.' '.$memo->destiny->person->first_name) !!}
        <br>
        <small>Hora: {{ date('H:i') }}</small>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="page-title">
            <h2>
                <span style="color: #009A2F">MEMORANDUM</span> <br>
                <small>{{ $memo->direccion ? $memo->direccion->sigla : 'S.D.A.F./D.F.' }} N° {{ $code }}</small>
            </h2>
        </div>
        <div class="page-body">
            <div class="page-head" style="width: 100%">
                <div class="border-right">
                    <br><br><br><br>
                    <span>Santísima Trinidad</span>, {{ date('d', strtotime($memo->date)) }} de {{ $months[intval(date('m', strtotime($memo->date)))] }} de {{ date('Y', strtotime($memo->date)) }}
                </div>
                <div class="border-left">
                    <b>DE:</b> {{ $memo->origin->person->first_name }} {{ $memo->origin->person->last_name }} <br>
                    <b>{{ Str::upper($memo->origin_alternate_job ?? $memo->origin->cargo ? $memo->origin->cargo->descripcion : $memo->origin->job->name) }}</b> <br> <br>
                    <b>A:</b> {{ $memo->destiny->person->first_name }} {{ $memo->destiny->person->last_name }}  <br>
                    <b>{{ Str::upper($memo->destiny_alternate_job ?? $memo->destiny->cargo ? $memo->destiny->cargo->descripcion : $memo->destiny->job->name) }}</b> <br> <br>
                </div>
            </div>
            <br><br>
            <p>
                @php
                    $numeros_a_letras = new NumeroALetras();
                @endphp
                Previa revisión y certificación de presupuesto mediante comprobante preventivo número: <b>{{ $memo->number }}</b> D.A. <b>{{ $memo->da_sigep }}</b> FTE: <b>{{ $memo->source }}</b> sirvase <b>realizar la/el {{ $memo->type }} de Recursos Económicos la suma de Bs. {{ number_format($memo->amount, 2, ',', '.') }} ({{ Str::upper($numeros_a_letras->toInvoice(number_format($memo->amount, 2, '.', ''), 2, 'Bolivianos')) }})</b> a la orden de
                @if ($memo->additional_person->count())
                    : <br>
                    <ul>
                        <li>
                            @if ($memo->person_external->number_acount)
                                CTA. CTE. <b>{{ $memo->person_external->number_acount }}</b>, a nombre de 
                            @endif
                            <b>{{ $memo->person_external->full_name }}</b> 
                            @if ($memo->person_external->ci_nit)
                                <b>con C.I. {{ $memo->person_external->ci_nit }}</b>.
                            @endif
                        </li>
                        @foreach ($memo->additional_person as $additional_person)
                        <li>
                            @if ($additional_person->person_external->number_acount)
                                CTA. CTE. <b>{{ $additional_person->person_external->number_acount }}</b>, a nombre de 
                            @endif
                            <b>{{ $additional_person->person_external->full_name }}</b> 
                            @if ($additional_person->person_external->ci_nit)
                                <b>con C.I. {{ $additional_person->person_external->ci_nit }}</b>.
                            @endif
                        </li>
                        @endforeach
                    </ul>

                @else
                    @if ($memo->person_external->number_acount)
                        CTA. CTE. <b>{{ $memo->person_external->number_acount }}</b>, a nombre de 
                    @endif
                    <b>{{ $memo->person_external->full_name }}</b> 
                    @if ($memo->person_external->ci_nit)
                        <b>con C.I. {{ $memo->person_external->ci_nit }}</b>; 
                    @else
                        ; 
                    @endif
                @endif
                Por concepto de {!! $memo->concept !!}{!! $memo->imputation ? ', según imputación presupuestaria <b>'.$memo->imputation.'</b>.' : '.' !!} Se adjunta dicha documentación de acuerdo a ley.
            </p>
            <br>
            <p>
                Atentamente.
            </p>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-title {
            text-align: center;
        }
        .page-head{
            display: flex;
            flex-direction: row;
            width: 100%;
            text-align: left;
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