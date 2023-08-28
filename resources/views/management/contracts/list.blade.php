<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Persona</th>
                    <th>Dirección administrativa</th>
                    <th>Detalles</th>
                    <th>Registrado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $meses = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                @endphp
                @forelse ($data as $item)
                    @php
                        // Contrato posteriores al actual
                        $contracts = \App\Models\Contract::where('person_id', $item->person_id)->where('deleted_at', NULL)->where('id', '>', $item->id)->get();
                        $addendums = $item->addendums;
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->type->name }}</td>
                        <td>
                            {{ $item->person->last_name }} {{ $item->person->first_name }}<br>
                            <b>CI</b>: {{ $item->person->ci }}
                            {!! $item->person->phone ? '<br><b>Telf</b>: '.$item->person->phone : '' !!}
                        </td>
                        <td>{{ $item->direccion_administrativa ? $item->direccion_administrativa->nombre : 'No definida' }}</td>
                        <td>
                            <ul style="list-style: none; padding-left: 0px">
                                <li>
                                    <b>Cargo: </b>
                                    @if ($item->cargo)
                                        {{ $item->cargo->Descripcion }}
                                    @elseif ($item->job)
                                        {{ $item->job->name }}
                                    @else
                                        @if ($item->job_description)
                                            {{ $item->job_description }}
                                        @else
                                            No definido
                                        @endif
                                    @endif
                                </li>
                                <li>
                                    @php
                                        $salary = 0;
                                        if ($item->cargo){
                                            $salary = $item->cargo->nivel->where('IdPlanilla', $item->cargo->idPlanilla)->first()->Sueldo;
                                        }elseif ($item->job){
                                            $salary = $item->job->salary;
                                        }elseif ($item->salary){
                                            $salary = $item->salary + $item->bonus;
                                        }
                                    @endphp
                                    <b>Sueldo: </b> <small>Bs.</small> {{ number_format($salary, 2, ',', '.') }}
                                </li>
                                <li><b>Desde </b>{{ date('d/', strtotime($item->start)).$meses[intval(date('m', strtotime($item->start)))].date('/Y', strtotime($item->start)) }}
                                @if ($item->finish)
                                <b> hasta </b>{{ date('d/', strtotime($item->finish)).$meses[intval(date('m', strtotime($item->finish)))].date('/Y', strtotime($item->finish)) }}
                                @endif
                                <li>
                                    @if ($item->start && $item->finish)
                                        @php
                                            $contract_duration = contract_duration_calculate($item->start, $item->finish);
                                            $total = ($salary *$contract_duration->months) + (number_format($salary /30, 5) *$contract_duration->days);    
                                        @endphp
                                        <b>Duración: </b> {{ ($contract_duration->months * 30) + $contract_duration->days }} Días <br>
                                        <b>Total: </b> <small>Bs.</small> {{ number_format($total, 2, ',', '.') }}
                                    @endif
                                </li>
                                <li>
                                    @php
                                        switch ($item->status) {
                                            case 'anulado':
                                                $label = 'danger';
                                                break;
                                            case 'elaborado':
                                                $label = 'default';
                                                // Si el creador del contrato es de una DA desconcentrada el siguiente estado es firmado, sino es enviado
                                                $netx_status = Auth::user()->direccion_administrativa_id ? 'firmado' : 'enviado';
                                                break;
                                            case 'enviado':
                                                $label = 'info';
                                                $netx_status = 'firmado';
                                                break;
                                            case 'firmado':
                                                $label = 'success';
                                                break;
                                            case 'concluido':
                                                $label = 'warning';
                                                break;
                                            default:
                                                $label = 'default';
                                                $netx_status = '';
                                                break;
                                        }
                                    @endphp
                                    <b>Estado</b>: <label class="label label-{{ $label }}">{{ ucfirst($item->status) }}</label>
                                    @if (count($addendums) > 0)
                                    @php
                                        if($addendums->first()->status == "firmado"){
                                            $class = 'success';
                                            $label = 'firmada';
                                        }elseif($addendums->first()->status == "elaborado"){
                                            $class = 'dark';
                                            $label = 'elaborada';
                                        }elseif($addendums->first()->status == "concluido"){
                                            $class = 'warning';
                                            $label = 'concluida';
                                        }else{
                                            $class = 'default';
                                            $label = 'desconocida';
                                        }
                                    @endphp
                                    <label class="label label-{{ $class }} label-addendum" title="Adenda {{ $label }}" data-contract='@json($item)' data-addendums='@json($addendums)' data-toggle="modal" data-target="#addendum-show-modal" style="cursor: pointer"><i class="voyager-calendar"></i></label>
                                    @endif
                                </li>
                            </ul>
                        </td>
                        <td>
                            {{ $item->user ? $item->user->name : '' }} <br>
                            {{ date('d/', strtotime($item->created_at)).$meses[intval(date('m', strtotime($item->created_at)))].date('/Y H:i', strtotime($item->created_at)) }} <br>
                            <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                        </td>
                        <td class="no-sort no-click bread-actions text-right">

                            {{-- No mostar la opción en caso de que sea un contrato TGN --}}
                            @if ($item->procedure_type_id != 6)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                                        Más <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                        {{-- Definir siguiente estado --}}
                                        @if (auth()->user()->hasPermission('upgrade_contracts') && $item->status != 'concluido' && $item->status != 'firmado' && ($item->cargo_id || $item->job_id))
                                        <li><a href="#" title="Promover a {{ $netx_status }}" data-toggle="modal" data-target="#status-modal" onclick="changeStatus({{ $item->id }}, '{{ $netx_status }}')">Promover</a></li>
                                        @endif
                                        {{-- Si está firmado se puede rotar --}}
                                        @if ($item->status == 'firmado' && auth()->user()->hasPermission('add_rotation_people'))
                                        <li><a href="#" class="btn-rotation" data-url="{{ route('people.rotation.store', ['id' => $item->person_id]) }}" data-toggle="modal" data-target="#modal-rotation" >Rotar</a></li>
                                        @endif
                                        {{-- Si está firmado se puede ratificar --}}
                                        @if ($item->status == 'firmado' && auth()->user()->hasPermission('ratificate_contracts'))
                                        <li><a href="#" title="Ratificar" data-toggle="modal" data-target="#ratificate-modal" onclick="ratificateContract({{ $item->id }})">Ratificar</a></li>
                                        @endif
                                        {{-- si está firmado se puede transferir --}}
                                        @if ($item->status == 'firmado' && $item->procedure_type_id == 1 && auth()->user()->hasPermission('transfer_contracts'))
                                        <li><a href="#" class="btn-transfer" data-toggle="modal" data-target="#transfer-modal" data-id="{{ $item->id }}" title="Crear transferencia" >Transferir</a></li>
                                        @endif
                                        {{-- si está firmado se puede promocionar --}}
                                        @if ($item->status == 'firmado' && $item->procedure_type_id == 1 && auth()->user()->hasPermission('promotion_contracts'))
                                        <li><a href="#" class="btn-promotion" data-toggle="modal" data-target="#promotion-modal" data-id="{{ $item->id }}" title="Crear promoción" >Promoción</a></li>
                                        @endif
                                        {{-- Crear adenda --}}
                                        @if (auth()->user()->hasPermission('add_addendum_contracts') && ($item->status == 'firmado' || $item->status == 'concluido') && count($contracts) == 0 && ($item->procedure_type_id == 2 || $item->procedure_type_id == 5) && $addendums->where('status', 'elaborado')->count() == 0 && $addendums->where('status', 'firmado')->count() == 0)
                                        <li><a class="btn-addendum" title="Crear adenda" data-toggle="modal" data-target="#addendum-modal" data-item='@json($item)' data-addendums='@json($addendums)' href="#">Crear adenda</a></li>
                                        @endif
                                        {{-- Crear memo/resolución --}}
                                        @if ($item->status == 'firmado' && auth()->user()->hasPermission('finish_contracts'))
                                        <li><a href="#" title="{{ $item->procedure_type_id == 1 ? 'Memo de agradecimiento' : 'Resolución' }}" data-toggle="modal" data-target="#finish-modal" onclick="finishContract({{ $item->id }}, '{{ $item->finish }}', {{ $item->procedure_type_id }})">{{ $item->procedure_type_id == 1 ? 'Agradecimiento' : 'Resolución' }}</a></li>
                                        @endif
                                        {{-- Restaurar contrato --}}
                                        @if (auth()->user()->hasPermission('restore_contracts') && $item->finished)
                                        <li><a href="#" title="Anular resolución de contrato" onclick="setFormAction('{{ route('contracts.finished.destroy', ['id' => $item->id]) }}', '#restore-form')" data-toggle="modal" data-target="#restore-modal">Anular resolución <br> ({{ $item->finished->previus_date ? date('d/m/Y', strtotime($item->finished->previus_date)) : 'Indefinido' }})</a></li>
                                        @endif
                                        {{-- Firmar adenda --}}
                                        @if (count($addendums) > 0)
                                            @if ($addendums->first()->status == 'elaborado' && ($item->procedure_type_id == 2 || $item->procedure_type_id == 5))
                                            <li><a class="btn-addendum-status" title="Firmar adenda" data-toggle="modal" data-target="#addendum-status-modal" data-id="{{ $addendums->first()->id }}" href="#">Firmar adenda</a></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            @endif
                            
                            {{-- Botón de impresión --}}
                            @if ($item->cargo_id || $item->job_id)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    Imprimir <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        @switch($item->procedure_type_id)
                                            @case(1)
                                                <li><a title="Designación" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum']) }}" target="_blank">Designación</a></li>
                                                @if ($item->ratifications->count() > 0 && auth()->user()->hasPermission('ratificate_contracts'))
                                                <li><a title="Ratificación" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-ratificacion']) }}" target="_blank">Ratificación</a></li>
                                                @endif
                                                <li><a title="Reasignación" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-reasignacion']) }}" target="_blank">Reasignación</a></li>
                                                @if ($item->transfers->count() > 0)
                                                <li><a title="Transferecnia" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.transfer']) }}" target="_blank">Transferencia</a></li>
                                                @endif
                                                @if ($item->promotions->count() > 0)
                                                <li><a title="Promoción" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.promotion']) }}" target="_blank">Promoción</a></li>
                                                @endif
                                                @if ($item->jobs->count() > 0)
                                                <li><a title="Reasignación de denominación de cargo" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-reasignacion-alt']) }}" target="_blank">Reasignación de cargo</a></li>
                                                @endif
                                                @if ($item->status == 'concluido' && auth()->user()->hasPermission('print_finish_contracts'))
                                                <li><a title="Memorándum de agradecimiento" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'permanente.memorandum-finished']) }}" target="_blank">Memorándum</a></li>
                                                @endif
                                                @break
                                            @case(2)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.autorization']) }}" target="_blank">Autorización</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.invitation']) }}" target="_blank">Invitación</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.declaration']) }}" target="_blank">Declaración</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.memorandum']) }}" target="_blank">Memorandum</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.report']) }}" target="_blank">Informe</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.adjudication']) }}" target="_blank">Nota de adjudicación</a></li>
                                                <li class="divider"></li>
                                                @if ($item->direccion_administrativa_id == 5)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.contract-sedeges']) }}" target="_blank">Contrato</a></li>
                                                @else
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.contract']) }}" target="_blank">Contrato</a></li>
                                                @endif
                                                {{-- Si hay una adenda firmada --}}
                                                @if (count($addendums) > 0)
                                                    @if ($addendums->first()->status == 'firmado')
                                                        @if ($item->direccion_administrativa_id == 5)
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.addendum-sedeges']).(count($addendums) == 1 ? '?type=first' : '') }}" target="_blank">Adenda</a></li>
                                                        @else
                                                            <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'consultor.addendum']).(count($addendums) == 1 ? '?type=first' : '') }}" target="_blank">Adenda</a></li>
                                                        @endif
                                                    @endif
                                                @endif
                                                {{-- @if ($item->finished && $item->status == 'concluido' && auth()->user()->hasPermission('print_finish_contracts'))
                                                <li><a title="Resolusión de contrato" href="{{ route('contracts.print', ['id' => $item->id, 'document' => '']) }}" target="_blank">Resolusión</a></li>
                                                @endif --}}

                                                @break
                                            @case(5)
                                                @if ($item->direccion_administrativa->tipo->id == 3 || $item->direccion_administrativa->tipo->id == 4)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-alt']) }}" target="_blank">Contrato</a></li>
                                                @elseif ($item->direccion_administrativa_id == 32 || $item->direccion_administrativa_id == 36 || $item->direccion_administrativa_id == 57)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-health']) }}" target="_blank">Contrato</a></li>
                                                @elseif ($item->direccion_administrativa_id == 5)
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-sedeges']) }}" target="_blank">Contrato</a></li>
                                                @else
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract']) }}" target="_blank">Contrato</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.contract-inamovible']) }}" target="_blank">Contrato inamovible</a></li>
                                                <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.memorandum-designacion']) }}" target="_blank">Memorandum</a></li>
                                                @endif

                                                {{-- Si hay una adenda firmada --}}
                                                @if (count($addendums) > 0)
                                                    @if ($addendums->first()->status == 'firmado')
                                                    <li><a href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.addendum']).(count($addendums) == 1 ? '?type=first' : '') }}" target="_blank">Adenda</a></li>
                                                    @endif
                                                @endif

                                                @if ($item->finished && $item->status == 'concluido' && auth()->user()->hasPermission('print_finish_contracts'))
                                                <li><a title="Resolución de contrato" href="{{ route('contracts.print', ['id' => $item->id, 'document' => 'eventual.resolution']) }}" target="_blank">Resolución</a></li>
                                                @endif

                                                @break
                                            @default
                                                
                                        @endswitch
                                    </ul>
                                </div>
                            @endif

                            @if ($item->procedure_type_id != 6)
                                <a href="{{ route('contracts.show', ['contract' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                </a>
                            @endif
                            
                            @if ($item->status != 'concluido')
                                {{-- Se puede editar el contrato si no está firmado --}}
                                @if (($item->status != 'firmado' && auth()->user()->hasPermission('edit_contracts')) || auth()->user()->role_id == 1 || $item->procedure_type_id == 6)
                                    <a href="{{ route('contracts.edit', ['contract' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                                        <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                    </a>
                                @endif

                                {{-- Si está firmado, no es un contrato TGN y tiene permiso de cambiar el estado --}}
                                @if ($item->status == 'firmado' && $item->procedure_type_id != 6 && auth()->user()->hasPermission('delete_contracts') && auth()->user()->hasPermission('downgrade_contracts'))
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                                            Anular <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" style="left: -90px !important">
                                            <li><a href="#" onclick="downgradeContract({{ $item->id }}, 'elaborado')" data-toggle="modal" data-target="#downgrade-modal">Quitar firma</a></li>
                                            <li>
                                                <a href="#" onclick="setFormAction('{{ route('contracts.destroy', ['contract' => $item->id]) }}', '#delete_form_alt')" data-toggle="modal" data-target="#delete-modal-alt" title="Anular">
                                                    Anular
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                @elseif(($item->status != 'firmado' && auth()->user()->hasPermission('delete_contracts')) || ($item->status == 'elaborado' && auth()->user()->hasPermission('add_contracts')))
                                    <a href="#" onclick="setFormAction('{{ route('contracts.destroy', ['contract' => $item->id]) }}', '#delete_form_alt')" data-toggle="modal" data-target="#delete-modal-alt" title="Anular" class="btn btn-sm btn-danger delete">
                                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Anular</span>
                                    </a>
                                @endif
                            @endif
                            
                        </td>
                    </tr>
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="7" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<style>
    .mce-edit-area{
        max-height: 250px !important;
        overflow-y: auto;
    }
</style>

<script>
    moment.locale('es');
    var page = "{{ request('page') }}";
    $(document).ready(function(){

        $('#select-job_id').select2({dropdownParent: $('#transfer-modal')});
        $('#select-job_id-alt').select2({dropdownParent: $('#promotion-modal')});
        $('#select-signature_id').select2({dropdownParent: $('#addendum-modal')});

        
        $.extend({selector: '.richTextBox'}, {})
        tinymce.init(window.voyagerTinyMCE.getConfig({selector: '.richTextBox'}));

        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });

        $('.btn-transfer').click(function(){
            let id = $(this).data('id');
            $('#form-transfer input[name="contract_id"]').val(id);
        });

        $('.btn-promotion').click(function(){
            let id = $(this).data('id');
            $('#form-promotion input[name="contract_id"]').val(id);
        });

        $('.btn-rotation').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            $('#rotation-form').attr('action', url);
        });

        // Crear adenda
        $('.btn-addendum').click(function(){
            let item = $(this).data('item');
            let date = moment(item.finish, "YYYY-MM-DD").add(1, 'days');
            let addendums = $(this).data('addendums').sort(function (a, b) {
                                if (a.id > b.id) {
                                    return 1;
                                }
                                if (a.id < b.id) {
                                    return -1;
                                }
                                return 0;
                            });
            $('#form-addendum input[name="id"]').val(item.id);
            $('#form-addendum input[name="start"]').val(date.format("YYYY-MM-DD"));
            $('#form-addendum input[name="signature_date"]').val(date.format("YYYY-MM-DD"));
            $('#form-addendum input[name="finish"]').attr('min', date.format("YYYY-MM-DD"));
            $('#label-duration').html('');

            // Calcular la fecha de inicio y fin del contrato principal
            var start = item.start;
            var finish = item.finish;
            if(addendums.length > 0){
                finish = moment(addendums[0].start).add(-1, 'days').format('YYYY-MM-DD');
            }
            $.get("{{ url('admin/get_duration') }}/"+start+"/"+finish, res => {
                main_contract_duration = (res.duration.months *30) + res.duration.days;
                $('#label-duration-contract').html(`Contrato principal desde ${moment(start).format('DD [de] MMMM')} hasta el ${moment(finish).format('DD [de] MMMM [de] YYYY')}, con una duración de ${main_contract_duration} días.`);
            });

            // Si es eventual
            if(item.procedure_type_id == 5){
                $('.div-eventual').fadeIn();
            }else{
                $('.div-eventual').fadeOut();
            }

            // Si es eventual o es consultor del SEDEGES
            if(item.procedure_type_id == 5 || (item.procedure_type_id == 2 && item.direccion_administrativa_id == 5)){
                $('.div-eventual-consultor_sedeges').fadeIn();
            }else{
                $('.div-eventual-consultor_sedeges').fadeOut();
            }

            // Si es consultor del SEDEGES
            if(item.procedure_type_id == 2 && item.direccion_administrativa_id == 5){
                $('.div-consultor_sedeges').fadeIn();
            }else{
                $('.div-consultor_sedeges').fadeOut();
            }

            if(date.weekday() > 4){
                $('#alert-weekend').fadeIn();
            }else{
                $('#alert-weekend').fadeOut();
            }
        });

        $('#form-addendum input[name="signature_date"]').change(function(){
            let date = moment($(this).val(), "YYYY-MM-DD");
            if(date.weekday() > 4){
                $('#alert-weekend').fadeIn('fast');
            }else{
                $('#alert-weekend').fadeOut('fast');
            }
        });

        // Mostrar adenda
        $('.label-addendum').click(function(){
            let addendums = $(this).data('addendums').sort(function (a, b) {
                                if (a.id > b.id) {
                                    return 1;
                                }
                                if (a.id < b.id) {
                                    return -1;
                                }
                                return 0;
                            });
            let contract = $(this).data('contract');
            $('#label-date-contract').html(`Inicio desde el ${moment(contract.start).format('DD [de] MMMM [de] YYYY')} hasta el ${moment(addendums[0].start).add(-1, 'days').format('DD [de] MMMM [de] YYYY')}.`);
            $('#details-addendum').empty();
            addendums.map(addendum => {
                let style, label;
                if (addendum.status == 'elaborado') {
                    style = 'dark';
                    label= 'Elaborada';
                } else if (addendum.status == 'firmado') {
                    style = 'success';
                    label= 'Firmada';
                }else if (addendum.status == 'concluido') {
                    style = 'warning';
                    label= 'Concluida';
                }else{
                    style = 'default';
                    label= 'Desconocida';
                }
                $('#details-addendum').append(`
                    <p>Inicio desde el ${moment(addendum.start).format('DD [de] MMMM [de] YYYY')} hasta el ${moment(addendum.finish).format('DD [de] MMMM [de] YYYY')}.</p>
                    <p><b>Estado de la adenda</b> <label class="label label-${style}">${label}</label></p>
                    ${addendum.program ? '<p>'+addendum.program.name+'</p>' : ''}
                    <br>
                `);
            });
        });

        $('.btn-addendum-status').click(function(){
            let id = $(this).data('id');
            $('#addendum-status-form input[name="id"]').val(id);
        });
    });
</script>