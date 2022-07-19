@extends('voyager::master')

@section('page_title', ($type == 'create' ? 'Agregar' : 'Editar').' Pago')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-dollar"></i>
        {{ $type == 'create' ? 'Agregar' : 'Editar' }} Pago
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form id="form" action="{{ $type == 'create' ? route('payments.store') : route('payments.update', ['payment' => $id]) }}" method="post">
                    @csrf
                    @if ($type == 'edit')
                        @method('PUT')
                    @endif
                    <input type="hidden" name="redirect">

                    {{-- Parámetro en caso de que la planilla sea de la base de datos nueva --}}
                    <input type="hidden" name="afp_alt" value="{{ $type == 'edit' ? $data->afp : '' }}">

                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="alert alert-info" role="alert" id="alert-details" style="display: none; margin-bottom: 20px"></div>
                                <div class="col-md-12" style="{{ $type == 'create' ? '' : 'display: none' }}">
                                    <label class="radio-inline"><input type="radio" name="type" class="radio-type" value="1" checked>No centralizadas</label>
                                    @if (!Auth::user()->direccion_administrativa_id)
                                    <label class="radio-inline"><input type="radio" name="type" class="radio-type" value="2">Centralizadas</label>
                                    @endif
                                </div>
                                <div class="form-group col-md-12 div-no-centralizada">
                                    <label for="planilla_haber_id">Planilla</label>
                                    <select name="planilla_haber_id" id="select-planilla_haber_id" class="form-control" required></select>
                                    <input type="hidden" name="afp_edit" id="input-afp_edit">
                                </div>
                                <div class="form-group col-md-3 div-centralizada">
                                    <label for="t_planilla">Tipo de planilla</label>
                                    <select name="t_planilla" id="select-t_planilla" class="form-control select2 select-request">
                                        <option value="1">Funcionamiento</option>
                                        <option value="2">Inversión</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 div-centralizada">
                                    <label for="periodo">Periodo</label>
                                    <input type="number" min="0" name="periodo" id="input-periodo" class="form-control select-request" placeholder="Periodo"  />
                                </div>
                                <div class="form-group col-md-3 div-centralizada">
                                    <label for="afp">Tipo de AFP</label>
                                    <select name="afp" id="select-afp" class="form-control select2 select-request">
                                        {{-- <option value="">Todas las AFP</option> --}}
                                        <option value="1">Futuro</option>
                                        <option value="2">Previsión</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 div-centralizada">
                                    <label for="centralize_code">Código centralizado</label>
                                    <select name="centralize_code" id="select-centralize_code" class="form-control select2 select-request">
                                    </select>
                                </div>
                                @if ($type == 'create')
                                <div class="form-group col-md-12 div-manual" style="display: none">
                                    <label for="planilla_haber_id">Planilla manual</label>
                                    <select name="spreadsheet_id" id="select-planilla_haber_id_manual" class="form-control select2">
                                    <option value="">Seleccione la planilla manual</option>
                                    @foreach (App\Models\Spreadsheet::where('deleted_at', NULL)->get() as $item)
                                    <option value="{{ $item->id }}" data-total="{{ $item->total }}" data-total_afp="{{ $item->total_afp }}">{{ $item->codigo_planilla }} | {{ $item->afp_id == 1 ? 'Futuro' : 'Previsión' }} Bs. {{ number_format($item->total, 2, ',', '.') }} - {{ $item->people }} Personas</option>
                                    @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-12" style="margin: 0px">
                                    <br>
                                    <h4>AFP's  <hr> </h4>
                                </div>
                                {{-- <div class="form-group col-md-6">
                                    <label for="payment_id">ID de pago</label>
                                    <input type="text" name="payment_id" class="form-control" value="{{ $type == 'edit' ? $data->payment_id : '' }}">
                                </div> --}}
                                <div class="form-group col-md-6">
                                    <label for="recipe_number_afp">N&deg; de recibo AFP</label>
                                    <input type="text" name="recipe_number_afp" class="form-control" value="{{ $type == 'edit' ? $data->recipe_number_afp : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fpc_number">N&deg; de FPC</label>
                                    <input type="number" class="form-control" name="fpc_number" value="{{ $type == 'edit' ? $data->fpc_number : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_payment_afp">Fecha de pago a AFP</label>
                                    <input type="date" class="form-control" name="date_payment_afp" max="{{ date('Y-m-d') }}" value="{{ $type == 'edit' ? $data->date_payment_afp : '' }}" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="check_number_afp">N&deg; de cheque(s) AFP</label>
                                    <input type="text" name="check_number_afp" class="form-control" value="{{ $type == 'edit' ? $data->check_number_afp : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="penalty_payment">Multa</label>
                                    <input type="number" step="0.01" min="0" name="penalty_payment" class="form-control" value="{{ $type == 'edit' ? $data->penalty_payment : '' }}">
                                </div>
                                <div class="col-md-12" style="margin: 0px">
                                    <br>
                                    <h4>Caja Cordes  <hr> </h4>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_payment_cc">Fecha de pago a Caja Cordes</label>
                                    <input type="date" class="form-control" name="date_payment_cc" max="{{ date('Y-m-d') }}" value="{{ $type == 'edit' ? $data->date_payment_cc : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="deposit_number">N&deg; de deposito</label>
                                    <input type="number" class="form-control" name="deposit_number" value="{{ $type == 'edit' ? $data->deposit_number : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gtc_number">N&deg; de GTC-11</label>
                                    <input type="number" class="form-control" name="gtc_number" value="{{ $type == 'edit' ? $data->gtc_number : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipe_number">N&deg; de recibo</label>
                                    <input type="number" class="form-control" name="recipe_number" value="{{ $type == 'edit' ? $data->recipe_number : '' }}">
                                </div>
                                {{-- <div class="form-group col-md-6">
                                    <label for="check_id">ID de pago</label>
                                    <input type="text" name="check_id" class="form-control" value="{{ $type == 'edit' ? $data->check_id : '' }}">
                                </div> --}}
                                <div class="form-group col-md-6">
                                    <label for="penalty_check">Multa</label>
                                    <input type="number" step="0.01" min="0" name="penalty_check" class="form-control" value="{{ $type == 'edit' ? $data->penalty_check : '' }}">
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
            @if($type == 'create')
                $("#select-planilla_haber_id").select2({
                    ajax: {
                        url: "{{ url('admin/planillas/pagos/search/id') }}",
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
                            return `${data.idPlanillaprocesada ? data.idPlanillaprocesada : String(data.id).padStart(6, 0)} - ${data.Afp == 1 ? 'AFP Futuro' : 'AFP Previsión'}`;
                        }
                    }
                }).change(function(){
                    $('#select-checks_beneficiary_id').val('').trigger('change');
                    if(planillaSelect){
                        
                        // Si no es una planilla del anterior sistema se obtiene la afp para alacenarla en el controlador
                        if(!planillaSelect.idPlanillaprocesada){
                            $('#form input[name="afp_alt"]').val(planillaSelect.Afp);
                        }else{
                            $('#form input[name="afp_alt"]').val('');
                        }

                        $('#alert-details').fadeIn();
                        $('#alert-details').html(`
                            Cantidad de personas: <b>${planillaSelect.cantidad_personas}</b> <br>
                            Periodo: <b>${planillaSelect.Periodo}</b> <br>
                            Monto total: <b>${new Intl.NumberFormat('es-ES').format(planillaSelect.total_ganado.toFixed(2))} Bs.</b>
                        `);
                    }
                });
                $('#select-planilla_haber_id').select2('open');
            @else
                let data = @json($data);
                let planilla = @json($planilla);
                if(planilla !== null){
                    $("#select-planilla_haber_id").append(`<option value="${planilla.idPlanillaprocesada}">${planilla.idPlanillaprocesada} - ${planilla.Afp == 1 ? 'Futuro' : 'Previsión'}</option>`);
                    $('#input-afp_edit').val(planilla.Afp)
                }else if(data){
                    if(data.spreadsheet){
                        $("#select-planilla_haber_id").append(`<option value="${data.spreadsheet.id}">${data.spreadsheet.codigo_planilla} - ${data.spreadsheet.afp_id == 1 ? 'Futuro' : 'Previsión'}</option>`);
                    }else{
                        $("#select-planilla_haber_id").append(`<option value="${data.paymentschedule.id}">${String(data.paymentschedule.id).padStart(6, '0')} - ${data.afp == 1 ? 'Futuro' : 'Previsión'}</option>`);
                    }
                }
            @endif

            @if($type == 'create')
            $('#form').submit(function(e){
                e.preventDefault();
                $('.page-content').loading({message: 'Guardando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.data == 'success'){
                        toastr.success('Pago agregado correctamente.', 'Bien hecho!');
                    }else{
                        toastr.error('Ocuarrió un error.', 'Error!');
                    }
                    $('.page-content').loading('toggle');
                    $('.alert-info').fadeOut();
                });
            });
            @endif

            $('.radio-type').click(function(){
                let type = $(".radio-type:checked").val();
                switch (type) {
                    case '1':
                        $('.div-no-centralizada').fadeIn('fast', () => {
                            $('.div-centralizada').fadeOut('fast');
                            $('.div-manual').fadeOut('fast');
                            $('#select-planilla_haber_id').attr('required', 'required')
                            $('#input-periodo').removeAttr('required');
                            $('#select-planilla_haber_id_manual').removeAttr('required');
                            $('#select-checks_beneficiary_id').val('').trigger('change');
                            $('#select-planilla_haber_id_manual').val('').trigger('change');
                            $('#select-planilla_haber_id').val('').trigger('change');
                        });
                        break;
                    case '2':
                        $('.div-centralizada').fadeIn('fast', () => {
                            $('.div-no-centralizada').fadeOut('fast');
                            $('.div-manual').fadeOut('fast');
                            $('#input-periodo').attr('required', 'required')
                            $('#select-planilla_haber_id').removeAttr('required');
                            $('#select-planilla_haber_id_manual').removeAttr('required');
                            $('#select-checks_beneficiary_id').val('').trigger('change');
                            $('#select-planilla_haber_id').val('').trigger('change');
                            $('#select-planilla_haber_id_manual').val('').trigger('change');
                            $('#input-periodo').val('');
                        });
                        break;
                    case '3':
                        $('.div-manual').fadeIn('fast', () => {
                            $('.div-centralizada').fadeOut('fast');
                            $('.div-no-centralizada').fadeOut('fast');
                            $('#select-planilla_haber_id_manual').attr('required', 'required');
                            $('#select-planilla_haber_id').removeAttr('required');
                            $('#input-periodo').removeAttr('required');
                            $('#select-checks_beneficiary_id').val('').trigger('change');
                            $('#select-planilla_haber_id').val('').trigger('change');
                            $('#select-planilla_haber_id_manual').val('').trigger('change');
                        });
                        break;
                }

                $('#alert-details').fadeOut();
                planillaSelect = null;
            });

            $('.select-request').change(function(){
                getPlanillas('{{ route("planillas.pagos.centralizada.search") }}');
            });

            $('#btn-reset').click(function(){
                $('#select-planilla_haber_id').val('').trigger('change');
                setTimeout(() => {
                    $('#select-status').val(1);
                    $('#select-planilla_haber_id').select2('open');
                    $('.alert-info').fadeOut();
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
                        ${data.idPlanillaprocesada ? data.idPlanillaprocesada : String(data.id).padStart(6, 0)} <br>
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
