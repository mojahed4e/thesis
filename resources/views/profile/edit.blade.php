@extends('layouts.app', ['activePage' => 'profile', 'menuParent' => 'laravel', 'titlePage' => __('User Profile')])

@section('content')
<div class="content bodybg">
  <div class="container-fluid">
	<div class="row">
      <div class="col-lg-4">
			<div class=" text-center">
				<div class="card ">
					<div class="card-block">
						<img src="{{ config('items.image_auth.path') }}{{ auth()->user()->profilePicture() }}" class="img-fluid rounded-circle w-25 mb-3" />		
						<h4 class="sid_text">{{ __(auth()->user()->name) }}</h4>
						<h5 class="text-muted">
							@php
							$aRoleInfo = App\Role::find(auth()->user()->role_id)
							@endphp
							{{ __($aRoleInfo->name) }}							
						</h5>
						<p class="c_text pb-5">&nbsp;</p>
						@if(auth()->user()->role_id == 4)
						<p class="c_text pb-5">&nbsp;</p>
						@endif
						<div class="d-flex flex-row justify-content-center"></div>
					</div>
				</div>
			</div>        
		</div> 
		<div class="col-lg-8">
			<div class="">
				<div class="card">
					<div class="card-block sid_text">
						<p class="view_word">{{ __('Name') }}<span class="form_chg pl-5" style="font-size:16px !important;">{{ __(auth()->user()->name) }}</span></p>
						<hr class="">
						<p class="view_word">{{ __('Email') }}<span class="form_chg pl-5" style="font-size:16px !important;">{{ __(auth()->user()->email) }}</span></p>
						<hr class="">
						<p class="view_word">{{ __('Role') }}<span class="form_chg pl-5" style="font-size:16px !important; pl-2">
							@php
							$aRoleInfo = App\Role::find(auth()->user()->role_id)
							@endphp
							{{ __($aRoleInfo->name) }}
							</span>
						</p>
						@if(auth()->user()->term_id > 0)
							<hr class="">
							<p class="view_word">{{ __('Cohort') }}<span class="form_chg pl-4" style="font-size:16px !important;">
							@php
								$aTermInfo = App\Term::find(auth()->user()->term_id);
								$vTermName = $aTermInfo->name;
							@endphp
							{{ __($vTermName) }}
							</span></p>
						@endif
						<hr class="pad_card_last">
						@if(auth()->user()->role_id < 3)
						<div  class="pt-5 text-center" style="padding-bottom:7px;">
							<a class="bct_list" href="{{ route('user.index') }}"><i class="fas fa-chevron-circle-left"></i>&nbsp {{ __('BACK TO LIST') }}</a>
						</div>
						@else
						<div  class="pt-3 text-center" style="line-height:7px;">
							&nbsp;
						</div>	
						@endif
					</div>
				</div>
			</div>        
		</div> 
    </div>
  </div>
</div>
@endsection