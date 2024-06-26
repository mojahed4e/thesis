@extends('layouts.app', ['activePage' => 'role-management', 'menuParent' => 'laravel', 'titlePage' => __('Role Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('term.store') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">category</i>
                </div>
                <h4 class="card-title">{{ __('Add Term') }}</h4>
              </div>
              <div class="card-body ">                
                <div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'name'])
                    </div>
                  </div>
                </div>
				<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Academic Year') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('academic_year') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('academic_year') ? ' is-invalid' : '' }}" name="academic_year" id="input-academic_year" type="text" placeholder="{{ __('Academic Year') }}" value="{{ old('academic_year') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'academic_year'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text" placeholder="{{ __('Description') }}" required="true" aria-required="true">{{ old('description') }}</textarea>
                      @include('alerts.feedback', ['field' => 'description'])
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                @if(auth()->user()->manager_flag != 2)
                  <button type="submit" class="btn bt_styl btn_txtbold">{{ __('Add Term') }}</button>				          
                @endif
                <a href="{{ route('term.index') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection