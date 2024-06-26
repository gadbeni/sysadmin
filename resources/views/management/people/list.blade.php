<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>CI</th>
                    <th>Lugar nac.</th>
                    <th>Fecha nac.</th>
                    <th>Telefono</th>
                    <th>AFP</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <table>
                            @php
                                $image = asset('images/default.jpg');
                                if($item->image){
                                    $image = asset('storage/'.str_replace('.', '-cropped.', $item->image));
                                }
                                $now = \Carbon\Carbon::now();
                                $birthday = new \Carbon\Carbon($item->birthday);
                                $age = $birthday->diffInYears($now);
                            @endphp
                            <tr>
                                <td><img src="{{ $image }}" alt="{{ $item->first_name }} {{ $item->last_name }}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px"></td>
                                <td>
                                    {{ $item->first_name }} {{ $item->last_name }} <br>
                                    @if ($item->profession)
                                        <small>{{ $item->profession }}</small> <br>
                                    @endif
                                    @if ($item->contracts->where('status', 'firmado')->count())
                                        @php
                                            $contract = $item->contracts->where('status', 'firmado')->first();
                                        @endphp
                                        <a href="{{ $contract->procedure_type_id != 6 ? route('contracts.show', ['contract' => $contract->id]) : '#' }}"><label class="label label-success" title="{{ $contract->code }} del {{ date('d/m/Y', strtotime($contract->start)) }} {{ $contract->finish ? ' al '.date('d/m/Y', strtotime($contract->finish)) : '' }}" style="cursor: pointer">Con contrato</label></a>
                                    @endif
                                    @if ($item->contracts->whereIn('status', ['elaborado', 'enviado'])->count())
                                        @php
                                            $contract = $item->contracts->whereIn('status', ['elaborado', 'enviado'])->first();
                                        @endphp
                                        <a href="{{ route('contracts.show', ['contract' => $contract->id]) }}"><label class="label label-default" title="{{ $contract->code }}" style="cursor: pointer">Contrato en proceso</label></a>
                                    @endif
                                    @php
                                        $irremovability = $item->irremovabilities->count() ? $item->irremovabilities[0] : NULL;
                                    @endphp
                                    @if ($irremovability)
                                        <label class="label label-danger" title="{{ $irremovability->type->name }}">Inamovible</label>
                                    @endif
                                    @if (!$item->afp_status)
                                        <label class="label label-warning">No aporta</label>
                                    @endif
                                    @if ($item->retired)
                                        <label class="label label-info">Jubilado</label>    
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>{{ $item->ci }}</td>
                    <td>{{ $item->city ? $item->city->name : 'No definido' }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->birthday)) }} <br> <small>{{ $age }} años</small> </td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->afp_type->name }} <br> <small>{{ $item->nua_cua }}</small> </td>
                    <td>
                        {{ $item->user ? $item->user->name : '' }} <br>
                        {{ date('d/m/Y H:i', strtotime($item->created_at)) }} <br>
                        <small>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                    </td>
                    <td class="no-sort no-click bread-actions text-right">
                        <div class="btn-group" style="margin-right: 3px">
                            <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                                Más <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" style="top: -10px !important; left: -180px !important">
                                @if (auth()->user()->hasPermission('add_file_people'))
                                <li><a href="#" class="btn-add-file" data-url="{{ route('people.file.store', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-add-file" >Agregar documentación</a></li>
                                @endif
                                {{-- Si no tiene ningún custodio activo --}}
                                @if (auth()->user()->hasPermission('add_assets_people') && $item->contracts->where('status', 'firmado')->count() && $item->assignments->count() == 0)
                                <li><a href="#" class="btn-add-assets" data-url="{{ route('people.assets.store', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-add-assets" >Agregar custodio</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('add_rotation_people'))
                                <li><a href="#" class="btn-rotation" data-url="{{ route('people.rotation.store', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-rotation" >Rotar</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('add_irremovability_people'))
                                <li><a href="#" class="btn-irremovability" data-url="{{ route('people.irremovability.store', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-irremovability" >Inamovilidad</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('edit_people'))
                                <li><a href="#" class="btn-afp-options" data-afp_status="{{ $item->afp_status }}" data-retired="{{ $item->retired }}" data-url="{{ route('people.afp_status.update', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-options-afp" >Opciones de AFP</a></li>
                                @endif
                                {{-- Opciones de marcaciones --}}
                                <li class="divider" style="margin: 5px 0px"></li>
                                {{-- Licencias y comisiones --}}
                                @php
                                    $current_contract = $item->contracts->where('status', 'firmado')->first();
                                    $contract_id = null;
                                    $contract_start = null;
                                    $contract_finish = null;
                                    if($current_contract){
                                        $contract_id = $current_contract->id;
                                        $contract_start = $current_contract->start;
                                        $contract_finish = $current_contract->finish;
                                    }
                                @endphp
                                @if ($item->contracts->where('status', 'firmado')->count())
                                <li><a href="#" class="btn-attendance_permit" data-contract_id="{{ $contract_id }}" data-toggle="modal" data-target="#attendance_permit-modal" >Licencias/Comisión</a></li>
                                @endif
                                @if ($item->contracts->where('status', 'firmado')->count())
                                <li><a href="#" class="btn-schedules" data-contract_id="{{ $contract_id }}" data-contract_start="{{ $contract_start }}" data-contract_finish="{{ $contract_finish }}" data-schedules='@json($item->schedules)' data-toggle="modal" data-target="#modal-schedules">Horario</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('edit_attendances_people_alt'))
                                <li><a href="#" class="btn-attendance-options-alt" data-url="{{ route('people.attendances', ['id' => $item->id]) }}" data-toggle="modal" data-target="#modal-options-attendance-alt" >Marcaciones</a></li>
                                @endif
                                @if (auth()->user()->hasPermission('edit_attendances_people'))
                                <li><a href="#" class="btn-attendance-options">Marcaciones</a></li>
                                @endif
                                <li class="divider" style="margin: 5px 0px"></li>
                                <li><a href="#" class="btn-qr-generate" data-id="{{ $item->id }}" data-full_name="{{ $item->first_name.' '.$item->last_name }}" data-toggle="modal" data-target="#modal-qr">Generar QR</a></li>
                                @if (setting('servidores.whatsapp') && setting('servidores.whatsapp-session'))
                                <li><a href="#" class="btn-notification" data-id="{{ $item->id }}" data-phone="{{ $item->phone }}" data-toggle="modal" data-target="#modal-notification" >Notificar <i class="voyager-paper-plane"></i></a></li>
                                @endif
                            </ul>
                        </div>
                        @if (auth()->user()->hasPermission('read_people'))
                        <a href="{{ route('voyager.people.show', ['id' => $item->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        @endif
                        @if (auth()->user()->hasPermission('edit_people'))
                        <a href="{{ route('voyager.people.edit', ['id' => $item->id]) }}" title="Editar" class="btn btn-sm btn-primary edit">
                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                        </a>
                        @endif
                        @if (auth()->user()->hasPermission('delete_people'))
                        <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('voyager.people.destroy', ['id' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr class="odd">
                        <td valign="top" colspan="9" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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

<script>
    var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });

        $('.btn-add-file').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            $('#add-file-form').attr('action', url);
        });

        $('.btn-add-assets').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            $('#add-assets-form').attr('action', url);
        });

        $('.btn-rotation').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            $('#rotation-form').attr('action', url);
        });

        $('.btn-irremovability').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            $('#irremovability-form').attr('action', url);
        });

        $('.btn-afp-options').click(function(e){
            e.preventDefault();
            let url = $(this).data('url');
            let afp_status = $(this).data('afp_status');
            let retired = $(this).data('retired');
            $('#checkbox-afp_status').bootstrapToggle(afp_status ? 'on' : 'off');
            $('#checkbox-retired').bootstrapToggle(retired ? 'on' : 'off');
            $('#options-afp-form').attr('action', url);
        });

        $('.btn-attendance-options-alt').click(function(e){
            e.preventDefault();
            urlListAttendances = $(this).data('url');
            $('#details-attendaces').html('<tr><td colspan="3">Seleccione la fecha</td></tr>');
            $('#options-attendance-form').trigger('reset');
        });

        $('.btn-schedules').click(function(){
            let schedules = $(this).data('schedules');
            let contract_id = $(this).data('contract_id');
            let contract_start = $(this).data('contract_start');
            let contract_finish = $(this).data('contract_finish');
            $('#form-schedules input[name="contract_id"]').val(contract_id);
            $('#form-schedules input[name="start"]').attr('min', contract_start);
            $('#form-schedules input[name="finish"]').val(contract_finish ? contract_finish : '');
            
            $('#form-schedules input[name="start"]').val('');
            if (schedules.length) {
                let last_schedule = schedules[schedules.length -1];
                $('#label-last-schedule').html(`Horario de <b>${last_schedule.schedule.name}</b> desde el ${moment(last_schedule.start).format('DD [de] MMMM [de] YYYY')} ${moment(last_schedule.finish).format('YYYY-MMMM-DD') < moment().format('YYYY-MMMM-DD') ? ' hasta '+moment(last_schedule.finish).format('DD [de] MMMM [de] YYYY') : ''}`);
                $('#form-schedules input[name="contract_schedule_id"]').val(last_schedule.id);
                $(`#select-schedule_id option[value="${last_schedule.schedule.id}"]`).prop('disabled', true);
            } else {
                $('#label-last-schedule').html('No tiene horarios asignados <i class="fa fa-ban"></i>');
                $(`#select-schedule_id option`).prop('disabled', false);
                $('#form-schedules input[name="start"]').val(contract_start);
            }
        });

        $('.btn-notification').click(function(){
            let id = $(this).data('id');
            let phone = $(this).data('phone');
            $('#form-notification input[name="person_id"]').val(id);
            $('#form-notification input[name="phone"]').val(phone);
        });

        $('.btn-qr-generate').click(function(){
            $('#div-qr').empty();
            let id = $(this).data('id');
            let full_name = $(this).data('full_name');
            generarQR("{{ url('person') }}/"+id, 'div-qr');
            $('#qr-detail').text(`QR para credencial de ${full_name}`);
            fileName = full_name;
        });

        $('.btn-attendance_permit').click(function(){
            let contract_id = $(this).data('contract_id');
            $('#form-attendance_permit input[name="contract_id[]"]').val(contract_id);
        });
    });
</script>