@extends('voyager::master')

@section('page_title', 'Ver Activo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-tag"></i> Viendo Activo &nbsp;
        @if (auth()->user()->hasPermission('edit_assets'))
        <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-info">
            <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">Editar</span>
        </a>&nbsp;
        @endif
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
                                <h3 class="panel-title">Imágenes</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                @foreach (json_decode($asset->images) as $image)
                                <img src="{{ asset('storage/'.str_replace('.', '-cropped.', $image)) }}" alt="Imagen" style="width: 70px">    
                                @endforeach
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observaciones</h3>
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
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="panel-title">Historial de custodio</h3>
                                    </div>
                                    <div class="col-md-4 text-right" style="padding-top: 20px">
                                        {{-- @if (auth()->user()->hasPermission('add_file_people'))
                                        <a href="#" class="btn btn-success btn-add-file" data-url="{{ route('people.file.store', ['id' => $person->id]) }}" data-toggle="modal" data-target="#modal-add-file" ><i class="voyager-plus"></i> <span>Agregar</span></a>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>ID</th>
                                        <th>Código de custodio</th>
                                        <th>Nombre completo</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                        <th>Registrado</th>
                                        {{-- <th class="text-right">Acciones</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($asset->assignments->sortByDesc('id') as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->person_asset->code }}</td>
                                            <td>
                                                {{ $item->person_asset->person->first_name }} {{ $item->person_asset->person->first_name }} <br>
                                                <small>{{ $item->person_asset->person->ci }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    if($item->status == "bueno"){
                                                        $class = 'success';
                                                    }elseif($item->status == "regular"){
                                                        $class = 'warning';
                                                    }elseif($item->status == "malo"){
                                                        $class = 'danger';
                                                    }else{
                                                        $item->status = 'desconocido';
                                                        $class = 'default';
                                                    }
                                                @endphp
                                                <label class="label label-{{ $class }}">{{ Str::ucfirst($item->status) }}</label>
                                            <td>{{ $item->observations }}</td>
                                            <td>
                                                {{ $item->person_asset->user ? $item->person_asset->user->name : '' }} <br>
                                                {{ date('d/m/Y H:i', strtotime($item->person_asset->created_at)) }} <br>
                                                <small>{{ \Carbon\Carbon::parse($item->person_asset->created_at)->diffForHumans() }}</small>
                                            </td>
                                            {{-- <td class="no-sort no-click bread-actions text-right">
                                                @if (auth()->user()->hasPermission('delete_file_people'))
                                                <button type="button" onclick="deleteItem('{{ route('people.file.delete', ['people' => $person->id, 'id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger delete">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                </button>
                                                @endif
                                            </td> --}}
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="5">No hay datos disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="panel-title">Historial de mantenimientos</h3>
                                    </div>
                                    <div class="col-md-4 text-right" style="padding-top: 20px">
                                        @if (auth()->user()->hasPermission('add_assets_maintenances'))
                                        <a href="#" class="btn btn-success btn-add-maintenances" data-toggle="modal" data-target="#modal-add-maintenances" ><i class="voyager-plus"></i> <span>Agregar</span></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Código de mantenimiento</th>
                                        <th>Técnico</th>
                                        <th>Diagnóstico</th>
                                        <th>Observaciones</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($asset->maintenances as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>
                                                @if ($item->technical)
                                                    {{ $item->technical->person->first_name }} {{ $item->technical->person->last_name }} <br>
                                                    <small>CI: <b>{{ $item->technical->person->ci }}</b> Telf: <b>{{ $item->technical->person->phone ?? 'Ninguno' }}</b></small>
                                                @else
                                                    No definido
                                                @endif
                                            </td>
                                            <td>{{ strlen($item->details) <= 100 ? $item->details : substr($item->details, 0, 100).'...' }}</td>
                                            <td>
                                                @if ($item->observations)
                                                {{ strlen($item->observations) <= 100 ? $item->observations : substr($item->observations, 0, 100).'...' }}
                                                @endif
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                <a href="{{ route('maintenances.print', $item->id) }}" title="Imprimir" class="btn btn-sm btn-default" target="_blank">
                                                    <i class="fa fa-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="8">No hay datos disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form class="form-submit" id="maintenances-form" action="{{ route('maintenances.store') }}" method="post">
        @csrf
        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
        <div class="modal modal-primary fade modal-option" tabindex="-1" id="modal-add-maintenances" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-wrench"></i> Registrar mantenimiento</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Destinatario</label>
                                <select name="destiny_id" id="select-destiny_id" class="form-control" required></select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Vía</label>
                                <select name="supervisor_id" id="select-supervisor_id" class="form-control" required></select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Referencia</label>
                                <input type="text" name="reference" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Diagnóstico</label>
                                <textarea name="details" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Observaciones</label>
                                <textarea name="observations" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Fecha</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group col-md-12 text-right">
                                <label class="checkbox-inline"><input type="checkbox" value="1" name="check" required> Aceptar y guardar</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark btn-submit" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet-1.9.4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/input-tags/dist/css/selectize.default.css') }}">
    <style>
        .form-control {
            text-transform: uppercase
        }
        #map {
            height: 320px;
            margin-top: 20px
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        moment.locale('es');
        $(document).ready(function () {
            customSelect('#select-destiny_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, $('#modal-add-maintenances'));
            customSelect('#select-supervisor_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, $('#modal-add-maintenances'));
        });
    </script>
    <script src="{{ asset('vendor/leaflet/leaflet-1.9.4.js') }}"></script>
    @if ($asset->subcategory->assets_category_id == 1)
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
    @endif
@stop
