@php
    $cardinals = array('', 'primera', 'segunda', 'tercera', 'cuarta', 'quinta', 'sexta', 'septima', 'octava', 'novena', 'decima', 'decima primera', 'decima segunda');
    $salary = 0;
    if ($contract->cargo){
        $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
    }elseif ($contract->job){
        $salary = $contract->job->salary;
    }

    $contract_duration = contract_duration_calculate($contract_start, $contract_finish);
    $amount_total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);
    $start = Carbon\Carbon::createFromFormat('Y-m-d', $contract_start);
    $finish = Carbon\Carbon::createFromFormat('Y-m-d', $contract_finish);
    $dias_primera_cuota = 0;
    $meses_intermedias_cuotas = 0;
    $dias_ultima_cuota = 0;

    // Si el contrato dura solo 1 mes se cuentan los días de ese mes
    if($start->format('Ym') == $finish->format('Ym')){
        if($finish->format('m') != 2 && $finish->format('d') > 30){
            $finish->addDays(-1);
        }
        
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
            if($start->format('d') > 30){
                $start = Carbon\Carbon::parse($start->addDays()->format('Y-m-d'));
            }else{
                $dias_primera_cuota = 30 - $start->format('d') +1;
                $start = Carbon\Carbon::createFromFormat('Y-m-d', $start->format('Y').'-'.($start->format('m') +1).'-01');
            }
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
     
    $numeros_a_letras = new NumeroALetras();
@endphp

<p>
    {!! $subtitle ? '<b>1.1. MONTO.-</b> ' : '' !!} El monto total a cancelar será de <b>Bs. {{ number_format($amount_total, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice(number_format($amount_total, 2, '.', ''), 2, 'Bolivianos') }})</b>, mismo que serán cancelados en {{ Str::lower($numeros_a_letras->toWords($cantidad_cuotas)) }} ({{ $cantidad_cuotas }}) cuotas mensuales: 
    @if ($dias_primera_cuota)
        la primera correspondiente a {{ Str::lower($numeros_a_letras->toWords($dias_primera_cuota)) }} ({{ $dias_primera_cuota }}) días del mes de {{ $months[intval(date('m', strtotime($contract_start)))] }} por un monto de <b>Bs. {{ number_format(($salary /30) *$dias_primera_cuota, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice(number_format(($salary /30) *$dias_primera_cuota, 2, '.', ''), 2, 'Bolivianos') }})</b>
    @endif

    @php
        $cont = 1;
    @endphp
    @if ($meses_intermedias_cuotas >= 1)
        @if (!$dias_primera_cuota && $meses_intermedias_cuotas)
            la 
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
    @else
        , la segunda
    @endif

    @if ($meses_intermedias_cuotas)
        correspondiente {{ $meses_intermedias_cuotas == 1 ? ' al mes ' : ' a los meses ' }} de 
        @php
            $cont = 1;
            $start = Carbon\Carbon::createFromFormat('Y-m-d', $contract_start);
            if($start->format('d') > 30){
                $start->addDays();
            }
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
        por un monto mensual de <b>Bs. {{ number_format($salary, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice(number_format($salary, 2, '.', ''), 2, 'Bolivianos') }})</b>
    @endif

    @if ($dias_ultima_cuota)
        , la {{ $cardinals[$cantidad_cuotas] }} correspondiente a {{ Str::lower($numeros_a_letras->toWords($dias_ultima_cuota)) }} ({{ $dias_ultima_cuota }}) días del mes de {{ $months[intval(date('m', strtotime($contract_finish)))] }} por un monto de <b>Bs. {{ number_format(($salary /30) *$dias_ultima_cuota, 2, ',', '.') }} ({{ $numeros_a_letras->toInvoice(number_format(($salary /30) *$dias_ultima_cuota, 2, '.', ''), 2, 'Bolivianos') }})</b>
    @endif
    .
</p>