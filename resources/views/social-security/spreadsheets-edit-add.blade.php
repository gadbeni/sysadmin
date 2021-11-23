@extends('voyager::master')

@section('page_title', 'Añadir Planilla Manual')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-edit"></i>
        Añadir Planilla Manual
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form id="form" action="{{ $type == 'create' ? route('spreadsheets.store') : route('spreadsheets.update', ['spreadsheet' => $data->id]) }}" method="post">
                    @csrf
                    @if ($type == 'edit')
                        @method('PUT')
                    @endif
                    <input type="hidden" name="redirect">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="alert alert-info" role="alert" id="alert-details" style="display: none; margin-bottom: 20px"></div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="direccion_administrativa_id">Dirección administrativa</label>
                                    <select name="direccion_administrativa_id" class="form-control select2" required>
                                        <option value="" disabled selected>Seleccione una dirección administrativa</option>
                                        @foreach($direccion_administrativa as $item)
                                            <option value="{{ $item->ID }}" @if($type == 'edit' && $item->ID == $data->direccion_administrativa_id) selected @endif>{{ $item->NOMBRE }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tipo_planilla_id">Tipo de planilla</label>
                                    <select name="tipo_planilla_id" class="form-control select2" required>
                                        <option value="" disabled selected>Tipo de planilla</option>
                                        @foreach($tipo_planilla as $item)
                                            <option value="{{ $item->ID }}" @if($type == 'edit' && $item->ID == $data->tipo_planilla_id) selected @endif @if($item->ID > 2) disabled @endif>{{ $item->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="codigo_planilla">Código de planilla</label>
                                    <input type="text" name="codigo_planilla" class="form-control" value="{{ $type == 'edit' ? $data->codigo_planilla : '' }}" placeholder="Código de planilla" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="month">Mes</label>
                                    <select name="month" class="form-control" id="select-month" required>
                                        <option value="" disabled selected>Seleccione el mes</option>
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="year">Año</label>
                                    <input type="number" name="year" min="2019" max="{{ date('Y') }}" step="1" class="form-control" value="{{ $type == 'edit' ? $data->year : '' }}" placeholder="Año" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="people">Cantidad de personas</label>
                                    <input type="number" name="people" min="1" max="1000" step="1" class="form-control" value="{{ $type == 'edit' ? $data->people : '' }}" placeholder="Cantidad de personas" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="afp_id">Tipo de AFP</label>
                                    <select name="afp_id" class="form-control select2" required>
                                        <option value="" disabled selected>Seleccione AFP</option>
                                        <option value="1" @if($type == 'edit' && 1 == $data->afp_id) selected @endif>Futuro</option>
                                        <option value="2" @if($type == 'edit' && 2 == $data->afp_id) selected @endif>Previsión</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="total">Total ganado</label>
                                    <input type="number" name="total" min="0" step="0.01" class="form-control" value="{{ $type == 'edit' ? $data->total : '' }}" placeholder="Total ganado" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="total">Total AFP</label>
                                    <input type="number" name="total_afp" min="0" step="0.01" class="form-control" value="{{ $type == 'edit' ? $data->total_afp : '' }}" placeholder="Total AFP" required />
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            @if ($type == 'create')
                            <button type="reset" id="btn-reset" class="btn btn-default">Vaciar <i class="voyager-refresh"></i></button>
                            @endif
                            <button type="submit" class="btn btn-primary">Guardar <i class="voyager-check"></i></button>
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
    <script>
        $(document).ready(function(){
            @if ($type == 'edit')
                $('#select-month').val({{ $data->month }});
            @endif
        });
    </script>
@stop
