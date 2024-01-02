@extends('layouts.app', [
  'class' => 'off-canvas-sidebar',
  'classPage' => 'login-page',
  'activePage' => 'login',
  'title' => __('Thesis Manager - ADSM'),
  'pageBackground' => asset("material").'/img/login.jpg'
])

@section('content')
<div class="container-fluid pad_0">
	<div>
		<img src="{{ asset("material") }}/img/welcome_logo.png" class="set pb-5">
		<center>
			<div class="form_sett card-login pb-5 pt-5 pl-5 pr-5 card-hidden">
				
				 <form class="form" method="POST" action="{{ route('login') }}">
				@csrf				
						<span class="log_top">Login</span>
						<hr class="light pb-5">
						<span class="form-group  bmd-form-group email-error {{ $errors->has('email') ? ' has-danger' : '' }}" >
							<div class="form-group icon_mnl">
								<input  type="email" class="form-contro cht_text mainLoginInput" name="email" placeholder="&nbsp;&nbsp; &#61447; Enter Email" value="{{ old('email', '') }}" id="#" required>
								@include('alerts.feedback', ['field' => 'email'])
							</div>
						</span>
						<span class="form-group bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
							<div class="form-group">								
								<input type="password" class="form-contro cht_text mainLoginInput" id="examplePassword" name="password" placeholder="&nbsp;&nbsp; &#61475; Enter password." value="{{ old('password', '') }}" required>
								@include('alerts.feedback', ['field' => 'password'])
							</div>
						</span>						
						<div class="form-group form-check">&nbsp;</div>
						<button type="submit" class="btn logbt">login</button>					
				</form>
				
			</div>
		</center>
		<p class="last_line" id="mar_h">© 
		@php
			echo date("Y").", ";
		@endphp
		{{ __('ADSM - Thesis Projects Manager') }}</p>
	</div>
</div>
@endsection

@push('js')
<script>
  $(document).ready(function() {
    md.checkFullPageBackgroundImage();
    setTimeout(function() {
      // after 1000 ms we add the class animated to the login/register card
      $('.form_sett').removeClass('card-hidden');
    }, 700);
  });
</script>
@endpush
