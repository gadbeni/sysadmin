@extends('voyager::master')

@section('page_title', 'Reporte de Marcaciones')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa fa-clock"></i> Marcaciones
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('attendances.generate') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <div class="row">
                                    <div class="form-group col-md-12 text-right">
                                        <label class="radio-inline"><input type="radio" value="0" name="type" class="radio-type" checked>Personal</label>
                                        <label class="radio-inline"><input type="radio" value="1" name="type" class="radio-type">General</label>
                                    </div>
                                    <div class="form-group col-md-12 div-personal">
                                        <select name="person_id" id="select-person_id" class="form-control"></select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="start" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="finish" class="form-control" required>
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
        
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {

            customSelect('#select-person_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, null);

            $('.radio-type').change(function(){
                let value = $(this).val();
                if(value == 1){
                    $('.div-personal').fadeOut();
                }else{
                    $('.div-personal').fadeIn();
                }
            });

            $('#form-search').on('submit', function(e){
                // e.preventDefault();
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