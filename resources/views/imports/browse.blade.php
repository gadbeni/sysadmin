@extends('voyager::master')

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
                                    <select name="type" id="select-type" class="form-control select2" required>
                                        <option value="">--Seleccione el tipo de archivo--</option>
                                        <option value="funcionamiento">Personal de funcionamiento</option>
                                        <option value="bono antiguedad">Bono antigüedad</option>
                                        <option value="estructura permanente" data-type="estructure-image">Estructura permanente</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="file">Archivo</label>
                                    <input type="file" name="file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                                </div>
                                <div class="col-md-6">
                                    <img class="sample-image" id="estructure-image" src="{{ asset('images/samples/formato_estructura.png') }}" width="100%" alt="Formato de estructura">
                                </div>
                                <div class="form-group col-md-6 text-right">
                                    <button type="submit" id="btn-submit" class="btn btn-success">Importar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .sample-image{
            display: none
        }
    </style>
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            $('#select-type').change(function(){
                let type = $('#select-type option:selected').data('type');
                $('.sample-image').css('display', 'none');
                if(type){
                    $(`#${type}`).css('display', 'block');
                }
            });
            $('#form-generate').submit(function(){
                $('#btn-submit').attr('disabled', 'disabled');
            });
        });
    </script>
@stop
