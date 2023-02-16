
<div class="col-md-12 text-right">
    {{-- @if (count($details))
        <button type="button" onclick="report_print()" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif --}}
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">
                            Detalles
                        </h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Corte</th>
                                    <th>Cantidad</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $cantidad_200 = 0;
                                    $cantidad_100 = 0;
                                    $cantidad_50 = 0;
                                    $cantidad_20 = 0;
                                    $cantidad_10 = 0;
                                    $cantidad_5 = 0;
                                    $cantidad_2 = 0;
                                    $cantidad_1 = 0;
                                    $cantidad_05 = 0;
                                    $cantidad_02 = 0;
                                    $cantidad_01 = 0;
                                    foreach ($details->where('type', 'ingreso') as $ingreso) {
                                        $cantidad_200 += $ingreso->cash->where('cash_value', '200.00')->sum('quantity');
                                        $cantidad_100 += $ingreso->cash->where('cash_value', '100.00')->sum('quantity');
                                        $cantidad_50 += $ingreso->cash->where('cash_value', '50.00')->sum('quantity');
                                        $cantidad_20 += $ingreso->cash->where('cash_value', '20.00')->sum('quantity');
                                        $cantidad_10 += $ingreso->cash->where('cash_value', '10.00')->sum('quantity');
                                        $cantidad_5 += $ingreso->cash->where('cash_value', '5.00')->sum('quantity');
                                        $cantidad_2 += $ingreso->cash->where('cash_value', '2.00')->sum('quantity');
                                        $cantidad_1 += $ingreso->cash->where('cash_value', '1.00')->sum('quantity');
                                        $cantidad_05 += $ingreso->cash->where('cash_value', '0.50')->sum('quantity');
                                        $cantidad_02 += $ingreso->cash->where('cash_value', '0.20')->sum('quantity');
                                        $cantidad_01 += $ingreso->cash->where('cash_value', '0.10')->sum('quantity');
                                    }
                                    foreach ($details->where('type', 'egreso') as $egreso) {
                                        $cantidad_200 -= $egreso->cash->where('cash_value', '200.00')->sum('quantity');
                                        $cantidad_100 -= $egreso->cash->where('cash_value', '100.00')->sum('quantity');
                                        $cantidad_50 -= $egreso->cash->where('cash_value', '50.00')->sum('quantity');
                                        $cantidad_20 -= $egreso->cash->where('cash_value', '20.00')->sum('quantity');
                                        $cantidad_10 -= $egreso->cash->where('cash_value', '10.00')->sum('quantity');
                                        $cantidad_5 -= $egreso->cash->where('cash_value', '5.00')->sum('quantity');
                                        $cantidad_2 -= $egreso->cash->where('cash_value', '2.00')->sum('quantity');
                                        $cantidad_1 -= $egreso->cash->where('cash_value', '1.00')->sum('quantity');
                                        $cantidad_05 -= $egreso->cash->where('cash_value', '0.50')->sum('quantity');
                                        $cantidad_02 -= $egreso->cash->where('cash_value', '0.20')->sum('quantity');
                                        $cantidad_01 -= $egreso->cash->where('cash_value', '0.10')->sum('quantity');
                                    }
                                @endphp
                                <tr>
                                    <td><img src="{{ asset('images/cash/200.jpg') }}" alt="200 Bs." width="70px"> 200 Bs.</td>
                                    <td>{{ number_format($cantidad_200, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_200 * 200, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/100.jpg') }}" alt="100 Bs." width="70px"> 100 Bs.</td>
                                    <td>{{ number_format($cantidad_100, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_100 * 100, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/50.jpg') }}" alt="50 Bs." width="70px"> 50 Bs.</td>
                                    <td>{{ number_format($cantidad_50, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_50 * 50, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/20.jpg') }}" alt="20 Bs." width="70px"> 20 Bs.</td>
                                    <td>{{ number_format($cantidad_20, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_20 * 20, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/10.jpg') }}" alt="10 Bs." width="70px"> 10 Bs.</td>
                                    <td>{{ number_format($cantidad_10, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_10 * 10, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/5.jpg') }}" alt="5 Bs." width="70px"> 5 Bs.</td>
                                    <td>{{ number_format($cantidad_5, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_5 * 5, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/2.jpg') }}" alt="2 Bs." width="70px"> 2 Bs.</td>
                                    <td>{{ number_format($cantidad_2, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_2 * 2, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/1.jpg') }}" alt="1 Bs." width="70px"> 1 Bs.</td>
                                    <td>{{ number_format($cantidad_1, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_1, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/0.5.jpg') }}" alt="50 Ctvs." width="70px"> 50 Ctvs.</td>
                                    <td>{{ number_format($cantidad_05, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_05 * 0.5, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/0.2.jpg') }}" alt="20 Ctvs." width="70px"> 20 Ctvs.</td>
                                    <td>{{ number_format($cantidad_02, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_02 * 0.2, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('images/cash/0.1.jpg') }}" alt="10 Ctvs." width="70px"> 10 Ctvs.</td>
                                    <td>{{ number_format($cantidad_01, 0) }}</td>
                                    <td class="text-right">{{ number_format($cantidad_01 * 0.1, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">TOTAL</td>
                                    <td class="text-right"><h4>Bs. {{ number_format(($cantidad_200 * 200) + ($cantidad_100 * 100) + ($cantidad_50 * 50) + ($cantidad_20 * 20) + ($cantidad_10 * 10) + ($cantidad_5 * 5) + ($cantidad_2 * 2) + $cantidad_1 + ($cantidad_05 * 0.5) + ($cantidad_02 * 0.2) + ($cantidad_01 * 0.1), 2, ',', '.') }}</h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr style="margin:0;">
                </div>
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