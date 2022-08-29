@extends('voyager::master')

@section('page_title', 'Ver Planilla - '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : ''))

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-logbook"></i> Planilla - {{ str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : '') }}
        <a href="{{ route('paymentschedules.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp; Volver a la lista
        </a>
        <button class="btn btn-danger" data-toggle="modal" data-target="#print-modal"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

        @if ($data->status == 'procesada' && (auth()->user()->hasPermission('edit_paymentschedules') || Auth::user()->direccion_administrativa_id))
            <button type="button" data-id="{{ $data->id }}" class="btn btn-dark btn-send" data-toggle="modal" data-target="#send-modal"><i class="glyphicon glyphicon-share-alt"></i> Enviar</button>
        @endif

        @if ($data->status == 'enviada' && auth()->user()->hasPermission('approve_paymentschedules'))
            <button title="Aprobar planilla" type="button" data-id="{{ $data->id }}" class="btn btn-info btn-approve" data-toggle="modal" data-target="#approve-modal"><i class="glyphicon glyphicon-ok-circle"></i> Aprobar</button>
        @endif

        {{-- Si la planilla está aprobada o está habilitada para pago y parte de la planilla no ha sido habilitada se muestra el botón de habilitación --}}
        @if (($data->status == 'aprobada' || ($data->status == 'habilitada' && $data->details->where('status', 'procesado')->where('deleted_at', NULL)->count()) > 0) && auth()->user()->hasPermission('enable_paymentschedules') && !$centralize)
            <button type="button" data-toggle="modal" data-target="#enable-modal" class="btn btn-success" style="margin-left: -10px; padding: 7px 15px"><i class="voyager-dollar"></i> Habilitar</button>
        @endif

        {{-- Si la planilla está habiliatda y todos ninguno de los funcionarios está con pago procesado para pago se muestra el botón de pagada --}}
        @if (!$centralize && $data->status == 'habilitada' && $data->details->where('status', 'procesado')->where('deleted_at', NULL)->count() == 0 && auth()->user()->hasPermission('close_paymentschedules') )
            <button type="button" data-toggle="modal" data-target="#close-modal" class="btn btn-primary"><i class="voyager-lock"></i> Cerrar planilla</button>
        @endif
    </h1>

    {{-- send modal --}}
    @include('paymentschedules.partials.modal-send-paymentschedule')
    
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered panel-details" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->direccion_administrativa->nombre }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Periodo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->period->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo de planilla</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->procedure_type->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cantidad de personas</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->details->count() }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if (!$afp)
                                        Todas
                                    @elseif($afp == 1)
                                        Futuro
                                    @elseif($afp == 2)
                                        Previsión
                                    @endif
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @php
                                        switch ($data->status) {
                                            case 'anulada':
                                                $label = 'danger';
                                                break;
                                            case 'borrador':
                                                $label = 'default';
                                                break;
                                            case 'procesada':
                                                $label = 'info';
                                                break;
                                            case 'enviada':
                                                $label = 'primary';
                                                break;
                                            case 'aprobada':
                                                $label = 'warning';
                                                break;
                                            case 'habilitada':
                                                $label = 'success';
                                                break;
                                            case 'pagada':
                                                $label = 'dark';
                                                break;
                                            default:
                                                $label = 'default';
                                                break;
                                        }
                                    @endphp
                                    <label class="label label-{{ $label }}">{{ ucfirst($data->status) }}</label>
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-details">
                                <thead>
                                    <tr>
                                        <th rowspan="3">N&deg;</th>
                                        <th rowspan="3">APELLIDOS Y NOMBRES / CARGO</th>
                                        <th rowspan="3">CI</th>
                                        <th rowspan="3">ITEM</th>
                                        <th rowspan="3">NIVEL</th>
                                        <th rowspan="3">N&deg; NUA/CUA</th>
                                        <th rowspan="3">FECHA INGRESO</th>
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
                                        <th rowspan="3">TOTAL DESC.</th>
                                        <th rowspan="3">LÍQUIDO PAGABLE</th>

                                        {{-- Si es planilla de funcionamiento se muestran los aportes patronales--}}
                                        @if ($data->procedure_type_id == 5 && false)
                                        <th style="text-align: center" colspan="5">APORTES PATRONALES</th>
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

                                        @if ($data->procedure_type_id == 5 && false)
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
                                        @if ($data->procedure_type_id == 5 && false)
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
                                        $cont = 1;
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
                                        $labor_total = 0;
                                        $labor_rc_iva_amount = 0;
                                        $labor_faults_amount = 0;
                                        $labor_liquid_payable = 0;
                                        $employer_total = 0;
                                    @endphp
                                    @forelse ($data->procedure_type_id == 1 ? $data->details->sortBy('contract.job.id') : $data->details as $item)
                                        @php
                                            $total_partial_salary += $item->partial_salary;
                                            $total_seniority_bonus_amount += $item->seniority_bonus_amount;
                                            $total_amount += $item->partial_salary + $item->seniority_bonus_amount;
                                            $total_health += $item->health;
                                            $total_common_risk += $item->common_risk;
                                            $total_solidary_employer += $item->solidary_employer;
                                            $total_housing_employer += $item->housing_employer;
                                            $total_solidary += $item->solidary;
                                            $total_afp_commission += $item->afp_commission;
                                            $total_retirement += $item->retirement;
                                            $total_solidary_national += $item->solidary_national;
                                            $labor_total += $item->labor_total;
                                            $labor_rc_iva_amount += $item->rc_iva_amount;
                                            $labor_faults_amount += $item->faults_amount;
                                            $labor_liquid_payable += $item->liquid_payable;

                                            $employer_amount = $item->common_risk + $item->solidary_employer + $item->housing_employer + $item->health;
                                            if ($data->procedure_type_id == 1) {
                                                $employer_total += $employer_amount;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>
                                                <b>{{ $item->contract->person->last_name }} {{ $item->contract->person->first_name }}</b> <br>
                                                <small>{{ $item->job }}</small>
                                            </td>
                                            <td><b>{{ $item->contract->person->ci }}</b></td>
                                            <td>{{ $item->contract->job ? $item->contract->job->item : '' }}</td>
                                            <td>{{ $item->job_level }}</td>
                                            <td>{{ $item->contract->person->nua_cua }}</td>
                                            <td>{{ $item->contract->start }}</td>
                                            <td><b>{{ $item->worked_days }}</b></td>
                                            <td class="text-right">{{ number_format($item->salary, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($item->partial_salary, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ number_format($item->seniority_bonus_percentage, 0, ',', '.') }}%</td>
                                            <td class="text-right">{{ number_format($item->seniority_bonus_amount, 2, ',', '.') }}</td>
                                            <td class="text-right"><b>{{ number_format($item->partial_salary + $item->seniority_bonus_amount, 2, ',', '.') }}</b></td>
                                            <td class="text-right">{{ number_format($item->solidary, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>
                                            
                                            {{-- Si es planilla de consultoría --}}
                                            @if ($data->procedure_type_id == 2)
                                            <td class="text-right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>
                                            @endif

                                            <td class="text-right">{{ number_format($item->afp_commission, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->retirement, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->solidary_national, 2, ',', '.') }}</td>
                                            
                                            {{-- Si la planilla es de consultoría al total aporte afp le sumamos el riesgo laboral (que es el mismo monto del riesgo común) --}}
                                            <td class="text-right"><b>{{ number_format($item->labor_total + ($data->procedure_type_id == 2 ? $item->common_risk : 0 ), 2, ',', '.') }}</b></td>
                                            
                                            <td class="text-right">{{ number_format($item->rc_iva_amount, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->faults_quantity, floor($item->faults_quantity) < $item->faults_quantity ? 1 : 0, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->faults_amount, 2, ',', '.') }}</td>
                                            <td class="text-right">
                                                @php
                                                    // Si el planilla es permanenteo eventual restamos el total de aportes laborales al líquido pagable
                                                    $labor_total = 0;
                                                    if($data->procedure_type_id == 1 || $data->procedure_type_id == 5){
                                                        $labor_total = $item->labor_total;
                                                    }
                                                @endphp
                                                {{ number_format($labor_total + $item->rc_iva_amount + $item->faults_amount, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right"><b>{{ number_format($item->liquid_payable, 2, ',', '.') }}</b></td>

                                            {{-- Si es planilla de funcionamiento --}}
                                            @if ($data->procedure_type_id == 5 && false)
                                            <td class="text-right">{{ number_format($item->common_risk, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->housing_employer, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->solidary_employer, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item->health, 2, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($employer_amount, 2, ',', '.') }}</td>
                                            @endif
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    @php
                                        
                                    @endphp
                                    <tr>
                                        <td colspan="8" class="text-right"><b>TOTAL</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('salary'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_partial_salary, 2, ',', '.') }}</b></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"><b>{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_amount, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('solidary'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>
                                        
                                        {{-- Si es planilla de consultoría --}}
                                        @if ($data->procedure_type_id == 2)
                                        <td class="text-right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>
                                        @endif

                                        <td class="text-right"><b>{{ number_format($data->details->sum('afp_commission'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('retirement'), 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('solidary_national'), 2, ',', '.') }}</b></td>
                                        
                                        {{-- Si la planilla es de consultoría al total aporte afp le sumamos el riesgo laboral (que es el mismo monto del riesgo común) --}}
                                        <td class="text-right"><b>{{ number_format($data->details->sum('labor_total') + ($data->procedure_type_id == 2 ? $total_common_risk : 0), 2, ',', '.') }}</b></td>
                                        
                                        <td class="text-right"><b>{{ number_format($data->details->sum('rc_iva_amount'), 2, ',', '.') }}</b></td>
                                        <td></td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('faults_amount'), 2, ',', '.') }}</b></td>
                                        <td class="text-right">
                                                @php
                                                    // Si el planilla es permanente o eventual restamos el total de aportes laborales al líquido pagable
                                                    $labor_total = 0;
                                                    if($data->procedure_type_id == 1 || $data->procedure_type_id == 5){
                                                        $labor_total = $data->details->sum('labor_total');
                                                    }
                                                @endphp
                                            <b>{{ number_format($labor_total + $data->details->sum('rc_iva_amount') + $data->details->sum('faults_amount'), 2, ',', '.') }}</b>
                                        </td>
                                        <td class="text-right"><b>{{ number_format($data->details->sum('liquid_payable'), 2, ',', '.') }}</b></td>

                                        {{-- Si es planilla de funcionamiento --}}
                                        @if ($data->procedure_type_id == 5 && false)
                                        <td class="text-right"><b>{{ number_format($total_common_risk, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_housing_employer, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_solidary_employer, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($total_health, 2, ',', '.') }}</b></td>
                                        <td class="text-right"><b>{{ number_format($employer_total, 2, ',', '.') }}</b></td>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($data->procedure_type_id != 2)
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
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
                                            <td class="text-right"><b><u>{{ number_format($total_amount + $lactation_amount + $total_social_security, 2, ',', '.') }}</u></b></td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>{{ $data->procedure_type_id == 1 ? '11000 Empleados Permanentes' : '12000 Servicios no Permanentes' }}</td>
                                            <td></td>
                                            <td class="text-right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            @php
                                                $total_debe += $total_amount + $lactation_amount;
                                            @endphp
                                            <td class="text-right">{{ number_format($total_amount + $lactation_amount, 2, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>11200 Categorías</td>
                                            <td></td>
                                            <td class="text-right">{{ number_format($total_seniority_bonus_amount, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>11600 Asignaciones Familiares</td>
                                            <td></td>
                                            <td class="text-right">{{ number_format($lactation_amount, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>{{ $data->procedure_type_id == 1 ? '11700 Empleados Permanentes' : '12100 Personal Eventual' }}</td>
                                            <td></td>
                                            <td class="text-right">{{ number_format($total_partial_salary, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>1300 PREVISIÓN SOCIAL</b></td>
                                            <td></td>
                                            <td class="text-right"><b><u>{{ number_format($total_social_security, 2, ',', '.') }}</u></b></td>
                                            {{-- ========== --}}
                                            @php
                                                $total_debe += $total_social_security;
                                            @endphp
                                            <td class="text-right">{{ number_format($total_social_security, 2, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>13110 Aporte Patronal Caja de Salud</td>
                                            <td>10%</td>
                                            <td class="text-right">{{ number_format($total_health, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>13120 Prima de Riesgo Profesión - Regimen de Largo Plazo</td>
                                            <td>1.71%</td>
                                            <td class="text-right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>13131 Aporte Patronal Solidario</td>
                                            <td>3%</td>
                                            <td class="text-right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>13200 Aporte Patronal A.F.P. Vivienda</td>
                                            <td>2%</td>
                                            <td class="text-right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b><u>APORTES PATRONALES - SALUD</u></b></td>
                                            <td></td>
                                            <td class="text-right"><b><u>{{ number_format($total_health, 2, ',', '.') }}</u></b></td>
                                            {{-- ========== --}}
                                            @php
                                                $total_haber += $total_health;
                                            @endphp
                                            <td></td>
                                            <td class="text-right">{{ number_format($total_health, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Caja de Salud</td>
                                            <td>10%</td>
                                            <td class="text-right">{{ number_format($total_health, 2, ',', '.') }}</td>
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
                                            <td class="text-right">{{ number_format($total_afp, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><b><u>PREVISIÓN SOCIAL</u></b></td>
                                            <td></td>
                                            <td class="text-right"><b><u>{{ number_format($total_patronal, 2, ',', '.') }}</u></b></td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Riesgo Profesion a Largo Plazo</td>
                                            <td>1.71%</td>
                                            <td class="text-right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Aporte Patronal Solidario</td>
                                            <td>3%</td>
                                            <td class="text-right">{{ number_format($total_solidary_employer, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Aporte Patronal Vivienda</td>
                                            <td>2%</td>
                                            <td class="text-right">{{ number_format($total_housing_employer, 2, ',', '.') }}</td>
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
                                            <td class="text-right"><b><u>{{ number_format($labor_total, 2, ',', '.') }}</u></b></td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Aporte Solidario</td>
                                            <td>0.5%</td>
                                            <td class="text-right">{{ number_format($total_solidary, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Riesgo Común</td>
                                            <td>1.71%</td>
                                            <td class="text-right">{{ number_format($total_common_risk, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Comisión AFP</td>
                                            <td>0.5%</td>
                                            <td class="text-right">{{ number_format($total_afp_commission, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Aporte Jubilación</td>
                                            <td>10%</td>
                                            <td class="text-right">{{ number_format($total_retirement, 2, ',', '.') }}</td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Aporte Nacional Solidario</td>
                                            <td>1%</td>
                                            <td class="text-right">{{ number_format($total_solidary_national, 2, ',', '.') }}</td>
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
                                                $total_haber += $labor_rc_iva_amount;
                                            @endphp
                                            <td class="text-right">{{ number_format($labor_rc_iva_amount, 2, ',', '.') }}</td>
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
                                            $total_haber += $labor_faults_amount;
                                        @endphp
                                        <td class="text-right">{{ number_format($labor_faults_amount, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Líquido Pagable</td>
                                            <td></td>
                                            <td></td>
                                            {{-- ========== --}}
                                            <td></td>
                                            @php
                                                $total_haber += $labor_liquid_payable;
                                            @endphp
                                            <td class="text-right">{{ number_format($labor_liquid_payable, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b>TOTAL</b></td>
                                            <td class="text-right"><b>{{ number_format($total_debe, 2, ',', '.') }}</b></td>
                                            <td class="text-right"><b>{{ number_format($total_haber, 2, ',', '.') }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- approve modal --}}
    <form id="form-approve" class="form-submit" action="{{ route('paymentschedules.update.status') }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="aprobada">
        <div class="modal modal-info fade submit-modal" tabindex="-1" id="approve-modal" role="dialog">
            <div class="modal-dialog @if($data->centralize) modal-lg @endif">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="glyphicon glyphicon-ok-circle"></i> Desea aprobar la siguiente planilla?</h4>
                    </div>
                    <div class="modal-body">
                        {{-- Si el usuario es encargo de RRHH de una DA desconcentrada --}}
                        @if (Auth::user()->direccion_administrativa_id || !$data->centralize)
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="col-md-12">
                                <p class="text-muted">
                                    <b>Advertencia!</b> <br>
                                    Esta acción cambiará el estado de la planilla a <b>Aprobada</b> y no podrá generar más planillas para este tipo de planillas y periodo.
                                </p>
                            </div>
                        @else
                            <input type="hidden" name="centralize" value="1">
                            <div class="form-group">
                                @php
                                    $paymentschedule = \App\Models\Paymentschedule::with(['user', 'direccion_administrativa', 'period', 'procedure_type', 'details' => function($query){
                                        $query->where('deleted_at', NULL);
                                    }])
                                    ->where('centralize', 1)
                                    ->where('centralize_code', $data->centralize_code)
                                    ->where('status', 'enviada')
                                    ->where('deleted_at', NULL)->get();
                                @endphp

                                <div class="col-md-12">
                                    <h4>
                                        La planilla seleccionada está centralizada, seleccione las planilla que desea aprobar.
                                    </h4>
                                    <br>
                                </div>

                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>N&deg;</th>
                                                    <th>ID</th>
                                                    <th>Dirección administrativa</th>
                                                    <th>N&deg; de personas</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                    $count_people = 0;
                                                    $total_amount = 0;
                                                @endphp
                                                @foreach ($paymentschedule as $item)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="id[]" value="{{ $item->id }}">
                                                        </td>
                                                        <td>{{$cont}}</td>
                                                        <td>{{ str_pad($item->id, 6, "0", STR_PAD_LEFT).($item->aditional ? '-A' : '') }}</td>
                                                        <td>{{ $item->direccion_administrativa->nombre }}</td>
                                                        <td class="text-right">{{ $item->details->count() }}</td>
                                                        <td class="text-right">{{ number_format($item->details->sum('liquid_payable'), 2, ',', '.') }}</td>
                                                    </tr>
                                                    @php
                                                        $cont++;
                                                        $count_people += $item->details->count();
                                                        $total_amount += $item->details->sum('liquid_payable');
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="4"><b>TOTAL</b></td>
                                                    <td class="text-right"><b>{{ $count_people }}</b></td>
                                                    <td class="text-right"><b>{{ number_format($total_amount, 2, ',', '.') }}</b></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-info" value="Sí, aprobar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- enable modal --}}
    @include('paymentschedules.partials.modal-enable-paymentschedule', ['id' => $data->id])

    {{-- close modal --}}
    @include('paymentschedules.partials.modal-close-paymentschedule', ['id' => $data->id])

    {{-- print modal --}}
    <form id="form-print" method="post" action="#">
        <div class="modal modal-danger fade" tabindex="-1" id="print-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="glypicon glypicon-print"></i> Imprimir planilla</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="type_generate">Tipo de impresión</label>
                                <select name="type_generate" class="form-control select2">
                                    <option value="1">Normal</option>
                                    <option value="2">Contabilidad</option>
                                    <option value="3">Recursos Humanos</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="float_numbers">Cantidad de decimales</label>
                                <input type="number" name="float_numbers" class="form-control" min="2" max="5" value="2" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="afp">AFP</label>
                                <select name="afp" class="form-control select2">
                                    <option value="">Todas</option>
                                    <option value="1">Futuro</option>
                                    <option value="2">Previsión</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cc">Caja de salud</label>
                                <select name="cc" class="form-control select2">
                                    <option value="">Todas</option>
                                    <option value="1">Caja cordes</option>
                                    <option value="2">Otras</option>
                                </select>
                            </div>
                            @php
                                $contracts = collect();
                                foreach($data->details as $item){
                                    $contracts->push($item->contract);
                                }
                            @endphp
                            <div class="form-group col-md-6">
                                <label for="program_id">Programa/Proyecto</label>
                                <select name="program_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($contracts->groupBy('program_id') as $item)
                                        <option value="{{ $item[0]->program->id }}">{{ $item[0]->program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="group">Agrupar por</label>
                                <select name="group" class="form-control select2">
                                    <option value="">Ninguno</option>
                                    <option value="1">Dirección administrativa</option>
                                    <option value="2">Programas/Proyectos</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="radio-inline"><input type="radio" name="optradio" class="type_render" value="1" checked>Generar PDF</label>
                                <label class="radio-inline"><input type="radio" name="optradio" class="type_render" value="2">HTML</label>
                                <label class="radio-inline"><input type="radio" name="optradio" class="type_render" value="3">Excel</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>
        
    
@stop

@section('css')
    <style>
        .table-details th{
            font-size: 7px !important
        }
        .table-details td{
            font-size: 10px !important
        }
        .table-details tfoot td{
            font-size: 11px !important
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            var centralize = "{{ $centralize ? '?centralize=true' : '?' }}";

            $('#form-print').submit(function(e){
                e.preventDefault();
                $('#print-modal').modal('toggle');
                let afp = '&afp='+$('#print-modal select[name="afp"] option:selected').val();
                let cc = '&cc='+$('#print-modal select[name="cc"] option:selected').val();
                let program = '&program='+$('#print-modal select[name="program_id"] option:selected').val();
                let group = '&group='+$('#print-modal select[name="group"] option:selected').val();
                let type_generate = '&type_generate='+$('#print-modal select[name="type_generate"] option:selected').val();
                let float_numbers = '&float_numbers='+$('#print-modal input[name="float_numbers"]').val();
                let type_render = '&type_render='+$(".type_render:checked").val();
                window.open(centralize+afp+cc+program+group+type_generate+float_numbers+type_render+'&print=true', '_blank');
            });

            $('.form-submit').submit(function(e){
            $('.submit-modal').modal('hide');
            e.preventDefault();
            $('#div-results').loading({message: 'Cargando...'});
            $.post($(this).attr('action'), $(this).serialize(), function(res){
                if(res.message){
                    toastr.success(res.message);
                    $('.panel-details').loading({message: 'Actualizando...'});
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }else{
                    toastr.error(res.error);
                    $('#div-results').loading('toggle');
                }
            });
        });

            $('.btn-send').click(function(){
                $('#form-send input[name="id"]').val($(this).data('id'));
            });
        });
    </script>
@stop
