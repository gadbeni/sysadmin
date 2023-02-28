@extends('voyager::master')

@section('page_title', 'Ver Tipo de Memos')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-window-list"></i> Tipo de Memos
        <a href="{{ route('voyager.memos-types.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Grupo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->group->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">De</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->origin->person->first_name }} {{ $data->origin->person->last_name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">A</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->destiny->person->first_name }} {{ $data->destiny->person->last_name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Description</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->description }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Concepto</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $data->concept }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            
        });
    </script>
@stop
