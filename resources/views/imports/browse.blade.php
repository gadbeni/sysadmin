@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Importación de Datos')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-file-text"></i>
        Importación de Datos
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form id="form-generate" action="{{ route('imports.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="type">Tipo de archivo</label>
                                    <select name="type" class="form-control select2" required>
                                        <option value="">--Seleccione el tipo de archivo--</option>
                                        <option value="bono antiguedad">Bono antigüedad</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="file">Archivo</label>
                                    <input type="file" name="file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                                </div>
                                <div class="form-group col-md-12 text-right">
                                    <button type="submit" class="btn btn-success">Generar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            
        });
    </script>
@stop
