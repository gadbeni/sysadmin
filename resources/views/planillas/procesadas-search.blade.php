
<div class="col-md-12" style="margin-bottom: 100px">
    <div style="z-index: -10;position: fixed;bottom:0">
        <input type="text" id="text-copy">
    </div>
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
            <h2 class="text-muted">{{ $title }}</h2><br>
            @if (!$cashier)
                <div class="alert alert-warning">
                    <h4>Advertencia</h4>
                    <p>No puedes realizar pagos debido a que no has aperturado caja.</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-8" style="margin: 0px">
                    {{-- Si todos los haberes están inhabilitados (pagada = 0) y la busqueda no se hizo por CI se muestra el botón "Habilitar planilla" --}}
                    @if ($planilla->where('pagada', 0)->count() == $planilla->count() && $tipo_planilla != 2)
                    <button type="button" data-toggle="modal" data-target="#pago-listo_modal" class="btn btn-success" onclick="setValueOpen('{{ $haberes_id }}')"><i class="voyager-file-text"></i> Habilitar planilla</button>
                    @endif

                    <button type="button" data-toggle="modal" data-target="#pago-multiple_modal" id="btn-pago-multiple" class="btn btn-success" style="display: none"><i class="voyager-dollar"></i> Pago múltiple</button>
                    
                    {{-- Si todos los haberes están pendientes (pagada = 1) o alguna planilla esté pendiente (Estado != 3) se muestra el botón "Cerrar planilla" --}}
                    @if ($planilla_no_pagada && $tipo_planilla != 2)
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


        {{-- Formulario de pago de aguinaldo --}}
        {{-- @if (count($aguinaldo) > 0) --}}
        <form action="" method="post">
            @csrf
            <div class="panel-body" style="margin-bottom: 50px">
                <div class="table-responsive">
                    <h3 class="text-center">PAGO DE AGUINALDO</h3>
                    <table id="dataTable-aguinaldo" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ITEM</th>
                                <th>FUNCIONARIO</th>
                                <th>CI</th>
                                <th>DÍAS TRAB.</th>
                                <th>SUELDO PROMEDIO</th>
                                <th>LÍQUIDO PAGABLE</th>
                                <th>ESTADO</th>
                                @if ($cashier || Auth::user()->role_id == 1)
                                <th>ACCIONES</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aguinaldo as $item)
                                <tr>
                                    <td>{{ $item->item }}</td>
                                    <td>{{ $item->funcionario }}</td>
                                    <td>{{ $item->ci }}</td>
                                    <td>{{ $item->nro_dias }}</td>
                                    <td>{{ $item->sueldo_promedio }}</td>
                                    <td>{{ $item->liquido_pagable }}</td>
                                    <td>
                                        @if ($item->estado == 'pendiente')
                                            <label class="label label-danger">Pendiente</label>
                                        @else
                                            <label class="label label-success">Pagada</label>
                                            @if ($item->payment)
                                                <br>
                                                <small>Por {{ $item->payment->cashier->user->name }} <br> {{ date('d-m-Y', strtotime($item->payment->created_at)) }} <br> {{ date('H:i:s', strtotime($item->payment->created_at)) }} </small>
                                            @endif
                                        @endif
                                    </td>
                                    @if ($cashier || Auth::user()->role_id == 1)
                                    <td>
                                        @if ($item->estado == 'pendiente')
                                        <button type="button" data-toggle="modal" data-target="#pagar-aguinaldo-modal" onclick='setValuePayBonus(@json($item), @json($cashier))' class="btn btn-success btn-pago"><i class="voyager-dollar"></i> Pagar</button>
                                        @endif

                                        @if ($item->estado == 'pagada')
                                        <button type="button" onclick="print_recipe({{ $item->id }}, 'aguinaldo')" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
        {{-- @endif --}}

        {{-- AdditionalSteddts --}}
        <form action="" method="post">
            @csrf
            <div class="panel-body" style="margin-bottom: 50px">
                <div class="table-responsive">
                    <h3 class="text-center">PAGO DE PLANILLAS ADICIONALES</h3>
                    <table id="dataTable-aguinaldo" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ITEM</th>
                                <th>CARGO</th>
                                <th>FUNCIONARIO</th>
                                <th>CI</th>
                                <th>DÍAS TRAB.</th>
                                <th>SUELDO PROMEDIO</th>
                                <th>MONTO FACTURA.</th>
                                <th>RC-IVA.</th>
                                <th>TOTAL</th>
                                <th>LÍQUIDO PAGABLE</th>
                                <th>ESTADO</th>
                                @if ($cashier || Auth::user()->role_id == 1)
                                <th>ACCIONES</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stipend as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->cargo }}</td>
                                    <td>{{ $item->funcionario }}</td>
                                    <td>{{ $item->ci }}</td>
                                    <td>{{ $item->dia }}</td>
                                    <td>{{ $item->sueldo }}</td>
                                    <td>{{ $item->montofactura }}</td>
                                    <td>{{ $item->rciva }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->liqpagable }}</td>
                                    <td>
                                        @if ($item->estado == 'pendiente')
                                            <label class="label label-danger">Pendiente</label>
                                        @else
                                            <label class="label label-success">Pagada</label>
                                            @if ($item->payment)
                                                <br>
                                                <small>Por {{ $item->payment->cashier->user->name }} <br> {{ date('d-m-Y', strtotime($item->payment->created_at)) }} <br> {{ date('H:i:s', strtotime($item->payment->created_at)) }} </small>
                                            @endif
                                        @endif
                                    </td>
                                    @if ($cashier || Auth::user()->role_id == 1)
                                    <td>
                                        @if ($item->estado == 'pendiente')
                                            <button type="button" data-toggle="modal" data-target="#pagar-planilla-adicional-modal" onclick='planillasetValuePayBonus(@json($item), @json($cashier))' class="btn btn-success btn-pago"><i class="voyager-dollar"></i> Pagar</button>
                                        @endif

                                        {{-- @if ($item->estado == 'pagada')
                                        <button type="button" onclick="print_recipe({{ $item->id }}, 'aguinaldo')" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                        @endif --}}
                                    </td>
                                    @endif
                                    
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>



        <form id="form-pago-multiple" action="{{ route('planillas.details.payment.multiple') }}" method="post">
            @csrf
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
                                <th style="width: 50px" class="text-center"><input type="checkbox" id="check-all" style="transform: scale(1.5);" @if ($tipo_planilla == 2 || $planilla->where('pagada', 0)->count() == $planilla->count()) disabled @endif></th>
                                @endif
                                <th>ITEM</th>
                                <th>SECRETARÍA</th>
                                <th>CÓDIGO</th>
                                <th>TIPO DE CONTRATO</th>
                                <th>AFP</th>
                                <th>MES</th>
                                <th>GESTIÓN</th>
                                <th>APELLIDOS Y NOMBRE(S)</th>
                                <th>C.I.</th>
                                <th style="width: 80px">DÍAS TRAB.</th>
                                <th>LÍQUIDO</th>
                                <th style="width: 100px">ESTADO</th>
                                @if ($cashier || Auth::user()->role_id == 1)
                                <th>ACCIONES</th>
                                @endif
                            </tr>
                        </thead>
                        @php
                            $total = 0;
                            $cont = 1;
                            $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        <tbody id="dataTable-body">
                            @forelse ($planilla as $item)
                            <tr>
                                @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
                                <td class="text-center"><input type="checkbox" name="planilla_haber_id[]" class="check-item" value="{{ $item->ID }}" style="transform: scale(1.5);" @if($item->pagada != 1) disabled @endif></td>
                                @endif
                                <td>{{ $cont }}</td>
                                <td class="text-selected">{{ $item->Direccion_Administrativa }}</td>
                                <td>{{ $item->Codigoid }}</td>
                                <td>{{ $item->tipo_planilla }}</td>
                                <td>{{ $item->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                <td>{{ $meses[intval($item->Mes)] }}</td>
                                <td>{{ $item->Anio }}</td>
                                <td class="text-selected">{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} {{ $item->Snombre }}</td>
                                <td class="text-selected">{{ $item->CedulaIdentidad }}</td>
                                <td>{{ $item->Dias_Trabajado }}</td>
                                <td class="text-right text-selected">{{ number_format($item->Tplanilla == 3 ? $item->Total_Ganado : $item->Liquido_Pagable, 2, ',', '') }}</td>
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
                                @if ($cashier || Auth::user()->role_id == 1)
                                <td width="100px" class="text-right">
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- <button type="button" class="btn btn-warning"><i class="voyager-eye"></i> Ver</button> --}}
                                        @if($item->pagada == 1)
                                        <button type="button" data-toggle="modal" data-target="#pagar-modal" onclick='setValuePay(@json($item), @json($cashier))' class="btn btn-success btn-pago"><i class="voyager-dollar"></i> Pagar</button>
                                        @endif
                                        @if($item->pagada == 2 && $detalle_pago)
                                            <button type="button" onclick="print_recipe({{ $detalle_pago->id }}, 'sueldo')" title="Imprimir" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                                        @endif
                                    </div>
                                </td>
                                @endif
                                @php
                                    $total += $item->Tplanilla == 3 ? $item->Total_Ganado : $item->Liquido_Pagable;
                                    $cont++;
                                @endphp
                            </tr>
                            @empty
                                <tr>
                                    <td @if (Auth::user()->role_id == 1) colspan="14" @else colspan="13" @endif><h4 class="text-center">No hay registros</h4></td>
                                </tr>
                            @endforelse
                            <tr>
                                <td @if (Auth::user()->role_id == 1) colspan="11" @else colspan="10" @endif><h5>TOTAL</h5></td>
                                <td><h5 class="text-right">{{ number_format($total, 2, ',', '.') }}</h3></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal de confirmación --}}
            <div class="modal modal-success fade" tabindex="-1" id="pago-multiple_modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-dollar"></i> Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">Desea registrar el pago múltiple?</p>
                            <input type="hidden" name="id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            {{-- <button type="submit" class="btn btn-default">Test pagar</button> --}}
                            <button type="button" class="btn btn-success" onclick="sendForm('form-pago-multiple', 'Pago(s) realizado(s) exitosamente')">Sí, pagar</button>
                        </div>
                    </div>
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

        $('.check-item').click(function(){
            if ($(".check-item").is(":checked")) {
                $('#check-all').prop('checked', true);
                $('#btn-pago-multiple').fadeIn('fast');
            }else{
                $('#check-all').prop('checked', false);
                $('#btn-pago-multiple').fadeOut('fast');
            }
        });

        $('#check-all').click(function(){
            if ($("#check-all").is(":checked")) {
                $('.check-item').prop('checked', true);
                $('#btn-pago-multiple').fadeIn('fast');
            }else{
                $('.check-item').prop('checked', false);
                $('#btn-pago-multiple').fadeOut('fast');
            }
        });
    });

    function print_recipe(id, type){
        switch (type) {
            case 'sueldo':
                window.open("{{ url('admin/planillas/pago/print') }}/"+id, "Recibo", `width=700, height=500`);
                break;
            case 'aguinaldo':
                window.open("{{ url('admin/aguinaldos/pago/print') }}/"+id, "Recibo", `width=700, height=500`);
                break;
            default:
                break;
        }
    }
</script>