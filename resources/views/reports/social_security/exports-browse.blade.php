@extends('voyager::master')

@section('page_title', 'Exportación de pagos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Exportación de pagos
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <div class="col-md-12 text-right" style="margin-bottom: 20px">
                                <label class="radio-inline"><input type="radio" class="radio-type" name="option" value="1" data-target="#form-ministerio" checked>Ministerio de trabajo</label>
                                <label class="radio-inline"><input type="radio" class="radio-type" name="option" value="2" data-target="#form-afp">AFP's</label>
                                {{-- <label class="radio-inline"><input type="radio" name="optradio">Option 3</label> --}}
                            </div>
                            <form class="form_search" id="form-ministerio" action="{{ route('reports.social_security.exports.list') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <input type="hidden" name="form" value="form-ministerio">
                                <div class="form-group">
                                    <select name="procedure_type_id" class="form-control select2">
                                        @foreach (\App\Models\ProcedureType::where('deleted_at', NULL)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="period_id" class="form-control select2" required>
                                        @foreach (\App\Models\Period::all() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                </div>
                                <br>
                            </form>

                            <form class="form_search" id="form-afp" style="display: none" action="{{ route('reports.social_security.exports.list') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <input type="hidden" name="form" value="form-afp">
                                <div class="form-group">
                                    <select name="procedure_type_id" class="form-control select2" required>
                                        <option value="1">Futuro</option>
                                        <option value="2">Previsión</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="period_id" class="form-control select2" required>
                                        @foreach (\App\Models\Period::all() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                </div>
                                <br>
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

            $('.radio-type').click(function(){
                let target = $('.radio-type:checked').data('target');
                console.log(target)
                $('.form_search').fadeOut('fast', () => $(target).fadeIn('fast'));
            });

            $('.form_search').on('submit', function(e){
                e.preventDefault();
                $('#div-results').empty();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(res){
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

        function report_export(type, form){
            $(form).attr('target', '_blank');
            $(form+' input[name="type"]').val(type);
            window.form_search.submit();
            $(form).removeAttr('target');
            $(form+' input[name="type"]').val('');
        }
    </script>
@stop