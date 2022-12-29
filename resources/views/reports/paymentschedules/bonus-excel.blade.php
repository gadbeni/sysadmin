<table>
    <thead>
        <tr>
            <th>Nro</th>
            <th>Tipo de documento de identidad</th>
            <th>Número de documento de identidad</th>
            <th>Lugar de expedición</th>
            <th>Fecha de nacimiento</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Nombres</th>
            <th>País de nacionalidad</th>
            <th>Sexo</th>
            <th>Jubilado</th>
            <th>¿Aporta a la AFP?</th>
            <th>¿Persona con discapacidad?</th>
            <th>Tutor de persona con discapacidad</th>
            <th>Fecha de ingreso</th>
            <th>Fecha de retiro</th>
            <th>Motivo retiro</th>
            <th>Caja de salud</th>
            <th>AFP a la que aporta</th>
            <th>NUA/CUA</th>
            <th>Sucursal o ubicación adicional</th>
            <th>Clasificación laboral</th>
            <th>Cargo</th>
            <th>Modalidad de contrato</th>
            <th>Promedio haber básico</th>
            <th>Promedio bono de antigüedad</th>
            <th>Promedio bono producción</th>
            <th>Promedio subsidio frontera</th>
            <th>Promedio trabajo extraordinario y nocturno</th>
            <th>Promedio pago dominical trabajado</th>
            <th>Promedio otros bonos</th>
            <th>Promedio total ganado</th>
            <th>Meses trabajados</th>
            <th>Total ganado después de duodécimas</th>
        </tr>
    </thead>
    <tbody>
        @php
            $cont = 1;
        @endphp
        @foreach ($bonuses as $item)
            <tr>
                <td>{{ $cont }}</td>
                <td>CI</td>
                <td>{{ $item->contract->person->ci }}</td>
                <td></td>
                <td>{{ date('d/m/Y', strtotime($item->contract->person->birthday)) }}</td>
                @php
                    $last_name = explode(' ', $item->contract->person->last_name);
                @endphp
                <td>{{ $last_name[0] }}</td>
                <td>{{ count($last_name) > 1 ? $last_name[1] : '' }}</td>
                <td>{{ $item->contract->person->first_name }}</td>
                <td>{{ $item->contract->person->city ? $item->contract->person->city->state->country->name : 'Bolivia' }}</td>
                <td>{{ $item->contract->person->gender == 'masculino' ? 'M' : 'F' }}</td>
                <td>{{ $item->contract->person->retired ? '1' : '0' }}</td>
                <td>{{ $item->contract->person->afp_status ? '1' : '0' }}</td>
                @php
                    $discapacidad = App\Models\PersonIrremovability::where('person_id', $item->contract->person->id)->where('deleted_at', NULL)->get();
                @endphp
                <td>
                    @if ($discapacidad->count())
                        {{ $discapacidad->where('irremovability_type_id', 4)->count() ? '1' : '0' }}
                    @else
                        0
                    @endif
                </td>
                <td>
                    @if ($discapacidad->count())
                        {{ $discapacidad->where('irremovability_type_id', 5)->count() ? '1' : '0' }}
                    @else
                        0
                    @endif
                </td>
                @php
                    $start_contract = $item->contract;
                    $aux = true;
                    while ($aux) {
                        $contract = App\Models\Contract::where('person_id', $start_contract->person_id)
                                    ->where('finish', '<', $start_contract->start)
                                    ->orderBy('finish', 'DESC')->where('deleted_at', NULL)->first();
                        if($contract){
                            $current_start = Carbon\Carbon::createFromFormat('Y-m-d', $start_contract->start);
                            $new_finish = Carbon\Carbon::createFromFormat('Y-m-d', $contract->finish);
                            if($current_start->diffInDays($new_finish) == 1){
                                $start_contract = $contract;
                            }else{
                                $aux = false;
                            }
                        }else{
                            $aux = false;
                        }
                    }
                @endphp
                <td>{{ date('d/m/Y', strtotime($start_contract->start)) }}</td>
                <td>{{ $item->contract->finish ? date('d/m/Y', strtotime($item->contract->finish)) : '' }}</td>
                <td>
                    {{-- Si el tipo de contrato es eventual --}}
                    @if ($item->contract->procedure_type_id == 5)
                        2
                    @endif

                    {{-- Si el tipo de contrato es permanente y finaliza el año actual --}}
                    @if ($item->contract->procedure_type_id == 1 && $item->contract->finish)
                        @if (date('Y', strtotime($item->contract->finish)) == $year)
                            2
                        @endif
                    @endif
                </td>
                <td>{{ $item->contract->person->afp == 1 ? '2' : '1' }}</td>
                <td>{{ $item->contract->person->cc == 1 ? '6' : '3' }}</td>
                <td>{{ $item->contract->person->nua_cua }}</td>
                <td>
                    @if ($item->contract->direccion_administrativa->direcciones_tipo_id <= 2)
                        1
                    @else
                        3
                    @endif
                </td>
                <td>1</td>
                <td>
                    @if ($item->contract->cargo)
                        {{ $item->contract->cargo->Descripcion }}
                    @elseif ($item->contract->job)
                        {{ $item->contract->job->name }}
                    @else
                        No definio
                    @endif
                </td>
                <td>{{ $item->contract->procedure_type_id }}</td>
                <td>
                    @php
                        $promedio_sueldo = ($item->partial_salary_1 + $item->partial_salary_2 + $item->partial_salary_3) /3;
                    @endphp
                    {{ number_format($promedio_sueldo, 2, '.', '') }}
                </td>
                <td>
                    @php
                        $promedio_bono_antiguedad = ($item->seniority_bonus_1 + $item->seniority_bonus_2 + $item->seniority_bonus_3) /3;
                    @endphp
                    {{ number_format($promedio_bono_antiguedad, 2, '.', '') }}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">{{ number_format($promedio_sueldo + $promedio_bono_antiguedad, 2, '.', '') }}</td>
                <td>{{ number_format($item->days /30, 2, '.', '') }}</td>
                <td class="text-right">{{ number_format((($promedio_sueldo + $promedio_bono_antiguedad) / 360) * $item->days, 2, '.', '') }}</td>
            </tr>
            @php
                $cont++;
            @endphp
        @endforeach
    </tbody>
</table>