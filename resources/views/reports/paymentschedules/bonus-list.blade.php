<div class="col-md-12 text-right">
    @if (count($bonuses))
        {{-- <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button> --}}
        <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-excel"></i> Excel</button>
    @endif
</div>
<br>
<div class="col-md-12">
    <table id="dataTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>PLANILLA</th>
                <th>NOMBRE COMPLETO</th>
                <th>CI</th>
                <th>INICIO</th>
                <th>FIN</th>
                <th>DÍAS TRABAJADOS</th>
                <th>MESES</th>
                <th>SUELDO PROMEDIO</th>
                <th>AGUINALDO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total = 0;
            @endphp
            @foreach ($bonuses as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->procedure_type->name }}</td>
                    <td>
                        {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                        @if ($item->contract->cargo)
                            <b>{{ $item->contract->cargo->Descripcion }}</b>
                        @elseif($item->contract->job)
                            <b>{{ $item->contract->job->name }}</b>
                        @endif
                    </td>
                    <td>{{ $item->contract->person->ci }}</td>
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
                    <td>{{ date('d-m-Y', strtotime($start_contract->start)) }}</td>
                    <td>{{ $item->contract->finish ? date('d-m-Y', strtotime($item->contract->finish)) : '31-12-'.$year }}</td>
                    <td class="text-right">{{ $item->days }}</td>
                    <td>
                        <table class="table">
                            <tr>
                                <td>{{ number_format($item->partial_salary_1 + $item->seniority_bonus_1, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->partial_salary_2 + $item->seniority_bonus_2, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->partial_salary_3 + $item->seniority_bonus_3, 2, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="text-right">
                        @php
                            $promedio = ($item->partial_salary_1 + $item->seniority_bonus_1 + $item->partial_salary_2 + $item->seniority_bonus_2 + $item->partial_salary_3 + $item->seniority_bonus_3) /3;
                        @endphp
                        {{ number_format($promedio, 2, ',', '.') }}
                    </td>
                    <td class="text-right">{{ number_format(($promedio / 360) * $item->days, 2, ',', '.') }}</td>
                </tr>
                @php
                    $cont++;
                    $total += ($promedio / 360) * $item->days;
                @endphp
            @endforeach
            <tr>
                <td colspan="9" class="text-right"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
</div>