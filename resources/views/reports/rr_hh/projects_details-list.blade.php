
<div class="col-md-12 text-right">
    @if (count($program))
        <button type="button" onclick="report_export('pdf')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
        <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Excel</button>
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
                            <th>NOMBRE</th>
                            <th>DIRECCIÓN ADMINSTRATIVA</th>
                            <th>TIPO</th>
                            <th>GESTIÓN</th>
                            <th>INVERSIÓN</th>
                            <th>MONTO EJECUTADO</th>
                            <th>PORCENTAJE</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            $amount_total = 0;
                            $expense_total = 0;
                        @endphp
                        @forelse ($program as $item)
                            @php
                                $expense_partial = 0;
                                foreach($item->contracts as $contract) {
                                    foreach($contract->paymentschedules_details as $paymentschedules_detail){
        
                                        $expense_partial += $paymentschedules_detail->partial_salary + $paymentschedules_detail->seniority_bonus_amount;
                                    }
                                }
                                $amount_total += $item->amount;
                                $expense_total += $expense_partial;
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->direccion_administrativa->nombre }}</td>
                                <td>{{ $item->procedure_type->name }}</td>
                                <td>{{ $item->year }}</td>
                                <td style="text-align: right">{{ number_format($item->amount, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($expense_partial, 2, ',', '.') }}</td>
                                <td style="text-align: right"><span @if($item->amount < $expense_partial) style="color: red" @endif>{{ $item->amount > 0 ? number_format(($expense_partial *100) / $item->amount, 2, ',', '.') : 0 }}%</span></td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="9"><h4 class="text-center">No hay resultados</h4></td>
                            </tr>
                        @endforelse
                        <tr>
                            <td style="text-align: right" colspan="5"><b>TOTAL</b></td>
                            <td style="text-align: right"><b>{{ number_format($amount_total, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b>{{ number_format($expense_total, 2, ',', '.') }}</b></td>
                            <td style="text-align: right"><b></b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    })
</script>