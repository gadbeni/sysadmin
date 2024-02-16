<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>Persona</th>
                    <th>Atrasos</th>
                    <th>Faltas</th>
                    <th>Abandonos</th>
                    <th>Días de <br> descuentos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @forelse ($contracts as $item)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>
                            {{ $item->person->first_name }} {{ $item->person->last_name }} <br>
                            <label class="label label-default">{{ date('d/m/Y', strtotime($item->start)) }} - {{ $item->finish ? date('d/m/Y', strtotime($item->finish)) : 'Indefinido' }}</label>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL</td>
                    <td class="text-right"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>