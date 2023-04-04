@extends('voyager::master')

@section('page_title', 'Reporte de Contratos')

@if(auth()->user()->hasPermission('browse_reportscontractscontracts'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-8" style="padding: 0px">
                                <h1 class="page-title">
                                    <i class="voyager-certificate"></i> Reporte de Contratos
                                </h1>
                                {{-- <div class="alert alert-info">
                                    <strong>Información:</strong>
                                    <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                                </div> --}}
                            </div>
                            <div class="col-md-4" style="margin-top: 30px">
                                <form name="form_search" id="form-search" action="{{ route('reports.contracts.contracts.list') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="type">
                                    <div class="form-group col-md-12">
                                        <select name="procedure_type_id" class="form-control select2">
                                            <option selected value="">Todos los tipos de contratos</option>
                                            @foreach (App\Models\ProcedureType::where('deleted_at', NULL)
                                                        ->whereRaw(Auth::user()->role_id == 16 || Auth::user()->role_id == 25 ? 'id = 2' : 1)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <select name="status" class="form-control select2">
                                            <option value="">Todos</option>
                                            <option value="elaborado">Elaborados</option>
                                            <option value="enviado">Enviado</option>
                                            <option value="firmado">Firmado</option>
                                            <option value="concluido">Concluido</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <select name="direcciones_tipo_id" id="select-direcciones_tipo_id" class="form-control select2">
                                            @if (!Auth::user()->direccion_administrativa_id)
                                            <option value="">Todos los tipos de DA</option>
                                            @endif
                                            @foreach (App\Models\DireccionesTipo::with(['direcciones_administrativas' => function($q){
                                                            $q->whereRaw("estado = 1 and deleted_at is null");
                                                        }])->whereHas('direcciones_administrativas', function($q){
                                                            $q->whereRaw(Auth::user()->direccion_administrativa_id ? "id = ".Auth::user()->direccion_administrativa_id : 1);
                                                        })->where('estado', 1)->where('deleted_at', NULL)->get() as $item)
                                            <option value="{{ $item->id }}" data-direcciones='@json($item->direcciones_administrativas)'>{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <select name="direccion_administrativa_id[]" id="select-direccion_administrativa_id" class="form-control select2" multiple @if(Auth::user()->direccion_administrativa_id) required @endif>
                                            @if (!Auth::user()->direccion_administrativa_id)
                                            <option value="">Todas las direcciones administrativas</option>
                                            @endif
                                            @foreach (App\Models\Direccion::where('estado', 1)->whereRaw(Auth::user()->direccion_administrativa_id ? "id = ".Auth::user()->direccion_administrativa_id : 1)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="number" name="year" class="form-control" placeholder="Año" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select name="month" class="form-control select2" required>
                                            {{-- <option value="">--Todos los meses--</option> --}}
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                    <br>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('content')
        <div class="page-content browse container-fluid">
            @include('voyager::alerts')
            <div class="row">
                <div id="div-results" style="min-height: 100px"></div>
            </div>
        </div>
    @stop

    @section('css')

    @stop

    @section('javascript')
        <script src="{{ url('js/main.js') }}"></script>
        <script>
            $(document).ready(function() {

                $('#select-direcciones_tipo_id').change(function(){
                    let direcciones = $('#select-direcciones_tipo_id option:selected').data('direcciones');
                    if (direcciones) {
                        $('#select-direccion_administrativa_id').empty()
                        direcciones.map(item => {
                            $('#select-direccion_administrativa_id').append(`<option value="${item.id}">${item.nombre}</option>`)
                        })
                    }
                    
                });

                $('#form-search').on('submit', function(e){
                    e.preventDefault();
                    $('#div-results').empty();
                    $('#div-results').loading({message: 'Cargando...'});
                    $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                        $('#div-results').html(res);
                    })
                    .fail(function() {
                        toastr.error('Ocurrió un error!', 'Oops!');
                    })
                    .always(function() {
                        $('#div-results').loading('toggle');
                        $('html, body').animate({
                            scrollTop: $("#div-results").offset().top - 70
                        }, 500);
                    });
                });
            });

            function report_export(type){
                $('#form-search').attr('target', '_blank');
                $('#form-search input[name="type"]').val(type);
                window.form_search.submit();
                $('#form-search').removeAttr('target');
                $('#form-search input[name="type"]').val('');
            }
        </script>
    @stop
    
@else
    @section('content')
        @include('errors.403')
    @stop
@endif