@extends('voyager::master')

@section('page_title', 'Ver planilla de aguinaldo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo planilla de aguinaldo
        <a href="{{ route('bonuses.index') }}" class="btn btn-warning">
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
                                <h3 class="panel-title">Dirección Administrativa</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $bonus->direccion->nombre }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-6">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Gestión</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $bonus->year }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>
                </div>
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th>Planilla</th>
                                        <th>Nombre completo</th>
                                        <th>CI</th>
                                        <th>Sueldo</th>
                                        <th>Días de contrato</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cont = 1;
                                        $total = 0;
                                    @endphp
                                    @foreach ($bonus->details as $item)
                                        <tr>
                                            <td>{{ $cont }}</td>
                                            <td>{{ $item->procedure_type->name }}</td>
                                            <td>
                                                {{ $item->contract->person->first_name }} {{ $item->contract->person->last_name }} <br>
                                                @if ($item->contract->cargo)
                                                    <b>{{ $item->contract->cargo->Descripcion }}</b>
                                                @elseif($item->contract->job)
                                                    <b>{{ $item->contract->job->name }}</b>
                                                @endif
                                            </td>
                                            <td>{{ $item->contract->person->ci }}</td>
                                            <td class="text-right">{{ $item->salary }}</td>
                                            <td class="text-right">{{ $item->days }}</td>
                                            <td class="text-right">{{ $item->amount }}</td>
                                        </tr>
                                        @php
                                            $cont++;
                                            $total += $item->amount;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class="text-right"><b>TOTAL</b></td>
                                        <td class="text-right"><b>{{ number_format($total, 2, ',', '.') }}</b></td>
                                    </tr>
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
