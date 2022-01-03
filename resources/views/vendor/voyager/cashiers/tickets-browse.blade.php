@if(auth()->user()->hasPermission('browse_pluginscashierstickets'))
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tickets - GADBENI</title>

    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif

    <link href="https://fonts.googleapis.com/css?family=Noto+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
            background: url("{{ url('storage/'.setting('auxiliares.fondo_tickets')) }}") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            overflow-y:hidden
            /* margin: 0;
            padding: 0; */
        }
        .title{
            font-size: 50px;
            color: white;
            margin-top: 20px
        }
        .dark-mask{
            position: absolute;
            top:0px;
            bottom: 0px;
            left:0px;
            background-color:rgba(0, 0, 0, 0.5);
            width: 100%;
            height: 100 hv;
            z-index: 1;
        }
        .footer{
            position:fixed;
            bottom:0px;
            left:0px;
            width: 100%;
            background-color:rgba(0, 0, 0, 0.7);
            z-index: 10000;
        }
        .footer-content{
            margin: 10px 20px;
            color: white;
            font-size: 20px;
        }
        iframe{
            background-color: white
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        @if (!setting('auxiliares.numero_ticket'))
            <div class="multimedia">
                <video width="100%">
                    <source src="{{ asset('videos/test.mp4') }}" type="video/mp4">
                </video>
            </div>
        @endif
        <div class="row">
            <div class="dark-mask"></div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12" style="z-index: 2">
                        <img src="{{ asset('images/icon.png') }}" alt="GADBENI" class="img-fluid" width="150px">
                    </div>
                </div>
                <div class="row" id="data-posts">
                    <div class="col-md-12 mt-5">
                        <div class="wrapper">
                            <div class="slider" id="slider">
                              <ul class="slides">
                                <li class="slide" id="slide1">
                                  <a href="#">
                                    <img src="{{ asset('images/slider/1.jpeg') }}" alt="photo 1">
                                  </a>
                                </li>
                                <li class="slide" id="slide2">
                                  <a href="#">
                                    <img src="{{ asset('images/slider/2.jpeg') }}" alt="photo 2">
                                  </a>
                                </li>
                              </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8" style="z-index: 2">
                <div class="row" id="data" style="margin-top:20px;overflow-y:hidden"></div>
            </div>
        </div>
        <div class="footer">
            @if (setting('auxiliares.marquesina'))
            <marquee><h1 class="text-white" style="padding-top: 10px">{{ setting('auxiliares.marquesina') }}</h1></marquee>
            @endif
            <div class="footer-content">
                Desarrollado por <span style="color: {{ env('APP_COLOR') }}">Unidad de Desarrollo de Sistemas</span>
            </div>
        </div>

        <div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="activateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activateModalLabel">Activar voz?</h5>
                    </div>
                    <div class="modal-body">
                        <p>Da click en cualquier parte de la pantalla para activar voz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <style>
        .card{
            background-color:rgba(0, 0, 0, 0.7);
            color:white;
            border: 10px solid rgba(0, 0, 0, 0.7);
        }
        .ticket-active{
            animation: colorchange 3s infinite; /* animation-name followed by duration in seconds*/
             /* you could also use milliseconds (ms) or something like 2.5s */
            -webkit-animation: colorchange 3s infinite; /* Chrome and Safari */
        }
        .multimedia{
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100vh;
            z-index: 10000;
            background-color: black
        }


        @keyframes colorchange
        {
            0%  {border: 10px solid rgba(0, 0, 0, 0.7);}
            20%   {border: 10px solid {{ env('APP_COLOR') }};}
            80%  {border: 10px solid rgba(0, 0, 0, 0.7);}
        }
        @-webkit-keyframes colorchange /* Safari and Chrome - necessary duplicate */
        {
            0%  {border: 10px solid rgba(0, 0, 0, 0.7);}
            25%   {border: 10px solid {{ env('APP_COLOR') }};}
            75%  {border: 10px solid rgba(0, 0, 0, 0.7);}
        }


        /* Slider */
        @keyframes slide {
            0% { transform: translateX(0); }
            50% { transform: translateX(0); }

            51% { transform: translateX(-100%); }
            100% { transform: translateX(-100%); }

            /* 35% { transform: translateX(-200%); }
            50% { transform: translateX(-200%); }

            55% { transform: translateX(-300%); }
            70% { transform: translateX(-300%); }

            75% { transform: translateX(-400%); }
            90% { transform: translateX(-400%); }

            95% { transform: translateX(-500%); }
            100% { transform: translateX(-500%); } */
        }

        .wrapper {
            width: 100%;
            position: relative;
            z-index: 10;
        }

        .slider {
            width: 100%;
            position: relative;
        }

        .slides {
            width: 100%;
            position: relative;
            display: flex;
            overflow: hidden;
            padding: 0px
        }

        .slide {
            width: 100%;
            flex-shrink: 0;
            animation-name: slide;
            animation-duration: 60s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
            list-style: none
        }

        .slides:hover .slide {
            animation-play-state: paused;
        }

        .slide img {
            width: 100%;
            vertical-align: top;
        }

        .slide a {
            width: 100%;
            display: inline-block;
            position: relative;
        }

        .slide:target {
            animation-name: none;
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 50;
        }

        .slider-controler {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            text-align: center;
            /* padding: 5px; */
            background-color: rgba(0,0,0,0.5);
            z-index: 100;
        }

        .slider-controler li {
            margin: 0 0.5rem;
            display: inline-block;
            vertical-align: top;
        }

        .slider-controler a {
            display: inline-block;
            vertical-align: top;
            text-decoration: none;
            color: white;
            font-size: 1.5rem;
        }
    </style>

    <script>
        var ventana_alto = $(window).height();
        const array_video = @json($videos);

        $(document).ready(function(){
            $('video').click(function(){
                $('video').trigger('play');
                $('#activateModal').modal('hide');
            });
            $('#data').css('height', ventana_alto-30);
            array_tickest();
            $('#activateModal').modal('show');

            $("video").bind("ended", function(){
                if(array_video.length > 0){
                    $('video').attr('src', "{{ asset('storage') }}/"+array_video[Math.floor(Math.random() * array_video.length)]);
                    $('video').trigger('play');
                }
            });
        });

        function array_tickest(){
            $.get("{{ url('admin/plugins/cashiers/tickets/get') }}", function(res){
                $('#data').empty();
                for (let index = res.ticket - 2 > 0 ? res.ticket - 2 : 1; index < parseInt(res.ticket)+10; index++) {
                    render(index, parseInt(res.ticket));
                }
            });
        }

        function render(number, active = 1){
            $('#data').append(`
                <div class="col-md-6">
                    <div class="card mb-3 ${number <= active ? 'ticket-active' : ''}" id="card-${number}">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="text-center" style="font-size:80px">T-${number}</h1>
                            </div>
                        </div>
                    </div>
                </div>`);
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.0/socket.io.js" integrity="sha512-nYuHvSAhY5lFZ4ixSViOwsEKFvlxHMU2NHts1ILuJgOS6ptUmAGt/0i5czIgMOahKZ6JN84YFDA+mCdky7dD8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const socket = io("{{ env('APP_URL') }}"+":3001");
        socket.on('get new ticket', data => {
            $('.multimedia').remove()
            array_tickest();
            let textoAEscuchar = 'Ticket número '+data.ticket;
            let mensaje = new SpeechSynthesisUtterance();
            // mensaje.voice = vocesDisponibles[$voces.value];
            mensaje.volume = 1;
            mensaje.rate = 0.7;
            mensaje.text = textoAEscuchar;
            mensaje.pitch = 1;
            speechSynthesis.speak(mensaje);
            setTimeout(() => {
                speechSynthesis.speak(mensaje);
            }, 4000);
        });
    </script>

    {{-- Snowfall --}}
    @if (setting('plantillas.navidad'))
    <div id="flake" style="z-index: 100">&#10052;</div>
    <link rel="stylesheet" href="{{ asset('css/snowfall.css') }}">
    <script src="{{ asset('js/snowfall.js') }}"></script>
    @endif
</body>
</html>

@else
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Error</title>
    </head>
    <body>
        <h1>Acceso denegado!</h1>
    </body>
    </html>
@endif