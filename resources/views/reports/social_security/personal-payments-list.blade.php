<div class="col-md-12 text-right">
    @if (count($planillas))
        {{-- <button type="button" onclick="report_export('excel')" class="btn btn-success"><i class="glyphicon glyphicon-cloud-download"></i> Excel</button> --}}
        <button type="button" onclick="report_export('print')" class="btn btn-danger"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @endif
</div>
<div class="col-md-12">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>N&deg;</th>
                            <th>NOMBRE COMPLETO</th>
                            <th>CARNET DE<br>IDENTIDAD</th>
                            <th>NUA/CUA</th>
                            <th>DIRECCIÓN ADMINISTRATIVA</th>
                            <th>ID PLANILLA</th>
                            <th>PERIODO</th>
                            <th>TOTAL<br>GANADO</th>
                            <th>APORTE AFP</th>
                            <th>FECHA DE PAGO</th>
                            <th>N&deg; DE FPC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cont = 1;
                        @endphp
                        @foreach ($planillas as $item)
                        {{-- {{ dd($item) }} --}}
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $item->empleado }}</td>
                            <td>{{ $item->ci }}</td>
                            <td>{{ $item->nua_cua }}</td>
                            <td>{{ $item->direccion_administrativa }}</td>
                            <td>{{ $item->planilla_procesada }}</td>
                            <td>{{ $item->periodo }}</td>
                            <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                            <td>{{ number_format($item->total_afp, 2, ',', '.') }}</td>
                            <td>
                                @foreach ($item->payments as $payment)
                                    {{ $payment->date_payment_afp }}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($item->payments as $payment)
                                    {{ $payment->fpc_number }}
                                @endforeach
                            </td>
                        </tr>
                        @php
                            $cont++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>