@extends('voyager::master')

@section('page_title', 'Ver Cheque')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-window-list"></i> Viendo Cheque
        <a href="{{ route('social-security.checks.index') }}" class="btn btn-warning">
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
                                <p>{{ $check->planilla_haber_id ?? str_pad($check->paymentschedule_id, 6, "0", STR_PAD_LEFT).' - '.$check->paymentschedule->period->name.' | '.($check->afp ? 'Futuro' : 'Previsión') }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Registrado por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $check->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $check->planilla_haber_id ? '' : $check->paymentschedule->direccion_administrativa->nombre }}</p>
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
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estoado</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                @php
                                    $status = '';
                                    switch ($check->status) {
                                        case '0':
                                            $status = '<label class="label label-danger">Anulado</label>';
                                            break;
                                        case '1':
                                            $status = '<label class="label label-info">Pendiente</label>';
                                            break;
                                        case '2':
                                            $status = '<label class="label label-success">Pagado</label>';
                                            break;
                                        case '3':
                                            $status = '<label class="label label-warning">Vencido</label>';
                                            break;
                                        case '4':
                                            $status = '<label class="label label-primary">Devuelto</label>';
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                @endphp
                                <p>{!! $status !!}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $check->observations ?? 'Ninguna' }}</p>
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
