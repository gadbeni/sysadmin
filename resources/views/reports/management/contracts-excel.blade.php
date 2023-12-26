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
            <th><b>NUA/CUA</b></th>
            <th><b>Cargo</b></th>
            <th><b>Nivel</b></th>
            <th><b>Sueldo</b></th>
            <th><b>Inicio contrato</b></th>
            <th><b>Fin contrato</b></th>
            <th><b>Fin adenda</b></th>
            <th><b>Duración contrato</b></th>
            <th><b>Duración contrato + adenda</b></th>
            <th><b>Monto contrato</b></th>
            <th><b>Monto contrato + adenda</b></th>
            <th><b>Programa/Proyecto</b></th>
            <th><b>Cat. prog.</b></th>
            <th><b>Estado</b></th>
            <th><b>Registrado</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $cont = 1;
        @endphp
        @foreach ($contracts as $item)
            @php
                $salary = 0;
                $total = 0;
                $duracion = 'Indefinido';
                if ($item->cargo) {
                    $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                }
                if($item->job){
                    $salary = $item->job->salary;
                }

                // Calcular finalización de contrato en caso de tener adenda
                if($item->addendums->count() > 0) {
                    $contract_finish = date('Y-m-d', strtotime($item->addendums->first()->start." -1 days"));
                } else {
                    $contract_finish = $item->finish;
                }

                $duracion_adenda = '';
                $total_adenda = '';
                if($item->start && $contract_finish){
                    $contract_duration = contract_duration_calculate($item->start, $contract_finish);
                    $duracion = ($contract_duration->months *30) + $contract_duration->days;
                    $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                    if($contract_finish != $item->finish){
                        $contract_total_duration = contract_duration_calculate($item->start, $item->finish);
                        $duracion_adenda = ($contract_total_duration->months *30) + $contract_total_duration->days;
                        $total_adenda = ($salary *$contract_total_duration->months) + (number_format($salary /30, 5) *$contract_total_duration->days);
                    }
                }
            @endphp
            <tr>
                <td>{{ $cont }}</td>
                <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->nombre : 'No definida' }}</td>
                <td>{{ $item->unidad_administrativa ? $item->unidad_administrativa->nombre : '' }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->type->name }}</td>
                <td>{{ $item->person->first_name }} </td>
                <td>{{ $item->person->last_name }}</td>
                <td>{{ $item->person->ci }}</td>
                <td>{{ $item->person->gender }}</td>
                <td>{{ $item->person->nua_cua }}</td>
                <td>
                    @if ($item->cargo)
                        {{ $item->cargo->Descripcion }}
                    @elseif ($item->job)
                        {{ $item->job->name }}
                    @else
                        No definio
                    @endif
                </td>
                <td>
                    @if ($item->cargo)
                        {{ $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->NumNivel }}
                    @elseif ($item->job)
                        {{ $item->job->level }}
                    @else
                        No definido
                    @endif
                </td>
                <td>{{ number_format($salary, 2, ',', '.') }}</td>
                <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                <td>{{ $contract_finish ? date('d/m/Y', strtotime($contract_finish)) : '' }}</td>
                <td>{{ $contract_finish != $item->finish ? date('d/m/Y', strtotime($item->finish)) : '' }}</td>
                <td>{{ $duracion }}</td>
                <td>{{ $duracion_adenda }}</td>
                <td>{{ $total ? number_format($total, 2, ',', '.') : 'No definido' }}</td>
                <td>{{ $total_adenda ? number_format($total_adenda, 2, ',', '.') : '' }}</td>
                <td>{{ $item->program ? $item->program->name : 'No definido' }}</td>
                <td>{{ $item->program ? $item->program->programatic_category : 'No definida' }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    {{ $item->user ? $item->user->name : '' }} <br>
                    <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                </td>
            </tr>
            @php
                $cont++;
            @endphp
        @endforeach
    </tbody>
</table>