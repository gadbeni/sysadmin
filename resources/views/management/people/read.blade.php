@extends('voyager::master')

@section('page_title', 'Ver Persona')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-people"></i> Viendo Persona
        <a href="{{ route('voyager.people.index') }}" class="btn btn-warning">
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
                                <h3 class="panel-title">Nombre(s)</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->first_name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Apellidos</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->last_name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">CI</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->ci }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de nacimiento</h3>
                            </div>
                            @php
                                $now = \Carbon\Carbon::now();
                                $birthday = new \Carbon\Carbon($person->birthday);
                                $age = $birthday->diffInYears($now);
                            @endphp
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ date('d/m/Y', strtotime($person->birthday)) }} - {{ $age }} años</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Profesión</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->profession }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Telefono</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->phone ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->address }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Email</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->email ?? 'No definido' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Género</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->gender }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Estado civil</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->civil_status == 1 ? 'Soltero(a)' : 'Casado(a)' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">AFP</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->afp == 1 ? 'Futuro' : 'BBVA Previsión' }} @if($person->afp_status == 0) <label class="label label-danger">Jubilado</label>@endif</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">NUA/CUA</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->nua_cua }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">N&deg; de cuenta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $person->number_account }}</p>
                            </div>
                            <hr style="margin:0;">
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
                                <h3 class="panel-title">Historial de inamovilidades</h3>
                            </div>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Tipo</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Observaciones</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @forelse ($person->irremovabilities as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->type->name }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->start)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->finish)) }}</td>
                                            <td>{{ $item->observations }}</td>
                                            <td class="text-right">
                                                
                                            </td>
                                        </tr>
                                        @php
                                            $cont++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="6">No hay datos disponible</td>
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
                                <h3 class="panel-title">Historial de rotaciones</h3>
                            </div>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Fecha</th>
                                        <th>Solicitante</th>
                                        <th>Destino</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 0;
                                    @endphp
                                    @foreach ($person->contracts as $contract)
                                        @foreach ($contract->rotations as $rotation)
                                            @php
                                                $cont++;
                                            @endphp
                                            <tr>
                                                <td>{{ $cont }}</td>
                                                <td>{{ date('d/m/Y', strtotime($rotation->date)) }}</td>
                                                <td>{{ $rotation->destiny->first_name }}</td>
                                                <td>{{ $rotation->destiny_dependency }}</td>
                                                <td class="text-right">
                                                    <a href="{{ url('admin/people/rotation/'.$rotation->id) }}" class="btn btn-default btn-sm" target="_blank"><i class="glyphicon glyphicon-print"></i> Imprimir</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    @if ($cont == 0)
                                        <tr>
                                            <td colspan="5">No hay datos disponible</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            
        });
    </script>
@stop
