<table>
    <thead>
        <tr>
            <th>N&deg;</th>
            <th>Dir. adm.</th>
            <th>Unid. adm.</th>
            <th>Código</th>
            <th>Tipo</th>
            <th>Nombre(s)</th>
            <th>Apellidos</th>
            <th>CI</th>
            <th>NUA/CUA</th>
            <th>Cargo</th>
            <th>Nivel</th>
            <th>Sueldo</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Monto total</th>
            <th>Programa</th>
            <th>Cat. prog.</th>
            <th>Estado</th>
            <th>Registrado</th>
        </tr>
    </thead>
    <tbody>
        @php
            $cont = 1;
        @endphp
        @forelse ($contracts as $item)
        @php
            $salary = 0;
            $total = 0;
            if($item->start && $item->finish){
                $contract_duration = contract_duration_calculate($item->start, $item->finish);
                if ($item->cargo) {
                    $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                }
                if($item->job){
                    $salary = $item->job->salary;
                }
                $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
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
            <td>{{ $item->finish ? date('d/m/Y', strtotime($item->finish)) : '' }}</td>
            <td>{{ number_format($total, 2, ',', '.') }}</td>
            <td>{{ $item->program ? $item->program->name : 'No definido' }}</td>
            <td>{{ $item->program ? $item->program->programatic_category : 'No definida' }}</td>
            <td>{{ $item->status }}</td>
            <td>
                {{ $item->user->name }} <br>
                {{ date('d/m/Y H:i', strtotime($item->created_at)) }}
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