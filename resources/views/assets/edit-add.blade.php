@extends('voyager::master')

@section('page_title', 'Añadir Activo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-tag"></i>
        Añadir Activo
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form class="form-submit" action="#" method="post">
                    @csrf
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="category_id">Categoría</label>
                                    <select name="category_id" id="select-category_id" class="form-control select2">
                                        <option value="">Seleccione una categoría</option>
                                        @foreach (App\Models\AssetsCategory::with('subcategories')->get() as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="assets_subcategory_id">Categoría</label>
                                    <select name="assets_subcategory_id" id="select-assets_subcategory_id" class="form-control select2">
                                        <option value="">Seleccione una subcategoría</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code">Código</label>
                                    <input type="text" name="code" id="input-code" class="form-control" placeholder="PB-0001" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code_siaf">Código del SIAF</label>
                                    <input type="text" name="code_siaf" id="input-code_siaf" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="code_siaf">Código del SIAF</label>
                                    <textarea name="description" class="form-control" rows="5" placeholder="Descripción detallada del activo"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary btn-submit">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
            
    </style>
@stop


@section('javascript')
    <script>
        $(document).ready(function(){
            $('#select-category_id').change(function(){
                let item = $('#select-category_id option:selected').data('item');
                $('#select-assets_subcategory_id').empty();
                $('#select-assets_subcategory_id').html('<option value="">Seleccione una subcategoría</option>');
                if (item) {
                    item.subcategories.map(subcategory => {
                        $('#select-assets_subcategory_id').append(`<option value="${subcategory.id}">${subcategory.name}</option>`);
                    });   
                }
            });
        });
    </script>
@stop
