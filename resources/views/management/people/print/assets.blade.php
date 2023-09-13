@extends('layouts.template-print-legal')

@section('page_title', 'Acta de custodio')

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    
@endphp

@section('qr_code')
    <div id="qr_code" >
        @php
            $qrcode = QrCode::size(80)->generate('Acta de custodio Nro '.$person_asset->code.' de '.$person_asset->details->count().' activo(s) del funcionario(a) '.$person_asset->person->first_name.' '.$person_asset->person->last_name.' con CI: '.$person_asset->person->ci.', de fecha '.date('d/m/Y', strtotime($person_asset->date)));
        @endphp
        {!! $qrcode !!}
    </div>
@endsection

@section('content')
    <div class="content" style="text-align: justify">
        <div class="page-head" style="margin-top: -60px">
            <h3>
                GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI <br>
                UNIDAD DE RESGISTRO Y CONTROL DE BIENES PÚBLICOS <br>
                GESTIÓN {{ date('Y', strtotime($person_asset->date)) }} <br>
                
            </h3>
            <hr>
            <h4>
                <u>
                    ACTA DE CUSTODIA U.R.C.B.P. N&deg; {{ $person_asset->code }} <br>
                    {{ Str::upper($person_asset->contract->direccion_administrativa->nombre) }} <br>
                    {{ $person_asset->contract->unidad_administrativa ? Str::upper($person_asset->contract->unidad_administrativa->nombre) : '' }}
                </u>
            </h4>
        </div>
        <table width="100%">
            <tr>
                <td><b>NOMBRE</b></td>
                <td>{{ Str::upper($person_asset->person->first_name.' '.$person_asset->person->last_name) }}</td>
                <td><b>FECHA</b></td>
                <td>{{ date('d/m/Y', strtotime($person_asset->date)) }}</td>
            </tr>
            <tr>
                <td><b>CI</b></td>
                <td>{{ $person_asset->person->ci }}</td>
                <td><b>CELULAR</b></td>
                <td>{{ $person_asset->person->phone }}</td>
            </tr>
            <tr>
                <td><b>CARGO</b></td>
                <td style="max-width: 200px">{{ Str::upper($person_asset->contract->cargo ? $person_asset->contract->cargo->Descripcion : $person_asset->contract->job->name) }}</td>
                <td><b>CONTRATO</b></td>
                <td>{{ Str::upper($person_asset->contract->type->name) }}</td>
            </tr>
        </table>
        <br><br>
        <table class="table-description" border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th width="60px">CODIGO</th>
                    <th>DETALLE</th>
                    <th>ESTADO</th>
                    <th>OBSERVVACIONES</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @foreach ($person_asset->details as $item)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->asset->code }}</td>
                        <td>{{ $item->asset->description }}</td>
                        <td>{{ ucfirst($item->asset->status) }}</td>
                        <td>{{ $item->asset->observations }}</td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <br>
        <p>
            <b>NOTA:</b> La asignación de bienes genera en el servidor público la consiguiente responsabilidad sobre el debido uso, custodia y mantenimiento de los mismos; la perdida, destrucción o maltrato por negligencia será imputada a su persona. Así mismo el funcionario que tiene a su cargo dichos bienes, por ningún motivo podrá efectuar préstamo y/o transferencia por cuenta propia, de conformidad al decreto supremo N&deg; 181 y disposiciones complementarias.
        </p>
        <table class="table-signature">
            <tr>
                @foreach ($person_asset->signatures as $item)
                <td>
                        ....................................................... <br>
                        {{ Str::upper($item->contract->person->first_name.' '.$item->contract->person->last_name) }} <br>
                        <b>{{ Str::upper($item->contract->cargo ? $item->contract->cargo->Descripcion : $item->contract->job->name) }}</b> <br>
                        <b>ENTREGUE CONFORME</b>
                </td>
                @endforeach
                <td>
                    ....................................................... <br>
                    {{ Str::upper($person_asset->person->first_name.' '.$person_asset->person->last_name) }} <br>
                    <b>{{ Str::upper($person_asset->contract->cargo ? $person_asset->contract->cargo->Descripcion : $person_asset->contract->job->name) }}</b> <br>
                    <b>RECIBI CONFORME</b>
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('css')
    <style>
        .table-description{
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .table-description th{
            text-align: center
        }
        .table-signature{
            font-size: 11px
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection