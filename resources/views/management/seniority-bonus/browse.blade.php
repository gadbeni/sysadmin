@extends('voyager::master')

@section('page_title', 'Viendo Bonos antigüedad')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-calendar"></i> Bonos antigüedad
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if(auth()->user()->hasPermission('add_seniority_bonus_people'))
                                <a href="{{ route('voyager.seniority-bonus-people.create') }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
                                </a>
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
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Periodo</th>
                                        <th>Persona</th>
                                        <th>Planilla</th>
                                        <th>Inicio</th>
                                        {{-- <th>Observaciones</th> --}}
                                        <th>Estado</th>
                                        <th>Registrado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->type->description }}</td>
                                            <td>
                                                {{ $item->person->first_name }} {{ $item->person->last_name }} <br>
                                                <small><b>{{ $item->person->ci }}</b></small> <br>
                                            </td>
                                            <td>
                                                @php
                                                    $contract = $item->person->contracts->count() > 0 ? $item->person->contracts[0] : null
                                                @endphp
                                                {{ $contract ? $contract->type->name : 'Sin contrato vigente' }}
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                                            {{-- <td>{{ $item->observations }}</td> --}}
                                            <td>
                                                <label class="badge badge-{{ $item->status ? 'success' : 'danger' }}">{{ $item->status ? 'Activo' : 'Inactivo' }}</label>
                                            </td>
                                            <td>
                                                {{ $item->user ? $item->user->name : '' }} <br>
                                                {{ date('d/m/Y H:i', strtotime($item->created_at)) }} <br>
                                                <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                                            </td>
                                            <td class="no-sort no-click bread-actions text-right">
                                                @if (auth()->user()->hasPermission('read_seniority_bonus_people'))
                                                <a href="{{ route('voyager.seniority-bonus-people.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                </a>
                                                @endif
                                                @if (auth()->user()->hasPermission('edit_seniority_bonus_people'))
                                                <a href="{{ route('voyager.seniority-bonus-people.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                </a>
                                                @endif
                                                @if (auth()->user()->hasPermission('delete_seniority_bonus_people'))
                                                <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('voyager.seniority-bonus-people.destroy', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
            $('#dataTable').DataTable({language, order: [[ 0, 'desc' ]],});
        });
    </script>
@stop
