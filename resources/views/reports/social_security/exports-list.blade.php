
<div class="col-md-12 text-right">
    @if (count($data))
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button> --}}
        <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>CÓDIGO DE ENTIDAD</th>
                            <th>TIPO DE PLANILLA</th>
                            <th>FUENTE</th>
                            <th>ORGANISMO</th>
                            <th>GESTION</th>
                            <th>MES</th>
                            <th>CARNET DE  IDENTIDAD</th>
                            <th>NUMERO COMPLEMENTARIO</th>
                            <th>EXPEDIDO</th>
                            <th>NOMBRE 1</th>
                            <th>NOMBRE 2</th>
                            <th>APELLIDO 1</th>
                            <th>APELLIDO 2</th>
                            <th>APELLIDO 3</th>
                            <th>FECHA DE NACIMIENTO</th>
                            <th>FECHA DE INGRESO</th>
                            <th>SEXO</th>
                            <th>ITEM</th>
                            <th>CARGO</th>
                            <th>TIPO EMPLEADO</th>
                            <th>GRADO DE FORMACIÓN</th>
                            <th>DÍAS TRABAJADOS</th>
                            <th>BÁSICO (Bs.)</th>
                            <th>BONO ANTIGÜEDAD (Bs.)</th>
                            <th>OTROS INGRESOS (Bs.)</th>
                            <th>TOTAL GANADO (Bs.)</th>
                            <th>APORTE AL SEGURO SOCIAL OBLIGATORIO A LARGO PLAZO (SSO) BS.</th>
                            <th>OTROS DESCUENTOS (Bs.)</th>
                            <th>APORTE SOLIDARIO DEL ASEGURADO 0.5%  (Bs.)</th>
                            <th>APORTE PATRONAL SOLIDARIO  3% (Bs.)</th>
                            <th>LIQUIDO PAGABLE (Bs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                        @php
                        // dd($item);
                            $period = \App\Models\Period::findOrFail($item->paymentschedule->period_id);
                            $year = Str::substr($period->name, 0, 4);
                            $month = Str::substr($period->name, 4, 2);
                            // dd($item->contract);
                        @endphp
                        <tr>
                            <td>908</td>
                            <td>PAGO DE HABERES</td>
                            <td>20 RECESP</td>
                            <td>220 REG</td>
                            <td>{{ $year }}</td>
                            <td>{{ $month }}</td>
                            <td>{{ explode('-', $item->contract->person->ci)[0] }}</td>
                            <td>{{ count(explode('-', $item->contract->person->ci)) > 1 ? explode('-', $item->contract->person->ci)[1] : '' }}</td>
                            <td>OTRO</td>
                            <td>{{ explode(' ', $item->contract->person->first_name)[0] }}</td>
                            <td>{{ count(explode(' ', $item->contract->person->first_name)) > 1 ? explode(' ', $item->contract->person->first_name)[1] : '' }}</td>
                            <td>{{ explode(' ', $item->contract->person->last_name)[0] }}</td>
                            <td>{{ count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : '' }}</td>
                            <td></td>
                            <td>{{ date('d/m/Y', strtotime($item->contract->person->birthday)) }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->contract->start)) }}</td>
                            <td>{{ $item->contract->person->gender == 'masculino' ? 'M' : 'F' }}</td>
                            <td>{{ $item->contract->job_id }}</td>
                            <td>{{ $item->job }}</td>
                            <td>{{ Str::upper($item->contract->type->name) }}</td>
                            <td>NO EXISTE INFORMACION</td>
                            <td>{{ $item->worked_days }}</td>
                            <td>{{ number_format($item->salary, 2, ',', '') }}</td>
                            <td>{{ number_format($item->seniority_bonus_amount, 2, ',', '') }}</td>
                            <td>0</td>
                            <td>{{ number_format($item->partial_salary, 2, ',', '') }}</td>
                            <td>{{ number_format($item->solidary + $item->common_risk + $item->retirement, 2, ',', '') }}</td>
                            <td>{{ number_format($item->faults_amount, 2, ',', '') }}</td>
                            <td>{{ number_format($item->solidary, 2, ',', '') }}</td>
                            <td>{{ number_format($item->solidary_employer, 2, ',', '') }}</td>
                            <td>{{ number_format($item->liquid_payable, 2, ',', '') }}</td>
                        </tr> 
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    th{
        font-size: 8px
    }
    td{
        font-size: 11px
    }
</style>

<script>
    $(document).ready(function(){

    })
</script>