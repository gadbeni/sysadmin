@extends('layouts.template-print-alt', compact('type_render'))

@if($type_render != 3)
    @section('page_title', 'Impresión de planilla '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : '').' | '.env('APP_NAME', 'MAMORE'))
@else
    @section('page_title', 'Planilla '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : ''))
@endif

@section('content')
    <div class="content">
        @if($type_render != 3)
            <div class="header" >
                <table @if($type_render != 3) width="100%" @endif>
                    <tr>
                        @php
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            $year = Str::substr($data->period->name, 0, 4);
                            $month = Str::substr($data->period->name, 4, 2);
                        @endphp
                        <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
                        <td style="text-align: right">
                            <h3 style="margin: 0px">PLANILLA DE PAGO HABERES AL PERSONAL DEPENDIENTE GAD-BENI</h3>
                            @php
                                $afp_type = App\Models\Afp::find($afp);
                            @endphp
                            <span>CORRESPONDIENTE AL MES DE {{ strtoupper($months[intval($month)]) }} DE {{ $year }} @if ($afp) | AFP - {{ $afp_type->name }} @else | Todas las AFP's @endif @if($cc) | {{ $cc == 1 ? 'Caja Cordes'  : 'Otras Cajas de salud'}} @endif</span>
                            @if ($centralize)
                                <h3 style="margin: 0px">{{ Str::upper($data->procedure_type->name) }}</h3>
                            @else
                                <h3 style="margin: 0px">{{ $data->direccion_administrativa->nombre }} {!! $program ? '<br>'.$program->name : '' !!}</h3>
                                <span>{{ Str::upper($data->procedure_type->name) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center; width: 90px">
                            @php
                                $string_qr = 'Planilla '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : '').' | '.$data->period->name.' | '.($centralize ? 'Planilla centralizada' : $data->direccion_administrativa->nombre).' | '.$data->procedure_type->name;
                            @endphp
                            @if ($type_render == 1)
                                @php
                                    $qrcode = base64_encode(QrCode::format('svg')->size(70)->errorCorrection('H')->generate($string_qr));
                                @endphp
                                <img src="data:image/png;base64, {!! $qrcode !!}"> <br>
                            @else
                                {!! QrCode::size(70)->generate($string_qr); !!} <br>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td style="text-align: center">
                            <b>
                                {{ str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : '') }}
                                @if (!$centralize && $data->centralize)
                                    <br>
                                    <span style="color: red">({{ str_pad($data->centralize_code, 6, "0", STR_PAD_LEFT) }})</span>
                                @endif
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right">
                            <small style="font-size: 9px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        <div style="margin-top: 20px">
            <table @if($type_render != 3) class="table-details" border="1" cellpadding="2" cellspacing="0" @endif>
                <thead>
                    <tr>
                        <th rowspan="3">N&deg;</th>
                        <th rowspan="3">APELLIDOS Y NOMBRES / CARGO</th>
                        <th rowspan="3">CI</th>
                        <th rowspan="3">ITEM</th>
                        <th rowspan="3">NIVEL</th>
                        <th rowspan="3">N&deg; NUA/CUA</th>
                        <th rowspan="3" style="width: 55px">FECHA INGRESO</th>
                        <th rowspan="3">DÍAS TRAB.</th>
                        <th rowspan="3">HABER BÁSICO</th>
                        <th rowspan="3">TOTAL DÍAS TRAB.</th>
                        <th rowspan="3">%</th>
                        <th rowspan="3">BONO ANTIG.</th>
                        <th rowspan="3">TOTAL GANADO</th>
                        
                        {{-- Si es planilla de consultoría se agrega una columna--}}
                        <th style="text-align: center" colspan="{{ $data->procedure_type_id == 2 ? 6 : 5 }}">APORTES LABORALES</th>

                        <th rowspan="3">TOTAL APORTES AFP</th>
                        <th rowspan="3">RC-IVA</th>
                        <th colspan="2">FONDO SOCIAL</th>
                        <th rowspan="3">DESC. ADICIONAL</th>
                        <th rowspan="3">TOTAL DESC.</th>
                        <th rowspan="3">LÍQUIDO PAGABLE</th>

                        @if ($type_generate == 1)
                        <th rowspan="3">FIRMA</th>
                        @endif

                        {{-- Si es planilla de funcionamiento se muestran los aportes patronales--}}
                        @if ($type_generate == 2)
                        <th style="text-align: center" colspan="5">APORTES PATRONALES</th>
                        @endif

                        {{-- Si se imprime para RRHH --}}
                        @if ($type_generate == 3)
                        <th rowspan="3">N&deg; DE CUENTA</th>
                        @endif
                    </tr>
                    <tr>
                        <th>APORTE SOLIDARIO</th>
                        <th>RIESGO COMÚN</th>

                        {{-- Si es planilla de consultoría --}}
                        @if ($data->procedure_type_id == 2)
                        <th>RIESGO LABORAL</th>
                        @endif

                        <th>COMISIÓN AFP</th>
                        <th>APORTE JUBILACIÓN</th>
                        <th>APORTE NAL. SOLIDARIO</th>
                        <th rowspan="2">DÍAS</th>
                        <th rowspan="2">MULTAS</th>

                        @if ($type_generate == 2)
                        <th>RIESGO PROFESIONAL</th>
                        <th>APORTE VIVIENDA</th>
                        <th>APORTE SOLIDARIO</th>
                        <th>SEGURO DE SALUD</th>
                        <th>TOTAL</th>
                        @endif
                    </tr>
                    <tr>
                        <th>0.5%</th>
                        <th>1.71%</th>

                        {{-- Si es planilla de consultoría --}}
                        @if ($data->procedure_type_id == 2)
                        <th>1.71%</th>
                        @endif

                        <th>0.5%</th>
                        <th>10%</th>
                        <th>1%</th>

                        {{-- Si es planilla de funcionamiento --}}
                        @if ($data->procedure_type_id == 5 && $type_generate == 2)
                        <th>1.71%</th>
                        <th>2%</th>
                        <th>3%</th>
                        <th>10%</th>
                        <th>16.71%</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 0;
                        $total_salary = 0;
                        $total_partial_salary = 0;
                        $total_seniority_bonus_amount = 0;
                        $total_amount = 0;
                        $total_health = 0;
                        $total_common_risk = 0;
                        $total_solidary_employer = 0;
                        $total_housing_employer = 0;
                        $total_solidary = 0;
                        $total_afp_commission = 0;
                        $total_retirement = 0;
                        $total_solidary_national = 0;
                        $total_labor_total = 0;
                        $total_rc_iva_amount = 0;
                        $total_faults_amount = 0;
                        $total_additional_discounts = 0;
                        $total_liquid_payable = 0;
                        $employer_total = 0;
                    @endphp

                    @php
                        $data_group = [];
                        if($group){
                            $data_group = $group == 1 ? $data->details->groupBy('paymentschedule.direccion_administrativa_id') : $data->details->groupBy('program_id');

                            if($group == 1){
                                $data_group = $data_group->map(function($item, $key){
                                    $da = \App\Models\Direccion::where('id', $key)->first();
                                    return [
                                        'id' => $da->id,
                                        'name' => $da->nombre,
                                        'order' => $da->orden,
                                        'details' => $item
                                    ];
                                });
                                $data_group = $data_group->sortBy('order');
                            }else{
                                $data_group = $data_group->map(function($item, $key) use($data){
                                    $program = \App\Models\Program::find($key);
                                    return [
                                        'id' => $program->id,
                                        'programatic_category' => $program->programatic_category,
                                        'name' => $program->name,
                                        'details' => $item
                                    ];
                                });
                                $data_group = $data_group->sortBy('order');
                            }
                        }
                        $group_by = $group ? $data_group : [$data];
                    @endphp
                    
                    @forelse ($group_by as $key => $item_group)
                        @php
                            $details = $item_group['details'];
                            $group_by_alt = $group && $group == 1 ? $details->groupBy('contract.program.name') : [$details];

                            // Inicializar variables en caso de mostrar los datos agrupados
                            $total_amount_group = 0;
                            $total_solidary_group = 0;
                            $total_common_risk_group = 0;
                            $total_afp_commission_group = 0;
                            $total_retirement_group = 0;
                            $total_solidary_national_group = 0;
                            $total_labor_total_group = 0;
                            $total_rc_iva_amount_group = 0;
                            $total_faults_amount_group = 0;
                            $total_additional_discounts_group = 0;
                            $total_discount_group = 0;
                            $total_payable_liquid_group = 0;
                            $total_solidary_employer_group = 0;
                            $total_housing_employer_group = 0;
                            $total_health_group = 0;
                            $total_employer_amount_group = 0;
                        @endphp

                        {{-- Poner cabecear de la forma de agrupar si existe --}}
                        @if ($group)
                            <tr>
                                @if ($group == 1)
                                <td colspan="@if ($data->procedure_type_id == 2) 27 @else 26  @endif"><b>{{ $item_group['name'] }}</b></td>
                                @else
                                <td colspan="@if ($data->procedure_type_id == 2) 27 @else 26  @endif"><b>{{ $item_group['programatic_category'] }} - {{ $item_group['name'] }}</b></td>
                                @endif

                                @if ($type_generate == 2)
                                    <td colspan="5"></td>
                                @endif
                            </tr>
                        @endif

                        @foreach ($group_by_alt as $key => $item_group_program)
                            @if ($group == 1)
                                @php
                                    // Inicializar variables en caso de mostrar los datos agrupados
                                    $total_amount_group_program = 0;
                                    $total_solidary_group_program = 0;
                                    $total_common_risk_group_program = 0;
                                    $total_afp_commission_group_program = 0;
                                    $total_retirement_group_program = 0;
                                    $total_solidary_national_group_program = 0;
                                    $total_labor_total_group_program = 0;
                                    $total_rc_iva_amount_group_program = 0;
                                    $total_faults_amount_group_program = 0;
                                    $total_additional_discounts_group_program = 0;
                                    $total_discount_group_program = 0;
                                    $total_payable_liquid_group_program = 0;
                                    $total_solidary_employer_group_program = 0;
                                    $total_housing_employer_group_program = 0;
                                    $total_health_group_program = 0;
                                    $total_employer_amount_group_program = 0;
                                @endphp
                                <tr>
                                    <td colspan="@if ($data->procedure_type_id == 2) 27 @else 26  @endif" style="text-align: center"><b>{{ $key }}</b></td>
                                </tr>
                            @endif
                            @forelse ($item_group_program as $item)
                                @php
                                    $cont++;
                                    $total_salary += $item->salary;
                                    $total_partial_salary += $item->partial_salary;
                                    $total_seniority_bonus_amount += $item->seniority_bonus_amount;
                                    $total_amount += number_format($item->partial_salary + $item->seniority_bonus_amount, 2, '.', '');
                                    $total_health += $item->health;
                                    $total_common_risk += $item->common_risk;
                                    $total_solidary_employer += $item->solidary_employer;
                                    $total_housing_employer += $item->housing_employer;
                                    $total_solidary += $item->solidary;
                                    $total_afp_commission += $item->afp_commission;
                                    $total_retirement += $item->retirement;
                                    $total_solidary_national += $item->solidary_national;
                                    $total_labor_total += $item->labor_total;
                                    $total_rc_iva_amount += $item->rc_iva_amount;
                                    $total_faults_amount += $item->faults_amount;
                                    $total_additional_discounts += $item->additional_discounts;
                                    $total_liquid_payable += $item->liquid_payable;

                                    $employer_amount = number_format($item->common_risk + $item->solidary_employer + $item->housing_employer + $item->health, 2, '.', '');
                                    $employer_total += $employer_amount;

                                    if($group){
                                        $total_amount_group += number_format($item->partial_salary + $item->seniority_bonus_amount, 2, '.', '');
                                        $total_solidary_group += $item->solidary;
                                        $total_common_risk_group += $item->common_risk;
                                        $total_afp_commission_group += $item->afp_commission;
                                        $total_retirement_group += $item->retirement;
                                        $total_solidary_national_group += $item->solidary_national;
                                        $total_labor_total_group += number_format($item->labor_total + ($data->procedure_type_id == 2 ? $item->common_risk : 0 ), 2, '.', '');
                                        $total_rc_iva_amount_group += $item->rc_iva_amount;
                                        $total_faults_amount_group += $item->faults_amount;
                                        $total_additional_discounts_group += $item->additional_discounts;
                                        $total_discount_group += number_format($item->labor_total + $item->rc_iva_amount + $item->faults_amount, 2, '.', '');
                                        $total_payable_liquid_group += $item->liquid_payable;
                                        $total_solidary_employer_group += $item->solidary_employer;
                                        $total_housing_employer_group += $item->housing_employer;
                                        $total_health_group += $item->health;
                                        $total_employer_amount_group += $employer_amount;

                                        if($group == 1){
                                            $total_amount_group_program += number_format($item->partial_salary + $item->seniority_bonus_amount, 2, '.', '');
                                            $total_solidary_group_program += $item->solidary;
                                            $total_common_risk_group_program += $item->common_risk;
                                            $total_afp_commission_group_program += $item->afp_commission;
                                            $total_retirement_group_program += $item->retirement;
                                            $total_solidary_national_group_program += $item->solidary_national;
                                            $total_labor_total_group_program += number_format($item->labor_total + ($data->procedure_type_id == 2 ? $item->common_risk : 0 ), 2, '.', '');
                                            $total_rc_iva_amount_group_program += $item->rc_iva_amount;
                                            $total_faults_amount_group_program += $item->faults_amount;
                                            $total_additional_discounts_group_program += $item->additional_discounts;
                                            $total_discount_group_program += number_format($item->labor_total + $item->rc_iva_amount + $item->faults_amount, 2, '.', '');
                                            $total_payable_liquid_group_program += $item->liquid_payable;
                                            $total_solidary_employer_group_program += $item->solidary_employer;
                                            $total_housing_employer_group_program += $item->housing_employer;
                                            $total_health_group_program += $item->health;
                                            $total_employer_amount_group_program += $employer_amount;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>
                                        <b>{{ $item->contract->person->last_name }} {{ $item->contract->person->first_name }}</b> <br>
                                        <small>{{ $item->job }}</small>
                                    </td>
                                    <td><b>{{ $item->contract->person->ci }}</b></td>
                                    <td>{{ $item->contract->job ? $item->contract->job->id : '' }}</td>
                                    <td>{{ $item->job_level }}</td>
                                    <td>{{ $item->contract->person->nua_cua }}</td>
                                    <td>{{ $item->contract->start }}</td>
                                    <td><b>{{ $item->worked_days }}</b></td>
                                    <td style="text-align: right">{{ number_format($item->salary, 2, ',', '.') }}</td>
                                    <td style="text-align: right"><b>{{ number_format($item->partial_salary, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right">{{ number_format($item->seniority_bonus_percentage, 0, ',', '.') }}%</td>
                                    <td style="text-align: right">{{ number_format($item->seniority_bonus_amount, 2, ',', '.') }}</td>
                                    <td style="text-align: right"><b>{{ number_format($item->partial_salary + $item->seniority_bonus_amount, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right">{{ number_format($item->solidary, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>

                                    {{-- Si es planilla de consultoría --}}
                                    @if ($data->procedure_type_id == 2)
                                    <td style="text-align: right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>
                                    @endif

                                    <td style="text-align: right">{{ number_format($item->afp_commission, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->retirement, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->solidary_national, 2, ',', '.') }}</td>
                                    
                                    {{-- Si la planilla es de consultoría al total aporte afp le sumamos el riego laboral (que es el mismo monto del riesgo común) --}}
                                    <td style="text-align: right"><b>{{ number_format($item->labor_total + ($data->procedure_type_id == 2 ? $item->common_risk : 0 ), 2, ',', '.') }}</b></td>

                                    <td style="text-align: right">{{ number_format($item->rc_iva_amount, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->faults_quantity, floor($item->faults_quantity) < $item->faults_quantity ? 1 : 0, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->faults_amount, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->additional_discounts, 2, ',', '.') }}</td>
                                    
                                    {{-- Faults total --}}
                                    <td style="text-align: right">
                                        @php
                                            // Si el planilla es permanenteo eventual restamos el total de aportes laborales al líquido pagable
                                            $labor_total = 0;
                                            if($data->procedure_type_id == 1 || $data->procedure_type_id == 5){
                                                $labor_total = $item->labor_total;
                                            }
                                            $rc_iva_amount = $item->rc_iva_amount;
                                            $faults_amount = $item->faults_amount;
                                        @endphp
                                        {{ number_format($labor_total + $rc_iva_amount + $faults_amount + $item->additional_discounts, 2, ',', '.') }}
                                    </td>

                                    <td style="text-align: right"><b>{{ number_format($item->liquid_payable, 2, ',', '.') }}</b></td>
                                    
                                    @if ($type_generate == 1)
                                    <td style="width: 150px; height: 50px"></td>
                                    @endif

                                    {{-- Si es planilla de funcionamiento --}}
                                    @if ($type_generate == 2)
                                    <td style="text-align: right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->housing_employer, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->solidary_employer, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($item->health, 2, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($employer_amount, 2, ',', '.') }}</td>
                                    @endif

                                    @if ($type_generate == 3)
                                    <td>{{ $item->contract->person->number_account }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="27"><h4>No hay resultados</h4></td>
                                </tr>
                            @endforelse

                            {{-- Poner footer si está agrupada por DA --}}
                            @if ($group == 1)
                                <tr>
                                    <td colspan="12"><b>TOTAL PROGRAMA/PROYECTO</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_amount_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_solidary_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_common_risk_group_program, 2, ',', '.') }}</b></td>

                                    {{-- Si es planilla de consultoría --}}
                                    @if ($data->procedure_type_id == 2)
                                    <td style="text-align: right"><b>{{ number_format($total_common_risk_group_program, 2, ',', '.') }}</b></td>
                                    @endif

                                    <td style="text-align: right"><b>{{ number_format($total_afp_commission_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_retirement_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_solidary_national_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_labor_total_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_rc_iva_amount_group_program, 2, ',', '.') }}</b></td>
                                    <td></td>
                                    <td style="text-align: right"><b>{{ number_format($total_faults_amount_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_additional_discounts_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($data->procedure_type_id != 2 ? $total_discount_group_program : 0, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_payable_liquid_group_program, 2, ',', '.') }}</b></td>
                                    
                                    @if ($type_generate == 1)
                                    <td colspan="2"></td>
                                    @endif
                                    
                                    @if ($type_generate == 2)
                                    <td style="text-align: right"><b>{{ number_format($total_common_risk_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_housing_employer_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_solidary_employer_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_health_group_program, 2, ',', '.') }}</b></td>
                                    <td style="text-align: right"><b>{{ number_format($total_employer_amount_group_program, 2, ',', '.') }}</b></td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach

                        {{-- Poner footer de la forma de agrupar si existe --}}
                        @if ($group)
                            <tr>
                                <td colspan="12"><b>{{ $group == 1 ? 'TOTAL DIRECCIÓN ADMINISTRATIVA' : 'TOTAL PROGRAMA/PROYECTO' }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_amount_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_solidary_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_common_risk_group, 2, ',', '.') }}</b></td>

                                {{-- Si es planilla de consultoría --}}
                                @if ($data->procedure_type_id == 2)
                                <td style="text-align: right"><b>{{ number_format($total_common_risk_group, 2, ',', '.') }}</b></td>
                                @endif

                                <td style="text-align: right"><b>{{ number_format($total_afp_commission_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_retirement_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_solidary_national_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_labor_total_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_rc_iva_amount_group, 2, ',', '.') }}</b></td>
                                <td></td>
                                <td style="text-align: right"><b>{{ number_format($total_faults_amount_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_additional_discounts_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($data->procedure_type_id != 2 ? $total_discount_group : 0, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_payable_liquid_group, 2, ',', '.') }}</b></td>
                                
                                @if ($type_generate == 1)
                                <td colspan="2"></td>
                                @endif
                                
                                @if ($type_generate == 2)
                                <td style="text-align: right"><b>{{ number_format($total_common_risk_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_housing_employer_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_solidary_employer_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_health_group, 2, ',', '.') }}</b></td>
                                <td style="text-align: right"><b>{{ number_format($total_employer_amount_group, 2, ',', '.') }}</b></td>
                                @endif
                            </tr>
                        @endif
                    @empty
                        
                    @endforelse

                    <tr>
                        <td colspan="8" style="text-align: right"><b>TOTAL</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_salary, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_partial_salary, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"></td>
                        <td style="text-align: right"><b>{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_amount, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_solidary, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>

                        {{-- Si es planilla de consultoría --}}
                        @if ($data->procedure_type_id == 2)
                        <td style="text-align: right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>
                        @endif

                        <td style="text-align: right"><b>{{ number_format($total_afp_commission, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_retirement, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_solidary_national, 2, ',', '.') }}</b></td>
                        
                        {{-- Si la planilla es de consultoría al total aporte afp le sumamos el riego laboral (que es el mismo monto del riesgo común) --}}
                        <td style="text-align: right"><b>{{ number_format(number_format($total_labor_total, 2, '.', '') + ($data->procedure_type_id == 2 ? number_format($total_common_risk, 2, '.', '') : 0), 2, ',', '.') }}</b></td>

                        <td style="text-align: right"><b>{{ number_format($total_rc_iva_amount, 2, ',', '.') }}</b></td>
                        <td></td>
                        <td style="text-align: right"><b>{{ number_format($total_faults_amount, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_additional_discounts, 2, ',', '.') }}</b></td>
                        <td style="text-align: right">
                            @php
                                // Si el planilla es permanente o eventual restamos el total de aportes laborales al líquido pagable
                                $labor_total = 0;
                                if($data->procedure_type_id == 1 || $data->procedure_type_id == 5){
                                    $labor_total = $total_labor_total;
                                }
                            @endphp
                            <b>{{ number_format($labor_total + $total_rc_iva_amount + $total_faults_amount + $total_additional_discounts, 2, ',', '.') }}</b>
                        </td>
                        <td style="text-align: right"><b>{{ number_format($total_liquid_payable, 2, ',', '.') }}</b></td>
                        
                        @if ($type_generate == 1)
                        <td></td>
                        @endif

                        {{-- Si es planilla de funcionamiento --}}
                        @if ($type_generate == 2)
                        <td style="text-align: right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_housing_employer, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_solidary_employer, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($total_health, 2, ',', '.') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($employer_total, 2, ',', '.') }}</b></td>
                        @endif

                        @if ($type_generate == 3)
                        <td></td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($data->procedure_type_id != 2 && $type_render != 3)
            <div class="saltopagina"></div>
            <div class="header-resume">
                <table @if($type_render != 3) width="100%" @endif>
                    <tr>
                        @php
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            $year = Str::substr($data->period->name, 0, 4);
                            $month = Str::substr($data->period->name, 4, 2);
                        @endphp
                        <td><img src="{{ asset('images/icon.png') }}" alt="GADBENI" width="100px"></td>
                        <td style="text-align: right">
                            <h3 style="margin: 0px">PLANILLA DE PAGO HABERES AL PERSONAL DEPENDIENTE GAD-BENI</h3>
                            <span>CORRESPONDIENTE AL MES DE {{ strtoupper($months[intval($month)]) }} DE {{ $year }} @if ($afp) | AFP - {{ $afp_type->name }} @else | Todas las AFP's @endif @if($cc) | {{ $cc == 1 ? 'Caja Cordes'  : 'Otras Cajas de salud'}} @endif </span>
                            @if ($centralize)
                                <h3 style="margin: 0px">{{ Str::upper($data->procedure_type->name) }}</h3>
                            @else
                                <h3 style="margin: 0px">{{ $data->direccion_administrativa->nombre }} {!! $program ? '<br>'.$program->name : '' !!}</h3>
                                <span>{{ Str::upper($data->procedure_type->name) }}</span>
                            @endif
                        </td>
                        <td style="text-align:center; width: 90px">
                            @php
                                $string_qr = 'Planilla '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : '').' | '.$data->period->name.' | '.($centralize ? 'Planilla centralizada' : $data->direccion_administrativa->nombre).' | '.$data->procedure_type->name;
                            @endphp
                            @if ($type_render == 1)
                                @php
                                    $qrcode = base64_encode(QrCode::format('svg')->size(70)->errorCorrection('H')->generate($string_qr));
                                @endphp
                                <img src="data:image/png;base64, {!! $qrcode !!}"> <br>
                            @else
                                {!! QrCode::size(70)->generate($string_qr); !!} <br>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td style="text-align: center"><b>{{ str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : '') }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right">
                            <small style="font-size: 9px; font-weight: 100">Impreso por: {{ Auth::user()->name }} {{ date('d/M/Y H:i:s') }}</small>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 10px">
                <table @if($type_render != 3) class="table-resumen" cellpadding="2" cellspacing="0" width="70%" @endif>
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center">RESUMEN</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center">Descripción</th>
                            <th class="text-center">Debe</th>
                            <th class="text-center">Haber</th>
                        </tr>
                    </thead>
                    @php
                        $lactation_amount = 0;
                        $total_social_security = $total_health + $total_common_risk + $total_solidary_employer + $total_housing_employer;
                        $total_debe = 0;
                        $total_haber = 0;
                    @endphp
                    <tbody>
                        <tr>
                            <td><b>{{ $data->procedure_type_id == 1 ? '10000 SERVICIOS PERSONALES' : '12000 SERVICIOS NO PERMANENTES' }}</b></td>
                            <td></td>
                            <td style="text-align: right"><b><u>{{ number_format($total_amount + $lactation_amount + $total_social_security, 2, ',', '.') }}</u></b></td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>{{ $data->procedure_type_id == 1 ? '11000 Empleados Permanentes' : '12000 Servicios no Permanentes' }}</td>
                            <td></td>
                            <td style="text-align: right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            @php
                                $total_debe += $total_amount + $lactation_amount;
                            @endphp
                            <td style="text-align: right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11200 Categorías</td>
                            <td></td>
                            <td style="text-align: right">{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11600 Asignaciones Familiares</td>
                            <td></td>
                            <td style="text-align: right">{{ number_format($lactation_amount, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>{{ $data->procedure_type_id == 1 ? '11700 Empleados Permanentes' : '12100 Personal Eventual' }}</td>
                            <td></td>
                            <td style="text-align: right">{{ number_format($total_partial_salary, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>1300 PREVISIÓN SOCIAL</b></td>
                            <td></td>
                            <td style="text-align: right"><b><u>{{ number_format($total_social_security, 2, ',', '.') }}</u></b></td>
                            {{-- ========== --}}
                            @php
                                $total_debe += $total_social_security;
                            @endphp
                            <td style="text-align: right">{{ number_format($total_social_security, 2, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>13110 Aporte Patronal Caja de Salud</td>
                            <td>10%</td>
                            <td style="text-align: right">{{ number_format($total_health, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>13120 Prima de Riesgo Profesión - Regimen de Largo Plazo</td>
                            <td>1.71%</td>
                            <td style="text-align: right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>13131 Aporte Patronal Solidario</td>
                            <td>3%</td>
                            <td style="text-align: right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>13200 Aporte Patronal A.F.P. Vivienda</td>
                            <td>2%</td>
                            <td style="text-align: right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b><u>APORTES PATRONALES - SALUD</u></b></td>
                            <td></td>
                            <td style="text-align: right"><b><u>{{ number_format($total_health, 2, ',', '.') }}</u></b></td>
                            {{-- ========== --}}
                            @php
                                $total_haber += $total_health;
                            @endphp
                            <td></td>
                            <td style="text-align: right">{{ number_format($total_health, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Caja de Salud</td>
                            <td>10%</td>
                            <td style="text-align: right">{{ number_format($total_health, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>

                        @php
                            $total_patronal = $total_common_risk + $total_solidary_employer + $total_housing_employer;
                            $total_afp = $labor_total + $total_patronal;

                            $total_haber += $total_afp;
                        @endphp
                        <tr>
                            <td><b><u>APORTES PATRONALES AFP</u></b></td>
                            <td></td>
                            <td></td>
                            {{-- ========== --}}
                            <td></td>
                            <td style="text-align: right">{{ number_format($total_afp, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><b><u>PREVISIÓN SOCIAL</u></b></td>
                            <td></td>
                            <td style="text-align: right"><b><u>{{ number_format($total_patronal, 2, ',', '.') }}</u></b></td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Riesgo Profesion a Largo Plazo</td>
                            <td>1.71%</td>
                            <td style="text-align: right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Aporte Patronal Solidario</td>
                            <td>3%</td>
                            <td style="text-align: right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Aporte Patronal Vivienda</td>
                            <td>2%</td>
                            <td style="text-align: right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b><u>APORTES LABORAL AFP</u></b></td>
                            <td></td>
                            <td></td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b><u>PREVISIÓN SOCIAL</u></b></td>
                            <td></td>
                            <td style="text-align: right"><b><u>{{ number_format($labor_total, 2, ',', '.') }}</u></b></td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Aporte Solidario</td>
                            <td>0.5%</td>
                            <td style="text-align: right">{{ number_format($total_solidary, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Riesgo Común</td>
                            <td>1.71%</td>
                            <td style="text-align: right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Comisión AFP</td>
                            <td>0.5%</td>
                            <td style="text-align: right">{{ number_format($total_afp_commission, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Aporte Jubilación</td>
                            <td>10%</td>
                            <td style="text-align: right">{{ number_format($total_retirement, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Aporte Nacional Solidario</td>
                            <td>1%</td>
                            <td style="text-align: right">{{ number_format($total_solidary_national, 2, ',', '.') }}</td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>OTROS DESCUENTOS LABORALES</b></td>
                            <td></td>
                            <td></td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>RC-IVA</td>
                            <td></td>
                            <td></td>
                            {{-- ========== --}}
                            <td></td>
                            @php
                                $total_haber += $total_rc_iva_amount;
                            @endphp
                            <td style="text-align: right">{{ number_format($total_rc_iva_amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Descuento de no Ley</td>
                            <td></td>
                            <td></td>
                            {{-- ========== --}}
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Multas y Sanciones</td>
                            <td></td>
                            <td></td>
                            {{-- ========== --}}
                            <td></td>
                            @php
                            $total_haber += $total_faults_amount + $total_additional_discounts;
                        @endphp
                        <td style="text-align: right">{{ number_format($total_faults_amount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Líquido Pagable</td>
                            <td></td>
                            <td></td>
                            {{-- ========== --}}
                            <td></td>
                            @php
                                $total_haber += $total_liquid_payable;
                            @endphp
                            <td style="text-align: right">{{ number_format($total_liquid_payable, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3"><b>TOTAL</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_debe, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($total_haber, 2, ',', '.') }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@section('css')
    <style>
        .content {
            /* padding-left: 30px;
            padding-right: 30px; */
            font-size: 12px;
        }
        .header{
            width: 100%;
        }
        /* .header-resume{
            display: none;
        } */
        .table-details th{
            font-size: 7px !important
        }
        .table-details td{
            font-size: 9px !important
        }
        .table-details tfoot td{
            font-size: 11px !important
        }
        .table-resumen{
            font-size: 10px !important;
            margin-top: 40px;
            /* margin-bottom: 100px; */
        }
        table, th, td {
            border-collapse: collapse;
        }
        .saltopagina{
            /* display: none; */
            display: block;
            page-break-before: always;
        }
        
        /* @media print{
            @page {
                size: landscape;
            }
            .content {
                margin-left: 25px;
                margin-right: -20px;
            }
            .header{
                top: 0px;
            }
            .header-resume{
                display: block;
            }
            .table-details th{
                font-size: 5px !important
            }
            .table-details td{
                font-size: 7px !important
            }
            .table-details tfoot td{
                font-size: 10px !important
            }
            .table-resumen{
                margin-top: 0px;
                margin-bottom: 0px;
            }
            .saltopagina{
                display: block;
                page-break-before: always;
            }
        } */
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection