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
                                    <div class="input-group">
                                        <select name="memos_type_id" id="select-memos_type_id" class="form-control select2" required>
                                            <option value="">--Seleccione el tipo de memo--</option>
                                            @foreach (App\Models\MemosType::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                            <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" data-toggle="modal" data-target="#add-type-modal" style="margin: 0px" type="button">
                                                <i class="voyager-plus"></i> Nuevo
                                            </button>
                                        </div>
                                    </div>
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
                                    <label for="number">Preventivo</label>
                                    <input type="text" name="number" class="form-control" value="{{ isset($memo) ? $memo->number : old('number') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="da_sigep">DA (SIGEP)</label>
                                    <input type="text" name="da_sigep" class="form-control" value="{{ isset($memo) ? $memo->da_sigep : old('da_sigep') }}" required>
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
                                    <div class="input-group">
                                        <select name="person_external_id" id="select-person_external_id" class="form-control select2" required>
                                            <option value="">--Seleccione orden--</option>
                                            @foreach (App\Models\PersonExternal::where('deleted_at', NULL)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->full_name }} - CI: {{ $item->ci_nit  }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" data-toggle="modal" data-target="#add-external-person-modal" style="margin: 0px" type="button">
                                                <i class="glyphicon glyphicon-plus"></i> Nuevo
                                            </button>
                                        </div>
                                    </div>
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

    {{-- add type modal --}}
    <form action="{{ route('voyager.memos-types.store') }}" id="form-add-type" method="POST">
        @csrf
        <input type="hidden" name="status" value="1">
        <input type="hidden" name="ajax" value="1">
        <div class="modal fade" tabindex="-1" id="add-type-modal" role="dialog">
            <div class="modal-dialog modal-success modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-plus"></i> Agregar Tipo de Memo</h4>
                    </div>
                    <div class="modal-body">
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
                                <select name="origin_id" id="select-origin_id-modal" class="form-control select2" required>
                                    <option value="">--Seleccione origen--</option>
                                    @foreach ($contracts as $item)
                                    <option value="{{ $item->id }}" data-item='@json($item->alternate_job)' >{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="destiny_id">A</label>
                                <select name="destiny_id" id="select-destiny_id-modal" class="form-control select2" required>
                                    <option value="">--Seleccione destino--</option>
                                    @foreach ($contracts as $item)
                                    <option value="{{ $item->id }}" data-item='@json($item->alternate_job)'>{{ $item->person->first_name }} {{ $item->person->last_name }} - CI: {{ $item->person->ci }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="description">Descripción</label>
                                <input type="text" name="description" class="form-control" placeholder="Descripción" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="concept">Concepto</label>
                                <textarea name="concept" class="form-control" rows="10" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-success" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- add beneficiary modal --}}
    <form action="{{ route('voyager.person-externals.store') }}" id="form-add-external-person" method="POST">
        @csrf
        <input type="hidden" name="status" value="1">
        <input type="hidden" name="ajax" value="1">
        <div class="modal fade" tabindex="-1" id="add-external-person-modal" role="dialog">
            <div class="modal-dialog modal-success modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-plus"></i> Agregar Beneficiario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="person_external_type_id ">Tipo</label>
                                <select name="person_external_type_id " class="form-control select2" required>
                                    <option value="">-- Seleccione el tipo --</option>
                                    @foreach (App\Models\PersonExternalType::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="person_id">Funcionario</label>
                                <select name="person_id" id="select-person_id" class="form-control select2">
                                    <option value="">-- Seleccione funcionario --</option>
                                    @foreach (App\Models\Person::where('deleted_at', NULL)->orderBy('last_name')->get() as $item)
                                    <option value="{{ $item->id }}" data-item='@json($item)'> {{ $item->first_name }} {{ $item->last_name }} - CI: {{ $item->ci }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="full_name">Nombre completo</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Nombre completo" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ci_nit">CI/NIT</label>
                                <input type="text" name="ci_nit" class="form-control" placeholder="CI/NIT">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="number_acount">N&deg; de cuenta</label>
                                <input type="text" name="number_acount" class="form-control" placeholder="N&deg; de cuenta">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">Celular</label>
                                <input type="text" name="phone" class="form-control" placeholder="Celular">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="address">Dirección</label>
                                <textarea name="address" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-success" value="Guardar">
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
                    $('#select-origin_id').val(item.origin_id).trigger('change');
                    $('#select-destiny_id').val(item.destiny_id).trigger('change');
                    @if(!isset($memo))
                    $('#textarea-concept').val(item.concept);
                    @endif
                }else{
                    $('#select-origin_id').val('').trigger('change');
                    $('#select-destiny_id').val('').trigger('change');
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

            $('#form-add-type').submit(function(e){
                $('#form-add-type input[type="submit"]').attr('disabled', 'disabled');
                $('#form-add-type input[type="submit"]').val('Guardando...');
                e.preventDefault();
                $.post($('#form-add-type').attr('action'), $('#form-add-type').serialize(), function(res){
                    if(res.error){
                        toastr.error('Ocurrió un error inesperado', 'Error');
                        return;    
                    }
                    $('#select-memos_type_id').append(`<option value="${res.id}" data-item='${JSON.stringify(res)}'>${res.description}</option>`);
                    $('#add-type-modal').modal('hide');
                    toastr.success('Tipo de memo guardado exitosamente', 'Bien hecho');

                    $('#form-add-type input[type="submit"]').removeAttr('disabled');
                    $('#form-add-type input[type="submit"]').val('Guardar');
                    $('#form-add-type').trigger('reset');
                    $('#select-origin_id').trigger('change');
                    $('#select-destiny_id').trigger('change');
                });
            });

            $('#select-person_id').change(function(){
                let item = $('#select-person_id option:selected').data('item');
                if(item){
                    $('#form-add-external-person input[name="full_name"]').val(`${item.first_name} ${item.last_name}`);
                    $('#form-add-external-person input[name="ci_nit"]').val(item.ci);
                    $('#form-add-external-person input[name="phone"]').val(item.phone ? item.phone : '');
                    $('#form-add-external-person input[name="email"]').val(item.email ? item.email : '');
                    $('#form-add-external-person textarea[name="address"]').val(item.address ? item.address : '');
                }else{
                    $('#form-add-external-person input[name="full_name"]').val('');
                    $('#form-add-external-person input[name="ci_nit"]').val('');
                    $('#form-add-external-person input[name="phone"]').val('');
                    $('#form-add-external-person input[name="email"]').val('');
                    $('#form-add-external-person textarea[name="address"]').val('');
                }
            });

            $('#form-add-external-person').submit(function(e){
                $('#form-add-external-person input[type="submit"]').attr('disabled', 'disabled');
                $('#form-add-external-person input[type="submit"]').val('Guardando...');
                e.preventDefault();
                $.post($('#form-add-external-person').attr('action'), $('#form-add-external-person').serialize(), function(res){
                    if(res.error){
                        toastr.error('Ocurrió un error inesperado', 'Error');
                        return;    
                    }
                    $('#select-person_external_id').append(`<option value="${res.id}">${res.full_name} - CI: ${res.ci_nit}</option>`);
                    $('#add-external-person-modal').modal('hide');
                    toastr.success('Beneficiario guardado exitosamente', 'Bien hecho');

                    $('#form-add-external-person input[type="submit"]').removeAttr('disabled');
                    $('#form-add-external-person input[type="submit"]').val('Guardar');
                    $('#form-add-external-person').trigger('reset');
                    $('#select-person_id').trigger('change');
                });
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
