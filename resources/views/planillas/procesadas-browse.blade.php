@extends('voyager::master')

@section('page_title', 'Viendo Planillas Procesadas')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-file-text"></i> Planillas Procesadas
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form id="form-search" action="{{ route('planillas.search') }}" method="post">
                                @csrf
                                <div class="form-group text-right">
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="1" checked>Centralizada</label>
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="0">No centralizada</label>
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="2">Por CI</label>
                                </div>
                                {{-- Opciones que se despliegan cuando se hace check en la opción "No centralizada" --}}
                                <div class="input-no-centralizada" style="display: none">
                                    <div class="form-group">
                                        <input type="number" step="1" name="planilla_id" class="form-control" placeholder="N&deg; de planilla">
                                    </div>
                                    <div class="form-group">
                                        <select name="afp_no_centralizada" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            <option value="1">Futuro</option>
                                            <option value="2">Previsión</option>
                                        </select>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info" style="margin-top: 0px; padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                </div>

                                <div class="input-ci" style="display: none;">
                                    <div class="input-group">
                                        <input type="text" step="1" name="ci" class="form-control" placeholder="Cédula de Identidad">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit" style="margin-top: -1px; height: 35px; padding: 5px 15px"><i class="voyager-search"></i></button>
                                        </span>
                                    </div>
                                    <select name="pagada" class="form-control select2" style="margin-bottom: 10px">
                                        <option value="">Todos</option>
                                        <option value="1">Pagos pendientes</option>
                                    </select>
                                    <div class="clearfix"></div>
                                    <br>
                                </div>
                                
                                {{-- Opciones que se despliegan cuando se hace check en la opción "No centralizada" --}}
                                <div class="input-centralizada">
                                    <div class="form-group">
                                        {{-- Nota: En caso de obtener estos datos en más de una consulta se debe hacer un metodo para hacerlo --}}
                                        <select name="t_planilla" class="form-control select2">
                                            <option selected disabled>Tipo de planilla</option>
                                            <option value="1">Funcionamiento</option>
                                            <option value="2">Inversión</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" min="0" name="periodo" class="form-control" placeholder="Periodo"  />
                                    </div>
                                    <div class="form-group">
                                        <select name="afp" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            <option value="1">Futuro</option>
                                            <option value="2">Previsión</option>
                                        </select>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="position: fixed; bottom: 40px; right: 20px; z-index: 10;padding: 10px 20px; background-color: white; box-shadow: 0px 0px 15px 10px white; border-radius: 5px">
            <button class="btn btn-success btn-lg btn-increment-ticket" ><h4>{{ setting('auxiliares.numero_ticket') == 0  ? 'Iniciar' : 'Siguiente' }} <span class="voyager-double-right"></span></h4></button>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div id="div-results" style="min-height: 100px"></div>
        </div>
    </div>

    {{-- Pago listo modal --}}
    <form id="form-abrir-planilla" action="{{ route('planillas.details.open') }}" method="post">
        @csrf
        <input type="hidden" name="id">
        <div class="modal modal-success fade" tabindex="-1" id="pago-listo_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Habilitar planilla</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Desea habilitar la planilla para realizar pagos?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        {{-- <button type="submit" class="btn btn-default">Test pagar</button> --}}
                        <button type="button" class="btn btn-success" onclick="sendForm('form-abrir-planilla', 'Planilla habilitada para pago exitosamente.')">Sí, habilitar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Pagar modal --}}
    <form id="form-pagar" action="{{ route('planillas.details.payment') }}" method="post">
        @csrf
        <input type="hidden" name="cashier_id">
        <input type="hidden" name="id">
        <input type="hidden" name="name">
        <input type="hidden" name="amount">
        <div class="modal modal-success fade" tabindex="-1" id="pagar-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Realizar pago</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Desea realizar el pago del siguiente funcionario?</p> <br>
                        <table width="100%">
                            <tr>
                                <td><small>Item</small></td>
                                <td><h4 id="label-item"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Nombre Completo</small></td>
                                <td><h4 id="label-name"></h4></td>
                            </tr>
                            <tr>
                                <td><small>AFP</small></td>
                                <td><h4 id="label-afp"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Mes</small></td>
                                <td><h4 id="label-month"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Sueldo Mensual</small></td>
                                <td><h4 id="label-salary"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Días Trabajados</small></td>
                                <td><h4 id="label-days"></h4></td>
                            </tr>
                            <tr>
                                <td><b>Liquido Pagable</b></td>
                                <td><h1 class="text-right" id="label-amount"></h1></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="form-group text-right" style="margin-top: 20px">
                                        <label class="checkbox-inline"><input type="checkbox" id="check-print" value="1" required>Imprimir recibo</label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-md-12" style="margin: 0px">
                                <textarea name="observations" class="form-control" rows="3" placeholder="Observaciones..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        {{-- <button type="submit" class="btn btn-default">test</button> --}}
                        <button type="button" class="btn btn-success btn-submit" onclick="sendForm('form-pagar', 'Pago realizado exitosamente.')">Sí, pagar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Pagar aguinaldo modal --}}
    <form id="form-pagar-aguinaldo" action="{{ route('planillas.details.payment') }}" method="post">
        @csrf
        <input type="hidden" name="cashier_id">
        <input type="hidden" name="aguinaldo_id">
        <input type="hidden" name="name">
        <input type="hidden" name="amount">
        <div class="modal modal-success fade" tabindex="-1" id="pagar-aguinaldo-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Realizar pago de aguinaldo</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Desea realizar el pago de aguinaldo del siguiente funcionario?</p> <br>
                        <table width="100%">
                            <tr>
                                <td style="width: 150px"><small>Item</small></td>
                                <td><h4 id="label-item-bonus"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Nombre Completo</small></td>
                                <td><h4 id="label-name-bonus"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Días Trabajados</small></td>
                                <td><h4 id="label-days-bonus"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Sueldo promedio</small></td>
                                <td><h4 id="label-amount-avg-bonus"></h4></td>
                            </tr>
                            <tr>
                                <td><b>Liquido Pagable</b></td>
                                <td><h1 class="text-right" id="label-amount-bonus"></h1></td>
                            </tr>
                            {{-- <tr>
                                <td colspan="2">
                                    <div class="form-group text-right" style="margin-top: 20px">
                                        <label class="checkbox-inline"><input type="checkbox" id="check-print" value="1" required>Imprimir recibo</label>
                                    </div>
                                </td>
                            </tr> --}}
                        </table>
                        <div class="row">
                            <div class="col-md-12" style="margin: 0px">
                                <textarea name="observations" class="form-control" rows="3" placeholder="Observaciones..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        {{-- <button type="submit" class="btn btn-default">test</button> --}}
                        <button type="button" class="btn btn-success btn-submit" onclick="sendForm('form-pagar-aguinaldo', 'Pago realizado exitosamente.')">Sí, pagar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="form-cerrar-planilla" action="{{ route('planillas.update.status') }}" method="post">
        @csrf
        <input type="hidden" name="status" value="3">
        <div class="modal modal-danger fade" tabindex="-1" id="cerrar-planilla-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Cerrar planilla</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Desea dar la planilla como pagada?</p>
                        <input type="hidden" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        {{-- <button type="submit" class="btn btn-default">Test pagar</button> --}}
                        <button type="button" class="btn btn-danger" onclick="sendForm('form-cerrar-planilla', 'Planilla pagada exitosamente')">Sí, pagar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')

@stop

@section('javascript')

    {{-- Socket.io --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.0/socket.io.js" integrity="sha512-nYuHvSAhY5lFZ4ixSViOwsEKFvlxHMU2NHts1ILuJgOS6ptUmAGt/0i5czIgMOahKZ6JN84YFDA+mCdky7dD8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const socket = io("{{ env('APP_URL') }}"+":3001");
    </script>

    <script>
        $(document).ready(function() {
            $('#form-search').on('submit', function(e){
                e.preventDefault();
                getData();
            });

            $('.radio-tipo_planilla').click(function(){
                let val = $('.radio-tipo_planilla:checked').val();
                switch (val) {
                    case "1":
                        $('.input-centralizada').fadeIn('fast');
                        $('.input-no-centralizada').fadeOut('fast');
                        $('.input-ci').fadeOut('fast');
                        break;
                    case "0":
                        $('.input-no-centralizada').fadeIn('fast');
                        $('.input-centralizada').fadeOut('fast');
                        $('.input-ci').fadeOut('fast');
                        break;
                    default:
                        $('.input-ci').fadeIn('fast');
                        $('.input-centralizada').fadeOut('fast');
                        $('.input-no-centralizada').fadeOut('fast');
                        break;
                }
            });

            $('.btn-increment-ticket').click(function(){
                $('.btn-increment-ticket').attr('disabled', true);
                $.post("{{ url('admin/plugins/cashiers/tickets/set') }}", {
                    _token: '{{ csrf_token() }}',
                }, function(data){
                    $('.btn-increment-ticket').html('<h4>Siguiente <span class="voyager-double-right"></span></h4>');
                    toastr.info('Ticket solicitado #'+data.ticket, 'Información');
                    socket.emit(`set new ticket`, data);
                });
                setTimeout(() => {
                    $('.btn-increment-ticket').attr('disabled', false);
                }, 5000);
            })
        });

        function getData(){
            $('#div-results').empty();
            $('#div-results').loading({message: 'Cargando...'});
            $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                $('#div-results').html(res);
            })
            .fail(function() {
                toastr.error('Ocurrió un error!', 'Oops!');
            })
            .always(function() {
                $('#div-results').loading('toggle');
                $('html, body').animate({
                    scrollTop: $("#div-results").offset().top - 70
                }, 500);
            });
        }

        function sendForm(formId, message){
            $('.btn-submit').attr('disabled', true);
            $.post($('#'+formId).attr('action'), $('#'+formId).serialize(), function(res){
                if(res.success){
                    toastr.success(message, 'Bien hecho!');
                    getData();
                    if(formId == 'form-pagar' && $('#check-print').is(':checked')){
                        window.open("{{ url('admin/planillas/pago/print') }}/"+res.payment_id, "Recibo", `width=700, height=400`)
                        $('#form-pagar').trigger("reset");
                    }
                }else{
                    toastr.error(res.message ? res.message : 'Ocurrió un error.', 'Oops!')
                }
                $('.btn-submit').attr('disabled', false);
            })
            .fail(function() {
                toastr.error('Ocurrió un error en nuestro servidor.', 'Oops!');
                $('.btn-submit').attr('disabled', false);
            })
            $('.modal').modal('hide');
        }

        function setValuePay(data, cashier){
            let months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $('#label-item').html(`N&deg; ${data.item}`);
            $('#label-name').text(data.Nombre_Empleado);
            $('#label-afp').html(data.Afp == 1 ? '<label class="label label-danger">Futuro</label>' : '<label class="label label-primary">Previsión</label>');
            $('#label-salary').html(`${data.Sueldo_Mensual.toFixed(2)} <small>Bs.</small>`);
            $('#label-month').text(months[parseInt(data.Mes)]);
            $('#label-days').html(`${data.Dias_Trabajado} <small>Días</small>`);
            $('#label-amount').html(`${data.Tplanilla == 3 ? data.Total_Ganado.toFixed(2) : data.Liquido_Pagable.toFixed(2)} <small>Bs.</small>`);
            $('#form-pagar input[name="id"]').val(data.ID);
            $('#form-pagar input[name="name"]').val(data.Nombre_Empleado);
            $('#form-pagar input[name="amount"]').val(data.Tplanilla == 3 ? data.Total_Ganado : data.Liquido_Pagable);
            $('#form-pagar input[name="cashier_id"]').val(cashier.id);
        }

        function setValuePayBonus(data, cashier){
            $('#label-item-bonus').html(`N&deg; ${data.item}`);
            $('#label-name-bonus').text(data.funcionario);
            $('#label-days-bonus').html(`${data.nro_dias} <small>Días</small>`);
            $('#label-amount-avg-bonus').html(`${data.sueldo_promedio} <small>Bs.</small>`);
            $('#label-amount-bonus').html(`${data.liquido_pagable} <small>Bs.</small>`);
            $('#form-pagar-aguinaldo input[name="aguinaldo_id"]').val(data.id);
            $('#form-pagar-aguinaldo input[name="name"]').val(data.funcionario);
            $('#form-pagar-aguinaldo input[name="amount"]').val(data.liquido_pagable);
            $('#form-pagar-aguinaldo input[name="cashier_id"]').val(cashier ? cashier.id : '');
        }

        function setValueOpen(id){
            $('#form-abrir-planilla input[name="id"]').val(id);
        }

        function setValueClose(id){
            $('#form-cerrar-planilla input[name="id"]').val(id);
        }
    </script>
@stop
