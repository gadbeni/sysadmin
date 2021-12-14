@extends('voyager::master')

@section('page_title', 'Ver Caja')

@php
    $url = $_SERVER['REQUEST_URI'];
    $url_array = explode('/', $url);
    $id = $url_array[count($url_array)-1];
    $cashier = \App\Models\Cashier::with(['payments.aguinaldo', 'user', 'payments.deletes' => function($q){
        $q->where('deleted_at', NULL);
    }, 'movements' => function($q){
        $q->where('deleted_at', NULL);
    }, 'movements.cashier_from', 'movements.cashier_to'])->where('id', $id)->first();
    $payments_id = [];
    foreach ($cashier->payments as $payment) {
        array_push($payments_id, $payment->planilla_haber_id);
    }
    $payments = DB::connection('mysqlgobe')->table('planillahaberes')->whereIn('id', $payments_id)->get();
    // dd($payments);
@endphp

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo Caja 
        <a href="{{ route('voyager.cashiers.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-print"></span> Impresión <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('print.open', ['cashier' => $cashier->id]) }}" target="_blank">Apertura</a></li>
                @if ($cashier->status == 'cerrada')
                <li><a href="{{ route('print.close', ['cashier' => $cashier->id]) }}" target="_blank">Cierre</a></li>
                @endif
                <li><a href="{{ route('print.payments', ['cashier' => $cashier->id]) }}" target="_blank">Pagos</a></li>
            </ul>
        </div>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->title }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Cajero</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $cashier->observations ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h4>Pagos realizados</h4>
                            {{-- <table class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>N&deg; de recibo</th>
                                        <th>Apellidos y Nombre(s)</th>
                                        <th>C.I.</th>
                                        <th style="text-align: right">Líquido pagable</th>
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                        $total = 0;
                                    @endphp
                                    @forelse ($payments as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->ID }}</td>
                                            <td>
                                                {{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} {{ $item->Snombre }}
                                                @php
                                                    $payment = $cashier->payments->where('planilla_haber_id', $item->ID)->first();
                                                @endphp
                                                @if ($payment->deletes)
                                                    <label class="label label-danger">Anulado</label>
                                                @endif
                                            </td>
                                            <td>{{ $item->CedulaIdentidad }}</td>
                                            <td class="text-right">{{ number_format($item->Liquido_Pagable, 2, '.', ',') }}</td>
                                            <td class="text-right">
                                                @php
                                                    $detalle_pago = \App\Models\CashiersPayment::with('cashier.user')->where('planilla_haber_id', $item->ID)->first();
                                                @endphp
                                                @if (!$payment->deletes)
                                                    <button type="button" onclick="print_recipe({{ $detalle_pago->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                                    <button type="button" data-toggle="modal" data-target="#delete_payment-modal" data-id="{{ $item->ID }}" class="btn btn-danger btn-delete"><i class="voyager-trash"></i> Anular</button>
                                                @else
                                                    <button type="button" onclick="print_recipe_delete({{ $detalle_pago->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Informe de anulación</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                            $total += $item->Liquido_Pagable;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6"><h4 class="text-center">No hay datos registrados</h4></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table> --}}

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
                                            // dd($data);
                                        @endphp
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>
                                                {{ $data ? $data->Nombre_Empleado : $payment->aguinaldo->funcionario  }} <br> <small>{{ $data ? $data->Direccion_Administrativa : '' }}</small>
                                                <br>
                                                @if ($payment->deleted_at)
                                                    <label class="label label-danger">Anulado</label>
                                                @endif
                                            </td>
                                            <td>{{ $data ? $data->CedulaIdentidad : $payment->aguinaldo->ci }}</td>
                                            <td>{{ $data ? $months[$data->Mes].'/'.$data->Anio : 'Aguinaldo' }}</td>
                                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                            <td style="text-align: right">{{ number_format($payment->amount, 2, ',', '.') }}</td>
                                            <td class="text-right">
                                                @if ($data)
                                                    @if (!$payment->deleted_at)
                                                    <button type="button" onclick="print_recipe({{ $payment->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                                    <button type="button" data-toggle="modal" data-target="#delete_payment-modal" data-id="{{ $data->ID }}" class="btn btn-danger btn-delete"><i class="voyager-trash"></i> Anular</button>
                                                @else
                                                    @if ($payment->deletes)
                                                        <button type="button" onclick="print_recipe_delete({{ $payment->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Informe de anulación</button>
                                                    @else
                                                        <label class="label label-danger">Eliminado manualmente</label>
                                                    @endif
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
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h4>Movimientos de caja</h4>
                            <table class="table table-bordered table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Descripción</th>
                                        <th>Tipo</th>
                                        <th>Hora</th>
                                        <th style="text-align: right">Monto</th>
                                        <th style="text-align: right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($cashier->movements as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ date('H:i:s', strtotime($item->created_at)) }}</td>
                                            <td class="text-right">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                            <td class="text-right">
                                                <button type="button" onclick="print_transfer({{ $item->id }})" title="Imprimir" class="btn btn-default btn-print"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6"><h4 class="text-center">No hay datos registrados</h4></td>
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

    <form id="form-delete" action="{{ route('planilla.payment.delete') }}" id="delete_form" method="POST">
        <input type="hidden" name="id" value="{{ $cashier->id }}">
        <input type="hidden" name="planilla_haber_id">
        @csrf
        <div class="modal modal-danger fade" tabindex="-1" id="delete_payment-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular el siguiente pago?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observation">Motivo</label>
                            <textarea name="observations" class="form-control" rows="5" placeholder="Describa el motivo de la anulación del pago" required></textarea>
                        </div>
                        <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar anulación</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Sí, ¡anúlalo!">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.btn-delete').click(function(){
                let id = $(this).data('id');
                $('#form-delete input[name="planilla_haber_id"]').val(id);
            });
        });

        function print_recipe(id){
            window.open("{{ url('admin/planillas/pago/print') }}/"+id, "Recibo", `width=700, height=500`)
        }

        function print_recipe_delete(id){
            window.open("{{ url('admin/planillas/pago/delete/print') }}/"+id, "Recibo", `width=700, height=500`)
        }

        function print_transfer(id){
            window.open("{{ url('admin/cashiers/print/transfer/') }}/"+id, "Comprobante de transferencia", `width=700, height=500`)
        }
    </script>
@stop
