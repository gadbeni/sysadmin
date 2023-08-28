@extends('voyager::master')

@section('page_title', (isset($asset) ? 'Editar' : 'Añadir').' Activo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-tag"></i>
        {{ isset($asset) ? 'Editar' : 'Añadir' }} Activo
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form class="form-submit" action="{{ isset($asset) ? route('assets.update', $asset->id) : route('assets.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @isset($asset)
                        @method('PUT')
                    @endisset
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="assets_category_id">Categoría</label>
                                    <select name="assets_category_id" id="select-assets_category_id" class="form-control select2" required>
                                        <option value="">Seleccione una categoría</option>
                                        @foreach (App\Models\AssetsCategory::with('subcategories')->get() as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="assets_subcategory_id">Subcategoría</label>
                                    <select name="assets_subcategory_id" id="select-assets_subcategory_id" class="form-control" required>
                                        <option value="">Seleccione una subcategoría</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code">Código</label>
                                    <div id="input-group-code">
                                        <input type="text" name="code" id="input-code" class="form-control" placeholder="PB-0001" style="text-transform:uppercase" value="{{ isset($asset) ? $asset->code : old('code') }}" {{ isset($asset) ? 'readonly' : '' }} required>
                                        <span class="input-group-addon" style="display: none"><i class="glyphicon glyphicon-warning-sign text-danger" data-toggle="tooltip" data-placement="left" title="Éste código ya está en uso."></i></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code_siaf">Código del SIAF</label>
                                    <input type="text" name="code_siaf" class="form-control" value="{{ isset($asset) ? $asset->code_siaf : old('code_siaf') }}" placeholder="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="code_internal">Código interno <span class="voyager-info-circled" data-toggle="tooltip" data-placement="top" title="Algún código o número para diferenciar el activo de otros iguales o similares Ej: Nro de serie, IMEI, Código de modelo, etc."></span></label>
                                    <input type="text" name="code_internal" class="form-control" value="{{ isset($asset) ? $asset->code_internal : old('code_internal') }}" placeholder="N&deg; Serie  123456789" placeholder="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-tags">Etiquetas <span class="voyager-info-circled" data-toggle="tooltip" data-placement="top" title="Etiquetas o plabras claves con las se podrá filtrar el activo en caso de busqueda"></span></label>
                                    <input type="text" class="input-tags" id="input-tags" name="tags" step="any" value="{{ isset($asset) ? $asset->tags : old('tags') }}" placeholder="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="code_siaf">Descripción</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Descripción detallada del activo" required>{{ isset($asset) ? $asset->description : old('description') }}</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="initial_price">Precio de compra</label>
                                    <input type="number" name="initial_price" value="{{ isset($asset) ? $asset->initial_price : old('initial_price') }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="current_price">Precio actual</label>
                                    <input type="number" name="current_price" value="{{ isset($asset) ? $asset->current_price : old('current_price') }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="images">Imágenes</label>
                                    <input type="file" name="images[]" multiple accept=".jpg, .jpeg, .png" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">Estado</label>
                                    <select name="status" class="form-control select2">
                                        <option value="bueno">Bueno</option>
                                        <option value="regular">Regular</option>
                                        <option value="malo">Malo</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="observations">Observaciones</label>
                                    <textarea name="observations" class="form-control" rows="5" placeholder="Observaciones">{{ isset($asset) ? $asset->observations : old('observations') }}</textarea>
                                </div>
                                <div class="form-group col-md-6 div-inmuebles">
                                    <label for="input-tags">Ciudad/Localidad <span class="voyager-info-circled" data-toggle="tooltip" data-placement="top" title="Ciudad/Localidad en la que se encuentra el bien inmueble"></span></label>
                                    <select name="city_id" id="select-city_id" class="form-control select2">
                                        <option value="">Seleccione la Ciudad/Localidad</option>
                                        @foreach (App\Models\City::get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} {{ $item->province ? '- '.$item->province : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 div-inmuebles">
                                    <label for="address">Dirección</label>
                                    <input type="text" name="address" class="form-control" value="{{ isset($asset) ? $asset->address : old('address') }}" placeholder="Av. Gral Federico Román">
                                </div>
                                <div class="form-group col-md-12" id="map"></div>
                                <input type="hidden" name="location" id="input-location" value="{{ isset($asset) ? $asset->location : old('location') }}">
                                <small class="text-muted div-inmuebles">Para cambiar la ubicación puede mover el mapa y dar click para mover el marcador del mapa</small>
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
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet-1.9.4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/input-tags/dist/css/selectize.default.css') }}">
    <style>
        .div-inmuebles {
            display: none
        }
        #map {
            height: 320px;
            margin-top: 20px
        }
    </style>
@stop


@section('javascript')
    <script src="{{ asset('vendor/input-tags/dist/js/standalone/selectize.js') }}"></script>
    <script src="{{ asset('vendor/input-tags/js/index.js') }}"></script>

    <script>
        var typingTimer; // Temporizador para detectar cuando se deja de escribir
        var doneTypingInterval = 1000; // Intervalo en milisegundos
        $(document).ready(function(){
            $('#select-assets_subcategory_id').select2({tags: true});
            $('#input-tags').selectize({
                persist: false,
                createOnBlur: true,
                create: true
            });

            @isset($asset)
                setTimeout(() => {
                    $('#select-assets_category_id').val('{{ $asset->subcategory->assets_category_id }}').trigger('change');
                    $('#select-city_id').val('{{ $asset->city_id }}').trigger('change');
                }, 0);
            @endisset

            $('#select-assets_category_id ').change(function(){
                let id = $('#select-assets_category_id option:selected').val();
                let item = $('#select-assets_category_id  option:selected').data('item');
                $('#select-assets_subcategory_id').empty();
                $('#select-assets_subcategory_id').html('<option value="">Seleccione una subcategoría</option>');
                if (item) {
                    item.subcategories.map(subcategory => {
                        $('#select-assets_subcategory_id').append(`<option value="${subcategory.id}">${subcategory.name}</option>`);
                    });   
                }

                if(id == 1){
                    $('.div-inmuebles').fadeIn('fats');
                    $('#map').fadeIn('fats');
                }else{
                    $('.div-inmuebles').fadeOut('fats');
                    $('#map').fadeOut('fats');
                }

                @isset($asset)
                    setTimeout(() => {
                        $('#select-assets_subcategory_id').val('{{ $asset->assets_subcategory_id }}').trigger('change');
                    }, 0);
                @endisset
            });

            $('#input-code').on('keyup', function() {
                clearTimeout(typingTimer); // Limpiamos el temporizador cada vez que se presiona una tecla
                typingTimer = setTimeout(doneTyping, doneTypingInterval); // Iniciamos un nuevo temporizador
            });

            $('#input-code').on('keydown', function() {
                clearTimeout(typingTimer); // Limpiamos el temporizador si se presiona una tecla antes de que expire
            });
        });

        function doneTyping() {
            $.get('{{ url("admin/assets/search/code") }}?code='+$('#input-code').val(), function(res){
                if(res.id){
                    $('#input-group-code').addClass('input-group');
                    $('#input-group-code .input-group-addon').css('display', 'table-cell');
                    $('.form-submit .btn-submit').attr('disabled', 'disabled');
                }else{
                    $('#input-group-code').removeClass('input-group');
                    $('#input-group-code .input-group-addon').css('display', 'none');
                    $('.form-submit .btn-submit').removeAttr('disabled');
                }
            });
        }
    </script>

    <script src="{{ asset('vendor/leaflet/leaflet-1.9.4.js') }}"></script>
    <script>
        var map = L.map('map').fitWorld();
        var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);
        var locationMarker;

        function onLocationFound(e) {
            @isset($asset)
                let location = "{{ $asset->location }}";
                locationMarker = L.marker(location ? {lat: location.split(',')[0], lng: location.split(',')[1]} : e.latlng).addTo(map);
                if (location) {
                    map.setView(new L.LatLng(location.split(',')[0], location.split(',')[1]), 13, { animation: true });
                }
            @else
                locationMarker = L.marker(e.latlng).addTo(map);
            @endisset
            
        }

        function onMapClick(e) {
            locationMarker.setLatLng(e.latlng);
            $('#input-location').val(`${e.latlng.lat},${e.latlng.lng}`);
        }

        function onLocationError(e) {
            alert(e.message);
        }

        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
        map.on('click', onMapClick);
        map.locate({setView: true, maxZoom: 16});
        setTimeout(() => {
            $('#map').fadeOut('fats');
        }, 0);
    </script>
@stop
