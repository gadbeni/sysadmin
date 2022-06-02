@extends('voyager::master')

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        {{-- Vista de cajero(a) --}}
        @if (Auth::user()->role_id == 4 || Auth::user()->role_id == 5)
            @php
                $cashier = \App\Models\Cashier::with(['payments.aguinaldo', 'payments.stipend', 'movements' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'payments' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'vault_details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }])
                ->where('user_id', Auth::user()->id)
                ->where('status', '<>', 'cerrada')
                ->where('deleted_at', NULL)->first();
                // dd($cashier);
            @endphp
            @if ($cashier)
                @if ($cashier->status == 'abierta' || $cashier->status == 'apertura pendiente')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body">
                                    @php
                                        $cashier_in = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('amount');
                                        $cashier_out = $cashier->movements->where('type', 'egreso')->where('deleted_at', NULL)->sum('amount');
                                        $payments = $cashier->payments->where('deleted_at', NULL)->sum('amount');
                                        $movements = $cashier_in - $cashier_out;
                                        $total = $movements - $payments;
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2>{{ $cashier->title }}</h2>
                                        </div>
                                        @if ($cashier->status == 'abierta')
                                            <div class="col-md-6 text-right">
                                                <button type="button" data-toggle="modal" data-target="#transfer-modal" class="btn btn-success">Transferir <i class="voyager-forward"></i></button>
                                                <a href="{{ route('cashiers.close', ['cashier' => $cashier->id]) }}" class="btn btn-danger">Cerrar <i class="voyager-lock"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($cashier->status == 'abierta')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 50px">
                                                <table width="100%" cellpadding="20">
                                                    <tr>
                                                        <td><small>Ingresos</small></td>
                                                        <td class="text-right"><h3>{{ number_format($cashier_in, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Egresos</small></td>
                                                        <td class="text-right"><h3>{{ number_format($cashier_out, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>Pagos realizados ({{ $cashier->payments->where('deleted_at', NULL)->count() }})</small></td>
                                                        <td class="text-right"><h3>{{ number_format($payments, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                    <tr>
                                                        <td><small>TOTAL EN CAJA</small></td>
                                                        <td class="text-right"><h3>{{ number_format($total, 2, ',', '.') }} <small>Bs.</small></h3></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <canvas id="myChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <h3>Detalle de pagos realizados</h3>
                                        <table class="table table-bordered table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>N&deg;</th>
                                                    <th>Nombre completo</th>
                                                    <th>CI</th>
                                                    <th>Planilla</th>
                                                    <th>Fecha de pago</th>
                                                    <th style="text-align: right">Monto</th>
                                                    <th style="text-align: right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 0;
                                                    $total = 0;
                                                @endphp
                                                @foreach ($cashier->payments as $payment)
                                                    @php
                                                        $data = DB::connection('mysqlgobe')->table('planillahaberes')->where('id', $payment->planilla_haber_id)->first();

                                                        $cont++;
                                                        if(!$payment->deleted_at){
                                                            $total += $payment->amount;
                                                        }
                                                        $months = [
                                                            '01' => 'Enero',
                                                            '02' => 'Febrero',
                                                            '03' => 'Marzo',
                                                            '04' => 'Abril',
                                                            '05' => 'Mayo',
                                                            '06' => 'Junio',
                                                            '07' => 'Julio',
                                                            '08' => 'Agosto',
                                                            '09' => 'Septiembre',
                                                            '10' => 'Octubre',
                                                            '11' => 'Noviembre',
                                                            '12' => 'Diciembre'
                                                        ];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $cont }}</td>
                                                        <td>
                                                            @if ($payment->planilla_haber_id)
                                                                {{ $data->Nombre_Empleado  }} <br> <small>{{ $data->Direccion_Administrativa }}</small>
                                                            @elseif($payment->aguinaldo_id)
                                                                {{ $payment->aguinaldo->funcionario }}
                                                            @elseif($payment->stipend_id)
                                                                {{ $payment->stipend->funcionario }}
                                                            @elseif($payment->paymentschedulesdetail)
                                                                {{ $payment->paymentschedulesdetail->contract->person->first_name }} {{ $payment->paymentschedulesdetail->contract->person->last_name }}
                                                            @endif
                                                            <br>
                                                            @if ($payment->deleted_at)
                                                                <label class="label label-danger">Anulado</label>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($payment->planilla_haber_id)
                                                                {{ $data->CedulaIdentidad }}
                                                            @elseif($payment->aguinaldo_id)
                                                                {{ $payment->aguinaldo->ci }}
                                                            @elseif($payment->stipend_id)
                                                                {{ $payment->stipend->ci }}
                                                            @elseif($payment->paymentschedulesdetail)
                                                                {{ $payment->paymentschedulesdetail->contract->person->ci }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($payment->planilla_haber_id)
                                                                {{ $months[$data->Mes].'/'.$data->Anio }}
                                                            @elseif($payment->aguinaldo_id)
                                                                Aguinaldo
                                                            @elseif($payment->stipend_id)
                                                                Estipendio
                                                            @elseif($payment->paymentschedulesdetail)
                                                                {{ str_pad($payment->paymentschedulesdetail->paymentschedule->centralize_code ?? $payment->paymentschedulesdetail->paymentschedule->id, 6, "0", STR_PAD_LEFT).($payment->paymentschedulesdetail->paymentschedule->aditional ? '-A' : '') }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                        <td style="text-align: right">{{ number_format($payment->amount, 2, ',', '.') }}</td>
                                                        <td class="text-right">
                                                            @if ($data)
                                                                @if (!$payment->deleted_at)
                                                                    <button type="button" onclick="print_recipe({{ $payment->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                                                @else
                                                                    <button type="button" onclick="print_recipe_delete({{ $payment->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Informe de anulación</button>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" style="text-align: right"><b>TOTAL</b></td>
                                                    <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <h3>Detalle de movimientos de caja</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>N&deg;</th>
                                                    <th>Detalle</th>
                                                    <th>Tipo</th>
                                                    <th class="text-right">Monto</th>
                                                    <th class="text-right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                    $total_movements = 0;
                                                @endphp
                                                @foreach ($cashier->movements->where('deleted_at', NULL) as $item)
                                                    <tr>
                                                        <td>{{ $cont }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td><label class="label label-{{ $item->type == 'ingreso' ? 'success' : 'danger' }}">{{ $item->type }}</label></td>
                                                        <td class="text-right">{{ $item->amount }}</td>
                                                        <td class="text-right">
                                                            <button type="button" onclick="print_transfer({{ $item->id }})" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $cont++;
                                                        if($item->type == 'ingreso'){
                                                            $total_movements += $item->amount;
                                                        }else{
                                                            $total_movements -= $item->amount;
                                                        }
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="3"><h5>TOTAL</h5></td>
                                                    <td  colspan="2" class="text-right"><h4><small>Bs.</small> {{ number_format($total_movements, 2, ',', '.') }}</h4></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="form-pagar" action="{{ route('cashiers.amount.store') }}" method="post">
                            @csrf
                            @if ($cashier)
                            <input type="hidden" name="id" value="{{ $cashier->id }}">
                            <input type="hidden" name="redirect" value="voyager.dashboard">
                            @endif
                            <div class="modal fade" tabindex="-1" id="transfer-modal" role="dialog">
                                <div class="modal-dialog modal-success">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="voyager-forward"></i> Realizar transferencia</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="cashier_id">Caja de destino</label>
                                                <select name="cashier_id" class="form-control select2" required>
                                                    <option value="" disabled selected>-- Seleccione la caja --</option>
                                                    @if ($cashier)
                                                    @foreach (\App\Models\Cashier::with('user')->where('closed_at', NULL)->where('deleted_at', NULL)->get() as $item)
                                                    <option value="{{ $item->id }}" @if($item->id == $cashier->id) disabled @endif >{{ $item->title }} - {{ $item->user->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Monto</label>
                                                <input type="number" step="0.1" min="0.1" name="amount" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">descripción</label>
                                                <textarea name="description" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Transferir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-bordered">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 50px">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Corte</th>
                                                            <th>Cantidad</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $cash = ['200', '100', '50', '20', '10', '5', '2', '1', '0.5', '0.2', '0.1'];
                                                        $total = 0;
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($cash as $item)
                                                        <tr>
                                                            <td><h4 style="margin: 0px"><img src=" {{ url('images/cash/'.$item.'.jpg') }} " alt="{{ $item }} Bs." width="70px"> {{ $item }} Bs. </h4></td>
                                                            <td>
                                                                @php
                                                                    $details = $cashier->vault_details->cash->where('cash_value', $item)->first();
                                                                @endphp
                                                                {{ $details ? $details->quantity : 0 }}
                                                            </td>
                                                            <td>
                                                                {{ $details ? number_format($details->quantity * $item, 2, ',', '.') : 0 }}
                                                                <input type="hidden" name="cash_value[]" value="{{ $item }}">
                                                                <input type="hidden" name="quantity[]" value="{{ $details ? $details->quantity : 0 }}">
                                                            </td>
                                                            @php
                                                            if($details){
                                                                $total += $details->quantity * $item;
                                                            }
                                                            @endphp
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <br>
                                                <div class="alert alert-info">
                                                    <strong>Información:</strong>
                                                    <p>Si la cantidad de de cortes de billetes coincide con la cantidad entregada por parte del administrador(a) de vóbeda, acepta la apertura de caja, caso contrario puedes rechazar la apertura.</p>
                                                </div>
                                                <br>
                                                <h2 class="text-right"><small>Total en caja: Bs.</small> {{ number_format($total, 2, ',', '.') }} </h2>
                                                <br>
                                                <div class="text-right">
                                                    <button type="button" data-toggle="modal" data-target="#refuse_cashier-modal" class="btn btn-danger">Rechazar apertura <i class="voyager-x"></i></button>
                                                    <button type="button" data-toggle="modal" data-target="#open_cashier-modal" class="btn btn-success">Aceptar apertura <i class="voyager-key"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Aceptar apertura de caja --}}
                        <form action="{{ route('cashiers.change.status', ['cashier' => $cashier->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="abierta">
                            <div class="modal fade" tabindex="-1" id="open_cashier-modal" role="dialog">
                                <div class="modal-dialog modal-success">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="voyager-key"></i> Aceptar apertura de caja</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted">Esta a punto de aceptar que posee todos los cortes de billetes descritos en la lista, ¿Desea continuar?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Si, aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Rechazar apertura de caja --}}
                        <form action="{{ route('cashiers.change.status', ['cashier' => $cashier->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="cerrada">
                            <div class="modal modal-danger fade" tabindex="-1" id="refuse_cashier-modal" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><i class="voyager-x"></i> Rechazar apertura de caja</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted">Esta a punto de rechazar la apertura de caja, ¿Desea continuar?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Si, rechazar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body text-center">
                                    <h2>Tienes una caja esperando por confimación de cierre</h2>
                                    <button type="button" data-toggle="modal" data-target="#cashier-revert-modal" class="btn btn-success"><i class="voyager-key"></i> Reabrir caja</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('cashiers.close.revert', ['cashier' => $cashier->id]) }}" method="post">
                        @csrf
                        <div class="modal fade" tabindex="-1" id="cashier-revert-modal" role="dialog">
                            <div class="modal-dialog modal-success">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="voyager-key"></i> Reabrir Caja</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted">Si reabre la caja deberá realizar el arqueo nuevamente, ¿Desea continuar?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">Si, reabrir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <h1 class="text-center">No tienes caja abierta</h1>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Vista de recursos humanos --}}
        @if (Auth::user()->role_id == 1 || (Auth::user()->role_id >= 9 && Auth::user()->role_id <= 12) || Auth::user()->role_id == 23 || Auth::user()->role_id == 25)
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Hola, {{ Auth::user()->name }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered" style="border-left: 5px solid #52BE80">
                    <div class="panel-body" style="height: 100px;padding: 15px 20px">
                        <div class="col-md-9">
                            @php
                                $contracts_active = App\Models\Contract::where('deleted_at', NULL)->where('status', 'firmado')->whereRaw('start <= "'.date('Y-m-d').'" and (finish >= "'.date('Y-m-d').'" or finish is null)')->count();
                            @endphp
                            <h5>Contratos activos</h5>
                            <h2>{{ $contracts_active }}</h2>
                        </div>
                        <div class="col-md-3 text-right">
                            <i class="icon voyager-news" style="color: #52BE80"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered" style="border-left: 5px solid #E67E22">
                    <div class="panel-body" style="height: 100px;padding: 15px 20px">
                        <div class="col-md-9">
                            <h5>Contratos en proceso</h5>
                            <h2>{{ App\Models\Contract::where('deleted_at', NULL)->where('status', 'elaborado')->count() }}</h2>
                        </div>
                        <div class="col-md-3 text-right">
                            <i class="icon voyager-calendar" style="color: #E67E22"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered" style="border-left: 5px solid #3498DB">
                    <div class="panel-body" style="height: 100px;padding: 15px 20px">
                        <div class="col-md-9">
                            <h5>Inamovilidades</h5>
                            <h2>{{ App\Models\PersonIrremovability::where('deleted_at', NULL)->whereRaw('start <= "'.date('Y-m-d').'" and (finish >= "'.date('Y-m-d').'" or finish is null)')->count() }}</h2>
                        </div>
                        <div class="col-md-3 text-right">
                            <i class="icon voyager-certificate" style="color: #3498DB"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered" style="border-left: 5px solid #E74C3C">
                    <div class="panel-body" style="height: 100px;padding: 15px 20px">
                        <div class="col-md-9">
                            @php
                                $jobs = App\Models\Job::whereRaw("id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null)")
                                            ->whereRaw(Auth::user()->direccion_administrativa_id ? 'direccion_administrativa_id = '.Auth::user()->direccion_administrativa_id : 1)
                                            ->where('deleted_at', NULL)->count();
                            @endphp
                            <h5>Cargos acéfalos</h5>
                            <h2>{{ $jobs }}</h2>
                        </div>
                        <div class="col-md-3 text-right">
                            <i class="icon voyager-book" style="color: #E74C3C"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-body" style="height: 250px; overflow-y: auto">
                        <h4>Cumpleaños</h4>
                        @php
                            $birthdays = App\Models\Person::whereHas('contracts', function($q){
                                                $q->where('status', 'firmado');
                                            })
                                            ->where('birthday', 'like', '%'.date('m-d').'%')
                                            ->where('deleted_at', NULL)->get()
                        @endphp
                        <div class="list-group">
                            @forelse ($birthdays as $item)
                                @php
                                    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                    $now = \Carbon\Carbon::now();
                                    $birthday = new \Carbon\Carbon($item->birthday);
                                    $age = $birthday->diffInYears($now);

                                    $image = asset('images/default.jpg');
                                    if($item->image){
                                        $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                                    }
                                @endphp
                                <a href="{{ url('admin/people/'.$item->id) }}" class="list-group-item" style="cursor: pointer">
                                    <div style="display: flex; flex-direction: row">
                                        <div style="margin-right: 10px">
                                            <img src="{{ $image }}" alt="{{ $item->first_name }} {{ $item->last_name }}" style="width: 50px; height: 50px; border-radius: 25px">
                                        </div>
                                        <div>
                                            <p>
                                                {{ $item->first_name }} {{ $item->last_name }}<br>
                                                <b>{{ $age }} años</b>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted text-center">Ninguno</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-body" style="height: 250px">
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-body" style="height: 250px">
                        <canvas id="doughnut-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .icon{
            font-size: 35px
        }
    </style>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
    @if ((Auth::user()->role_id == 4 || Auth::user()->role_id == 5) && $cashier)
        @if ($cashier->status == 'abierta')
            <script>
                $(document).ready(function(){
                    const data = {
                        labels: [
                            'Pago de realizados',
                            'Ingresos',
                            'Egresos'
                        ],
                        datasets: [{
                            label: 'My First Dataset',
                            data: ["{{ $payments }}", "{{ $cashier_in }}", "{{ $cashier_out }}"],
                            backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)'
                            ],
                            hoverOffset: 4
                        }]
                    };
                    const config = {
                        type: 'pie',
                        data: data,
                    };
                    var myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );

                    // Si retorna las opciones para generar recibo de traspaso a caja
                    @if (session('id_transfer'))
                        let id_transfer = "{{ session('id_transfer') }}";
                        print_transfer(id_transfer);
                    @endif
                });

                function print_transfer(id){
                    window.open("{{ url('admin/cashiers/print/transfer') }}/"+id, "Entrega de fondos", `width=700, height=400`);
                }
                function print_recipe(id){
                    window.open("{{ url('admin/planillas/pagos/print') }}/"+id, "Recibo", `width=700, height=500`)
                }
                function print_recipe_delete(id){
                    window.open("{{ url('admin/planillas/pagos/delete/print') }}/"+id, "Recibo", `width=700, height=500`)
                }
            </script>
        @endif
    @endif
@stop
