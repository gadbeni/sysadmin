@extends('voyager::master')

@section('page_title', 'Viendo Pagos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                            <i class="voyager-certificate"></i> Planillas Adicionales
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
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
                                        <th>C.I.</th>
                                        <th>Funcionario</th>
                                        <th>Cargo</th>
                                        <th>Sueldo</th>
                                        <th>Dias Trabajados</th>
                                        <th>Monto Factura</th>
                                        <th>RC-IVA</th>
                                        <th>Total</th>
                                        <th>Liquido Pagable</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $dat)
                                        <tr>
                                            <td>{{ $dat->id}}</td>
                                            <td>{{ $dat->ci }}</td>
                                            <td>{{ $dat->funcionario }}</td>
                                            <td>{{ $dat->cargo }}</td>
                                            <td>
                                                <ul>
                                                    <li><b>Sueldo: </b> <small>Bs.</small> {{ $dat->sueldo }}</li>
                                                </ul>
                                            </td>
                                            <td>{{ $dat->dia }}</td>
                                            <td>{{ $dat->montofactura }}</td>
                                            <td>{{ $dat->rciva }}</td>
                                            <td>{{ $dat->total }}</td>
                                            <td>{{ $dat->liqpagable }}</td>
                                            <td>
                                                <a type="button" data-toggle="modal" data-target="#delete_modal" data-id="{{ $dat->id}}" class="btn btn-danger"><i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="odd">
                                            <td valign="top" colspan="11" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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
    <!-- The Modal -->
    <div class="modal fade" role="dialog" id="modalRegistrar">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header btn-success">
                    <h4 class="modal-title"><i class="voyager-plus"></i>Registrar Pago</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {!! Form::open(['route' => 'planillas_adicionales.store','class' => 'was-validated'])!!}
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><b>CI:</b></span>
                          </div>
                          <input type="text" class="form-control" id="ci" name="ci"required>
                        </div>
                        <div class="col-md-8">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><b>Funcionario:</b></span>
                          </div>
                          <input type="text" class="form-control" id="funcionario" name="funcionario" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><b>Sueldo Bs:</b></span>
                            </div>
                            <input type="number" step="any" class="form-control" id="sueldo" name="sueldo" required>
                        </div>
                        <div class="col-md-8">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><b>Cargo:</b></span>
                          </div>
                          <input type="text" class="form-control" id="cargo" name="cargo"required>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><b>Dias Trabajados:</b></span>
                            </div>
                            <input type="number" step="any" class="form-control" id="dia" name="dia" required>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><b>Monto Factura:</b></span>
                          </div>
                          <input type="number" step="any" class="form-control" id="montofactura" name="montofactura"required>
                        </div> 
                        <div class="col-md-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><b>RC-IVA:</b></span>
                            </div>
                            <input type="number" step="any" class="form-control" id="rciva" name="rciva"required>
                        </div>                
                    </div>
                    <div class="row">    
                        <div class="col-md-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><b>Total:</b></span>
                            </div>
                            <input type="number" step="any" class="form-control" id="total" name="total"required>
                        </div>        
                        <div class="col-md-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><b>Liquido Pagable:</b></span>
                            </div>
                            <input type="number" step="any" class="form-control" id="liqpagable" name="liqpagable"required>
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

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route' => 'planillas.adicional.delete', 'method' => 'DELETE']) !!}
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

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(() => {
            $('#dataTable').DataTable({
                language
            });
        });

        $('#delete_modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) //captura valor del data-empresa=""

            var id = button.data('id')

            var modal = $(this)
            modal.find('.modal-body #id').val(id)
            
        });

    </script>
@stop
