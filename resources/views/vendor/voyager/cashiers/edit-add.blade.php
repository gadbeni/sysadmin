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
                    <form role="form" action="{{ route('cashiers.store') }}" method="post">
                        @csrf
                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="user_id">Cajero</label>
                                <select name="user_id" class="form-control select2" required>
                                    <option value="">Seleccione al usuario</option>
                                    @foreach (\App\Models\User::where('deleted_at', NULL)->where('role_id', 3)->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="title">Nombre de la caja</label>
                                <input type="text" name="title" class="form-control" value="Caja {{ date('ymd') }}" placeholder="Caja 1" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="amount">Monto de apertura</label>
                                <input type="number" name="amount" step="1" min="0" class="form-control" placeholder="10000" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="observations">Observaciones</label>
                                <textarea name="observations" class="form-control" rows="5"></textarea>
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
