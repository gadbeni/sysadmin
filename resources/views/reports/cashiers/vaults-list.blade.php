
<div class="col-md-12 text-right">
    @if (count($closure))
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
                            <th>ID</th>
                            <th>CERRADA POR</th>
                            <th style="max-width:150px">OBSERVACIONES</th>
                            <th>FECHA DE CIERRE </th>
                            <th class="text-right">MONTO (Bs.)</th>
                            <th class="text-right">OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                        @endphp
                        @forelse ($closure as $item)
                            @php
                                $total = 0;
                                foreach($item->details as $detail) {
                                    $total += $detail->cash_value * $detail->quantity;
                                }
                            @endphp
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->observations }} </td>
                                <td>{{ date('d', strtotime($item->created_at)).'/'.$months[intval(date('m', strtotime($item->created_at)))].'/'.date('Y', strtotime($item->created_at)) }} {{ date('H:i', strtotime($item->created_at)) }} </td>
                                <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                <td class="text-right"><a href="{{ route('vaults.print.closure', ['vault' => $item->id]) }}" target="_blank" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-print"></i> Detalles</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"><h4 class="text-center">No hay resultados</h4></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-sm {
        padding: 5px 10px !important;
    font-size: 12px !important;
    }
</style>

<script>
    $(document).ready(function(){

    })
</script>