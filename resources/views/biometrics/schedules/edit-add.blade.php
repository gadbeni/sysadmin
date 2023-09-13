@extends('voyager::master')

@section('page_title', 'Añadir Horario')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-calendar"></i>
        Añadir Horario
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form class="form-submit" action="{{ isset($schedule) ? route('schedules.update', $schedule->id) : route('schedules.store') }}" method="POST">
                        @csrf
                        @isset ($schedule)
                            @method('put')
                        @endif
                        <div class="panel-body">
                            <div class="form-group col-md-12">
                                <label class="control-label" for="name">Nombre</label>
                                <input type="text" class="form-control" name="name" placeholder="Nombre" value="{{ isset($schedule) ? $schedule->name : '' }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="description">Descripción</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Descripción">{{ isset($schedule) ? $schedule->description : '' }}</textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <br>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Día(s)</th>
                                            <th style="width: 150px">Entrada</th>
                                            <th style="width: 150px">Salida</th>
                                            <th style="width: 150px">Entrada</th>
                                            <th style="width: 150px">Salida</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="days" class="form-control select2" {{ isset($schedule) ? '' : 'required' }}>
                                                    <option value="">--Seleccionar una opción--</option>
                                                    <option value="5">Lunes a Viernes</option>
                                                    <option value="6">Lunes a Sábado</option>
                                                </select>
                                            </td>
                                            <td><input type="time" name="entry_1" class="form-control" {{ isset($schedule) ? '' : 'required' }}></td>
                                            <td><input type="time" name="exit_1" class="form-control" {{ isset($schedule) ? '' : 'required' }}></td>
                                            <td><input type="time" name="entry_2" class="form-control"></td>
                                            <td><input type="time" name="exit_2" class="form-control"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary btn-submit">{{ isset($schedule) ? 'Editar' : 'Guardar' }} <i class="voyager-check"></i> </button>
                        </div>
                    </form>
                </div>
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

        });
    </script>
@stop
