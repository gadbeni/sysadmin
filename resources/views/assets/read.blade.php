@extends('voyager::master')

@section('page_title', 'Ver Activo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-tag"></i> Viendo Activo
        <a href="{{ route('assets.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Categoría</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->subcategory->category->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Subcatgoría</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->subcategory->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Código</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->code }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Código SIAF</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->code_siaf ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Código interno</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->code_internal ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Etiquetas</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                @if ($asset->tags)
                                    <p>
                                        @foreach (explode(',', $asset->tags) as $item)
                                        <label class="label label-default">{{ $item }}</label>
                                        @endforeach
                                    </p>
                                @else
                                    <p>Ninguna</p>
                                @endif
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->description }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Precio inicial</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->initial_price	?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Precio actual</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->current_price ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">observaciones</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->observations ?? 'Ninguna' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        {{-- Si el activo es un inmueble --}}
                        @if ($asset->subcategory->assets_category_id == 1)
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Ciudad/Localidad</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->city ? $asset->city->name.($asset->city->province ? '/'.$asset->city->province : '') : 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $asset->address ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>    
                        @endif
                        @if ($asset->location)
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Ubicación</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="form-group col-md-12" id="map"></div>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet-1.9.4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/input-tags/dist/css/selectize.default.css') }}">
    <style>
        #map {
            height: 320px;
            margin-top: 20px
        }
    </style>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            
        });
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
            @isset($asset->location)
                let location = "{{ $asset->location }}";
                locationMarker = L.marker({lat: location.split(',')[0], lng: location.split(',')[1]}).addTo(map);
                map.setView(new L.LatLng(location.split(',')[0], location.split(',')[1]), 13, { animation: true });
            @else
                locationMarker = L.marker(e.latlng).addTo(map);
            @endisset
            
        }

        function onLocationError(e) {
            alert(e.message);
        }

        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);
        map.locate({setView: true, maxZoom: 16});
    </script>
@stop
