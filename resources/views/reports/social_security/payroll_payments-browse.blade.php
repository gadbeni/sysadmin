@extends('voyager::master')

@section('page_title', 'Pagos de cheques')

@if (auth()->user()->hasPermission('browse_reportssocial-securitypayrollpayments'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-8" style="padding: 0px">
                                <h1 class="page-title">
                                    <i class="voyager-tag"></i> Pagos de cheques
                                </h1>
                            </div>
                            <div class="col-md-4" style="margin-top: 30px">
                                <form name="form_search" id="form-search" action="{{ route('reports.social_security.payrollpayments.list') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="type">
                                    <div class="form-group col-md-12">
                                        <select name="direccion_administrativa_id" class="form-control select2">
                                            <option selected value="">--Todas las direcciones adminstrativas--</option>
                                            @foreach (\App\Models\Direccion::where('estado', 1)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="start" value="{{ date('Y-m') }}-01" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="finish" value="{{ date('Y-m-d') }}" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-12 text-right">
                                        <label class="checkbox-inline"><input type="checkbox" name="user" value="1">Solo mi usuario</label>
                                    </div>
                                    <div class="form-group text-right">
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
        <script>
            $(document).ready(function() {            
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

@endif