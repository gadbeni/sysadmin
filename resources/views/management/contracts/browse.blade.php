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
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Tipo</th>
                                        <th>Persona</th>
                                        <th>Cargo</th>
                                        <th>Dirección administrativa</th>
                                        <th>Detalles</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($contracts as $item)
                                    <tr>
                                        <td>{{ $item->code }}/{{ date('Y', strtotime($item->start)) }}</td>
                                        <td>{{ $item->type->name }}</td>
                                        <td>{{ $item->person->first_name }} {{ $item->person->last_name }}</td>
                                        <td>{{ $item->cargo->Descripcion }}</td>
                                        <td>{{ $item->direccion_administrativa->NOMBRE }}</td>
                                        <td>
                                            <ul>
                                                <li><b>Sueldo: </b> <small>Bs.</small> {{ $item->cargo->nivel->Sueldo }}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            @switch($item->status)
                                                @case(1)
                                                    <label class="label label-success">Activo</label>
                                                    @break
                                                @case(2)
                                                    <label class="label label-danger">Finalizado</label>
                                                    @break
                                                @default
                                                    
                                            @endswitch
                                        </td>
                                        <td class="no-sort no-click bread-actions text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                  Imprimir <span class="caret"></span>
                                                </button>
                                              
                                                <ul class="dropdown-menu" role="menu">
                                                    @switch($item->procedure_type_id)
                                                        @case(1)
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'autorization']) }}" target="_blank">Autorización</a></li>
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'invitation']) }}" target="_blank">Invitación</a></li>
                                                            {{-- <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'answer']) }}" target="_blank">Respuesta</a></li> --}}
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'declaration']) }}" target="_blank">Declaración</a></li>
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'memorandum']) }}" target="_blank">Memorandum</a></li>
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'report']) }}" target="_blank">Informe</a></li>
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'adjudication']) }}" target="_blank">Nota de adjudicación</a></li>
                                                            {{-- <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'presentation']) }}" target="_blank">Presentación de documentos</a></li> --}}
                                                            <li class="divider"></li>
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'contract-consultor']) }}" target="_blank">Contrato</a></li>
                                                            @break
                                                        @case(4)
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'contract-eventual']) }}" target="_blank">Contrato</a></li>
                                                            @break
                                                        @default
                                                            
                                                    @endswitch
                                                </ul>
                                            </div>
                                            <a href="#" title="Ver" class="btn btn-sm btn-warning view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                            </a>
                                            <a href="{{ route('contracts.edit', ['contract' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                            </a>
                                            <a href="javascript:;" title="Borrar" class="btn btn-sm btn-danger delete">
                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                            </a>
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
                language
            });
        });

        function deleteItem(id){
            let url = '{{ url("admin/ventas") }}/'+id;
            $('#delete_form').attr('action', url);
        }

    </script>
@stop
