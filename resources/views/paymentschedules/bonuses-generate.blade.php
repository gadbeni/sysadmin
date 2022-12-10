<form id="form" action="{{ route('bonuses.store') }}" method="post">
    @csrf
    <input type="hidden" name="direccion_id" value="{{ $direccion->id }}">
    <input type="hidden" name="year" value="{{ $year }}">
    <div class="col-md-12">
        <div id="dataTable" class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">N&deg;</th>
                        <th class="text-center">ID</th>
                        <th class="text-center">NOMBRE COMPLETO</th>
                        <th class="text-center">CI</th>
                        <th class="text-center">DIRECCIÓN ADMINISTRATIVA</th>
                        <th class="text-center">DETALLES</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $total = 0;
                    @endphp
                    @foreach ($people as $item)
                        @if ($item->amounts)
                            @php
                                $contract = App\Models\Contract::find($item->amounts[count($item->amounts) -1]['contract'][count($item->amounts[count($item->amounts) -1]['contract']) -1]);
                                // dd($contract->direccion_administrativa_id);
                            @endphp
                            @if ($contract->direccion_administrativa_id == $direccion->id)
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->last_name }} {{ $item->first_name }}</td>
                                    <td>{{ $item->ci }}</td>
                                    <td>{{ $direccion->nombre }}</td>
                                    <td>
                                        <table class="table">
                                            @foreach ($item->amounts as $amounts)
                                                @php
                                                    // dd($amounts);
                                                    $contract = App\Models\Contract::find($amounts["contract"]->sortDesc()->first());
                                                @endphp
                                                <tr>
                                                    @php
                                                        $index = 1;
                                                        $subtotal_amount = 0;

                                                        // Si es planilla permanente hay que agregar el mes de enero manualmente
                                                        // debido a que no se planillo enero en el sistema
                                                        if(count($amounts['months']) > 0){
                                                            if($contract->procedure_type_id == 1 && $amounts['months'][count($amounts['months']) -1]['period'] == '202202'){
                                                                $data = $amounts['months'][count($amounts['months']) -1];
                                                                $amounts['months']->push([
                                                                    'partial_salary' => $data['partial_salary'],
                                                                    'seniority_bonus_amount' => $data['seniority_bonus_amount'],
                                                                    'worked_days' => $data['worked_days'],
                                                                    'period' => '202201'
                                                                ]);
                                                            }
                                                        }

                                                    @endphp
                                                    <td>{{ $contract->type->name }}</td>
                                                    @foreach ($amounts['months']->groupBy('period')->sort()->take(3) as $month)
                                                        @php
                                                            $partial_salary = 0;
                                                            $seniority_bonus_amount = 0;
                                                            $worked_days = 0;
                                                            $period = '';
                                                        @endphp
                                                        @foreach ($month as $month_group)
                                                            @php
                                                                $partial_salary += $month_group['partial_salary'];
                                                                $seniority_bonus_amount += $month_group['seniority_bonus_amount'];
                                                                $worked_days += $month_group['worked_days'];
                                                                $period = $month_group['period'];
                                                            @endphp
                                                        @endforeach
                                                        <td class="text-center" style="cursor: pointer" title="{{ $period }}">
                                                            {{ $partial_salary + $seniority_bonus_amount }} <br>
                                                            <small style="font-size: 9px">{{ $worked_days }} Días</small>
                                                            <input type="hidden" name="partial_salary_{{ $index }}[]" value="{{ $partial_salary }}">
                                                            <input type="hidden" name="seniority_bonus_{{ $index }}[]" value="{{ $seniority_bonus_amount }}">
                                                        </td>
                                                        @php
                                                            $subtotal_amount += $partial_salary + $seniority_bonus_amount;
                                                            $index++;
                                                        @endphp
                                                    @endforeach
                                                    <td class="text-right">
                                                        {{ $amounts["duration"] }}
                                                        <input type="hidden" name="procedure_type_id[]" value="{{ $contract->procedure_type_id }}">
                                                        <input type="hidden" name="contract_id[]" value="{{ $contract->id }}">
                                                        <input type="hidden" name="days[]" value="{{ $amounts["duration"] }}">
                                                    </td>
                                                    <td class="text-right">{{ number_format($subtotal_amount /3, 2, ',', '.') }}</td>
                                                    <td class="text-right">{{ number_format((($subtotal_amount /3) /360) * $amounts["duration"], 2, ',', '.') }}</td>
                                                </tr>
                                                @php
                                                    $total += (($subtotal_amount /3) /360) * $amounts["duration"];
                                                    $cont++;
                                                @endphp
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-right"><b>TOTAL</b></td>
                        <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 text-right" style="margin-top: 50px">
        <label class="checkbox-inline"><input type="checkbox" value="" required>Aceptar y continuar</label>
    </div>
    <div class="col-md-12 text-right">
        <button type="submit" id="btn-submit" class="btn btn-success btn-lg">Guardar</button>
    </div>    
</form>
<script>
    $(document).ready(function(){
        $('#form').submit(function(e){
            $('#btn-submit').attr('disabled', true);
        });
    });
</script>