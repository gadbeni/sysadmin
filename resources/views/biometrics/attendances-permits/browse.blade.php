@extends('voyager::master')

@section('page_title', 'Viendo Permisos/Licencias')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-custom">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-documentation"></i> Permisos/Licencias
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_attendances-permits'))
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="voyager-plus"></i> Crear <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu" style="left: -170px !important; top: 0px !important;">
                                        <li><a href="{{ route('attendances-permits.create') }}" title="Permiso a un solo funcionario">Personal</a></li>
                                        <li><a href="#" title="Agregar permiso masivo">Grupal</a></li>
                                    </ul>
                                </div>
                            @endif
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
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .panel-custom{
            box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;
        }
    </style>
@stop

@section('javascript')
    <script>
        $(document).ready(function() {

        });
    </script>
@stop
