
<div class="col-md-12" style="margin-bottom: 100px">
    <div style="z-index: -10;position: fixed;bottom:0">
        <input type="text" id="text-copy">
    </div>
    <div class="panel panel-bordered">
        @php
            $cashier = \App\Models\Cashier::where('deleted_at', NULL)->where('status', 'abierta')->where('user_id', Auth::user()->id)->first();
        @endphp
        @if (count($paymentschedule))
        <div class="panel-body" style="padding-bottom: 0px">
            <h2 class="text-muted">{{ $title }}</h2><br>
            @if (!$cashier)
                <div class="alert alert-warning">
                    <h4>Advertencia</h4>
                    <p>No puedes realizar pagos debido a que no has aperturado caja.</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-8" style="margin: 0px">

                </div>
                @if ($tipo_planilla != 2)
                    <div class="col-md-4 text-right" style="margin: 0px">
                        <div class="input-group" style="padding-top: 10px">
                            <input type="text" class="form-control" id="input-search" placeholder="Nombre, Apellidos o CI">
                            <span class="input-group-addon" id="basic-addon1"><i class="voyager-search"></i></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif


        <form id="form-pago-multiple" action="{{ route('planillas.pagos.details.payment.multiple') }}" method="post">
            @csrf
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ITEM</th>
                                <th>Dirección administrativa</th>
                                <th>TIPO DE CONTRATO</th>
                                <th>AFP</th>
                                <th>MES</th>
                                <th>GESTIÓN</th>
                                <th>APELLIDOS Y NOMBRE(S)</th>
                                <th>C.I.</th>
                                <th style="width: 80px">DÍAS TRAB.</th>
                                <th>LÍQUIDO</th>
                                <th style="width: 100px">ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        @php
                            $total = 0;
                            $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        <tbody id="dataTable-body">
                            @forelse ($paymentschedule as $item)
                            <tr>
                                <td>{{ $item->item }}</td>
                                <td class="text-selected" title="{{ str_pad($item->paymentschedule->id, 6, "0", STR_PAD_LEFT).($item->paymentschedule->aditional ? '-A' : '') }}">{{ $item->contract->direccion_administrativa->nombre }}</td>
                                <td>{{ $item->contract->type->name }}</td>
                                <td>{{ $item->afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                @php
                                    $period = $item->paymentschedule->period->name;
                                    $year = Str::substr($period, 0, 4);
                                    $month = Str::substr($period, 5, 2);
                                @endphp
                                <td>{{ $meses[intval($month)] }}</td>
                                <td>{{ $year }}</td>
                                <td class="text-selected">{{ $item->contract->person->last_name }} {{ $item->contract->person->first_name }}</td>
                                <td class="text-selected">{{ $item->contract->person->ci }}</td>
                                <td>{{ $item->worked_days }}</td>
                                <td class="text-right text-selected">{{ number_format($item->liquid_payable, 2, ',', '') }}</td>
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
                                <td width="100px" class="text-right">
                                    @if ($cashier && $item->status == 'habilitado')
                                        <button type="button" onclick='setValuePay(@json($item), @json($cashier), true)' data-toggle="modal" data-target="#pagar-modal" class="btn btn-success btn-pago"><i class="voyager-dollar"></i> Pagar</button>
                                    @endif
                                    @if ($item->status == 'pagado' && $item->payment)
                                        <button type="button" onclick="print_recipe({{ $item->payment->id }}, 'sueldo')" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="12"><h4 class="text-center">No hay registros</h4></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </form>
    </div>
</div>
<style>
    .text-selected {
        cursor: pointer;
    }
    .app-footer {
        opacity: 1 !important;
    }
    .text-selected-copy{
        color: green !important;
        text-decoration: underline !important;
    }

    #dataTable-aguinaldo th{
        background-color: #E74C3C !important;
    }
    th{
        font-size: 11px !important;
    }
</style>

<script>
    $(document).ready(function(){
        $('#input-search').keyup(function(){
            var value = $(this).val().toLowerCase();
            if(value){
                $("#dataTable-body tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            }else{
                $('tr').fadeIn('fast')
            }
        });

        $('.text-selected').click(function(){
            let val = $(this).text();
            $('#text-copy').val(val);
            $("#text-copy").select();
            document.execCommand("copy");
            $(this).addClass('text-selected-copy');
            setTimeout(function(){
                $('.text-selected-copy').removeClass('text-selected-copy');
            }, 300);
        });
    });

    function print_recipe(id, type){
        switch (type) {
            case 'sueldo':
                window.open("{{ url('admin/planillas/pagos/print') }}/"+id, "Recibo", `width=700, height=500`);
                break;
            case 'aguinaldo':
                window.open("{{ url('admin/planillas/pagos/aguinaldos/print') }}/"+id, "Recibo", `width=700, height=500`);
                break;
            default:
                break;
        }
    }
</script>