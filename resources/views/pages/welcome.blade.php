@extends('layouts.app', [
  'class' => 'off-canvas-sidebar',
  'classPage' => 'login-page',
  'activePage' => '',
  'title' => __('Thesis Manager - ADSM'),
  'pageBackground' => asset("material").'/img/login.png'
])

@section('content')
   <div class="container-fluid pad_0">
     <div>
        <img src="{{ asset("material") }}/img/welcome_logo.png" class="set pb-5">
		<div class="ms_ba" >
			<span>{{ __('MASTER OF SCIENCE IN BUSINESS ANALYTICS') }}</span>
		</div>
		<div class="pro_m" class="Welcome_sml">
			<span>{{ __('Welcome to Thesis') }} <br> {{__('Projects Manager') }}</span>
		</div>
		<a href="{{ route('login') }}" class="button bt_look mt-5">{{ __('Get Started') }}<i class="pl-3 fas fa-arrow-right"></i></a>
        <p class="welcome_last_line" id="mar_h">© 
		@php
			echo date("Y").", ";
		@endphp
		{{ __('Thesis Projects Manager') }}</p>
     </div>
   </div>
@endsection
