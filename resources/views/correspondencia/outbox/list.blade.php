<div class="table-responsive">
    <table id="dataTable" class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                {{-- <th>Tipo</th> --}}
                <th style="width: 200px">Fecha de registro</th>
                <th>Nro. de cite</th>
                <th>Remitente</th>
                <th>Destinatario</th>
                <th>Referencia</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    {{-- <td>{{ $item->tipo == 'I' ? 'Interna' : 'Externa' }}</td> --}}
                    <td>
                        @if ($item->created_at)
                        {{ date('d/m/Y H:i:s', strtotime($item->created_at)) }} <br> <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        @else
                        No definida
                        @endif
                    </td>
                    <td>
                        {{ $item->cite }} <br>
                        {!! $item->tipo == 'I' ? '<label class="label label-info">Interna</lable>' : '<label class="label label-success">Externa</lable>' !!}
                    </td>
                    <td>{{ $item->remitente }}</td>
                    <td>
                        @php
                            $person = \App\Models\Person::where('id', $item->people_id_para)->first();
                        @endphp
                        {{$person->first_name}}
                        {{$person->last_name}} 
                    </td>

                    <td>{{ $item->referencia }}</td>
                    <td>{{ $item->estado->nombre }}</td>
                    <td class="no-sort no-click bread-actions text-right">
                        <a href="{{ route('outbox.show', ['outbox' => $item->id]) }}" title="Ver" class="btn btn-sm btn-info view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        {{-- @if ($item->inbox->count() == 0)
                            <a href="{{ route('outbox.edit', ['outbox' => $item->id]) }}" title="Editar" class="btn btn-sm btn-warning">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                        @endif --}}

                        @if ($item->inbox->whereNull('deleted_at')->count() <= 1)
                            <button title="Anular" class="btn btn-sm btn-danger delete" data-toggle="modal" data-target="#delete_modal" onclick="deleteItem('{{ url('admin/outbox/'.$item->id) }}')">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <h5 class="text-center" style="margin-top: 50px">
                            <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.5"> <br>
                            No hay resultados
                        </h5>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
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
    $('.page-link').click(function(e){
        e.preventDefault();
        let link = $(this).attr('href');
        if(link){
            page = link.split('=')[1];
            list(page);
        }
    });
</script>