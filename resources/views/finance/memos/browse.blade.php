@extends('voyager::master')

@section('page_title', 'Viendo Memos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-news"></i> Memos
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_memos'))
                                <a href="{{ route('memos.create') }}" class="btn btn-success">
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
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            <div class="col-sm-3 text-right">
                                <input type="text" id="input-search" placeholder="Buscar..." class="form-control">
                                {{-- <a href="#more-options" class="btn btn-link" data-toggle="collapse"> <i class="fa fa-plus"></i> Más opciones</a> --}}
                            </div>
                            {{-- <div class="col-sm-12">
                                <div id="more-options" class="collapse">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="origin_id">De</label>
                                            <select name="origin_id" class="form-control select2" id="select-origin_id"></select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="destiny_id">A</label>
                                            <select name="destiny_id" class="form-control select2" id="select-destiny_id"></select>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div> --}}
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
    {{-- <script src="{{ url('js/main.js') }}"></script> --}}
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        var origin_id = '';
        var destiny_id = '';
        $(document).ready(() => {
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

            $('#select-origin_id').change(function(){
                origin_id = $('#select-origin_id option:selected').val();
                list();
            });

            $('#select-destiny_id').change(function(){
                destiny_id = $('#select-destiny_id option:selected').val();
                list();
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let url = '{{ url("admin/memos/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}/?search=${search}&origin_id=${origin_id}&destiny_id=${destiny_id}&paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    $('#div-results').loading('toggle');
                }
            });
        }

        function deleteItem(url){
            $('#delete_form_alt').attr('action', url);
        }

    </script>
@stop
