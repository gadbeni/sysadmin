@extends('voyager::master')

@section('page_title', (isset($type) ? 'Edit' : 'Añadir').' Tipo de Memo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-window-list"></i>
        {{ isset($type) ? 'Edit' : 'Añadir' }} Tipo de Memo
    </h1>
@stop

@php
    $contracts = App\Models\Contract::with(['alternate_job' => function($q){
                        $q->where('status', 1)->where('deleted_at', NULL);
                    }])->where('status', 'firmado')->where('deleted_at', NULL)->get();
@endphp

@section('content')
    <div class="page-content edit-add container-fluid">
        <form id="form-submit" action="{{ isset($type) ? route('voyager.memos-types.update', $type->id) : route('voyager.memos-types.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="memos_types_group_id ">Grupo</label>
                                    <select name="memos_types_group_id" id="select-memos_types_group_id" class="form-control select2" required>
                                        <option value="">-- Seleccione el tipo --</option>
                                        @foreach (App\Models\MemosTypesGroup::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="origin_id">De</label>
                                    <select name="origin_id" id="select-origin_id" class="form-control select2" required>
                                        <option value="">--Seleccione origen--</option>
                                        @foreach ($contracts as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item->alternate_job)' >{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="destiny_id">A</label>
                                    <select name="destiny_id" id="select-destiny_id" class="form-control select2" required>
                                        <option value="">--Seleccione destino--</option>
                                        @foreach ($contracts as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item->alternate_job)'>{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description">Descripción</label>
                                    <input type="text" name="description" class="form-control" value="{{ isset($type) ? $type->description : '' }}" placeholder="Descripción" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="status">Estado</label> <br>
                                    <input type="checkbox" name="status" id="checkbox-status" class="toggleswitch" checked data-on="Activo" data-off="Inactivo">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="concept">Concepto</label>
                                    <textarea name="concept" class="form-control" rows="5" required>{{ isset($type) ? $type->concept : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary save">Guardar <i class="voyager-check"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            $('.toggleswitch').bootstrapToggle();

            @isset($type)
                $('#select-memos_types_group_id').val("{{ $type->memos_types_group_id }}").trigger('change');
                $('#select-origin_id').val("{{ $type->origin_id }}").trigger('change');
                $('#select-destiny_id').val("{{ $type->destiny_id }}").trigger('change');
                console.log('{{ $type->status }}')
                if("{{ $type->status }}" == 0){
                    $('#checkbox-status').bootstrapToggle('off');
                }
            @endisset
        });
    </script>
@stop
