@extends('voyager::master')

@section('page_title', 'Añadir Programa/Proyecto')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-documentation"></i>
        Añadir Programa/Proyecto
    </h1>
@stop

@php
    $program = null;
    if($dataTypeContent->getKey()){
        $program = App\Models\Program::with(['direcciones_administrativas'])->where('id', $dataTypeContent->getKey())->first();
    }
@endphp

@section('content')
    <form action="{{ $program ? route('programs.update', $program->id) : route('programs.store') }}" class="form-submit" method="post">
        @csrf
        @if($program)
            {{ method_field("PUT") }}
        @endif
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <label for="class">Tipo</label>
                                <select name="class" class="form-control select2" required>
                                    <option value="programa">Programa</option>
                                    <option value="proyecto">Proyecto</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name">Nombre</label>
                                <input type="text" name="name" class="form-control" value="{{ $program ? $program->name : old('name') }}" placeholder="FORTAL INSTITUCIONAL" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="procedure_type_id">Tipo de planilla</label>
                                <select name="procedure_type_id" class="form-control select2" required>
                                    <option value="">Planilla a la que pertenece</option>
                                    @foreach (App\Models\ProcedureType::where('planilla_id', '<>', null)->orderBy('planilla_id')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="direccion_id">Dirección administrativa</label>
                                <select name="direccion_id[]" class="form-control select2" multiple required>
                                    <option value="">Dirección a la que pertenece</option>
                                    @foreach (App\Models\Direccion::where('estado', 1)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="number">Partida</label>
                                <input type="text" name="number" class="form-control" value="{{ $program ? $program->number : old('name') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="programatic_category">Categoría programátiva</label>
                                <input type="text" name="programatic_category" class="form-control" value="{{ $program ? $program->programatic_category : old('name') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="amount">Monto</label>
                                <input type="number" name="amount" class="form-control" value="{{ $program ? $program->amount : old('name') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="year">Gestión</label>
                                <input type="number" name="year" class="form-control" value="{{ $program ? $program->year : old('name') ?? date('Y') }}" required>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary btn-submit save">Guardar <i class="voyager-check"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        
    </style>
@stop

@section('javascript')
    <script>
        var program = @json($program);
        $(document).ready(function(){
            if(program){
                $('.form-submit select[name="class"]').val(program.class.toLowerCase()).trigger('change');
                $('.form-submit select[name="procedure_type_id"]').val(program.procedure_type_id).trigger('change');
                let programs = [];
                program.direcciones_administrativas.map(da => {
                    programs.push(da.id);
                })
                $('.form-submit select[name="direccion_id[]"]').val(programs).trigger('change');
            }
        });
    </script>
@stop
