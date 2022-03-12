<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Persona</th>
                    <th>Dirección administrativa</th>
                    <th>Detalles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->type->name }}</td>
                    <td>
                        {{ $item->person->first_name }} {{ $item->person->last_name }} <br>
                        <b>CI</b>: {{ $item->person->ci }}
                        {!! $item->person->phone ? '<br><b>Telf</b>: '.$item->person->phone : '' !!}
                    </td>
                    <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->NOMBRE : 'No definida' }}</td>
                    <td>
                        <ul style="list-style: none; padding-left: 0px">
                            <li>
                                <b>Cargo: </b>
                                @if ($item->cargo)
                                    {{ $item->cargo->Descripcion }}
                                @elseif ($item->job)
                                    {{ $item->job->name }}
                                @else
                                    No definio
                                @endif
                            </li>
                            <li>
                                <b>Sueldo: </b> <small>Bs.</small> 
                                @if ($item->cargo)
                                    {{ number_format($item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.') }}
                                @elseif ($item->job)
                                    {{ number_format($item->job->salary, 2, ',', '.') }}
                                @else
                                    0.00
                                @endif
                            </li>
                            <li>
                                @php
                                    switch ($item->status) {
                                        case 'anulado':
                                            $label = 'danger';
                                            break;
                                        case 'elaborado':
                                            $label = 'default';
                                            // Si el creador del contrato es de una DA desconcentrada el siguiente estado es firmado, sino es enviado
                                            $netx_status = Auth::user()->direccion_administrativa_id ? 'firmado' : 'enviado';
                                            break;
                                        case 'enviado':
                                            $label = 'info';
                                            $netx_status = 'firmado';
                                            break;
                                        case 'firmado':
                                            $label = 'success';
                                            break;
                                        case 'finalizado':
                                            $label = 'warning';
                                            break;
                                        default:
                                            $label = 'default';
                                            $netx_status = '';
                                            break;
                                    }
                                @endphp
                                <b>Estado</b>: <label class="label label-{{ $label }}">{{ ucfirst($item->status) }}</label>
                            </li>
                        </ul>
                    </td>
                    <td class="no-sort no-click bread-actions text-right">
                        
                        {{-- Definir siguiente estado --}}
                        @if ($item->status != 'firmado')
                            <button title="Promover a la siguiente instancia" data-toggle="modal" data-target="#status-modal" onclick="changeStatus({{ $item->id }}, '{{ $netx_status }}')" class="btn btn-sm btn-dark btn-status view">
                                <i class="voyager-check"></i> <span class="hidden-xs hidden-sm">Promover</span>
                            </button>
                        @endif

                        {{-- Botón de impresión --}}
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                              Imprimir <span class="caret"></span>
                            </button>
                          
                            <ul class="dropdown-menu" role="menu">
                                @switch($item->procedure_type_id)
                                    @case(1)
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum']) }}" target="_blank">Memoramdum de desiganación</a></li>
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-reasigancion']) }}" target="_blank">Memoramdum de reasignación</a></li>
                                        @break
                                    @case(2)
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.autorization']) }}" target="_blank">Autorización</a></li>
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.invitation']) }}" target="_blank">Invitación</a></li>
                                        {{-- <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'answer']) }}" target="_blank">Respuesta</a></li> --}}
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.declaration']) }}" target="_blank">Declaración</a></li>
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.memorandum']) }}" target="_blank">Memorandum</a></li>
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.report']) }}" target="_blank">Informe</a></li>
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.adjudication']) }}" target="_blank">Nota de adjudicación</a></li>
                                        {{-- <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'presentation']) }}" target="_blank">Presentación de documentos</a></li> --}}
                                        <li class="divider"></li>
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.contract']) }}" target="_blank">Contrato</a></li>
                                        @break
                                    @case(5)
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract']) }}" target="_blank">Contrato</a></li>
                                        <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.memorandum-desigancion']) }}" target="_blank">Memorandum</a></li>
                                        @break
                                    @default
                                        
                                @endswitch
                            </ul>
                        </div>
                        <a href="#" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>

                        {{-- Si está firmado y tiene permiso de cambiar el estado --}}
                        @if ($item->status == 'firmado' && auth()->user()->hasPermission('downgrade_contracts'))
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                                    Anular <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                    <li><a href="#" onclick="downgradeContract({{ $item->id }}, 'elaborado')" data-toggle="modal" data-target="#downgrade-modal">Quitar firma</a></li>
                                    <li>
                                        <a href="#" onclick="deleteItem('{{ route('contracts.destroy', ['contract' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal-alt" title="Anular">
                                            Anular
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @elseif($item->status != 'firmado' && auth()->user()->hasPermission('delete_contracts'))
                            <a href="{{ route('contracts.edit', ['contract' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                            <a href="#" onclick="deleteItem('{{ route('contracts.destroy', ['contract' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal-alt" title="Anular" class="btn btn-sm btn-danger delete">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                            </a>
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

{{-- change status modal --}}
<form action="{{ route('contracts.status') }}" id="form-status" method="POST">
    {{ csrf_field() }}
    <div class="modal modal-primary fade" tabindex="-1" id="status-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-check"></i> Desea promover el contrato a la siguiente instancia?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id">
                        <input type="hidden" name="status">
                        <label for="observations">Observaciones</label>
                        <textarea name="observations" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-dark" value="Aceptar">
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal modal-danger fade" tabindex="-1" id="downgrade-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar la firma del contrato?</h4>
            </div>
            <div class="modal-footer">
                <form action="{{ route('contracts.status') }}" id="downgrade-form" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id">
                    <input type="hidden" name="status">
                    <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-danger fade" tabindex="-1" id="delete-modal-alt" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
            </div>
            <div class="modal-footer">
                <form action="#" id="delete_form_alt" method="POST">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
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

        $('#form-status').submit(function(e){
            $('#status-modal').modal('hide');
            e.preventDefault();
            $('#div-results').loading({message: 'Cargando...'});
            $.post($(this).attr('action'), $(this).serialize(), function(res){
                if(res.message){
                    list(page);
                }else{
                    toastr.error(res.error);
                    $('#div-results').loading('toggle');
                }
            });
        });

        $('#downgrade-form').submit(function(e){
            $('#downgrade-modal').modal('hide');
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

        $('#delete_form_alt').submit(function(e){
            $('#delete-modal-alt').modal('hide');
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
    });
</script>