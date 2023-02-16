@extends('layouts.master')

@section('meta')
  <title>{{ setting('admin.title') }} | Registro</title>
  <meta property="og:url"           content="{{ url('') }}" />
  {{-- <meta property="og:type"          content="" /> --}}
  <meta property="og:title"         content="{{ setting('site.title') }}" />
  <meta property="og:description"   content="{{ setting('site.description') }}" />
  <meta property="og:image"         content="{{ asset('images/icon.png') }}" />
  <meta name="keywords" content="beni, mamore, pagos, gadbeni, gobernacion">
@endsection

@section('content')
  <section id="" style="margin-top: 100px; margin-bottom: 100px">
    <div class="container" data-aos="fade-up">
        <header class="section-header">
          <h3>Censate por tu Beni</h3>
          <p>Registrate para ayudar a tu departamento y así poder tomar decisiones en favor de este hermoso departamento.</p>
        </header>
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-5" data-aos="zoom-in" data-aos-delay="100">
            <div class="form">
              <form id="form-register" action="{{ route('home.register.person.store') }}" method="post" role="form">
                @csrf
                <input type="hidden" name="location" >
                <div class="form-group col-lg-12">
                  <label for="full_name">Nombre completo</label>
                  <input type="text" name="full_name" class="form-control" placeholder="Nombre completo" required>
                </div>
                <div class="row">
                  <div class="form-group col-lg-6 mt-3">
                    <label for="birthday">Fecha de nacimiento</label>
                    <input type="date" class="form-control" name="birthday" required>
                  </div>
                  <div class="form-group col-lg-6 mt-3">
                    <label for="gender">Género</label>
                    <select name="gender" class="form-control" required>
                      <option value="">--Género--</option>
                      <option value="femenino">Femenino</option>
                      <option value="masculino">Masculino</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-6 mt-3">
                    <label for="ci_nit">CI</label>
                    <input type="text" class="form-control" name="ci_nit" placeholder="Cédula de Identidad" required>
                  </div>
                  <div class="form-group col-lg-6 mt-3">
                    <label for="phone">N&deg; de celular</label>
                    <input type="phone" class="form-control" name="phone" placeholder="N&deg; de celular" required>
                  </div>
                </div>
                <div class="row">
                  @php
                    $provinces = App\Models\City::where('states_id', 1)->where('province', '<>', '')->groupBy('province')->get();
                    $cont = 0;
                    foreach ($provinces as $item) {
                      $cities = App\Models\City::where('province', $item->province)->get();
                      $provinces[$cont]->cities = $cities;
                      $cont++;
                    }
                  @endphp
                  <div class="form-group col-lg-6 mt-3">
                    <label for="province">Provincia</label>
                    <select name="province" id="select-province" class="form-control" required>
                      <option value="">--Seleccione la provincia--</option>
                      @foreach ($provinces as $item)
                      <option value="{{ $item->province }}" data-item='@json($item)'>{{ Str::upper($item->province) }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-lg-6 mt-3">
                    <label for="city_id">Ciudad de residencia</label>
                    <select name="city_id" id="select-city_id" class="form-control" required>
                      <option value="">--Seleccione la ciudad--</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-lg-12 mt-3">
                  <label for="address">Dirección</label>
                  <textarea class="form-control" name="address" rows="3"></textarea>
                </div>
                <div class="form-group col-lg-12 mt-3">
                  <label for="family">Cuántos hijos tiene</label>
                  <input type="number" name="family" class="form-control">
                </div>
                <div class="form-group col-lg-12 mt-3">
                  <label for="job">Ocupación</label>
                  <input type="text" class="form-control" name="job" placeholder="Ocupación" required>
                </div>
                <div class="form-group mt-5 text-right">
                  <button type="reset" class="btn btn-dark">Cancelar</button>
                  <button type="submit" class="btn btn-success" id="btn-submit">Registrarse</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
  </section>
@endsection

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('script')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      geoFindMe();
      $('#select-province').select2();
      $('#select-city_id').select2();

      $('#form-register').submit(function(e){
        $('#btn-submit').text('Registrando...');
        $('#btn-submit').attr('disabled', 'disabled');
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function(res){
          if(res.success){
            $('#form-register').trigger('reset');
            Swal.fire({
              title: `Bien hecho!`,
              text: res.message,
              icon: 'success',
              confirmButtonText: 'Entendido'
            });
          }else{
            Swal.fire({
              title: 'Error',
              text: res.message,
              icon: 'error',
              confirmButtonText: 'Entendido'
            });
          }
          $('#btn-submit').text('Registrar');
          $('#btn-submit').removeAttr('disabled');
        });
      });

      $('#select-province').change(function(){
        let item = $('#select-province option:selected').data('item');
        if(item){
          $('#select-city_id').html('<option value="">--Seleccione la ciudad--</option>');
          item.cities.forEach(city => {
            $('#select-city_id').append(`<option value="${city.id}">${city.name}</option>`);
          });
        }
      });
    });

    function geoFindMe() {
      function success(position) {
        const latitude  = position.coords.latitude;
        const longitude = position.coords.longitude;
        $('#form-register input[name="location"]').val(`${latitude},${longitude}`);
      }

      function error() {
        status.textContent = 'Unable to retrieve your location';
      }

      if (!navigator.geolocation) {
        console.log('Geolocation is not supported by your browser');
      } else {
        navigator.geolocation.getCurrentPosition(success, error);
      }
    }
  </script>
@endsection