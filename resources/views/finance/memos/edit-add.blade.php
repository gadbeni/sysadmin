@extends('voyager::master')

@section('page_title', 'Añadir Memo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-news"></i>
        Añadir Memo
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="memos_type_id ">Tipo</label>
                                <select name="memos_type_id " class="form-control select2" id="" required>
                                    <option value="">--Sleccione el tipo de memo--</option>
                                    @foreach (App\Models\MemosType::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for=""></label>
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
