<div class="col-md-12">
    <div id="dataTable" class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>ID</th>
                    <th>NOMBRE COMPLETO</th>
                    <th>CI</th>
                    <th>PLANILLA</th>
                    <th>DIRECCIÓN ADMINISTRATIVA</th>
                    <th>DETALLE</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                    $total = 0;
                @endphp
                @foreach ($people as $item)
                    @if ($item->amounts)
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->last_name }} {{ $item->first_name }}</td>
                            <td>{{ $item->ci }}</td>
                            <td>{{ $item->last_contract->type->name }}</td>
                            <td>{{ $item->last_contract->direccion_administrativa ? $item->last_contract->direccion_administrativa->nombre : 'No definida' }}</td>
                            <td>
                                <table class="table">
                                    @foreach ($item->amounts as $amounts)
                                        @php
                                            $partial_amount = 0;
                                            foreach ($amounts["partial_amounts"] as $amount) {
                                                $partial_amount += ($amount["amount"] /30) * $amount["days"] + ($amount["bonus"] /30) * $amount["days"];
                                                // $partial_amount += ($amount["amount"] / 30) * $amount["days"];
                                            }
                                            $total_amount = (($partial_amount /3) /360) *$amounts["duration"];
                                        @endphp
                                        <tr>
                                            <td>{{ $amounts["duration"] }} dias</td>
                                            <td>sueldo: {{ number_format($partial_amount /3, 2, ',', '.') }}</td>
                                            <td><b>{{ number_format($total_amount, 2, ',', '.') }}</b></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @php
                            $cont++;
                            $total += $total_amount;
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <td colspan="6" class="text-right"><b>TOTAL</b></td>
                    <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<script>

</script>