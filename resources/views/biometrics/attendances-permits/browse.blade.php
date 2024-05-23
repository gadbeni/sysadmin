@extends('voyager::master')

@section('page_title', 'Viendo Permisos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-custom">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-certificate"></i> Permisos
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_attendances-permits'))
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="voyager-plus"></i> Crear <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu" style="left: -170px !important; top: 0px !important;">
                                        <li><a href="{{ route('attendances-permits.create') }}" title="Permiso a un solo funcionario">Personal</a></li>
                                        <li><a href="{{ route('attendances-permits.create') }}?type=group" title="Agregar permiso masivo">Grupal</a></li>
                                    </ul>
                                </div>
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
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="dataTables_length" id="dataTable_length">
                                <label>Mostrar <select id="select-paginate" class="form-control input-sm select-filter">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> registros</label>
                            </div>
                        </div>
                        <div class="col-sm-3 text-right">
                            <input type="text" id="input-search" placeholder="Buscar..." class="form-control">
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
    <style>
        .panel-custom{
            box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;
        }
    </style>
@stop

@section('javascript')
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';

        $(document).ready(function() {
            list();
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('.select-filter').change(function(){
                countPage = $('#select-paginate option:selected').val();
                direccion_administrativa_id = $('#select-direccion_administrativa_id option:selected').val();
                status = $('#select-status option:selected').val();
                user_id = $('#select-user_id option:selected').val();
                list();
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let url = '{{ route("attendances-permits.list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}?search=${search}&paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    $('#div-results').loading('toggle');
                }
            });
        }
    </script>
@stop
