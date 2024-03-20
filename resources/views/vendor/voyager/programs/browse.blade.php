@extends('voyager::master')

@section('page_title', 'Viendo Programas/Proyectos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-documentation"></i> Programas/Proyectos
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_programs'))
                                <a href="{{ route('voyager.programs.create') }}" class="btn btn-success">
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
                                <a href="#more-options" class="btn btn-link" data-toggle="collapse"> <i class="fa fa-plus"></i> Más opciones</a>
                            </div>
                            <div class="col-sm-12">
                                <div id="more-options" class="collapse">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="direccion_administrativa_id">Dirección administrativa</label>
                                            <select name="direccion_administrativa_id" class="form-control select2 select-filter" id="select-direccion_administrativa_id">
                                                <option value="">Todas</option>
                                                @foreach (App\Models\Direccion::where('estado', 1)->where('deleted_at', null)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="procedure_type_id">Tipo de planilla</label>
                                            <select name="procedure_type_id" class="form-control select2 select-filter" id="select-procedure_type_id">
                                                <option value="">Todos</option>
                                                @foreach (App\Models\ProcedureType::all() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="year">Gestión</label>
                                            <input type="number" class="form-control select-filter" id="input-year" name="year" step="1" min="2020" max="{{ date('Y') }}">
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Restrict modal --}}
    <form action="{{ route('programs.restrict') }}" id="form-restrict" class="form-submit" method="POST">
        @csrf
        <div class="modal fade" tabindex="-1" id="restrict-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-list"></i> Restringir a una sola Jefatura/Unidad</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="unidad_administrativa_id">Unidad Administrativa</label>
                            <select name="unidad_administrativa_id" id="select-unidad_administrativa_id" class="form-control" required></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        var direccion_administrativa_id = '';
        var procedure_type_id = '';
        var year = '';

        $(document).ready(function() {
            list();
            $('#select-unidad_administrativa_id').select2();
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('.select-filter').change(function(){
                countPage = $('#select-paginate option:selected').val();
                direccion_administrativa_id = $('#select-direccion_administrativa_id option:selected').val();
                procedure_type_id = $('#select-procedure_type_id option:selected').val();
                year = $('#input-year').val();
                list();
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let url = '{{ url("admin/programs/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}?search=${search}&direccion_administrativa_id=${direccion_administrativa_id}&year=${year}&procedure_type_id=${procedure_type_id}&paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    $('#div-results').loading('toggle');
                }
            });
        }
    </script>
@stop