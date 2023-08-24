<table>
    <thead>
        <tr>
            <th><b>Nombre completo</b></th>
            <th><b>CI</b></th>
            <th><b>Lugar nac.</b></th>
            <th><b>Fecha nac.</b></th>
            <th><b>Edad</b></th>
            <th><b>Género</b></th>
            <th><b>Telefono</b></th>
            <th><b>Email</b></th>
            <th><b>AFP</b></th>
            <th><b>NUA/CUA</b></th>
            <th><b>Caja de salud</b></th>
            <th><b>Dependencia</b></th>
            <th><b>Registrado</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($people as $item)
            @php
                $now = \Carbon\Carbon::now();
                $birthday = new \Carbon\Carbon($item->birthday);
                $age = $birthday->diffInYears($now);
            @endphp
            <tr>
                <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                <td>{{ $item->ci }}</td>
                <td>{{ $item->city ? $item->city->name : 'No definido' }}</td>
                <td>{{ date('d/m/Y', strtotime($item->birthday)) }}</td>
                <td>{{ $age }}</td>
                <td>{{ Str::ucfirst($item->gender) }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->afp_type->name }}</td>
                <td>{{ $item->nua_cua }}</td>
                <td>{{ $item->cc == 1 ? 'Caja Cordes' : 'Caja Petrolera' }}</td>
                <td>
                    @if ($item->contracts->count())
                        {{ $item->contracts[0]->direccion_administrativa->nombre }}
                    @endif
                </td>
                <td>
                    {!! $item->user ? $item->user->name.'<br>' : '' !!}
                    <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>