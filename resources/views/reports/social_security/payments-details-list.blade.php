
<div class="col-md-12 text-right">
    @if (count($da))
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <tbody>
                        @foreach ($da as $item)
                            <tr>
                                <th style="font-size: 15px">{{ $item->NOMBRE }}</th>
                            </tr>
                            <tr>
                                <td>
                                    <table class="table" style="margin-bottom: 0px">
                                        <thead>
                                            <tr>
                                                <th>Tipo de planilla</th>
                                                <th>AFP</th>
                                                <th>N&deg; de personas</th>
                                                <th>Total ganado</th>
                                                <th>N&deg; de cheque</th>
                                                <th>Monto de cheque</th>
                                                <th>Fecha de pago AFP</th>
                                                <th>N&deg; FPC</th>
                                                <th>Multa AFP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item->planillas as $planilla)
                                                <tr>
                                                    <td>{{ $planilla->tipo_planilla }}</td>
                                                    <td>{{ $planilla->afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                                    <td>{{ $planilla->personas }}</td>
                                                    <td>{{ number_format($planilla->total_ganado, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    th{
        font-size: 10px
    }
    td{
        font-size: 13px
    }
</style>

<script>
    $(document).ready(function(){

    })
</script>