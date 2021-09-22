@extends('voyager::master')

@section('page_title', 'Cheques de Planilla')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-window-list"></i> Cheques de Planilla
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            <a href="{{ route('checks.create') }}" class="btn btn-success btn-add-new">
                                <i class="voyager-plus"></i> <span>Agregar cheque</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover"></table>
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
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let columns = [
                { data: 'id', title: 'id' },
                { data: 'planilla_id', title: 'Planilla' },
                { data: 'amount', title: 'Monto' },
                { data: 'beneficiary', title: 'Beneficiario' },
                { data: 'date_print', title: 'Fecha de impresión' },
                { data: 'user', title: 'Resgistrado por' },
                { data: 'created_at', title: 'Creado' },
                { data: 'actions', title: 'Acciones' },
            ];
            customDataTable("{{ url('admin/social-security/checks/list') }}", columns);
        });
    </script>
@stop
