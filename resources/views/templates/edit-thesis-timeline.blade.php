@extends('layouts.app', ['activePage' => 'thesis-timeline', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Timeline Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('timeline.update',['timeline_id='.$timelineinfo[0]->timeline_id.'&type=tu']) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __('Edit Timeline') }}</h4>
              </div>
              <div class="card-body ">
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Program') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group form_chg pt-2">                       
                        @foreach ($programs as $program)
                          @if($program->id == $timelineinfo[0]->program_id)
                            {{ $program->name }}
                          @endif
                        @endforeach
                    </div>
                  </div>
                </div> 
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Cohort') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group form_chg pt-2">                       
                        @foreach ($cohorts as $term)
                          @if($term->id == $timelineinfo[0]->term_id)
                            {{ $term->name }}
                          @endif
                        @endforeach
                    </div>
                  </div>
                </div>                 
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Timeline Name') }}</label>
                   <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('folder_name') ? ' is-invalid' : '' }}" name="folder_name" id="folder_name" type="text" placeholder="{{ __('Folder Name') }}" value="{{ old('folder_name', $timelineinfo[0]->timeline_name) }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'folder_name'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group view_word {{ $errors->has('folder_description') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="10" class="form-control{{ $errors->has('folder_description') ? ' is-invalid' : '' }}" name="folder_description" id="folder_description" type="text" placeholder="{{ __('Description') }}" required="true" aria-required="true">{{ old('folder_description', $timelineinfo[0]->timeline_description) }}</textarea>
                      @include('alerts.feedback', ['field' => 'folder_description'])
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Term - I Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1date') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1date" id="term1date" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1date', ($timelineinfo[0]->term1_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term1_completion)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1date'])
                    </div>
                  </div>                  
                </div>

                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 1 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet1') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet1" id="term1meet1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet1', ($timelineinfo[0]->t1_meeting_minutes1 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes1)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet1'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 2 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet2') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet2" id="term1meet2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet2', ($timelineinfo[0]->t1_meeting_minutes2 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes2)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet2'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 3 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet3') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet3" id="term1meet3" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet3', ($timelineinfo[0]->t1_meeting_minutes3 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes3)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet3'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - I Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1chapter1') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1chapter1" id="term1chapter1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet3', ($timelineinfo[0]->term1chapter1 ? \Carbon\Carbon::parse($timelineinfo[0]->term1chapter1)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1chapter1'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>                  
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 4 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet4') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet4" id="term1meet4" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet4', ($timelineinfo[0]->t1_meeting_minutes4 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes4)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet4'])
                    </div>
                  </div>                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 5 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1meet5') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1meet5" id="term1meet5" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet5', ($timelineinfo[0]->t1_meeting_minutes5 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes5)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1meet5'])
                    </div>
                  </div>                                     
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - II Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1chapter2') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1chapter2" id="term1chapter2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet5', ($timelineinfo[0]->term1chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term1chapter2)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1chapter2'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Presentation Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term1presentation') ? ' has-danger' : '' }}">
                      <input type="text"  name="term1presentation" id="term1presentation" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet5', ($timelineinfo[0]->term1presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term1presentation)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term1presentation'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{{ __('Term - II Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2date') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2date" id="term2date" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->term2_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term2_completion)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2date'])
                    </div>
                  </div>                  
                </div>

                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 1 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet1') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet1" id="term2meet1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes1 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes1)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet1'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 2 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet2') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet2" id="term2meet2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes2 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes2)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet2'])
                    </div>
                  </div>                                    
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 3 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet3') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet3" id="term2meet3" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes3 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes3)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet3'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - I Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2chapter1') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2chapter1" id="term2chapter1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->term2chapter1 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter1)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2chapter1'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>                  
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 4 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet4') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet4" id="term2meet4" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes4 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes4)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet4'])
                    </div>
                  </div>                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 5 Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2meet5') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2meet5" id="term2meet5" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes5 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes5)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2meet5'])
                    </div>
                  </div>                                     
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - II Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2chapter2') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2chapter2" id="term2chapter2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2chapter2'])
                    </div>
                  </div>                                  
                </div>
                <div class="row">
                  <div class="col-sm-2">&nbsp;</div>
                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Presentation Completion Date') }}</label>
                  <div class="col-sm-2">
                    <div class="form-group view_word {{ $errors->has('term2presentation') ? ' has-danger' : '' }}">
                      <input type="text"  name="term2presentation" id="term2presentation" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->term2presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term2presentation)->format('d-m-Y') : '')) }}"/>            
                      @include('alerts.feedback', ['field' => 'term2presentation'])
                    </div>
                  </div>                                  
                </div>
              </div>             
               @php
                $aProgressInfo = \App\TermProgressChecklist::where(['timeline_id' => $timelineinfo[0]->timeline_id, 'status' => 1])->get();                        
                @endphp  
              <div class="card-footer ml-auto mr-auto">
                <input type="hidden" name="type" id="type" value="tu">
                <input type="hidden"  name="timeline_id" id="timeline_id" value="{{$timelineinfo[0]->timeline_id}}">
                @can('manage-items', App\User::class)
                  @if(auth()->user()->manager_flag != 2 && count($aProgressInfo) == 0)
                    <button type="submit" class="btn bt_styl btn_txtbold">{{ __('Update') }}</button>
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
    else{
      document.frmfrmCreateFolder.method='post'
      document.frmfrmCreateFolder.submit();
    }    
  }
</script>
@endpush