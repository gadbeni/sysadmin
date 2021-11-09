@extends('voyager::master')

@section('page_title', 'Pagos de Planilla')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Pagos de Planilla
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            {{-- button delete multiple --}}
                            <a href="#" id="btn-delete-multiple" style="display: none" class="btn btn-danger" data-toggle="modal" data-target="#delete_multiple">
                                <i class="voyager-trash"></i> <span>Eliminar seleccionados</span>
                            </a>
                            <a href="{{ route('payments.create') }}" class="btn btn-success btn-add-new">
                                <i class="voyager-plus"></i> <span>Agregar pago</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('payments.delete_multiple') }}" id="form_delete_multiple" method="POST">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-hover"></table>
                            </div>
                        </div>
                    </div>

                    {{-- Modal delete massive --}}
                    <div class="modal modal-danger fade" tabindex="-1" id="delete_multiple" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><i class="voyager-trash"></i> ¿Estás seguro?</h4>
                                </div>
                                <div class="modal-body">
                                    <h4>¿Estás seguro de que quieres eliminar los pagos seleccionados?</h4>
                                </div>
                                <div class="modal-footer">
                                        {{ csrf_field() }}
                                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Eliminar">
                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                        Cancelar
                                    </button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let columns = [
                { data: 'checkbox', title: '', orderable: false, searchable: false },
                { data: 'id', title: 'id' },
                { data: 'planilla_id', title: 'Planilla' },
                { data: 'fpc_number', title: 'Nro de FPC' },
                { data: 'gtc_number', title: 'Nro de GTC-11' },
                { data: 'deposit_number', title: 'Nro de deposito' },
                { data: 'user', title: 'Resgistrado por' },
                { data: 'created_at', title: 'Registrado el' },
                { data: 'actions', title: 'Acciones', orderable: false, searchable: false },
            ];
            customDataTable("{{ url('admin/social-security/payments/list') }}", columns, 1);
        });
    </script>
@stop
