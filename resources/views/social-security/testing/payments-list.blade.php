<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>planilla</th>
                            <th>pago ID AFP</th>
                            <th>FPC</th>
                            <th>AFP</th>
                            <th>Fecha de pago</th>
                            <th>Pago ID CC</th>
                            <th>Fecha de pago CC</th>
                            <th>Nro de deposito CC</th>
                            <th>GTC 11</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @foreach ($data as $item)
                        {{-- {{ dd($item) }} --}}
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $item['planilla'] }}</td>
                            <td>{{ $item['pago_id'] }}</td>
                            <td>{{ $item['fpc_number'] }}</td>
                            <td>{{ $item['afp'] }}</td>
                            <td>{{ $item['fecha_pago'] }}</td>
                            <td>{{ $item['pago_id_cc'] }}</td>
                            <td>{{ $item['fecha_pago_cc'] }}</td>
                            <td>{{ $item['nro_deposito_cc'] }}</td>
                            <td>{{ $item['gtc11'] }}</td>
                        </tr>
                        @php
                            $cont++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>