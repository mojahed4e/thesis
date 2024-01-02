@extends('layouts.app', ['activePage' => 'thesis-timeline', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Timeline Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" name="frmfrmCreateFolder" id="frmfrmCreateFolder" action="{{ route('timeline.store') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')           
            <div class="card ">
              <div class="card-header">
                <h4 class="card-title view_word">{{ __('Add New Timeline') }}</h4>
              </div>
              <div class="card-body"> 
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Select Program') }}</label>
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
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Select Cohort') }}</label>
                  <div class="col-sm-7">
                    <div>
                      @foreach ($cohorts as $term)
                        @php
                        $aTimelineFound = \App\ThesisTimeline::Active()->where(['term_id' => $term->id])->get();                        
                        @endphp
                        @if(count($aTimelineFound) != count($programs))
                          <label class="form-check-label" style="line-height:35px; column-width: 200px; margin-left: 20px;">
                            <input name="sle_cohort_selected[]" class="form-check-input" id="sle_cohort_selected{{$term->id}}" value="{{$term->id}}" type="checkbox" style="margin-top: 8px;" />
                            <span class="form-check-sign">
                            <span class="check"></span>
                            </span>
                            <span class="col-sm-1 col-form-label form_chg" style="padding-left: 0px; padding-right: 25px;" >&nbsp;{{$term->name}}</span>
                          </label>
                        @endif
                      @endforeach                     
                    </div>
                  </div>
                </div> 

                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Timeline Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('folder_name') ? ' is-invalid' : '' }}" name="folder_name" id="folder_name" type="text" placeholder="{{ __('Folder Name') }}" value="{{ old('folder_name') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'folder_name'])
                    </div>
                  </div>
                </div>                 
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('folder_description') ? ' is-invalid' : '' }}" name="folder_description" id="folder_description" type="text" placeholder="{{ __('Folder Description') }}" required="true" aria-required="true">{{ old('folder_description') }}</textarea>
                      @include('alerts.feedback', ['field' => 'folder_description'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Term - I Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1date') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1date" id="term1date" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1date', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1date'])
                    </div>
                  </div>                  
                </div>                
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 1 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet1') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet1" id="term1meet1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet1', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet1'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 2 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet2') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet2" id="term1meet2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet2', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet2'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 3 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet3') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet3" id="term1meet3" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet3', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet3'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - I Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1chapter1') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1chapter1" id="term1chapter1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1chapter1', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1chapter1'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>                  
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 4 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet4') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet4" id="term1meet4" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet4', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet4'])
                    </div>
                  </div>                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 5 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet5') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet5" id="term1meet5" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet5', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet5'])
                    </div>
                  </div>                                     
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - II Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1chapter2') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1chapter2" id="term1chapter2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1chapter2', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1chapter2'])
                    </div>
                  </div>                                  
                </div>
                 <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Presentation Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1presentation') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1presentation" id="term1presentation" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1presentation', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term1presentation'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Term - II Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2date') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2date" id="term2date" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term2date'])
                    </div>
                  </div>                  
                </div>

                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 1 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet1') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet1" id="term2meet1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2meet1', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet1'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 2 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet2') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet2" id="term2meet2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2meet2', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet2'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 3 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet3') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet3" id="term2meet3" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2meet3', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet3'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - I Completion Date') }}</label>
                <div class="col-sm-2">
                  <div class="form-group view_word {{ $errors->has('term2chapter1') ? ' has-danger' : '' }}">
                    <input type="text"  name="term2chapter1" id="term2chapter1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2chapter1', '') }}"/>            
                    @include('alerts.feedback', ['field' => 'term2chapter1'])
                  </div>
                </div>                                  
              </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>                  
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 4 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet4') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet4" id="term2meet4" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2meet4', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet4'])
                    </div>
                  </div>                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 5 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet5') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet5" id="term2meet5" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2meet5', '') }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet5'])
                    </div>
                  </div>                                     
                </div>
              </div>
              <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - II Completion Date') }}</label>
                <div class="col-sm-2">
                  <div class="form-group view_word {{ $errors->has('term2chapter2') ? ' has-danger' : '' }}">
                    <input type="text"  name="term2chapter2" id="term2chapter2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2chapter2', '') }}"/>            
                    @include('alerts.feedback', ['field' => 'term2chapter2'])
                  </div>
                </div>                                  
              </div>
              <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Presentation Completion Date') }}</label>
                <div class="col-sm-2">
                  <div class="form-group view_word {{ $errors->has('term2presentation') ? ' has-danger' : '' }}">
                    <input type="text"  name="term2presentation" id="term2presentation" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2presentation', '') }}"/>            
                    @include('alerts.feedback', ['field' => 'term2presentation'])
                  </div>
                </div>                                  
              </div>
              <div class="card-footer ml-auto mr-auto">
                <span id="showErrors" class="form_chg" style="color:red !important;"></span>
              </div>       
              <div class="card-footer ml-auto mr-auto">
                <input type="hidden" name="type" id="type" value="tc">
                @can('manage-items', App\User::class)
                  @if(auth()->user()->manager_flag != 2)
                    <button type="button" onclick="funValidateFormInput()" class="btn bt_styl btn_txtbold">{{ __('Add Timeline') }}</button>
                  @endif
                @endcan
				        <a href="{{ route('timeline.view-thesis-timeline') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
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
  $(document).ready(function() {
    $('.datetimepicker').datetimepicker({
      icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-chevron-up",
          down: "fa fa-chevron-down",
          previous: 'fa fa-chevron-left',
          next: 'fa fa-chevron-right',
          today: 'fa fa-screenshot',
          clear: 'fa fa-trash',
          close: 'fa fa-remove'
      },
      format: 'DD-MM-YYYY'
    });
    tinymce.init({
      selector: '#folder_description',
      setup: function (editor) {
        editor.on('change', function () {
          editor.save();
        });
      }
    });
  });  
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
      swal("", "Please enter the timeline name", "error").then(function() { 
         $("#folder_name").focus();
         return false;      
      }); 
    }
    else if($("#folder_description").val() == "") {
      swal("", "Please enter the timeline description", "error").then(function() {
         $("#folder_description").focus();        
         return false;      
      }); 
    }
    else if($("#term1date").val() == "" || $("#term1meet1").val() == ""|| $("#term1meet2").val() == ""|| $("#term1meet3").val() == ""|| $("#term1meet4").val() == ""|| $("#term1meet5").val() == ""|| $("#term2date").val() == ""|| $("#term2meet1").val() == ""|| $("#term2meet2").val() == ""|| $("#term2meet3").val() == ""|| $("#term2meet4").val() == ""|| $("#term2meet5").val() == "") {
      $('#showErrors').html("Please select all date");
      return false;            
    }
    else{
      document.frmfrmCreateFolder.method='post'
      document.frmfrmCreateFolder.submit();
    }    
  }
</script>
@endpush