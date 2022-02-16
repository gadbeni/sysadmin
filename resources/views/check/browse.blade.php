@extends('voyager::master')

@section('page_title', 'Viendo Cheques')

@if(auth()->user()->hasPermission('browse_planillas_adicionales'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-8" style="padding: 0px">
                                <h1 class="page-title">
                                <i class="voyager-certificate"></i> Cheques
                                </h1>
                                {{-- <div class="alert alert-info">
                                    <strong>Información:</strong>
                                    <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                                </div> --}}
                            </div>
                            @if(auth()->user()->hasPermission('add_planillas_adicionales'))
                            @endif
                                <div class="col-md-4 text-right" style="margin-top: 30px">
                                    <a type="button" data-toggle="modal" data-target="#modalRegistrar" class="btn btn-success">
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
                                            <th>Id&deg;</th>
                                            <th>Tipo.</th>
                                            <th>Nro Cheque.</th>
                                            <th>Resumen.</th>
                                            <th>Nro Mem.</th>
                                            <th>Nro Prev</th>
                                            <th>Nro Dev.</th>
                                            <th>Fecha Cheque</th>
                                            <th>Monto </th>
                                            <th>Deposito</th>
                                            <th>Observacion</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($check as $item)
                                        @php
                                            
                                        @endphp
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ json_decode($item->resumen)->nrocheque }}</td>
                                            <td>{{ json_decode($item->resumen)->resumen }}</td>
                                            <td>{{ json_decode($item->resumen)->nromemo }}</td>
                                            <td>{{ json_decode($item->resumen)->nroprev }}</td>
                                            <td>{{ json_decode($item->resumen)->nrodev }}</td>
                                            <td>{{ json_decode($item->resumen)->fechacheque }}</td>
                                            <td>{{ json_decode($item->resumen)->monto }}</td>
                                            <td>{{ json_decode($item->resumen)->deposito }}</td>
                                            <td>{{ json_decode($item->resumen)->observacion }}</td>
                                            <td>
                                                    @if ($item->status == 1)
                                                        <label class="label label-danger">Pendiente</label>
                                                    @endif

                                                    @if ($item->status == 2)
                                                        <label class="label label-success">Entregado</label>
                                                    @endif
                                                </td>

                                            <td>
                                                @if ($item->status != 2)
                                                    <a type="button" data-toggle="modal" data-target="#modal_entregar" data-id="{{ $item->id}}"  class="btn btn-success"><i class="voyager-dollar"></i> <span class="hidden-xs hidden-sm">Entregar</span></a>
                                                    <a type="button" data-toggle="modal" data-target="#delete_editar" data-id="{{ $item->id}}" data-items="{{$item->resumen}}"  class="btn btn-primary"><i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span></a>
                                                    <a type="button" data-toggle="modal" data-target="#delete_modal" data-id="{{ $item->id}}" class="btn btn-danger"><i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span></a>
                                             
                                                @endif  
                                            </td>
                                        </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="modal_entregar" role="dialog">
            <div class="modal-dialog modal-success">
                <div class="modal-content">
                    {!! Form::open(['route' => 'checks.entregar', 'method' => 'POST']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Entregar Cheque</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">

                        <div class="text-center" style="text-transform:uppercase">
                            <i class="voyager-dollar" style="color: green; font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea entregar el cheque....!</b></p>
                        </div>
                    </div>                
                    <div class="modal-footer">
                        
                            <input type="submit" class="btn btn-success pull-right" value="Sí, Entregar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
                </div>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal fade" role="dialog" id="modalRegistrar">
            <div class="modal-dialog">
                <div class="modal-content">                
                    <!-- Modal Header -->
                    <div class="modal-header btn-success">
                        <h4 class="modal-title"><i class="voyager-plus"></i>Registrar Cheque</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    {!! Form::open(['route' => 'checks.store','class' => 'was-validated'])!!}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Tipo de Cheque:</b></span>
                                </div>
                                <select name="checkcategoria_id" id="" class="form-control select2" required>
                                    <option value="">Seleccione un tipo..</option>
                                    @foreach($tipos as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Nro Cheque:</b></span>
                                </div>
                                <input type="text" class="form-control" name="nrocheque"required>
                            </div>
                            <div class="col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><b>Resumen:</b></span>
                            </div>
                            <input type="text" class="form-control" name="resumen" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Nro memorándum:</b></span>
                                </div>
                                <input type="number" step="any" class="form-control" name="nromemo" required>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Nro Preventivo:</b></span>
                                </div>
                                <input type="text" class="form-control" name="nroprev"required>
                            </div>     
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Nro Devengado:</b></span>
                                </div>
                                <input type="number" step="any" class="form-control" name="nrodev" required>
                            </div>                   
                        </div>
                        <div class="row">                            
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Fecha Cheque:</b></span>
                                </div>
                                <input type="date" step="any" class="form-control" name="fechacheque"required>
                            </div> 
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Monto:</b></span>
                                </div>
                                <input type="number" step="any" class="form-control" name="monto"required>
                            </div>   
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Deposito:</b></span>
                                </div>
                                <input type="text" step="any" class="form-control" name="deposito"required>
                            </div>                
                        </div>
                        <div class="row">    
                                 
                            <div class="col-md-12">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Observacion:</b></span>
                                </div>
                                <textarea name="observacion" cols="77" rows="3" required></textarea>
                            </div>                
                        </div>
                        
                        
                        
                    </div>
                    
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-between">
                        <button type="button text-left" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Volver">Cancelar
                        </button>
                        <button type="submit" class="btn btn-success btn-sm" title="Registrar..">
                            Registrar
                        </button>
                    </div>
                    {!! Form::close()!!} 
                    
                </div>
            </div>
        </div>


        <div class="modal fade" role="dialog" id="delete_editar">
            <div class="modal-dialog modal-info">
                <div class="modal-content">
                
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="voyager-edit"></i>Editar Cheque</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    {!! Form::open(['route' => 'checks.updat','class' => 'was-validated'])!!}
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Tipo de Cheque:</b></span>
                                </div>
                                <select name="checkcategoria_id" id="select-checkcategoria_id" class="form-control" required>
                                    <option value="">Seleccione un tipo..</option>
                                    @foreach($tipos as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Nro Cheque:</b></span>
                                </div>
                                <input type="text" class="form-control" id="nrocheque" name="nrocheque"required>
                            </div>
                            <div class="col-md-8">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><b>Resumen:</b></span>
                            </div>
                            <input type="text" class="form-control" id="resumen" name="resumen" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Nro memorándum:</b></span>
                                </div>
                                <input type="number" step="any" class="form-control" id="nromemo" name="nromemo" required>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Nro Preventivo:</b></span>
                                </div>
                                <input type="text" class="form-control" id="nroprev" name="nroprev"required>
                            </div>     
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Nro Devengado:</b></span>
                                </div>
                                <input type="number" step="any" class="form-control" id="nrodev" name="nrodev" required>
                            </div>                   
                        </div>
                        <div class="row">                            
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Fecha Cheque:</b></span>
                                </div>
                                <input type="date" step="any" class="form-control" id="fechacheque" name="fechacheque" required>
                            </div> 
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><b>Monto:</b></span>
                                </div>
                                <input type="number" step="any" class="form-control" id="monto" name="monto" required>
                            </div>   
                            <div class="col-md-4">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Deposito:</b></span>
                                </div>
                                <input type="text" step="any" class="form-control" id="deposito" name="deposito" required>
                            </div>                
                        </div>
                        <div class="row">    
                                 
                            <div class="col-md-12">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><b>Observacion:</b></span>
                                </div>
                                <textarea id="observacion" name="observacion" cols="77" rows="3" required></textarea>
                            </div>                
                        </div>
                        
                        
                        
                    </div>
                    
                    
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-between">
                        <button type="button text-left" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Volver">Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm" title="Registrar..">
                            Registrar
                        </button>
                    </div>
                    {!! Form::close()!!} 
                    
                </div>
            </div>
        </div>

        {{-- Single delete modal --}}
        <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route' => 'checks.delet', 'method' => 'DELETE']) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">

                        <div class="text-center" style="text-transform:uppercase">
                            <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                            <br>
                            
                            <p><b>Desea eliminar el siguiente registro?</b></p>
                        </div>
                    </div>                
                    <div class="modal-footer">
                        
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                    {!! Form::close()!!} 
                </div>
            </div>
        </div>
    @stop

    @section('css')
        <style>
            .select2{
                width: 100% !important;
            }
        </style>
    @stop

    @section('javascript')
        <script src="{{ url('js/main.js') }}"></script>
        <script>
            $(document).ready(() => {
                $('#dataTable').DataTable({
                    language: {
                            // "order": [[ 0, "desc" ]],
                            sProcessing: "Procesando...",
                            sLengthMenu: "Mostrar _MENU_ registros",
                            sZeroRecords: "No se encontraron resultados",
                            sEmptyTable: "Ningún dato disponible en esta tabla",
                            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                            sSearch: "Buscar:",
                            sInfoThousands: ",",
                            sLoadingRecords: "Cargando...",
                            oPaginate: {
                                sFirst: "Primero",
                                sLast: "Último",
                                sNext: "Siguiente",
                                sPrevious: "Anterior"
                            },
                            oAria: {
                                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            },
                            buttons: {
                                copy: "Copiar",
                                colvis: "Visibilidad"
                            }
                        },
                        order: [[ 0, 'desc' ]],
                });

                $('#select-checkcategoria_id').select2();
            });

            $('#delete_editar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""

                var id = button.data('id')
                var item = button.data('items')


                // alert(item.nromemo)

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                modal.find('.modal-body #select-checkcategoria_id').val(item.checkcategoria_id).trigger('change')
                modal.find('.modal-body #nrocheque').val(item.nrocheque)
                modal.find('.modal-body #resumen').val(item.resumen)
                modal.find('.modal-body #nromemo').val(item.nromemo)
                modal.find('.modal-body #nroprev').val(item.nroprev)
                modal.find('.modal-body #nrodev').val(item.nrodev)
                modal.find('.modal-body #fechacheque').val(item.fechacheque)
                modal.find('.modal-body #monto').val(item.monto)
                modal.find('.modal-body #deposito').val(item.deposito)
                modal.find('.modal-body #observacion').val(item.observacion)
               
                


                
            });
            $('#modal_entregar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""

                var id = button.data('id')

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                
            });
            $('#delete_modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) //captura valor del data-empresa=""

                var id = button.data('id')

                var modal = $(this)
                modal.find('.modal-body #id').val(id)
                
            });

        </script>
    @stop

@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif