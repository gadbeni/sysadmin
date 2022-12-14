@extends('voyager::master')

@section('page_title', 'Ver planilla de aguinaldo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i> Viendo planilla de aguinaldo
        <a href="{{ route('bonuses.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a>
        <button class="btn btn-danger" data-toggle="modal" data-target="#print-modal"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
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
                                        <th>PLANILLA</th>
                                        <th>NOMBRE COMPLETO</th>
                                        <th>CI</th>
                                        <th>MESES</th>
                                        <th>SUELDO PROMEDIO</th>
                                        <th>DÍAS TRABAJADOS</th>
                                        <th>INICIO</th>
                                        <th>FIN</th>
                                        <th>AGUINALDO</th>
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
                                            <td>
                                                <table class="table">
                                                    <tr>
                                                        <td>{{ number_format($item->partial_salary_1 + $item->seniority_bonus_1, 2, ',', '.') }}</td>
                                                        <td>{{ number_format($item->partial_salary_2 + $item->seniority_bonus_2, 2, ',', '.') }}</td>
                                                        <td>{{ number_format($item->partial_salary_3 + $item->seniority_bonus_3, 2, ',', '.') }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $promedio = ($item->partial_salary_1 + $item->seniority_bonus_1 + $item->partial_salary_2 + $item->seniority_bonus_2 + $item->partial_salary_3 + $item->seniority_bonus_3) /3;
                                                @endphp
                                                {{ number_format($promedio, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">{{ $item->days }}</td>
                                            @php
                                                $start_contract = $item->contract;
                                                $aux = true;
                                                while ($aux) {
                                                    $contract = App\Models\Contract::where('person_id', $start_contract->person_id)
                                                                ->where('finish', '<', $start_contract->start)
                                                                ->orderBy('finish', 'DESC')->where('deleted_at', NULL)->first();
                                                    if($contract){
                                                        $current_start = Carbon\Carbon::createFromFormat('Y-m-d', $item->contract->start);
                                                        $new_finish = Carbon\Carbon::createFromFormat('Y-m-d', $contract->finish);
                                                        if($current_start->diffInDays($new_finish) == 1){
                                                            $start_contract = $contract;
                                                        }else{
                                                            $aux = false;
                                                        }
                                                    }else{
                                                        $aux = false;
                                                    }
                                                }
                                            @endphp
                                            <td>{{ date('d-m-Y', strtotime($start_contract->start)) }}</td>
                                            <td>{{ $item->contract->finish ? date('d-m-Y', strtotime($item->contract->finish)) : '' }}</td>
                                            <td class="text-right">{{ number_format(($promedio / 360) * $item->days, 2, ',', '.') }}</td>
                                        </tr>
                                        @php
                                            $cont++;
                                            $total += ($promedio / 360) * $item->days;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="9" class="text-right"><b>TOTAL</b></td>
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

    {{-- print modal --}}
    <form id="form-print" action="{{ route('bonuses.print', $bonus->id) }}" method="post" target="_blank">
        @csrf
        <div class="modal modal-danger fade" tabindex="-1" id="print-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="glypicon glypicon-print"></i> Imprimir planilla de aguinaldos</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="procedure_type_id">Tipo de planilla</label>
                                <select name="procedure_type_id" class="form-control select2">
                                    <option value="1">Permanente</option>
                                    <option value="5">Eventual</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="radio-inline"><input type="radio" name="type_render" value="1" checked>Generar PDF</label>
                                <label class="radio-inline"><input type="radio" name="type_render" value="2">HTML</label>
                                {{-- <label class="radio-inline"><input type="radio" name="type_render" value="3">Excel</label> --}}
                            </div>
                            <div class="form-group col-md-12 checkbox">
                                <label><input type="checkbox" name="signature_field" value="1">Imprimir el espacio donde va la firma</label>
                              </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-danger" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        th{
            text-align: center
        }
    </style>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            
        });
    </script>
@stop
