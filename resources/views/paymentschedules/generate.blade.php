@if($paymentschedule)
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="alert alert-danger">
                    <h4 class="text-center">Ya se generó una planilla con los datos ingresados</h4>
                </div>
            </div>
        </div>
    </div>
@else    
    <form action="{{ route('paymentschedules.store') }}" method="POST">
        @csrf
        <input type="hidden" name="direccion_administrativa_id" value="{{ $direccion_administrativa_id }}">
        <input type="hidden" name="period_id" value="{{ $period->id }}">
        <input type="hidden" name="procedure_type_id" value="{{ $procedure_type_id }}">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
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
                                        $contracts_rc_iva = $paymentschedules_file->where('type', 'rc-iva')->first();
                                        $contracts_faults = $paymentschedules_file->where('type', 'biométrico')->first();

                                        $array_contract_id = [];
                                        $array_worked_days = [];
                                        $array_salary = [];
                                        $array_partial_salary = [];
                                        $array_job = [];
                                        $array_job_level = [];
                                        $array_seniority_bonus_percentage = [];
                                        $array_seniority_bonus_amount = [];
                                        $array_solidary = [];
                                        $array_common_risk = [];
                                        $array_afp_commission = [];
                                        $array_retirement = [];
                                        $array_solidary_national = [];
                                        $array_labor_total = [];
                                        $array_housing_employer = [];
                                        $array_solidary_employer = [];
                                        $array_health = [];
                                        $array_rc_iva_amount = [];
                                        $array_faults_quantity = [];
                                        $array_faults_amount = [];
                                        $array_liquid_payable = [];
                                    @endphp
                                    @forelse ($contracts as $item)
                                        @php
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
                                            $seniority_bonus_percentage = 0;
                                            $minimum_salary_quantity = 1;
                                            if(count($item->person->seniority_bonus) > 0){
                                                if(date('Ym', strtotime($item->person->seniority_bonus->first()->start)) <= $period->name){
                                                    $seniority_bonus_percentage = $item->person->seniority_bonus->first()->type->percentage;
                                                    $minimum_salary_quantity = $item->person->seniority_bonus->first()->quantity;
                                                }
                                            }
                                            $seniority_bonus_amount = ((($minimum_salary * $minimum_salary_quantity) * ($seniority_bonus_percentage /100)) /30) * $days_enabled_worker;

                                            // Total ganado
                                            $total_amout = $partial_salary + $seniority_bonus_amount;

                                            // Calcular edad
                                            $now = \Carbon\Carbon::now();
                                            $birthday = new \Carbon\Carbon($item->person->birthday);
                                            $age = $birthday->diffInYears($now);

                                            // Calcular aportes AFP
                                            $solidary = $total_amout * 0.005;
                                            $retirement = $total_amout * 0.1;
                                            $common_risk = $total_amout *0.0171;   
                                            
                                            // Si es mayor a 57 y menos a 65 años y tiene su AFP inactiva no paga jubilación
                                            if($age >= 58 && $age < 65 && $item->person->afp_status == 0){
                                                $retirement = 0;
                                            }

                                            // Si es mayor a 64 años y tiene su AFP activa no paga riesgo común
                                            if($age >= 65 && $item->person->afp_status == 1){
                                                $common_risk = 0;
                                            }

                                            // Si es mayor a 64 años y tiene su AFP inactiva no paga jubilación ni riesgo común
                                            if($age >= 65 && $item->person->afp_status == 0){
                                                $retirement = 0;
                                                $common_risk = 0;
                                            }

                                            $afp_commission = $total_amout * 0.005;

                                            $solidary_national = 0;
                                            if($total_amout >= 13000){
                                                $solidary_national = ($total_amout - 13000) *0.01;
                                            }
                                            if ($total_amout >= 25000) {
                                                $solidary_national = ($total_amout - 25000) *0.05;
                                            }
                                            if ($total_amout >= 35000) {
                                                $solidary_national = ($total_amout - 35000) *0.1;
                                            }

                                            $labor_total = $solidary + $common_risk + $afp_commission + $retirement + $solidary_national;

                                            $solidary_employer = $total_amout * 0.03;
                                            $housing_employer = $total_amout * 0.02;
                                            $health = $total_amout * 0.1;

                                            // Calcular RC-IVA
                                            $rc_iva = $contracts_rc_iva ? $contracts_rc_iva->details->where('person_id', $item->person->id)->first() : null;
                                            $rc_iva_amount = $rc_iva ? json_decode($rc_iva->details)->iva : 0;

                                            // Faltas
                                            $faults = $contracts_faults ? $contracts_faults->details->where('person_id', $item->person->id)->first() : null;
                                            $faults_quantity = $faults ? json_decode($faults->details)->faults : 0;
                                            $faults_amount = ($salary / 30) * $faults_quantity;

                                            // Descuentos
                                            $total_discount = $labor_total + $faults_amount + $rc_iva_amount;

                                            // Líquido pagable
                                            $liquid_payable = $total_amout - $total_discount;

                                            // Inputs
                                            array_push($array_contract_id, $item->id);
                                            array_push($array_worked_days, $days_enabled_worker);
                                            array_push($array_salary, $salary);
                                            array_push($array_partial_salary, $partial_salary);
                                            array_push($array_job, $item->cargo ? $item->cargo->Descripcion : $item->job->name);
                                            array_push($array_job_level, $level);
                                            array_push($array_seniority_bonus_percentage, $seniority_bonus_percentage);
                                            array_push($array_seniority_bonus_amount, $seniority_bonus_amount);
                                            array_push($array_solidary, $solidary);
                                            array_push($array_common_risk, $common_risk);
                                            array_push($array_afp_commission, $afp_commission);
                                            array_push($array_retirement, $retirement);
                                            array_push($array_solidary_national, $solidary_national);
                                            array_push($array_labor_total, $labor_total);
                                            array_push($array_solidary_employer, $solidary_employer);
                                            array_push($array_housing_employer, $housing_employer);
                                            array_push($array_health, $health);
                                            array_push($array_rc_iva_amount, $rc_iva_amount);
                                            array_push($array_faults_quantity, $faults_quantity);
                                            array_push($array_faults_amount, $faults_amount);
                                            array_push($array_liquid_payable, $liquid_payable);
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
                                            <td class="text-right">{{ number_format($salary, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($partial_salary, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ $seniority_bonus_percentage }}%</td>
                                            <td class="text-right">{{ number_format($seniority_bonus_amount, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($total_amout, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ number_format($solidary, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($common_risk, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($afp_commission, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($retirement, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($solidary_national, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($labor_total, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ number_format($rc_iva_amount, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($faults_quantity, floor($faults_quantity) < $faults_quantity ? 1 : 0, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($faults_amount, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($total_discount, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($liquid_payable, 2, ',', '.') }}</b></td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-right"><b>TOTAL</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_salary)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_partial_salary)->sum(), 2, ',', '.') }}</b></td>
                                        <td></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_seniority_bonus_amount)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_partial_salary)->sum() + collect($array_seniority_bonus_amount)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_solidary)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_common_risk)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_afp_commission)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_retirement)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_solidary_national)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_labor_total)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_rc_iva_amount)->sum(), 2, ',', '.') }}</b></td>
                                        <td></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_faults_amount)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_labor_total)->sum() + collect($array_faults_amount)->sum() + collect($array_rc_iva_amount)->sum(), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format(collect($array_liquid_payable)->sum(), 2, ',', '.') }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#store_modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- store modal --}}    
        <div class="modal fade" tabindex="-1" id="store_modal" role="dialog">
            <div class="modal-dialog modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-settings"></i> Desea guardar la siguiente planilla generada?</h4>
                    </div>
                    <div class="modal-body">
                        {{-- <p class="text-muted">Si realiza esta acción no podrá deshacer los cambios, desea continuar?</p> --}}
                        <input type="hidden" name="contract_id" value="{{ json_encode($array_contract_id) }}">
                        <input type="hidden" name="worked_days" value="{{ json_encode($array_worked_days) }}">
                        <input type="hidden" name="salary" value="{{ json_encode($array_salary) }}">
                        <input type="hidden" name="partial_salary" value="{{ json_encode($array_partial_salary) }}">
                        <input type="hidden" name="job" value="{{ json_encode($array_job) }}">
                        <input type="hidden" name="job_level" value="{{ json_encode($array_job_level) }}">
                        <input type="hidden" name="seniority_bonus_percentage" value="{{ json_encode($array_seniority_bonus_percentage) }}">
                        <input type="hidden" name="seniority_bonus_amount" value="{{ json_encode($array_seniority_bonus_amount) }}">
                        <input type="hidden" name="solidary" value="{{ json_encode($array_solidary) }}">
                        <input type="hidden" name="common_risk" value="{{ json_encode($array_common_risk) }}">
                        <input type="hidden" name="afp_commission" value="{{ json_encode($array_afp_commission) }}">
                        <input type="hidden" name="retirement" value="{{ json_encode($array_retirement) }}">
                        <input type="hidden" name="solidary_national" value="{{ json_encode($array_solidary_national) }}">
                        <input type="hidden" name="labor_total" value="{{ json_encode($array_labor_total) }}">
                        <input type="hidden" name="solidary_employer" value="{{ json_encode($array_solidary_employer) }}">
                        <input type="hidden" name="housing_employer" value="{{ json_encode($array_housing_employer) }}">
                        <input type="hidden" name="health" value="{{ json_encode($array_health) }}">
                        <input type="hidden" name="rc_iva_amount" value="{{ json_encode($array_rc_iva_amount) }}">
                        <input type="hidden" name="faults_quantity" value="{{ json_encode($array_faults_quantity) }}">
                        <input type="hidden" name="faults_amount" value="{{ json_encode($array_faults_amount) }}">
                        <input type="hidden" name="liquid_payable" value="{{ json_encode($array_liquid_payable) }}">

                        <div class="form-group">
                            <label>Centralizar planilla</label> <br>
                            <label class="radio-inline"><input type="radio" name="centralize" value="1">Sí</label>
                            <label class="radio-inline"><input type="radio" name="centralize" value="0" checked>No</label>
                        </div>
                        <div class="form-group">
                            <textarea name="observations" class="form-control" placeholder="Observaciones" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-primary" value="Sí, guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif

<style>
    th{
        font-size: 7px !important
    }
    td{
        font-size: 10px !important
    }
    tfoot td{
        font-size: 11px !important
    }
</style>