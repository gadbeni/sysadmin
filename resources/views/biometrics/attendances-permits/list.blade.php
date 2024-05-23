<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Fecha</th>
                    <th>Cantidad de<br>personas</th>
                    <th>Estado</th>
                    <th>Registrado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $months = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                    $days = ['', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                @endphp
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->type ? $item->type->name : 'No definido' }}</td>
                        <td>
                            @switch($item->category)
                                @case(1)
                                    Licencia
                                    @break
                                @case(2)
                                    Comisión
                                    @break
                                @case(3)
                                    Permiso especial
                                    @break
                                @default
                                    
                            @endswitch
                        </td>
                        <td>
                            @if ($item->start == $item->finish)
                                {{ $days[date('N', strtotime($item->start))].' '.date('d/', strtotime($item->start)).$months[intval(date('m', strtotime($item->start)))].date('/Y', strtotime($item->start)) }}
                                @if ($item->time_start && $item->time_finish)
                                    <br>
                                    De {{ date('H:i', strtotime($item->time_start)) }} a {{ date('H:i', strtotime($item->time_finish)) }}
                                @endif
                            @else
                                {{ $days[date('N', strtotime($item->start))].' '.date('d/', strtotime($item->start)).$months[intval(date('m', strtotime($item->start)))] }} al {{ $days[date('N', strtotime($item->finish))].' '.date('d/', strtotime($item->finish)).$months[intval(date('m', strtotime($item->finish)))] }} de {{ date('Y', strtotime($item->start)) }}
                            @endif
                        </td>
                        <td>{{ $item->details->count() }}</td>
                        <td>
                            @php
                                switch ($item->status) {
                                    case 'elaborado':
                                        $label = 'default';
                                        break;
                                    default:
                                        $label = 'default';
                                        break;
                                }
                            @endphp
                            <label class="label label-{{ $label }}">{{ ucfirst($item->status) }}</label>
                        </td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/', strtotime($item->created_at)).$months[intval(date('m', strtotime($item->created_at)))].date('/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">
                            @if (auth()->user()->hasPermission('read_attendances-permits'))
                                <a href="{{ route('attendances-permits.show', $item->id) }}" title="Ver" class="btn btn-sm btn-warning view">
                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="8" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<style>

</style>

<script>
    var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>