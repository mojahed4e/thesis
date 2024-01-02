@extends('layouts.app', ['activePage' => 'category-management', 'menuParent' => 'laravel', 'titlePage' => __('Document Folder Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" name="frmfrmCreateFolder" id="frmfrmCreateFolder" action="{{ route('templates.store-folder-files') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')           
            <div class="card ">
              <div class="card-header">
                <h4 class="card-title view_word">{{ __('Add New Folder') }}</h4>
              </div>
              <div class="card-body"> 

                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Select Program') }}</label>
                  <div class="col-sm-7">
                    <div>
                        @foreach ($programs as $program)
                       <label class="form-check-label" style="line-height:35px; column-width: 450px;">
                          <input name="sle_program_selected[]" class="form-check-input1" id="sle_program_selected{{$program->id}}" value="{{$program->id}}" type="checkbox" style="margin-top: 8px;" />                          
                          <span class="form-check-sign">
                          <span class="check"></span>
                          </span>
                          <span class="col-sm-1 col-form-label form_chg" style="padding-left: 0px; padding-right: 25px;" >&nbsp;{{$program->description}}</span></span>
                        </label>
                        @endforeach                     
                    </div>
                  </div>
                </div> 
                <div class="row">&nbsp;</div>
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Select Cohort') }}</label>
                  <div class="col-sm-7">
                    <div>
                        @foreach ($cohorts as $term)
                       <label class="form-check-label" style="line-height:35px; column-width: 200px; margin-left: 20px;">
                          <input name="sle_cohort_selected[]" class="form-check-input" id="sle_cohort_selected{{$term->id}}" value="{{$term->id}}" type="checkbox" style="margin-top: 8px;" />
                          <span class="form-check-sign">
                          <span class="check"></span>
                          </span>
                          <span class="col-sm-1 col-form-label form_chg" style="padding-left: 0px; padding-right: 25px;" >&nbsp;{{$term->name}}</span></span>
                        </label>
                        @endforeach                     
                    </div>
                  </div>
                </div> 

                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Folder Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('folder_name') ? ' is-invalid' : '' }}" name="folder_name" id="folder_name" type="text" placeholder="{{ __('Folder Name') }}" value="{{ old('folder_name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'folder_name'])
                    </div>
                  </div>
                </div>                 
                <div class="row">
                  <label class="col-sm-2 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('folder_description') ? ' is-invalid' : '' }}" name="folder_description" id="folder_description" type="text" placeholder="{{ __('Folder Description') }}" required="true" aria-required="true">{{ old('folder_description') }}</textarea>
                      @include('alerts.feedback', ['field' => 'folder_description'])
                    </div>
                  </div>
                </div>
              </div>              
              <div class="card-footer ml-auto mr-auto">
                <input type="hidden" name="type" id="type" value="fc">
                @can('manage-items', App\User::class)
                  @if(auth()->user()->manager_flag != 2)
                    <button type="button" onclick="funValidateFormInput()" class="btn bt_styl btn_txtbold">{{ __('Add Folder') }}</button>
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

@push('js')
<script src="{{ asset('material') }}/uploader/jquery.uploadfile.min.js"></script>
<script>  
  function funValidateFormInput() {
    if($(".form-check-input1:checked").length == 0) {
      swal("", "Please select atlease one program", "error").then(function() {        
         return false;      
      }); 
    }
    else if($(".form-check-input:checked").length == 0) {
      swal("", "Please select atlease one cohort", "error").then(function() {        
         return false;      
      }); 
    }
    else if($("#folder_name").val() == "") {
      swal("", "Please enter the folder name", "error").then(function() { 
         $("#folder_name").focus();
         return false;      
      }); 
    }
    else if($("#folder_description").val() == "") {
      swal("", "Please enter the folder description", "error").then(function() {
         $("#folder_description").focus();        
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