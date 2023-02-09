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
                                <select name="memos_type_id" id="select-memos_type_id" class="form-control select2" required>
                                    <option value="">--Seleccione el tipo de memo--</option>
                                    @foreach (App\Models\MemosType::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="origin_id">Origen</label>
                                <select name="origin_id" class="form-control select2" required>
                                    <option value="">--Seleccione origen--</option>
                                    @foreach (App\Models\Contract::where('status', 'firmado')->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="destiny_id">Origen</label>
                                <select name="destiny_id" class="form-control select2" required>
                                    <option value="">--Seleccione destino--</option>
                                    @foreach (App\Models\Contract::where('status', 'firmado')->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="code">Preventivo</label>
                                <input type="text" name="code" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="da_sigep">DA (SIGEP)</label>
                                <input type="number" name="da_sigep" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="origin">Fuente</label>
                                <input type="text" name="origin" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="amount">Monto</label>
                                <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date">Fecha</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="imputation">Imputación</label>
                                <input type="number" name="imputation" class="form-control" >
                            </div>
                            <div class="form-group col-md-12">
                                <label for="person_external_id">Orden</label>
                                <select name="person_external_id" class="form-control select2" required>
                                    <option value="">--Seleccione orden--</option>
                                    @foreach (App\Models\PersonExternal::where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->full_name }} - CI: {{ $item->ci_nit  }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="subject">Concepto</label>
                                <textarea name="subject" id="textarea-subject" class="form-control richTextBox" rows="3"></textarea>
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

            $.extend({selector: '.richTextBox'}, {})
            tinymce.init(window.voyagerTinyMCE.getConfig({selector: '.richTextBox'}));

            $('#select-memos_type_id').change(function(){
                let item = $('#select-memos_type_id option:selected').data('item');
                if(item){
                    $('#textarea-concept').val(item.destiny);
                    tinymce.activeEditor.setContent(item.concept);
                }else{
                    $('#textarea-concept').val('');
                    tinymce.activeEditor.setContent('');
                }
            });
        });
    </script>
@stop
