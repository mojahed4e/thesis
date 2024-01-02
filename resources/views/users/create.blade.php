@extends('layouts.app', ['activePage' => 'user-management', 'menuParent' => 'laravel', 'titlePage' => __('User Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" enctype="multipart/form-data" action="{{ route('user.store') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Add User') }}</h4>
              </div>
              <div class="card-body ">                                
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'name'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Email') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('email') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="input-email" type="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}" required />
                      @include('alerts.feedback', ['field' => 'email'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Role') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('role_id') ? ' has-danger' : '' }}">
                      <select class="selectpicker col-sm-12 pl-0 pr-0" name="role_id" id="role_id" onchange="funShowAvailable()" data-style="select-with-transition" title="" data-size="100">
                        <option value="2" @if (old('role_id') == '2') selected="selected" @endif>{{ __('Manager') }}</option>
                        <option value="3" @if (old('role_id') == '3') selected="selected" @endif>{{ __('Supervisor') }}</option>						
                      </select>
                      @include('alerts.feedback', ['field' => 'role_id'])
                    </div>
                  </div>
                </div>				
        				<div class="row" style="display:none" id="divShowCohort">
        					<label class="col-lg-2 col-form-label form_chg pt-3">{{ __('Cohort') }}</label>
        					<div class="col-lg-7">
        						<div class="form-group view_word{{ $errors->has('term_id') ? ' has-danger' : '' }}">
        							<select class="selectpicker col-sm-12 pl-0 pr-0" onchange="funShowAvailable()" name="term_id" id="term_id" data-style="select-with-transition" title="" data-size="100">
        								@foreach($terms as $term)											
        									<option value="{{$term->id}}" @if (old('term_id') == $term->id ) selected="selected" @endif>{{ $term->name }}</option>
        								@endforeach											
        							</select>
        							@include('alerts.feedback', ['field' => 'term_id'])
        							
        						</div>
        					</div>
        				</div>               			
        				<input type="hidden" name="password" id="input-password" value="secret" />
        				<input type="hidden" name="password_confirmation" id="input-password-confirmation" value="secret" />                
        				<div class="row" id="divShowAvailable" style="display:none">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Make Available') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('availabe_flage') ? ' has-danger' : '' }}">
                      <select class="selectpicker col-sm-12 pl-0 pr-0" name="availabe_flage" data-style="select-with-transition" title="" data-size="100">
                        <option value="1" @if (old('availabe_flage') == '1') selected="selected" @endif>{{ __('Ready To Accept') }}</option>
                        <option value="2" @if (old('availabe_flage') == '2') selected="selected" @endif>{{ __('Busy On Other Schedules') }}</option>						
                      </select>
                      @include('alerts.feedback', ['field' => 'availabe_flage'])
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" id="divShowAProgram" style="display:none">
                <label class="col-sm-2 col-form-label form_chg" style="text-align:left; margin-left: 85px;">{{ __('Program') }}</label>
                <div class="col-sm-7">
                  @php                
                   $aProgramInfo = \App\Program::select('programs.*')->where(['programs.status' => 1])->get();
                   $aProgramAvailability = array();                     
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
          </form>
        </div>
      </div>
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
		if(vOptValue == 3) {
			$('#divShowAvailable').show();
			$('#divShowCohort').hide();
      $('#divShowAProgram').show();
		}
		else if(vOptValue == 4) {
			$('#divShowAvailable').hide();
			$('#divShowCohort').show();
      $('#divShowAProgram').hide();
		}		
		else {
			$('#divShowCohort').hide();
			$('#divShowCohort').hide();
		}
	}
 </script>
@endpush