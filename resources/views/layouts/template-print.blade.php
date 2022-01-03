<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page_title') | {{ env('APP_NAME', 'SYSADMIN') }}</title>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
    <style>
        body{
            margin: 50px auto;
            font-family: Arial, sans-serif;
            max-width: 740px;
        }
        #logo{
            position: fixed;
            top: 20px;
            margin-left: 20px;
            text-align: center;
            width: 90px;
        }
        #watermark {
            position: fixed;
            top: 350px;
            opacity: 0.1;
            z-index:  -1;
        }
        #watermark img{
            position: relative;
            width: 300px;
            left: 205px;
        }

        #footer {
            display: none;
        }

        @page {
            size: letter;
            margin: 10mm 0mm 0mm 0mm;
        }

        @media print {
            body{
                margin: 20px auto;
            }
            #logo{
                top: 0px;
            }
            #watermark {
                top: 280px;
            }
            #footer {
                display: block;
                position: fixed;
                left: 0px;
                right: 0px;
                bottom: 3px;
                background: rgb(24,155,85);
                background: linear-gradient(90deg, rgba(24,155,85,1) 0%, rgba(3,180,85,1) 100%);
                height: 50px;
                text-align: center;
                color: white;
                padding-top: 15px
            }
        }
    </style>
</head>
<body>
    <img id="logo" src="{{ asset('images/icon.png') }}" />
    <div id="watermark">
        <img src="{{ asset('images/icon.png') }}" /> 
    </div>

    @yield('content')

    <div id="footer">
        Plaza Principal Mcal. José Ballivián acera sur <br> www.beni.gob.bo
    </div>

    @yield('css')
    @yield('script')
</body>
</html>