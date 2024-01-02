@extends('errors.layout', ['classPage' => 'error-page', 'activePage' => '403', 'title' => __('Thesis Manager -  ADSM'), 'pageBackground' => asset("material").'/img/login.png'])

@section('content')
  <div class="container text-center">
    <div class="row">
      <div class="col-md-12">
        <h1 class="title">403</h1>
        <h2>{{ __('Forbidden :') }}(</h2>
        <h4>{{ __('Ooooups! Looks like you got lost.') }}</h4>
      </div>
    </div>
  </div>
@endsection
