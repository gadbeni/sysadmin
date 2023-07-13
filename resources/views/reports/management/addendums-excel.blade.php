<table>
    <thead>
        <tr>
            <th>N&deg;</th>
            <th>Dirección administrativa</th>
            <th>Unidad administrativa</th>
            <th>Código</th>
            <th>Tipo</th>
            <th>Nombre(s)</th>
            <th>Apellidos</th>
            <th>CI</th>
            <th>Género</th>
            <th>Cargo</th>
            <th>Nivel</th>
            <th>Sueldo</th>
            <th>Duración contrato principal</th>
            <th>Inicio de adenda</th>
            <th>Fin de adenda</th>
            <th>Monto adenda</th>
            <th>Monto contrato adenda</th>
            <th>Estado</th>
            <th>Registrado</th>
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
                $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);

                $adenda_duration = contract_duration_calculate($item->start, $item->finish);
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
                <td>{{ date('d/m/Y', strtotime($item->contract->start)) }} <br> {{ date('d/m/Y', strtotime($item->contract->finish))}}</td>
                <td>{{ number_format($total, 2, ',', '.') }}</td>
                <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                <td>{{ date('d/m/Y', strtotime($item->finish))}}</td>
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
            <tr class="odd">
                <td valign="top" colspan="19" class="text-center">No hay datos disponibles en la tabla</td>
            </tr>
        @endforelse
    </tbody>
</table>