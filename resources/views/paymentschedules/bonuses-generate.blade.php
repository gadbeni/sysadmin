<form class="form-submit" action="{{ route('bonuses.store') }}" method="post">
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
                        <th class="text-center">MONTO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $total = 0;
                    @endphp
                    @foreach ($bonuses as $bonus)
                        @php
                            $amount_accumulated = 0;
                        @endphp
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $bonus->id }}</td>
                            <td>{{ $bonus->first_name }} {{ $bonus->last_name }}</td>
                            <td>{{ $bonus->ci }}</td>
                            <td>{{ $direccion->nombre }}</td>
                            <td>
                                <table class="table" style="margin: 0px">
                                    @php
                                        $index = 1;
                                    @endphp
                                    @foreach ($bonus->contracts_list as $contracts_list)
                                        @php
                                            $amounts = array();
                                            $subtotal = 0;
                                            $count = 1;
                                            foreach ($contracts_list['contracts'] as $contract) {
                                                $current_contract = $contract;
                                                foreach ($contract->paymentschedules_details->sortByDesc('paymentschedule.period.name')->groupBy('paymentschedule.period.name') as $paymentschedules_detail) {
                                                    $salary = $paymentschedules_detail->sum('salary') / $paymentschedules_detail->count();
                                                    $seniority_bonus_amount = $paymentschedules_detail->sum('seniority_bonus_amount') / $paymentschedules_detail->count();
                                                    $subtotal += $salary + $seniority_bonus_amount;
                                                    array_push($amounts, ['salary' => $salary, 'seniority_bonus_amount' => $seniority_bonus_amount]);
                                                    $count++;
                                                    if ($count > 3) {
                                                        break;
                                                    }
                                                }
                                                if ($count > 3) {
                                                    break;
                                                }
                                            }
                                            $amount_subtotal = (($subtotal /3) /360) * ($contracts_list['days']);
                                            $amount_accumulated += $amount_subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $current_contract->type->name }}</td>
                                            @php
                                                $index = 1;
                                            @endphp
                                            @foreach ($amounts as $amount)
                                            <td class="text-right">
                                                {{ $amount['salary'] + $amount['seniority_bonus_amount'] }}
                                                <input type="hidden" name="partial_salary_{{ $index }}[]" value="{{ $amount['salary'] }}">
                                                <input type="hidden" name="seniority_bonus_{{ $index }}[]" value="{{ $amount['seniority_bonus_amount'] }}">
                                            </td>
                                            @php
                                                $index++;
                                            @endphp
                                            @endforeach
                                            <td class="text-right"><b>{{ $contracts_list['days'] }}</b></td>
                                            <td class="text-right">
                                                <b>{{ $amount_subtotal == intval($amount_subtotal) ? intval($amount_subtotal) : number_format($amount_subtotal, 2, ',', '.') }}</b>
                                                <input type="hidden" name="procedure_type_id[]" value="{{ $current_contract->procedure_type_id }}">
                                                <input type="hidden" name="contract_id[]" value="{{ $current_contract->id }}">
                                                <input type="hidden" name="days[]" value="{{ $contracts_list['days'] }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                            <td class="text-right">{{ $amount_accumulated == intval($amount_accumulated) ? intval($amount_accumulated) : number_format($amount_accumulated, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total += $amount_accumulated;
                            $cont++;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="6" class="text-right"><b>TOTAL</b></td>
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
        <button type="submit" class="btn btn-success btn-lg btn-submit">Guardar</button>
    </div>    
</form>
<script>
    $(document).ready(function(){
        // $('#form').submit(function(e){
        //     $('#btn-submit').attr('disabled', true);
        // });
    });
</script>