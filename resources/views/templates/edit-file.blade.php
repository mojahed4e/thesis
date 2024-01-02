@extends('layouts.app', ['activePage' => 'template-folder', 'menuParent' => 'laravel', 'titlePage' => __('Document Files Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('templates.store-folder-files',['folder_id='.$templatefiles[0]->folder_id.'&subfolder_id='.$templatefiles[0]->subfolder_id.'&file_id='.$templatefiles[0]->file_id.'&type=ef']) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Edit File') }}</h4>
              </div>
              <div class="card-body ">                
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('File Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('file_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('file_name') ? ' is-invalid' : '' }}" name="file_name" id="file_name" type="text" placeholder="{{ __('File Name') }}" value="{{ old('file_name', $templatefiles[0]->file_name) }}" required="flase" aria-required="flase"/>
                      @include('alerts.feedback', ['field' => 'file_name'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Select File') }}</label>
                  <div class="col-sm-7">
                      <div class="fileinput fileinput-new cht_text" style="padding-top:10px;" data-provides="fileinput">
                        <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20;">
                          <span class="fileinput-new" style="line-height: 0.30">Change File</span>
                          <span class="fileinput-exists" style="line-height: 0.30">Change File</span>
                          <input type="file" name="templatefile" id="templatefile"  required="true" />
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a> 
                        <span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$templatefiles[0]->file_id,'type=templete']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>                       
                      </div>
                    </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('file_description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('file_description') ? ' is-invalid' : '' }}" name="file_description" id="file_description" type="text" placeholder="{{ __('File Description') }}" required="true" aria-required="true">{{ old('file_description', $templatefiles[0]->file_description) }}</textarea>
                      @include('alerts.feedback', ['field' => 'file_description'])
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <input type="hidden" name="type" id="type" value="ef" />
                <input type="hidden" name="folder_id" id="folder_id" value="{{ $templatefiles[0]->folder_id }}"/>
                <input type="hidden" name="subfolder_id" id="subfolder_id" value="{{ $templatefiles[0]->subfolder_id }}"/>
                <input type="hidden" name="file_id" id="file_id" value="{{ $templatefiles[0]->file_id }}"/>
                @if(auth()->user()->manager_flag != 2)
                  <button type="submit" class="btn bt_styl btn_txtbold">{{ __('Save') }}</button>
                @endif
				        <a href="{{ route('templates.view-folders-files',['folder_id='.$templatefiles[0]->folder_id.'&subfolder_id='.$templatefiles[0]->subfolder_id.'&type=vf']) }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection