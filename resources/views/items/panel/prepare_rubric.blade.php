@php
if($type == 1){
	$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('Prepare Term - '.$term.' Report Grade'));
	$pagetitle = 'Prepare Term - '.$term.' Report Grade ';
}
else{
	$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('Prepare Term - '.$term.' Presentation Grade'));
	$pagetitle = 'Prepare Term - '.$term.' Presentation Grade ';
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
											$aDraftRubricArraySet = array();								
	                    $aStudentInfo = \App\User::select('users.name')->where(['users.id' => $iteminfo[0]->requested_by])->get();
	                    $aSupervisorInfo = \App\User::select('users.name')->where(['users.id' => $iteminfo[0]->assigned_to])->get();	
	                    $aDraftRubricDetails =  \App\ThesisRubricDetails::select('thesis_rubric_details.*')
							->where(['created_by' => auth()->user()->id,'item_id' => $iteminfo[0]->id,'rubric_term' => $term,'rubric_type' => $type,'status' => 0])->get();  
											if(count($aDraftRubricDetails) > 0){
												foreach($aDraftRubricDetails as $draft){
														$aDraftRubricArraySet[$draft->rubric_template_id] = $draft;														
												}
											}
											echo "<pre>";
											//print_r($aDraftRubricArraySet);
											//echo $aDraftRubricArraySet[15]['criteria_score_percent'];
	                    @endphp
						        <tr>
											<td style="text-align: center; font-size: 25px; font-weight:bold;" colspan="4">
												<span class="sid_text">
												@if($type == 1)
													{{__('CHAPTER 1 GRADE') }}
												@elseif($type == 3)
													{{ __('FINAL THESIS REPORT GRADE') }}
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
			                    <input type="hidden"  name="presentationdate" id="presentationdate" value=""/>
			                	</td>
			                @else
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
											<td class="cbold_text" colspan="4" style="text-align: center;">
												@if($type == 1)
													{{ __('To be completed by the supervisor. Please enter marks percentage value for each evaluation criteria that you feel within each attribute category range.') }}
												@else
													{{ __('To be completed by each examiner member. Please enter marks percentage value for each evaluation criteria that you feel within each attribute category range.') }}<br/>{{__('Each examiner member should review chapter 2 and presentation .')}}
												@endif
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
											@if($type == 3)
											<td class="cbold_text" style="background-color:#B5DBEC">{{ __('Report Section') }}</td>
											@else
											<td class="cbold_text" style="background-color:#B5DBEC">{{ __('Criteria') }}</td>
											@endif
											<td class="cbold_text" style="background-color:#F7E2C2">{{ __('Does Not Meet Expectations ') }}<br/>< 70%</td>
											<td class="cbold_text" style="background-color:#EBEFC1">{{ __('Meets Expectations ') }}<br/>70% - 89%</td>
											<td class="cbold_text" style="background-color:#E2EA9C">{{ __('Exceeds Expectations') }}<br/>90% - 100%</td>
										</tr>																					
										@if(count($templateinfo) > 0)	
											@php
											$loopseq = 1;
											@endphp																								
											@foreach($templateinfo as $template)
												@if(($loopseq == 5 && $type == 2) || ($loopseq == 8 && $type == 3))
												<tr style="background-color:#e0e0eb">
													<td class="cbold_text" colspan="4" style="text-align: center; font-size: 21px;">
															{{ __('PRESENTATION GRADE (30% Marks)') }}
													</td>
												</tr>	
												<tr style="text-align: center;">
													<td class="cbold_text" style="background-color:#B5DBEC">{{ __('Criteria') }}</td>
													<td class="cbold_text" style="background-color:#F7E2C2">{{ __('Does Not Meet Expectations ') }}<br/>< 70%</td>
													<td class="cbold_text" style="background-color:#EBEFC1">{{ __('Meets Expectations ') }}<br/>70% - 89%</td>
													<td class="cbold_text" style="background-color:#E2EA9C">{{ __('Exceeds Expectations') }}<br/>90% - 100%</td>
												</tr>	
												@elseif($loopseq == 5 && $type == 3)
													<tr style="background-color:#e0e0eb">
													<td class="cbold_text" colspan="4" style="text-align: center; font-size: 21px;">
															{{ __('OVERALL PROJECT') }}
													</td>
												</tr>	
												<tr style="text-align: center;">
													<td class="cbold_text" style="background-color:#B5DBEC">{{ __('Criteria') }}</td>
													<td class="cbold_text" style="background-color:#F7E2C2">{{ __('Does Not Meet Expectations ') }}<br/>< 70%</td>
													<td class="cbold_text" style="background-color:#EBEFC1">{{ __('Meets Expectations ') }}<br/>70% - 89%</td>
													<td class="cbold_text" style="background-color:#E2EA9C">{{ __('Exceeds Expectations') }}<br/>90% - 100%</td>
												</tr>
												@endif
												@if($type == 3)
												<tr style="text-align: center;" id='div_criteria{{$template->template_id}}'>
													<td class="cbold_text" style="text-align: left; width:250px;">{!! $template->criteria !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $template->mark_percentage}}%
													&nbsp;&nbsp;&nbsp;<br /><br /><span style="font-weight:normal;">Score&nbsp;Percentage:</span><input type="text" name='txt_criteria{{$template->template_id}}' placeholder="1 to 100" id='txt_criteria{{$template->template_id}}' value="{{ !empty($aDraftRubricArraySet) ? (($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] : '') : ''}}" size="8" class="criteria" onblur="funHighlightRange(this.value,'{{$template->template_id}}')" tabindex="{{$loopseq}}" title="Values between 1 to 100" maxlength="3">
													<input type="hidden" name='hid_criteria{{$template->template_id}}' id='hid_criteria{{$template->template_id}}' value="{{ $template->mark_percentage}}">
													<input type="hidden" name='hidcal_criteria{{$template->template_id}}' id='hidcal_criteria{{$template->template_id}}' value="{{ $template->calculation_percentage}}">
													</td>
													<td style="text-align: center; font-size: 18px; {{ !empty($aDraftRubricArraySet) ? (($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] < 70 && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? 'background-color:#f6fca9;' : '') : '' }}" id="div_criteria{{$template->template_id}}_1">
														{!! $template->does_not_meet_expectations !!}
														<div class="form-check text-center">
															<label class="form-input">
				        							  <input name='criteria{{$template->template_id}}[]' class="form-check-input" id="criteria{{$template->template_id}}_1" value="0" type="hidden">
				        							  </span>
				        							</label>
				        						</div>
													</td>
													<td style="text-align: center; font-size: 18px; {{ !empty($aDraftRubricArraySet) ? ((($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 69 && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] < 90) && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? 'background-color:#f6fca9;' : '') : '' }}" id="div_criteria{{$template->template_id}}_2"> 
														{!! $template->meets_expectations !!}
														<div class="form-check text-center">
															<label class="form-input">
				        							  <input name='criteria{{$template->template_id}}[]' class="form-check-input" id="criteria{{$template->template_id}}_2" value="0" type="hidden">
				        							  </span>
				        							</label>
				        						</div>
													</td>
													<td style="text-align: center; font-size: 18px; {{ !empty($aDraftRubricArraySet) ? ((($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 89 && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] < 101) && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? 'background-color:#f6fca9;' : '') : '' }}" id="div_criteria{{$template->template_id}}_3">
														{!! $template->exceeds_expectations !!}
														<div class="form-check text-center">
															<label class="form-input">
				        							  <input name='criteria{{$template->template_id}}[]' class="form-check-input" id="criteria{{$template->template_id}}_3" value="0" type="hidden">
				        							  </span>
				        							</label>
				        						</div>
													</td>
												</tr>
												@else
												<tr style="text-align: center;" id="div_criteria{{$template->template_id}}">
													<td class="cbold_text" style="text-align: left; width:250px;">{!! str_replace(" ","&nbsp;",$template->criteria) !!}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $template->mark_percentage}}%
													&nbsp;&nbsp;&nbsp;<br /><br /><span style="font-weight:normal;">Score&nbsp;Percentage:</span><input type="text" name='txt_criteria{{$template->template_id}}' placeholder="1 to 100" id='txt_criteria{{$template->template_id}}' value="{{ !empty($aDraftRubricArraySet) ? (($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] : '') : ''}}" size="8" class="criteria" onblur="funHighlightRange(this.value,{{$template->template_id}})" tabindex="{{$loopseq}}" title="Values between 1 to 100" maxlength="3">
													<input type="hidden" name='hid_criteria{{$template->template_id}}' id='hid_criteria{{$template->template_id}}' value="{{ $template->mark_percentage}}">
													<input type="hidden" name='hidcal_criteria{{$template->template_id}}' id='hidcal_criteria{{$template->template_id}}' value="{{ $template->calculation_percentage}}">
													</td>
													<td style="text-align: center; font-size: 18px; {{ !empty($aDraftRubricArraySet) ? (($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] < 70 && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? 'background-color:#f6fca9;' : '') : '' }}" id="div_criteria{{$template->template_id}}_1">
														{!! $template->does_not_meet_expectations !!}
														<div class="form-check text-center">
															<label class="form-input">
				        							  <input name='criteria{{$template->template_id}}[]' class="form-check-input" id="criteria{{$template->template_id}}_1" value="0" type="hidden">
				        							  </span>
				        							</label>
				        						</div>
													</td>
													<td style="text-align: center; font-size: 18px; {{ !empty($aDraftRubricArraySet) ? ((($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 69 && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] < 90) && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? 'background-color:#f6fca9;' : '') : '' }}" id="div_criteria{{$template->template_id}}_2"> 
														{!! $template->meets_expectations !!}
														<div class="form-check text-center">
															<label class="form-input">
				        							  <input name='criteria{{$template->template_id}}[]' class="form-check-input" id="criteria{{$template->template_id}}_2" value="0" type="hidden">
				        							  </span>
				        							</label>
				        						</div>
													</td>
													<td style="text-align: center; font-size: 18px; {{ !empty($aDraftRubricArraySet) ? ((($aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 89 && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] < 101) && $aDraftRubricArraySet[$template->template_id]['criteria_score_percent'] > 0) ? 'background-color:#f6fca9;' : '') : '' }}" id="div_criteria{{$template->template_id}}_3">
														{!! $template->exceeds_expectations !!}
														<div class="form-check text-center">
															<label class="form-input">
				        							  <input name='criteria{{$template->template_id}}[]' class="form-check-input" id="criteria{{$template->template_id}}_3" value="0" type="hidden">
				        							  </span>
				        							</label>
				        						</div>
													</td>
												</tr>
												@endif
												@php
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
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Score') }}" value="" title="{{ __('Chapter 1 Overall Score') }}" required="true" aria-required="true" readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
                <label class="col-sm-3 col-form-label form_chg">{!! __('Aggregate&nbsp;Score( 30% )') !!}</label>
                <div class="col-sm-2">
                  <div class="form-group view_word {{ $errors->has('aggregate_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="aggregate_percentage" id="aggregate_percentage" type="text" placeholder="{{ __('Agg. Score') }}" title="{{ __('Aggregate Score') }}" value="" required="true" aria-required="true" readonly>
                    @include('alerts.feedback', ['field' => 'aggregate_percentage'])
                  </div>
                </div>
              </div>
              @else
              <div class="row">
                <label class="col-sm-4 col-form-label form_chg">{!! __('Chapter&nbsp;2&nbsp;Overall&nbsp;Score&nbsp;( 100% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('overall_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="overall_percentage" id="overall_percentage" type="text" placeholder="{{ __('Overall Score') }}" value="" required="true" aria-required="true" readonly>
                    @include('alerts.feedback', ['field' => 'overall_percentage'])
                  </div>
                </div>
                <label class="col-sm-3 col-form-label form_chg">{!! __('Aggregate&nbsp;Score&nbsp;( 40% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('aggregate_percentage') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="aggregate_percentage" id="aggregate_percentage" type="text" placeholder="{{ __('Agg. Score') }}" value="" required="true" aria-required="true" readonly>
                    @include('alerts.feedback', ['field' => 'aggregate_percentage'])
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-4 col-form-label form_chg">{!! __('Presentation&nbsp;Overall&nbsp;Score&nbsp;( 100% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('presentationoverall') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="presentationoverall" id="presentationoverall" type="text" placeholder="{{ __('Overall Score') }}" value="" required="true" aria-required="true"/ readonly>
                    @include('alerts.feedback', ['field' => 'presentationoverall'])
                  </div>
                </div>
                <label class="col-sm-3 col-form-label form_chg">{!! __('Aggregate&nbsp;Score&nbsp;( 30% )') !!}</label>
                <div class="col-sm-1">
                  <div class="form-group view_word {{ $errors->has('presentationaggregate') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="presentationaggregate" id="presentationaggregate" type="text" placeholder="{{ __('Agg. Score') }}" value="" required="true" aria-required="true" readonly>
                    @include('alerts.feedback', ['field' => 'presentationaggregate'])
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
                    <textarea name="confidential_comments" id="input-confidential-comments" cols="35" rows="15" class="form-control{{ $errors->has('confidential_comments') ? ' is-invalid' : '' }}" placeholder="{{ __('Confidential Comments (Mandatory to be filled)') }}">{{ !empty($aDraftRubricArraySet) ? $aDraftRubricArraySet[0]['rubric_comments']  : ''  }}</textarea>
                    @include('alerts.feedback', ['field' => 'confidential_comments'])
                  </div>
                </label>
              </div>
              <div class="row">
                <label class="col-sm-12 col-form-label form_chg">
                {{ __('Feedback & Comments for the Student') }}</label>                
              </div>
							<div class="row">                
                <label class="col-sm-10 col-form-label form_chg">
                  <div class="form-group view_word  {{ $errors->has('students_comments') ? ' has-danger' : '' }}">
                    <textarea name="students_comments" id="input-students_comments" cols="35" rows="15" class="form-control{{ $errors->has('students_comments') ? ' is-invalid' : '' }}" placeholder="{{ __('Feedback and Comments to the Student') }}">{{ !empty($aDraftRubricArraySet) ? $aDraftRubricArraySet[0]['additional_comments_students']  : '' }}</textarea>
                    @include('alerts.feedback', ['field' => 'students_comments'])
                  </div>
                </label>
              </div>
              <div class="row">
									<div class="col-md-12 text-left pl-5 ml-5" style="padding:0px;">
											<span style="color: red; font-size:15px;">* All fields are mandatory fields</span>
									</div>
							</div>
							<dir class="row">
									<div class="col-md-12 text-center" style="padding:0px;">
										<span id="divDisplayError" style="color: red; font-size:15px;">&nbsp;</span>
										<!--<button type="button" id="postprogresscomment" onclick="funAutoSaveRubricValues()" class="btn bt_styl btn_txtbold">{{ __('Save') }}</button> -->
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
  	vDate.setMonth(vDate.getMonth() - 1); 	
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
	$('.criteria').keydown(function (e) {
		if (e.shiftKey || e.ctrlKey || e.altKey) {
			e.preventDefault();
		} else {
		var key = e.keyCode;
			if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57)      || (key >= 96 && key <= 105))) {
				e.preventDefault();
			}
		}
	});
	// To allow value within range between 50 to 100
	$(".criteria").on("change", function() {
	    var val = parseInt(this.value);
	    if(val > 100 || val < 1)
	    {
	        alert('Wrong range');
	        this.value =''; 
	        this.focus();       
	    }
	})	
	setTimeout(function() {
		funCalculateOverallPercentage();
  	funAutoSaveRubricValues();
		}, 3200);
});

function funHighlightRange(pmValue,pmCriteria){	
	$('#div_criteria'+pmCriteria+'_1').css('background-color','');		
	$('#div_criteria'+pmCriteria+'_2').css('background-color','');		
	$('#div_criteria'+pmCriteria+'_3').css('background-color','');		
	if(parseInt(pmValue) < 70){
		$('#div_criteria'+pmCriteria+'_1').css('background-color','#f6fca9');		
		$('#criteria'+pmCriteria+'_1').val(1);
		$('#criteria'+pmCriteria+'_2').val(0);
		$('#criteria'+pmCriteria+'_3').val(0);
		funCalculateOverallPercentage();
	}
	else if(parseInt(pmValue) > 69 && parseInt(pmValue) < 90){
		$('#div_criteria'+pmCriteria+'_2').css('background-color','#f6fca9');		
		$('#criteria'+pmCriteria+'_1').val(0);
		$('#criteria'+pmCriteria+'_2').val(1);
		$('#criteria'+pmCriteria+'_3').val(0);
		funCalculateOverallPercentage();
	}
	else if(parseInt(pmValue) > 89 && parseInt(pmValue) < 101){
		$('#div_criteria'+pmCriteria+'_3').css('background-color','#f6fca9');		
		$('#criteria'+pmCriteria+'_1').val(0);
		$('#criteria'+pmCriteria+'_2').val(0);
		$('#criteria'+pmCriteria+'_3').val(1);
		funCalculateOverallPercentage();
	}	
}

function funCalculateOverallPercentage() {
	var vOverllPercentage = 0;
	var vPresentationPercent = 0;
	var type = {{$type}};
	var ChapterMark = 0;
	var PresentationMark = 0;
	var vLoop = 0;
	$('.criteria').each( function() {
		if(type == 1){
			var txtInputID =($(this).attr('id')).replace('txt_','');			
			var vMark = $("#hid_"+txtInputID).val();
			var vCalPercent = $("#hidcal_"+txtInputID).val();
			if(parseInt(vMark) > 0 && parseInt($(this).val()) > 0){
				vOverllPercentage += (parseInt(vMark)*parseInt($(this).val()))/100;
			}
			ChapterMark = vCalPercent;
		}
		else {
			var txtInputID =($(this).attr('id')).replace('txt_','');			
			var vMark = $("#hid_"+txtInputID).val();
			var vCalPercent = $("#hidcal_"+txtInputID).val();								
			if(type == 3){
				if(parseInt(vMark) > 0 && ($(this).val() != "" && parseInt($(this).val()) > 0) && vLoop < 7){
					vOverllPercentage += (parseInt(vMark)*parseInt($(this).val()))/100;
					ChapterMark = vCalPercent;
				}
				else {
					if(parseInt(vMark) > 0 && ($(this).val() != "" && parseInt($(this).val()) > 0) > 0){
						vPresentationPercent  += (parseInt(vMark)*parseInt($(this).val()))/100;	
						PresentationMark = vCalPercent;
					}								
				}
			}
			else{
				if(parseInt(vMark) > 0 && ($(this).val() != "" && parseInt($(this).val()) > 0) && vLoop < 4){
					vOverllPercentage += (parseInt(vMark)*parseInt($(this).val()))/100;
					ChapterMark = vCalPercent;
				}
				else {
					if(parseInt(vMark) > 0 && ($(this).val() != "" && parseInt($(this).val()) > 0) > 0){
						vPresentationPercent  += (parseInt(vMark)*parseInt($(this).val()))/100;	
						PresentationMark = vCalPercent;
					}								
				}
			}
		}
		vLoop++;	
	})	
	if(type == 1){
		$("#overall_percentage").val(vOverllPercentage.toFixed(2));	
		$("#aggregate_percentage").val(((vOverllPercentage.toFixed(2)*parseInt(ChapterMark))/100).toFixed(2));		
	}
	else {		
		$("#overall_percentage").val(vOverllPercentage.toFixed(2));	
		$("#aggregate_percentage").val(((vOverllPercentage*parseInt(ChapterMark))/100).toFixed(2));	
		$("#presentationoverall").val(vPresentationPercent.toFixed(2));	
		$("#presentationaggregate").val(((vPresentationPercent.toFixed(2)*parseInt(PresentationMark))/100).toFixed(2));
	}
	return;
}

function funRubricSubmmitValidate(){
	$("#divDisplayError").html('');
	var todayDate = new Date();
	var type = {{$type}};
	dateNow = [todayDate.getMonth() + 1, todayDate.getDate(), todayDate.getFullYear()].join('/');		
	var storePath = '{{$storePath}}';		
	vValidateSuccess = 1;
	var vFieldEmpty = 0;
	$("#divDisplayError").html('');
	$('.criteria').each( function() {				
		if($(this).val() == '' || $(this).val() == 0){
			vFieldEmpty = 1;
		}		
	})
	if($("#presentationdate").val() == "" && (type == 2 || type == 3)){							
		swal("", "Please select presentation date!", "error").then((result) => {
		  //$("#presentationdate").focus();
		});		 
		return false;
	}
	else if(vFieldEmpty == 1) {			
			swal("", "Please enter score percentage values for all criteria!", "error").then((result) => {			  
			});	
			return false;
	}
	else if($('#input-confidential-comments').val() == ""){
		swal("", "Please enter confidential comments!", "error").then((result) => {			  
			$("#input-confidential-comments").focus();	
		});	 		
		return false;		
	}
	else if($('#input-students_comments').val() == ""){			
		swal("", "Please enter feedback & comments for the Student!", "error").then((result) => {			  
			$("#input-students_comments").focus();	
		});	 		
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

function funAutoSaveRubricValues() {	
	var storePath = '{{$storePath}}';	
	var formData = $('#frmCommentUpdate').serializeArray();
	formData.push({ name: "_token", value: "{{ csrf_token() }}" });
	formData.push({ name: "autosave", value: 1 });
  $.ajax({
	   type: 'POST',
	   url: storePath,		   
	   data: formData,
	   success: function( msg ) {
				//#---   		
	   		funCalculateOverallPercentage();
	   		setTimeout(function() {
        	funAutoSaveRubricValues();
    		}, 3200);
	   }
  });  
}

function funCancelLog() {
	window.location.href = '{{ url("/mythesis/examine") }}';			
}
</script>
@endpush