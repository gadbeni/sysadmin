
<div class="col-md-12">
    <div class="panel panel-bordered">
        @php
            $planilla_no_pagada = false;
            $planilla_haberes = $planilla->groupBy('idPlanillaprocesada');
            $planilla_id = '';
            $haberes_id = '';
            
            // Obtener todos los id de planillas para hacer cierre masivo
            foreach ($planilla_haberes as $value) {
                $planilla_id .= $value[0]->idPlanillaprocesada.',';
                if($value[0]->estado_planilla_procesada != 3 && $value[0]->pagada != 0){
                    $planilla_no_pagada = true;
                }
            }
            $planilla_id = substr($planilla_id, 0, -1);

            // Obtener todos los id de planillas haberes para hacer habilitación masivo
            foreach ($planilla as $value) {
                $haberes_id .= $value->ID.',';
            }
            $haberes_id = substr($haberes_id, 0, -1);

            $cashier = \App\Models\Cashier::where('deleted_at', NULL)->where('status', 'abierta')->where('user_id', Auth::user()->id)->first();
        @endphp
        @if (count($planilla))
        <div class="panel-body" style="padding-bottom: 0px">
            @if (!$cashier)
                <div class="alert alert-warning">
                    <h4>Advertencia</h4>
                    <p>No puedes realizar pagos debido a que no has aperturado caja.</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-8" style="margin: 0px">
                    {{-- Si todos los haberes están inhabilitados (pagada = 0) se muestra el botón "Habilitar planilla" --}}
                    @if ($planilla->where('pagada', 0)->count() == $planilla->count())
                    <button type="button" data-toggle="modal" data-target="#pago-listo_modal" class="btn btn-success" onclick="setValueOpen('{{ $haberes_id }}')"><i class="voyager-file-text"></i> Habilitar planilla</button>
                    @endif
                    
                    {{-- Si todos los haberes están pendientes (pagada = 1) o alguna planilla esté pendiente (Estado != 3) se muestra el botón "Cerrar planilla" --}}
                    @if ($planilla_no_pagada)
                    <button type="button" data-toggle="modal" data-target="#cerrar-planilla-modal" class="btn btn-danger" onclick="setValueClose('{{ $planilla_id }}')"><i class="voyager-check"></i> Cerrar planilla</button>
                    @endif

                    {{-- Si hay haberes pendientes (pagada = 1), pagados (pagada = 2) y las planillas a las que corresponden se encuentan pagadas (Estado = 3)
                    Se muestra la alerta de que ya las planillas están pagadas (Estado = 3) pero que algunos haberes están pendientes (pagada = 1)  --}}
                    @if ($planilla->where('pagada', 1)->count() && $planilla->where('pagada', 2)->count() && !$planilla_no_pagada)
                    {{-- <div class="alert alert-info">
                        <h4>Información</h4>
                        <p>Existen funcionarios de ésta planilla que no se han pagado aún.</p>
                    </div> --}}
                    @endif

                    {{-- Si todos los haberes están pagados (pagada = 2) se muestra el título "Planilla pagada" --}}
                    @if ($planilla->where('pagada', 2)->count() == $planilla->count())
                    <h3>Planilla pagada <i class="voyager-check"></i></h3>
                    @endif
                </div>
                <div class="col-md-4 text-right" style="margin: 0px">
                    <div class="input-group" style="padding-top: 10px">
                        <input type="text" class="form-control" id="input-search" placeholder="Nombre, Apellidos o CI">
                        <span class="input-group-addon" id="basic-addon1"><i class="voyager-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>Secretaría</th>
                            <th>código</th>
                            <th>Tipo de contrato</th>
                            <th>AFP</th>
                            <th>Mes</th>
                            <th>Gestión</th>
                            <th>Item</th>
                            <th>Apellidos y Nombre(s)</th>
                            <th>C.I.</th>
                            <th>Líquido</th>
                            <th style="width: 100px">Estado</th>
                            @if ($cashier)
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    @php
                        $total = 0;
                        $cont = 1;
                    @endphp
                    <tbody id="dataTable-body">
                        @forelse ($planilla as $item)
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $item->Direccion_Administrativa }}</td>
                            <td>{{ $item->Codigoid }}</td>
                            <td>{{ $item->tipo_planilla }}</td>
                            <td>{{ $item->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                            <td>{{ $item->Mes }}</td>
                            <td>{{ $item->Anio }}</td>
                            <td>{{ $item->item }}</td>
                            <td>{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} {{ $item->Snombre }}</td>
                            <td>{{ $item->CedulaIdentidad }}</td>
                            <td class="text-right">{{ number_format($item->Liquido_Pagable, 2, '.', ',') }}</td>
                            <td>
                                @switch($item->pagada)
                                    @case(1)
                                        <label class="label label-danger">Pendiente</label>
                                        @break
                                    @case(2)
                                        <label class="label label-success">Pagada</label> <br>
                                        @php
                                            $detalle_pago = \App\Models\CashiersPayment::with('cashier.user')->where('planilla_haber_id', $item->ID)->first();
                                        @endphp
                                        @if ($detalle_pago)
                                            <small>Por {{ $detalle_pago->cashier->user->name }} <br> {{ date('d-m-Y', strtotime($detalle_pago->created_at)) }} <br> {{ date('H:i:s', strtotime($detalle_pago->created_at)) }} </small>
                                        @endif
                                        @break
                                    @default
                                        <label class="label label-default">inhabilitada</label>
                                @endswitch
                            </td>
                            @if ($cashier)
                            <td width="100px" class="text-right">
                                <div class="btn-group btn-group-sm" role="group">
                                    {{-- <button type="button" class="btn btn-warning"><i class="voyager-eye"></i> Ver</button> --}}
                                    @if($item->pagada == 1)
                                    <button type="button" data-toggle="modal" data-target="#pagar-modal" onclick='setValuePay(@json($item), @json($cashier))' class="btn btn-success btn-pago"><i class="voyager-dollar"></i> Pagar</button>
                                    @endif
                                    @if($item->pagada == 2 && $detalle_pago)
                                        <button type="button" onclick="print_recipe({{ $detalle_pago->id }})" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                    @endif
                                </div>
                            </td>
                            @endif
                            @php
                                $total += $item->Liquido_Pagable;
                                $cont++;
                            @endphp
                        </tr>
                        @empty
                            <tr>
                                <td colspan="13"><h4 class="text-center">No hay registros</h4></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="11"><h5>TOTAL</h5></td>
                            <td><h5 class="text-right">{{ number_format($total, 2, ',', '.') }}</h3></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
        })
    });

    function print_recipe(id){
        window.open("{{ url('admin/planillas/pago/print') }}/"+id, "Recibo", `width=700, height=500`)
    }
</script>