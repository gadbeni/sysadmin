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
                    <th>Registrado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    @php
                        // Contrato posteriores al actual
                        $contracts = \App\Models\Contract::where('person_id', $item->person_id)->where('deleted_at', NULL)->where('id', '>', $item->id)->get();
                        $addendums = $item->addendums->where('deleted_at', NULL)->sortByDesc('id');
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->type->name }}</td>
                        <td>
                            {{ $item->person->last_name }} {{ $item->person->first_name }}<br>
                            <b>CI</b>: {{ $item->person->ci }}
                            {!! $item->person->phone ? '<br><b>Telf</b>: '.$item->person->phone : '' !!}
                        </td>
                        <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->nombre : 'No definida' }}</td>
                        <td>
                            <ul style="list-style: none; padding-left: 0px">
                                <li>
                                    <b>Cargo: </b>
                                    @if ($item->cargo)
                                        {{ $item->cargo->Descripcion }}
                                    @elseif ($item->job)
                                        {{ $item->job->name }}
                                    @else
                                        No definido
                                    @endif
                                </li>
                                <li>
                                    @php
                                        $salary = 0;
                                        if ($item->cargo){
                                            $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                                        }elseif ($item->job){
                                            $salary = $item->job->salary;
                                        }
                                    @endphp
                                    <b>Sueldo: </b> <small>Bs.</small> {{ number_format($salary, 2, ',', '.') }}
                                </li>
                                <li><b>Desde </b>{{ date('d/m/Y', strtotime($item->start)) }}
                                @if ($item->finish)
                                <b> hasta </b>{{ date('d/m/Y', strtotime($item->finish)) }}
                                @endif
                                <li>
                                    @if ($item->start && $item->finish)
                                        @php
                                            $contract_duration = contract_duration_calculate($item->start, $item->finish);
                                            $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);    
                                        @endphp
                                        <b>Duración: </b> {{ ($contract_duration->months * 30) + $contract_duration->days }} Días <br>
                                        <b>Total: </b> <small>Bs.</small> {{ number_format($total, 2, ',', '.') }}
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
                                            case 'concluido':
                                                $label = 'warning';
                                                break;
                                            default:
                                                $label = 'default';
                                                $netx_status = '';
                                                break;
                                        }
                                    @endphp
                                    <b>Estado</b>: <label class="label label-{{ $label }}">{{ ucfirst($item->status) }}</label>
                                    @if (count($addendums) > 0)
                                    @php
                                        if($addendums->first()->status == "firmado"){
                                            $class = 'success';
                                            $label = 'firmada';
                                        }elseif($addendums->first()->status == "elaborado"){
                                            $class = 'dark';
                                            $label = 'elaborada';
                                        }elseif($addendums->first()->status == "concluido"){
                                            $class = 'warning';
                                            $label = 'concluida';
                                        }else{
                                            $class = 'default';
                                            $label = 'desconocida';
                                        }
                                    @endphp
                                    <label class="label label-{{ $class }} label-addendum" title="Adenda {{ $label }}" data-item='@json($addendums->first())' data-toggle="modal" data-target="#addendum-show-modal" style="cursor: pointer"><i class="voyager-calendar"></i></label>
                                    @endif
                                </li>
                            </ul>
                        </td>
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
                                    {{-- Definir siguiente estado --}}
                                    @if (auth()->user()->hasPermission('upgrade_contracts') && $item->status != 'concluido' && $item->status != 'firmado' && ($item->cargo_id || $item->job_id))
                                    <li><a href="#" title="Promover a {{ $netx_status }}" data-toggle="modal" data-target="#status-modal" onclick="changeStatus({{ $item->id }}, '{{ $netx_status }}')">Promover</a></li>
                                    @endif
                                    {{-- Si está firmado --}}
                                    @if ($item->status == 'firmado' && auth()->user()->hasPermission('ratificate_contracts'))
                                    <li><a href="#" title="Ratificar" data-toggle="modal" data-target="#ratificate-modal" onclick="ratificateContract({{ $item->id }})">Ratificar</a></li>
                                    @endif
                                    @if ($item->status == 'firmado' && auth()->user()->role_id == 1)
                                    <li><a href="#" title="Finalizar" data-toggle="modal" data-target="#finish-modal" onclick="finishContract({{ $item->id }}, '{{ $item->finish }}')">Finalizar</a></li>
                                    @endif
                                    {{-- si está concluido y es permanente --}}
                                    @if ($item->status == 'concluido' && $item->procedure_type_id == 1 && auth()->user()->hasPermission('print_finish_contracts'))
                                    <li><a title="Imprimir memorándum de agradecimiento" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-finished']) }}" target="_blank">Imprimir memorándum</a></li>
                                    @endif

                                    @if (auth()->user()->hasPermission('add_addendum_contracts') && $item->status == 'concluido' && count($contracts) == 0 && $item->procedure_type_id == 2 && $addendums->where('status', 'elaborado')->count() == 0 && $addendums->where('status', 'firmado')->count() == 0)
                                    <li><a class="btn-addendum" title="Crear adenda" data-toggle="modal" data-target="#addendum-modal" data-item='@json($item)' href="#">Crear adenda</a></li>
                                    @endif

                                    @if (count($addendums) > 0)
                                        @if ($item->status == 'concluido' && $addendums->first()->status == 'elaborado' && $item->procedure_type_id == 2)
                                        <li><a class="btn-addendum-status" title="Firmar adenda" data-toggle="modal" data-target="#addendum-status-modal" data-id="{{ $addendums->first()->id }}" href="#">Firmar adenda</a></li>
                                        @endif
                                    @endif

                                    {{-- Restaurar contrato --}}
                                    @if (auth()->user()->hasPermission('restore_contracts') && $item->status == 'concluido' && $item->procedure_type_id == 1)
                                    <li><a href="#" title="Restaurar" data-toggle="modal" data-target="#status-modal" onclick="changeStatus({{ $item->id }}, 'restaurar')">Restaurar</a></li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Botón de impresión --}}
                            @if ($item->cargo_id || $item->job_id)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    Imprimir <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        @switch($item->procedure_type_id)
                                            @case(1)
                                            <li><a title="Designación" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum']) }}" target="_blank">Designación</a></li>
                                                @if ($item->ratifications->count() > 0 && auth()->user()->hasPermission('ratificate_contracts'))
                                                <li><a title="Ratificación" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-ratificacion']) }}" target="_blank">Ratificación</a></li>
                                                @endif
                                                <li><a title="Reasignación" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-reasigancion']) }}" target="_blank">Reasignación</a></li>
                                                @break
                                            @case(2)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.autorization']) }}" target="_blank">Autorización</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.invitation']) }}" target="_blank">Invitación</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.declaration']) }}" target="_blank">Declaración</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.memorandum']) }}" target="_blank">Memorandum</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.report']) }}" target="_blank">Informe</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.adjudication']) }}" target="_blank">Nota de adjudicación</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.contract']) }}" target="_blank">Contrato</a></li>
                                                
                                                {{-- Si hay una adenda firmada --}}
                                                @if (count($addendums) > 0)
                                                    @if ($addendums->first()->status == 'firmado')
                                                    <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.addendum']).(count($addendums) == 1 ? '?type=first' : '') }}" target="_blank">Adenda</a></li>
                                                    @endif
                                                @endif

                                                @break
                                            @case(5)
                                                @if ($item->direccion_administrativa->tipo->id == 3 || $item->direccion_administrativa->tipo->id == 4)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-alt']) }}" target="_blank">Contrato</a></li>
                                                @elseif ($item->direccion_administrativa_id == 32 || $item->direccion_administrativa_id == 36 || $item->direccion_administrativa_id == 57)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-health']) }}" target="_blank">Contrato</a></li>
                                                @elseif ($item->direccion_administrativa_id == 5)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-sedeges']) }}" target="_blank">Contrato</a></li>
                                                @else
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract']) }}" target="_blank">Contrato</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-inamovible']) }}" target="_blank">Contrato inamovible</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.memorandum-designacion']) }}" target="_blank">Memorandum</a></li>
                                                @endif

                                                @break
                                            @default
                                                
                                        @endswitch
                                    </ul>
                                </div>
                            @endif

                            <a href="{{ route('contracts.show', ['contract' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                            
                            @if ($item->status != 'concluido')
                                {{-- Se puede editar el contrato si no está firmado --}}
                                @if (($item->status != 'firmado' && auth()->user()->hasPermission('edit_contracts')) || auth()->user()->role_id == 1)
                                    <a href="{{ route('contracts.edit', ['contract' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                    </a>
                                @endif

                                {{-- Si está firmado y tiene permiso de cambiar el estado --}}
                                @if ($item->status == 'firmado' && auth()->user()->hasPermission('delete_contracts') && auth()->user()->hasPermission('downgrade_contracts'))
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
                                    <a href="#" onclick="deleteItem('{{ route('contracts.destroy', ['contract' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal-alt" title="Anular" class="btn btn-sm btn-danger delete">
                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                    </a>
                                @endif
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
<form action="{{ route('contracts.status') }}" id="form-status" class="form-submit" method="POST">
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

{{-- Finish modal --}}
<form action="{{ route('contracts.status') }}" id="form-finish" method="POST">
    {{ csrf_field() }}
    <div class="modal fade" tabindex="-1" id="finish-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-calendar"></i> Desea dar el contrato como concluido?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" name="status" value="concluido">
                    <div class="form-group">
                        <label for="">Fecha de conclusión</label>
                        <input type="date" name="finish" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="observations">Observaciones</label>
                        <textarea name="observations" class="form-control" rows="4" required></textarea>
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

{{-- Finish modal --}}
<form action="{{ route('contracts.ratificate') }}" id="form-ratificate" class="form-submit" method="POST">
    {{ csrf_field() }}
    <div class="modal fade" tabindex="-1" id="ratificate-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-calendar"></i> Desea ratificar el siguiente contrato?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label for="">Fecha de ratificación</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="observations">Observaciones</label>
                        <textarea name="observations" class="form-control" rows="4"></textarea>
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

{{-- Addendum create modal --}}
<form action="{{ route('contracts.addendum.store') }}" id="form-addendum" class="form-submit" method="POST">
    @csrf
    <input type="hidden" name="id">
    <div class="modal modal-primary fade" tabindex="-1" id="addendum-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-certificate"></i> Crear adenda</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="start">Inicio</label>
                            <input type="date" name="start" class="form-control" readonly required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="finish">Fin</label>
                            <input type="date" name="finish" class="form-control" required>
                        </div>
                        <div class="form-group col-md-12">
                            <textarea class="form-control richTextBox" name="details_payments">
                                <p><strong><em>MONTO. - </em></strong><em>El monto total del presente contrato modificatorio ser&aacute; por la suma de </em><strong><em>Bs.- </em></strong><strong><em>13.500,00</em></strong><strong><em>.-</em></strong><em> (</em><em>Trece Mil Quinientos 00</em><em>/100 Bolivianos), el pago de esta consultor&iacute;a ser&aacute; de la siguiente manera: En </em><em>cuatro</em><em> (</em><em>04</em><em>) cuotas mensuales, la primera cuota correspondiente a </em><em>12 d&iacute;as</em><em> del mes de </em><em>julio</em><em> por un monto de </em><strong><em>Bs. </em></strong><strong><em>1.800,00.</em></strong><strong><em>- </em></strong><em>(</em><em>Un Mil Ochocientos 00</em><em>/100 Bolivianos), la segunda </em><em>y tercer</em><em> cuota correspondiente a </em><em>los meses de agosto y septiembre</em><em> por un monto de </em><strong><em>Bs. </em></strong><strong><em>4.500,00</em></strong><em>.-(Cuatro Mil Quinientos 00</em><em>/100 Bolivianos), la </em><em>cuarta</em><em> y &uacute;ltima cuota correspondiente a </em><em>18 d&iacute;as</em><em> del mes de </em><em>octubre</em><em> por un monto de </em><strong><em>Bs. </em></strong><strong><em>2.700,00</em></strong><em>.- (Dos Mil Setecientos 00</em><em>/100 Bolivianos). La cancelaci&oacute;n del servicio prestado se realizar&aacute; previa presentaci&oacute;n y aprobaci&oacute;n de informe de actividades de acuerdo a T&eacute;rminos de Referencia, aprobado por el Secretario Departamental de </em><em>Desarrollo Productivo y Econom&iacute;a Plural</em><em> del GAD-BENI.</em></p>
                            </textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="signature_id">Firma autorizada</label>
                            <select name="signature_id" class="form-control select2">
                                <option value="">Secretario(a) de Administración y Finanzas</option>
                                @foreach (App\Models\Signature::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                <option @if($item->direccion_administrativa_id == Auth::user()->direccion_administrativa_id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12 text-right" style="margin-bottom: 0px">
                            <div class="checkbox">
                                <label><input type="checkbox" required> Aceptar y guardar</label>
                            </div>
                        </div>
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

{{-- Addendum show modal --}}
<div class="modal modal-primary fade" tabindex="-1" id="addendum-show-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-certificate"></i> Viendo adenda</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>Duración</b></p>
                        <p id="label-date-addendum"></p>
                        <p><b>Forma de pago</b></p>
                        <p id="label-details_payments-addendum"></p>
                        <p id="label-status-addendum"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Addendum status modal --}}
<div class="modal fade" tabindex="-1" id="addendum-status-modal" role="dialog">
    <div class="modal-dialog modal-success">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-certificate"></i> Desea firmar la adenda?</h4>
            </div>
            <div class="modal-footer text-right">
                <form action="{{ route('contracts.addendum.status') }}" id="addendum-status-form" class="form-submit" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-success" value="Sí, firmar">
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete signature modal --}}
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

{{-- Delete modal --}}
<form action="#" id="delete_form_alt" method="POST">
    <div class="modal modal-danger fade" tabindex="-1" id="delete-modal-alt" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular el siguiente contrato?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="observations">Observaciones</label>
                        <textarea name="observations" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</form>

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

        $.extend({selector: '.richTextBox'}, {})
        tinymce.init(window.voyagerTinyMCE.getConfig({selector: '.richTextBox'}));

        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });

        $('.form-submit').submit(function(e){
            $('#status-modal').modal('hide');
            $('#addendum-modal').modal('hide');
            $('#addendum-status-modal').modal('hide');
            $('#ratificate-modal').modal('hide');
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

        // Crear adenda
        $('.btn-addendum').click(function(){
            let item = $(this).data('item');
            let date = moment(item.finish, "YYYY-MM-DD").add(1, 'days');
            $('#form-addendum input[name="id"]').val(item.id);
            $('#form-addendum input[name="start"]').val(date.format("YYYY-MM-DD"));
            $('#form-addendum input[name="finish"]').attr('min', date.format("YYYY-MM-DD"));
        });

        // Mostrar adenda
        $('.label-addendum').click(function(){
            let item = $(this).data('item');
            $('#label-date-addendum').html(`Inicio desde el ${moment(item.start).format('DD [de] MMMM [de] YYYY')} hasta el ${moment(item.finish).format('DD [de] MMMM [de] YYYY')}.`);
            $('#label-details_payments-addendum').html(`${item.details_payments}`);
            let style, label;
            if (item.status == 'elaborado') {
                style = 'dark';
                label= 'Elaborada';
            } else if (item.status == 'firmado') {
                style = 'success';
                label= 'Firmada';
            }else if (item.status == 'concluido') {
                style = 'warning';
                label= 'Concluida';
            }else{
                style = 'default';
                label= 'Desconocida';
            }
            $('#label-status-addendum').html(`<b>Estado de la adenda</b> <label class="label label-${style}">${label}</label>`);
        });

        $('.btn-addendum-status').click(function(){
            let id = $(this).data('id');
            $('#addendum-status-form input[name="id"]').val(id);
        });

        $('#form-finish').submit(function(e){
            $('#finish-modal').modal('hide');
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