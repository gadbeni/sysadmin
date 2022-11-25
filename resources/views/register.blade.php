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
  <div class="container">
    <div class="col-md-12 mb-5" style="margin-top: 120px; margin-bottom: 120px">
    </div>
  </div>
@endsection