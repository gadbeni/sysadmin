@extends('voyager::master')

@section('page_title', (isset($memo) ? 'Editar' : 'Añadir').' Memo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-news"></i>
        {{ isset($memo) ? 'Editar' : 'Añadir' }} Memo
    </h1>
@stop

@php
    $contracts = App\Models\Contract::with(['alternate_job' => function($q){
                        $q->where('status', 1)->where('deleted_at', NULL);
                    }])->where('status', 'firmado')->where('deleted_at', NULL)->get();
@endphp

@section('content')
    <form id="form-submit" action="{{ isset($memo) ? route('memos.update', $memo->id) : route('memos.store') }}" method="post">
        @csrf
        @isset($memo)
            @method('put')
        @endisset
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="memos_type_id ">Tipo</label>
                                    <select name="memos_type_id" id="select-memos_type_id" class="form-control select2" required>
                                        <option value="">--Seleccione el tipo de memo--</option>
                                        @foreach (App\Models\MemosType::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="origin_id">Origen</label>
                                    <select name="origin_id" id="select-origin_id" class="form-control select2" required>
                                        <option value="">--Seleccione origen--</option>
                                        @foreach ($contracts as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item->alternate_job)' >{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="origin_alternate_job">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="destiny_id">Destino</label>
                                    <select name="destiny_id" id="select-destiny_id" class="form-control select2" required>
                                        <option value="">--Seleccione destino--</option>
                                        @foreach ($contracts as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item->alternate_job)'>{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="destiny_alternate_job">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code">Preventivo</label>
                                    <input type="text" name="code" class="form-control" value="{{ isset($memo) ? $memo->code : old('code') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="da_sigep">DA (SIGEP)</label>
                                    <input type="number" name="da_sigep" class="form-control" value="{{ isset($memo) ? $memo->da_sigep : old('da_sigep') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="source">Fuente</label>
                                    <input type="text" name="source" class="form-control" value="{{ isset($memo) ? $memo->source : old('source') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="amount">Monto</label>
                                    <input type="number" name="amount" class="form-control" value="{{ isset($memo) ? $memo->amount : old('amount') }}" step="0.01" min="0" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date">Fecha</label>
                                    <input type="date" name="date" class="form-control" value="{{ isset($memo) ? $memo->date : old('date') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="imputation">Imputación</label>
                                    <input type="text" name="imputation" class="form-control" value="{{ isset($memo) ? $memo->imputation : old('imputation') }}" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="type">Sirvase</label>
                                    <select name="type" id="select-type" class="form-control select2" required>
                                        <option value="">--Seleccione--</option>
                                        <option value="Desembolso">Desembolso</option>
                                        <option value="Reembolso">Reembolso</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Fondo en Avance">Fondo en Avance</option>
                                        <option value="Pago">Pago</option>
                                        <option value="Deposito">Deposito</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="person_external_id">A la orden de</label>
                                    <select name="person_external_id" id="select-person_external_id" class="form-control select2" required>
                                        <option value="">--Seleccione orden--</option>
                                        @foreach (App\Models\PersonExternal::where('deleted_at', NULL)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->full_name }} - CI: {{ $item->ci_nit  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="concept">Concepto</label>
                                    <textarea name="concept" id="textarea-concept" class="form-control" rows="5">{{ isset($memo) ? $memo->concept : old('concept') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary save">Guardar <i class="voyager-check"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            $('#select-memos_type_id').change(function(){
                let item = $('#select-memos_type_id option:selected').data('item');
                if(item){
                    $('#textarea-concept').val(item.concept);
                }else{
                    $('#textarea-concept').val('');
                }
            });

            $('#select-origin_id').change(function(){
                let item = $('#select-origin_id option:selected').data('item');
                if (item) {
                    $('#form-submit input[name="origin_alternate_job"]').val(item.length ? item[0].name : '');
                }
            });

            $('#select-destiny_id').change(function(){
                let item = $('#select-destiny_id option:selected').data('item');
                if (item) {
                    $('#form-submit input[name="destiny_alternate_job"]').val(item.length ? item[0].name : '');
                }
            });

            @isset($memo)
                $('#select-memos_type_id').val("{{ $memo->memos_type_id }}").trigger('change');
                $('#select-origin_id').val("{{ $memo->origin_id }}").trigger('change');
                $('#select-destiny_id').val("{{ $memo->destiny_id }}").trigger('change');
                $('#select-type').val("{{ $memo->type }}").trigger('change');
                $('#select-person_external_id').val("{{ $memo->person_external_id }}").trigger('change');
            @endisset
        });
    </script>
@stop
