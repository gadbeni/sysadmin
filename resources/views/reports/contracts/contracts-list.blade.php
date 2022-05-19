
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
                            <th>Unidad administrativa</th>
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
                            <th>Cat. prog.</th>
                            <th>Estado</th>
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
                            <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->nombre : 'No definida' }}</td>
                            <td>{{ $item->unidad_administrativa ? $item->unidad_administrativa->nombre : '' }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->type->name }}</td>
                            <td>{{ $item->person->first_name }} </td>
                            <td>{{ $item->person->last_name }}</td>
                            <td>{{ $item->person->ci }}</td>
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
                            <td>
                                @if ($item->cargo)
                                    {{ number_format($item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.') }}
                                @elseif ($item->job)
                                    {{ number_format($item->job->salary, 2, ',', '.') }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                            <td>{{ $item->finish ? date('d/m/Y', strtotime($item->finish)) : '' }}</td>
                            <td>{{ $item->program->name }}</td>
                            <td>{{ $item->program->programatic_category }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                        @php
                            $cont++;
                        @endphp
                        @empty
                            <tr class="odd">
                                <td valign="top" colspan="16" class="text-center">No hay datos disponibles en la tabla</td>
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