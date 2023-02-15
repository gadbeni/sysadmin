@extends('voyager::master')

@section('page_title', 'Ver Memo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-news"></i> Ver Memo
        <a href="{{ route('memos.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <a href="{{ url('admin/memos/'.$memo->id.'/print') }}" class="btn btn-danger" target="_blank">
            <span class="glyphicon glyphicon-print"></span>&nbsp;
            Imprimir
        </a>
    </h1>
@stop

@php
    $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
@endphp

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Número</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ str_pad($memo->number, 9, "0", STR_PAD_LEFT) }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">De</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    {{ $memo->origin->person->first_name }} {{ $memo->origin->person->last_name }} <br>
                                    <small><b>{{ Str::upper($memo->origin_alternate_job ?? $memo->origin->cargo ? $memo->origin->cargo->descripcion : $memo->origin->job->name) }}</b></small>
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Para</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    {{ $memo->destiny->person->first_name }} {{ $memo->destiny->person->last_name }} <br>
                                    <small><b>{{ Str::upper($memo->destiny_alternate_job ?? $memo->destiny->cargo ? $memo->destiny->cargo->descripcion : $memo->destiny->job->name) }}</b></small>
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    {{ $memo->memos_type->description }} <br>
                                    <small><b>{{ $memo->type }}</b></small>
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Preventivo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $memo->code }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">D.A.</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $memo->da_sigep }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fuente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $memo->source }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Orden</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    {{ $memo->person_external->full_name }} <br>
                                    @if ($memo->person_external->ci_nit)
                                    <small>CI: {{ $memo->person_external->ci_nit }}</small> <br>
                                    @endif
                                    @if ($memo->person_external->number_acount)
                                    <small>{{ $memo->person_external->number_acount }}</small>
                                    @endif
                                </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Monto</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ number_format($memo->amount, 2, ',', '.') }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Imputación</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $memo->imputation }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d/m/Y', strtotime($memo->date)) }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Glosa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $memo->concept }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {

        });
    </script>
@stop
