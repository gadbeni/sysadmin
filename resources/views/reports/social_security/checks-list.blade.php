<div class="col-md-12 text-right">
    @if (count($data))
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button> --}}
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table border="1" cellspacing="0" cellpadding="5" width="100%" class="table">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>Dirección administrativa</th>
                            <th>Tipo de planilla</th>
                            <th>Periodo</th>
                            <th>N&deg; de planilla</th>
                            <th>AFP</th>
                            <th>N&deg; personas</th>
                            <th>Total ganado</th>
                            <th>Nro Cheque</th>
                            <th>Fecha de impresión</th>
                            <th>Beneficiario</th>
                            <th>Estado</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $total = 0;
                        @endphp
                        @forelse ($data as $item)
                            @if ($item->planilla)
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>{{ $item->planilla->Direccion_Administrativa }}</td>
                                    <td>{{ $item->planilla->tipo_planilla }}</td>
                                    <td>{{ $item->planilla->Periodo }}</td>
                                    <td>{{ $item->planilla->idPlanillaprocesada }}</td>
                                    <td>{{ $item->planilla->Afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                    <td>{{ $item->planilla->NumPersonas }}</td>
                                    <td style="text-align: right">{{ number_format($item->planilla->Monto, 2, ',', '.') }}</td>
                                    <td>{{ $item->number }}</td>
                                    <td>{{ date('d/M/Y', strtotime($item->date_print)) }}</td>
                                    <td>{{ $item->beneficiary ? $item->beneficiary->full_name : 'No definido' }}</td>
                                    <td>
                                        @switch($item->status)
                                            @case(1)
                                                <label class="label label-info">Pendiente</label>
                                                @break
                                            @case(2)
                                                <label class="label label-success">Pagado</label>
                                                @break
                                            @case(3)
                                                <label class="label label-warning">Vencido</label>
                                                @break
                                            @default
                                            <label class="label label-danger">Anulado</label>
                                        @endswitch
                                    </td>
                                    <td style="text-align: right">{{ number_format($item->amount, 2, ',', '.') }}</td>
                                </tr>
                                @php
                                    $cont++;
                                    $total += $item->amount;
                                @endphp
                            @endif
                        @empty
                            <tr>
                                <td colspan="13"><h5 class="text-center">No se encontraron registros</h5></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="12"><b>TOTAL</b></td>
                            <td style="text-align: right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>