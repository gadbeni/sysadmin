<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        
        @yield('meta')

        <!-- Favicons -->
        <link href="{{ asset('images/icon.png') }}" rel="icon">
        <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{ asset('vendor/landingpage/vendor/aos/aos.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/landingpage/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/landingpage/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/landingpage/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/landingpage/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="{{ asset('vendor/landingpage/css/style.css') }}" rel="stylesheet">

        @yield('css')

        <!-- =======================================================
        * Template Name: NewBiz - v4.3.0
        * Template URL: https://bootstrapmade.com/newbiz-bootstrap-business-template/
        * Author: BootstrapMade.com
        * License: https://bootstrapmade.com/license/
        ======================================================== -->
    </head>

    <body>

        <!-- ======= Header ======= -->
        <header id="header" class="fixed-top d-flex align-items-center">
            <div class="container d-flex justify-content-between">
    
            <div class="logo">
                <!-- Uncomment below if you prefer to use an text logo -->
                <!-- <h1><a href="index.html">NewBiz</a></h1> -->
                <a href="{{ url('') }}">
                    <img src="{{ asset('images/icon-alt.png') }}" alt="GADBENI" class="img-fluid">
                </a>
            </div>
    
            <nav id="navbar" class="navbar">
                <ul>
                <li><a class="nav-link scrollto" href="{{ url('') }}#hero">Inicio</a></li>
                <li><a class="nav-link scrollto" href="{{ url('') }}#about">Acerca de</a></li>
                <li><a class="nav-link scrollto" href="{{ url('') }}#services">Servicios</a></li>
                <li><a class="nav-link scrollto" href="{{ url('admin') }}">Administración</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
    
            </div>
        </header><!-- #header -->

        @yield('content')

        <!-- ======= Footer ======= -->
        <footer id="footer">
            <div class="footer-top">
            <div class="container">
                <div class="row">

                <div class="col-lg-6 col-md-6 footer-info">
                    <a href="{{ url('') }}">
                        <img src="{{ asset('images/icon-alt.png') }}" alt="GADBENI" class="img-fluid" width="220px">
                    </a>
                    <p>Con la bendicion de Dios saldremos Adelante...!!!.</p>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Enlaces</h4>
                    <ul>
                    <li><a href="{{ url('') }}">Inicio</a></li>
                    <li><a href="https://siscor.beni.gob.bo/">SISCOR</a></li>
                    <li><a href="https://gaceta.beni.gob.bo/">Gaceta</a></li>
                    <li><a href="https://transparencia.beni.gob.bo/">Transparencia</a></li>
                    <li><a href="{{ url('policies') }}">Políticas de privacidad</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-contact">
                    <h4>Contáctanos</h4>
                    <p>
                        Plaza Principal acera sur <br>
                        Santísima Trinidad - Beni - Bolivia <br>
                        <strong>Telefono/Celular:</strong> +591 73961213<br>
                        <strong>Email:</strong> despacho@beni.gob.bo<br>
                    </p>

                    <div class="social-links">
                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                    </div>

                </div>

                {{-- <div class="col-lg-3 col-md-6 footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna veniam enim veniam illum dolore legam minim quorum culpa amet magna export quem marada parida nodela caramase seza.</p>
                    <form action="" method="post">
                        <input type="email" name="email"><input type="submit" value="Subscribe">
                    </form>
                </div> --}}

                </div>
            </div>
            </div>

            <div class="container">
            <div class="copyright">
                &copy; Copyright <strong>GADBENI</strong>. Todos los derechos reservados
            </div>
            <div class="credits">
                Diseñado por <a href="{{ url('') }}">Unidad de Sistemas</a>
            </div>
            </div>
        </footer><!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <!-- Vendor JS Files -->
        <script src="{{ asset('vendor/landingpage/vendor/aos/aos.js') }}"></script>
        <script src="{{ asset('vendor/landingpage/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/landingpage/vendor/glightbox/js/glightbox.min.js') }}"></script>
        <script src="{{ asset('vendor/landingpage/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
        <script src="{{ asset('vendor/landingpage/vendor/php-email-form/validate.js') }}"></script>
        <script src="{{ asset('vendor/landingpage/vendor/purecounter/purecounter.js') }}"></script>
        <script src="{{ asset('vendor/landingpage/vendor/swiper/swiper-bundle.min.js') }}"></script>

        <!-- Template Main JS File -->
        <script src="{{ asset('vendor/landingpage/js/main.js') }}"></script>

        <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>

        {{-- Loading --}}
        <link rel="stylesheet" href="{{ asset('vendor/loading/loading.css') }}">
        <script src="{{ asset('vendor/loading/loading.js') }}"></script>

        {{-- SweetAlert2 --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function(){
                $('#form-search').submit(function(e){
                    $('body').loading({message: 'Buscando...'});
                    e.preventDefault();
                    $.post($(this).attr('action'), $(this).serialize(), function(res){
                        $('body').loading('toggle');
                        if(res.error){
                            Swal.fire({
                                title: 'Error',
                                text: res.error,
                                icon: 'error',
                                confirmButtonText: 'Entendido'
                            });
                            return;
                        }

                        let months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        if(res.search.length > 0){
                        let data = '';
                        res.search.forEach(function(item){
                            data += `${months[parseInt(parseInt(item.paymentschedule.period.name.substr(4, 2)))]} y `;
                        });
                        Swal.fire({
                            title: `Habilitado para pago del mes de ${data.substr(0, data.length-3)}`,
                            text: 'Puedes pasar por caja a realizar el cobro',
                            icon: 'success',
                            confirmButtonText: 'Entendido'
                        });
                        }else{
                        Swal.fire({
                            title: 'Trámite de pago no encontrado',
                            text: 'La cedula de identidad ingresada no tiene trámites de pago en el sistema.',
                            icon: 'warning',
                            confirmButtonText: 'Entendido'
                        });
                        }
                    })
                    .catch(function(err){
                        $('body').loading('toggle');
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrio un error al realizar la busqueda',
                            icon: 'error',
                            confirmButtonText: 'Entendido'
                        });
                    });
                });
            });
        </script>

        {{-- Snowfall --}}
        @if (setting('plantillas.navidad'))
        <div id="flake">&#10052;</div>
        <link rel="stylesheet" href="{{ asset('css/snowfall.css') }}">
        <script src="{{ asset('js/snowfall.js') }}"></script>
        @endif

        <style>
            #odometer{
                background: white;
                border-radius: 7px;
                border: 3px solid #3f3f3f;
                font-size: 30px;
                padding: 0px 20px
            }
        </style>
        <!-- Odometr includes -->
        <link rel="stylesheet" href="{{ asset('vendor/odometer/odometer-theme-default.css') }}" />
        <script src="{{ asset('vendor/odometer/odometer.js') }}"></script>

        @yield('script')
    </body>
</html>