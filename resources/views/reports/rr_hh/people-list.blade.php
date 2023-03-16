
<div class="col-md-12 text-right">
    @if (count($people))
        {{-- <button type="button" onclick="report_export('pdf')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button> --}}
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Excel</button> --}}
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
                            <th>Nombre completo</th>
                            <th>CI</th>
                            <th>Lugar nac.</th>
                            <th>Fecha nac.</th>
                            <th>Telefono</th>
                            <th>AFP</th>
                            <th>NUA/CUA</th>
                            <th>CAJA DE SALUD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @forelse ($people as $item)
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                            <td>{{ $item->ci }}</td>
                            <td>{{ $item->city ? $item->city->name : 'No definido' }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->birthday)) }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->afp_type->name }}</td>
                            <td>{{ $item->nua_cua }}</td>
                            <td>{{ $item->cc == 1 ? 'Caja Cordes' : 'Caja Petrolera' }}</td>
                        </tr>
                        @php
                            $cont++;
                        @endphp
                        @empty
                            <tr class="odd">
                                <td valign="top" colspan="8" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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