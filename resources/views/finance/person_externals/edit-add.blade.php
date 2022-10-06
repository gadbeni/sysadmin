@extends('voyager::master')

@section('page_title', 'Añadir Persona externa')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-people"></i>
        Añadir Persona externa
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="person_external_type_id ">Tipo</label>
                                <select name="person_external_type_id " class="form-control select2">
                                    <option value="">-- Seleccione el tipo --</option>
                                    @foreach (App\Models\PersonExternalType::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    
@stop

@section('javascript')
    <script>
        $(document).ready(function(){

        });
    </script>
@stop
