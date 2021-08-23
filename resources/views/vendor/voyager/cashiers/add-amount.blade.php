@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Abonar Monto a Caja')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i>
                                Abonar Monto a Caja
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $vault = \App\Models\Vault::with(['details.cash'])->where('status', 'activa')->where('deleted_at', NULL)->first();
            @endphp
            @if (!$vault)
            <div class="alert alert-warning">
                <strong>Advertencia:</strong>
                <p>No puedes aperturar caja debido a que no existe un registro de bóveda activo.</p>
            </div>
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form role="form" action="{{ route('cashiers.amount.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="cashier_id" value="{{ $id }}">
                        <input type="hidden" name="type" value="ingreso">
                        <input type="hidden" name="vault_id" value="{{ $vault ? $vault->id : 0 }}">
                        <div class="panel-body">
                            <div class="form-group col-md-12">
                                <div class="panel-body" style="padding-top:0;max-height:400px;overflow-y:auto">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Corte</th>
                                                <th>Cantidad</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lista_cortes"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="amount">Monto a abonar</label>
                                <input type="number" name="amount" id="input-total" class="form-control" readonly required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="description">Detalle</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if ($vault)
                            <button type="submit" class="btn btn-primary save">Abonar <i class="voyager-check"></i> </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('js/cash_value.js') }}"></script>
    <script>
        $(document).ready(function(){

        });
    </script>
@stop
