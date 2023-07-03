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
                margin: 0px auto;
                font-family: Arial, sans-serif;
            }
            .container {
                display: flex;
                justify-content: center;
                width: 100%;
                background: rgb(115,117,117);
                background: linear-gradient(90deg, rgba(115,117,117,1) 0%, rgba(173,173,173,1) 50%, rgba(115,117,117,1) 100%);
            }
            .sheet {
                max-width: 780px;
                background-color: white
            }
            .content {
                text-align: justify;
                padding: 0px 34px;
                font-size: 13px;
            }
            #logo{
                margin: 0px;
                width: 90px;
            }
            .page-head {
                text-align: center;
                margin-bottom: 30px
            }
            .page-head h3 {
                margin-top: 0px !important
            }
            #watermark {
                position: fixed;
                top: 350px;
                opacity: 0.1;
                z-index:  100;
            }
            #watermark img{
                position: relative;
                width: 300px;
                left: 205px;
            }

            .btn {
                padding: 8px 15px
            }
            .text-center{
                text-align: center;
            }
            ol p{
                margin: 10px
            }
            .table-signature {
                width: 100%;
                text-align: center;
                margin-top: 80px;
                margin-bottom: 50px;
            }

            @page {
                size: legal;
                margin: 10mm 10mm 40mm 10mm;
            }
        </style>

        @if ($contract->files->count() == 0)
            <style>
                .sheet {
                    padding: 30px;
                }
                @media print {
                    body{
                        margin: 0px auto;
                    }
                    .options {
                        display: none
                    }
                    .sheet {
                        padding: 0px;
                        max-width: 100%;
                        background-color: white
                    }
                    .container {
                        background-color: transparent
                    }
                    .table-signature {
                        margin-bottom: 0px;
                    }
                }
            </style>
        @endif
    </head>
    <body>
        <div class="container">
            <div class="sheet">
                <table width="100%">
                    <tr>
                        <td><img id="logo" src="{{ asset('images/icon.png') }}" /></td>
                        <td style="text-align: right">@yield('qr_code')</td>
                    </tr>
                </table>
                <div id="watermark">
                    <img src="{{ asset('images/icon.png') }}" /> 
                </div>
                @if (setting('auxiliares.edit-docs') && $contract->files->count() == 0)
                <div class="options" style="position: fixed; bottom: 10px; right: 20px">
                    <button type="button" class="btn btn-edit">Editar</button>
                    <button type="button" class="btn btn-print" onclick="window.print()">Imprimir</button>
                    <button type="button" class="btn btn-save" style="display: none">Guardar</button>
                </div>
                @endif
        
                @yield('content')
            </div>
        </div>

        <form id="form-submit" action="{{ route('contracts.file.store', $contract->id) }}" style="display: none" method="post">
            @csrf
            <input type="text" name="name" value="{{ $document }}">
            <textarea name="text" id="" cols="30" rows="10"></textarea>
        </form>

        @yield('css')

        <script>
            window.onafterprint = function(event) {
                console.log('before print');
            };
        </script>

        <script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
        <script>
            $(document).ready(function () {
                @isset($contract)
                    @if ($contract->files->count() > 0)
                    $('.options').css('display', 'none');
                    @endif
                @endisset
                
                $('#location-id').change(function () {
                    $('#label-location').html($(this).val());
                });

                $('.btn-edit').click(function(){
                    document.designMode = 'on';
                    $(this).css('display', 'none');
                    $('.btn-print').css('display', 'none');
                    $('.btn-save').css('display', 'block');
                });
                $('.btn-save').click(function(){
                    document.designMode = 'off';
                    $(this).css('display', 'none');
                    $('textarea[name="text"]').text($('.content').html());
                    $('#form-submit').trigger('submit');
                });
            });
        </script>
        @yield('script')
    </body>
</html>