@extends('voyager::master')

@section('page_title', 'Viendo Contratos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-certificate"></i> Contratos
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            <a href="{{ route('contracts.create') }}" class="btn btn-success">
                                <i class="voyager-plus"></i> <span>Crear</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
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
                                    @forelse ($contracts as $item)
                                    {{-- {{ dd($item) }} --}}
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
                                                <button title="Promover a la siguiente instancia" data-toggle="modal" data-target="#status_modal" onclick="changeStatus({{ $item->id }}, '{{ $netx_status }}')" class="btn btn-sm btn-dark btn-status view">
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
                                            @if ($item->status != 'firmado')
                                                <a href="{{ route('contracts.edit', ['contract' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                </a>
                                                <a href="javascript:;" title="Borrar" class="btn btn-sm btn-danger delete">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr class="odd">
                                            <td valign="top" colspan="8" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- change status modal --}}
    <form action="{{ route('contracts.status') }}" id="delete_form" method="POST">
        {{ csrf_field() }}
        <div class="modal modal-primary fade" tabindex="-1" id="status_modal" role="dialog">
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
    
    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(() => {
            $('#dataTable').DataTable({
                language,
                order: [[0, 'desc']],
            });
        });

        function changeStatus(id, status) {
            $('#delete_form input[name="id"]').val(id)
            $('#delete_form input[name="status"]').val(status)
        };

        function deleteItem(id){
            let url = '{{ url("admin/ventas") }}/'+id;
            $('#delete_form').attr('action', url);
        }

    </script>
@stop
