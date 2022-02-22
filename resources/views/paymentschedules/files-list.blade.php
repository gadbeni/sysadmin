<div class="col-md-12">
    <div id="dataTable" class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dirección administrativa</th>
                    <th>Periodo</th>
                    <th>Tipo planilla</th>
                    <th>Tipo de archivo</th>
                    <th>Estado</th>
                    <th>Creado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->direccion_administrativa->NOMBRE }}</td>
                        <td>{{ $item->period->name }}</td>
                        <td>{{ $item->procedure_type->name }}</td>
                        <td>{{ $item->type }}</td>
                        <td>
                            @php
                                $status = '';
                                switch ($item->status) {
                                    case '1':
                                        $status = '<label class="label label-default">Borrador</label>';
                                        break;
                                    case '2':
                                        $status = '<label class="label label-success">Cargado</label>';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            @endphp
                            {!! $status !!}
                        </td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/m/Y', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">
                            <a href="{{ url('storage/'.$item->url) }}" target="_blank" title="Descargar" class="btn btn-sm btn-warning view">
                                <i class="voyager-book-download"></i> <span class="hidden-xs hidden-sm">Descargar</span>
                            </a>
                            {{-- <button type="button" onclick="deleteItem('{{ route('paymentschedules.destroy', ['paymentschedule' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                            </button> --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9"><h5 class="text-center">No se encontraron resultados</h5></td>
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

{{-- Modal delete massive --}}
<div class="modal modal-danger fade" tabindex="-1" id="delete_multiple" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i> ¿Estás seguro?</h4>
            </div>
            <div class="modal-body">
                <h4>¿Estás seguro de que quieres eliminar los cheques seleccionados?</h4>
            </div>
            <div class="modal-footer">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <input type="submit" class="btn btn-danger delete-confirm" value="Anular">
            </div>
        </div>
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