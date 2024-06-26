@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'classPage' => 'error-page', 'activePage' => 'error', 'title' => __('Thesis Manager - ADSM'), 'pageBackground' => asset("material").'/img/login.png'])

@section('content')
  <div class="container text-center">
    <div class="row">
      <div class="col-md-12">
        <h1 class="title">404</h1>
        <h2>{{ __('Page not found :') }}(</h2>
        <h4>{{ __('Ooooups! Looks like you got lost.') }}</h4>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      md.checkFullPageBackgroundImage();
    });
  </script>
@endpush
