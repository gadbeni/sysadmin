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
            background-color:rgba(0, 0, 0, 0.8);
            width: 100%;
            height: 100 hv;
            z-index: 1;
        }
        .footer{
            position:fixed;
            bottom:0px;
            left:0px;
            width: 100%;
            background-color:rgba(0, 0, 0, 0.8);
            z-index: 100;
        }
        .footer-content{
            margin: 10px 20px;
            color: white
        }
        iframe{
            background-color: white
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="dark-mask"></div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12" style="z-index: 2">
                        <img src="{{ asset('images/icon-alt.png') }}" alt="GADBENI" class="img-fluid" width="250px">
                    </div>
                </div>
                <div class="row" id="data-posts">
                    <div class="col-md-12 text-center" style="z-index: 1">
                        <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fgobernacionbeni2021%2Fposts%2F238809501721979&show_text=true&width=500" width="500" height="793" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-8" style="z-index: 2">
                <div class="row" id="data" style="margin-top:20px;overflow-y:hidden"></div>
            </div>
        </div>
        <div class="footer">
            <div class="footer-content">
                Desarrollado por <span style="color: {{ env('APP_COLOR') }}">Unidad de Desarrollo de Sistemas</span>
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
    </style>

    <script>
        var ventana_alto = $(window).height();

        $(document).ready(function(){
            $('#data').css('height', ventana_alto-30);
            array_tickest();
            setInterval(() => {
                array_tickest();
            }, 10000);
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