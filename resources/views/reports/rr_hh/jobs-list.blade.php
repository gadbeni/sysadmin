
<div class="col-md-12 text-right">
    @if (count($jobs))
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
                            <th>N&deg;</th>
                            <th>ITEM</th>
                            <th>CARGO</th>
                            <th>SUELDO</th>
                            <th>DIRECCIÓN ADMINISTRATIVA</th>
                            <th>CÓDIGO</th>
                            <th>FUNCIONARIO</th>
                            <th>INICIO</th>
                            <th>FIN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        @forelse ($jobs as $item)
                            {{-- {{ dd($item) }} --}}
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->item }}</td>
                                <td>{{ $item->name }}</td>
                                <td style="text-align: right">{{ number_format($item->salary, 2, ',', '.') }}</td>
                                <td>{{ $item->direccion_administrativa->NOMBRE }}</td>
                                <td>{!! $item->contract ? $item->contract->code : '<b style="color: red">Acéfalo</b>' !!}</td>
                                <td>{{ $item->contract ? $item->contract->person->last_name.' '.$item->contract->person->first_name : '' }}</td>
                                <td>{{ $item->contract ? date('d/m/Y', strtotime($item->contract->start)) : '' }}</td>
                                <td>{{ $item->contract ? $item->contract->finish ? date('d/m/Y', strtotime($item->contract->finish)) : 'No definido' : '' }}</td>
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