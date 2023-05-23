@extends('voyager::master')

@section('page_title', 'Ver Persona')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-people"></i> Viendo Persona
        <a href="{{ route('voyager.people.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Nombre(s)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->first_name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Apellidos</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->last_name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">CI</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->ci }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de nacimiento</h3>
                            </div>
                            @php
                                $now = \Carbon\Carbon::now();
                                $birthday = new \Carbon\Carbon($person->birthday);
                                $age = $birthday->diffInYears($now);
                            @endphp
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d/m/Y', strtotime($person->birthday)) }} - {{ $age }} años</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Profesión</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->profession }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Telefono</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->phone ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->address }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Email</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->email ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Género</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->gender }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado civil</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->civil_status == 1 ? 'Soltero(a)' : 'Casado(a)' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->afp_type->name }} @if($person->afp_status == 0) <label class="label label-warning">No aporta</label>@endif @if($person->retired == 1) <label class="label label-danger">Jubilado</label>@endif</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">NUA/CUA</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->nua_cua }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">N&deg; de cuenta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->number_account }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="panel-title">Documentación</h3>
                                    </div>
                                    <div class="col-md-4 text-right" style="padding-top: 20px">
                                        @if (auth()->user()->hasPermission('add_file_people'))
                                        <a href="#" class="btn btn-success btn-add-file" data-url="{{ route('people.file.store', ['id' => $person->id]) }}" data-toggle="modal" data-target="#modal-add-file" ><i class="voyager-plus"></i> <span>Agregar</span></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Título</th>
                                        <th>Observaciones</th>
                                        <th>Registrado</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($person->files as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->observations }}</td>
                                            <td title="Actualizado {{ date('d/m/Y H:i', strtotime($item->updated_at)) }}">
                                                {{ $item->user ? $item->user->name : '' }} <br>
                                                {{ date('d/m/Y H:i', strtotime($item->created_at)) }} <br>
                                                <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if (auth()->user()->hasPermission('browse_file_people'))
                                                <a href="{{ url('storage/'.$item->file) }}" class="btn btn-warning" target="_blank"><i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span></a>
                                                @endif
                                                @if (auth()->user()->hasPermission('edit_file_people'))
                                                <button type="button" data-item='@json($item)' data-url="{{ route('people.file.update', ['id' => $person->id]) }}" data-toggle="modal" data-target="#modal-edit-file" title="Editar" class="btn btn-sm btn-info btn-edit-file">
                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                </button>
                                                @endif
                                                @if (auth()->user()->hasPermission('delete_file_people'))
                                                <button type="button" onclick="deleteItem('{{ route('people.file.delete', ['people' => $person->id, 'id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger delete">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="5">No hay datos disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Historial de inamovilidades</h3>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Tipo</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Observaciones</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($person->irremovabilities as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->type->name }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                                            <td>{{ $item->finish ? date('d/m/Y', strtotime($item->finish)) : "No definido" }}</td>
                                            <td>{{ $item->observations }}</td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if (auth()->user()->hasPermission('delete_irremovability_people'))
                                                <button type="button" onclick="deleteItem('{{ route('people.irremovability.delete', ['people' => $person->id, 'id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6">No hay datos disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Historial de rotaciones</h3>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Fecha</th>
                                        <th>Solicitante</th>
                                        <th>Destino</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 0;
                                    @endphp
                                    @foreach ($person->contracts as $contract)
                                        @foreach ($contract->rotations as $rotation)
                                            @php
                                                $cont++;
                                            @endphp
                                            <tr>
                                                <td>{{ $cont }}</td>
                                                <td>{{ date('d/m/Y', strtotime($rotation->date)) }}</td>
                                                <td>{{ $rotation->destiny->first_name }} {{ $rotation->destiny->last_name }}</td>
                                                <td>{{ $rotation->destiny_dependency }}</td>
                                                <td class="no-sort no-click bread-actions text-right">
                                                    <a href="{{ url('admin/people/rotation/'.$rotation->id) }}" class="btn btn-default btn-sm" target="_blank"><i class="glyphicon glyphicon-print"></i> Imprimir</a>
                                                    @if (auth()->user()->hasPermission('delete_rotation_people'))
                                                    <button type="button" onclick="deleteItem('{{ route('people.rotation.delete', ['people' => $person->id, 'id' => $rotation->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    @if ($cont == 0)
                                        <tr>
                                            <td colspan="5">No hay datos disponible</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal add file --}}
    @include('management.people.partials.modal-add-file')

    {{-- Modal edit file --}}
    <form class="form-submit" id="edit-file-form" action="#" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id">
        <div class="modal modal-primary fade" tabindex="-1" id="modal-edit-file" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-file-text"></i> Editar documentación</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="title" class="form-control" required >
                        </div>
                        <div class="form-group">
                            <label>Archivo</label>
                            <input type="file" name="file" class="form-control" accept="application/pdf">
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <textarea name="observations" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark btn-submit" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.btn-edit-file').click(function(e){
                e.preventDefault();
                let url = $(this).data('url');
                let item = $(this).data('item');

                $('#edit-file-form').attr('action', url);
                $('#edit-file-form input[name="id"]').val(item.id);
                $('#edit-file-form input[name="title"]').val(item.title);
                $('#edit-file-form textarea[name="observations"]').val(item.observations);
            });

            $('.form-submit').submit(function(){
                $('.btn-submit').val('Guardando...');
                $('.btn-submit').attr('disabled', 'disabled');
            });

            $('.btn-add-file').click(function(e){
                e.preventDefault();
                let url = $(this).data('url');
                $('#add-file-form').attr('action', url);
            });
        });
    </script>
@stop
