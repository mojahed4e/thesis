@php
$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('Meeting Minutes'));
$pagetitle = 'View Meeting Minutes - '.$logseq;
@endphp

@extends('layouts.app',  $header )

@section('content')
<style type="text/css">
table, th, td {
  border: 1px solid #ddd;
  border-collapse: collapse;
}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<form method="post" enctype="multipart/form-data" name="frmCommentUpdate" id="frmCommentUpdate" action="" autocomplete="off" class="form-horizontal">
				@csrf					
				@method('post')
					<div class="card ">
						<div class="card-header">               
			            	<h4 class="card-title view_word">{{ $pagetitle }}</h4>
			            </div>					
						<div class="card-body">
							<dir class="row">
								<div class="col-md-1">&nbsp;</div>								
								<div class="col-md-10">
									<table class="table">    
										<tbody>
											@php											
		                    $aStudentInfo = \App\User::select('users.name')->where(['users.id' => $iteminfo[0]->requested_by])->get();
		                    $aSupervisorInfo = \App\User::select('users.name')->where(['users.id' => $iteminfo[0]->assigned_to])->get();
		                    if(count($progresschecklist) > 0){
		                    	$vStartDate = \Carbon\Carbon::parse($progresschecklist[0]->created_date)->format('m/d/Y');
		                    	$vCompletionDate = \Carbon\Carbon::parse($progresschecklist[0]->completion_date)->format('m/d/Y');
		                    }
		                    @endphp
						        <tr style="background-color:#e5e5e5;">
											<td style="text-align: center;" colspan="4">
												<span class="cbold_text">{{ __('SECTION -1')}}</span><br/><span class="sid_text">
												{{__(' (to be completed by the STUDENT prior to meeting)') }}</span>
											</td>
										</tr>
										<tr>
											<td class="cbold_text" colspan="4" style="text-align: center;">{{ __('Title of the Project:') }}&nbsp;{{ $iteminfo[0]->title }}</td>
										</tr>
										<tr>
											<td class="cbold_text">{{ __('Students\' Name') }}:</td>
											<td class="sid_text" style="border-right: solid 1px #ddd;">
											@if(count($aStudentInfo) > 0)
	                      {{ $aStudentInfo[0]->name }}
                    	@endif
	                	</td>
						        <td class="cbold_text">{{ __('Supervisor Name') }}</td>
										<td class="sid_text">
											@if(count($aSupervisorInfo) > 0)
	                      {{ $aSupervisorInfo[0]->name }}
	                    @endif
	                	</td>
										</tr>																												
										<tr>
											<td class="cbold_text"><span class="mark">*</span>{{ __('Date of meeting') }}:</td>
											<td class="sid_text" style="border-right: solid 1px #ddd;">	
											@if($meetinglogifo[0]->supervisor_approval_status == 0)
												<input type="text"  name="meetingdate" id="meetingdate" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ ($meetinglogifo[0]->meeting_date ? \Carbon\Carbon::parse($meetinglogifo[0]->meeting_date)->format('d-m-Y') : '') }}"/>
												@include('alerts.feedback', ['field' => 'meetingdate'])	
											@else
													{{ ($meetinglogifo[0]->meeting_date ? \Carbon\Carbon::parse($meetinglogifo[0]->meeting_date)->format('d-m-Y') : '') }}								
											@endif

						          </td>
						          <td class="cbold_text">{{ __('Date of previous meeting') }}</td>
											<td class="sid_text">

											@if(count($previousmeetinglogifo) > 0)
												@if($previousmeetinglogifo[0]->supervisor_approval_status < 2)
                      		{{\Carbon\Carbon::parse($previousmeetinglogifo[0]->next_meeting_date)->format('d-m-Y')}}
                      	@elseif($previousmeetinglogifo[0]->supervisor_approval_status == 2)
                      		{{\Carbon\Carbon::parse($previousmeetinglogifo[0]->modified_date)->format('d-m-Y')}}
                      	@else
                      		{{ __('N/A') }}
                      	@endif
	                    @else
	                      	{{ __('N/A') }}						                      	
                  		@endif
		                	</td>
										</tr>									
										@if($progresschecklist[0]->student_upload_status >= 2 && $logseq <= 5)
										<tr>
											<td class="cbold_text"><span class="mark">*</span>{{ __('Next meeting date') }}</td>
											<td class="sid_text">
												{{ ($meetinglogifo[0]->next_meeting_date ? \Carbon\Carbon::parse($meetinglogifo[0]->next_meeting_date)->format('d-m-Y') : '') }} 
											</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										@endif
										<tr>
											<td class="cbold_text" colspan="4"> 
			                  <label class="col-form-label cbold_text" style="padding:0px;"><span class="mark">*</span>{{ __('Work undertaken since last meeting/ Last milestone achieved:') }}</label>
			                    <span class="form-group view_word  {{ $errors->has('workundertaken') ? ' has-danger' : '' }}">
			                    @if(count($meetinglogifo) > 0)
			                      <textarea name="milestone_achived_last_meeting" id="input-workundertaken" cols="35" rows="15" class="form-control{{ $errors->has('workundertaken') ? ' is-invalid' : '' }}" placeholder="{{ __('Work undertaken since last meeting/ Last milestone achieved') }}" value="{{ old('workundertaken',($meetinglogifo[0]->milestone_achived_last_meeting ? $meetinglogifo[0]->milestone_achived_last_meeting : '')) }}">{{ old('workundertaken',($meetinglogifo[0]->milestone_achived_last_meeting ? $meetinglogifo[0]->milestone_achived_last_meeting : '')) }}</textarea>
			                      @include('alerts.feedback', ['field' => 'workundertaken'])</span>
			                    @else
			                    	<textarea name="milestone_achived_last_meeting" id="input-workundertaken" cols="35" rows="15" class="form-control{{ $errors->has('workundertaken') ? ' is-invalid' : '' }}" placeholder="{{ __('Work undertaken since last meeting/ Last milestone achieved') }}" value="{{ old('workundertaken') }}">{{ old('workundertaken') }}</textarea>
			                      @include('alerts.feedback', ['field' => 'workundertaken'])</span>
			                    @endif
            						</td>
										</tr>		
										<tr>
											<td class="cbold_text" colspan="4"> 
			                  <label class="col-form-label cbold_text" style="padding:0px;"><span class="mark">*</span>{{ __('Issues/progress you would like to discuss in this meeting:') }}</label>
			                    <span class="form-group view_word  {{ $errors->has('issuesprogress') ? ' has-danger' : '' }}">
			                    @if(count($meetinglogifo) > 0)
		                    	 <textarea name="issuesprogress" id="input-issuesprogress" cols="35" rows="15" class="form-control{{ $errors->has('issuesprogress') ? ' is-invalid' : '' }}" placeholder="{{ __('Issues/progress you would like to discuss in this meeting') }}" value="{{ old('issuesprogress') }}">{{ old('issuesprogress',($meetinglogifo[0]->discussed_this_meeting ? $meetinglogifo[0]->discussed_this_meeting : '')) }}</textarea>
		                      	 @include('alerts.feedback', ['field' => 'issuesprogress'])</span>
			                    @else
			                      <textarea name="issuesprogress" id="input-issuesprogress" cols="35" rows="15" class="form-control{{ $errors->has('issuesprogress') ? ' is-invalid' : '' }}" placeholder="{{ __('Issues/progress you would like to discuss in this meeting') }}" value="{{ old('issuesprogress') }}">{{ old('issuesprogress') }}</textarea>
			                      @include('alerts.feedback', ['field' => 'issuesprogress'])</span>
			                    @endif
            						</td>
										</tr>
										@if($progresschecklist[0]->student_upload_status >= 2 && $logseq < 5)
										<tr style="background-color:#e5e5e5;">
											<td style="text-align: center;" colspan="4">
											<span class="cbold_text">{{ __('SECTION -2')}}</span><br/><span class="sid_text">
											{{__(' (to be completed by the SUPERVISOR at the time of meeting)') }}</span>
											</td>
										</tr>
										@if($action_type == 1 && count($previousmeetinglogifo) > 0)
											@if(($previousmeetinglogifo[0]->meeting_log_type == 1 &&  $logseq > 1) || $previousmeetinglogifo[0]->meeting_log_type == 2)
											<tr>
												<td colspan="4"> 
				                  <label class="col-form-label cbold_text pb-3" style="padding:0px;">{{ __('Last meeting agenda:') }}</label>
				                    <span class="form-sid_text">				                  
				                    {!! $previousmeetinglogifo[0]->next_meeting_agenda !!}
				                  	</span>
	            						</td>
											</tr>
											@endif	
										@endif
										<tr>
											<td class="cbold_text" colspan="4"> 
			                  <label class="col-form-label cbold_text" style="padding:0px;"><span class="mark">*</span>{{ __('Next meeting agenda:') }}</label>
			                    <span class="form-group view_word  {{ $errors->has('nextmeetingagenda') ? ' has-danger' : '' }}">
			                    <textarea name="nextmeetingagenda" id="input-nextmeetingagenda" cols="35" rows="15" class="form-control{{ $errors->has('nextmeetingagenda') ? ' is-invalid' : '' }}" placeholder="{{ __('Next meeting agenta') }}" value="{{ old('nextmeetingagenda',($meetinglogifo[0]->next_meeting_agenda ? $meetinglogifo[0]->next_meeting_agenda : '')) }}">{{ old('nextmeetingagenda',($meetinglogifo[0]->next_meeting_agenda ? $meetinglogifo[0]->next_meeting_agenda : '')) }}</textarea>
		                      @include('alerts.feedback', ['field' => 'nextmeetingagenda'])</span>
            						</td>
										</tr>
										@else
										<tr style="background-color:#e5e5e5;">
											<td style="text-align: center;" colspan="4">
											<span class="cbold_text">{{ __('SECTION -2')}}</span><br/><span class="sid_text">
											{{__(' (to be completed by the SUPERVISOR at the time of meeting)') }}</span>
											</td>
										</tr>
										<tr>
											<td class="cbold_text" colspan="4"> 
			                  <label class="col-form-label cbold_text" style="padding:0px;">{{ __('Next meeting agenda:') }}</label>
			                    <span class="form-group view_word  {{ $errors->has('nextmeetingagenda') ? ' has-danger' : '' }}">
			                    <textarea name="nextmeetingagenda" id="input-nextmeetingagenda" cols="35" rows="15" class="form-control{{ $errors->has('nextmeetingagenda') ? ' is-invalid' : '' }}" placeholder="{{ __('Next meeting agenta') }}" value="{{ old('nextmeetingagenda','') }}" readonly>{{ old('nextmeetingagenda', ($meetinglogifo[0]->next_meeting_agenda ? $meetinglogifo[0]->next_meeting_agenda : '')) }}</textarea>
		                      @include('alerts.feedback', ['field' => 'nextmeetingagenda'])</span>
            						</td>
										</tr>		
										@endif		
										</tbody>
									</table>
								</div>
								<div class="col-md-1">&nbsp;</div>   
							</dir>
							<div class="card-body" style="padding:0px;">
								<dir class="row">
									<div class="col-md-11 text-center" style="padding:0px;">
											<button type="button" id="postprogresscomment" onclick="funCancelLog()" class="btn bt_styl btn_txtbold">{{ __('Back to Progress Tracking') }}</button>
										<input type="hidden" name="approve_flag" id="approve_flag" value="0">
										<input type="hidden" name="thesis_id" id="thesis_id" value="{{$iteminfo[0]->id}}">
										<input type="hidden" name="meeting_log_seq" id="meeting_log_seq" value="{{$logseq}}">
										<input type="hidden" name="meeting_log_id" id="meeting_log_id" value="{{$meetinglogifo[0]->id}}">
										<input type="hidden" name="meeting_log_type" id="meeting_log_type" value="{{$logterm}}">
										<input type="hidden" name="request_detail_id" id="request_detail_id" value="{{$iteminfo[0]->request_detail_id}}">
									</div>
								</dir>
								<div class="col-md-1">&nbsp;</div> 
							</div>
							<div  class="pt-5 text-center">
							@if(request()->get('action') == 'ac')
								<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
							@else
								<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
							@endif
							</div>
						</div>
					</div>			
				</div>			
			</form>
		</div>
	</div>
</div>
@endsection

@push('js')
<script src="{{ asset('material') }}/uploader/jquery.uploadfile.min.js"></script>
<script src="{{ asset('material') }}/wnumb/wNumb.min.js"></script>
<script>
  $(document).ready(function() {
  	var vMinutesStat = {{$progresschecklist[0]->student_upload_status}};
  	var startDate = '{{$vStartDate}}';
  	var endDate = '{{$vCompletionDate}}';
  	var vDate = new Date();  	
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
      format: 'DD-MM-YYYY',
      minDate: startDate,      
      maxDate: endDate
  });
  $('#nextmeetingdate').datetimepicker({
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
      format: 'DD-MM-YYYY',
      minDate: vDate.setDate(vDate.getDate() + 2)      
    });
	tinymce.init({
		selector: '#input-workundertaken',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});
	tinymce.init({
		selector: '#input-issuesprogress',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});
	tinymce.init({
		selector: '#input-nextmeetingagenda',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
			if(parseInt(vMinutesStat) < 2){
				editor.settings.readonly = true
			}
		}
	});
});

function funLogSubmmitValidate(pmValue){
	var todayDate = new Date();
	dateNow = [todayDate.getMonth() + 1, todayDate.getDate(), todayDate.getFullYear()].join('/');		
	var storePath = '{{$storePath}}';	
	if(pmValue == 1) {
		var meetingdate = $('#meetingdate').val().split('-');
		var meetSchedule = [meetingdate[1],meetingdate[0],meetingdate[2]].join('/');
		if($("#meetingdate").val() == ""){
			swal("", "Please select meeting date!", "error").then((result) => {
			  $("#meetingdate").focus();
			});
			return false;
		}
		else if (Date.parse(meetSchedule) < Date.parse(dateNow)){
				swal("", "Please modify the completion deadline with Manager and reschedule the meeting date!", "error").then((result) => {
			 $("#meetingdate").focus();
			});
			return false;
		}
		else if($("#input-workundertaken").val() == ""){
			swal("", "Work undertaken since last meeting/ last milestone achieved!", "error").then((result) => {
			  tinymce.EditorManager.get('input-workundertaken').focus();
			});
			return false;
		}
		else if($("#input-issuesprogress").val() == ""){
			swal("", "Please enter issues/progress you would like to discuss in this meeting!", "error").then((result) => {
			  tinymce.EditorManager.get('input-issuesprogress').focus();
			});
			return false;
		}
		else {
			$("#approve_flag").val(pmValue);
			document.frmCommentUpdate.action=storePath;
			document.frmCommentUpdate.method='POST'
			document.frmCommentUpdate.submit();
		}
	}
	else {		
		if($("#nextmeetingdate").val() == ""){
			swal("", "Please select next meeting date!", "error").then((result) => {
			  $("#nextmeetingdate").focus();
			});
			return false;
		}		
		else if($("#input-workundertaken").val() == ""){
			swal("", "Work undertaken since last meeting/ last milestone achieved!", "error").then((result) => {
			  tinymce.EditorManager.get('input-workundertaken').focus();
			});
			return false;
		}
		else if($("#input-issuesprogress").val() == ""){
			swal("", "Please enter issues/progress you would like to discuss in this meeting!", "error").then((result) => {
			  tinymce.EditorManager.get('input-issuesprogress').focus();
			});
			return false;
		}
		else if($("#input-nextmeetingagenda").val() == ""){
			swal("", "Please enter next meeting agenda!", "error").then((result) => {
			  tinymce.EditorManager.get('input-nextmeetingagenda').focus();
			});
			return false;
		}
		else {			
			$("#approve_flag").val(pmValue);
			document.frmCommentUpdate.action=storePath;
			document.frmCommentUpdate.method='POST'
			document.frmCommentUpdate.submit();
		}
	}
}

function funCancelLog() {
	var vRoleId = {{auth()->user()->role_id}};
	var action = "&action={{request()->get('action')}}";
	if(vRoleId == 2)
		window.location.href = '{{ url("/mythesis/detail?") }}'+{{$iteminfo[0]->id}}+'&tab=4'+action;	
	else
		window.location.href = '{{ url("/mythesis/detail?") }}'+{{$iteminfo[0]->id}}+'&tab=4';			
}
</script>
@endpush