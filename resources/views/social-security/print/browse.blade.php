@extends('voyager::master')

@section('page_title', 'Impresiones')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-people"></i> Impresiones
                            </h1>
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
            <div class="col-md-12">
                <form action="{{ route('print.form', ['name' =>'gtc']) }}" method="post" target="_blank">
                    @csrf
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <h3>Impresión de GTC-11</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="dependence_id">Dependencia</label>
                                    <select name="dependence_id" class="form-control select2">
                                        @foreach (\App\Models\Dependence::where('deleted_at', NULL)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->legal_representative_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nro_planilla">N&deg; de planilla</label>
                                    <input type="text" name="nro_planilla" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-primary">Generar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            
        });
    </script>
@stop
