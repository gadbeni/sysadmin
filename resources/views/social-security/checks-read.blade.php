@extends('voyager::master')

@section('page_title', 'Ver Cheque')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-window-list"></i> Viendo Cheque
        <a href="{{ route('checks.index') }}" class="btn btn-warning">
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
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Planilla</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $check->planilla_haber_id }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Monto</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $check->amount }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Beneficiario</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $check->beneficiary->full_name }}<br><small>{{ $check->beneficiary->type->name }}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de impresión</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d/m/Y', strtotime($check->date_print)) }} <br><small>{{ \Carbon\Carbon::parse($check->date_print)->diffForHumans() }}</small> </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $observations ?? 'Ninguna' }}</p>
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
