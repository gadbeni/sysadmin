
<div class="col-md-12 text-right">
    @if (count($planillas))
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button> --}}
        {{-- <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button> --}}
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>DIRECCIÓN ADMINISTRATIVA</th>
                            <th>TIPO DE PLANILLA</th>
                            <th>PLANILLA</th>
                            <th>AÑO</th>
                            <th>MES</th>
                            <th>N&deg; PERSONAS</th>
                            <th>TOTAL</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        @forelse ($planillas as $item)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->direccion_administrativa }}</td>
                                <td>{{ $item->tipo_planilla }}</td>
                                <td>{{ $item->ID }}</td>
                                <td>{{ $item->Anio }}</td>
                                <td>{{ $months[intval($item->Mes)] }}</td>
                                <td>{{ $item->NumPersonas }}</td>
                                <td style="text-align: right">{{ number_format($item->Monto, 2, ',', '.') }}</td>
                                <td>
                                    @switch($item->Estado)
                                        @case(1)
                                            <span class="label label-dark">Cargada</span>
                                            @break
                                        @case(2)
                                            <span class="label label-info">Procesada</span>
                                            @break
                                        @case(3)
                                            <span class="label label-success">Pagada</span>
                                            @break
                                        @default
                                            <span class="label label-default">Desconocido</span>
                                    @endswitch
                                </td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* th{
        font-size: 8px
    }
    td{
        font-size: 11px
    } */
</style>

<script>
    $(document).ready(function(){

    })
</script>