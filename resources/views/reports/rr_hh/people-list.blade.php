
<div class="col-md-12 text-right">
    @if (count($people))
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
        <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Excel</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>Fotografía</th>
                            <th>Nombre completo</th>
                            <th>CI</th>
                            <th>Lugar nac.</th>
                            <th>Fecha nac.</th>
                            <th>Edad</th>
                            <th>Género</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>AFP</th>
                            <th>NUA/CUA</th>
                            <th>Caja de salud</th>
                            <th>Registrado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @forelse ($people as $item)
                            @php
                                $image = asset('images/default.jpg');
                                if($item->image){
                                    $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                                }
                                $now = \Carbon\Carbon::now();
                                $birthday = new \Carbon\Carbon($item->birthday);
                                $age = $birthday->diffInYears($now);
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td><img src="{{ $image }}" alt="{{ $item->first_name }} {{ $item->last_name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"></td>
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
                                    {!! $item->user ? $item->user->name.'<br>' : '' !!}
                                    <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                                </td>
                            </tr>
                        @php
                            $cont++;
                        @endphp
                        @empty
                            <tr class="odd">
                                <td valign="top" colspan="14" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    })
</script>