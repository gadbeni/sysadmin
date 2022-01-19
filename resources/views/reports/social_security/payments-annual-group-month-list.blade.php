<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Monto total ganado</th>
                            <th>Monto a pagar AFP</th>
                            <th>Monto pagado AFP</th>
                            <th>Monto a pagar CC</th>
                            <th>Monto pagado CC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $months = [
                                '',
                                'Enero',
                                'Febrero',
                                'Marzo',
                                'Abril',
                                'Mayo',
                                'Junio',
                                'Julio',
                                'Agosto',
                                'Septiembre',
                                'Octubre',
                                'Noviembre',
                                'Diciembre'
                            ];
                        @endphp
                        @foreach ($data as $item)
                            @php
                                $total_afp = 0;
                                $total_cc = 0;
                                foreach($item->planillas as $planilla) {
                                    if(strpos($planilla->check_beneficiary->full_name, 'Caja de Salud') === false) {
                                        $total_afp += $planilla->amount;
                                    }else{
                                        $total_cc += $planilla->amount;
                                    }
                                }
                            @endphp
                        <tr>
                            <td>{{ $months[intval($item->month)] }}</td>
                            <td>{{ number_format($item->total_ganado, 2, ',', '.') }}</td>
                            <td>{{ number_format($item->total_afp, 2, ',', '.') }}</td>
                            <td>{{ number_format($total_afp, 2, ',', '.') }}</td>
                            <td>{{ number_format($item->total_cc, 2, ',', '.') }}</td>
                            <td>{{ number_format($total_cc, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>