@extends('layouts.app', ['activePage' => 'user-management', 'menuParent' => 'laravel', 'titlePage' => __('User Management')])

@section('content')
  <div class="content bodybg">
    <div class="container-fluid">
		<form method="post" enctype="multipart/form-data" action="{{ route('user.update', $user) }}" autocomplete="off" class="form-horizontal">
		@csrf
		@method('put')
		<div class="row">
			<div class="col-lg-4">
				<div class=" text-center">
					<div class="card ">
						<div class="card-block">
							<img src="{{ config('items.image_auth.path') }}{{ $user->profilePicture() }}" class="img-fluid rounded-circle w-25 pb-3 pt-5" />		
							<h4 class="sid_text">{{ __($user->name) }}</h4>
							<h5 class="text-muted">
								@php
								$aRoleInfo = App\Role::find($user->role_id)
								@endphp
								{{ __($aRoleInfo->name) }}
							</h5>
							<p class="c_text pb-4">&nbsp;</p>
							@if($user->role_id == 4)
								<p class="c_text" style="padding-bottom:100px;">&nbsp;</p>
							@else
								<p class="c_text" style="padding-bottom:250px;">&nbsp;</p>
							@endif
							<div class="d-flex flex-row justify-content-center"></div>
						</div>
					</div>
				</div>        
			</div> 
			<div class="col-lg-8">	
			<div class="">					
					<div class="card p-5">
						<div class="row pb-5">
							<h4 class="card-title view_word">{{ __('Edit User') }}</h4>
						</div>
						<div class="row">
							<label class="col-lg-3 col-form-label form_chg pt-3">{{ __('Name') }}</label>
							<div class="col-lg-9">
								<div class="form-group view_word {{ $errors->has('name') ? ' has-danger' : '' }}">
									<input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required="true" aria-required="true"/>
									@include('alerts.feedback', ['field' => 'name'])
								</div>
							</div>
						</div>
						
						<div class="row">
							<label class="col-lg-3 col-form-label form_chg pt-3">{{ __('Email') }}</label>
							<div class="col-lg-9">
								<div class="form-group view_word{{ $errors->has('email') ? ' has-danger' : '' }}">
									<input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" required />
									@include('alerts.feedback', ['field' => 'email'])
								</div>
							</div>
						</div>
			
						<div class="row">
							<label class="col-lg-3 col-form-label form_chg pt-3">{{ __('Role') }}</label>
							<div class="col-lg-9">
								<div class="form-group view_word{{ $errors->has('role_id') ? ' has-danger' : '' }}">
									<select class="selectpicker col-sm-12 pl-0 pr-0" onchange="funShowAvailable()" name="role_id" id="role_id" data-style="select-with-transition" title="" data-size="100">
										<option value="2" @if (old('role_id', $user->role_id) == '2') selected="selected" @endif>{{ __('Manager') }}</option>
										<option value="3" @if (old('role_id', $user->role_id) == '3') selected="selected" @endif>{{ __('Supervisor') }}</option>
										<option value="4" @if (old('role_id', $user->role_id) == '4') selected="selected" @endif>{{ __('Student') }}</option>
									</select>
									@include('alerts.feedback', ['field' => 'role_id'])
								</div>
							</div>
						</div>
						@if($user->role_id == 4)
						<div class="row">
							<label class="col-lg-3 col-form-label form_chg pt-3">{{ __('Program') }}</label>
							<div class="col-lg-9">
								<div class="form-group view_word{{ $errors->has('program_id') ? ' has-danger' : '' }}">
									<select class="selectpicker col-sm-12 pl-0 pr-0" onchange="funShowAvailable()" name="program_id" id="program_id" data-style="select-with-transition" title="" data-size="100">
										@foreach($programs as $program)											
											<option value="{{$program->id}}" @if (old('program_id', $user->program_id) == $program->id ) selected="selected" @endif>{{ $program->description }}</option>
										@endforeach											
									</select>
									@include('alerts.feedback', ['field' => 'program_id'])
								</div>
							</div>
						</div>
						<div class="row">
							<label class="col-lg-3 col-form-label form_chg pt-3">{{ __('Cohort') }}</label>
							<div class="col-lg-9">
								<div class="form-group view_word{{ $errors->has('term_id') ? ' has-danger' : '' }}">
									<select class="selectpicker col-sm-12 pl-0 pr-0" onchange="funShowAvailable()" name="term_id" id="term_id" data-style="select-with-transition" title="" data-size="100">
										@foreach($terms as $term)											
											<option value="{{$term->id}}" @if (old('term_id', $user->term_id) == $term->id ) selected="selected" @endif>{{ $term->name }}</option>
										@endforeach											
									</select>
									@include('alerts.feedback', ['field' => 'term_id'])
								</div>
							</div>
						</div>
						@endif
						<input type="hidden" name="password" id="input-password" value="secret" />
						<input type="hidden" name="password_confirmation" id="input-password-confirmation" value="secret" />

						<div class="row" id="divShowAvailable" style="display:none">
							<label class="col-lg-3 col-form-label form_chg pt-3">{{ __('Make Available') }}</label>
							<div class="col-lg-9">
								<div class="form-group view_word {{ $errors->has('availabe_flage') ? ' has-danger' : '' }}">
									<select class="selectpicker col-sm-12 pl-0 pr-0" name="availabe_flage" data-style="select-with-transition" title="" data-size="100">
										<option value="1" @if (old('availabe_flage', $user->availabe_flage) == '1') selected="selected" @endif>{{ __('Ready To Accept') }}</option>
										<option value="2" @if (old('availabe_flage', $user->availabe_flage) == '2') selected="selected" @endif>{{ __('Busy On Other Schedules') }}</option>						
									</select>
									@include('alerts.feedback', ['field' => 'availabe_flage'])
								</div>
							</div>
						</div>

						<div class="row" id="divShowAProgram" style="display:none">
							<label class="col-lg-3 col-form-label form_chg pt-3">{{ __('Program') }}</label>
							<div class="col-lg-9">
								@php								
								 $aProgramInfo = \App\Program::select('programs.*')->where(['programs.status' => 1])->get();
								 $aProgramAvailability = explode(",",$user->program_availability);										 
								@endphp
								<div class="form-group view_word {{ $errors->has('program_availability') ? ' has-danger' : '' }}">
									@if(count($aProgramInfo) > 0)
										@foreach($aProgramInfo as $program)											
											<label><input type="checkbox" name="program_availability[]" value="{{ $program->id }}" style="width: 1.5em;height: 1.5em; vertical-align: middle; margin-right: 8px;" @if (in_array($program->id,$aProgramAvailability)) checked='checked' @endif> {{ $program->description }}</label><br />		                
										@endforeach
									@endif
								</div>
							</div>
						</div>
						
						<div class="card-footer ml-auto mr-auto">
							@if(auth()->user()->manager_flag != 2)
								<button type="submit" class="btn bt_styl btn_txtbold">{{ __('Save') }}</button>
							@endif
							<a href="{{ route('user.index') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
						</div>						
					</div>
				</div>        
			</div> 
		</div>
		</form>
    </div>
  </div>
@endsection
@push('js')  
	<script>
	$(document).ready(function() {
		funShowAvailable();
	});	
	function funShowAvailable() {
		var vOptValue = $('#role_id').val();
		if(vOptValue == 3  || vOptValue == 2) {
			$('#divShowAvailable').show();
			$('#divShowAProgram').show();
		}
		else {
			$('#divShowAvailable').hide();
			$('#divShowAProgram').hide();
		}
	}
 </script>
@endpush	