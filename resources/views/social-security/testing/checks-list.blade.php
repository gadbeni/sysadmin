<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <h1>Reporte de cheques {{ $year }}</h1> <br>
                <table border="1" cellspacing="0" cellpadding="5" width="100%" class="table">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>Dirección administrativa</th>
                            <th>Tipo de planilla</th>
                            <th>Periodo</th>
                            <th>Nro de planilla</th>
                            <th>AFP</th>
                            <th>N&deg; personas</th>
                            <th>Total ganado</th>
                            <th>Nro Cheque</th>
                            <th>Monto</th>
                            {{-- <th>Fecha de pago CC</th>
                            <th>Nro de deposito CC</th>
                            <th>GTC 11</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @foreach ($data as $item)
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
                            <td style="text-align: right">{{ number_format($item->amount, 2, ',', '.') }}</td>
                        </tr>
                        @php
                            $cont++;
                        @endphp
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>