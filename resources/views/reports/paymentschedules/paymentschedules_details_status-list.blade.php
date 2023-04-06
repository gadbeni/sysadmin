
<div class="col-md-12 text-right">
    @if (count($payments))
        <button type="button" onclick="report_print('pdf')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
        <button type="button" onclick="report_print('excel')" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Excel</button>
    @endif
</div>
@php
    $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
@endphp
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                @if ($grouped)
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>Dirección administrativa</th>
                            <th style="text-align: right">Nro personas</th>
                            <th style="text-align: right">Total días trabajados</th>
                            <th style="text-align: right">Total bono antigüedad</th>
                            <th style="text-align: right">Total ganado</th>
                            <th style="text-align: right">Riesgo común<br>(1.71%)</th>
                            <th style="text-align: right">Vivienda<br>(2%)</th>
                            <th style="text-align: right">Aporte patronal<br>(3%)</th>
                            <th style="text-align: right">Salud<br>(10%)</th>
                            <th style="text-align: right">Líquido pagable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @foreach ($payments->groupBy('paymentschedule.direccion_administrativa_id') as $item)
                            @php
                                $total_amount = $item->sum('partial_salary') + $item->sum('seniority_bonus_amount');
                            @endphp
                            <tr>
                                <td>{{ $cont }}</td>
                                <td>{{ count($item) > 0 ? $item[0]->paymentschedule->direccion_administrativa->nombre : '' }}</td>
                                <td style="text-align: right">{{ $item->count() }}</td>
                                <td style="text-align: right">{{ number_format($item->sum('partial_salary'), 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->sum('seniority_bonus_amount'), 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($total_amount, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->sum('common_risk'), 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->sum('housing_employer'), 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->sum('solidary_employer'), 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($total_amount *0.1, 2, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($item->sum('liquid_payable'), 2, ',', '.') }}</td>
                            </tr>
                            @php
                                $cont++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                @else
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>Planilla</th>
                            <th>Dirección administrativa</th>
                            <th>Unidad administrativa</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Nombre completo</th>
                            <th>CI</th>
                            <th>Cargo</th>
                            <th>Nivel</th>
                            <th>Sueldo</th>
                            <th>LÍquido pagable</th>
                            {{-- <th>Inicio</th>
                            <th>Fin</th>
                            <th>Programa</th>
                            <th>Cat. prog.</th> --}}
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                            $salary_total = 0;
                            $payable_total = 0;
                            $payment_total = 0;
                        @endphp
                        @forelse ($payments as $item)
                        {{-- {{ dd($item) }} --}}
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ str_pad($item->paymentschedule->id, 6, "0", STR_PAD_LEFT) }}</td>
                            <td>{{ $item->contract->direccion_administrativa ? $item->contract->direccion_administrativa->nombre : 'No definida' }}</td>
                            <td>{{ $item->contract->unidad_administrativa ? $item->contract->unidad_administrativa->nombre : '' }}</td>
                            <td>{{ $item->contract->code }}</td>
                            <td>{{ $item->contract->type->name }}</td>
                            <td>{{ $item->contract->person->last_name }} {{ $item->contract->person->first_name }}</td>
                            <td>{{ $item->contract->person->ci }}</td>
                            <td>{{ $item->job }}</td>
                            <td>{{ $item->job_level }}</td>
                            <td>
                                {{ number_format($item->salary, 2, ',', '.') }}
                                @php
                                    $salary_total += $item->salary;
                                @endphp
                            </td>
                            <td>
                                {{ number_format($item->liquid_payable, 2, ',', '.') }}
                                @php
                                    $payable_total += $item->liquid_payable;
                                @endphp
                            </td>
                            <td>
                                @if ($item->payment)
                                    <label class="label label-success">Pagada</label> <br> {{ date('d/m/Y', strtotime($item->payment->created_at)) }}
                                @endif
                            </td>
                        </tr>
                        @php
                            $cont++;
                            $payment_total += $item->payment ? $item->payment->amount : 0;
                        @endphp
                        @empty
                            <tr class="odd">
                                <td valign="top" colspan="12" class="text-center">No hay datos disponibles en la tabla</td>
                            </tr>
                        @endforelse

                        <tr>
                            <td colspan="10" class="text-right">Total</td>
                            <td>{{ number_format($salary_total, 2, ',', '.') }}</td>
                            <td>{{ number_format($payable_total, 2, ',', '.') }}</td>
                            <td>{{ number_format($payment_total, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

    })
</script>