@extends('voyager::master')

@section('page_title', 'Viendo Ingresos')


    @section('page_header')
        <div class="container-fluid div-phone">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="page-title">
                        <i class="voyager-credit-cards"></i> Ingresos
                    </h1>
                    <a href="{{ route('outbox.create') }}" class="btn btn-success btn-add-new">
                        <i class="voyager-plus"></i> <span>Crear</span>
                    </a>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>
    @stop

    @section('content')
        <div class="page-content browse container-fluid">
            @include('voyager::alerts')
            <div class="row">
                <div class="col-md-12 div-phone">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-9" style="margin-bottom: 0px">
                                    <div class="dataTables_length" id="dataTable_length">
                                        <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> registros</label>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="margin-bottom: 0px">
                                    <input type="text" id="input-search" class="form-control" placeholder="Ingrese busqueda..."> <br>
                                </div>
                            </div>
                            <div class="row" id="div-results" style="min-height: 120px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('correspondencia.partials.modal-delete',['name'=>'Si anula esta entrada tambien anulara la derivacion si tuviese, desea continuar?'])
        
    @stop

    @section('css')
        <style>
            .select2-container {
                width: 100% !important;
            }
        </style>

        <style>
            .entrada:hover{
                cursor: pointer;
                opacity: .7;
            }
            .unread{
                background-color: rgba(135, 183, 148, 0.2) !important
            }



            /* ALL LOADERS */

            .loader{
                width: 100px;
                height: 100px;
                border-radius: 100%;
                position: relative;
                margin: 0 auto;
            }
            /* LOADER 3 */

            #loader-3:before, #loader-3:after{
                content: "";
                width: 20px;
                height: 20px;
                position: absolute;
                top: 0;
                left: calc(50% - 10px);
                background-color: #5eaf4a;
                animation: squaremove 1s ease-in-out infinite;
            }

            #loader-3:after{
                bottom: 0;
                animation-delay: 0.5s;
            }

            @keyframes squaremove{
                0%, 100%{
                    -webkit-transform: translate(0,0) rotate(0);
                    -ms-transform: translate(0,0) rotate(0);
                    -o-transform: translate(0,0) rotate(0);
                    transform: translate(0,0) rotate(0);
                }

                25%{
                    -webkit-transform: translate(40px,40px) rotate(45deg);
                    -ms-transform: translate(40px,40px) rotate(45deg);
                    -o-transform: translate(40px,40px) rotate(45deg);
                    transform: translate(40px,40px) rotate(45deg);
                }

                50%{
                    -webkit-transform: translate(0px,80px) rotate(0deg);
                    -ms-transform: translate(0px,80px) rotate(0deg);
                    -o-transform: translate(0px,80px) rotate(0deg);
                    transform: translate(0px,80px) rotate(0deg);
                }

                75%{
                    -webkit-transform: translate(-40px,40px) rotate(45deg);
                    -ms-transform: translate(-40px,40px) rotate(45deg);
                    -o-transform: translate(-40px,40px) rotate(45deg);
                    transform: translate(-40px,40px) rotate(45deg);
                }
            }
        </style>
    @stop

    @push('javascript')
        <script>
            var destinatario_id;
            var intern_externo = 1;
            var countPage = 10;
            $(document).ready(function() {
                list();
                $('#input-search').on('keyup', function(e){
                    if(e.keyCode == 13) {
                        list();
                    }
                });
                $('#select-paginate').change(function(){
                    countPage = $(this).val();
                    list();
                });
            });
            function list(page = 1){
                // $("#div-results").LoadingOverlay("show");
                var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
                $('#div-results').html(loader);

                let url = '{{ url("admin/outbox/ajax/list") }}';
                let search = $('#input-search').val() ? $('#input-search').val() : '';
                $.ajax({
                    url: `${url}?search=${search}&paginate=${countPage}&page=${page}`,
                    type: 'get',
                    success: function(response){
                        $('#div-results').html(response);
                        $("#div-results").LoadingOverlay("hide");
                    }
                });
            }
            // function derivacionItem(id,destinoid=0){
            //     $('#form-derivacion input[name="id"]').val(id);
            //     destinatario_id = destinoid;
            //     // alert(destinatario_id);
            // }
            function deleteItem(url){
                $('#delete_form').attr('action', url);
            }
            $(function() {
                let socket = io(IP_ADDRESS + ':' + SOCKET_PORT);
                @if (session('alert-type'))
                socket.emit('sendNotificationToServer', "{{ session('funcionario_id') }}");
                @endif
            });
        </script>
    @endpush
    