@extends('voyager::master')

@section('page_title', 'Pagos de Planilla')

@if(auth()->user()->hasPermission('browse_social-securitypayments'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-6" style="padding: 0px">
                                <h1 class="page-title">
                                    <i class="voyager-dollar"></i> Pagos de Planilla
                                </h1>
                                {{-- <div class="alert alert-info">
                                    <strong>Información:</strong>
                                    <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                                </div> --}}
                            </div>
                            <div class="col-md-6 text-right" style="margin-top: 30px">
                                {{-- button selección multiple --}}
                                <a href="#" id="btn-delete-multiple" data-url="{{ route('payments.delete_multiple') }}" style="display: none" class="btn btn-danger btn-multiple" data-toggle="modal" data-target="#delete_multiple">
                                    <i class="voyager-trash"></i> <span>Eliminar seleccionados</span>
                                </a>
                                <a href="{{ route('payments.create') }}" class="btn btn-success btn-add-new">
                                    <i class="voyager-plus"></i> <span>Añadir Pago</span>
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
                    <form action="#" id="form-selection-multiple" method="POST">
                        {{ csrf_field() }}
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="dataTables_length" id="dataTable_length">
                                            <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select> registros</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" id="input-search" class="form-control">
                                    </div>
                                </div>
                                <div class="row" id="div-results" style="min-height: 120px"></div>
                            </div>
                        </div>

                        {{-- Modal delete massive --}}
                        <div class="modal modal-danger fade" tabindex="-1" id="delete_multiple" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="voyager-trash"></i> ¿Estás seguro prueba?</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h4>¿Estás seguro de que quieres eliminar los pagos seleccionados?</h4>
                                    </div>
                                    <div class="modal-footer">
                                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Eliminar">
                                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @stop

    @section('css')
        {{-- <style>
            .btn-multiple{
                display: none;
            }
        </style> --}}
    @stop

    @section('javascript')
        <script src="{{ url('js/main.js') }}"></script>
        <script>

            var countPage = 10, order = 'id', typeOrder = 'desc';
            $(document).ready(function() {
                list();
                
                $('#input-search').on('keyup', function(e){
                    if(e.keyCode == 13) {
                        list();
                    }
                });

                $('#select-paginate').change(function(){
                    countPage = $(this).val();
                    list();
                });

                $('.btn-multiple').click(function(){
                    let url = $(this).data('url');
                    let form = $('#form-selection-multiple');
                    form.attr('action', url);
                });
            });

            function list(page = 1){
                $('#div-results').loading({message: 'Cargando...'});
                let url = '{{ url("admin/social-security/payments/list") }}';
                let search = $('#input-search').val() ? $('#input-search').val() : '';
                $.ajax({
                    url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                    type: 'get',
                    success: function(response){
                        $('#div-results').html(response);
                        $('#div-results').loading('toggle');
                    }
                });
            }
        </script>
    @stop

    @else
    @section('content')
        <h1>Error</h1>
    @stop
@endif