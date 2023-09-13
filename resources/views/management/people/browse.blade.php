@extends('voyager::master')

@section('page_title', 'Viendo Datos Personales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-people"></i> Datos Personales
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_people'))
                            <a href="{{ route('voyager.people.create') }}" class="btn btn-success">
                                <i class="voyager-plus"></i> <span>Crear</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="input-search" class="form-control">
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px; padding-bottom: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal irremovability --}}
    <form class="form-submit" id="irremovability-form" action="#" method="post">
        @csrf
        <div class="modal modal-primary fade modal-option" tabindex="-1" id="modal-irremovability" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-forward"></i> Registrar inamovilidad</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Destino</label>
                            <select name="irremovability_type_id" class="form-control select2" required>
                                <option selected disabled value="">--Seleccione el tipo de inamovilidad--</option>
                                @foreach (\App\Models\IrremovabilityType::where('status', 'activo')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Inicio de inamovilidad</label>
                            <input type="date" name="start" class="form-control" value="{{ date('Y-m-d') }}" required >
                        </div>
                        <div class="form-group">
                            <label>Fin de inamovilidad</label>
                            <input type="date" name="finish" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <textarea name="observations" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark btn-submit" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Modal afp options --}}
    <form class="form-submit" id="options-afp-form" action="#" method="post">
        @csrf
        <div class="modal modal-primary fade modal-option" tabindex="-1" id="modal-options-afp" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-params"></i> Opciones de AFP</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-md-6">
                            <label>Aporte a la AFP</label> <br>
                            <input type="checkbox" name="afp_status" id="checkbox-afp_status" class="toggleswitch" data-on="Sí" data-off="No">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Jubilado</label> <br>
                            <input type="checkbox" name="retired" id="checkbox-retired" class="toggleswitch" data-on="Sí" data-off="No">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark btn-submit" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Modal attendance options --}}
    <div class="modal modal-primary fade modal-option" tabindex="-1" id="modal-options-attendance" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-calendar"></i> Marcaciones</h4>
                </div>
                <div class="modal-body">
                    <form id="options-attendance-form" action="#" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="date">Fecha</label>
                                <input type="date" name="date" id="input-date" class="form-control">
                                <input type="hidden" name="current_hour" id="input-current-hour">
                                <input type="hidden" name="new_hour" id="input-new-hour">
                                <input type="hidden" name="ci" id="input-ci">
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>N&deg;</th>
                                            <th>Hora</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="details-attendaces"></tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal add file --}}
    @include('management.people.partials.modal-add-file')

    {{-- Modal add assets --}}
    @include('management.people.partials.modal-add-assets')

    {{-- Modal rotation --}}
    @include('management.people.partials.modal-rotation')
    
@stop

@section('css')
    <style>
        @media (max-width: 767px) {
            .table-responsive .dropdown-menu {
                position: static !important;
            }
        }
        @media (min-width: 768px) {
            .table-responsive {
                overflow: visible;
            }
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        moment.locale('es');
        var countPage = 10, order = 'id', typeOrder = 'desc';
        var assetSelected = null;
        var urlListAttendances = null; // Variavle auxiliar para almacenar la ruta de acceso a la lista de marcaciones del funcionario actual (al que se le dió click)
        $(document).ready(() => {

            $('#select-destiny_id').select2({dropdownParent: $('#modal-rotation')});
            $('#select-destiny_dependency').select2({dropdownParent: $('#modal-rotation')});
            $('#select-responsible_id').select2({dropdownParent: $('#modal-rotation')});
            customSelect('#select-asset_id', '{{ url("admin/assets/search/ajax") }}', formatResultAssets, data => {assetSelected = data}, $('#modal-add-assets'));
            customSelect('#select-signature_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, $('#modal-add-assets'));
            list();

            $('.toggleswitch').bootstrapToggle();
            
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
                list();
            });

            $('#select-asset_id').change(function(){
                let asset = assetSelected;
                if (document.getElementById(`tr-asset-${asset.id}`)) {
                    toastr.info('El activo ya se encuentra en la lista', 'Aviso')
                    return 0;
                }
                $('#assets-list').append(`
                    <tr id="tr-asset-${asset.id}">
                        <td class="td-assets"></td>
                        <td>
                            <input type="hidden" name="asset_id[]" value="${asset.id}" />
                            ${asset.code}
                        </td>
                        <td>${asset.description}</td>
                        <td>
                            <input type="hidden" name="status[]" value="${asset.status}" />
                            ${asset.status.charAt(0).toUpperCase() + asset.status.slice(1)}
                        </td>
                        <td><textarea name="asset_observations[]" class="form-control"></textarea></td>
                        <td>
                            <button class="btn btn-link" onclick="deleteAssetTr(${asset.id})"><i class="voyager-trash text-danger"></i></button>
                        </td>
                    </tr>
                `);
                generateNumberRow();
            });

            $('.form-submit').submit(function(e){
                $('.form-submit .btn-submit').val('Guardando...');
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: res => {
                        if(res.success){
                            toastr.success(res.message, 'Bien hecho');
                            $('.modal-option').modal('hide');
                            $(this).trigger('reset');
                            $('#assets-list').html(`<tr id="tr-empty"><td colspan="5" class="text-center">Ningun activo seleccionado</td></tr>`);
                            list();
                            
                            if(res.rotation){
                                window.open(`{{ url('admin/people/rotation') }}/${res.rotation.id}`, '_blank').focus();
                            }
                            if(res.person_asset){
                                window.open(`{{ url('admin/people') }}/${res.person_id}/assets/${res.person_asset.id}/print`, '_blank').focus();
                            }
                        }else{
                            toastr.error(res.message, 'Error');
                        }
                        $('.form-submit .btn-submit').val('Guardar');
                        setTimeout(() => {
                            $('.form-submit .btn-submit').removeAttr('disabled');
                        }, 0);
                    }
                });
            });

            $('#input-date').change(function(){
                $('#details-attendaces').html('<tr><td colspan="3">Obteniendo datos...</td></tr>');
                $.post(urlListAttendances, $('#options-attendance-form').serialize(), function(res){
                    $('#details-attendaces').empty();
                    res.attendances.map((item, index) => {
                        $('#details-attendaces').append(`
                            <tr id="tr-${index}">
                                <td>${index +1}</td>
                                <td id="td-${index}">
                                    <span>${moment(item.hora).format('HH:mm:ss')}</span>
                                    <div class="input-group" id="input-group-hour-${index}" style="display:none">
                                        <input type="time" id="input-edit-hour-${index}" class="form-control" onchange="setNewHour(${index})" value="${moment(item.hora).format('HH:mm:ss')}" />
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="button" onclick="tdUpdate(${index})" style="margin-top: -1px" title="Guardar">
                                                <i class="voyager-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-link" title="Agregar nuevo" id="btn-add-${index}" onclick="trAdd(${index}, ${res.person_id})" style="${index != res.attendances.length -1 ? 'display:none' : ''}"><i class="voyager-plus text-success"></i></button>
                                    <button type="button" class="btn btn-link" title="Editar" id="btn-edit-${index}" onclick="tdEdit(${index}, ${res.person_id})"><i class="voyager-edit text-info"></i></button>
                                    <button type="button" class="btn btn-link" title="Eliminar" id="btn-delete-${index}" onclick="tdDelete(${index}, ${res.person_id})"><i class="voyager-trash text-danger"></i></button>
                                </td>
                            </tr>
                        `);
                        $('#input-ci').val(item.ci)
                    });
                    $('#details-attendaces').append(`<tr><td colspan="3">última sincronización <b class="text-primary">${moment(res.last_attendance.fecha).format('DD [de] MMMM [de] YYYY')}, ${moment(res.last_attendance.hora).format('HH:mm:ss')}</b></td></tr>`);
                    if(!res.attendances.length){
                        $('#details-attendaces').html(`<tr id="tr-0"><td colspan="3">No hay marcaciones <button type="button" class="btn btn-link" title="Agregar nuevo" id="btn-add-0" onclick="trAdd(0, ${res.person_id})"><i class="voyager-plus text-success"></i></button></td></tr>`);
                    }
                });
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let url = '{{ url("admin/people/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}/${search}?paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    $('#div-results').loading('toggle');
                }
            });
        }

        function generateNumberRow(){
            let number = 1;
            $('.td-assets').each(function(){
                $(this).text(number);
                number++;
            });

            // Mostrar/Ocultar en mensaje de "tabla vacía"
            if(number > 1){
                $('#tr-empty').fadeOut('fast');
            }else{
                $('#tr-empty').fadeIn('fast');
            }
        }

        function deleteAssetTr(id){
            $(`#tr-asset-${id}`).remove();
            generateNumberRow();
        }

        // Edit attendance
        function tdEdit(index, person_id){
            $('#options-attendance-form').attr('action', "{{ url('admin/people') }}/"+person_id+"/attendance/update");
            $(`#details-attendaces #td-${index} span`).fadeOut('fast');
            $(`#details-attendaces #btn-add-${index}`).fadeOut('fast');
            $(`#details-attendaces #btn-edit-${index}`).fadeOut('fast');
            $(`#details-attendaces #btn-delete-${index}`).fadeOut('fast');
            $(`#details-attendaces #td-${index} #input-group-hour-${index}`).fadeIn('fast');
            $(`#input-current-hour`).val($(`#details-attendaces #td-${index} #input-edit-hour-${index}`).val());
            $(`#input-new-hour`).val($(`#details-attendaces #td-${index} #input-edit-hour-${index}`).val());
        }

        // Edit attendance
        function trAdd(index, person_id){
            $('#options-attendance-form').attr('action', "{{ url('admin/people') }}/"+person_id+"/attendance/store");
            $(`#tr-${index}`).after(`
                <tr id="tr-add-${index}">
                    <td></td>
                    <td>
                        <div class="input-group" id="input-group-hour-${index}">
                            <input type="time" id="input-add-hour-${index}" class="form-control" />
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="tdSave(${index})" style="margin-top: -1px" title="Guardar">
                                    <i class="voyager-check"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                    <td></td>
                </tr>
            `);
        }

        function setNewHour(index){
            $(`#input-new-hour`).val($(`#details-attendaces #td-${index} #input-edit-hour-${index}`).val());
        }

        function tdSave(index){
            $(`#input-new-hour`).val($(`#input-add-hour-${index}`).val());
            $(`#tr-add-${index}`).html('<tr><td colspan="3">Registrando...</td></tr>');
            $.post($('#options-attendance-form').attr('action'), $('#options-attendance-form').serialize(), function(res){
                $('#input-date').trigger('change');
                if(res.success){
                    toastr.success('Dato registrado', 'Bien hecho');
                }else{
                    toastr.error('Ocurrió un error', 'Error');
                }
            });
        }

        function tdUpdate(index){
            $.post($('#options-attendance-form').attr('action'), $('#options-attendance-form').serialize(), function(res){
                $(`#details-attendaces #td-${index} #input-group-hour-${index}`).fadeOut('fast');
                $('#input-date').trigger('change');
                if(res.success){
                    toastr.success('Dato actualizado', 'Bien hecho');
                }else{
                    toastr.error('Ocurrió un error', 'Error');
                }
            });
        }

        function tdDelete(index, person_id){
            $(`#input-current-hour`).val($(`#details-attendaces #td-${index} #input-edit-hour-${index}`).val());
            $.post("{{ url('admin/people') }}/"+person_id+"/attendance/delete", $('#options-attendance-form').serialize(), function(res){
                $('#input-date').trigger('change');
                if(res.success){
                    toastr.success('Dato eliminado', 'Bien hecho');
                }else{
                    toastr.error('Ocurrió un error', 'Error');
                }
            });
        }
    </script>
@stop
