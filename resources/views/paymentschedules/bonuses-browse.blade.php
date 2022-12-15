@extends('voyager::master')

@section('page_title', 'Viendo Aguinaldos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Aguinaldos
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_bonuses'))
                            <a href="{{ route('bonuses.create') }}" class="btn btn-success">
                                <i class="voyager-plus"></i> <span>Crear</span>
                            </a>
                            @endif
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
                                        <th>Dirección administrativa</th>
                                        <th>N&deg; de personas</th>
                                        <th>Gestión</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @foreach ($bonus as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->direccion->nombre }}</td>
                                            <td>{{ $item->details->count() }}</td>
                                            <td>{{ $item->year }}</td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                                        <span class="hidden-xs hidden-sm">Más <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                                        @if ($item->status == 1)
                                                        <li><a href="#" class="btn-edit" title="Promover planilla" onclick="update('{{ route('bonuses.update', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#update-modal">Promover</a></li>    
                                                        @elseif($item->status == 2)
                                                        <li><a title="Imprimir boleta" href="{{ route('bonuses.recipe', ['id' => $item->id]) }}" target="_blank">Imprimir Boletas</a></li>
                                                        @else
                                                        <li>Sin opciones</li>
                                                        @endif
                                                    </ul>
                                                </div>

                                                @if (auth()->user()->hasPermission('read_bonuses'))
                                                <a href="{{ route('bonuses.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                </a>
                                                @endif
                                                @if ($item->status == 1 && auth()->user()->hasPermission('delete_bonuses'))
                                                <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('bonuses.destroy', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}
    <form action="#" id="update_form" method="POST">
        @csrf
        @method('PUT')
        <div class="modal modal-info fade" tabindex="-1" id="update-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-forward"></i> Desea promover la siguiente planilla?</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Si promueve la planilla ya no podrá editarla o anularla.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-info" value="Sí, Promover">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        .dropdown-toggle{
            border: 0px
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({language});
        });
        function update(url){
            $('#update_form').attr('action', url);
        }
    </script>
@stop
