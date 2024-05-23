<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección Administrativa</th>
                    <th>Partida</th>
                    <th>Categoría Programática</th>
                    <th>Monto</th>
                    <th>Gestión</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $meses = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                    $cont = 1;
                @endphp
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $cont }}</td>
                        <td>{{ $item->id }}</td>
                        <td>
                            {{ $item->name }} <br>
                            <label class="label label-default">{{ $item->procedure_type->name }}</label>  @if($item->unidad_administrativa_id) <i class="voyager-info-circled" title="Restringido para {{ $item->unidad_administrativa->nombre }}"></i> @endif
                        </td>
                        <td>
                            @if ($item->direcciones_administrativas->count())
                                <ul style="padding-left: 20px">
                                    @foreach ($item->direcciones_administrativas as $direccion_administrativa)
                                        <li>{{ $direccion_administrativa->nombre }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $item->direccion_administrativa->nombre }}        
                            @endif
                        </td>
                        <td>{{ $item->number }}</td>
                        <td>{{ $item->programatic_category }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $item->year }}</td>
                        <td>
                            {{ date('d/', strtotime($item->created_at)).$meses[intval(date('m', strtotime($item->created_at)))].date('/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">
                            @if (auth()->user()->hasPermission('edit_programs') && $item->direcciones_administrativas->count() == 1)
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Más <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                    <li><a href="#" data-toggle="modal" data-target="#restrict-modal" data-item='@json($item)' class="btn btn-restrict" title="Restingir a una sola Jefatura/Unidad/Sección">Restringir</a></li>
                                </ul>
                            </div>
                            @endif
                            @if (auth()->user()->hasPermission('read_programs'))
                            <a href="{{ route('voyager.programs.show', $item->id) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                            @endif
                            @if (auth()->user()->hasPermission('edit_programs'))
                            <a href="{{ route('voyager.programs.edit', $item->id) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                            @endif
                            @if (auth()->user()->hasPermission('delete_programs'))
                                <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('voyager.programs.destroy', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @php
                        $cont++;
                    @endphp
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="10" class="dataTables_empty">No hay datos registrados</td>
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
    .bread-actions .btn{
        border: 0px
    }
    .mce-edit-area{
        max-height: 250px !important;
        overflow-y: auto;
    }
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

        $('.btn-restrict').click(function(){
            let item = $(this).data('item');
            $('#form-restrict input[name="id"]').val(item.id);
            $('#select-unidad_administrativa_id').html('<option>Seleccionar Jefatura/Unidad</option>');
            item.direcciones_administrativas.map(da => {
                da.unidades_administrativas.map(ua => {
                    $('#select-unidad_administrativa_id').append(`<option value="${ua.id}">${ua.nombre}</option>`);
                });
            });
        });
    });
</script>