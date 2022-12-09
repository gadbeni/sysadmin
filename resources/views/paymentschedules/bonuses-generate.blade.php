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
                        {{-- <th>DIRECCIÓN ADMINISTRATIVA</th> --}}
                        <th>DETALLE</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $total = 0;
                    @endphp
                    @foreach ($people as $item)
                        @if ($item->months)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->last_name }} {{ $item->first_name }}</td>
                                <td>{{ $item->ci }}</td>
                                {{-- <td>{{ $item->last_contract->type->name }}</td> --}}
                                {{-- <td>{{ $item->last_contract->direccion_administrativa ? $item->last_contract->direccion_administrativa->nombre : 'No definida' }}</td> --}}
                                <td>
                                    <table>
                                        @foreach ($item->months as $month)
                                            <tr>
                                                <td>{{ $month['amount'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right"><b>TOTAL</b></td>
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