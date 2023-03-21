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
        #qr_code{
            display: none;
            position: fixed;
            top: 20px;
            right: 60px;
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

        #label-location{
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
            #logo, #qr_code{
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
                bottom: 5px;
                background: rgb(24,155,85);
                background: linear-gradient(90deg, rgba(24,155,85,1) 0%, rgba(3,180,85,1) 100%);
                height: 50px;
                text-align: center;
                color: white;
                padding-top: 10px
            }
            #qr_code{
                display: block;
            }
            #location-id{
                display: none;
            }
            #label-location{
                display: inline;
            }
            #options{
                display: none;
            }
        }
    </style>
</head>
<body>
    <img id="logo" src="{{ asset('images/icon.png') }}" />
    @yield('qr_code')
    <div id="watermark">
        <img src="{{ asset('images/icon.png') }}" /> 
    </div>
    @if (setting('auxiliares.edit-docs'))
    <div id="options" style="position: fixed; bottom: 10px; right: 20px">
        <button type="button" onclick="javascript:document.designMode = 'on'; document.getElementById('options').style.display = 'none'">Editar</button>
    </div>
    @endif

    @yield('content')

    <div id="footer">
        Plaza Principal Mcal. José Ballivián acera sur <br> www.beni.gob.bo
    </div>

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
            $('#location-id').change(function () {
                $('#label-location').html($(this).val());
            });
        });
    </script>
    @yield('script')
</body>
</html>