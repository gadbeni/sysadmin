@extends('voyager::master')

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        @if (Auth::user()->role_id == 3)
            @php
                $cashier = \App\Models\Cashier::with(['movements' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'payments' => function($q){
                    $q->where('deleted_at', NULL);
                }])
                ->where('user_id', Auth::user()->id)
                ->whereRaw('(status = "abierta" or status = "cierre pendiente")')
                ->where('deleted_at', NULL)->first();
                // dd($cashier);
            @endphp
            @if ($cashier)
                @if ($cashier->status == 'abierta')
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
                                        <div class="col-md-6 text-right">
                                            <button type="button" data-toggle="modal" data-target="#pagar-transfer" class="btn btn-success">Transferir <i class="voyager-forward"></i></button>
                                            <a href="{{ route('cashiers.close', ['cashier' => $cashier->id]) }}" class="btn btn-danger">Cerrar <i class="voyager-lock"></i></a>
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
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>N&deg;</th>
                                                <th>Detalle</th>
                                                <th>Observaciones</th>
                                                <th class="text-right">Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $cont = 1;
                                                $total_payments = 0;
                                            @endphp
                                            @foreach ($cashier->payments->where('deleted_at', NULL) as $item)
                                                <tr>
                                                    <td>{{ $cont }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->observations }}</td>
                                                    <td class="text-right">{{ $item->amount }}</td>
                                                </tr>
                                                @php
                                                    $cont++;
                                                    $total_payments += $item->amount;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td colspan="3"><h5>TOTAL</h5></td>
                                                <td class="text-right"><h4><small>Bs.</small> {{ number_format($total_payments, 2, ',', '.') }}</h4></td>
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
                                                <td class="text-right"><h4><small>Bs.</small> {{ number_format($total_movements, 2, ',', '.') }}</h4></td>
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
                        <div class="modal modal-success fade" tabindex="-1" id="pagar-transfer" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="voyager-forward"></i> Realizar transferencia</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="cashier_id">Caja</label>
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
                                <div class="panel-body text-center">
                                    <h2>Tienes una caja esperando por confimación de cierre</h2>
                                    <button type="button" data-toggle="modal" data-target="#cashier-revert-modal" class="btn btn-success"><i class="voyager-key"></i> Reabrir caja</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('cashiers.close.revert', ['cashier' => $cashier->id]) }}" method="post">
                        @csrf
                        <div class="modal modal-success fade" tabindex="-1" id="cashier-revert-modal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="voyager-key"></i> Reabrir Caja</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Si reabre la caja deberá realizar el arqueo nuevamente, ¿Desea continuar?</p>
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
    </div>
@stop

@section('javascript')
    @if (Auth::user()->role_id == 3 && $cashier)
        @if ($cashier->status == 'abierta')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
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
                });
            </script>
        @endif
    @endif
@stop
