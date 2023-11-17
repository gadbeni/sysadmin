<table class="table table-bordered">
    <thead>
        <tr>
            <th>N&deg;</th>
            <th>Funcionario</th>
            <th>Inicio de contrato</th>
            <th>Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        @php
            $cont = 1;
        @endphp
        @forelse ($contracts as $item)
            <tr>
                <td>{{ $cont }}</td>
                <td>{{ $item->person->first_name }} {{ $item->person->last_name }}</td>
                <td>{{ $item->start }}</td>
                <td class="text-center"><input type="checkbox" name="contract_id[]" value="{{ $item->id }}" style="scale: 1.2"></td>
            </tr>
            @php
                $cont++;
            @endphp
        @empty
            
        @endforelse
    </tbody>
</table>