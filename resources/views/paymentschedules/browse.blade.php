@extends('voyager::master')

@section('page_title', 'Viendo Planillas')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-logbook"></i> Planillas
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if(auth()->user()->hasPermission('add_paymentschedules'))
                                <a href="{{ route('paymentschedules.create') }}" class="btn btn-success">
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
    <div class="page-content edit-add container-fluid">
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
                                <input type="text" id="input-search" class="form-control">
                                @if (!Auth::user()->direccion_administrativa_id)
                                <a href="#more-options" class="btn btn-link" data-toggle="collapse"> <i class="fa fa-plus"></i> Más opciones</a>
                                @endif
                            </div>
                            <div class="col-sm-12">
                                <div id="more-options" class="collapse">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="procedure_type_id">Planillas</label>
                                            <select name="procedure_type_id" class="form-control select2" id="select-procedure_type_id">
                                                <option value="">Todas</option>
                                                <option value="1">Permanente</option>
                                                <option value="5">Eventual</option>
                                                <option value="2">Consultoría de línea</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="direccion_administrativa_id">Dirección administrativa</label>
                                            <select name="direccion_administrativa_id" class="form-control select2" id="select-direccion_administrativa_id">
                                                <option value="">Todas</option>
                                                @foreach (App\Models\Direccion::where('estado', 1)->where('deleted_at', null)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="user_id">Registrado por</label>
                                            <select name="user_id" class="form-control select2" id="select-user_id">
                                                <option value="">Todos los usuarios</option>
                                                @foreach (App\Models\User::where('deleted_at', null)->whereRaw("id in (select user_id from contracts where deleted_at is null)")->where('role_id', '>', Auth::user()->role_id == 1 ? 0 : 1)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
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
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        var procedure_type_id = '';
        var direccion_administrativa_id = '';
        var user_id = '';
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

            $('#select-paginate').change(function(){
                countPage = $(this).val();
                list();
            });

            $('#select-procedure_type_id').change(function(){
                procedure_type_id = $('#select-procedure_type_id option:selected').val();
                list();
            });

            $('#select-direccion_administrativa_id').change(function(){
                direccion_administrativa_id = $('#select-direccion_administrativa_id option:selected').val();
                list();
            });

            $('#select-user_id').change(function(){
                user_id = $('#select-user_id option:selected').val();
                list();
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let url = '{{ url("admin/paymentschedules/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}?search=${search}&procedure_type_id=${procedure_type_id}&direccion_administrativa_id=${direccion_administrativa_id}&user_id=${user_id}&paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    $('#div-results').loading('toggle');
                }
            });
        }
    </script>
@stop
