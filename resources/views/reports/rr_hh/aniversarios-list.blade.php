
<div class="col-md-12 text-right">
    @if (count($people))
        <button type="button" onclick="report_print()" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>APELLIDOS Y NOMBRES</th>
                            <th>CARGO</th>
                            <th>CÉDULA DE IDENTIDAD</th>
                            <th>AFP</th>
                            <th>FECHA NACIMIENTO</th>
                            <th>INICIO DE CONTRATO</th>
                            <th>FIN DE CONTRATO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        @forelse ($people as $item)
                            @php
                                $contract = $item->contracts->last();
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->last_name }} {{ $item->first_name }}</td>
                                <td>
                                    @if ($contract)
                                        @if ($contract->cargo)
                                            {{ $contract->cargo->Descripcion }}
                                        @elseif ($contract->job)
                                            {{ $contract->job->name }}
                                        @else
                                            No definido
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $item->ci }}</td>
                                <td>{{ $item->afp == 1 ? 'Futuro' : 'Previsión' }}</td>
                                <td>{{ date('d', strtotime($item->birthday)).'/'.$months[intval(date('m', strtotime($item->birthday)))].'/'.date('Y', strtotime($item->birthday)) }}</td>
                                <td>{{ $contract ? date('d', strtotime($contract->start)).'/'.$months[intval(date('m', strtotime($contract->start)))].'/'.date('Y', strtotime($contract->start)) : '' }}</td>
                                <td>
                                    @if ($contract)
                                        {{ $contract->finish ? date('d', strtotime($contract->finish)).'/'.$months[intval(date('m', strtotime($contract->finish)))].'/'.date('Y', strtotime($contract->finish)) : 'NO DEFINIDO' }}
                                    @endif
                                </td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="9"><h4 class="text-center">No hay resultados</h4></td>
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