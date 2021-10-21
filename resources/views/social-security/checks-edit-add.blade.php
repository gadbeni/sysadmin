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
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="planilla_haber_id">Planilla(s)</label>
                                    <select name="planilla_haber_id" id="select-planilla_haber_id" class="form-control" required></select>
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
        
    </style>
@stop

@section('javascript')
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
                });

                $('#select-status').val(1);
            @else
                let data = @json($data);
                $("#select-planilla_haber_id").append(`<option value="${data.planilla_haber_id}">${data.planilla_haber_id}</option>`)
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
                    let porcentaje = type.percentage/100;
                    let amount = planillaSelect.total_ganado * porcentaje;
                    $('#input-amout').val(amount.toFixed(2));
                }
            });

            $('#btn-reset').click(function(){
                $('#select-planilla_haber_id').val('').trigger('change')
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
                            ${data.total_ganado.toFixed(2)} Bs. - ${data.cantidad_personas} Persona(s)
                        </p>
                    </h4>
                </div>`
            );

            return $container;
        }
    </script>
@stop
