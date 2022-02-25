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
                        <td>
                            {{ str_pad($item->id, 6, "0", STR_PAD_LEFT) }} <br>
                            @if ($item->centralize_code)
                                <label class="label label-danger" title="{{ str_pad($item->centralize_code, 6, "0", STR_PAD_LEFT) }}">centralizada</label>
                                
                            @endif
                        </td>
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
                            @if ($item->status == 'procesada')
                                <button type="button" data-id="{{ $item->id }}" class="btn btn-dark btn-send" data-toggle="modal" data-target="#send_modal"><i class="glyphicon glyphicon-ok-circle"></i> Enviar</button>
                            @endif

                            @if ($item->status != 'borrador')
                                @if ($item->centralize_code)
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ route('paymentschedules.show', ['paymentschedule' => $item->id]) }}">Sola</a></li>
                                            <li><a href="{{ route('paymentschedules.show', ['paymentschedule' => $item->id]).'?centralize=true' }}">Agrupada</a></li>
                                        </ul>
                                    </div>
                                @else
                                    <a href="{{ route('paymentschedules.show', ['paymentschedule' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                        <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                    </a>
                                @endif
                            @endif
                            @if ($item->status != 'pagada' && Auth::user()->hasPermission('delete_paymentschedules') )
                                <button type="button" data-id="{{ $item->id }}" data-toggle="modal" data-target="#cancel-modal" title="Anular" class="btn btn-sm btn-danger btn-cancel edit">
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

{{-- send modal --}}
<form id="form-send" action="{{ route('paymentschedules.update.status') }}" method="POST">
    @csrf
    <input type="hidden" name="id">
    <input type="hidden" name="status" value="enviada">
    <div class="modal modal-primary fade" tabindex="-1" id="send_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-ok-circle"></i> Desea enviar la siguiente planilla?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-dark" value="Sí, enviar">
                </div>
            </div>
        </div>
    </div>
</form>

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
                    <input type="hidden" name="id" >
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

        $('.btn-send').click(function(){
            $('#form-send input[name="id"]').val($(this).data('id'));
        });

        $('.btn-cancel').click(function(){
            $('#form-cancel input[name="id"]').val($(this).data('id'));
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