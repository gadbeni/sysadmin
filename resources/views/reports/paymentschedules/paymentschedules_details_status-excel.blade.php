    @if ($grouped)
        <table>
            <thead>
                <tr>
                    <th>Dirección administrativa</th>
                    <th>Nro personas</th>
                    <th>Total días trabajados</th>
                    <th>Total bono antigüedad</th>
                    <th>Total ganado</th>
                    <th>Riesgo común<br>(1.71%)</th>
                    <th>Vivienda<br>(2%)</th>
                    <th>Aporte patronal<br>(3%)</th>
                    <th>Salud<br>(10%)</th>
                    <th>Líquido pagable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments->groupBy('paymentschedule.direccion_administrativa_id') as $item)
                    @php
                        $total_amount = $item->sum('partial_salary') + $item->sum('seniority_bonus_amount');
                    @endphp
                    <tr>
                        <td>{{ count($item) > 0 ? $item[0]->paymentschedule->direccion_administrativa->nombre : '' }}</td>
                        <td>{{ $item->count() }}</td>
                        <td>{{ number_format($item->sum('partial_salary'), 2, ',', '.') }}</td>
                        <td>{{ number_format($item->sum('seniority_bonus_amount'), 2, ',', '.') }}</td>
                        <td>{{ number_format($total_amount, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->sum('common_risk'), 2, ',', '.') }}</td>
                        <td>{{ number_format($item->sum('housing_employer'), 2, ',', '.') }}</td>
                        <td>{{ number_format($item->sum('solidary_employer'), 2, ',', '.') }}</td>
                        <td>{{ number_format($total_amount *0.1, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->sum('liquid_payable'), 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th>Planilla</th>
                    <th>Dirección administrativa</th>
                    <th>Unidad administrativa</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Nombre completo</th>
                    <th>CI</th>
                    <th>Cargo</th>
                    <th>Nivel</th>
                    <th>Sueldo</th>
                    <th>LÍquido pagable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $item)
                <tr>
                    <td>{{ str_pad($item->paymentschedule->id, 6, "0", STR_PAD_LEFT) }}</td>
                    <td>{{ $item->contract->direccion_administrativa ? $item->contract->direccion_administrativa->nombre : 'No definida' }}</td>
                    <td>{{ $item->contract->unidad_administrativa ? $item->contract->unidad_administrativa->nombre : '' }}</td>
                    <td>{{ $item->contract->code }}</td>
                    <td>{{ $item->contract->type->name }}</td>
                    <td>{{ $item->contract->person->last_name }} {{ $item->contract->person->first_name }}</td>
                    <td>{{ $item->contract->person->ci }}</td>
                    <td>{{ $item->job }}</td>
                    <td>{{ $item->job_level }}</td>
                    <td>{{ number_format($item->salary, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->liquid_payable, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
