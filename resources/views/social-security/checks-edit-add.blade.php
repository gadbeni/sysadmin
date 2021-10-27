@extends('voyager::master')

@section('page_title', ($type == 'create' ? 'Agregar' : 'Editar').' Cheque')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i>
        {{ $type == 'create' ? 'Agregar' : 'Editar' }} Cheque
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form id="form" action="{{ $type == 'create' ? route('checks.store') : route('checks.update', ['check' => $id]) }}" method="post">
                    @csrf
                    @if ($type == 'edit')
                        @method('PUT')
                    @endif
                    <input type="hidden" name="redirect">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="alert alert-info" role="alert" id="alert-details" style="display: none; margin-bottom: 20px"></div>
                            <div class="row">
                                <div class="col-md-12" style="{{ $type == 'create' ? '' : 'display: none' }}">
                                    <label class="radio-inline"><input type="radio" name="type" class="radio-type" value="1" checked>No centralizadas</label>
                                    <label class="radio-inline"><input type="radio" name="type" class="radio-type" value="2">Centralizadas</label>
                                </div>
                                <div class="form-group col-md-6 div-no-centralizada">
                                    <label for="planilla_haber_id">Planilla(s)</label>
                                    <select name="planilla_haber_id" id="select-planilla_haber_id" class="form-control"></select>
                                </div>
                                <div class="form-group col-md-6 div-centralizada">
                                    {{-- Nota: En caso de obtener estos datos en más de una consulta se debe hacer un metodo para hacerlo --}}
                                    <label for="t_planilla">Tipo de planilla</label>
                                    <select name="t_planilla" id="select-t_planilla" class="form-control select2 select-request">
                                        <option value="1">Funcionamiento</option>
                                        <option value="2">Inversión</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 div-centralizada">
                                    <label for="periodo">Periodo</label>
                                    <input type="number" min="0" name="periodo" id="input-periodo" class="form-control select-request" placeholder="Periodo"  />
                                </div>
                                <div class="form-group col-md-6 div-centralizada">
                                    <label for="afp">Tipo de AFP</label>
                                    <select name="afp" id="select-afp" class="form-control select2 select-request">
                                        {{-- <option value="">Todas las AFP</option> --}}
                                        <option value="1">Futuro</option>
                                        <option value="2">Previsión</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="amount">N&deg; de cheque</label>
                                    <input type="text" class="form-control" name="number" value="{{ $type == 'edit' ? $data->number : '' }}" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="beneficiary">Beneficiario</label>
                                    {{-- <input type="text" class="form-control" name="beneficiary" value="{{ $type == 'edit' ? $data->beneficiary : '' }}" required /> --}}
                                    <select name="checks_beneficiary_id" id="select-checks_beneficiary_id" class="form-control" required>
                                        <option value="">-- Seleccione al beneficiario --</option>
                                        @foreach (\App\Models\ChecksBeneficiary::with('type')->where('deleted_at', NULL)->get() as $item)
                                        <option value="{{ $item->id }}" data-type='@json($item->type)'>{{ $item->full_name }} - {{ $item->type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="amount">Monto</label>
                                    <input type="number" class="form-control" min="0" step="0.01" name="amount" id="input-amout" value="{{ $type == 'edit' ? $data->amount : '' }}" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_print">Fecha de impresión</label>
                                    <input type="date" class="form-control" name="date_print" max="{{ date('Y-m-d') }}" value="{{ $type == 'edit' ? $data->date_print : '' }}" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">Estado</label>
                                    <select name="status" id="select-status" class="form-control">
                                        <option value="0">Anulado</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">Pagado</option>
                                        <option value="3">Vencido</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="observations">Observaciones</label>
                                    <textarea class="form-control" name="observations" rows="3">{{ $type == 'edit' ? $data->observations : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            {{-- <button type="button" id="btn-submit-redirect" class="btn btn-primary">Guardar y volver aquí <i class="voyager-refresh"></i></button> --}}
                            @if ($type == 'create')
                            <button type="reset" id="btn-reset" class="btn btn-default">Vaciar <i class="voyager-refresh"></i></button>
                            @endif
                            <button type="submit" class="btn btn-primary">Guardar <i class="voyager-check"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .div-centralizada{
            display: none
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        var planillaSelect = null;
        $(document).ready(function(){
            $('#select-checks_beneficiary_id').select2();
            @if($type == 'create')
                $("#select-planilla_haber_id").select2({
                    ajax: {
                        url: "{{ url('admin/planillas/search/id') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function (data) {
                            return {results: data};
                        },
                        cache: true
                    },
                    placeholder: 'Buscar planilla',
                    language: {
                        inputTooShort: function (data) {
                            return `Ingrese al menos ${data.minimum - data.input.length} caracteres`;
                        }
                    },
                    minimumInputLength: 3,
                    templateResult: formatResult,
                    templateSelection: data => {
                        if(data.id){
                            planillaSelect = data;
                            return `${data.idPlanillaprocesada} - ${data.Afp == 1 ? 'AFP Futuro' : 'AFP Previsión'}`;
                        }
                    }
                }).change(function(){
                    $('#select-checks_beneficiary_id').val('').trigger('change');
                    if(planillaSelect){
                        $('#alert-details').fadeIn();
                        $('#alert-details').html(`
                            Cantidad de personas: <b>${planillaSelect.cantidad_personas}</b> <br>
                            Periodo: <b>${planillaSelect.Periodo}</b> <br>
                            Monto total: <b>${new Intl.NumberFormat('es-ES').format(planillaSelect.total_ganado.toFixed(2))} Bs.</b>
                        `);
                    }
                });

                $('#select-status').val(1);
		        $('#select-planilla_haber_id').select2('open');
            @else
                let data = @json($data);
                let planilla = @json($planilla);
                $("#select-planilla_haber_id").append(`<option value="${planilla.idPlanillaprocesada}">${planilla.idPlanillaprocesada} - ${planilla.Afp == 1 ? 'Futuro' : 'Previsión'}</option>`)
                $('#select-checks_beneficiary_id').val(data.check_beneficiary.id).trigger('change');
                $('#select-status').val(data.status);
            @endif

            @if($type == 'create')
            $('#form').submit(function(e){
                e.preventDefault();
                $('.page-content').loading({message: 'Guardando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.data == 'success'){
                        toastr.success('Cheque agregado correctamente.', 'Bien hecho!');
                    }else{
                        toastr.error('Ocuarrió un error.', 'Error!');
                    }
                    $('.page-content').loading('toggle');
                });
            });
            @endif

            $('#select-checks_beneficiary_id').change(function(){
                let type = $('#select-checks_beneficiary_id option:selected').data('type');
                if(type && planillaSelect){

                    let aporte_patronal = (planillaSelect.total_ganado * 0.05) + planillaSelect.riesgo_comun;
                    let sip = planillaSelect.total_aportes_afp + aporte_patronal - (planillaSelect.total_ganado * (5.5 / 100));
                    let aporte_solidario = planillaSelect.total_ganado * 0.035;
                    let aporte_vivienda = planillaSelect.total_ganado * 0.02;
                    let amount = 0;
                    if(type.percentage != null){
                        let porcentaje = type.percentage/100;
                        amount = planillaSelect.total_ganado * porcentaje;
                        $('#input-amout').val(amount.toFixed(2));
                    }else{
                        switch (type.id) {
                            case 4:
                                amount = sip + aporte_solidario;
                                break;
                            case 5:
                                amount = sip;
                                break;
                            case 6:
                                amount = sip + aporte_solidario + aporte_vivienda;
                                break;
                            default:
                                amount = 0;
                                break;
                        }
                        $('#input-amout').val(amount.toFixed(2));
                    }
                }else{
                    $('#input-amout').val('');
                }
            });

            $('.radio-type').click(function(){
                let type = $(".radio-type:checked").val();
                if(type == 1){
                    $('.div-no-centralizada').fadeIn('fast', () => {
                        $('.div-centralizada').fadeOut('fast');
                        $('#select-planilla_haber_id').attr('required', 'required')
                        $('#input-periodo').removeAttr('required');
                        $('#select-checks_beneficiary_id').val('').trigger('change');
                    });
                }else{
                    $('.div-centralizada').fadeIn('fast', () => {
                        $('.div-no-centralizada').fadeOut('fast');
                        $('#select-checks_beneficiary_id').val('').trigger('change');
                        $('#select-planilla_haber_id').val('').trigger('change');
                        $('#select-planilla_haber_id').removeAttr('required');
                        $('#input-periodo').val('');
                    });
                }
                $('#alert-details').fadeOut();
                planillaSelect = null;
            });

            $('.select-request').change(function(){
                getPlanillas('{{ route("planillas.centralizada.search") }}');
            });

            $('#btn-reset').click(function(){
                $('#select-planilla_haber_id').val('').trigger('change');
                setTimeout(() => {
                    $('#select-status').val(1);
                    $('#select-planilla_haber_id').select2('open');
                }, 0);
            });
        });

        function formatResult(data) {
            if (data.loading) {
                return 'Buscando...';
            }
            var $container = $(
                `<div class="option-select2-custom">
                    <h4>
                        ${data.idPlanillaprocesada} <br>
                        <p style="font-size: 13px; margin-top: 5px">
                            ${data.Afp == 1 ? 'AFP Futuro' : 'AFP Previsión'} - ${data.Periodo} <br>
                            ${new Intl.NumberFormat('es-ES').format(data.total_ganado.toFixed(2))} Bs. - ${data.cantidad_personas} Persona(s)
                        </p>
                    </h4>
                </div>`
            );

            return $container;
        }
    </script>
@stop
