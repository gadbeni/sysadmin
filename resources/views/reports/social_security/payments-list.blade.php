
<div class="col-md-12 text-right">
    @if (count($planillas))
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button> --}}
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="3">ITEM</th>
                            <th rowspan="3">NIVEL</th>
                            <th rowspan="3">APELLIDOS Y NOMBRES / CARGO</th>
                            <th rowspan="3">CI</th>
                            <th rowspan="3">N&deg; NUA/CUA</th>
                            <th rowspan="3">FECHA INGRESO</th>
                            <th rowspan="3">DÍAS TRAB.</th>
                            <th style="text-align: right" rowspan="3">SUELDO MENSUAL</th>
                            <th style="text-align: right" rowspan="3">SUELDO PARCIAL</th>
                            <th rowspan="3">%</th>
                            <th style="text-align: right" rowspan="3">BONO ANTIG.</th>
                            <th style="text-align: right" rowspan="3">TOTAL GANADO</th>
                            <th style="text-align: center" colspan="5">APORTES LABORALES</th>
                            <th rowspan="3">TOTAL APORTES AFP</th>
                            <th rowspan="3">RC-IVA</th>
                            <th colspan="2">FONDO SOCIAL</th>
                            <th rowspan="3">TOTAL DESC.</th>
                            <th rowspan="3">LÍQUIDO PAGABLE</th>
                        </tr>
                        <tr>
                            <th>APORTE SOLIDARIO</th>
                            <th>RIESGO COMÚN</th>
                            <th>COMISIÓN AFP</th>
                            <th>APORTE JUBILACIÓN</th>
                            <th>APORTE NACIONAL SOLIDARIO</th>
                            <th rowspan="2">DÍAS</th>
                            <th rowspan="2">MULTAS</th>
                        </tr>
                        <tr>
                            <th>0.5%</th>
                            <th>1.71%</th>
                            <th>0.5%</th>
                            <th>10%</th>
                            <th>1%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $total_sueldo_mensual = 0;
                            $total_sueldo_parcial = 0;
                            $total_bono_antiguedad = 0;
                            $total_ganancia = 0;
                            $total_aporte_solidario = 0;
                            $total_riesgo_comun = 0;
                            $total_comision_afp = 0;
                            $total_aporte_jubilacion = 0;
                            $total_aporte_n_s = 0;
                            $total_aporte_afp = 0;
                            $total_rc_iva = 0;
                            $total_multas = 0;
                            $total_descuentos = 0;
                            $total_liquido_pagable = 0;
                        @endphp
                        @forelse ($planillas as $item)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->Nivel }}</td>
                                <td>{{ $item->Nombre_Empleado }} <br> <small><b>{{ $item->Cargo }}</b></small> </td>
                                <td>{{ $item->CedulaIdentidad }} {{ $item->Expedido }}</td>
                                <td>{{ $item->Num_Nua }}</td>
                                <td>{{ $item->Fecha_Ingreso }}</td>
                                <td>{{ $item->Dias_Trabajado }}</td>
                                <td style="text-align: right">{{ number_format($item->Sueldo_Mensual, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Sueldo_Parcial, 2, ',', '.') }}</td>
                                <td></td>
                                <td style="text-align: right">{{ number_format($item->Bono_Antiguedad, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Total_Ganado, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Aporte_Solidario, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Riesgo_Comun, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Comision_Afp, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Aporte_Jubilacion, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Aporte_NS, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Total_Aportes_Afp, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->RC_IVA, 2, ',', '.') }}</td>
                                <td>{{ $item->FsDias }}</td>
                                <td style="text-align: right">{{ number_format($item->FsMultas, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Total_Descuento, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->Liquido_Pagable, 2, ',', '.') }}</td>
                            </tr>
                            @php
                                $cont++;
                                $total_sueldo_mensual += $item->Sueldo_Mensual;
                                $total_sueldo_parcial += $item->Sueldo_Parcial;
                                $total_bono_antiguedad += $item->Bono_Antiguedad;
                                $total_ganancia += $item->Total_Ganado;
                                $total_aporte_solidario += $item->Aporte_Solidario;
                                $total_riesgo_comun += $item->Riesgo_Comun;
                                $total_comision_afp += $item->Comision_Afp;
                                $total_aporte_jubilacion += $item->Aporte_Jubilacion;
                                $total_aporte_n_s += $item->Aporte_NS;
                                $total_aporte_afp += $item->Total_Aportes_Afp;
                                $total_rc_iva += $item->RC_IVA;
                                $total_multas += $item->FsMultas;
                                $total_descuentos += $item->Total_Descuento;
                                $total_liquido_pagable += $item->Liquido_Pagable;
                            @endphp
                        @empty
                            
                        @endforelse
                        <tr>
                            <td colspan="7"><b>TOTAL</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_sueldo_mensual, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_sueldo_parcial, 2, ',', '.') }}</b></td>
                            <td></td>
                            <td style="text-align: right"><b>{{ number_format($total_bono_antiguedad, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_ganancia, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_aporte_solidario, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_riesgo_comun, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_comision_afp, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_aporte_jubilacion, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_aporte_n_s, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_aporte_afp, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_rc_iva, 2, ',', '.') }}</b></td>
                            <td></td>
                            <td style="text-align: right"><b>{{ number_format($total_multas, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_descuentos, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_liquido_pagable, 2, ',', '.') }}</b></td>
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