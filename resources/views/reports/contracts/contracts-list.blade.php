
<div class="col-md-12 text-right">
    @if (count($contracts))
        <button type="button" onclick="report_print()" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
@php
    $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
@endphp
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>Dirección administrativa</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Nombre(s)</th>
                            <th>Apellidos</th>
                            <th>CI</th>
                            <th>Cargo</th>
                            <th>Nivel</th>
                            <th>Sueldo</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Programa</th>
                            <th>Categoría programática</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @forelse ($contracts as $item)
                        {{-- {{ dd($item) }} --}}
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $item->direccion_administrativa->NOMBRE }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->type->name }}</td>
                            <td>{{ $item->person->first_name }} </td>
                            <td>{{ $item->person->last_name }}</td>
                            <td>{{ $item->person->ci }}</td>
                            <td>{{ $item->cargo ? $item->cargo->Descripcion : $item->job->name }}</td>
                            <td>{{ $item->cargo ? $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->NumNivel : $item->job->level }}</td>
                            <td>{{ number_format($item->cargo ? $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo : $item->job->salary, 2, ',', '.') }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->finish)) }}</td>
                            <td>{{ $item->program->name }}</td>
                            <td>{{ $item->program->programatic_category }}</td>

                        </tr>
                        @php
                            $cont++;
                        @endphp
                        @empty
                            <tr class="odd">
                                <td valign="top" colspan="14" class="text-center">No hay datos disponibles en la tabla</td>
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