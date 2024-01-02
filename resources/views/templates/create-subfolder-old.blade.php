@extends('layouts.app', ['activePage' => 'category-management', 'menuParent' => 'laravel', 'titlePage' => __('Document Sub Folder Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('templates.store-folder-files',['folder_id='.$subfolders[0]->folder_id.'&type=sfc']) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')           
            <div class="card ">
              <div class="card-header">
                <h4 class="card-title view_word">{{ __('Add New Sub Folder') }}</h4>
              </div>
              <div class="card-body"> 
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Folder Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word" style="font-weight:normal; font-size: 16px; padding-top: 8px;">
                        {{ $subfolders[0]->folder_name }}
                    </div>
                  </div>
                </div>                              
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Sub Folder Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('subfolder_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('subfolder_name') ? ' is-invalid' : '' }}" name="subfolder_name" id="subfolder_name" type="text" placeholder="{{ __('Sub Folder Name') }}" value="{{ old('subfolder_name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'subfolder_name'])
                    </div>
                  </div>
                </div>                 
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('subfolder_description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('subfolder_description') ? ' is-invalid' : '' }}" name="subfolder_description" id="subfolder_description" type="text" placeholder="{{ __('Sub Folder Description') }}" required="true" aria-required="true">{{ old('subfolder_description') }}</textarea>
                      @include('alerts.feedback', ['field' => 'subfolder_description'])
                    </div>
                  </div>
                </div>
              </div>              
              <div class="card-footer ml-auto mr-auto">
                <input type="hidden" name="type" id="type" value="sfc">
                @can('manage-items', App\User::class)
                  @if(auth()->user()->manager_flag != 2)
                    <button type="submit" class="btn bt_styl btn_txtbold">{{ __('Add Sub Folder') }}</button>
                  @endif
                @endcan
				        <a href="{{ route('templates.view-folders-files',['folder_id='.$folder_id.'&type=sf']) }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection