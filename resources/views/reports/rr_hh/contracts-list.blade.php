
<div class="col-md-12 text-right">
    @if (count($funcionarios_ingreso))
        <button type="button" onclick="report_print()" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
@php
    $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
@endphp
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="15"><h3>ALTAS</h3></th>
                        </tr>
                        <tr>
                            <th>ITEM</th>
                            <th>NIVEL</th>
                            <th>APELLIDOS Y NOMBRES / CARGO</th>
                            <th>CÉDULA DE IDENTIDAD</th>
                            <th>EXP</th>
                            <th>NUA/CUA</th>
                            <th>AFP</th>
                            <th>NOVEDAD</th>
                            <th>FECHA</th>
                            <th>DÍAS TRAB.</th>
                            <th>SUELDO MENSUAL</th>
                            <th>SUELDO PARCIAL</th>
                            <th>%</th>
                            <th>BONO ANTIG.</th>
                            <th>TOTAL GANADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @forelse ($funcionarios_ingreso as $item)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->Nivel }}</td>
                                <td>{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} <br> <small>{{ $item->Cargo }}</small></td>
                                <td>{{ $item->CedulaIdentidad }}</td>
                                <td>{{ $item->Expedido }}</td>
                                <td>{{ $item->Num_Nua }}</td>
                                <td>{{ $item->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                <td>I</td>
                                <td>{{ date('d', strtotime($item->Fecha_Ingreso)).'/'.$months[intval(date('m', strtotime($item->Fecha_Ingreso)))].'/'.date('Y', strtotime($item->Fecha_Ingreso)) }}</td>
                                <td>{{ $item->Dias_Trabajado }}</td>
                                <td>{{ number_format($item->Sueldo_Mensual, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->Sueldo_Parcial, 2, ',', '.') }}</td>
                                <td>{{ $item->Porcentaje }}</td>
                                <td>{{ number_format($item->Bono_Antiguedad, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->Total_Ganado, 2, ',', '.') }}</td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="15"><h4 class="text-center">No hay resultados</h4></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="15"><h3>BAJAS</h3></th>
                        </tr>
                        <tr>
                            <th>ITEM</th>
                            <th>NIVEL</th>
                            <th>APELLIDOS Y NOMBRES / CARGO</th>
                            <th>CÉDULA DE IDENTIDAD</th>
                            <th>EXP</th>
                            <th>NUA/CUA</th>
                            <th>AFP</th>
                            <th>NOVEDAD</th>
                            <th>FECHA</th>
                            <th>DÍAS TRAB.</th>
                            <th>SUELDO MENSUAL</th>
                            <th>SUELDO PARCIAL</th>
                            <th>%</th>
                            <th>BONO ANTIG.</th>
                            <th>TOTAL GANADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @forelse ($funcionarios_egreso as $item)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->Nivel }}</td>
                                <td>{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} <br> <small>{{ $item->Cargo }}</small></td>
                                <td>{{ $item->CedulaIdentidad }}</td>
                                <td>{{ $item->Expedido }}</td>
                                <td>{{ $item->Num_Nua }}</td>
                                <td>{{ $item->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                <td>E</td>
                                <td>{{ date('d', strtotime($item->Fecha_Conclusion)).'/'.$months[intval(date('m', strtotime($item->Fecha_Conclusion)))].'/'.date('Y', strtotime($item->Fecha_Conclusion)) }}</td>
                                <td>{{ $item->Dias_Trabajado }}</td>
                                <td>{{ number_format($item->Sueldo_Mensual, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->Sueldo_Parcial, 2, ',', '.') }}</td>
                                <td>{{ $item->Porcentaje }}</td>
                                <td>{{ number_format($item->Bono_Antiguedad, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->Total_Ganado, 2, ',', '.') }}</td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="15"><h4 class="text-center">No hay resultados</h4></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    })
</script>