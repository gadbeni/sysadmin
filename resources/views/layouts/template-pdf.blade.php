<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page_title') | {{ env('APP_NAME', 'MAMORE') }}</title>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
    <style>
        body{
            margin: 30px auto;
            font-family: Arial, sans-serif;
        }
        #logo{
            position: absolute;
            top: 0px;
            margin-left: 20px;
            text-align: center;
            width: 80px;
        }
        #watermark {
            position: fixed;
            top: 400px;
            opacity: 0.1;
            z-index:  -1;
            width: 100%;
            text-align: center
        }
        #watermark img{
            position: relative;
            width: 300px;
            /* left: 205px; */
        }
    </style>
</head>
<body>
    <img id="logo" src="{{ asset('images/icon.png') }}" />
    <div id="watermark">
        <img src="{{ asset('images/icon.png') }}" /> 
    </div>

    @yield('content')

    @yield('css')

    <script>
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>

    <script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function () {

        });
    </script>
    @yield('script')
</body>
</html>