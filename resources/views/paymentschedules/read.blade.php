@extends('voyager::master')

@section('page_title', 'Ver Planilla - '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT))

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-logbook"></i> Planilla - {{ str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT) }}
        <a href="{{ route('paymentschedules.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp; Volver a la lista
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              AFP's <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="?{{ $centralize ? '&centralize=true' : '' }}">Todas</a></li>
                <li><a href="?afp=1{{ $centralize ? '&centralize=true' : '' }}">Futuro</a></li>
                <li><a href="?afp=2{{ $centralize ? '&centralize=true' : '' }}">Previsión</a></li>
            </ul>
        </div>

        <button class="btn btn-danger" data-toggle="modal" data-target="#print-modal"><i class="glyphicon glyphicon-print"></i> Imprimir</button>

        {{-- Si la planilla está aprobada o está habiliatda para pago y parte de la planilla no ha sido habilitada se mouestra el botón de habilitación --}}
        @if (($data->status == 'aprobada' || ($data->status == 'habilitada' && $data->details->where('status', 'procesado')->where('deleted_at', NULL)->count()) > 0) && auth()->user()->hasPermission('enable_paymentschedules') && !$centralize)
            <button type="button" data-toggle="modal" data-target="#enable-modal" class="btn btn-success" style="margin-left: -10px; padding: 7px 15px"><i class="voyager-dollar"></i> Habilitar</button>
        @endif

        @if ($data->status == 'procesada' && auth()->user()->hasPermission('add_paymentschedules'))
            <button type="button" data-id="{{ $data->id }}" class="btn btn-dark btn-send" data-toggle="modal" data-target="#send-modal"><i class="glyphicon glyphicon-share-alt"></i> Enviar</button>
        @endif

        @if ($data->status == 'enviada' && auth()->user()->hasPermission('approve_paymentschedules'))
            <button type="button" data-id="{{ $data->id }}" class="btn btn-info btn-approve" data-toggle="modal" data-target="#approve-modal"><i class="glyphicon glyphicon-ok-circle"></i> Aprobar</button>
        @endif
    </h1>

    {{-- send modal --}}
    @include('paymentschedules.partials.modal-send-paymentschedule')


    {{-- approve modal --}}
    <form id="form-approve" action="{{ route('paymentschedules.update.status') }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="aprobada">
        <div class="modal modal-info fade" tabindex="-1" id="approve-modal" role="dialog">
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
                                                    <th>ID</th>
                                                    <th>Dirección administrativa</th>
                                                    <th>N&deg; de personas</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($paymentschedule as $item)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="id[]" value="{{ $item->id }}">
                                                        </td>
                                                        <td>{{ str_pad($item->id, 6, "0", STR_PAD_LEFT) }}</td>
                                                        <td>{{ $item->direccion_administrativa->NOMBRE }}</td>
                                                        <td>{{ $item->details->count() }}</td>
                                                        <td class="text-right">{{ number_format($item->details->sum('liquid_payable'), 2, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
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
    
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->direccion_administrativa->NOMBRE }}</p>
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
                                        <th rowspan="3">ITEM</th>
                                        <th rowspan="3">NIVEL</th>
                                        <th rowspan="3">APELLIDOS Y NOMBRES / CARGO</th>
                                        <th rowspan="3">CI</th>
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
                                        @endphp
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->contract->job ? $item->contract->job->id : '' }}</td>
                                            <td>{{ $item->job_level }}</td>
                                            <td>
                                                <b>{{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }}</b> <br>
                                                <small>{{ $item->job }}</small>
                                            </td>
                                            <td><b>{{ $item->contract->person->ci }}</b></td>
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
                                            
                                            {{-- Si la planilla es de consultoría al total aporte afp le sumamos el riego laboral (que es el mismo monto del riesgo común) --}}
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
                                        
                                        {{-- Si la planilla es de consultoría al total aporte afp le sumamos el riego laboral (que es el mismo monto del riesgo común) --}}
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
                                            <td><b>12000 SERVICIOS NO PERMANENTES</b></td>
                                            <td></td>
                                            <td class="text-right"><b><u>{{ number_format($total_amount + $lactation_amount + $total_social_security, 2, ',', '.') }}</u></b></td>
                                            {{-- ========== --}}
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>12000 Servicios no Permanentes</td>
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
                                            <td>12100 Personal Eventual</td>
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

    {{-- send modal --}}
    <form id="form-send" action="{{ route('paymentschedules.update.status') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $data->id }}">
        <input type="hidden" name="status" value="habilitada">
        <div class="modal fade" tabindex="-1" id="enable-modal" role="dialog">
            <div class="modal-dialog modal-success">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Desea habilitar la siguiente planilla para pago?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="afp">AFP</label>
                            <select name="afp" class="form-control select2">
                                <option value="">Todas las AFP</option>
                                <option @if($afp == 1) selected @endif value="1">Futuro</option>
                                <option @if($afp == 2) selected @endif value="2">Previsón</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="observations" class="form-control" rows="5" placeholder="Observaciones"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-success" value="Sí, Habilitar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- print modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="print-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glypicon glypicon-print"></i> Imprimir planilla</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="program_id">AFP</label>
                        <select name="afp" class="form-control select2">
                            <option value="">Todas</option>
                            <option value="1">Futuro</option>
                            <option value="2">Previsión</option>
                        </select>
                    </div>
                    @php
                        $contracts = collect();
                        foreach($data->details as $item){
                            $contracts->push($item->contract);
                        }
                    @endphp
                    <div class="form-group">
                        <label for="program_id">Programa/Proyecto</label>
                        <select name="program_id" class="form-control select2">
                            <option value="">Todos</option>
                            @foreach ($contracts->groupBy('program_id') as $item)
                                <option value="{{ $item[0]->program->id }}">{{ $item[0]->program->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="program_id">Agrupar por</label>
                        <select name="group" class="form-control select2">
                            <option value="">Ninguno</option>
                            <option value="1">Dirección administrativa</option>
                            <option value="2">Programas/Proyectos</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="button" class="btn btn-danger btn-print" value="Aceptar">
                </div>
            </div>
        </div>
    </div>
    
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

            $('.btn-print').click(function(){
                $('#print-modal').modal('toggle');
                let afp = '&afp='+$('#print-modal select[name="afp"] option:selected').val();
                let program = '&program='+$('#print-modal select[name="program_id"] option:selected').val();
                let group = '&group='+$('#print-modal select[name="group"] option:selected').val();
                console.log(afp,program,group)
                window.open(centralize+afp+program+group+'&print=true', '_blank');
            });

            $('.btn-send').click(function(){
                $('#form-send input[name="id"]').val($(this).data('id'));
            });
        });
    </script>
@stop
