@php
if($type == 1){
	$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('View Term - '.$term.' Report Grade '));
	$pagetitle = 'View Term - '.$term.' Report Grade ';
}
else{
	$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('View Term - '.$term.' Presentation Grade '));
	$pagetitle = 'View Term - '.$term.' Presentation Grade ';
}
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
								<div class="col-md-12">
									<table class="table">    
										<tbody>
											@php											
	                    $aStudentInfo = \App\User::select('users.name')->where(['users.id' => $iteminfo[0]->requested_by])->get();
	                    $aSupervisorInfo = \App\User::select('users.name')->where(['users.id' => $iteminfo[0]->assigned_to])->get();	                    
	                    @endphp
						        <tr>
											<td style="text-align: center; font-size: 25px; font-weight:bold;" colspan="4">
												<span class="sid_text">
												@if($type == 1)
													{{__('CHAPTER 1 GRADE') }}
												@else
													{{__('CHAPTER 2 & PRESENTATION GRADE') }}
												@endif
												</span>
											</td>
										</tr>
										<tr>
											<td class="cbold_text">{{ __('Supervisor Name') }}</td>
											@if($type == 1)
												<td class="sid_text" colspan="3">
													@if(count($aSupervisorInfo) > 0)
			                      {{ $aSupervisorInfo[0]->name }}
			                    @endif
			                	</td>
		                	@else
			                	<td class="sid_text">
													@if(count($aSupervisorInfo) > 0)
			                      {{ $aSupervisorInfo[0]->name }}
			                    @endif
			                	</td>
												<td class="cbold_text">{{ __('Date of Presentation') }}:</td>
												<td class="sid_text" style="border-right: solid 1px #ddd;">
													{{ \Carbon\Carbon::parse($templateinfo['presentation_date'])->format('d-m-Y')}}
		                		</td>
	                		@endif
										</tr>
										<tr>
											<td class="cbold_text">{{ __('Thesis Title') }}</td>
											<td class="sid_text" colspan="3" style="text-align: left;">
												{{ $iteminfo[0]->title }}
											</td>
										</tr>
										<tr>
											<td class="cbold_text">{{ __('Student Name') }}</td>
											<td class="sid_text" colspan="3" style="text-align: left;">
												@if(count($aStudentInfo) > 0)
	                      {{ $aStudentInfo[0]->name }}
                    		@endif
                    	</td>
										</tr>		
										<tr>
											<td class="cbold_text" colspan="4" style="text-align: center;">{{ __('(To be completed by each committee member. Please select each evaluation criteria that you feel are appropriate within each attribute category)') }}
											</td>
										</tr>
										<tr style="background-color:#e0e0eb">
											<td class="cbold_text" colspan="4" style="text-align: center; font-size: 21px;">
												@if($type == 1)
													{{ __('CHAPTER 1 GRADE (30% Marks)') }}
												@elseif($type == 3)
													{{ __('FINAL THESIS REPORT GRADE') }}
												@else
													{{ __('CHAPTER 2 GRADE (40% Marks)') }}
												@endif
											</td>
										</tr>	
										<tr style="text-align: center;">
											<td class="cbold_text" style="background-color:#B5DBEC">{{ __('Criteria') }}</td>
											<td class="cbold_text" style="background-color:#F7E2C2">{{ __('Does Not Meet Expectations ') }}<br/>< 70%</td>
											<td class="cbold_text" style="background-color:#EBEFC1">{{ __('Meets Expectations ') }}<br/>70% - 89%</td>
											<td class="cbold_text" style="background-color:#E2EA9C">{{ __('Exceeds Expectations') }}<br/>90% - 100%</td>
										</tr>											
										@if(count($templateinfo) > 0)	
											@php
											$loopseq = 0;
											$vChaperOverllScore = 0;											
											$vPresentationOverllScore = 0;											
											@endphp																								
											@foreach($templateinfo['templateinfo'] as $template)
												@if($loopseq == 4 && $type == 2)												
												<tr style="background-color:#e0e0eb">
													<td class="cbold_text" colspan="4" style="text-align: center; font-size: 21px;">
															{{ __('PRESENTATION RUBRIC (30% Marks)') }}
													</td>
												</tr>	
												<tr style="text-align: center;">
													<td class="cbold_text" style="background-color:#B5DBEC">{{ __('Criteria') }}</td>
													<td class="cbold_text" style="background-color:#F7E2C2">{{ __('Does Not Meet Expectations ') }}<br/>< 70%</td>
													<td class="cbold_text" style="background-color:#EBEFC1">{{ __('Meets Expectations ') }}<br/>70% - 89%</td>
													<td class="cbold_text" style="background-color:#E2EA9C">{{ __('Exceeds Expectations') }}<br/>90% - 100%</td>
												</tr>	
												@endif
												<tr style="text-align: center;" id="{{str_replace(' ','_',strtolower($template->criteria))}}">
													<td class="cbold_text" style="text-align: left;">{!! str_replace(" ","&nbsp;",$template->criteria) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $template->mark_percentage}}%
													<br /><br /><span style="font-weight:normal;">Score&nbsp;Percentage:</span><input type="text" name='txt_{{str_replace(" ","_",strtolower($template->criteria))}}' placeholder="1 to 100" id='txt_{{str_replace(" ","_",strtolower($template->criteria))}}' value="{{$templateinfo["valueinfo"][$loopseq]->criteria_score_percent}}" size="5" class="criteria" onblur="funHighlightRange(this.value,'{{str_replace(" ","_",strtolower($template->criteria))}}')" tabindex="{{$loopseq}}" title="Values between 1 to 100">
													</td>
													<td style="text-align: center; font-size: 18px; {{ ($templateinfo['valueinfo'][$loopseq]->does_not_meet_expectations == 1) ? 'background-color:#f6fca9;' : '' }}">
														{{ $template->does_not_meet_expectations}}														
													</td>
													<td style="text-align: center; font-size: 18px; {{ ($templateinfo["valueinfo"][$loopseq]->meets_expectations == 1) ? 'background-color:#f6fca9;' : '' }}"> 
														{{ $template->meets_expectations}}
													</td>
													<td style="text-align: center; font-size: 18px; {{ ($templateinfo["valueinfo"][$loopseq]->exceeds_expectations == 1) ? 'background-color:#f6fca9;' : '' }}">
														{{ $template->exceeds_expectations}}														
													</td>
												</tr>
												@php
												if($type == 2) {
													if($loopseq < 4){
														$vChaperOverllScore += ($templateinfo['valueinfo'][$loopseq]->criteria_score_percent*$template->mark_percentage)/100;
													}
													else {
														$vPresentationOverllScore += ($templateinfo['valueinfo'][$loopseq]->criteria_score_percent*$template->mark_percentage)/100;
													}																											
												}
												else {
													$vChaperOverllScore += ($templateinfo['valueinfo'][$loopseq]->criteria_score_percent*$template->mark_percentage)/100;
												}
												$loopseq++;												
												@endphp	
											@endforeach
										@endif										
										</tbody>
									</table>									
								</div>								   
							</dir>
							@if($type == 1)
              <div class="row">
                <label class="col-sm-3 col-form-label form_chg">{!! __('Chapter&nbsp;1&nbsp;Overall&nbsp;Score ( 100% )') !!}</label>
                <div class="col-sm-2">
                  <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Percentage') }}" value="{{ $vChaperOverllScore }}" required="true" aria-required="true"/ readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
                <label class="col-sm-3 col-form-label form_chg">{!! __('Aggregate&nbsp;Score( 30% )') !!}</label>
                <div class="col-sm-2">
                  <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Percentage') }}" value="{{ ($vChaperOverllScore*30)/100 }}" required="true" aria-required="true"/ readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
              </div>
              @else
              <div class="row">
                <label class="col-sm-4 col-form-label form_chg">{!! __('Chapter&nbsp;2&nbsp;Overall&nbsp;Score&nbsp;( 100% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Percentage') }}" value="{{ $vChaperOverllScore }}" required="true" aria-required="true"/ readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
                <label class="col-sm-4 col-form-label form_chg">{!! __('Aggregate&nbsp;Score&nbsp;( 40% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Percentage') }}" value="{{ ($vChaperOverllScore*40)/100  }}" required="true" aria-required="true"/ readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-4 col-form-label form_chg">{!! __('Presentation&nbsp;Overall&nbsp;Score&nbsp;( 100% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Percentage') }}" value="{{ $vPresentationOverllScore }}" required="true" aria-required="true"/ readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
                <label class="col-sm-4 col-form-label form_chg">{!! __('Aggregate&nbsp;Score&nbsp;( 30% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Percentage') }}" value="{{ ($vPresentationOverllScore*30)/100  }}" required="true" aria-required="true"/ readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
              </div>
              @endif
							<div class="row">
                <label class="col-sm-12 col-form-label form_chg">
                {{ __('Confidential Comments to Thesis Manager (Mandatory to be filled)') }}</label>                
              </div>
							<div class="row">                
                <label class="col-sm-10 col-form-label form_chg">
                  <div class="form-group view_word  {{ $errors->has('confidential_comments') ? ' has-danger' : '' }}">
                    <textarea name="confidential_comments" id="input-confidential-comments" cols="35" rows="15" class="form-control{{ $errors->has('confidential_comments') ? ' is-invalid' : '' }}" placeholder="{{ __('Confidential Comments (Mandatory to be filled)') }}">{{ $templateinfo['comments'] }}</textarea>
                    @include('alerts.feedback', ['field' => 'confidential_comments'])
                  </div>
                </label>
              </div>
              <div class="row">
                <label class="col-sm-12 col-form-label form_chg">
                {{ __('Feedback & Comments to the Student') }}</label>                
              </div>
							<div class="row">                
                <label class="col-sm-10 col-form-label form_chg">
                  <div class="form-group view_word  {{ $errors->has('students_comments') ? ' has-danger' : '' }}">
                    <textarea name="students_comments" id="input-students_comments" cols="35" rows="15" class="form-control{{ $errors->has('additional_comments_students') ? ' is-invalid' : '' }}" placeholder="{{ __('Feedback and Comments to the Student') }}">{{ $templateinfo['additional_comments_students'] }}</textarea>
                    @include('alerts.feedback', ['field' => 'additional_comments_students'])
                  </div>
                </label>
              </div>              
							<dir class="row">
									<div class="col-md-12 text-center" style="padding:0px;">										
										<button type="button" id="postprogresscomment" onclick="funCancelLog()" class="btn bt_styl btn_txtbold">{{ __('BACK') }}</button>									
										<input type="hidden" name="thesis_id" id="thesis_id" value="{{$iteminfo[0]->id}}">
										<input type="hidden" name="type" id="type" value="{{$type}}">									
										<input type="hidden" name="term" id="term" value="{{$term}}">									
										<input type="hidden" name="action" id="action" value="">			
									</div>
								</dir>
							</div>							
							<div  class="pt-5 text-center">							
								<a href="javascript:void(0)" onclick="funBacktoList()" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
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
  	var vDate = new Date();  	
    $('#presentationdate').datetimepicker({
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
      minDate: vDate
  });
  tinymce.init({
		selector: '#input-confidential-comments',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});	  	
	tinymce.init({
		selector: '#input-students_comments',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});
});

function funRubricSubmmitValidate(){
	var todayDate = new Date();
	dateNow = [todayDate.getMonth() + 1, todayDate.getDate(), todayDate.getFullYear()].join('/');		
	var storePath = '{{$storePath}}';	
	alert(storePath);
	vValidateSuccess = 1;
	if($("#presentationdate").val() == ""){		
		alert("Please select presentation date!")
		$("#presentationdate").focus();		 
		return false;
	}
	else if ($('input:radio[name="problem_statement"]:checked').length == 0){
		alert("Please select problem statement criteria!")
		$("#problem_statement_1").focus();		 
		return false;
	}
	else if($('input:radio[name="problem_solution"]:checked').length == 0){
		alert("Please select problem solution criteria!")
		$("#problem_solution_1").focus();		 
		return false;		
	}
	else if($('input:radio[name="subject_knowledge"]:checked').length == 0){
		alert("Please select subject knowledge criteria!")
		$("#subject_knowledge_1").focus();		 
		return false;	
	}
	else if($('input:radio[name="composition"]:checked').length == 0){
		alert("Please select composition criteria!")
		$("#composition_1").focus();		 
		return false;		
	}
	else if($('input:radio[name="overall_quality_of_presentation"]:checked').length == 0){
		alert("Please select overall quality of presentation criteria!")
		$("#overall_quality_of_presentation_1").focus();		 
		return false;		
	}
	else if($('input:radio[name="overall_breadth_of_knowledge"]:checked').length == 0){
		alert("Please select overall breadth of knowledge criteria!")
		$("#overall_breadth_of_knowledge_1").focus();		 
		return false;				
	}
	else if($('input:radio[name="quality_of_response_to_questions"]:checked').length == 0){
		alert("Please select quality of response to questions criteria!")
		$("#quality_of_response_to_questions_1").focus();		 		
		return false;
	}
	else if($('#overall_percentage').val() == ""){
		alert("Please enter overall percentage value!")
		$("#overall-percentage").focus();		 		
		return false;	
	}
	else if($('#input-confidential-comments').val() == ""){
		alert("Please enter confidential comments!")
		$("#input-confidential-comments").focus();		 		
		return false;		
	}
	else {		
		document.frmCommentUpdate.action=storePath;
		document.frmCommentUpdate.method='POST'
		document.frmCommentUpdate.submit();
	}
}

function funCancelLog() {
	var vType = {{$type}};	
	var rubriccreatedby = {{ $rubriccreatedby ? $rubriccreatedby : 0 }};
	if(vType == 1 || rubriccreatedby > 0){				
		window.location.href = '{{ url("/mythesis/detail?") }}'+{{$iteminfo[0]->id}}+'&tab=4';
	}
	else{
		window.location.href = '{{ url("/mythesis/examine") }}';			
	}
}

function funBacktoList() {
	var vType = {{$type}};
	var rubriccreatedby = {{ $rubriccreatedby ? $rubriccreatedby : 0 }};	
	if(vType == 1 && rubriccreatedby == 0){				
		window.location.href = '{{ url("/mythesis/assigned?") }}';
	}
	else if(rubriccreatedby > 0){
		window.location.href = '{{ url("/item") }}';
	}
	else{
		window.location.href = '{{ url("/mythesis/examine") }}';			
	}
}
</script>
@endpush