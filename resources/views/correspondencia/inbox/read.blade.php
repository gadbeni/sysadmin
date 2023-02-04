@extends('voyager::master')

@section('page_title', 'Ver Detalle de Bandeja')


    @section('page_header')
        <div class="col-md-6" style="margin-top: 20px;">
            <a href="{{ route('inbox.index') }}" class="btn btn-default"><i class="voyager-angle-left"></i> Volver</a>
        </div>
        <div class="col-md-6 text-right" style="margin-top: 20px;">
            <div class="btn-group" role="group" aria-label="...">
                @php                                                        
                  
                @endphp
                @if ($data->estado_id != 4 && $derivacion->ok != 'ARCHIVADO' && $derivacion->ok != 'SI' && $derivacion->ok != 'RECHAZADO')
                    <button type="button" data-toggle="modal" data-target="#modal-archivar" title="Archivar" class="btn btn-default"><i class="voyager-categories"></i> Archivar</button>
                    @if($derivacion->via == 0)
                        <button type="button" data-toggle="modal" data-target="#modal-derivar" title="Derivar" class="btn btn-default"><i class="voyager-forward"></i> Derivar</button>
                        {{-- <button type="button" data-toggle="modal" data-target="#modal-rechazar" title="Rechazar" class="btn btn-default"><i class="voyager-warning"></i> Rechazar</button> --}}
                    @endif
                @endif
            </div>
        </div>
        <div class="col-md-12" style="margin-bottom: 20px">
            <h3 class="text-muted" style="padding-left: 10px">{{ $data->referencia }}</h3>
        </div>
    @stop

    @section('content')
        <div class="page-content read container-fluid div-phone">
            <div class="row">
                <div class="col-md-12">

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
                                    <p>{{ $data->cite ?? 'No definido' }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Número de hojas</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ $data->nro_hojas ?? 'No definido' }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Origen</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    @if ($data->tipo == 'E')
                                    <p>{{ $data->entity->nombre }}</p>
                                    @else
                                    <p>{{ $origen }}</p>
                                    @endif
                                </div>
                                <hr style="margin:0;">
                            </div>
                            <div class="col-md-6">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Remitente</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ $data->remitente }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
                            @if ($data->tipo == 'I')
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Destino</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    {{-- @if ($destino)
                                        <p>
                                            {{ $destino->nombre }} <br>
                                            <b style="font-weight: bold">{{ $destino->cargo }}</b> <br>
                                            <b style="font-weight: bold">{{ $destino->unidad }} {{ $destino->direccion ? ' - '.$destino->direccion : '' }}</b>
                                        </p>
                                    @endif --}}
                                </div>
                                <hr style="margin:0;">
                            </div>
                            @endif
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Referencia</h3>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <p>{{ $data->referencia }}</p>
                                </div>
                                <hr style="margin:0;">
                            </div>
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
                                            {{-- @if ($data->estado_id != 4)
                                            <a href="#" data-toggle="modal" data-target="#modal-upload" class="btn btn-success" style="margin: 15px;">
                                                <span class="voyager-plus"></span>&nbsp;
                                                Agregar nuevo
                                            </a>
                                            @endif --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body" style="padding-top:0;">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>N&deg;</th>
                                                <th>Título</th>
                                                <th>Adjuntado por</th>
                                                <th>Fecha de registro</th>
                                                <th>Archivo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $cont = 1;
                                            @endphp
                                            @forelse ($data->archivos as $item)
                                                <tr>
                                                    <td>{{ $cont }}</td>
                                                    <td>{{ $item->nombre_origen }}</td>
                                                    <td>{{ $item->user->name ?? 'RDE' }}</td>
                                                    <td>{{ date('d/m/Y H:i:s', strtotime($item->created_at)) }} <br><small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small></td>
                                                    <td><a href="{{ url('https://siscor.beni.gob.bo/storage/'.$item->ruta) }}" class="btn btn-sm btn-info" target="_blank"> <i class="voyager-eye"></i> Ver</a></td>
                                                </tr>
                                                @php
                                                    $cont++;
                                                @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="5"><h5 class="text-center">No hay archivos guardados</h5></td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <hr style="margin:0;">
                            </div>
                        </div>
                    </div>
                    @php
                        $dates = date("d-m-Y", strtotime($data->created_at));
                        // dd($data->created_at);
                    @endphp
              
                    {{-- @if ($data->created_at >= '2022-09-01' )
                        <div style="width:100%;" id="tree">
                        </div>
                    @endif --}}
                    
                    <div class="panel panel-bordered" style="padding-bottom:5px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-heading" style="border-bottom:0;">
                                    <h3 class="panel-title">Historial de derivaciones</h3>
                                </div>
                                @if ($data->inbox->count() > 0)
                                <div class="panel-body" style="padding-top:0;">
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
                                                {{-- <th>Fecha de recepción</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $cont = 1;
                                            @endphp
                                            @forelse ($data->inbox as $item)
                                                <tr @if ($item->rechazo) style="background-color: rgba(192,57,43,0.3)" @endif @if ($item->via) style="background-color: rgb(224,223,223)" @endif>
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
                                                    @php
                                                     
                                                        $people = \App\Models\TcPersona::where('ci', Auth::user()->ci)->where('deleted_at', null)->first();
                                                        // dd($people);

                                                    @endphp
                                                    <td>
                                                        @if ($item->derivation == 0 && $item->ok == 'NO' && $item->via == 0 && $item->visto == NULL && $item->people_id_de == $people->id)
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
                                @endif
                                 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Modal para eliminar las derivaciones --}}
        <div class="modal modal-danger fade" tabindex="-1" id="anular_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular la siguiente derivación?</h4>
                    </div>
                    <div class="modal-footer">
                        <p></p>
                        <form id="anulacion_form" action="{{ route('inbox-derivation.delete') }}" method="POST">
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
        
        {{-- Para archivar documento --}}
        <form action="{{ route('inbox.archivar', ['id' => $data->id]) }}" method="post">
            <div class="modal fade" tabindex="-1" id="modal-archivar" role="dialog">
                <div class="modal-dialog modal-success">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-categories"></i> Archivar correspondencia</h4>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <input type="hidden" name="derivacion_id" value="{{ $derivacion->id }}">
                        </div>
                        <div class="modal-footer text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Archivar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        @include('correspondencia.partials.modal-derivar', ['id' => $data->id, $der_id = $derivacion->id, 'redirect' => 'inbox.index'])


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


            #tree {
                width: 100%;
                height: 100%;
            }
        </style>
    @endsection
        
    @section('javascript')
        <script>
            var destinatario_id = 0;
            var intern_externo = 1;

            $(document).ready(function () {
                // $('#select-destinatario').select2({ dropdownParent: "#derivar-modal" });
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


                $('.btn-anular').click(function(){
                    let id = $(this).data('id');
                    $('#anulacion_form input[name="id"]').val(id);
                });
            });

        </script>
        

        
    @stop
    

