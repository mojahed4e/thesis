@extends('layouts.app', ['activePage' => 'template-folder', 'menuParent' => 'laravel', 'titlePage' => __('Document Files Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          @if(request()->get('req') != "main")
            <form method="post" action="{{ route('templates.store-folder-files',['folder_id='.$folder_id.'&subfolder_id='.request()->get('subfolder_id').'&type=uf']) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
          @else
            <form method="post" action="{{ route('templates.store-folder-files',['req=main&type=uf']) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
          @endif
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header">
                <h4 class="card-title view_word">{{ __('Add New File') }}</h4>
              </div>
              <div class="card-body">
                @if(request()->get('req') == "main")
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Select Sub Folder') }}<br />[{{ __('Program - Cohort - Folder Name - Sub Folder Name') }}]</label>
                  <div class="col-sm-9">
                    <div>
                       @foreach ($programs as $program)
                         @foreach ($cohorts as $term)
                          @php
                          $aFolderInfo = \App\DocumentTemplateFolders::select('document_template_folders.*')->where(['document_template_folders.term_id' => $term->id,'document_template_folders.program_id' => $program->id])->get();
                          @endphp
                          @if(count($aFolderInfo) > 0)
                            @foreach ($aFolderInfo as $folder)
                              @php
                              $aSubFolderInfo = \App\DocumentTemplateSubFolders::select('document_template_subfolders.*')->where(['document_template_subfolders.folder_id' => $folder->folder_id])->get();
                              @endphp
                              @if(count($aSubFolderInfo) > 0)
                                @foreach ($aSubFolderInfo as $subfolder)
                                  @if(!empty($subfolder->subfolder_name))
                                    <label class="form-check-label" style="line-height:35px; column-width:550px;">
                                      @if(request()->get('req') != "main" && request()->get('subfolder_id') == $subfolder->folder_id)
                                        <input name="sle_folder_selected[]" class="form-check-input" id="sle_folder_selected{{$subfolder->subfolder_id}}" value="{{$subfolder->subfolder_id}}" type="checkbox" style="margin-top: 8px;" checked='checked' />
                                      @else
                                          <input name="sle_folder_selected[]" class="form-check-input" id="sle_folder_selected{{$subfolder->subfolder_id}}" value="{{$subfolder->subfolder_id}}" type="checkbox" style="margin-top: 8px;" />
                                      @endif
                                      <span class="form-check-sign">
                                      <span class="check"></span>
                                      </span>
                                      <span class="col-sm-3 col-form-label form_chg" style="padding-left: 0px; padding-right: 5px;" >{{$program->name}}&nbsp;-&nbsp;{{$term->name}}&nbsp;-&nbsp;{{$folder->folder_name}}&nbsp;-&nbsp;{{$subfolder->subfolder_name}}</span></span>
                                    </label>
                                  @endif
                                @endforeach 
                              @endif
                            @endforeach 
                          @endif
                          @endforeach                     
                        @endforeach
                    </div>
                  </div>
                </div>


                @endif               
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('File Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('file_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('file_name') ? ' is-invalid' : '' }}" name="file_name" id="file_name" type="text" placeholder="{{ __('File Name') }}" value="{{ old('file_name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'file_name'])
                    </div>
                  </div>
                </div>

                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Select File') }}</label>
                  <div class="col-sm-7">
                      <div class="fileinput fileinput-new cht_text" style="padding-top:10px;" data-provides="fileinput">
                        <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20;">
                          <span class="fileinput-new" style="line-height: 0.30">Select file</span>
                          <span class="fileinput-exists" style="line-height: 0.30">Change File</span>
                          <input type="file" name="templatefile" id="templatefile"  required="true" />
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>                        
                      </div>
                    </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('file_description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('file_description') ? ' is-invalid' : '' }}" name="file_description" id="file_description" type="text" placeholder="{{ __('File Description') }}" required="true" aria-required="true">{{ old('file_description') }}</textarea>
                      @include('alerts.feedback', ['field' => 'file_description'])
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <input type="hidden" name="type" id="type" value="uf" />
                <input type="hidden" name="folder_id" id="folder_id" value="{{ $folder_id }}"/>
                <input type="hidden" name="subfolder_id" id="subfolder_id" value="{{ request()->get('subfolder_id') }}" />
                @if(auth()->user()->manager_flag != 2)
                  <button type="submit" class="btn bt_styl btn_txtbold">{{ __('Add File') }}</button>
                @endif
				        <a href="{{ route('templates.view-folders-files',['folder_id='.$folder_id.'&subfolder_id='.request()->get('subfolder_id').'&type=sf']) }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection