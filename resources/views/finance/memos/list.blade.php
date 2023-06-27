<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>Código</th>
                    <th>De</th>
                    <th>Para</th>
                    <th>Tipo</th>
                    <th>Orden</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ str_pad($item->code, 9, "0", STR_PAD_LEFT) }}</td>
                        <td>
                            {{ $item->origin->person->first_name }} {{ $item->origin->person->last_name }} <br>
                            <small><b>{{ Str::upper($item->origin_alternate_job ?? $item->origin->cargo ? $item->origin->cargo->descripcion : $item->origin->job->name) }}</b></small>
                        </td>
                        <td>
                            {{ $item->destiny->person->first_name }} {{ $item->destiny->person->last_name }} <br>
                            <small><b>{{ Str::upper($item->destiny_alternate_job ?? $item->destiny->cargo ? $item->destiny->cargo->descripcion : $item->destiny->job->name) }}</b></small>
                        </td>
                        <td>
                            <span data-toggle="popover" data-placement="top" data-trigger="hover" title="Glosa" data-content="{{ $item->concept }}" style="cursor: pointer">
                                {{ $item->memos_type->description }} <br>
                                <small><b>{{ $item->type }}</b></small>
                            </span>
                        </td>
                        <td>
                            {{ $item->person_external->full_name }} <br>
                            @if ($item->person_external->ci_nit)
                                <small>CI: {{ $item->person_external->ci_nit }}</small> <br>
                            @endif
                            @if ($item->person_external->number_acount)
                                <small>{{ $item->person_external->number_acount }}</small>
                            @endif
                            @if ($item->additional_person->count())
                                @foreach ($item->additional_person as $additional_person)
                                    {{ $additional_person->person_external->full_name }} <br>
                                    @if ($item->person_external->ci_nit)
                                        <small>CI: {{ $additional_person->person_external->ci_nit }}</small> <br>
                                    @endif
                                    @if ($additional_person->person_external->number_acount)
                                        <small>{{ $additional_person->person_external->number_acount }}</small>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>{{ number_format($item->amount, 2, ',', '.') }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/m/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                                    Más <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                    <li><a href="{{ url('admin/memos/'.$item->id.'/print') }}" title="Imprimir" target="_blank">Imprimir</a></li>
                                </ul>
                            </div>
                            <a href="{{ route('memos.show', $item->id) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                            <a href="{{ route('memos.edit', $item->id) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                            <a href="#" title="Borrar" class="btn btn-sm btn-danger delete">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                            </a>
                        </td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="10" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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