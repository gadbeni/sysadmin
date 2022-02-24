<div class="col-md-12">
    <div id="dataTable" class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dirección administrativa</th>
                    <th>Periodo</th>
                    <th>Tipo planilla</th>
                    <th>N&deg; de personas</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Creado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ str_pad($item->centralize_code != '' ? $item->centralize_code : $item->id, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ $item->direccion_administrativa->NOMBRE }}</td>
                        <td>{{ $item->period->name }}</td>
                        <td>{{ $item->procedure_type->name }}</td>
                        <td>{{ $item->details->count() }}</td>
                        <td class="text-right">{{ number_format($item->details->sum('liquid_payable'), 2, ',', '.') }}</td>
                        <td>
                            @php
                                switch ($item->status) {
                                    case 'anulada':
                                        $label = 'danger';
                                        break;
                                    case 'borrador':
                                        $label = 'default';
                                        break;
                                    case 'procesada':
                                        $label = 'info';
                                        break;
                                    case 'enviada':
                                        $label = 'primary';
                                        break;
                                    case 'habilitada':
                                        $label = 'success';
                                        break;
                                    case 'pagada':
                                        $label = 'dark';
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
                            {{ date('d/m/Y', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">
                            @if ($item->status != 'borrador')
                                <a href="{{ route('paymentschedules.show', ['paymentschedule' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                </a>
                            @endif
                            @if ($item->status != 'pagada' && $item->status != 'borrador')
                                <button type="button" data-id="{{ $item->centralize_code }}" data-toggle="modal" data-target="#cancel-modal" title="Revertir" class="btn btn-sm btn-dark btn-cancel edit">
                                    <i class="glyphicon glyphicon-retweet"></i> <span class="hidden-xs hidden-sm">Revertir</span>
                                </button>
                            @endif
                            @if ($item->status != 'pagada' )
                                <button type="button" onclick="deleteItem('{{ route('paymentschedules.destroy', ['paymentschedule' => $item->centralize_code]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                </button>
                            @endif
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

{{-- Modal cancel --}}
<form id="form-cancel" action="{{ route('paymentschedules.cancel') }}" method="post">
    <div class="modal modal-primary fade" tabindex="-1" id="cancel-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-retweet"></i> Desea revertir la siguiente planilla?</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="centralize_code" >
                    <div class="form-group">
                        <label for="observations">Descipción del Motivo</label>
                        <textarea class="form-control richTextBox" name="observations"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-dark" value="Revertir">
                </div>
            </div>
        </div>
    </div>
</form>

<style>

</style>

<script>
    var page = "{{ request('page') }}";
    $(document).ready(function(){
        $.extend({selector: '.richTextBox'}, {})
        tinymce.init(window.voyagerTinyMCE.getConfig({selector: '.richTextBox'}));

        $('.btn-cancel').click(function(){
            $('#form-cancel input[name="centralize_code"]').val($(this).data('id'));
        });

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