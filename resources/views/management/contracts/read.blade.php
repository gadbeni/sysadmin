@extends('voyager::master')

@section('page_title', 'Ver Contrato')

@if (auth()->user()->hasPermission('read_contracts'))

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
                                    <h3 class="panel-title">Profesi??n</h3>
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
                                    <h3 class="panel-title">Direcci??n</h3>
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
                                    <h3 class="panel-title">G??nero</h3>
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
                                    <p>{{ $contract->person->afp == 1 ? 'Futuro' : 'BBVA Previsi??n' }} @if($contract->person->afp_status == 0) <label class="label label-danger">Jubilado</label>@endif</p>
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
                                    <h3 class="panel-title">C??digo de contrato</h3>
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
                                    <h3 class="panel-title">Direcci??n administrativa</h3>
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
                                    <h3 class="panel-title">Conclusi??n</h3>
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
                                    <h3 class="panel-title">Historial de inamovilidades</h3>
                                </div>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>N&deg;</th>
                                            <th>Planilla</th>
                                            <th>Periodo</th>
                                            <th>D??as trabajados</th>
                                            <th>Sueldo</th>
                                            <th>Sueldo parcial</th>
                                            <th>Bono antig??edad</th>
                                            <th>Descuentos</th>
                                            <th>L??quido pagable</th>
                                            <th class="text-right">Estado</th>
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
                                                        // dd($cashiers_payment);
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
    @stop

    @section('javascript')
        <script>
            $(document).ready(function () {
                
            });
        </script>
    @stop
@endif
