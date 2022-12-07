<form id="form" action="{{ route('bonuses.store') }}" method="post">
    @csrf
    <input type="hidden" name="direccion_id" value="{{ $direccion_id }}">
    <input type="hidden" name="year" value="{{ $year }}">
    <div class="col-md-12">
        <div id="dataTable" class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>N&deg;</th>
                        <th>ID</th>
                        <th>NOMBRE COMPLETO</th>
                        <th>CI</th>
                        <th>DIRECCIÓN ADMINISTRATIVA</th>
                        <th>DETALLE</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $total = 0;
                    @endphp
                    @foreach ($people as $item)
                        @if ($item->amounts)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->last_name }} {{ $item->first_name }}</td>
                                <td>{{ $item->ci }}</td>
                                {{-- <td>{{ $item->last_contract->type->name }}</td> --}}
                                <td>{{ $item->last_contract->direccion_administrativa ? $item->last_contract->direccion_administrativa->nombre : 'No definida' }}</td>
                                <td>
                                    <table class="table">
                                        @foreach ($item->amounts as $amounts)
                                            @php
                                                $contract = App\Models\Contract::find($amounts["contract"]->sortDesc()->first());
                                                $partial_amount = 0;
                                                foreach ($amounts["partial_amounts"] as $amount) {
                                                    $partial_amount += ($amount["amount"] /30) * $amount["days"] + ($amount["bonus"] /30) * $amount["days"];
                                                    // $partial_amount += ($amount["amount"] / 30) * $amount["days"];
                                                }
                                                $total_amount = (($partial_amount /3) /360) *$amounts["duration"];
                                            @endphp
                                            <tr>
                                                <td>{{ $contract->type->name }}</td>
                                                <td>{{ $amounts["duration"] }} dias</td>
                                                <td>sueldo: {{ number_format($partial_amount /3, 2, ',', '.') }}</td>
                                                <td class="text-right">
                                                    <b>{{ number_format($total_amount, 2, ',', '.') }}</b>
                                                    <input type="hidden" name="procedure_type_id[]" value="{{ $contract->procedure_type_id }}">
                                                    <input type="hidden" name="contract_id[]" value="{{ $contract->id }}">
                                                    <input type="hidden" name="salary[]" value="{{ $partial_amount /3 }}">
                                                    <input type="hidden" name="days[]" value="{{ $amounts["duration"] }}">
                                                    <input type="hidden" name="amount[]" value="{{ $total_amount }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            @php
                                $cont++;
                                $total += $total_amount;
                            @endphp
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