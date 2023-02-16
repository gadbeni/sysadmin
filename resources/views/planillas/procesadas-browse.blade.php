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
                            <form id="form-search" action="{{ route('planillas.pagos.search') }}" method="post">
                                @csrf
                                <div class="form-group text-right">
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="1">Centralizada</label>
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="0">No centralizada</label>
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="2" checked>Por CI</label>
                                </div>
                                {{-- Opciones que se despliegan cuando se hace check en la opción "No centralizada" --}}
                                <div class="input-no-centralizada" style="display: none">
                                    <div class="form-group">
                                        <input type="number" step="1" name="planilla_id" class="form-control" placeholder="N&deg; de planilla">
                                    </div>
                                    <div class="form-group">
                                        <select name="afp_no_centralizada" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            @foreach (App\Models\Afp::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info" style="margin-top: 0px; padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                </div>

                                {{-- Opciones que se despliegan cuando se hace check en la opción "Centralizada" --}}
                                <div class="input-centralizada" style="display: none;">
                                    <div class="form-group">
                                        <select name="t_planilla" class="form-control select2">
                                            <option selected disabled>Tipo de planilla</option>
                                            @foreach (\App\Models\ProcedureType::where('deleted_at', NULL)->get() as $item)
                                            <option value="{{ $item->planilla_id }}">{{ $item->name }}</option>    
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" min="0" name="periodo" class="form-control" placeholder="Periodo" />
                                    </div>
                                    <div class="form-group">
                                        <select name="afp" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            @foreach (App\Models\Afp::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                </div>

                                {{-- Busqueda individual --}}
                                <div class="input-ci">
                                    <div class="form-group">
                                        <select name="people_id" class="form-control" id="select-people_id" ></select>
                                    </div>
                                    <div class="form-group">
                                        <select name="pagada" class="form-control select2" style="margin-bottom: 10px">
                                            <option value="">Todos</option>
                                            <option value="1">Pagos pendientes</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <label class="radio-inline"><input type="radio" name="type_system" value="0">Antiguo sistema</label>
                                    <label class="radio-inline"><input type="radio" name="type_system" value="1" checked>Nuevo sistema</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ((Auth::user()->role_id >= 2 && Auth::user()->role_id <= 5) || Auth::user()->role_id == 1)
            <div style="position: fixed; bottom: 40px; right: 20px; z-index: 10;padding: 10px 20px; background-color: white; box-shadow: 0px 0px 15px 10px white; border-radius: 5px">
                <div class="btn-group btn-group-lg">
                    <button type="button" class="btn btn-warning btn-lg btn-increment-ticket" ><h4>{{ setting('auxiliares.numero_ticket') == 0  ? 'Iniciar' : 'Siguiente' }} <span class="voyager-double-right"></span></h4></button>
                    <button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#reset-tickets-modal"><h4><span class="voyager-settings"></span></h4></button>
                </div>
            </div>
        @endif
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
    <form id="form-abrir-planilla" action="{{ route('planillas.pagos.details.open') }}" method="post">
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
    <form id="form-pagar" action="{{ route('planillas.pagos.details.payment') }}" method="post">
        @csrf
        <input type="hidden" name="cashier_id">
        <input type="hidden" name="id">
        <input type="hidden" name="paymentschedules_detail_id">
        <input type="hidden" name="name">
        <input type="hidden" name="amount">
        <div class="modal fade" tabindex="-1" id="pagar-modal" role="dialog">
            <div class="modal-dialog modal-success">
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
                                        <label class="checkbox-inline"><input type="checkbox" id="check-print" value="1">Imprimir recibo</label>
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
    <form id="form-pagar-aguinaldo" action="{{ route('planillas.pagos.details.payment') }}" method="post">
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

    {{-- Pagar Planilla Adicional modal --}}
    <form id="form-pagar-planillaadicional" action="{{ route('planillas.pagos.details.payment') }}" method="post">
        @csrf
        <input type="hidden" name="cashier_id">
        <input type="hidden" name="stipend_id">
        <input type="hidden" name="name">
        <input type="hidden" name="amount">
        <div class="modal modal-success fade" tabindex="-1" id="pagar-planilla-adicional-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-dollar"></i> Realizar pago de la Planilla Adicional</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Desea realizar el pago de aguinaldo del siguiente funcionario?</p> <br>
                        <table width="100%">
                            <tr>
                                <td style="width: 150px"><small>Item</small></td>
                                <td><h4 id="label-item-planilla"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Nombre Completo</small></td>
                                <td><h4 id="label-name-planilla"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Días Trabajados</small></td>
                                <td><h4 id="label-days-planilla"></h4></td>
                            </tr>
                            <tr>
                                <td><small>Sueldo promedio</small></td>
                                <td><h4 id="label-sueldo-planilla"></h4></td>
                            </tr>
                            <tr>
                                <td><b>Liquido Pagable</b></td>
                                <td><h1 class="text-right" id="label-amount-planilla"></h1></td>
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
                        <button type="button" class="btn btn-success btn-submit" onclick="sendForm('form-pagar-planillaadicional', 'Pago realizado exitosamente.')">Sí, pagar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="form-cerrar-planilla" action="{{ route('planillas.pagos.update.status') }}" method="post">
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

    <form id="form-update-planilla" action="{{ url('admin/plugins/cashiers/tickets/set') }}" method="post">
        @csrf
        <input type="hidden" name="reset" value="1">
        <div class="modal modal-warning fade" tabindex="-1" id="reset-tickets-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-refresh"></i> Actualizar tickets</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">N&deg; de tickets</label>
                            <input type="number" name="value" class="form-control">
                            <span>Si desea restablecer el número de ticket dejar vació.</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Sí, actualizar</button>
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
        const socket = io("{{ env('APP_URL_SOCKET') }}:{{ env('APP_PORT_SOCKET') }}");
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

            $('#select-people_id').select2({
                ajax: {
                    url: "{{ url('admin/planillas/pagos/people/search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            type: $('input[name="type_system"]:checked').val()
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
                minimumInputLength: 4,
                templateResult: formatResult,
                templateSelection: data => {
                    if(data.first_name){
                        return data.first_name+' '+data.last_name;
                    }
                }
            }).change(function(){
                let people_id = $('#select-people_id option:selected').val();
                console.log(people_id)
                if(people_id){
                    getData();
                }
            });

            $('input[name="type_system"]').click(function(){
                $('#select-people_id').val('').trigger('change');
            });
        });

        function getData(){
            $('#div-results').empty();
            $('#div-results').loading({message: 'Cargando...'});
            console.log($('#form-search').serialize())
            $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                $('#div-results').html(res);
            })
            .fail(function() {
                toastr.error('Ocurrió un error.!', 'Oops!');
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
                        window.open("{{ url('admin/planillas/pagos/print') }}/"+res.payment_id, "Recibo", `width=700, height=400`)
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

        function setValuePay(data, cashier, newSystem = false){
            let months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            let full_name = newSystem ? data.contract.person.first_name+' '+data.contract.person.last_name : data.Nombre_Empleado;
            let liquid_payable = newSystem ? parseFloat(data.liquid_payable).toFixed(2) : (data.Tplanilla == 3 ? data.Total_Ganado.toFixed(2) : data.Liquido_Pagable.toFixed(2));
            
            $('#label-item').html(`N&deg; ${data.item}`);
            $('#label-name').text(full_name);
            $('#label-afp').html((newSystem ? data.afp : data.Afp) == 1 ? '<label class="label label-danger">Futuro</label>' : '<label class="label label-primary">Previsión</label>');
            $('#label-salary').html(`${newSystem ? parseFloat(data.salary).toFixed(2) : data.Sueldo_Mensual.toFixed(2)} <small>Bs.</small>`);
            $('#label-month').text(months[parseInt(newSystem ? parseInt(data.paymentschedule.period.name.substr(5, 2)) : data.Mes)]);
            $('#label-days').html(`${newSystem ? data.worked_days : data.Dias_Trabajado} <small>Días</small>`);
            $('#label-amount').html(`${liquid_payable} <small>Bs.</small>`);
            $('#form-pagar input[name="id"]').val(newSystem ? '' : data.ID);
            $('#form-pagar input[name="paymentschedules_detail_id"]').val(newSystem ? data.id : '');
            $('#form-pagar input[name="name"]').val(full_name);
            $('#form-pagar input[name="amount"]').val(liquid_payable);
            $('#form-pagar input[name="cashier_id"]').val(cashier ? cashier.id : 0);
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

        function planillasetValuePayBonus(data, cashier){
            // alert(data.funcionario)
            $('#label-item-planilla').html(`N&deg; ${data.id}`);
            $('#label-name-planilla').text(data.funcionario);
            $('#label-days-planilla').html(`${data.dia} <small>Días</small>`);
            $('#label-sueldo-planilla').html(`${data.sueldo} <small>Bs.</small>`);
            $('#label-amount-planilla').html(`${data.liqpagable} <small>Bs.</small>`);
            $('#form-pagar-planillaadicional input[name="stipend_id"]').val(data.id);
            $('#form-pagar-planillaadicional input[name="name"]').val(data.funcionario);
            $('#form-pagar-planillaadicional input[name="amount"]').val(data.liqpagable);
            $('#form-pagar-planillaadicional input[name="cashier_id"]').val(cashier ? cashier.id : '');
        }

        function setValueOpen(id){
            $('#form-abrir-planilla input[name="id"]').val(id);
        }

        function setValueClose(id){
            $('#form-cerrar-planilla input[name="id"]').val(id);
        }

        function formatResult(data) {
            if (data.loading) {
                return 'Buscando...';
            }

            let image = "{{ asset('images/default.jpg') }}";
            if(data.image){
                image = "{{ asset('storage') }}/"+data.image.replace('.', '-cropped.');
            }
            var $container = $(
                
                `<div class="option-select2-custom">
                    <div style="display:flex; flex-direction: row">
                        <div>
                            <img src="${image}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px" />
                        </div>
                        <div>
                            <h5>
                                ${data.first_name} ${data.last_name} <br>
                                <p style="font-size: 13px; margin-top: 5px">
                                    ${data.ci} ${data.profession ? ' - '+data.profession : ''} <br>
                                </p>
                            </h5>
                        </div>
                    </div>
                    
                </div>`
            );

            return $container;
        }
    </script>
@stop
