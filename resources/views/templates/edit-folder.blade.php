@extends('layouts.app', ['activePage' => 'category-management', 'menuParent' => 'laravel', 'titlePage' => __('Document Folder Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('templates.store-folder-files',['folder_id='.$templatefolder[0]->folder_id.'&type=fu']) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Edit Folder') }}</h4>
              </div>
              <div class="card-body "> 
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Cohort') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('term_id') ? ' has-danger' : '' }}">
                        <select class="selectpicker col-sm-12 pl-0 pr-0"  name="term_id" data-style="select-with-transition" style="margin-top: 36px !important;" title="" data-size="10" required="true" aria-required="true">
                        <option value="">Select Cohort</option>
                        @foreach ($cohorts as $term)
                        <option value="{{ $term->id }}" {{ $term->id == $templatefolder[0]->term_id ? 'selected' : '' }}>{{ $term->name }}</option>
                        @endforeach
                      </select>
                      @include('alerts.feedback', ['field' => 'term_id'])
                    </div>
                  </div>
                </div>                 
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Folder Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('folder_name') ? ' is-invalid' : '' }}" name="folder_name" id="folder_name" type="text" placeholder="{{ __('Name') }}" value="{{ old('folder_name', $templatefolder[0]->folder_name) }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'folder_name'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('folder_description') ? ' is-invalid' : '' }}" name="folder_description" id="folder_description" type="text" placeholder="{{ __('Description') }}" required="true" aria-required="true">{{ old('folder_description', $templatefolder[0]->folder_description) }}</textarea>
                      @include('alerts.feedback', ['field' => 'folder_description'])
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <input type="hidden" name="type" id="type" value="fe">
                @can('manage-items', App\User::class)
                  @if(auth()->user()->manager_flag != 2)
                    <button type="submit" class="btn bt_styl btn_txtbold">{{ __('Save') }}</button>
                  @endif
                @endcan
				        <a href="{{ route('templates.view-folders-files') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection