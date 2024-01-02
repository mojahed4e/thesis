@php
if($type == 1){
	$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('Prepare Term - '.$term.' Report Rubric'));
	$pagetitle = 'Prepare Term - '.$term.' Report Rubric ';
}
else{
	$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('Prepare Term - '.$term.' Presentation Rubric'));
	$pagetitle = 'Prepare Term - '.$term.' Presentation Rubric ';
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
													{{__('PROJECT REPORT RUBRIC') }}
												@else
													{{__('PROJECT PRESENTATION RUBRIC') }}
												@endif
												</span>
											</td>
										</tr>
										<tr>
											<td class="cbold_text">{{ __('Supervisor Name') }}</td>
										<td class="sid_text">
											@if(count($aSupervisorInfo) > 0)
	                      {{ $aSupervisorInfo[0]->name }}
	                    @endif
	                	</td>
											<td class="cbold_text">{{ __('Date of Presentation') }}:</td>
											<td class="sid_text" style="border-right: solid 1px #ddd;">
												<input type="text"  name="presentationdate" id="presentationdate" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value=""/>
												<span id="error-presentationdate" style="color: red !important;"></span>
	                		</td>
										</tr>
										<tr>
											<td class="cbold_text">{{ __('Thesis Title') }}</td>
											<td class="sid_text" colspan="3" style="text-align: left;">
												{{ $iteminfo[0]->title }}
											</td>
										</tr>
										<tr>
											<td class="cbold_text">{{ __('Student Name') }}</td>
											<td class="sid_text" colspan="4" style="text-align: left;">
												@if(count($aStudentInfo) > 0)
	                      {{ $aStudentInfo[0]->name }}
                    		@endif
                    	</td>
										</tr>		
										<tr>
											<td class="cbold_text" colspan="4" style="text-align: center;">{{ __('(To be completed by each committee member. Please select each evaluation criteria that you feel are appropriate within each attribute category)') }}
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
											$loopseq = 1;
											@endphp																								
											@foreach($templateinfo as $template)
												@if($loopseq == 5)
												<tr>
													<td class="cbold_text" colspan="4" style="text-align: center; font-size: 21px;">{{ __('Presentation') }}
													</td>
												</tr>
												@endif
												<tr style="text-align: center;" id="{{str_replace(' ','_',strtolower($template->criteria))}}">
													<td class="cbold_text" style="text-align: left;">{!! str_replace(" ","&nbsp;",$template->criteria) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $template->mark_percentage}}%
													&nbsp;&nbsp;&nbsp;Score&nbsp;Percentage:<input type="text" name='txt_{{str_replace(" ","_",strtolower($template->criteria))}}' id='txt_{{str_replace(" ","_",strtolower($template->criteria))}}' value="">
													</td>
													<td style="text-align: center; font-size: 18px;" id="div{{str_replace(' ','_',strtolower($template->criteria))}}_1">
														{{ $template->does_not_meet_expectations}}
														<div class="form-check text-center">
															<label class="form-check-label">
				        							  <input name='{{str_replace(" ","_",strtolower($template->criteria))}}' class="form-check-input" id="{{str_replace(' ','_',strtolower($template->criteria))}}_1" value="0" type="hidden">
				        							  <span class="circle">
				        								<span class="check"></span>
				        							  </span>
				        							</label>
				        						</div>
													</td>
													<td style="text-align: center; font-size: 18px;" id="div{{str_replace(' ','_',strtolower($template->criteria))}}_2"> 
														{{ $template->meets_expectations}}
														<div class="form-check text-center">
															<label class="form-check-label">
				        							  <input name='{{str_replace(" ","_",strtolower($template->criteria))}}' class="form-check-input" id="{{str_replace(' ','_',strtolower($template->criteria))}}_2" value="0" type="hidden">
				        							  <span class="circle">
				        								<span class="check"></span>
				        							  </span>
				        							</label>
				        						</div>
													</td>
													<td style="text-align: center; font-size: 18px;" id="div{{str_replace(' ','_',strtolower($template->criteria))}}_3">
														{{ $template->exceeds_expectations}}
														<div class="form-check text-center">
															<label class="form-check-label">
				        							  <input name='{{str_replace(" ","_",strtolower($template->criteria))}}' class="form-check-input" id="{{str_replace(' ','_',strtolower($template->criteria))}}_3" value="0" type="hidden">
				        							  <span class="circle">
				        								<span class="check"></span>
				        							  </span>
				        							</label>
				        						</div>
													</td>
												</tr>
												@php
												$loopseq++;												
												@endphp	
											@endforeach
										@endif										
										</tbody>
									</table>									
								</div>								   
							</dir>
							 <div class="row">
                  <label class="col-sm-3 col-form-label form_chg">{!! __('Overall&nbsp;Percentage') !!}</label>
                  <div class="col-sm-4">
                    <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Percentage') }}" value="{{ old('overall_percentage', '') }}" required="true" aria-required="true"/>
                      @include('alerts.feedback', ['field' => 'overall_percentage'])
                    </div>
                  </div>
                </div>
							<div class="row">
                <label class="col-sm-12 col-form-label form_chg">
                {{ __('Confidential Comments (Mandatory to be filled)') }}</label>                
              </div>
							<div class="row">                
                <label class="col-sm-10 col-form-label form_chg">
                  <div class="form-group view_word  {{ $errors->has('confidential_comments') ? ' has-danger' : '' }}">
                    <textarea name="confidential_comments" id="input-confidential-comments" cols="35" rows="15" class="form-control{{ $errors->has('confidential_comments') ? ' is-invalid' : '' }}" placeholder="{{ __('Confidential Comments (Mandatory to be filled)') }}">{{ old('confidential_comments','') }}</textarea>
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
                    <textarea name="students_comments" id="input-students_comments" cols="35" rows="15" class="form-control{{ $errors->has('students_comments') ? ' is-invalid' : '' }}" placeholder="{{ __('Feedback and Comments to the Student') }}">{{ old('students_comments','') }}</textarea>
                    @include('alerts.feedback', ['field' => 'students_comments'])
                  </div>
                </label>
              </div>
              <div class="row">
									<div class="col-md-12 text-left pl-5 ml-5" style="padding:0px;">
											<span style="color: red; font-size:15px;">* All fiedls are mandatory fields</span>
									</div>
							</div>
							<dir class="row">
									<div class="col-md-12 text-center" style="padding:0px;">
										<button type="button" id="postprogresscomment" onclick="funRubricSubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
										<button type="button" id="postprogresscomment" onclick="funCancelLog()" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</button>									
										<input type="hidden" name="thesis_id" id="thesis_id" value="{{$iteminfo[0]->id}}">
										<input type="hidden" name="rubrictype" id="rubrictype" value="{{$type}}">									
										<input type="hidden" name="rubricterm" id="rubricterm" value="{{$term}}">									
									</div>
								</dir>
							</div>							
							<div  class="pt-5 text-center">							
								<a href="{{ route('mythesis.examine') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
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
});

function funRubricSubmmitValidate(){
	var todayDate = new Date();
	dateNow = [todayDate.getMonth() + 1, todayDate.getDate(), todayDate.getFullYear()].join('/');		
	var storePath = '{{$storePath}}';		
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
		swal({
			  title: 'Are you sure you want to submit the grade information',
			  text: "You won't be able to revert this back!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#47a44b',
			  cancelButtonColor: '#ea2c6d',			  
			  confirmButtonText: 'Yes, Submit it!'
			}).then((result) => {
			  if (result.value) {
					swal({
					  title: 'Submitted!',
					  text: 'Your have successfully generated the rubric.',
					  type: 'success',
					  confirmButtonColor: '#47a44b'
					}).then ((result) =>{
						document.frmCommentUpdate.action=storePath;
						document.frmCommentUpdate.method='POST'
						document.frmCommentUpdate.submit();
					})
			  }
			})
	}
}

function funCancelLog() {
	window.location.href = '{{ url("/mythesis/examine") }}';			
}
</script>
@endpush