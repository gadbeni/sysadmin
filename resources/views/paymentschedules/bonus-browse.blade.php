@extends('voyager::master')

@section('page_title', 'Generar Aguinaldos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-logbook"></i> Generar Aguinaldos
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            <div class="input-group">
                                <input type="number" min="2022" step="1" value="{{ date('Y') }}" class="form-control" id="input-year" placeholder="Año" readonly>
                                <div class="input-group-btn">
                                    <button class="btn btn-primary btn-sm" id="btn-submit" style="margin: 0px">
                                        Generar <i class="voyager-settings"></i>
                                    </button>
                                </div>
                            </div>
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
                        {{-- <div class="row">
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
                        </div> --}}
                        <div class="row" id="div-results" style="min-height: 120px">
                            
                        </div>
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

            $('#btn-submit').click(function(){
                list();
            });
        });

        function list(page = 1){
            let url = '{{ route("bonus.generate") }}';
            let year = $('#input-year').val();
            if(year){
                $('#div-results').loading({message: 'Cargando...'});
                $.ajax({
                    url: `${url}?year=${year}`,
                    type: 'get',
                    success: function(response){
                        $('#div-results').html(response);
                        $('#div-results').loading('toggle');
                    }
                });
            }else {
                toastr.warning("Debes ingresar el año", "Advertencia");
            }
        }
    </script>
@stop
