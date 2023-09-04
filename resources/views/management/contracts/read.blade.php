@extends('voyager::master')

@section('page_title', 'Ver Contrato')

@php
    switch ($contract->status) {
        case 'anulado':
            $label = 'danger';
            break;
        case 'elaborado':
            $label = 'default';
            // Si el creador del contrato es de una DA desconcentrada el siguiente estado es firmado, sino es enviado
            $netx_status = Auth::user()->direccion_administrativa_id ? 'firmado' : 'enviado';
            break;
        case 'enviado':
            $label = 'info';
            $netx_status = 'firmado';
            break;
        case 'firmado':
            $label = 'success';
            break;
        case 'concluido':
            $label = 'warning';
            break;
        default:
            $label = 'default';
            $netx_status = '';
            break;
    }
    $contract_finish = $contract->finish;
    $contract_duration_days = 0;
    if($contract->addendums->count()){
        $contract_finish = date('Y-m-d', strtotime($contract->addendums[0]->start.' -1 days'));
    }
    if ($contract_finish) {
        $contract_duration = contract_duration_calculate($contract->start, $contract_finish);
        $contract_duration_days = ($contract_duration->months * 30) + $contract_duration->days;
    }
@endphp

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Ver Contrato
        <label class="label label-{{ $label }}">{{ ucfirst($contract->status) }}</label>
        <a href="{{ route('contracts.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
    </h1>
@stop

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $code = $contract->code;
    $signature = \App\Models\Signature::with(['direccion_administrativa'])->where('direccion_administrativa_id', $contract->direccion_administrativa_id)->first();
@endphp

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Nombre completo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->first_name }} {{ $contract->person->last_name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">CI</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->ci }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Profesión</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->profession }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Telefono</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->phone ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->address }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Email</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->email ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Género</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->gender }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado civil</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->civil_status == 1 ? 'Soltero(a)' : 'Casado(a)' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->afp_type->name }} @if($contract->person->afp_status == 0) <label class="label label-danger">Jubilado</label>@endif</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">NUA/CUA</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->nua_cua }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">N&deg; de cuenta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->person->number_account ?? 'S/N' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Código de contrato</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>GAD-BENI N&deg; {{ $code }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Planilla</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ Str::upper($contract->type->name) }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cargo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->job ? $contract->job->name : $contract->cargo->Descripcion }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->direccion_administrativa_id ? $contract->direccion_administrativa->nombre : 'No definida' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Unidad administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->unidad_administrativa_id ? $contract->unidad_administrativa->nombre : 'No definida' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Programa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $contract->program->name }} {{ $contract->program->programatic_category ? ' ('.$contract->program->programatic_category.')' : '' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Inicio</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d', strtotime($contract->start)).' de '.$months[intval(date('m', strtotime($contract->start)))].' de '.date('Y', strtotime($contract->start)) }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Conclusión</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    {{ $contract_finish ? date('d', strtotime($contract_finish)).' de '.$months[intval(date('m', strtotime($contract_finish)))].' de '.date('Y', strtotime($contract_finish)) : 'No definido' }}
                                    @if ($contract_duration_days)
                                        <b>({{ $contract_duration_days }} días)</b>
                                    @endif
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
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Historial de pagos</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>N&deg;</th>
                                            <th>Planilla</th>
                                            <th>Periodo</th>
                                            <th>Días trabajados</th>
                                            <th>Sueldo</th>
                                            <th>Sueldo parcial</th>
                                            <th>Bono antigüedad</th>
                                            <th>Descuentos</th>
                                            <th>Líquido pagable</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cont = 1;
                                        @endphp
                                        @forelse ($contract->paymentschedules_details as $item)
                                            <tr>
                                                <td>{{ $cont }}</td>
                                                <td>{{ str_pad($item->paymentschedule->id, 6, "0", STR_PAD_LEFT).($item->paymentschedule->aditional ? '-A' : '') }}</td>
                                                <td>{{ $item->paymentschedule->period->name }}</td>
                                                <td>{{ $item->worked_days }}</td>
                                                <td>{{ number_format($item->salary, 2, ',', '.') }}</td>
                                                <td>{{ number_format($item->partial_salary, 2, ',', '.') }}</td>
                                                <td>{{ number_format($item->seniority_bonus_amount, 2, ',', '.') }}</td>
                                                <td>{{ number_format($item->labor_total + $item->rc_iva_amount + $item->faults_amount, 2, ',', '.') }}</td>
                                                <td>{{ number_format($item->liquid_payable, 2, ',', '.') }}</td>
                                                <td>
                                                    @php
                                                        switch ($item->status) {
                                                            case 'procesado':
                                                                $label = 'default';
                                                                break;
                                                            case 'habilitado':
                                                                $label = 'danger';
                                                                break;
                                                            case 'pagado':
                                                                $label = 'success';
                                                                break;
                                                            default:
                                                                $label = 'default';
                                                                break;
                                                        }
                                                    @endphp
                                                    <label class="label label-{{ $label }}">{{ ucfirst($item->status) }}</label>
                                                    @php
                                                        $cashiers_payment = \App\Models\CashiersPayment::with('cashier.user')->where('paymentschedules_detail_id', $item->id)->where('deleted_at', NULL)->first();
                                                    @endphp
                                                    @if ($cashiers_payment)
                                                    <br><small>Por {{ $cashiers_payment->cashier->user->name }} <br> {{ date('d-m-Y', strtotime($cashiers_payment->created_at)) }} <br> {{ date('H:i:s', strtotime($cashiers_payment->created_at)) }} </small>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php
                                                $cont++;
                                            @endphp
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted"><h5>No hay datos disponible</h5></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Historial de adendas</h3>
                            </div>
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>N&deg;</th>
                                            <th>ID</th>
                                            <th>Código</th>
                                            <th>Inicio</th>
                                            <th>Conclusión</th>
                                            <th>Observaciones</th>
                                            <th>Programa/Proyecto</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cont = 0;
                                        @endphp
                                        @forelse ($contract->addendums as $item)
                                            @php
                                                $cont++;
                                            @endphp
                                            <tr>
                                                <td>{{ $cont }}</td>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ str_pad($item->code, 8, "0", STR_PAD_LEFT) }}</td>
                                                <td>{{ date('d/M/Y', strtotime($item->start)) }}</td>
                                                <td>{{ date('d/M/Y', strtotime($item->finish)) }}</td>
                                                <td>{{ $item->observations }}</td>
                                                <td>{{ $item->program ? $item->program->name.($item->program->programatic_category ? ' ('.$item->program->programatic_category.')' : '') : '*Programa del contrato original' }}</td>
                                                <td>
                                                    @php
                                                        switch ($item->status) {
                                                            case 'elaborado':
                                                                $style = 'dark';
                                                                $label = 'Elaborada';
                                                                break;
                                                            case 'firmado':
                                                                $style = 'success';
                                                                $label = 'Firmada';
                                                                break;
                                                            case 'concluido':
                                                                $style = 'warning';
                                                                $label = 'Concluida';
                                                                break;
                                                            default:
                                                                $style = 'default';
                                                                $label = 'No definido';
                                                                break;
                                                        }
                                                    @endphp
                                                    <label class="label label-{{ $style }}">{{ $label }}</label>
                                                </td>
                                                <td class="no-sort no-click bread-actions text-right">
                                                    @php
                                                        // Tipo de contrato
                                                        if ($contract->procedure_type_id == 2) {
                                                            $type = 'consultor';
                                                        } else {
                                                            $type = 'eventual';
                                                        }
                                                        
                                                        // Tipo de documento
                                                        switch ($contract->direccion_administrativa_id) {
                                                            case 5:
                                                                $file = "addendum-sedeges";
                                                                break;
                                                            default:
                                                                $file = "addendum";
                                                                break;
                                                        }
                                                    @endphp
                                                    <a href="{{ route('contracts.print', ['id' => $contract->id, 'document' => $type.'.'.$file]) }}?type={{ $cont == 1 ? 'first' : '' }}" class="btn btn-default btn-sm" target="_blank">
                                                        <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                                    </a>
                                                    @if ((auth()->user()->hasPermission('edit_addendum_contracts') /*&& $item->status == 'elaborado'*/) || Auth::user()->role_id == 1)
                                                    <a href="#" data-toggle="modal" data-target="#update-addendum-modal" data-contract='@json($contract)' data-item='@json($item)' title="Editar" class="btn btn-sm btn-primary edit btn-edit-addendum">
                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                    </a>
                                                    @endif
                                                    @if (((auth()->user()->hasPermission('delete_addendum_contracts') && $item->status == 'elaborado') || (Auth::user()->role_id == 1 && ($item->status == 'elaborado' || $item->status == 'firmado'))) && $contract->addendums->count() == $cont)
                                                    <button type="button" data-toggle="modal" data-target="#delete-addendum-modal" data-id="{{ $item->id }}" title="Eliminar" class="btn btn-danger btn-delete-addendum">
                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted"><h5>No hay registros</h5></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Historial de rotaciones</h3>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Solicitante</th>
                                        <th>Destino</th>
                                        <th>Registro</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 0;
                                    @endphp
                                    @forelse ($contract->rotations as $rotation)
                                        @php
                                            $cont++;
                                        @endphp
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $rotation->id }}</td>
                                            <td>{{ date('d/m/Y', strtotime($rotation->date)) }}</td>
                                            <td>{{ $rotation->destiny->first_name }} {{ $rotation->destiny->last_name }}</td>
                                            <td>{{ $rotation->destiny_dependency }}</td>
                                            <td>
                                                {{ $rotation->user ? $rotation->user->name : '' }} <br>
                                                {{ date('d/m/Y H:i', strtotime($rotation->created_at)) }} <br>
                                                <small>{{ \Carbon\Carbon::parse($rotation->created_at)->diffForHumans() }}</small>
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                <a href="{{ url('admin/people/rotation/'.$rotation->id) }}" class="btn btn-default btn-sm" target="_blank"><i class="glyphicon glyphicon-print"></i> Imprimir</a>
                                                @if (auth()->user()->hasPermission('delete_rotation_people'))
                                                <button type="button" onclick="deleteItem('{{ route('people.rotation.delete', ['people' => $contract->person_id, 'id' => $rotation->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted"><h5>No hay registros</h5></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->role_id == 1)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Documentos generados</h3>
                            </div>
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>N&deg;</th>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cont = 0;
                                        @endphp
                                        @forelse ($contract->files as $item)
                                            @php
                                                $cont++;
                                            @endphp
                                            <tr>
                                                <td>{{ $cont }}</td>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td class="no-sort no-click bread-actions text-right">
                                                    <a title="Ver" class="btn btn-warning" href="{{ route('contracts.print', ['id' => $contract->id, 'document' => $item->name]) }}" target="_blank">
                                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                    </a>
                                                    <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('contracts.file.destroy', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted"><h5>No hay registros</h5></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Update addendums --}}
    <form action="{{ route('contracts.addendum.update') }}" id="update-addendum-form" class="form-submit" method="POST">
        @csrf
        <input type="hidden" name="id">
        <input type="hidden" name="contract_id" value="{{ $contract->id }}">
        <div class="modal modal-primary fade" tabindex="-1" id="update-addendum-modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-certificate"></i> Editar adenda</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="start">Inicio</label>
                                <input type="date" name="start" id="input-start" class="form-control" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="finish">Fin</label>
                                <input type="date" name="finish" id="input-finish" class="form-control" required>
                                <div id="label-duration"></div>
                            </div>
                            <div class="form-group col-md-12 div-eventual-consultor_sedeges">
                                <label for="applicant_id">Solicitante</label>
                                <select name="applicant_id" id="select-applicant_id" class="form-control"></select>
                            </div>

                            {{-- Eventual central --}}
                            <div class="form-group col-md-6 div-eventual">
                                <label for="nci_code">NCI</label>
                                <input type="text" name="nci_code" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-eventual">
                                <label for="nci_date">Fecha de NCI</label>
                                <input type="date" name="nci_date" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-eventual">
                                <label for="certification_code">Certificación presupuestaria</label>
                                <input type="text" name="certification_code" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-eventual">
                                <label for="certification_date">Fecha de certificación presupuestaria</label>
                                <input type="date" name="certification_date" class="form-control" >
                            </div>

                            {{-- Consultor SEDEGES --}}
                            <div class="form-group col-md-6 div-consultor_sedeges">
                                <label for="request_date">Fecha de solicitud</label>
                                <input type="date" name="request_date" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-consultor_sedeges">
                                <label for="legal_report_date">Fecha de informe legal</label>
                                <input type="date" name="legal_report_date" class="form-control" >
                            </div>

                            <div class="form-group col-md-6">
                                <label for="signature_id">Firma autorizada</label>
                                <select name="signature_id" id="select-signature_id" class="form-control">
                                    <option value="">Secretario(a) de Administración y Finanzas</option>
                                    @foreach (App\Models\Signature::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                    <option @if($item->direccion_administrativa_id == Auth::user()->direccion_administrativa_id) selected @endif value="{{ $item->id }}">{{ $item->designation }} {{ $item->name }} - {{ $item->job }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="signature_date">Fecha de suscripción de adenda</label>
                                <input type="date" name="signature_date" class="form-control" required>
                                <span id="alert-weekend" class="text-danger" style="font-weight: bold !important">Fin de semana</span>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="program_id">Programa/Proyecto</label>
                                <select name="program_id" id="select-program_id" class="form-control">
                                    <option value="">*Programa/Proyecto del contrato original</option>
                                    @foreach (App\Models\Program::where('direccion_administrativa_id', $contract->direccion_administrativa_id)->where('year', date('Y'))->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} {{ $item->programatic_category ? ' ('.$item->programatic_category.')' : '' }}</option>
                                    @endforeach
                                </select>
                                <small>Solo seleccione el programa/proyecto en caso de que cambie al del contrato principal</small>
                            </div>
                            <div class="form-group col-md-12 text-right" style="margin-bottom: 0px">
                                <div class="checkbox">
                                    <label><input type="checkbox" required> Aceptar y guardar</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark btn-submit">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Delete addendums --}}
    <form action="{{ route('contracts.addendum.delete') }}" id="form-delete-addendum" class="form-submit" method="POST">
        @csrf
        <input type="hidden" name="id">
        <input type="hidden" name="contract_id" value="{{ $contract->id }}">
        <div class="modal modal-danger fade" tabindex="-1" id="delete-addendum-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular la siguente adenda?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger btn-submit" value="Sí, eliminar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        .mce-edit-area{
            max-height: 200px !important;
            overflow-y: auto;
        }
    </style>
@endsection

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        var main_contract_duration = "{{ $contract_duration_days }}";
        $(document).ready(function () {
            moment.locale('es');
            $('#select-signature_id').select2({dropdownParent: $('#update-addendum-modal')});
            $('#select-program_id').select2({dropdownParent: $('#update-addendum-modal')});

            customSelect('#select-applicant_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, $('#update-addendum-modal'));

            $('.btn-edit-addendum').click(function(){
                let item = $(this).data('item');
                let contract = $(this).data('contract');
                $('#update-addendum-form input[name="id"]').val(item.id);
                $('#update-addendum-form input[name="start"]').val(item.start);
                $('#update-addendum-form input[name="finish"]').val(item.finish);
                $('#update-addendum-form input[name="signature_date"]').val(item.signature_date);
                $('#update-addendum-form select[name="signature_id"]').val(item.signature_id).trigger('change');
                if(item.program_id){
                    $('#select-program_id').val(item.program_id).trigger('change')
                }
                if(item.signature_id){
                    $('#select-signature_id').val(item.signature_id).trigger('change')
                }

                // Si es eventual
                if(contract.procedure_type_id == 5){
                    $('.div-eventual').fadeIn();
                }else{
                    $('.div-eventual').fadeOut();
                }

                // Si es eventual o es consultor del SEDEGES
                if(contract.procedure_type_id == 5 || (contract.procedure_type_id == 2 && contract.direccion_administrativa_id == 5)){
                    console.log(1)
                    $('.div-eventual-consultor_sedeges').fadeIn();
                }else{
                    $('.div-eventual-consultor_sedeges').fadeOut();
                }

                // Si es consultor del SEDEGES
                if(contract.procedure_type_id == 2 && contract.direccion_administrativa_id == 5){
                    $('.div-consultor_sedeges').fadeIn();
                }else{
                    $('.div-consultor_sedeges').fadeOut();
                }

                let signature_date = moment(item.signature_date, "YYYY-MM-DD");
                if(signature_date.weekday() > 4){
                    $('#alert-weekend').fadeIn();
                }else{
                    $('#alert-weekend').fadeOut();
                }

                getDuration(item.start, item.finish)
            });

            $('#input-finish').change(function(){
                let start = $('#input-start').val();
                let finish = $(this).val();
                getDuration(start, finish);
            });

            $('#update-addendum-form input[name="signature_date"]').change(function(){
            let date = moment($(this).val(), "YYYY-MM-DD");
            if(date.weekday() > 4){
                $('#alert-weekend').fadeIn('fast');
            }else{
                $('#alert-weekend').fadeOut('fast');
            }
        });

            $('.btn-delete-addendum').click(function(){
                let id = $(this).data('id');
                $('#form-delete-addendum input[name="id"]').val(id);
            });
        });

        function getDuration(start, finish){
            $.get("{{ url('admin/get_duration') }}/"+start+"/"+finish, function(res){
                let duration = (res.duration.months *30) + res.duration.days;
                    $('#label-duration').html(`<b class="text-${duration <= main_contract_duration ? 'success' : 'danger'}" style="font-weight: bold !important">${duration} días de duración</b>`);
                    if(duration <= main_contract_duration){
                        $('#update-addendum-form .btn-submit').removeAttr('disabled');
                    }else{
                        $('#update-addendum-form .btn-submit').attr('disabled', 'disabled');
                    }
            });
        }
    </script>
@stop
