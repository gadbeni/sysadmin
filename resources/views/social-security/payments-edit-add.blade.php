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
                                <div class="form-group col-md-12">
                                    <label for="planilla_haber_id">Planilla(s)</label>
                                    <select name="planilla_haber_id" id="select-planilla_haber_id" class="form-control" required></select>
                                </div>
                                <div class="col-md-12" style="margin: 0px">
                                    <br>
                                    <h4>AFP's  <hr> </h4>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_payment_afp">Fecha de pago a AFP</label>
                                    <input type="date" class="form-control" name="date_payment_afp" value="{{ $type == 'edit' ? $data->date_payment_afp : '' }}" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fpc_number">N&deg; de FPC</label>
                                    <input type="number" class="form-control" name="fpc_number" value="{{ $type == 'edit' ? $data->fpc_number : '' }}">
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
                                    <label for="gtc_number">N&deg; de GTC-11</label>
                                    <input type="number" class="form-control" name="gtc_number" value="{{ $type == 'edit' ? $data->gtc_number : '' }}">
                                </div>
                                {{-- <div class="form-group col-md-4">
                                    <label for="gtc_number">N&deg; de cheque</label>
                                    <input type="number" class="form-control" name="check_number" value="{{ $type == 'edit' ? $data->check_number : '' }}">
                                </div> --}}
                                <div class="form-group col-md-6">
                                    <label for="recipe_number">N&deg; de recibo</label>
                                    <input type="number" class="form-control" name="recipe_number" value="{{ $type == 'edit' ? $data->recipe_number : '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="deposit_number">N&deg; de deposito</label>
                                    <input type="number" class="form-control" name="deposit_number" value="{{ $type == 'edit' ? $data->deposit_number : '' }}">
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
        
    </style>
@stop

@section('javascript')
    <script>
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
                            return `${data.idPlanillaprocesada} - ${data.Afp == 1 ? 'AFP Futuro' : 'AFP Previsión'}`;
                        }
                    }
                });
            @else
                let data = @json($data);
                $("#select-planilla_haber_id").append(`<option value="${data.planilla_haber_id}">${data.planilla_haber_id}</option>`)
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
                });
            });
            @endif

            $('#btn-reset').click(function(){
                $('#select-planilla_haber_id').val('').trigger('change')
            });
        });

        function formatRepo(data) {
            if (data.loading) {
                return 'Buscando...';
            }

            var $container = $(
                `<div class="option-select2-custom">
                    <h4>${data.idPlanillaprocesada} <br> <p style="font-size: 13px; margin-top: 5px">${data.Afp == 1 ? 'AFP Futuro' : 'AFP Previsión'} - ${data.Periodo}</p> </h4>
                </div>`
            );

            return $container;
        }
    </script>
@stop
