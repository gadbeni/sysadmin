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
                                <p>{{ $signature ? $signature->direccion_administrativa->sigla : 'UJ/SDAF' }}/GAD-BENI N&deg; {{ $code }}</p>
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
                                <p>{{ $contract->program->name }}</p>
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
                                <p>{{ $contract->finish ? date('d', strtotime($contract->finish)).' de '.$months[intval(date('m', strtotime($contract->finish)))].' de '.date('Y', strtotime($contract->finish)) : 'No definido' }}</p>
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
                                                <td colspan="6">No hay datos disponible</td>
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
                                            <th>Código</th>
                                            <th>Inicio</th>
                                            <th>Conclusión</th>
                                            <th>Observaciones</th>
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
                                                <td>{{ str_pad($item->code, 8, "0", STR_PAD_LEFT) }}</td>
                                                <td>{{ date('d/M/Y', strtotime($item->start)) }}</td>
                                                <td>{{ date('d/M/Y', strtotime($item->finish)) }}</td>
                                                <td>{{ $item->observations }}</td>
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
                                                    @if (auth()->user()->hasPermission('edit_addendum_contracts') && $item->status == 'elaborado')
                                                    <a href="#" data-toggle="modal" data-target="#update-addendum-modal" data-item='@json($item)' title="Editar" class="btn btn-sm btn-primary edit btn-edit-addendum">
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
                                                <td colspan="7">No hay datos disponible</td>
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
    </div>

    {{-- Update addendums --}}
    <form action="{{ route('contracts.addendum.update') }}" id="form-update-addendum" class="form-submit" method="POST">
        @csrf
        <input type="hidden" name="id">
        <input type="hidden" name="contract_id" value="{{ $contract->id }}">
        <div class="modal modal-primary fade" tabindex="-1" id="update-addendum-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-certificate"></i> Editar adenda</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="start">Inicio</label>
                                <input type="date" name="start" class="form-control" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="finish">Fin</label>
                                <input type="date" name="finish" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="signature_id">Firma autorizada</label>
                                <select name="signature_id" id="select-signature_id" class="form-control">
                                    <option value="">Secretario(a) de Administración y Finanzas</option>
                                    @foreach (App\Models\Signature::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->designation }} {{ $item->name }} - {{ $item->job }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark" value="Aceptar">
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
    <script>
        $(document).ready(function () {
            $('#select-signature_id').select2({dropdownParent: $('#update-addendum-modal')});
            $('.btn-edit-addendum').click(function(){
                let item = $(this).data('item');
                $('#form-update-addendum input[name="id"]').val(item.id);
                $('#form-update-addendum input[name="start"]').val(item.start);
                $('#form-update-addendum input[name="finish"]').val(item.finish);
                $.extend({selector: '.richTextBox'}, {})
                tinymce.init(window.voyagerTinyMCE.getConfig({selector: '.richTextBox'}));
                $('#form-update-addendum select[name="signature_id"]').val(item.signature_id).trigger('change');
            });

            $('.btn-delete-addendum').click(function(){
                let id = $(this).data('id');
                $('#form-delete-addendum input[name="id"]').val(id);
            });
        });
    </script>
@stop
