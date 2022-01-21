@extends('layouts.template-print-alt')

@section('page_title', 'Reporte de declaraciones juradas')

@section('content')
    <table width="100%">
        <tr>
            <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="120px"></td>
            <td style="text-align: right">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    DECLARACIÓN JURADA - BIENES Y RENTAS <br>
                    @php
                        $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        $year = Str::substr($periodo, 0, 4);
                        $month = intval(Str::substr($periodo, 4, 2));
                        if($afp == 1){
                            $afp = '- FUTURO';
                        }elseif($afp == 2){
                            $afp = '- PREVISÓN';
                        }
                    @endphp
                    <small> {{ strtoupper($months[$month]) }} GESTIÓN {{ $year }} {{ $afp }} </small> <br>
                    {{-- <small>RECURSOS HUMANOS</small> <br> --}}
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/M/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
        <tr>
            <tr></tr>
        </tr>
    </table>
    <br><br>
    <table style="width: 100%; font-size: 12px" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ITEM</th>
                <th>NIVEL</th>
                <th>APELLIDOS Y NOMBRES / CARGO</th>
                <th>CÉDULA DE IDENTIDAD</th>
                <th>EXP</th>
                <th>N&deg; NUA/CUA</th>
                <th>FECHA INGRESO</th>
                <th>FECHA NACIMIENTO</th>
                <th>FECHA CONCLUSIÓN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($funcionarios as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->Nivel }}</td>
                    <td>{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} <br> <small>{{ $item->Cargo }}</small></td>
                    <td>{{ $item->CedulaIdentidad }}</td>
                    <td>{{ $item->Expedido }}</td>
                    <td>{{ $item->Num_Nua }}</td>
                    <td>{{ $item->Fecha_Inicio }}</td>
                    <td>{{ $item->fechanacimiento }}</td>
                    <td>{{ $item->Fecha_Conclusion == '0000-00-00' ? 'No definida' : $item->Fecha_Conclusion }}</td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="9"><h4 class="text-center">No hay resultados</h4></td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection