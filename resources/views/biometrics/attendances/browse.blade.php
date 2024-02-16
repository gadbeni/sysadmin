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
                                        <label class="radio-inline"><input type="radio" value="personal" name="type" class="radio-type" checked>Personal</label>
                                        <label class="radio-inline"><input type="radio" value="group" name="type" class="radio-type">General</label>
                                    </div>
                                    <div class="form-group col-md-12 div-personal">
                                        <select name="person_id" id="select-person_id" class="form-control" required></select>
                                    </div>
                                    <div class="form-group col-md-12 div-group">
                                        <select name="direccion_administrativa_id" id="select-direccion_id" class="form-control select2">
                                            <option value="">Todas las secretarías</option>
                                            @foreach (App\Models\Direccion::where('estado', 1)->whereRaw(Auth::user()->direccion_administrativa_id ? "id = ".Auth::user()->direccion_administrativa_id : 1)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="start" value="{{ date('Y-m') }}-01" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="finish" value="{{ date('Y-m') }}-{{ date('t') }}" class="form-control" required>
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
        .div-group{
            display: none;
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {

            customSelect('#select-person_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, null);

            $('.radio-type').change(function(){
                let value = $(this).val();
                if(value == 'personal'){
                    $('.div-personal').fadeIn();
                    $('.div-group').fadeOut();
                    $('#select-person_id').prop('required', true);
                }else{
                    $('.div-group').fadeIn();
                    $('.div-personal').fadeOut();
                    $('#select-person_id').prop('required', false);
                }
                $('#select-person_id').val('').trigger('change');
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