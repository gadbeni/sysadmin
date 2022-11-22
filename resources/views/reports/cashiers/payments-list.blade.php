
<div class="col-md-12 text-right">
    @if (count($payments))
        <button type="button" onclick="report_print()" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>DIRECCIÓN ADMINISTRATIVA</th>
                            <th>DETALLE</th>
                            <th>CI</th>
                            <th>TIPO</th>
                            <th>PERIODO</th>
                            <th>AFP</th>
                            <th>FECHA PAGO</th>
                            <th>CAJERO(A)</th>
                            <th>OBSERVACIONES </th>
                            <th class="text-right">MONTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                            $total = 0;
                        @endphp
                        @forelse ($payments as $item)
                            @php
                                if(!$item->deleted_at){
                                    $total += $item->amount;
                                }
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>
                                    {{-- {{ dd($item->planilla) }} --}}
                                    @if ($item->planilla)
                                        {{ $item->planilla->Direccion_Administrativa }}
                                    @elseif($item->aguinaldo)
                                        
                                    @elseif($item->paymentschedulesdetail)
                                        {{ $item->paymentschedulesdetail->paymentschedule->direccion_administrativa->nombre }}
                                    @endif
                                </td>
                                <td @if($item->deleted_at) class="item-delete" @endif>{{ $item->description }}</td>
                                <td>
                                    @if ($item->planilla)
                                        {{ $item->planilla->CedulaIdentidad }}
                                    @elseif($item->aguinaldo)
                                        {{ $item->aguinaldo->ci }}
                                    @elseif($item->stipend)
                                        {{ $item->stipend->ci }}
                                    @elseif($item->paymentschedulesdetail)
                                        {{ $item->paymentschedulesdetail->contract->person->ci }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->planilla)
                                        @if ($item->planilla->Tplanilla == 1)
                                            Permanente
                                        @endif
                                        @if ($item->planilla->Tplanilla == 2)
                                            Eventual
                                        @endif
                                        @if ($item->planilla->Tplanilla == 3)
                                            Consultoría de línea
                                        @endif
                                    @elseif($item->aguinaldo)
                                
                                    @elseif($item->paymentschedulesdetail)
                                        {{ $item->paymentschedulesdetail->contract->type->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->planilla)
                                        {{ $item->planilla->Periodo }}
                                    @elseif($item->aguinaldo)
                                        AGUINALDO
                                    @elseif($item->paymentschedulesdetail)
                                        {{ $item->paymentschedulesdetail->paymentschedule->period->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->planilla)
                                        {{ $item->planilla->Afp ? 'FUTURO' : 'PREVISIÓN' }}
                                    @elseif($item->paymentschedulesdetail)
                                        {{ $item->paymentschedulesdetail->afp_type->name }}
                                    @endif
                                </td>
                                <td>{{ date('d', strtotime($item->created_at)).'/'.$months[intval(date('m', strtotime($item->created_at)))].'/'.date('Y', strtotime($item->created_at)) }} <br> <small>{{ date('H:i', strtotime($item->created_at)) }}</small> </td>
                                <td>{{ $item->cashier->user->name }} </td>
                                <td style="max-width: 150px">
                                    {{ $item->observations }}
                                    @if ($item->deletes)
                                        <br><b>Motivo de eliminación:</b><br>
                                        {{  $item->deletes->observations }}
                                    @endif
                                </td>
                                <td @if($item->deleted_at) class="item-delete" @endif style="text-align:right"><b>{{ number_format($item->amount, 2, ',', '.') }}</b></td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="11"><h4 class="text-center">No hay resultados</h4></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="10" class="text-right"><b>TOTAL</b></td>
                            <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .item-delete{
        text-decoration: line-through;
        color: red !important;
    }
</style>

<script>
    $(document).ready(function(){

    })
</script>