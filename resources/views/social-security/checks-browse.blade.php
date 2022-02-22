@extends('voyager::master')

@section('page_title', 'Cheques de Planilla')

@if(auth()->user()->hasPermission('browse_social-securitychecks'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-8" style="padding: 0px">
                                <h1 class="page-title">
                                    <i class="voyager-window-list"></i> Cheques de Planilla
                                </h1>
                                {{-- <div class="alert alert-info">
                                    <strong>Información:</strong>
                                    <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                                </div> --}}
                            </div>
                            <div class="col-md-4 text-right" style="margin-top: 30px">
                                {{-- button delete multiple --}}
                                <a href="#" id="btn-delete-multiple" style="display: none" class="btn btn-danger btn-multiple" data-toggle="modal" data-target="#delete_multiple">
                                    <i class="voyager-trash"></i> <span>Eliminar seleccionados</span>
                                </a>
                                <a href="{{ route('social-security.checks.create') }}" class="btn btn-success btn-add-new">
                                    <i class="voyager-plus"></i> <span>Añadir Cheque</span>
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
                </div>
            </div>
        </div>
    @stop

    @section('css')

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
            });

            function list(page = 1){
                $('#div-results').loading({message: 'Cargando...'});
                let url = '{{ url("admin/social-security/checks/list") }}';
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