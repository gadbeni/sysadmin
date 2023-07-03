<div class="col-md-12">
    <div id="dataTable" class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dirección administrativa</th>
                    <th>Tipo planilla</th>
                    <th>N&deg; de personas</th>
                    <th>Monto</th>
                    <th style="min-width: 200px">Detalles</th>
                    <th>Creado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>
                            {{ str_pad($item->id, 6, "0", STR_PAD_LEFT).($item->aditional ? '-A' : '') }} <br>
                            @if ($item->centralize)
                                <label class="label label-danger" title="Centralizada">{{ str_pad($item->centralize_code, 6, "0", STR_PAD_LEFT) }}</label>
                            @endif
                        </td>
                        <td>{{ $item->direccion_administrativa->nombre }}</td>
                        <td>{{ $item->procedure_type->name }} <br> <b>{{ $item->period->name }}</b></td>
                        <td>
                            {{ $item->details->count() }} Personas<br>
                            @if ($item->status == 'habilitada')
                            <small>Pendientes {{ $item->details->where('status', 'habilitado')->count() }}</small> <br>
                            <small>Pagadas {{ $item->details->where('status', 'pagado')->count() }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->details->sum('liquid_payable'), 2, ',', '.') }}</td>
                        <td>
                            <b>Total ganado: {{ number_format($item->details->sum('partial_salary') + $item->details->sum('seniority_bonus_amount'), 2, ',', '.') }}</b> <br>
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
                                    case 'aprobada':
                                        $label = 'warning';
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
                                $details = $item->details->groupBy('afp');
                                $afp_total = 0;
                            @endphp
                            @foreach ($details as $key => $detail)
                                @php
                                    $patronal = $detail->sum('common_risk') + $detail->sum('solidary_employer') + $detail->sum('housing_employer');
                                    $labor = $detail->sum('labor_total');

                                    $afp_total += $patronal + $labor;
                                    $afp_type = App\Models\Afp::find($key);
                                @endphp
                                <span>{{ $afp_type->name }}</span>:  {{number_format($patronal + $labor, 2, ',', '.') }} (<b>{{ $detail->count() }}</b>)<br>
                            @endforeach
                            <b>Total AFP : {{ number_format($afp_total, 2, ',', '.') }}</b> <br>
                            <label class="label label-{{ $label }}">{{ ucfirst($item->status) }}</label>
                        </td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/m/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">

                            @if ($item->status == 'procesada' && (auth()->user()->hasPermission('approve_paymentschedules') || Auth::user()->direccion_administrativa_id))
                                <button type="button" data-id="{{ $item->id }}" class="btn btn-default btn-send" data-toggle="modal" data-target="#send-modal"><i class="glyphicon glyphicon-share-alt"></i> Enviar</button>
                            @endif

                            {{-- Si la planillas está  enviada --}}
                            @if (!$item->centralize && $item->status == 'enviada' && auth()->user()->hasPermission('approve_paymentschedules'))
                                <button title="Aprobar planilla" type="button" data-id="{{ $item->id }}" class="btn btn-default btn-approve" data-toggle="modal" data-target="#approve-modal"><i class="glyphicon glyphicon-ok-circle"></i> Aprobar</button>
                            @endif

                            {{-- Si la planilla está aprobada o está habilitada para pago y parte de la planilla no ha sido habilitada se muestra el botón de habilitación --}}
                            @if (($item->status == 'aprobada' || ($item->status == 'habilitada' && $item->details->where('status', 'procesado')->where('deleted_at', NULL)->count()) > 0) && auth()->user()->hasPermission('enable_paymentschedules'))
                                <button title="Habilitar para pagos" type="button" data-id="{{ $item->id }}" data-toggle="modal" data-target="#enable-modal" class="btn btn-default btn-enable"><i class="voyager-dollar"></i> Habilitar</button>
                            @endif

                            {{-- Si la planilla está habiliatda y ninguno de los funcionarios está con pago procesado para pago o la planilla está aprobada y el usuario es de una DA desconcentrada se muestra el botón de pagada --}}
                            @if ($item->status == 'habilitada' && $item->details->where('status', 'procesado')->where('deleted_at', NULL)->count() == 0 && auth()->user()->hasPermission('close_paymentschedules') || ($item->status == 'aprobada' && Auth::user()->direccion_administrativa_id) )
                                <button type="button" data-id="{{ $item->id }}" data-toggle="modal" data-target="#close-modal" class="btn btn-default btn-close"><i class="voyager-lock"></i> Cerrar</button>
                            @endif

                            @if ($item->status != 'borrador' && auth()->user()->hasPermission('read_paymentschedules'))
                                @if ($item->centralize_code && auth()->user()->hasPermission('read_centralize_paymentschedules'))
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                            <span class="voyager-eye"> <span class="hidden-xs hidden-sm">Ver</span> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{ route('paymentschedules.show', ['paymentschedule' => $item->id]) }}">Sola</a></li>
                                            <li><a href="{{ route('paymentschedules.show', ['paymentschedule' => $item->id]).'?centralize=true' }}">Centralizada</a></li>
                                        </ul>
                                    </div>
                                @else
                                    <a href="{{ route('paymentschedules.show', ['paymentschedule' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                        <i class="voyager-eye"></i><span class="hidden-xs hidden-sm"> Ver</span>
                                    </a>
                                @endif
                            @endif

                            <div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                    <span class="voyager-plus"> <span class="hidden-xs hidden-sm">Más</span> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                    @if ($item->centralize && auth()->user()->hasPermission('edit_paymentschedules') && $item->status != 'pagada')
                                        <li><a href="#" class="btn-update-centralize" data-id="{{ $item->id }}" data-type="decentralize" data-toggle="modal" data-target="#update-centralize-modal" title="Descentralizar">Descentralizar</a></li>
                                    @endif

                                    @if (!$item->centralize && auth()->user()->hasPermission('edit_paymentschedules') && $item->status != 'pagada')
                                        <li><a href="#" class="btn-update-centralize" data-id="{{ $item->id }}" data-type="centralize" data-toggle="modal" data-target="#update-centralize-modal" title="Centralizar">Centralizar</a></li>
                                    @endif

                                    @if ( ($item->status == 'aprobada' || $item->status == 'habilitada' || $item->status == 'pagada') && (auth()->user()->hasPermission('print_paymentschedules') || Auth::user()->role_id == 1) )
                                        <li><a title="Imprimir boleta" href="{{ route('planillas.pagos.print.group', ['id' => $item->id]) }}" target="_blank">Imprimir Boleta</a></li>
                                    @endif  
                                </ul>
                            </div>

                            @if ((($item->status != 'habilitada' && $item->status != 'pagada' && auth()->user()->hasPermission('delete_paymentschedules')) || Auth::user()->role_id == 1 ) && $item->status != 'pagada')
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
@include('paymentschedules.partials.modal-send-paymentschedule')

{{-- send modal --}}
@include('paymentschedules.partials.modal-enable-paymentschedule')

{{-- close modal --}}
@include('paymentschedules.partials.modal-close-paymentschedule')

{{-- approve modal --}}
<form id="form-approve" class="form-submit" action="{{ route('paymentschedules.update.status') }}" method="POST">
    @csrf
    <div class="modal modal-info fade submit-modal" tabindex="-1" id="approve-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-ok-circle"></i> Desea aprobar la siguiente planilla?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" name="status" value="aprobada">
                    <div class="col-md-12">
                        <p class="text-muted">
                            <b>Advertencia!</b> <br>
                            Esta acción cambiará el estado de la planilla a <b>Aprobada</b> y no podrá generar más planillas para este tipo de planillas y periodo.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-info" value="Sí, aprobar">
                </div>
            </div>
        </div>
    </div>
</form>

{{-- approve modal --}}
<form id="form-update-centralize" class="form-submit" action="{{ route('paymentschedules.update.centralize') }}" method="POST">
    @csrf
    <div class="modal fade submit-modal" tabindex="-1" id="update-centralize-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-pushpin"></i> Desea descentralizar planilla?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" name="type">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-info" value="Sí, aceptar">
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Modal cancel --}}
<form id="form-cancel" class="form-submit" action="{{ route('paymentschedules.cancel') }}" method="post">
    <div class="modal modal-danger fade submit-modal" tabindex="-1" id="cancel-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-retweet"></i> Desea anular la siguiente planilla?</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" >
                    <div class="form-group">
                        <label for="observations">Descripción del Motivo</label>
                        <textarea class="form-control richTextBox" name="observations"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-danger" value="Anular">
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .dropdown-toggle{
        border: 0px
    }
</style>

<script>
    var page = "{{ request('page') }}";
    $(document).ready(function(){
        $.extend({selector: '.richTextBox'}, {})
        tinymce.init(window.voyagerTinyMCE.getConfig({selector: '.richTextBox'}));

        $('.btn-send').click(function(){
            $('#form-send input[name="id"]').val($(this).data('id'));
        });

        $('.btn-approve').click(function(){
            $('#form-approve input[name="id"]').val($(this).data('id'));
        });

        $('.btn-enable').click(function(){
            $('#form-enable input[name="id"]').val($(this).data('id'));
        });

        $('.btn-close').click(function(){
            $('#form-close input[name="id"]').val($(this).data('id'));
        });

        $('.btn-update-centralize').click(function(){
            $('#form-update-centralize input[name="id"]').val($(this).data('id'));
            $('#form-update-centralize input[name="type"]').val($(this).data('type'));
            if($(this).data('type') == 'centralize'){
                $('#form-update-centralize .modal-title').html(`<i class="glyphicon glyphicon-pushpin"></i> Desea centralizar planilla?`);
            }else{
                $('#form-update-centralize .modal-title').html(`<i class="glyphicon glyphicon-pushpin"></i> Desea descentralizar planilla?`);
            }
        });

        $('.btn-cancel').click(function(){
            $('#form-cancel input[name="id"]').val($(this).data('id'));
        });

        $('.form-submit').submit(function(e){
            $('.submit-modal').modal('hide');
            e.preventDefault();
            $('#div-results').loading({message: 'Cargando...'});
            $.post($(this).attr('action'), $(this).serialize(), function(res){
                if(res.message){
                    toastr.success(res.message);
                    list(page);
                }else{
                    toastr.error(res.error);
                    $('#div-results').loading('toggle');
                }
            });
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