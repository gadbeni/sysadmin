<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tipo</th>
            <th>Número</th>
            <th>EXP</th>
            <th>NUA / CUA</th>
            <th>Primer apellido (Paterno)</th>
            <th>Segundo apellido (Materno)</th>
            <th>Apellido Casada</th>
            <th>Primer Nombre</th>
            <th>Segundo Nombre</th>
            <th>Departamento</th>
            <th>Novedad (I/R/L/S)</th>
            <th>Fecha Novedad dd/mm/aaaa</th>
            <th>Dias Cotizados</th>
            <th>Tipo de Asegurado</th>
            <th>Total Ganado dep. o aseg. < 65 Aporta</th>
            <th>Total Ganado dep. o aseg. > 65 Aporta</th>
            <th>Total Ganado aseg. con pens. < 65 no Aporta</th>
            <th>Total Ganado aseg. con pens. > 65 no Aporta</th>
            <th>Cotizacion Adicional</th>
            <th>Total Ganado Bs. Vivienda</th>
            <th>Total Ganado Bs. Fondo Social</th>
            <th>Total Ganado Bs.(minero)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $cont = 1
        @endphp
        @foreach($data as $item)
            @php
            // dd($item);
                $period = \App\Models\Period::findOrFail($item->paymentschedule->period_id);
                $year = Str::substr($period->name, 0, 4);
                $month = Str::substr($period->name, 4, 2);
            @endphp
            <tr>
                <td>{{ $cont }}</td>
                <td>CI</td>
                <td>{{ $item->contract->person->ci }}</td>
                <td></td>
                <td>{{ $item->contract->person->nua_cua }}</td>
                <td>{{ explode(' ', $item->contract->person->last_name)[0] }}</td>
                <td>{{ count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : '' }}</td>
                <td></td>
                <td>{{ explode(' ', $item->contract->person->first_name)[0] }}</td>
                <td>{{ count(explode(' ', $item->contract->person->first_name)) > 1 ? explode(' ', $item->contract->person->first_name)[1] : '' }}</td>
                <td>BENI</td>
                <td></td>
                <td></td>
                <td>{{ $item->worked_days }}</td>
                <td>N</td>
                {{-- < 65 && no jubilado si aporta --}}
                <td>{{ number_format(0, 2, ',', '.') }}</td>
                {{-- >= 65 && no jubilado si aporta --}}
                <td>{{ number_format(0, 2, ',', '.') }}</td>
                {{-- < 65 && si jubilado no aporta --}}
                <td>{{ number_format(0, 2, ',', '.') }}</td>
                {{-- >= 65 && si jubilado no aporta --}}
                <td>{{ number_format(0, 2, ',', '.') }}</td>
                
                <td>{{ number_format(0, 2, ',', '.') }}</td>
                
                {{-- Sueldo --}}
                <td>{{ number_format(0, 2, ',', '.') }}</td>
                {{-- Sueldo --}}
                <td>{{ number_format(0, 2, ',', '.') }}</td>
                
                <td>{{ number_format(0, 2, ',', '.') }}</td>
            </tr> 
            @php
                $cont++;
            @endphp
        @endforeach
    </tbody>
</table>