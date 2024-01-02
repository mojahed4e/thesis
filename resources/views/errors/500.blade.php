@extends('errors.layout', ['classPage' => 'error-page', 'activePage' => '500', 'title' => __('Thesis Manager -  ADSM'), 'pageBackground' => asset("material").'/img/login.png'])

@section('content')
  <div class="container text-center">
    <div class="row">
      <div class="col-md-12">
        <h1 class="title">500</h1>
        <h2>{{ __('Server Error :') }}(</h2>
        <h4>{{ __('Ooooups! Looks like something went wrong') }}</h4>
      </div>
    </div>
  </div>
@endsection