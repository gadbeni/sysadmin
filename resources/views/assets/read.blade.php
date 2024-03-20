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
                                        <th>Código</th>
                                        <th>Tipo</th>
                                        <th>Técnico</th>
                                        <th>Detalles</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($asset->maintenances->sortByDesc('date_start') as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->date_start == $item->date_finish ? date('d/m/Y', strtotime($item->date_start)) : date('d/m/Y', strtotime($item->date_start)).' - '.date('d/m/Y', strtotime($item->date_finish)) }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ ucfirst($item->type) }}</td>
                                            <td>
                                                @if ($item->technical)
                                                    {{ $item->technical->person->first_name }} {{ $item->technical->person->last_name }} <br>
                                                    <small>CI: <b>{{ $item->technical->person->ci }}</b> Telf: <b>{{ $item->technical->person->phone ?? 'Ninguno' }}</b></small>
                                                @else
                                                    No definido
                                                @endif
                                            </td>
                                            <td>
                                                <ul>
                                                    @foreach ($item->details as $detail)
                                                    @if ($detail->type)
                                                        <li>{{ $detail->type->name }}</li>                                                        
                                                    @else
                                                        <li>{{ $detail->observations }}</li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if (!$item->destiny)
                                                    <a href="#" title="Generar informe" class="btn btn-sm btn-primary btn-add-maintenances-report" data-id="{{ $item->id }}" data-reference="{{ 'MANTENIMIENTO '.Str::upper($item->type) }}" data-toggle="modal" data-target="#modal-add-maintenances-report">
                                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Informe</span>
                                                    </a>
                                                @endif
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                        Imprimir <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                                        <li><a href="{{ route('maintenances.print', ['id' => $item->id]) }}" title="Imprimir acta de trabajo" target="_blank">Acta de trabajo</a></li>
                                                        @if ($item->destiny)
                                                        <li><a href="{{ route('maintenances.print', ['id' => $item->id, 'type' => 'report']) }}" title="Imprimir informe" target="_blank">Informe</a></li>
                                                        @endif
                                                    </ul>
                                                </div>
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

    {{-- Crear mantenimiento --}}
    <form class="form-submit" id="maintenances-form" action="{{ route('maintenances.store') }}" enctype="multipart/form-data" method="post">
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
                            <div class="form-group col-md-12">
                                <label>Estado de ingreso</label>
                                <textarea name="income_status" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Procedencia</label>
                                <input type="text" name="origin" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Tipo de mantenimiento</label> <br>
                                <label class="radio-inline"><input type="radio" name="type" value="preventivo" checked>Preventivo</label>
                                <label class="radio-inline"><input type="radio" name="type" value="correctivo">Correctivo</label>
                                <label class="radio-inline"><input type="radio" name="type" value="planificado">Planificado</label>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Trabajo realizado</label>
                                <div style="padding: 10px 20px; border: 1px solid #aaa">
                                    @php
                                        $types = App\Models\assetMaintenanceType::where('status', 1)->get();
                                    @endphp
                                    @foreach ($types->sortBy('category')->groupBy('category')->chunk(3) as $chunk)
                                        <div class="row">
                                            @foreach ($chunk as $key => $item)
                                                <div class="col-md-4">
                                                    <b>{{ Str::upper($key) }}</b> <br>
                                                    @foreach ($item as $type)
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" name="asset_maintenance_type_id[]" value="{{ $type->id }}">{{ $type->name }}</label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="checkbox-inline"><input type="checkbox" value="" id="checkbox-another">Otro</label>
                                            <div id="div-another" style="display: none">
                                                <textarea name="details_observations" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Fecha de ingreso</label>
                                <input type="date" name="date_start" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Fecha de salida</label>
                                <input type="date" name="date_finish" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Lugar de trabajo</label> <br>
                                <label class="radio-inline"><input type="radio" name="work_place" value="1" checked>Unidad de sistemas</label>
                                <label class="radio-inline"><input type="radio" name="work_place" value="2">Oficina del funcionario</label>
                            </div>
                            {{-- <div class="form-group col-md-12">
                                <label>Fotografía (opcional)</label>
                                <input type="file" name="file" class="form-control" accept="image/jpg, image/jpeg, image/png">
                            </div> --}}
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

    {{-- Agregar informe a mantenimiento --}}
    <form class="form-submit" id="maintenances-report-form" action="{{ route('maintenances.report.store') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
        <div class="modal modal-primary fade modal-option" tabindex="-1" id="modal-add-maintenances-report" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-list"></i> Agregar informe</h4>
                    </div>
                    <div class="modal-body">
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
                            <label>Informe</label>
                            <textarea name="report" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Sugerencias</label>
                            <textarea name="observations" class="form-control" rows="3"></textarea>
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
            customSelect('#select-destiny_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, $('#modal-add-maintenances-report'));
            customSelect('#select-supervisor_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, $('#modal-add-maintenances-report'));

            $('#checkbox-another').click(function(){
                $('#maintenances-form textarea[name="observations"]').val('');
                if($(this).is(':checked')){
                    $('#div-another').fadeIn('fast');
                }else{
                    $('#div-another').fadeOut('fast');
                }
            });

            $('.btn-add-maintenances-report').click(function(){
                let id = $(this).data('id');
                let reference = $(this).data('reference');
                $('#maintenances-report-form input[name="id"]').val(id);
                $('#maintenances-report-form input[name="reference"]').val(reference);
            });
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
