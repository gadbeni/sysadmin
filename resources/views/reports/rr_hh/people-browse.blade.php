@extends('voyager::master')

@section('page_title', 'Reporte de Personas')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-people"></i> Reporte de Personas
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('reports.humans_resources.people.list') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <div class="row">
                                    <div class="form-group col-md-12 text-right">
                                        <label class="radio-inline"><input type="radio" value="0" name="contract_active" class="radio-contracts" checked>Todos</label>
                                        <label class="radio-inline"><input type="radio" value="1" name="contract_active" class="radio-contracts">Contrato activo</label>
                                        <label class="radio-inline"><input type="radio" value="2" name="contract_active" class="radio-contracts">Contrato en proceso</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select name="retired" class="form-control select2">
                                            <option value="todos">Todos</option>
                                            <option value="1">Jubilados</option>
                                            <option value="0">No Jubilados</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select name="afp_status" class="form-control select2">
                                            <option value="todos">Todos estado de AFP</option>
                                            <option value="1">AFP activo</option>
                                            <option value="0">AFP inactivo</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select name="gender" class="form-control select2">
                                            <option value="todos">Todos los géneros</option>
                                            <option value="masculino">Masculino</option>
                                            <option value="femenino">Femenino</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select name="afp_id" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            @foreach (App\Models\Afp::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 with-contract">
                                        <select name="procedure_type_id" class="form-control select2">
                                            <option value="">Todos los tipos de contartos</option>
                                            <option value="1">Permanente</option>
                                            <option value="5">Eventual</option>
                                            <option value="2">Consultoría de línea</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                </div>
                            </form>
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
            <div id="div-results" style="min-height: 100px"></div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .with-contract{
            display: none
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.radio-contracts').change(function(){
                let value = $(this).val();
                if (value > 0) {
                    $('.with-contract').fadeIn('fast');
                }else{
                    $('.with-contract').fadeOut('fast');
                }
            });

            $('#form-search').on('submit', function(e){
                e.preventDefault();
                $('#div-results').empty();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                    $('#div-results').html(res);
                })
                .fail(function() {
                    toastr.error('Ocurrió un error!', 'Oops!');
                })
                .always(function() {
                    $('#div-results').loading('toggle');
                    $('html, body').animate({
                        scrollTop: $("#div-results").offset().top - 70
                    }, 500);
                });
            });
        });

        function report_export(type){
            $('#form-search').attr('target', '_blank');
            $('#form-search input[name="type"]').val(type);
            window.form_search.submit();
            $('#form-search').removeAttr('target');
            $('#form-search input[name="type"]').val('');
        }
    </script>
@stop