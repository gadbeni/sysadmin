@extends('voyager::master')

@section('page_title', 'Ver Ingresos')


    @section('page_header')
        <div class="col-md-6 col-xs-6" style="margin-top: 20px;">
            <a href="{{ route('outbox.index') }}" class="btn btn-default"><i class="voyager-angle-left"></i> Volver</a>
        </div>
        <div class="col-md-6 col-xs-6 text-right" style="margin-top: 20px;">
            {{-- @if($data->derivaciones->count() > 0) --}}
                <div class="dropdown">
                    <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">Imprimir
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right">
                      <li>
                        <a href="{{ route('outbox.print', ['outbox' => $data->id]) }}" target="_blank">
                            <span class="glyphicon glyphicon-print"></span>&nbsp;
                                Comprobante
                        </a>
                      </li>
                      <li>
                        <a href="{{ asset('file/Hojaruta.pdf') }}" target="_blank">
                            <span class="glyphicon glyphicon-print"></span>&nbsp;
                                Hoja de Ruta
                        </a>
                      </li>
                    </ul>
                    {{-- @php
                        dd($data->inbox->whereNull('deleted_at'));
                    @endphp --}}
                    @if ($data->inbox->whereNull('deleted_at')->count() == 0)
                        @if (count($nci)>0)
                            <button data-toggle="modal" data-target="#modal-derivar" onclick="derivacionItem({{ $data->id }}, {{ $data->people_id_para }})" title="Derivar" class="btn btn-sm btn-dark view" style="border: 0px">
                                <i class="voyager-forward"></i> <span class="hidden-xs hidden-sm">Derivar</span>
                            </button>
                        @endif
                    @endif
                </div>
        
                
            <h3 class="text-muted" style="padding-left: 10px">{{ $data->referencia }}</h3>
        </div>
    @stop

    @section('content')
        <div class="page-content read container-fluid div-phone">
            <div class="row">
                <div class="col-md-12">
                    
                    @if (count($nci)==0)     
                        <form action="{{route('outbox-file-nci.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="alert" style="background-color: #F5C02A;">
                                <strong>Advertencia:</strong>
                                <p>Carge el Comprobante o documento adjuntado al tramite</p>
                                <input type="hidden" name="id" value="{{$data->id}}" class="form-control">
                                <input type="file" name="archivos[]" multiple class="form-control" accept="image/jpeg,image/jpg,image/png,application/pdf" required>
                                <button type="submit" class="btn btn-success">Subir Archivos</button>

                            </div>

                        </form>
                    @endif


                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Hoja de Ruta</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ $data->tipo.'-'.$data->gestion.'-'.$data->id }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Fecha de Ingreso</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ date('d/m/Y H:i:s', strtotime($data->created_at)) }} <small>{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</small></p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Número de Cite</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ $data->cite ?? '' }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Número de hojas</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ $data->nro_hojas ?? 'No definida' }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Origen</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    @if ($data->tipo == 'E')
                                    <p>{{ $data->entity->nombre ?? 'Sin Origen' }}</p>
                                    @else
                                        No definido
                                    @endif
                                    
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Remitente</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ $data->remitente ? strtoupper($data->remitente) : '' }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            @if ($data->tipo == 'I')
                                <div class="col-md-12">
                                    <div class="panel-heading" style="border-bottom:0;">
                                        <h3 class="panel-title">Destino</h3>
                                    </div>
                                    <div class="panel-body" style="padding-top:0;">
                                        <p>
                                            {{-- {{ $data->person ? $data->person->first_name.' '.$data->person->last_name : '' }} --}}
                                            {{$destino->nombre}}
                                        </p>
                                    </div>
                                    <hr style="margin:0;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h3 class="panel-title">Archivos</h3>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <a href="#" data-toggle="modal" data-target="#modal-upload" class="btn btn-success" style="margin: 15px;">
                                                <span class="voyager-plus"></span>&nbsp;
                                                Agregar nuevo
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>N&deg;</th>
                                                    <th>Título</th>
                                                    <th>Adjuntado por</th>
                                                    <th>Fecha de registro</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                @endphp
                                                @forelse ($data->archivos as $item)
                                                    <tr>
                                                        <td>{{ $cont }}</td>
                                                        <td>
                                                            @if ($item->nci)
                                                            {{-- <label class="label label-success"><i class="fa-solid fa-file"></i> Comprobante</label> <br> --}}
                                                            @endif
                                                            {{ $item->nombre_origen }}
                                                        </td>
                                                        <td>{{ $item->user->name ?? '' }}</td>
                                                        <td>{{ date('d/m/Y H:i:s', strtotime($item->created_at)) }} <br><small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small></td>
                                                        <td style="text-align: right">
                                                            <a href="{{ url('https://siscor.beni.gob.bo/storage/'.$item->ruta) }}" class="btn btn-sm btn-info" target="_blank"> <i class="voyager-eye"></i> Ver</a>
                                                            @if (!$item->nci)
                                                                <button type="button" data-toggle="modal" data-target="#delete-file-modal" data-id="{{ $item->id }}" class="btn btn-danger btn-sm btn-delete-file"><span class="voyager-trash"></span></button>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    @php
                                                        $cont++;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="6"><h5 class="text-center">No hay archivos guardados</h5></td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                    </div>

                    @if($data->tipo == 'I')
                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h3 class="panel-title">Vias</h3>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            @if ($data->inbox->whereNull('deleted_at')->count() == 0)
                                                <button 
                                                    type="button" 
                                                    data-toggle="modal" 
                                                    data-target="#modal-derivar-via" 
                                                    title="Nuevo" class="btn btn-success"
                                                    style="margin: 15px;">
                                                    <i class="voyager-list-add"></i> 
                                                    Nuevo
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID&deg;</th>
                                                    <th>Nombre</th>
                                                    <th>Cargo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                @endphp
                                                @forelse ($data->vias as $item)
                                                    <tr style="text-transform: uppercase;">
                                                        <td>{{ $cont }}</td>
                                                        <td>{{ $item->nombre }}</td>
                                                        <td>{{ $item->cargo }}</td>
                                                        <td style="text-align: right">
                                                            @if ($data->inbox->whereNull('deleted_at')->count() == 0)
                                                                <button type="button" 
                                                                data-toggle="modal" 
                                                                data-target="#delete-via-modal" 
                                                                data-id="{{ $item->id }}" 
                                                                data-entrada_id="{{ $data->id }}"
                                                                class="btn btn-danger btn-sm btn-delete-via">
                                                                    <span class="voyager-trash"></span>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $cont++;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="6"><h5 class="text-center">No hay archivos guardados</h5></td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Historial de derivaciones</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <div class="table-responsive">
                                        {{-- para que el dueño del tramite pueda eliminar todas las derivacion --}}
                                        @php
                                            $ok = \App\Models\TcInbox::where('parent_id', $data->id)->where('entrada_id', $data->id)->where('via', 0)
                                                    ->where('deleted_at', null)
                                                    ->where('derivation', 0)
                                                    ->where('ok', 'NO')->first();
                                            // dd($ok);
                                        @endphp

                                        @if ($ok)
                                            @if ($ok->visto == null || auth()->user()->hasRole('admin'))
                                                <button type="button" data-toggle="modal" data-target="#modal_derivacionAnular" class="btn btn-danger btn-sm btn-anular"><span class="voyager-trash"></span> Eliminar Derivación</button>                                                
                                            @endif                                            
                                        @endif


                                        <table class="table table-bordered-table-hover">
                                            <thead>
                                                <tr>
                                                    <th>N&deg;</th>
                                                    <th>Dirección</th>
                                                    <th>Unidad</th>
                                                    <th>Funcionario</th>
                                                    <th>Observaciones</th>
                                                    <th>Fecha de derivación</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $cont = 1;
                                                @endphp
                                                @forelse ($data->inbox as $item)
                                                    <tr  @if ($item->via) style="background-color: rgb(224,223,223)" @endif @if ($item->rechazo) style="background-color: rgba(192,57,43,0.3)" @endif>
                                                        <td>{{ $cont }}</td>
                                                        <td>{{ $item->funcionario_direccion_para }}</td>
                                                        <td>{{ $item->funcionario_unidad_para }}</td>
                                                        <td>{{ $item->funcionario_nombre_para }} <br> <small>{{ $item->funcionario_cargo_para }}</small> </td>
                                                        <td>{{ $item->observacion }}</td>
                                                        <td>{{ date('d/m/Y H:i:s', strtotime($item->created_at)) }} <br> <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small><br>
                                                            @if ($item->visto)
                                                                <i class="fa-solid fa-eye" style="color: rgb(9,132,41)"></i>
                                                            @else
                                                                <i class="fa-solid fa-eye-slash"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $ok = \App\Models\TcInbox::where('parent_id', $item->id)->get();
                                                            @endphp
                                                            @if(0 == count($ok) && $item->via == 0 && auth()->user()->hasRole('admin') && $item->entrada_id != $item->parent_id)
                                                                <button type="button" data-toggle="modal" data-target="#anular_modal" data-id="{{ $item->id }}" class="btn btn-danger btn-sm btn-anular"><span class="voyager-trash"></span></button>
                                                            @endif
                                                        </td> 
                                                    </tr>
                                                    @php
                                                        $cont++;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="7"><h5 class="text-center">No se han realizado derivaciones</h5></td>
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
            </div>
        </div>

        {{-- anulación modal --}}
        <div class="modal modal-danger fade" tabindex="-1" id="anular_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular la siguiente derivación?</h4>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                        <form id="anulacion_form" action="{{ route('delete.outbox') }}" method="POST">
                            @csrf
                            <input type="hidden" name="entrada_id" value="{{ $data->id }}">
                            <input type="hidden" name="id">
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, anular">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal modal-danger fade" tabindex="-1" id="modal_derivacionAnular" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular la siguiente derivación?</h4>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                        <form action="{{ route('delete.outboxs') }}" method="POST">
                            @csrf
                            <input type="hidden" name="entrada_id" value="{{ $data->id }}">

                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, anular">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- delete file modal --}}
        <div class="modal modal-danger fade" tabindex="-1" id="delete-file-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el archivo?</h4>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                        <form id="delete_file_form" action="{{ route('delete.outbox.file') }}" method="POST">
                            @csrf
                            <input type="hidden" name="entrada_id" value="{{ $data->id }}">
                            <input type="hidden" name="id">
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- para derivar la correspondencia --}}
        @include('correspondencia.partials.modal-derivarOutbox')

        {{-- delete via modal --}}
        @include('correspondencia.partials.modal-dropzone', ['title' => 'Agregar archivo', 'id' => $data->id, 'action' => url('admin/outbox/store/file')])

        {{-- Personas modal --}}
        @include('correspondencia.partials.modal-agregar-vias', ['id' => $data->id])
        @include('correspondencia.partials.modal-delete-via')


      
    @stop

    @section('css')
    <style>
        .select2-container {
            width: 100% !important;
        }
        /* CSS to style Treeview menu  */
        ol.tree {
                    padding: 0 0 0 30px;
                    /* width: 500px; */
            }
            .li { 
                    position: relative; 
                    margin-left: -15px;
                    list-style: none;
            }      
            .li input {
                    position: absolute;
                    left: 0;
                    margin-left: 0;
                    opacity: 0;
                    z-index: 2;
                    cursor: pointer;
                    height: 1em;
                    width: 1em;
                    top: 0;
            }
            .li input + ol {
                    background: url({{asset("/images/treeview/toggle-small-expand.png")}}) 40px 0 no-repeat;
                    margin: -1.600em 0px 8px -44px; 
                    height: 1em;
            }
            .li input + ol > .li { 
                    display: none; 
                    margin-left: -14px !important; 
                    padding-left: 1px; 
            }
            .li label {
                    background: url({{asset("/images/treeview/default.png")}}) 15px 1px no-repeat;
                    cursor: pointer;
                    display: block;
                    padding-left: 37px;
            }
            .li input:checked + ol {
                    background: url({{asset("images/treeview/toggle-small.png")}}) 40px 5px no-repeat;
                    margin: -1.96em 0 0 -44px; 
                    padding: 1.563em 0 0 80px;
                    height: auto;
            }
            .li input:checked + ol > .li { 
                    display: block; 
                    margin: 8px 0px 0px 0.125em;
            }
            .li input:checked + ol > .li:last-child { 
                    margin: 8px 0 0.063em;
            }
    </style>
    @endsection

    @section('javascript')
        <script>
            var destinatario_id = 0;
            var intern_externo = 1;
            $(document).ready(function () {

                $('.btn-anular').click(function(){
                    let id = $(this).data('id');
                    $('#anulacion_form input[name="id"]').val(id);
                });
                $('.btn-delete-file').click(function(){
                    let id = $(this).data('id');
                    $('#delete_file_form input[name="id"]').val(id);
                });
                $('.btn-delete-via').click(function(){
                    let id = $(this).data('id');
                    let entrada_id = $(this).data('entrada_id');
                    $('#delete_via_form input[name="id"]').val(id);
                    $('#delete_via_form input[name="entrada_id"]').val(entrada_id);
                });
                $('.btn-showinfo').click(function(){
                    let id = $(this).data('id');
                    let unidad_para = $(this).data('unidad_para');
                    let direccion_para = $(this).data('direccion_para');
                    let observacion = $(this).data('observacion');
                    $('#info_form input[name="id"]').val(id);
                    $('#info_form input[name="direccion_para"]').val(direccion_para);
                    $('#info_form input[name="unidad_para"]').val(unidad_para);
                    $('#info_form textarea[name="observacion"]').val(observacion);
                });
            });

            function derivacionItem(id,destinoid=0){
                $('#form-derivacion input[name="id"]').val(id);
                destinatario_id = destinoid;
                // alert(destinatario_id);
            }
        </script>
    @stop

