
<div class="col-md-12 text-right">
    @if ($data)
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button> --}}
        <button type="button" onclick="report_export('excel', '{{ $type_report }}')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                @if ($type_report == '#form-ministerio')
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>N&deg;</th>
                                <th>CÓDIGO DE ENTIDAD</th>
                                <th>TIPO DE PLANILLA</th>
                                <th>FUENTE</th>
                                <th>ORGANISMO</th>
                                <th>GESTION</th>
                                <th>MES</th>
                                <th>CARNET DE  IDENTIDAD</th>
                                <th>NUMERO COMPLEMENTARIO</th>
                                <th>EXPEDIDO</th>
                                <th>NOMBRE 1</th>
                                <th>NOMBRE 2</th>
                                <th>APELLIDO 1</th>
                                <th>APELLIDO 2</th>
                                <th>APELLIDO 3</th>
                                <th>FECHA DE NACIMIENTO</th>
                                <th>FECHA DE INGRESO</th>
                                <th>SEXO</th>
                                <th>ITEM</th>
                                <th>CARGO</th>
                                <th>TIPO EMPLEADO</th>
                                <th>GRADO DE FORMACIÓN</th>
                                <th>DÍAS TRABAJADOS</th>
                                <th>BÁSICO (Bs.)</th>
                                <th>BONO ANTIGÜEDAD (Bs.)</th>
                                <th>OTROS INGRESOS (Bs.)</th>
                                <th>TOTAL GANADO (Bs.)</th>
                                <th>APORTE AL SEGURO SOCIAL OBLIGATORIO A LARGO PLAZO (SSO) BS.</th>
                                <th>OTROS DESCUENTOS (Bs.)</th>
                                <th>APORTE SOLIDARIO DEL ASEGURADO 0.5%  (Bs.)</th>
                                <th>APORTE PATRONAL SOLIDARIO  3% (Bs.)</th>
                                <th>LIQUIDO PAGABLE (Bs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $cont = 1;
                            @endphp
                            @foreach($data as $item)
                                @php
                                // dd($item);
                                    $period = \App\Models\Period::findOrFail($item->paymentschedule->period_id);
                                    $year = Str::substr($period->name, 0, 4);
                                    $month = Str::substr($period->name, 4, 2);
                                @endphp
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>908</td>
                                    <td>PAGO DE HABERES</td>
                                    <td>20 RECESP</td>
                                    <td>220 REG</td>
                                    <td>{{ $year }}</td>
                                    <td>{{ $month }}</td>
                                    <td>{{ explode('-', $item->contract->person->ci)[0] }}</td>
                                    <td>{{ count(explode('-', $item->contract->person->ci)) > 1 ? explode('-', $item->contract->person->ci)[1] : '' }}</td>
                                    <td>OTRO</td>
                                    <td>{{ explode(' ', $item->contract->person->first_name)[0] }}</td>
                                    <td>{{ count(explode(' ', $item->contract->person->first_name)) > 1 ? explode(' ', $item->contract->person->first_name)[1] : '' }}</td>
                                    <td>{{ explode(' ', $item->contract->person->last_name)[0] }}</td>
                                    <td>{{ count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : '' }}</td>
                                    <td></td>
                                    <td>{{ date('d/m/Y', strtotime($item->contract->person->birthday)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($item->contract->start)) }}</td>
                                    <td>{{ $item->contract->person->gender == 'masculino' ? 'M' : 'F' }}</td>
                                    <td>{{ $item->contract->job_id ?? '' }}</td>
                                    <td>{{ $item->job }}</td>
                                    <td>{{ Str::upper($item->contract->type->name) }}</td>
                                    <td>NO EXISTE INFORMACION</td>
                                    <td>{{ $item->worked_days }}</td>
                                    <td>{{ number_format($item->salary, 2, ',', '') }}</td>
                                    <td>{{ number_format($item->seniority_bonus_amount, 2, ',', '') }}</td>
                                    <td>0</td>
                                    <td>{{ number_format($item->partial_salary, 2, ',', '') }}</td>
                                    <td>{{ number_format($item->solidary + $item->common_risk + $item->retirement, 2, ',', '') }}</td>
                                    <td>{{ number_format($item->faults_amount, 2, ',', '') }}</td>
                                    <td>{{ number_format($item->solidary, 2, ',', '') }}</td>
                                    <td>{{ number_format($item->solidary_employer, 2, ',', '') }}</td>
                                    <td>{{ number_format($item->liquid_payable, 2, ',', '') }}</td>
                                    @php
                                        $cont++;
                                    @endphp
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                @endif
                
                @if ($type_report == '#form-afp')
                    @if ($afp == 1)
                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N&deg;</th>
                                    <th>TIPO DOCUMENTO</th>
                                    <th>N&deg; DE DOCUMENTO</th>
                                    <th>EXPEDIDO</th>
                                    <th>NUA/CUA</th>
                                    <th>APELLIDO PATERNO</th>
                                    <th>APELLIDO MATERNO</th>
                                    <th>APELLIDO DE CASADA</th>
                                    <th>PRIMER NOMBRE</th>
                                    <th>SEGUNDO NOMBRE</th>
                                    <th>DEPARTAMENTO</th>
                                    <th>NOVEDAD (I/R/L/S)</th>
                                    <th>FECHA DE NOVEDAD</th>
                                    <th>DÍAS COTIZADOS</th>
                                    <th>TIPO DE ASEGURADO</th>
                                    <th>TOTAL GANADO DEP. o ASEG. < 65 APORTA</th>
                                    <th>TOTAL GANADO DEP. o ASEG. > 65 APORTA</th>
                                    <th>TOTAL GANADO DEP. o ASEG. CON PENS. < 65 APORTA</th>
                                    <th>TOTAL GANADO DEP. o ASEG. CON PENS. > 65 APORTA</th>
                                    <th>COTIZACIÓN ADICIONAL</th>
                                    <th>TOTAL GANADO BS. VIVIENDA</th>
                                    <th>TOTAL GANADO BS. FONDO SOCIAL</th>
                                    <th>TOTAL GANADO BS. MINERO </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cont = 1;
                                    if($group_by == 1){
                                        $data = $data->details->groupBy('contract.program_id');
                                        $data = $data->map(function($item, $key){
                                            $program = \App\Models\Program::find($key);
                                            return [
                                                'id' => $program->id,
                                                'programatic_category' => $program->programatic_category,
                                                'name' => $program->name,
                                                'direccion_administrativa' =>$program->direccion_administrativa->nombre,
                                                'details' => $item
                                            ];
                                        });
                                        $data = $data->sortBy('order');
                                    }elseif($group_by == 2){
                                        $data = $data->details->groupBy('paymentschedule.direccion_administrativa_id');
                                        $data = $data->map(function($item, $key){
                                            $da = \App\Models\Direccion::where('id', $key)->first();
                                            return [
                                                'id' => $da->id,
                                                'name' => $da->nombre,
                                                'order' => $da->orden,
                                                'details' => $item
                                            ];
                                        });
                                        $data = $data->sortBy('order');
                                    }else{
                                        $data = ['' => $data->details];
                                    }
                                    // dd($data);
                                @endphp
                                @foreach ($data as $value)
                                    @if ($group_by)
                                        <tr>
                                            <td colspan="23"><b>{{ $value['name'] }}</b></td>
                                        </tr>
                                    @endif
                                    @foreach($value['details'] as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>CI</td>
                                            <td>{{ $item->contract->person->ci }}</td>
                                            <td></td>
                                            <td>{{ $item->contract->person->nua_cua }}</td>
                                            <td>{{ explode(' ', $item->contract->person->last_name)[0] }}</td>
                                            <td>{{ count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : '' }}</td>
                                            <td></td>
                                            <td>{{ explode(' ', $item->contract->person->first_name)[0] }}</td>
                                            <td>{{ count(explode(' ', $item->contract->person->first_name)) > 1 ? explode(' ', $item->contract->person->first_name)[1] : '' }}</td>
                                            <td>BENI</td>
                                            @php
                                                $novelty = '';
                                                $novelty_date = '';
                                                if(date('Ym', strtotime($item->contract->start)) == $item->paymentschedule->period->name){
                                                    $novelty = 'I';
                                                    $novelty_date = date('d-m-Y', strtotime($item->contract->start));
                                                }
                                                if(date('Ym', strtotime($item->contract->finish)) == $item->paymentschedule->period->name){
                                                    $novelty = 'R';
                                                    $novelty_date = date('d-m-Y', strtotime($item->contract->finish));
                                                }
                                            @endphp
                                            <td>{{ $novelty }}</td>
                                            <td>{{ $novelty_date }}</td>
                                            <td class="text-right">{{ $item->worked_days }}</td>
                                            <td>N</td>
                                            <td class="text-right">
                                                @php
                                                    // Calcular edad
                                                    $now = \Carbon\Carbon::now();
                                                    $birthday = new \Carbon\Carbon($item->contract->person->birthday);
                                                    $age = $birthday->diffInYears($now);
                                                    $total_amount = $item->partial_salary + $item->seniority_bonus_amount;
                                                    
                                                    $total = 0;
                                                    // Si es menor a 65 años y aporta
                                                    if($age < 65 && $item->contract->person->afp_status == 1){
                                                        $total = $total_amount;
                                                    }
                                                @endphp
                                                {{ number_format($total, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $total = 0;
                                                    // Si es mayor o igual a 65 años y aporta
                                                    if($age >= 65 && $item->contract->person->afp_status == 1){
                                                        $total = $total_amount;
                                                    }
                                                @endphp
                                                {{ number_format($total, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $total = 0;
                                                    // Si es menor a 65 años y no aporta
                                                    if($age < 65 && $item->contract->person->afp_status == 0){
                                                        $total = $total_amount;
                                                    }
                                                @endphp
                                                {{ number_format($total, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $total = 0;
                                                    // Si es mayor a 65 años y aporta
                                                    if($age >= 65 && $item->contract->person->afp_status == 0){
                                                        $total = $total_amount;
                                                    }
                                                @endphp
                                                {{ number_format($total, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">{{ number_format(0, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($total_amount, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->partial_salary, 2, ',', '') }}</td>
                                            <td class="text-right">{{ number_format(0, 2, ',', '.') }}</td>
                                            @php
                                                $cont++;
                                            @endphp
                                        </tr> 
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @elseif($afp == 2)
                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N&deg;</th>
                                    <th>TIPO DOCUMENTO</th>
                                    <th>N&deg; DE DOCUMENTO</th>
                                    <th>ALFANUMÉRICO</th>
                                    <th>NUA/CUA</th>
                                    <th>APELLIDO PATERNO</th>
                                    <th>APELLIDO MATERNO</th>
                                    <th>APELLIDO DE CASADA</th>
                                    <th>PRIMER NOMBRE</th>
                                    <th>SEGUNDO NOMBRE</th>
                                    <th>NOVEDAD (I/R/L/S)</th>
                                    <th>FECHA DE NOVEDAD</th>
                                    <th>DÍAS COTIZADOS</th>
                                    <th>TOTAL GANADO</th>
                                    <th>TIPO DE COTIZANTE</th>
                                    <th>TIPO DE ASEGURADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cont = 1;
                                    if($group_by == 1){
                                        $data = $data->details->groupBy('contract.program_id');
                                        $data = $data->map(function($item, $key){
                                            $program = \App\Models\Program::find($key);
                                            return [
                                                'id' => $program->id,
                                                'programatic_category' => $program->programatic_category,
                                                'name' => $program->name,
                                                'direccion_administrativa' =>$program->direccion_administrativa->nombre,
                                                'details' => $item
                                            ];
                                        });
                                        $data = $data->sortBy('order');
                                    }elseif($group_by == 2){
                                        $data = $data->details->groupBy('paymentschedule.direccion_administrativa_id');
                                        $data = $data->map(function($item, $key){
                                            $da = \App\Models\Direccion::where('id', $key)->first();
                                            return [
                                                'id' => $da->id,
                                                'name' => $da->nombre,
                                                'order' => $da->orden,
                                                'details' => $item
                                            ];
                                        });
                                        $data = $data->sortBy('order');
                                    }else{
                                        $data = ['' => $data->details];
                                    }
                                    // dd($data);
                                @endphp
                                @foreach ($data as $value)
                                    @if ($group_by)
                                        <tr>
                                            <td colspan="23"><b>{{ $value['name'] }}</b></td>
                                        </tr>
                                    @endif
                                    @foreach($value['details']->groupBy('contract.person.ci') as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>CI</td>
                                            <td>{{ $item[0]->contract->person->ci }}</td>
                                            <td>{{ count(explode('-', $item[0]->contract->person->ci)) > 1 ? explode('-', $item[0]->contract->person->ci)[1] : '' }}</td>
                                            <td>{{ $item[0]->contract->person->nua_cua }}</td>
                                            <td>{{ explode(' ', $item[0]->contract->person->last_name)[0] }}</td>
                                            <td>{{ count(explode(' ', $item[0]->contract->person->last_name)) > 1 ? explode(' ', $item[0]->contract->person->last_name)[1] : '' }}</td>
                                            <td></td>
                                            <td>{{ explode(' ', $item[0]->contract->person->first_name)[0] }}</td>
                                            <td>{{ count(explode(' ', $item[0]->contract->person->first_name)) > 1 ? explode(' ', $item[0]->contract->person->first_name)[1] : '' }}</td>
                                            @php
                                                $novelty = '';
                                                $novelty_date = '';
                                                if(date('Ym', strtotime($item[0]->contract->start)) == $item[0]->paymentschedule->period->name){
                                                    $novelty = 'I';
                                                    $novelty_date = date('Ymd', strtotime($item[0]->contract->start));
                                                }
                                                if(date('Ym', strtotime($item[0]->contract->finish)) == $item[0]->paymentschedule->period->name){
                                                    $novelty = 'R';
                                                    $novelty_date = date('Ymd', strtotime($item[0]->contract->finish));
                                                }
                                            @endphp
                                            <td>{{ $novelty }}</td>
                                            <td>{{ $novelty_date }}</td>
                                            @php
                                                $worked_days = $item->sum('worked_days');
                                                $total_amount = $item->sum('partial_salary') + $item->sum('seniority_bonus_amount');
                                            @endphp
                                            <td class="text-right">{{ $worked_days }}</td>
                                            <td class="text-right">{{ number_format($total_amount, 2, ',', '.') }}</td>
                                            @php
                                                // Calcular edad
                                                $now = \Carbon\Carbon::now();
                                                $birthday = new \Carbon\Carbon($item[0]->contract->person->birthday);
                                                $age = $birthday->diffInYears($now);
                                                $type = '';
                                                if($age < 65 && $item[0]->contract->person->afp_status == 1){
                                                    $type = '1';
                                                }
                                                if($age >= 65 && $item[0]->contract->person->afp_status == 1){
                                                    $type = '8';
                                                }
                                                if($age < 65 && $item[0]->contract->person->afp_status == 0){
                                                    $type = 'C';
                                                }
                                                if($age >= 65 && $item[0]->contract->person->afp_status == 0){
                                                    $type = 'D';
                                                }
                                            @endphp
                                            <td class="text-right">{{ $type }}</td>
                                            <td></td>
                                            @php
                                                $cont++;
                                            @endphp
                                        </tr> 
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @elseif($afp == 3)
                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N&deg;</th>
                                    <th>TIPO DOCUMENTO (I/E)</th>
                                    <th>N&deg; DE DOCUMENTO</th>
                                    <th>COMPLEMENTO CI</th>
                                    <th>CUA</th>
                                    <th>APELLIDO PATERNO</th>
                                    <th>APELLIDO MATERNO</th>
                                    <th>APELLIDO DE CASADA</th>
                                    <th>PRIMER NOMBRE</th>
                                    <th>SEGUNDO NOMBRE</th>
                                    <th>TIPO DE NOVEDAD (I/R/L/S)</th>
                                    <th>FECHA DE NOVEDAD</th>
                                    <th>DÍAS COTIZADOS</th>
                                    <th>TIPO DE ASEGURADO (MINERO-M,ESTACIONAL-E,CONSULTOR DE LÍNEA-CL)</th>
                                    <th>TOTAL GANADO</th>
                                    <th>COTIZACIÓN ADICIONAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cont = 1;
                                    if($group_by == 1){
                                        $data = $data->details->groupBy('contract.program_id');
                                        $data = $data->map(function($item, $key){
                                            $program = \App\Models\Program::find($key);
                                            return [
                                                'id' => $program->id,
                                                'programatic_category' => $program->programatic_category,
                                                'name' => $program->name,
                                                'direccion_administrativa' =>$program->direccion_administrativa->nombre,
                                                'details' => $item
                                            ];
                                        });
                                        $data = $data->sortBy('order');
                                    }elseif($group_by == 2){
                                        $data = $data->details->groupBy('paymentschedule.direccion_administrativa_id');
                                        $data = $data->map(function($item, $key){
                                            $da = \App\Models\Direccion::where('id', $key)->first();
                                            return [
                                                'id' => $da->id,
                                                'name' => $da->nombre,
                                                'order' => $da->orden,
                                                'details' => $item
                                            ];
                                        });
                                        $data = $data->sortBy('order');
                                    }else{
                                        $data = ['' => $data->details];
                                    }
                                    // dd($data);
                                @endphp
                                @foreach ($data as $value)
                                    @if ($group_by)
                                        <tr>
                                            <td colspan="23"><b>{{ $value['name'] }}</b></td>
                                        </tr>
                                    @endif
                                    @foreach($value['details']->groupBy('contract.person.ci') as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>I</td>
                                            <td>{{ $item[0]->contract->person->ci }}</td>
                                            <td>{{ count(explode('-', $item[0]->contract->person->ci)) > 1 ? explode('-', $item[0]->contract->person->ci)[1] : '' }}</td>
                                            <td>{{ $item[0]->contract->person->nua_cua }}</td>
                                            <td>{{ explode(' ', $item[0]->contract->person->last_name)[0] }}</td>
                                            <td>{{ count(explode(' ', $item[0]->contract->person->last_name)) > 1 ? explode(' ', $item[0]->contract->person->last_name)[1] : '' }}</td>
                                            <td></td>
                                            <td>{{ explode(' ', $item[0]->contract->person->first_name)[0] }}</td>
                                            <td>{{ count(explode(' ', $item[0]->contract->person->first_name)) > 1 ? explode(' ', $item[0]->contract->person->first_name)[1] : '' }}</td>
                                            @php
                                                $novelty = '';
                                                $novelty_date = '';
                                                if(date('Ym', strtotime($item[0]->contract->start)) == $item[0]->paymentschedule->period->name){
                                                    $novelty = 'I';
                                                    $novelty_date = date('Ymd', strtotime($item[0]->contract->start));
                                                }
                                                if(date('Ym', strtotime($item[0]->contract->finish)) == $item[0]->paymentschedule->period->name){
                                                    $novelty = 'R';
                                                    $novelty_date = date('Ymd', strtotime($item[0]->contract->finish));
                                                }
                                            @endphp
                                            <td>{{ $novelty }}</td>
                                            <td>{{ $novelty_date }}</td>
                                            @php
                                                $worked_days = $item->sum('worked_days');
                                                $total_amount = $item->sum('partial_salary') + $item->sum('seniority_bonus_amount');
                                            @endphp
                                            <td class="text-right">{{ $worked_days }}</td>
                                            <td class="text-right">D</td>
                                            <td class="text-right">{{ number_format($total_amount, 2, ',', '.') }}</td>
                                            <td>0,00</td>
                                            @php
                                                $cont++;
                                            @endphp
                                        </tr> 
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
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