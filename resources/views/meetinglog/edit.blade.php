@extends('layouts.app', ['activePage' => 'metting-logs', 'menuParent' => 'laravel', 'titlePage' => __('Meeting Logs Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" id="frmMeetingLogs" name="frmMeetingLogs" action="{{ route('meetinglogs.update',$meetinglogifo[0]) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">category</i>
                </div>
                <h4 class="card-title">{{ __('Update Meeting Log') }}</h4>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('meetinglogs.index','id='.request()->item_id) }}" class="btn btn-sm btn-rose">{{ __('Back to list') }}</a>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label">{{ __('Thesis Title') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
						{{ $iteminfo[0]->title }}
                    </div>
                  </div>
                </div>
				<div class="row">
                  <label class="col-sm-3 col-form-label">{{ __('Student Names') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('academic_year') ? ' has-danger' : '' }}">
                      {{ $iteminfo[0]->requested_by }}
					  @if(count($groupinfo))
						  @foreach($groupinfo AS $group)
							, {{ $group->group_members }}
						  @endforeach
					  @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label">{{ __('Supervisor Name') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
						{{ auth()->user()->name }}
                    </div>
                  </div>
                </div>
				<div class="row">
                  <label class="col-sm-3 col-form-label"><span class="mark">*</span>{{ __('Date Of Meeting') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                       <input type="text" name="meeetingdate" id="meeetingdate"
						  placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('meeetingdate', \Carbon\Carbon::parse($meetinglogifo[0]->meeting_date)->format('d-m-Y')) }}"/>
						  @include('alerts.feedback', ['field' => 'meeetingdate'])
                    </div>
                  </div>
                </div>				
				<div class="row">
				  <label class="col-sm-3 col-form-label">{{ __('Date Of Previous Meeting') }}:</label>
				  <div class="col-sm-7">
					<div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
					@if(count($meetinglogifo) > 0)					
						{{ \Carbon\Carbon::parse($meetinglogifo[0]->meeting_date)->format('d-m-Y') }}
					@else
						{{ __('-NIL-') }}
					@endif
					</div>
				  </div>
				</div>				
				<div class="row">
                  <label class="col-sm-3 col-form-label"><span class="mark">*</span>{{ __('Work Undertaken Since ')}}<br />{{__('Last Meeting/Last Milestone Achieved') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('lastmilestoneachieved') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="15" class="form-control{{ $errors->has('lastmilestoneachieved') ? ' is-invalid' : '' }}" name="lastmilestoneachieved" id="lastmilestoneachieved" type="text" placeholder="{{ __('Work Undertaken Since Last Meeting/Last Milestone Achieved') }}" required="true" aria-required="true">{{ $meetinglogifo[0]->milestone_achived_last_meeting }}</textarea>
                      @include('alerts.feedback', ['field' => 'lastmilestoneachieved'])
                    </div>
                  </div>
                </div>
				<div class="row">
                  <label class="col-sm-3 col-form-label"><span class="mark">*</span>{{ __('Issues/Progress You Would')}}<br />{{__(' Like To Discuss In This Meeting') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('discussthismeeting') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="15" class="form-control{{ $errors->has('discussthismeeting') ? ' is-invalid' : '' }}" name="discussthismeeting" id="discussthismeeting" type="text" placeholder="{{ __('Issues/Progress You Would Like To Discuss In This Meeting') }}" required="true" aria-required="true">{{ $meetinglogifo[0]->discussed_this_meeting }}</textarea>
                      @include('alerts.feedback', ['field' => 'discussthismeeting'])
                    </div>
                  </div>
                </div>
				<div class="row">
                  <label class="col-sm-3 col-form-label"><span class="mark">*</span>{{ __('Student Should Undertake Work Between Now And')}}<br />{{__(' Next Meeting (Next Meeting Agenda Points)') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('nextmeetingagenda') ? ' has-danger' : '' }}">
                      <textarea cols="30" rows="15" class="form-control{{ $errors->has('nextmeetingagenda') ? ' is-invalid' : '' }}" name="nextmeetingagenda" id="nextmeetingagenda" type="text" placeholder="{{ __('Student Should Undertake Work Between Now And Next Meeting(Next Meeting Agenda Points') }}" required="true" aria-required="true">{{ $meetinglogifo[0]->next_meeting_agenda }}</textarea>
                      @include('alerts.feedback', ['field' => 'nextmeetingagenda'])
                    </div>
                  </div>
                </div>
				<div class="row">
                  <label class="col-sm-3 col-form-label"><span class="mark">*</span>{{ __('Date Of Next Meeting') }}:</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                      <input type="text" name="nextmeetdate" id="nextmeetdate"
						  placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('nextmeetdate', \Carbon\Carbon::parse($meetinglogifo[0]->next_meeting_date)->format('d-m-Y')) }}"/>
						  @include('alerts.feedback', ['field' => 'nextmeetdate'])
                    </div>
                  </div>
                </div>
              </div>			 
              <div class="card-footer ml-auto mr-auto">				
				@if(count($meetinglogifo) > 0)					
					<input type="hidden" name="meeting_log_seq" id="meeting_log_seq" value="{{ $meetinglogifo[0]->meeting_log_seq }}" />
				@else
					<input type="hidden" name="meeting_log_seq" id="meeting_log_seq" value="0" />
				@endif					
				<input type="hidden" name="item_id" id="item_id" value="{{ $iteminfo[0]->id }}" />
				<input type="hidden" name="log_id" id="log_id" value="{{ $meetinglogifo[0]->id }}" />
				<input type="hidden" name="request_detail_id" id="request_detail_id" value="{{ $iteminfo[0]->request_detail_id }}" />
				<input type="hidden" name="meeting_log_type" id="meeting_log_type" value="{{ $logterm }}" />
				<button type="button" id="postcomment" onclick="funAddMeetingLogInfo()" class="btn btn-success">{{ __('Update Meeting Log') }}</button>
				<a href="{{ route('meetinglogs.index','id='.request()->item_id) }}" class="btn btn-rose">{{ __('Cancel') }}</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
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
		selector: '#lastmilestoneachieved',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
		
	});	
	tinymce.init({
		selector: '#discussthismeeting',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});	
	tinymce.init({
		selector: '#nextmeetingagenda',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});	
  });
  
	function funAddMeetingLogInfo() {	

		meetDate = $("#meeetingdate").val().split('-');
		nextDate = $("#nextmeetdate").val().split('-');
		var curDateVal = new Date(meetDate[2]+"-"+meetDate[1]+"-"+meetDate[0]);
		var nextDateVal= new Date(nextDate[2]+"-"+nextDate[1]+"-"+nextDate[0]);		
		//# time differencre
		var timeDiffer = nextDateVal.getTime() - curDateVal.getTime();		
		
		if($("#meeetingdate").val() == "") {
			swal("", "Please select meeting date!", "error").then((result) => {				
				$("#meeetingdate").focus();				
				return false;
			});	
		}
		else if($('#lastmilestoneachieved').val() == "") {
			swal("", "Please enter work undertaken since last meeting/last milestone achieved!", "error").then((result) => {
				tinymce.EditorManager.get('lastmilestoneachieved').focus();					
				return false;
			});
		}
		else if($('#discussthismeeting').val() == "") {
			swal("", "Please enter issues/progress you would like to discuss in this weeting!", "error").then((result) => {
				tinymce.EditorManager.get('discussthismeeting').focus();				
				return false;
			});
		}
		else if($('#nextmeetingagenda').val() == "") {
			swal("", "Please enter student should undertake work between now and next meeting (Next meeting agenda points)!", "error").then((result) => {
				tinymce.EditorManager.get('nextmeetingagenda').focus();				
				return false;
			});
		}
		else if($("#nextmeetdate").val() == "") {
			swal("", "Please select next meeting date!", "error").then((result) => {				
				$("#nextmeetdate").focus();				
				return false;
			});
		}
		else if(timeDiffer <= 0) {
			swal("", "Next meeting date greater then date of meeting date!", "error").then((result) => {				
				$("#meeetingdate").focus();				
				return false;
			});
		}
		else {
			document.frmMeetingLogs.submit();
		}
	}
</script>
@endpush