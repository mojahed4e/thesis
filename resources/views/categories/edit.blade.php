@extends('layouts.app', ['activePage' => 'category-management', 'menuParent' => 'laravel', 'titlePage' => __('Category Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('category.update', $category) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Edit Category') }}</h4>
              </div>
              <div class="card-body ">
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Program') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('program_id') ? ' has-danger' : '' }}">
                        <select class="selectpicker col-sm-12 pl-0 pr-0" name="program_id" id="program_id" data-style="select-with-transition" title="" data-size="100">
                        <option value="">Select Program</option>
                        @foreach ($programs as $program)
                        <option value="{{ $program->id }}" {{ $program->id == $category->program_id ? 'selected' : '' }}>{{ $program->description }}</option>
                        @endforeach
                      </select>
                      @include('alerts.feedback', ['field' => 'program_id'])
                    </div>
                  </div>
                </div>                
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $category->name) }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'name'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text" placeholder="{{ __('Description') }}" required="true" aria-required="true">{{ old('description', $category->description) }}</textarea>
                      @include('alerts.feedback', ['field' => 'description'])
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                @if(auth()->user()->manager_flag != 2)
                  <button type="submit" class="btn bt_styl btn_txtbold">{{ __('Save') }}</button>
                @endif
				        <a href="{{ route('category.index') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection