@extends('voyager::master')

@section('page_title', 'Reporte de pagos realizados')

@if (auth()->user()->hasPermission('browse_reportscashierpayments'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-8" style="padding: 0px">
                                <h1 class="page-title">
                                    <i class="voyager-dollar"></i> Reporte de pagos realizados
                                </h1>
                            </div>
                            <div class="col-md-4" style="margin-top: 30px">
                                <form name="form_search" id="form-search" action="{{ route('reports.cashier.payments.list') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="print">
                                    <div class="form-group">
                                        <input type="date" name="start" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="finish" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <select name="user_id" class="form-control select2">
                                            <option value="">Todos los cajeros</option>
                                            @foreach (\App\Models\User::whereRaw('role_id = 5 or role_id = 4')->get() as $item)
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

            function report_print(){
                $('#form-search').attr('target', '_blank');
                $('#form-search input[name="print"]').val(1);
                window.form_search.submit();
                $('#form-search').removeAttr('target');
                $('#form-search input[name="print"]').val('');
            }
        </script>
    @stop

@else
    @section('content')
        @include('errors.403')
    @stop
@endif