
<div class="col-md-12 text-right">
    {{-- @if (count($funcionarios)) --}}
        <button type="button" onclick="report_print()" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    {{-- @endif --}}
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center" colspan="9">DATOS GENERALES</th>
                            <th style="text-align: center" colspan="3">ADMINISTRADORES DE FONDOS DE PENSIONES</th>
                            <th style="text-align: center" colspan="6">CAJA DE SALUD CORDES</th>
                        </tr>
                        <tr>
                            <th>N&deg;</th>
                            {{-- <th>ID PAGO</th> --}}
                            <th>HR/NCI</th>
                            <th>PERIODO</th>
                            <th>DIRECCIÓN ADMINISTRATIVA</th>
                            <th>TIPO DE PLANILLA</th>
                            <th>CÓDIGO DE PLANILLA</th>
                            <th>N&deg; DE PERSONAS</th>
                            <th>AFP</th>
                            <th style="text-align: right">TOTAL GANADO</th>
                            <th style="text-align: right">APORTE AFP</th>
                            <th>FECHA DE PAGO AFP</th>
                            <th>N&deg; FCP</th>
                            <th style="text-align: right">APORTE CC</th>
                            <th>FECHA DE PAGO CC</th>
                            <th>F GTC-11</th>
                            <th>N&deg; DE CHEQUE</th>
                            <th>N&deg; DE RECIBO</th>
                            <th>N&deg; DE DEPOSITO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $total_ganado = 0;
                            $total_afp = 0;
                            $total_cc = 0;
                        @endphp
                        @forelse ($planillas as $item)
                            <tr>
                                <td>{{ $cont }}</td>
                                {{-- <td></td> --}}
                                <td>{{ $item->certificacion ? $item->certificacion->HojaRuta_NCI : '' }}</td>
                                <td>{{ $item->Periodo }}</td>
                                <td>{{ $item->Direccion_Administrativa }}</td>
                                <td>{{ $item->tipo_planilla }} {{ $item->certificacion ? ' - '.$item->certificacion->nombre_planilla : '' }}</td>
                                <td>{{ $item->idPlanillaprocesada }}</td>
                                <td>{{ $item->cantidad_personas }}</td>
                                <td>{{ $item->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                <td style="text-align: right">{{ number_format($item->Liquido_Pagable, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Total_Aportes_Afp, 2, ',', '.') }}</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">{{ number_format($item->Liquido_Pagable*0.1, 2, ',', '.') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $cont++;
                                $total_ganado += $item->Liquido_Pagable;
                                $total_afp += $item->Total_Aportes_Afp;
                                $total_cc += $item->Liquido_Pagable * 0.1;
                            @endphp
                        @empty
                            
                        @endforelse
                        <tr>
                            <td colspan="8"></td>
                            <td><b>{{ number_format($total_ganado, 2, ',', '.') }}</b></td>
                            <td><b>{{ number_format($total_afp, 2, ',', '.') }}</b></td>
                            <td></td>
                            <td></td>
                            <td><b>{{ number_format($total_cc, 2, ',', '.') }}</b></td>
                            <td colspan="5"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    th{
        font-size: 8px
    }
    td{
        font-size: 11px
    }
</style>

<script>
    $(document).ready(function(){

    })
</script>