@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Añadir Caja')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i>
                                Añadir Caja
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
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
                        <div class="panel-body">
                            <div class="form-group col-md-12">
                                <label class="control-label" for="amount">Monto a abonar</label>
                                <input type="number" name="amount" step="1" min="1" class="form-control" placeholder="10000" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="description">Detalle</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary save">Guardar <i class="voyager-check"></i> </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function(){

        });
    </script>
@stop
