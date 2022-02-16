<div class="row">
    <div class="col-md-12">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                            @endphp
                            @forelse ($contracts as $item)
                                @php
                                    $salary = $item->cargo ? $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo : $item->job->salary;
                                    $level = $item->cargo ? $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->NumNivel : $item->job->level;
                                @endphp
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>{{ $level }}</td>
                                    <td>
                                        <b>{{ $item->person->first_name }} {{ $item->person->last_name }}</b> <br>
                                        <small>{{ $item->cargo ? $item->cargo->Descripcion : $item->job->name }}</small>
                                    </td>
                                    <td><b>{{ $item->person->ci }}</b></td>
                                    <td>{{ $item->person->nua_cua }}</td>
                                    <td>{{ $item->start }}</td>
                                    <td><b>30</b></td>
                                    <td>{{ number_format($salary, 2, ',', '.') }}</td>
                                    <td><b>{{ number_format($salary, 2, ',', '.') }}</b></td>
                                    <td>0%</td>
                                    <td>0,00</td>
                                    <td><b>{{ number_format($salary, 2, ',', '.') }}</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @php
                                    $cont++;
                                @endphp
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    th{
        font-size: 7px !important
    }
    td{
        font-size: 10px !important
    }
</style>