<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>CI</th>
                    <th>Lugar nac.</th>
                    <th>Fecha nac.</th>
                    <th>Telefono</th>
                    <th>AFP</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <table>
                            @php
                                $image = asset('images/default.jpg');
                                if($item->image){
                                    $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                                }
                                $now = \Carbon\Carbon::now();
                                $birthday = new \Carbon\Carbon($item->birthday);
                                $age = $birthday->diffInYears($now);
                            @endphp
                            <tr>
                                <td><img src="{{ $image }}" alt="{{ $item->first_name }} {{ $item->last_name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"></td>
                                <td>
                                    {{ $item->first_name }} {{ $item->last_name }}
                                    @php
                                        $irremovability = $item->irremovabilities->where('start', '<=', date('Y-m-d'))->first();
                                    @endphp
                                    @if ($irremovability)
                                        @if (!$irremovability->finish || $irremovability->finish >= date('Y-m-d'))
                                        <label class="label label-danger" title="{{ $irremovability->type->name }}">Inamovible</label>        
                                        @endif
                                    @endif
                                    <br> <small>{{ $item->profession }}</small>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>{{ $item->ci }}</td>
                    <td>{{ $item->city ? $item->city->name : 'No definido' }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->birthday)) }} <br> <small>{{ $age }} años</small> </td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->afp_type->name }} <br> <small class="@if(!$item->afp_status) text-warning @elseif(!is_numeric($item->nua_cua) || strlen($item->nua_cua) < 8 ) text-danger @endif" >{{ $item->nua_cua }}</small> </td>
                    <td class="no-sort no-click bread-actions text-right">
                        <div class="btn-group" style="margin-right: 3px">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                Más <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                @if (auth()->user()->hasPermission('add_rotation_people'))
                                <li><a href="#" class="btn-rotation" data-url="{{ route('people.rotation.store', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-rotation" >Rotar</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('add_irremovability_people'))
                                <li><a href="#" class="btn-irremovability" data-url="{{ route('people.irremovability.store', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-irremovability" >Inamovilidad</a></li>
                                @endif
                            </ul>
                        </div>

                        @if (auth()->user()->hasPermission('read_people'))
                        <a href="{{ route('voyager.people.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        @endif
                        @if (auth()->user()->hasPermission('edit_people'))
                        <a href="{{ route('voyager.people.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                        </a>
                        @endif
                        @if (auth()->user()->hasPermission('delete_people'))
                        <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('voyager.people.destroy', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
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

        $('.btn-rotation').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            $('#rotation-form').attr('action', url);
        });

        $('.btn-irremovability').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            $('#irremovability-form').attr('action', url);
        });

    });
</script>