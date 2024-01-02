@extends('layouts.app', ['activePage' => 'category-management', 'menuParent' => 'laravel', 'titlePage' => __('Document Sub Folder Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" name="frmfrmCreateFolder" id="frmfrmCreateFolder"  action="{{ route('templates.store-folder-files',['folder_id='.$subfolders[0]->folder_id.'&type=sfc']) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')           
            <div class="card ">
              <div class="card-header">
                <h4 class="card-title view_word">{{ __('Add New Sub Folder') }}</h4>
              </div>
              <div class="card-body"> 
                
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Select Folder') }}<br />[{{ __('Cohort - Folder Name') }}]</label>
                  <div class="col-sm-9">
                    <div>
                       @foreach ($programs as $program)
                         @foreach ($cohorts as $term)
                          @php
                          $aFolderInfo = \App\DocumentTemplateFolders::select('document_template_folders.*')->where(['document_template_folders.term_id' => $term->id,'document_template_folders.program_id' => $program->id])->get();
                          @endphp
                          @if(count($aFolderInfo) > 0)
                            @foreach ($aFolderInfo as $folder)
                              <label class="form-check-label" style="line-height:35px; column-width:550px;">
                                @if(request()->get('req') != "main" && request()->get('folder_id') == $folder->folder_id)
                                  <input name="sle_folder_selected[]" class="form-check-input" id="sle_folder_selected{{$folder->folder_id}}" value="{{$folder->folder_id}}" type="checkbox" style="margin-top: 8px;" checked='checked' />
                                @else
                                    <input name="sle_folder_selected[]" class="form-check-input" id="sle_folder_selected{{$folder->folder_id}}" value="{{$folder->folder_id}}" type="checkbox" style="margin-top: 8px;" />
                                @endif
                                <span class="form-check-sign">
                                <span class="check"></span>
                                </span>
                                <span class="col-sm-3 col-form-label form_chg" style="padding-left: 0px; padding-right: 5px;" >{{$program->name}}&nbsp;-&nbsp;{{$term->name}}&nbsp;-&nbsp;{{$folder->folder_name}}</span></span>
                              </label>
                            @endforeach 
                          @endif
                          @endforeach                     
                        @endforeach
                    </div>
                  </div>
                </div>

                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Sub Folder Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('subfolder_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('subfolder_name') ? ' is-invalid' : '' }}" name="subfolder_name" id="subfolder_name" type="text" placeholder="{{ __('Sub Folder Name') }}" value="{{ old('subfolder_name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'subfolder_name'])
                    </div>
                  </div>
                </div>                 
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Description') }}</label>
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
                    <button type="button" onclick="funValidateFormInput()" class="btn bt_styl btn_txtbold">{{ __('Add Sub Folder') }}</button>
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
@push('js')
<script src="{{ asset('material') }}/uploader/jquery.uploadfile.min.js"></script>
<script>  
  function funValidateFormInput() {
    if($(".form-check-input:checked").length == 0) {
      swal("", "Please select atlease one folder", "error").then(function() {        
         return false;      
      }); 
    }
    else if($("#subfolder_name").val() == "") {
      swal("", "Please enter the sub folder name", "error").then(function() { 
         $("#subfolder_name").focus();
         return false;      
      }); 
    }
    else if($("#subfolder_description").val() == "") {
      swal("", "Please enter the sub folder description", "error").then(function() {
         $("#subfolder_description").focus();        
         return false;      
      }); 
    }
    else{
      document.frmfrmCreateFolder.method='post'
      document.frmfrmCreateFolder.submit();
    }    
  }
</script>
@endpush