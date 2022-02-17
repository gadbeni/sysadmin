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
                                    // Periodo de la planilla
                                    $period = \App\Models\Period::find($periodo_id);

                                    // Periodo de inicio de contrato
                                    $period_start = date('Ym', strtotime($item->start));

                                    // Días que el trabajador debió haber trabajado
                                    $days_enabled_worker = 30;
                                    if($period_start == $period->name){
                                        $start_day = date('d', strtotime($item->start));
                                        if($start_day > 0){
                                            $days_enabled_worker = 30 - ($start_day - 1);
                                        }
                                    }

                                    // Sueldo actual
                                    $salary = $item->cargo ? $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo : $item->job->salary;
                                    // Nivel salarial
                                    $level = $item->cargo ? $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->NumNivel : $item->job->level;

                                    // Sueldo parcial (en caso de que el trabajador tenga una fecha de ingreso posterior al periodo de la planilla)
                                    $partial_salary = ($salary/30) * $days_enabled_worker;

                                    // Calcular bono antigüedad
                                    $minimum_salary = setting('planillas.minimum_salary') ?? 2164;
                                    $seniority_bonus_pecentaje = 0;
                                    if(count($item->person->seniority_bonus) > 0){
                                        $seniority_bonus_pecentaje = $item->person->seniority_bonus->first()->type->percentaje;
                                        // dd($item->person->seniority_bonus->first());
                                    }
                                    $seniority_bonus_amount = (($minimum_salary * ($seniority_bonus_pecentaje /100)) /30) * $days_enabled_worker;

                                    // Total ganado
                                    $total_amout = $partial_salary + $seniority_bonus_amount;

                                    // Calcular edad
                                    $now = \Carbon\Carbon::now();
                                    $birthday = new \Carbon\Carbon($item->person->birthday);
                                    $age = $birthday->diffInYears($now);

                                    // Calcular aportes AFP
                                    $solidary_contribution = $total_amout * 0.005;
                                    $common_risk = 0;
                                    if($age < 65){
                                        $common_risk = $total_amout *0.0171;   
                                    }
                                    $commission_afp = $total_amout * 0.005;
                                    $retirement_contribution = 0;
                                    if($item->person->afp_status == 1){
                                        $retirement_contribution = $total_amout * 0.1;
                                    }

                                    $solidary_contribution_national = 0;
                                    if($total_amout >= 13000){
                                        $solidary_contribution_national = ($total_amout - 13000) *0.01;
                                    }
                                    if ($total_amout >= 25000) {
                                        $solidary_contribution_national = ($total_amout - 25000) *0.05;
                                    }
                                    if ($total_amout >= 35000) {
                                        $solidary_contribution_national = ($total_amout - 35000) *0.1;
                                    }

                                    $total_contributions_afp = $solidary_contribution + $common_risk + $commission_afp + $retirement_contribution + $solidary_contribution_national;
                                    
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
                                    <td><b>{{ $days_enabled_worker }}</b></td>
                                    <td>{{ number_format($salary, 2, ',', '.') }}</td>
                                    <td><b>{{ number_format($partial_salary, 2, ',', '.') }}</b></td>
                                    <td>{{ $seniority_bonus_pecentaje }}%</td>
                                    <td>{{ number_format($seniority_bonus_amount, 2, ',', '.') }}</td>
                                    <td><b>{{ number_format($total_amout, 2, ',', '.') }}</b></td>
                                    <td>{{ number_format($solidary_contribution, 2, ',', '.') }}</td>
                                    <td>{{ number_format($common_risk, 2, ',', '.') }}</td>
                                    <td>{{ number_format($commission_afp, 2, ',', '.') }}</td>
                                    <td>{{ number_format($retirement_contribution, 2, ',', '.') }}</td>
                                    <td>{{ number_format($solidary_contribution_national, 2, ',', '.') }}</td>
                                    <td>{{ number_format($total_contributions_afp, 2, ',', '.') }}</td>
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