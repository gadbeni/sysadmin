<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th width="90px">Código</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Custodio</th>
                    <th>Imagen</th>
                    <th>Estado</th>
                    <th>Registrado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $meses = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                @endphp
                @forelse ($data as $item)
                    @php
                        $image = asset('images/default.jpg');
                        if(count(json_decode($item->images))){
                            $image = asset('storage/'.str_replace('.', '-cropped.', json_decode($item->images)[0]));
                        }
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ Str::upper($item->code) }}</td>
                        <td>
                            {{ $item->subcategory->name }} <br>
                            <label class="label label-default">{{ $item->subcategory->category->name }}</label>
                        </td>
                        <td>{{ strlen($item->description) <= 100 ? $item->description : substr($item->description, 0, 100).'...' }}</td>
                        <td>
                            @if ($item->assignments->count())
                                @php
                                    $assignment = $item->assignments[0];
                                @endphp
                                <a href="{{ route('voyager.people.show', ['id' => $assignment->person_asset->person_id]) }}" title="Ver persona" target="_blank">{{ $assignment->person_asset->person->first_name }} {{ $assignment->person_asset->person->last_name }}</a>
                            @else
                                Ninguno
                            @endif
                        </td>
                        <td><img src="{{ $image }}" alt="image" width="60px"></td>
                        <td>
                            @php
                                if($item->status == "bueno"){
                                    $class = 'success';
                                }elseif($item->status == "regular"){
                                    $class = 'warning';
                                }elseif($item->status == "malo"){
                                    $class = 'danger';
                                }else{
                                    $item->status = 'desconocido';
                                    $class = 'default';
                                }
                            @endphp
                            <label class="label label-{{ $class }}">{{ Str::ucfirst($item->status) }}</label>
                        </td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/', strtotime($item->created_at)).$meses[intval(date('m', strtotime($item->created_at)))].date('/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">
                            @if (auth()->user()->hasPermission('read_assets'))
                                <a href="{{ route('assets.show', ['asset' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                </a>
                            @endif
                            @if (auth()->user()->hasPermission('edit_assets'))
                                <a href="{{ route('assets.edit', ['asset' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                </a>
                            @endif
                            @if (auth()->user()->hasPermission('delete_assets'))
                                <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('assets.destroy', ['asset' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="7" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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
    .mce-edit-area{
        max-height: 250px !important;
        overflow-y: auto;
    }
</style>

<script>
    moment.locale('es');
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