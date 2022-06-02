
<div class="col-md-12 text-right">
    @if (count($relationships))
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
                            <th>APELLIDOS</th>
                            <th>NOMBRES</th>
                            <th>CÉDULA DE IDENTIDAD</th>
                            <th>INAMOVILIDAD</th>
                            <th>PLANILLA</th>
                            <th>CARGO</th>
                            <th>DIRECCIÓN ADMINSTRATIVA</th>
                            {{-- <th>AFP</th>
                            <th>FECHA INGRESO</th>
                            <th>FECHA NACIMIENTO</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        @forelse ($relationships as $item)
                            @foreach ($item as $person)
                                @php
                                    // dd($person);
                                @endphp
                                <tr>
                                    <td>{{ $cont }}</td>
                                    <td>{{ $person->last_name }}</td>
                                    <td>{{ $person->first_name }}</td>
                                    <td>{{ $person->ci }}</td>
                                    <td>
                                        @php
                                            $irremovability = $person->irremovabilities->where('start', '<=', date('Y-m-d'))->whereRaw('(finish >= "'.date('Y-m-d').'" or finish is null')->first();
                                        @endphp
                                        {{ $irremovability ? 'Sí' : 'No' }}
                                    </td>
                                    <td>{{ $person->contracts->first()->type->name }}</td>
                                    <td>
                                        @if ($person->contracts->first()->cargo)
                                            {{ $person->contracts->first()->cargo->Descripcion }}
                                        @elseif ($person->contracts->first()->job)
                                            {{ $person->contracts->first()->job->name }}
                                        @else
                                            No definido
                                        @endif
                                    </td>
                                    <td>{{ $person->contracts->first()->direccion_administrativa->nombre }}</td>
                                </tr>
                                @php
                                    $cont++;
                                @endphp
                            @endforeach
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