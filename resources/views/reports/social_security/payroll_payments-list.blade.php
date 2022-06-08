
<div class="col-md-12 text-right">
    @if (count($payroll_payment))
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button> --}}
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>PLANILLA</th>
                            <th>DIRECCIÓN ADMINISTRATIVA</th>
                            <th>TIPO DE PLANILLA</th>
                            <th>PERIODO</th>
                            <th>N&deg; PERSONAS</th>
                            <th>TOTAL GANADO</th>
                            <th>N&deg; FPC</th>
                            <th>N&deg; GTC-11</th>
                            <th>N&deg; DEPOSITO</th>
                            <th>USUARIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        @endphp
                        @forelse ($payroll_payment as $item)
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ str_pad($item->paymentschedule->id, 6, "0", STR_PAD_LEFT).($item->paymentschedule->aditional ? '-A' : '') }}</td>
                                <td>{{ $item->paymentschedule->direccion_administrativa->nombre }}</td>
                                <td>{{ $item->paymentschedule->procedure_type->name }}</td>
                                <td>{{ $item->paymentschedule->period->name }}</td>
                                <td>{{ $item->paymentschedule->details->where('contract.person.afp', $item->afp)->count() }}</td>
                                <td style="text-align: right">{{ number_format($item->paymentschedule->details->where('contract.person.afp', $item->afp)->sum('partial_salary') +$item->paymentschedule->details->where('contract.person.afp', $item->afp)->sum('seniority_bonus_amount'), 2, ',', '.') }}</td>
                                <td>
                                    @if($item->fpc_number)
                                        @php
                                            $date = $item->date_payment_afp ? date('d/m/Y', strtotime($item->date_payment_afp)) : 'Pendiente';
                                        @endphp
                                        {{ $item->fpc_number }} <br> {{ $date }}
                                    @endif
                                </td>
                                <td>{{ $item->gtc_number }}</td>
                                <td>
                                    @if($item->deposit_number)
                                        @php
                                            $date = $item->date_payment_cc ? date('d/m/Y', strtotime($item->date_payment_cc)) : 'Pendiente';
                                        @endphp
                                        {{ $item->deposit_number }} <br> {{ $date }}
                                    @endif
                                </td>
                                <td>{{ $item->user->name }} <br> {{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @empty
                            <tr>
                                <td class="text-center" colspan="11">No se encontraron resulatados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* th{
        font-size: 8px
    }
    td{
        font-size: 11px
    } */
</style>

<script>
    $(document).ready(function(){

    })
</script>