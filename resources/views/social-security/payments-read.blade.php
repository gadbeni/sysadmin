@extends('voyager::master')

@section('page_title', 'Ver Pago')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-window-list"></i> Viendo Pago
        <a href="{{ route('payments.index') }}" class="btn btn-warning">
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
                                <p>{{ $payment->planilla_haber_id ?? str_pad($payment->paymentschedule_id, 6, "0", STR_PAD_LEFT).' - '.$payment->paymentschedule->period->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Registrado por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->user->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">ID pago AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->payment_id ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">N&deg; de FPC</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->fpc_number ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de pago a AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->date_payment_afp ? date('d/m/Y', strtotime($payment->date_payment_afp)) : 'No definida' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Multa AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->penalty_payment ?? 0 }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">GTC-11</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->gtc_number ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de pago a caja cordes</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->date_payment_cc ? date('d/m/Y', strtotime($payment->date_payment_cc)) : 'No definida' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Número de recibo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->recipe_number ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Número de deposito</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->deposit_number ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">ID pago caja de salud</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->check_id ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Multa caja de salud</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $payment->penalty_check ?? 0 }}</p>
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
