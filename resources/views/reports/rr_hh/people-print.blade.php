@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de Personas')

@section('content')
    <div class="content">
        <table width="100%">
            <tr>
                <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
                <td style="text-align: right">
                    <h2 style="margin-bottom: -20px; margin-top: 10px">Reporte de Personas</h2>
                    <small>
                         <br>
                        @php
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        {{-- <small>RECURSOS HUMANOS</small> <br> --}}
                        <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                    </small>
                </td>
            </tr>
            <tr>
                <tr></tr>
            </tr>
        </table>
        <br><br>
        <table class="table-data" style="width: 100%;" border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>Fotografía</th>
                    <th>Nombre completo</th>
                    <th>CI</th>
                    <th>Lugar nac.</th>
                    <th>Fecha nac.</th>
                    <th>Edad</th>
                    <th>Género</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>AFP</th>
                    <th>NUA/CUA</th>
                    <th>Caja de salud</th>
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @forelse ($people as $item)
                    @php
                        $image = asset('images/default.jpg');
                        if($item->image){
                            $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                        }
                        $now = \Carbon\Carbon::now();
                        $birthday = new \Carbon\Carbon($item->birthday);
                        $age = $birthday->diffInYears($now);
                    @endphp
                    <tr>
                        <td>{{ $cont }}</td>
                        <td><img src="{{ $image }}" alt="{{ $item->first_name }} {{ $item->last_name }}" style="width: 50px; height: 60px; border-radius: 30px; margin-right: 10px"></td>
                        <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                        <td>{{ $item->ci }}</td>
                        <td>{{ $item->city ? $item->city->name : 'No definido' }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->birthday)) }}</td>
                        <td>{{ $age }}</td>
                        <td>{{ Str::ucfirst($item->gender) }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->afp_type->name }}</td>
                        <td>{{ $item->nua_cua }}</td>
                        <td>{{ $item->cc == 1 ? 'Caja Cordes' : 'Caja Petrolera' }}</td>
                        <td>
                            {!! $item->user ? $item->user->name.'<br>' : '' !!}
                            <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                        </td>
                    </tr>
                @php
                    $cont++;
                @endphp
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="14" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('css')
<style>
    .content {
        padding-left: 30px;
        padding-right: 30px;
    }
    .table-data th{
        font-size: 9px !important
    }
    .table-data td{
        font-size: 10px !important
    }
    table, th, td {
        border-collapse: collapse;
    }
    @page {
        margin: 10mm 40mm 10mm 0mm;
    }
    @media print {
        .table-data th{
            font-size: 7px !important
        }
        .table-data td{
            font-size: 8px !important
        }
    }
</style>
@endsection