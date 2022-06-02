@extends('voyager::master')

@section('page_title', 'Viendo Registros')

{{-- @section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-treasure"></i> List
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop --}}

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <tbody>
                                    @php
                                        $cont = 1;
                                    @endphp
                                    @foreach ($contarcts as $item)
                                        @if ($item->contracts->count() > 1)
                                            @php
                                                $job_id = $item->contracts[0]->job_id;
                                                $cargo_id = $item->contracts[0]->cargo_id;
                                                $find = false;
                                                for ($i = 1; $i < $item->contracts->count(); $i++){
                                                    if($job_id != $item->contracts[$i]->job_id ||  $cargo_id != $item->contracts[0]->cargo_id){
                                                        $find = true;
                                                    }
                                                }
                                            @endphp
                                            @if ($find)
                                                <tr>
                                                    <td>{{ $cont }}</td>
                                                    <td>{{ $item->last_name }}</td>
                                                    <td>{{ $item->first_name }}</td>
                                                    <td>{{ $item->ci }}</td>
                                                    <td>
                                                        <table class="table">
                                                            @foreach ($item->contracts as $contract)
                                                                {{-- {{ dd($contract) }} --}}
                                                                <tr>
                                                                    <td>
                                                                        {{ $contract->job ? $contract->job->name : $contract->cargo->Descripcion }} <br>
                                                                        <small>{{ $contract->direccion_administrativa->nombre }} - {{ $contract->unidad_administrativa ? $contract->unidad_administrativa->nombre : 'NN' }}</small>
                                                                    </td>
                                                                    <td style="text-align: right">
                                                                        @if ($contract->cargo)
                                                                            {{ number_format($contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo, 2, ',', '.') }}
                                                                        @elseif ($contract->job)
                                                                            {{ number_format($contract->job->salary, 2, ',', '.') }}
                                                                        @else
                                                                            0.00
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                @php
                                                    $cont++;
                                                @endphp
                                            @endif
                                        @endif
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

        });
    </script>
@stop
