@extends('voyager::master')

@section('page_title', 'Pagos de Planilla')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-6" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Pagos de Planilla
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-6 text-right" style="margin-top: 30px">
                            {{-- button selección multiple --}}
                            <a href="#" id="btn-update-multiple" data-url="{{ route('payments.update_multiple') }}" style="display: none" class="btn btn-info btn-multiple" data-toggle="modal" data-target="#update_multiple">
                                <i class="voyager-trash"></i> <span>Editar seleccionados</span>
                            </a>
                            <a href="#" id="btn-delete-multiple" data-url="{{ route('payments.delete_multiple') }}" style="display: none" class="btn btn-danger btn-multiple" data-toggle="modal" data-target="#delete_multiple">
                                <i class="voyager-trash"></i> <span>Eliminar seleccionados</span>
                            </a>
                            <a href="{{ route('payments.create') }}" class="btn btn-success btn-add-new">
                                <i class="voyager-plus"></i> <span>Añadir Pago</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="#" id="form-selection-multiple" method="POST">
                    {{ csrf_field() }}
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-hover"></table>
                            </div>
                        </div>
                    </div>

                    {{-- Modal delete massive --}}
                    <div class="modal modal-danger fade" tabindex="-1" id="delete_multiple" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><i class="voyager-trash"></i> ¿Estás seguro?</h4>
                                </div>
                                <div class="modal-body">
                                    <h4>¿Estás seguro de que quieres eliminar los pagos seleccionados?</h4>
                                </div>
                                <div class="modal-footer">
                                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Eliminar">
                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                        Cancelar
                                    </button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>

                    <div class="modal modal-info fade" tabindex="-1" id="update_multiple" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><i class="voyager-edit"></i> Editar</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-12" style="margin: 0px">
                                        <br>
                                        <h4>AFP's  <hr> </h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="payment_id">ID de pago</label>
                                        <input type="text" name="payment_id" class="form-control" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="fpc_number">N&deg; de FPC</label>
                                        <input type="number" class="form-control" name="fpc_number" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="date_payment_afp">Fecha de pago a AFP</label>
                                        <input type="date" class="form-control" name="date_payment_afp" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="penalty_payment">Multa</label>
                                        <input type="number" step="0.01" min="0" name="penalty_payment" class="form-control" >
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="rate_penalty_payment" id="check-rate_penalty_payment">
                                            <label class="form-check-label" for="check-rate_penalty_payment">prorrateo de monto</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin: 0px">
                                        <br>
                                        <h4>Caja Cordes  <hr> </h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="date_payment_cc">Fecha de pago a Caja Cordes</label>
                                        <input type="date" class="form-control" name="date_payment_cc" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="deposit_number">N&deg; de deposito</label>
                                        <input type="number" class="form-control" name="deposit_number" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="gtc_number">N&deg; de GTC-11</label>
                                        <input type="number" class="form-control" name="gtc_number" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="recipe_number">N&deg; de recibo</label>
                                        <input type="number" class="form-control" name="recipe_number" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="check_id">ID de pago</label>
                                        <input type="text" name="check_id" class="form-control" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="penalty_check">Multa</label>
                                        <input type="number" step="0.01" min="0" name="penalty_check" class="form-control" >
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="rate_penalty_check" id="check-rate_penalty_check">
                                            <label class="form-check-label" for="check-rate_penalty_check">prorrateo de monto</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer text-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-info">
                                        Guardar
                                    </button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <style>
        .btn-multiple{
            display: none;
        }
    </style> --}}
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            let columns = [
                { data: 'checkbox', title: '', orderable: false, searchable: false },
                { data: 'id', title: 'id' },
                { data: 'planilla_id', title: 'Planilla' },
                { data: 'fpc_number', title: 'Nro de FPC' },
                { data: 'gtc_number', title: 'Nro de GTC-11' },
                { data: 'deposit_number', title: 'Nro de deposito' },
                { data: 'user', title: 'Resgistrado por' },
                { data: 'created_at', title: 'Registrado el' },
                { data: 'actions', title: 'Acciones', orderable: false, searchable: false },
            ];
            customDataTable("{{ url('admin/social-security/payments/list') }}", columns, 1);

            $('.btn-multiple').click(function(){
                let url = $(this).data('url');
                let form = $('#form-selection-multiple');
                form.attr('action', url);
            });

            $('#btn-update-multiple').click(function(){
                let id = 0;
                $("#form-selection-multiple input[name='id[]']:checked").each(function() {
                    id = $(this).val();
                });
                $.get("{{ url('admin/social-security/payments') }}/"+id+'?ajax=1', function(data){
                    $('#form-selection-multiple input[name="payment_id"]').val(data.payment.payment_id);
                    $('#form-selection-multiple input[name="fpc_number"]').val(data.payment.fpc_number);
                    $('#form-selection-multiple input[name="date_payment_afp"]').val(data.payment.date_payment_afp);
                    $('#form-selection-multiple input[name="penalty_payment"]').val(data.payment.penalty_payment);
                    $('#form-selection-multiple input[name="date_payment_cc"]').val(data.payment.date_payment_cc);
                    $('#form-selection-multiple input[name="deposit_number"]').val(data.payment.deposit_number);
                    $('#form-selection-multiple input[name="gtc_number"]').val(data.payment.gtc_number);
                    $('#form-selection-multiple input[name="recipe_number"]').val(data.payment.recipe_number);
                    $('#form-selection-multiple input[name="check_id"]').val(data.payment.check_id);
                    $('#form-selection-multiple input[name="penalty_check"]').val(data.payment.penalty_check);
                });

                $('#form-selection-multiple input[name="penalty_payment"]').keypress(function(){
                    $('#check-rate_penalty_payment').attr('checked', 'checked');
                });
                
                $('#form-selection-multiple input[name="penalty_check"]').keypress(function(){
                    $('#check-rate_penalty_check').attr('checked', 'checked');
                });
            });
        });
    </script>
@stop
