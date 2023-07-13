
<div class="col-md-12 text-right">
    @if (count($contracts))
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
        <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Excel</button>
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
                            <th>Unidad administrativa</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Nombre(s)</th>
                            <th>Apellidos</th>
                            <th>CI</th>
                            <th>Género</th>
                            <th>NUA/CUA</th>
                            <th>Cargo</th>
                            <th>Nivel</th>
                            <th>Sueldo</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Monto total</th>
                            <th>Programa/Proyecto</th>
                            <th>Cat. prog.</th>
                            <th>Estado</th>
                            <th>Registrado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @forelse ($contracts as $item)
                            @php
                                $salary = 0;
                                $total = 0;
                                if ($item->cargo) {
                                    $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                                }
                                if($item->job){
                                    $salary = $item->job->salary;
                                }

                                // Calcular finalización de contrato en caso de tener adenda
                                if($item->addendums->count() > 0) {
                                    $contract_finish = date('Y-m-d', strtotime($item->addendums->first()->start." -1 days"));
                                } else {
                                    $contract_finish = $item->finish;
                                }

                                if($item->start && $contract_finish){
                                    $contract_duration = contract_duration_calculate($item->start, $contract_finish);
                                    $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
                                }
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->nombre : 'No definida' }}</td>
                                <td>{{ $item->unidad_administrativa ? $item->unidad_administrativa->nombre : '' }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->type->name }}</td>
                                <td>{{ $item->person->first_name }} </td>
                                <td>{{ $item->person->last_name }}</td>
                                <td>{{ $item->person->ci }}</td>
                                <td>{{ $item->person->gender }}</td>
                                <td>{{ $item->person->nua_cua }}</td>
                                <td>
                                    @if ($item->cargo)
                                        {{ $item->cargo->Descripcion }}
                                    @elseif ($item->job)
                                        {{ $item->job->name }}
                                    @else
                                        No definio
                                    @endif
                                </td>
                                <td>
                                    @if ($item->cargo)
                                        {{ $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->NumNivel }}
                                    @elseif ($item->job)
                                        {{ $item->job->level }}
                                    @else
                                        No definido
                                    @endif
                                </td>
                                <td>{{ number_format($salary, 2, ',', '.') }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                                <td>{{ $contract_finish ? date('d/m/Y', strtotime($contract_finish)) : '' }}</td>
                                <td>{{ $total ? number_format($total, 2, ',', '.') : 'No definido' }}</td>
                                <td>{{ $item->program ? $item->program->name : 'No definido' }}</td>
                                <td>{{ $item->program ? $item->program->programatic_category : 'No definida' }}</td>
                                <td>{{ $item->status }}</td>
                                <td>
                                    {{ $item->user->name }} <br>
                                    <small>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
                                </td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            <tr class="odd">
                                <td valign="top" colspan="20" class="text-center">No hay datos disponibles en la tabla</td>
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