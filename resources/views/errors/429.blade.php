@extends('errors.layout', ['classPage' => 'error-page', 'activePage' => '429', 'title' => __('Thesis Manager -  ADSM'), 'pageBackground' => asset("material").'/img/login.png'])

@section('content')
  <div class="container text-center">
    <div class="row">
      <div class="col-md-12">
        <h1 class="title">429</h1>
        <h2>{{ __('Too Many Requests :') }}(</h2>
        <h4>{{ __('Ooooups! Looks like your did too many requests') }}</h4>
      </div>
    </div>
  </div>
@endsection

