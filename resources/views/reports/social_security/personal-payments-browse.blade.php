@extends('voyager::master')

@section('page_title', 'Pagos individuales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-person"></i> Pagos individuales
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('reports.social_security.personal.payments.list') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <div class="form-group col-md-12">
                                    <label class="radio-inline"><input type="radio" name="type_data" value="1" checked>Nuevo sistema</label>
                                    <label class="radio-inline"><input type="radio" name="type_data" value="2">Antiguo sistema</label>
                                </div>
                                <div class="form-group col-md-12" id="div-contribuyente"  style="display:none">
                                    <select name="contribuyente_id" class="form-control select2">
                                        <option value="">--Seleccione a un funcionario--</option>
                                        @foreach($contribuyentes as $contribuyente)
                                            <option value="{{ $contribuyente->ID }}">{{ str_replace('  ', ' ', $contribuyente->NombreCompleto) }} - {{ $contribuyente->N_Carnet }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12" id="div-person">
                                    <select name="person_id" id="select-person_id" class="form-control"></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="month" name="start" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="month" name="finish" class="form-control" required>
                                </div>
                                <div class="form-group col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Generar <i class="voyager-settings"></i></button>
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

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {

            customSelect('#select-person_id', '{{ url("admin/people/ajax/search") }}', formatResultPeople, data => data.first_name+' '+data.last_name, null);

            $('#form-search input[name="type_data"]').click(function(){
                if($(this).val() == 1){
                    $('#div-contribuyente').fadeOut();
                    $('#div-person').fadeIn();
                }else{
                    $('#div-contribuyente').fadeIn();
                    $('#div-person').fadeOut();
                }
            });
            
            $('#form-search').submit(function(e) {
                e.preventDefault();
                $('#div-results').empty();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(data) {
                    $('#div-results').html(data);
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
