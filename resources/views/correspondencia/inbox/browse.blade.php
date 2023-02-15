@extends('voyager::master')

@section('page_title', 'Bandeja de Entradas')

{{-- @if(auth()->user()->hasPermission('browse_bandeja')) --}}

    @section('content')
        <div id="page-content" class="page-content browse container-fluid">
            @include('voyager::alerts')
            <div class="row">
                <div class="col-md-12 div-phone">
                    <div class="panel panel-bordered">
                        <div class="panel-body">

                            <div class="alert alert-info" style="padding: 5px 10px; display: none" role="alert" id="alert-new">
                                <div class="row">
                                    <div class="col-md-8" style="margin: 0px">
                                    <p style="margin-top: 10px"><b>Atención!</b> Tienes una nueva derivación, refresca la página para actualizar la lista.</p></div>
                                    <div class="col-md-4 text-right" style="margin: 0px"><button class="btn btn-default" onclick="location.reload()">Refrescar <i class="voyager-refresh"></i></button></div>
                                </div>
                            </div>

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
                                <div class="col-md-12 text-right">
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="pendientes" checked>Pendientes</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="urgentes">Urgentes</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="archivados">Archivados</label>
                                </div>
                            </div>
                            <div class="row" id="div-results" style="min-height: 120px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('css')
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
    @endsection

    @section('javascript')
        <script>
            var countPage = 10;
            $(document).ready(function(){
                
                list();
                $('.radio-type').click(function(){
                    // alert(1)
                    list();
                });
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
                
                let type = $(".radio-type:checked").val();
                let url = '{{ url("admin/inbox/list/".$funcionario_id) }}';
                let search = $('#input-search').val() ? $('#input-search').val() : '';
                $.ajax({
                    url: `${url}/${type}?search=${search}&paginate=${countPage}&page=${page}`,
                    type: 'get',
                    success: function(response){
                        $('#div-results').html(response);
                        $("#div-results").LoadingOverlay("hide");
                    }
                });
            }

            function read(id){
                window.location = "{{ url('admin/inbox') }}/"+id;
            }
            
        </script>
    @stop
    
{{-- @else
    @section('content')
        @include('errors.403')
    @stop
@endif --}}