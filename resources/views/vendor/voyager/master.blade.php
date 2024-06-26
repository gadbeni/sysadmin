<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="assets-path" content="{{ route('voyager.voyager_assets') }}"/>

    @yield('meta')

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif



    <!-- App CSS -->
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">

    @yield('css')
    @if(__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif

    <!-- Few Dynamic Styles -->
    <style type="text/css">
        .voyager .side-menu .navbar-header {
            background:{{ config('voyager.primary_color','#22A7F0') }};
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary{
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus{
            background:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .voyager .breadcrumb a{
            color:{{ config('voyager.primary_color','#22A7F0') }};
        }
    </style>

    @if(!empty(config('voyager.additional_css')))<!-- Additional CSS -->
        @foreach(config('voyager.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
    @endif

    @yield('head')
</head>

<body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">

    <div id="voyager-loader">
        <?php $admin_loader_img = Voyager::setting('admin.loader', ''); ?>
        @if($admin_loader_img == '')
            <img src="{{ asset('images/loading.png') }}" alt="Voyager Loader">
        @else
            <img src="{{ Voyager::image($admin_loader_img) }}" alt="Voyager Loader">
        @endif
    </div>

    <?php
    if (\Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'http://') || \Illuminate\Support\Str::startsWith(Auth::user()->avatar, 'https://')) {
        $user_avatar = Auth::user()->avatar;
    } else {
        $user_avatar = Voyager::image(Auth::user()->avatar);
    }
    ?>

    <div class="app-container">
        <div class="fadetoblack visible-xs"></div>
        <div class="row content-container">
            @include('voyager::dashboard.navbar')
            @include('voyager::dashboard.sidebar')
            <script>
                (function(){
                        var appContainer = document.querySelector('.app-container'),
                            sidebar = appContainer.querySelector('.side-menu'),
                            navbar = appContainer.querySelector('nav.navbar.navbar-top'),
                            loader = document.getElementById('voyager-loader'),
                            hamburgerMenu = document.querySelector('.hamburger'),
                            sidebarTransition = sidebar.style.transition,
                            navbarTransition = navbar.style.transition,
                            containerTransition = appContainer.style.transition;

                        sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition =
                        appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition =
                        navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = 'none';

                        if (window.innerWidth > 768 && window.localStorage && window.localStorage['voyager.stickySidebar'] == 'true') {
                            appContainer.className += ' expanded no-animation';
                            loader.style.left = (sidebar.clientWidth/2)+'px';
                            hamburgerMenu.className += ' is-active no-animation';
                        }

                    navbar.style.WebkitTransition = navbar.style.MozTransition = navbar.style.transition = navbarTransition;
                    sidebar.style.WebkitTransition = sidebar.style.MozTransition = sidebar.style.transition = sidebarTransition;
                    appContainer.style.WebkitTransition = appContainer.style.MozTransition = appContainer.style.transition = containerTransition;
                })();
            </script>
            <!-- Main Content -->
            <div class="container-fluid">
                <div class="side-body padding-top">
                    @yield('page_header')
                    <div id="voyager-notifications"></div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @include('voyager::partials.app-footer')

    {{-- Sugerencias button --}}
    <div style="position: fixed; bottom: 10px; right: 10px; width: 60px; height: 60px; border-radius: 30px; background-color: {{ env('APP_COLOR', '#ccc') }}; text-align: center; z-index: 1">
        <button data-toggle="modal" data-target="#suggestion-modal" class="btn btn-link" style="margin-top: 10px" title="Hacer sugerencia"><i class="fa fa-commenting fa-2x"></i></button>
    </div>

    {{-- Sugerencias modal --}}
    <form action="{{ route('send.suggestion') }}" id="form-suggestion" class="form-submit" method="POST">
        @csrf
        <div class="modal fade" tabindex="-1" id="suggestion-modal" role="dialog">
            <div class="modal-dialog modal-success modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-paper-plane"></i> Enviar sugerencias</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="details" class="richTextBox" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-paper-plane"></i> Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript Libs -->


    <script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>

    <script>
        $(document).ready(function(){
            $.extend({selector: '.richTextBox'}, {})
            tinymce.init(window.voyagerTinyMCE.getConfig({selector: '.richTextBox'}));

            $('#form-suggestion').submit(function(e){
                $('#form-suggestion .btn-submit').attr('disabled', 'disabled');
                $('#form-suggestion .btn-submit').html('<i class="fa fa-paper-plane"></i> Enviando');
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.success){
                        toastr.success('Sugerencia enviada, gracias', 'Bien hecho');
                    }else{
                        toastr.error('Ocurrió un error', 'Error');
                    }

                    $('#suggestion-modal').modal('hide');
                    $('.btn-submit').removeAttr('disabled');
                    $('.btn-submit').html('<i class="fa fa-paper-plane"></i> Enviar');
                });
            });
        });

        @if(Session::has('alerts'))
            let alerts = {!! json_encode(Session::get('alerts')) !!};
            helpers.displayAlerts(alerts, toastr);
        @endif

        @if(Session::has('message'))

        // TODO: change Controllers to use AlertsMessages trait... then remove this
        var alertType = {!! json_encode(Session::get('alert-type', 'info')) !!};
        var alertMessage = {!! json_encode(Session::get('message')) !!};
        var alerter = toastr[alertType];

        if (alerter) {
            alerter(alertMessage);
        } else {
            toastr.error("toastr alert-type " + alertType + " is unknown");
        }
        @endif
    </script>

    <script src="{{ asset('vendor/momentjs/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/momentjs/moment-with-locales.min.js') }}"></script>

    @include('voyager::media.manager')
    @yield('javascript')
    @stack('javascript')
    @if(!empty(config('voyager.additional_js')))<!-- Additional Javascript -->
        @foreach(config('voyager.additional_js') as $js)<script type="text/javascript" src="{{ asset($js) }}"></script>@endforeach
    @endif


    <script>
        moment.locale('es');
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
            $('.form-submit').submit(function(){
                $('.form-submit .btn-submit').attr('disabled', 'disabled');
            });
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });

        function delay(fn, ms) {
            let timer = 0
            return function(...args) {
                clearTimeout(timer)
                timer = setTimeout(fn.bind(this, ...args), ms || 0)
            }
        }
    </script>

</body>
{{-- Snowfall --}}
@if (setting('plantillas.navidad'))
    <div id="flake">&#10052;</div>
    <link rel="stylesheet" href="{{ asset('css/snowfall.css') }}">
    <script src="{{ asset('js/snowfall.js') }}"></script>
@endif
</html>
