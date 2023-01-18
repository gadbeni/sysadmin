@php
    $salary = 0;
    if ($contract->cargo){
        $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
    }elseif ($contract->job){
        $salary = $contract->job->salary;
    }

    $contract_duration = contract_duration_calculate($contract->start, $contract->finish);
    $amount_total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
    $start = Carbon\Carbon::createFromFormat('Y-m-d', $contract->start);
    $finish = Carbon\Carbon::createFromFormat('Y-m-d', $contract->finish);
    $dias_primera_cuota = 0;
    $meses_intermedias_cuotas = 0;
    $dias_ultima_cuota = 0;

    // Si el contrato dura solo 1 mes se cuentan los días de ese mes
    if($start->format('Ym') == $finish->format('Ym')){
        $dias_primera_cuota = $start->diffInDays($finish) +1;

        // Si el contrato tiene mas de 30 días
        if($dias_primera_cuota >= 30){
            $meses_intermedias_cuotas++;
            $dias_primera_cuota = 0;
        }

        // Si es febrero y el contrato dura todo el mes
        if($start->format('m') == 2 && $dias_primera_cuota == 28){
            $meses_intermedias_cuotas++;
            $dias_primera_cuota = 0;
        }
    }else{
        
        // Si el contrato empieza luego del inicio del mes
        if($start->format('d') > 1){
            $dias_primera_cuota = 30 - $start->format('d') +1;
            $start = Carbon\Carbon::createFromFormat('Y-m-d', $start->format('Y').'-'.($start->format('m') +1).'-01');
            
            // Evitar que se agregue un mes si el contrato acaba el siguiente mes
            // if($start->format('Ym') != $finish->format('Ym') && $finish->format('d') <= 30){
            //     $meses_intermedias_cuotas++;
            // }
        }

        // Agregar los meses completos a pagar
        while ($start->format('Y-m-d') <= $finish->format('Y-m-d')) {
            $meses_intermedias_cuotas++;
            $start = $start->addMonths(1);
        }

        // Quitar la última iteracción
        $meses_intermedias_cuotas--;

        // Si el ultimo mes no se completa se deben contar solo los días
        if($finish->format('d') < 30){
            $dias_ultima_cuota = $finish->format('d');
        }else{
            $meses_intermedias_cuotas++;
        }
    }

    // Calcular cantidad total de cuotas
    $cantidad_cuotas = 0;
    if($dias_primera_cuota){
        $cantidad_cuotas++;
    }
    if($meses_intermedias_cuotas){
        $cantidad_cuotas += $meses_intermedias_cuotas;
    }
    if($dias_ultima_cuota){
        $cantidad_cuotas++;
    }

    // dd($contract->start, $contract->finish, $dias_primera_cuota, $meses_intermedias_cuotas, $dias_ultima_cuota);
@endphp

<p>
    El monto total a cancelar será de Bs.- <b>{{ NumerosEnLetras::convertir(number_format($amount_total, 2, '.', ''), 'Bolivianos', true) }}</b>, mismo que serán cancelados en {{ Str::lower(NumerosEnLetras::convertir($cantidad_cuotas)) }} ({{ $cantidad_cuotas }}) cuotas mensuales: 
    @if ($dias_primera_cuota)
        la primera correspondiente a {{ Str::lower(NumerosEnLetras::convertir($dias_primera_cuota)) }} ({{ $dias_primera_cuota }}) días del mes de {{ $months[intval(date('m', strtotime($contract->start)))] }} por <b>Bs.- {{ NumerosEnLetras::convertir(number_format(($salary /30) *$dias_primera_cuota, 2, '.', ''), 'Bolivianos', true) }}</b>
    @endif

    @php
        $cont = 1;
    @endphp

    @if ($meses_intermedias_cuotas > 1)
        @if (!$dias_primera_cuota && $meses_intermedias_cuotas)
            , la 
            @while ($cont <= $meses_intermedias_cuotas)
                {{ $cardinals[$cont].($cont == $meses_intermedias_cuotas ? '' : ($cont == $meses_intermedias_cuotas -1 ? ' y ' : ', ')) }} 
                @php
                    $cont++;
                @endphp
            @endwhile
        @elseif($dias_primera_cuota && $meses_intermedias_cuotas)
            , la 
            @while ($cont <= $meses_intermedias_cuotas)
                {{ $cardinals[$cont +1].($cont == $meses_intermedias_cuotas ? '' : ($cont == $meses_intermedias_cuotas -1 ? ' y ' : ', ')) }} 
                @php
                    $cont++;
                @endphp
            @endwhile
        @endif
    @endif

    @if ($meses_intermedias_cuotas)
        correspondiente {{ $meses_intermedias_cuotas == 1 ? ' al mes ' : ' a los meses ' }} de 
        @php
            $cont = 1;
            $start = Carbon\Carbon::createFromFormat('Y-m-d', $contract->start);
            if($dias_primera_cuota){
                $mes_inicio = intval($start->addMonths(1)->format('m'));
            }else{
                $mes_inicio = intval($start->format('m'));
            }
        @endphp
        @while ($cont <= $meses_intermedias_cuotas)
            {{ $months[intval($mes_inicio)].($cont == $meses_intermedias_cuotas ? '' : ($cont == $meses_intermedias_cuotas -1 ? ' y ' : ', ')) }} 
            @php
                $cont++;
                $mes_inicio++;
            @endphp
        @endwhile
        por un monto mensual de <b>Bs.- {{ NumerosEnLetras::convertir(number_format($salary, 2, '.', ''), 'Bolivianos', true) }}</b>
    @endif

    @if ($dias_ultima_cuota)
        , la {{ $cardinals[$cantidad_cuotas] }} correspondiente a {{ Str::lower(NumerosEnLetras::convertir($dias_ultima_cuota)) }} ({{ $dias_ultima_cuota }}) días del mes de {{ $months[intval(date('m', strtotime($contract->finish)))] }} por <b>Bs.- {{ NumerosEnLetras::convertir(number_format(($salary /30) *$dias_ultima_cuota, 2, '.', ''), 'Bolivianos', true) }}</b>
    @endif
    .
</p>