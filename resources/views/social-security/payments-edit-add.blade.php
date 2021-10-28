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
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="alert alert-info" role="alert" id="alert-details" style="display: none; margin-bottom: 20px"></div>
                                <div class="col-md-12" style="{{ $type == 'create' ? '' : 'display: none' }}">
                                    <label class="radio-inline"><input type="radio" name="type" class="radio-type" value="1" checked>No centralizadas</label>
                                    <label class="radio-inline"><input type="radio" name="type" class="radio-type" value="2">Centralizadas</label>
                                </div>
                                <div class="form-group col-md-12 div-no-centralizada">
                                    <label for="planilla_haber_id">Planilla</label>
                                    <select name="planilla_haber_id" id="select-planilla_haber_id" class="form-control" required></select>
                                </div>
                                <div class="form-group col-md-4 div-centralizada">
                                    {{-- Nota: En caso de obtener estos datos en más de una consulta se debe hacer un metodo para hacerlo --}}
                                    <label for="t_planilla">Tipo de planilla</label>
                                    <select name="t_planilla" id="select-t_planilla" class="form-control select2 select-request">
                                        <option value="1">Funcionamiento</option>
                                        <option value="2">Inversión</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4 div-centralizada">
                                    <label for="periodo">Periodo</label>
                                    <input type="number" min="0" name="periodo" id="input-periodo" class="form-control select-request" placeholder="Periodo"  />
                                </div>
                                <div class="form-group col-md-4 div-centralizada">
                                    <label for="afp">Tipo de AFP</label>
                                    <select name="afp" id="select-afp" class="form-control select2 select-request">
                                        {{-- <option value="">Todas las AFP</option> --}}
                                        <option value="1">Futuro</option>
                                        <option value="2">Previsión</option>
                                    </select>
                                </div>
                                <div class="col-md-12" style="margin: 0px">
                                    <br>
                                    <h4>AFP's  <hr> </h4>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="payment_id">ID de pago</label>
                                    <input type="text" name="payment_id" class="form-control" value="{{ $type == 'edit' ? $data->payment_id : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fpc_number">N&deg; de FPC</label>
                                    <input type="number" class="form-control" name="fpc_number" value="{{ $type == 'edit' ? $data->fpc_number : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_payment_afp">Fecha de pago a AFP</label>
                                    <input type="date" class="form-control" name="date_payment_afp" value="{{ $type == 'edit' ? $data->date_payment_afp : '' }}" >
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
                                    <input type="date" class="form-control" name="date_payment_cc" value="{{ $type == 'edit' ? $data->date_payment_cc : '' }}">
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
                                <div class="form-group col-md-6">
                                    <label for="check_id">ID de pago</label>
                                    <input type="text" name="check_id" class="form-control" value="{{ $type == 'edit' ? $data->check_id : '' }}">
                                </div>
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
                    templateResult: formatRepo,
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
                $('#select-planilla_haber_id').select2('open');
            @else
                let data = @json($data);
                let planilla = @json($planilla);
                $("#select-planilla_haber_id").append(`<option value="${planilla.idPlanillaprocesada}">${planilla.idPlanillaprocesada} - ${planilla.Afp == 1 ? 'Futuro' : 'Previsión'}</option>`)
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
                    $('.alert-info').fadeOut();
                }, 0);
            });
        });

        function formatRepo(data) {
            if (data.loading) {
                return 'Buscando...';
            }

            var $container = $(
                `<div class="option-select2-custom">
                    <h4>
                        ${data.idPlanillaprocesada} <br>
                        <p style="font-size: 13px; margin-top: 5px">
                            ${data.Afp == 1 ? 'AFP Futuro' : 'AFP Previsión'} - ${data.Periodo} <br>
                            ${data.total_ganado.toFixed(2)} Bs. - ${data.cantidad_personas} Persona(s)
                        </p>
                    </h4>
                </div>`
            );

            return $container;
        }
    </script>
@stop
