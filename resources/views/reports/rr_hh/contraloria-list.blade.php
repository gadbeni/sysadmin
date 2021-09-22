
<div class="col-md-12 text-right">
    @if (count($funcionarios))
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
                            <th>NIVEL</th>
                            <th>APELLIDOS Y NOMBRES / CARGO</th>
                            <th>CÉDULA DE IDENTIDAD</th>
                            <th>EXP</th>
                            <th>N&deg; NUA/CUA</th>
                            <th>FECHA INGRESO</th>
                            <th>FECHA NACIMIENTO</th>
                            <th>FECHA CONCLUSIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @forelse ($funcionarios as $item)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->Nivel }}</td>
                                <td>{{ $item->Apaterno }} {{ $item->Amaterno }} {{ $item->Pnombre }} <br> <small>{{ $item->Cargo }}</small></td>
                                <td>{{ $item->CedulaIdentidad }}</td>
                                <td>{{ $item->Expedido }}</td>
                                <td>{{ $item->Num_Nua }}</td>
                                <td>{{ $item->Fecha_Inicio }}</td>
                                <td>{{ $item->fechanacimiento }}</td>
                                <td>{{ $item->Fecha_Conclusion == '0000-00-00' ? 'No definida' : $item->Fecha_Conclusion }}</td>
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