@extends('layouts.master')

@section('meta')
    <title>{{ setting('admin.title') }} | Bienvenido</title>
    <meta property="og:url"           content="{{ url('') }}" />
    {{-- <meta property="og:type"          content="" /> --}}
    <meta property="og:title"         content="{{ setting('site.title') }}" />
    <meta property="og:description"   content="{{ setting('site.description') }}" />
    <meta property="og:image"         content="{{ asset('images/icon.png') }}" />
    <meta name="keywords" content="beni, mamore, pagos, gadbeni, gobernacion">
@endsection

@section('content')
  @if (setting('auxiliares.numero_ticket') != null)
      <div style="position: absolute; top: 100px; right: 20px; text-align: center; z-index: 1">
      <b style="color: white">Ticket N&deg;</b> <br>
      <div id="odometer" class="odometer">{{ setting('auxiliares.numero_ticket') }}</div>
      </div>
  @endif

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="clearfix">
      <div class="container" data-aos="fade-up">
      <div class="hero-img" data-aos="zoom-out" data-aos-delay="200">
          <img src="{{ asset('vendor/landingpage/img/hero-img.png') }}" alt="" class="img-fluid">
      </div>

      <form id="form-search" action="{{ route('home.search.payroll.ci') }}" method="post">
          <div class="hero-info" data-aos="zoom-in" data-aos-delay="100">
              <h2>SISTEMA DE GESTIÓN DE PAGOS</h2>
              <div class="input-group mb-3 mt-5 input-group-lg">
                  @csrf
                  <input type="search" name="search" class="form-control" placeholder="Número de CI" aria-label="" aria-describedby="basic-addon1" required>
                  <div class="input-group-prepend">
                      <button class="btn btn-secondary" type="submit" style="height: 50px"><i class="bi bi-search"></i></button>
                  </div>
              </div>
              <h6 class="text-white">Consulta si tu tramite de pago está habilitado </h6>
              {{-- <div>
                  <a href="#about" class="btn-get-started scrollto">Get Started</a>
                  <a href="#services" class="btn-services scrollto">Our Services</a>
              </div> --}}
          </div>
      </form>

      </div>
  </section><!-- End Hero Section -->

  <main id="main">

      <!-- ======= About Section ======= -->
      <section id="about">
      <div class="container" data-aos="fade-up">

          <header class="section-header">
          <h3>About Us</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </header>

          <div class="row about-container">

          <div class="col-lg-6 content order-lg-1 order-2">
              <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
              </p>

              <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="bi bi-card-checklist"></i></div>
              <h4 class="title"><a href="">Eiusmod Tempor</a></h4>
              <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>
              </div>

              <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
              <div class="icon"><i class="bi bi-brightness-high"></i></div>
              <h4 class="title"><a href="">Magni Dolores</a></h4>
              <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
              </div>

              <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
              <div class="icon"><i class="bi bi-calendar4-week"></i></div>
              <h4 class="title"><a href="">Dolor Sitema</a></h4>
              <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tarad limino ata</p>
              </div>

          </div>

          <div class="col-lg-6 background order-lg-2" data-aos="zoom-in">
              <img src="{{ asset('vendor/landingpage/img/about-img.svg') }}" class="img-fluid" alt="">
          </div>
          </div>

          <div class="row about-extra">
          <div class="col-lg-6" data-aos="fade-right">
              <img src="{{ asset('vendor/landingpage/img/about-extra-1.svg') }}" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-5 pt-lg-0" data-aos="fade-left">
              <h4>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h4>
              <p>
              Ipsum in aspernatur ut possimus sint. Quia omnis est occaecati possimus ea. Quas molestiae perspiciatis occaecati qui rerum. Deleniti quod porro sed quisquam saepe. Numquam mollitia recusandae non ad at et a.
              </p>
              <p>
              Ad vitae recusandae odit possimus. Quaerat cum ipsum corrupti. Odit qui asperiores ea corporis deserunt veritatis quidem expedita perferendis. Qui rerum eligendi ex doloribus quia sit. Porro rerum eum eum.
              </p>
          </div>
          </div>

          <div class="row about-extra">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left">
              <img src="{{ asset('vendor/landingpage/img/about-extra-2.svg') }}" class="img-fluid" alt="">
          </div>

          <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-right">
              <h4>Neque saepe temporibus repellat ea ipsum et. Id vel et quia tempora facere reprehenderit.</h4>
              <p>
              Delectus alias ut incidunt delectus nam placeat in consequatur. Sed cupiditate quia ea quis. Voluptas nemo qui aut distinctio. Cumque fugit earum est quam officiis numquam. Ducimus corporis autem at blanditiis beatae incidunt sunt.
              </p>
              <p>
              Voluptas saepe natus quidem blanditiis. Non sunt impedit voluptas mollitia beatae. Qui esse molestias. Laudantium libero nisi vitae debitis. Dolorem cupiditate est perferendis iusto.
              </p>
              <p>
              Eum quia in. Magni quas ipsum a. Quis ex voluptatem inventore sint quia modi. Numquam est aut fuga mollitia exercitationem nam accusantium provident quia.
              </p>
          </div>

          </div>

      </div>
      </section><!-- End About Section -->

      <!-- ======= Services Section ======= -->
      <section id="services" class="section-bg">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
            <h3>Services</h3>
            <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant vituperatoribus.</p>
            </header>

            <div class="row justify-content-center">

            <div class="col-md-6 col-lg-5" data-aos="zoom-in" data-aos-delay="100">
                <div class="box">
                <div class="icon"><i class="bi bi-briefcase" style="color: #ff689b;"></i></div>
                <h4 class="title"><a href="">Lorem Ipsum</a></h4>
                <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-5" data-aos="zoom-in" data-aos-delay="200">
                <div class="box">
                <div class="icon"><i class="bi bi-card-checklist" style="color: #e9bf06;"></i></div>
                <h4 class="title"><a href="">Dolor Sitema</a></h4>
                <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tarad limino ata</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-5" data-aos="zoom-in" data-aos-delay="100">
                <div class="box">
                <div class="icon"><i class="bi bi-bar-chart" style="color: #3fcdc7;"></i></div>
                <h4 class="title"><a href="">Sed ut perspiciatis</a></h4>
                <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-5" data-aos="zoom-in" data-aos-delay="200">
                <div class="box">
                <div class="icon"><i class="bi bi-binoculars" style="color:#41cf2e;"></i></div>
                <h4 class="title"><a href="">Magni Dolores</a></h4>
                <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-5" data-aos="zoom-in" data-aos-delay="100">
                <div class="box">
                <div class="icon"><i class="bi bi-brightness-high" style="color: #d6ff22;"></i></div>
                <h4 class="title"><a href="">Nemo Enim</a></h4>
                <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-5" data-aos="zoom-in" data-aos-delay="200">
                <div class="box">
                <div class="icon"><i class="bi bi-calendar4-week" style="color: #4680ff;"></i></div>
                <h4 class="title"><a href="">Eiusmod Tempor</a></h4>
                <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>
                </div>
            </div>

            </div>

        </div>
      </section>
      <!-- End Services Section -->

  </main><!-- End #main -->
@endsection