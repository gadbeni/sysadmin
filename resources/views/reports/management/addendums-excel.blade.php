<table>
    <thead>
        <tr>
            <th><b>N&deg;</b></th>
            <th><b>Dirección administrativa</b></th>
            <th><b>Unidad administrativa</b></th>
            <th><b>Código</b></th>
            <th><b>Tipo</b></th>
            <th><b>Nombre(s)</b></th>
            <th><b>Apellidos</b></th>
            <th><b>CI</b></th>
            <th><b>Género</b></th>
            <th><b>Cargo</b></th>
            <th><b>Nivel</b></th>
            <th><b>Sueldo</b></th>
            <th><b>Inicio contrato principal</b></th>
            <th><b>Fin contrato principal</b></th>
            <th><b>Duración contrato principal (días)</b></th>
            <th><b>Monto contrato principal</b></th>
            <th><b>Inicio de adenda</b></th>
            <th><b>Fin de adenda</b></th>
            <th><b>Duración adenda (días)</b></th>
            <th><b>Monto adenda</b></th>
            <th><b>Estado</b></th>
            <th><b>Registrado</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $cont = 1;
        @endphp
        @forelse ($addendums as $item)
            @php
                $salary = 0;
                $total = 0;
                if ($item->contract->cargo) {
                    $salary = $item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->Sueldo;
                }
                if($item->contract->job){
                    $salary = $item->contract->job->salary;
                }

                $contract_duration = contract_duration_calculate($item->contract->start, $item->contract->finish);
                $contract_duration_days = ($contract_duration->months *30) + $contract_duration->days;
                $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);

                $adenda_duration = contract_duration_calculate($item->start, $item->finish);
                $adenda_duration_days = ($adenda_duration->months *30) + $adenda_duration->days;
                $total_adenda = ($salary *$adenda_duration->months) + (number_format($salary /30, 5) *$adenda_duration->days);
            @endphp
            <tr>
                <td>{{ $cont }}</td>
                <td>{{ $item->contract->direccion_administrativa ? $item->contract->direccion_administrativa->nombre : 'No definida' }}</td>
                <td>{{ $item->contract->unidad_administrativa ? $item->contract->unidad_administrativa->nombre : '' }}</td>
                <td>{{ $item->contract->code }}</td>
                <td>{{ $item->contract->type->name }}</td>
                <td>{{ $item->contract->person->first_name }} </td>
                <td>{{ $item->contract->person->last_name }}</td>
                <td>{{ $item->contract->person->ci }}</td>
                <td>{{ $item->contract->person->gender }}</td>
                <td>
                    @if ($item->contract->cargo)
                        {{ $item->contract->cargo->Descripcion }}
                    @elseif ($item->contract->job)
                        {{ $item->contract->job->name }}
                    @else
                        No definio
                    @endif
                </td>
                <td>
                    @if ($item->contract->cargo)
                        {{ $item->contract->cargo->nivel->where('IdPlanilla', $item->contract->cargo->idPlanilla)->first()->NumNivel }}
                    @elseif ($item->contract->job)
                        {{ $item->contract->job->level }}
                    @else
                        No definido
                    @endif
                </td>
                <td>{{ number_format($salary, 2, ',', '.') }}</td>
                <td>{{ date('d/m/Y', strtotime($item->contract->start)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->contract->finish))}}</td>
                <td>{{ $contract_duration_days }}</td>
                <td>{{ number_format($total, 2, ',', '.') }}</td>
                <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->finish))}}</td>
                <td>{{ $adenda_duration_days }}</td>
                <td>{{ number_format($total_adenda, 2, ',', '.') }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    @if ($item->user)
                        {{ $item->user->name }} <br>
                    @endif
                    <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                </td>
            </tr>
            @php
                $cont++;
            @endphp
        @empty

        @endforelse
    </tbody>
</table>