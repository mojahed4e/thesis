<div class="card-body">
	<!-- Supervisor:<br />
	1. Final document submission option<br />
	2. Final Presentation document submission<br />
	3. Meeting Minutes Submission<br />
	4. Proposal Rubric Submission<br />
	Coordinator:<br />
	1. Review and Approve<br />
	2. Final report rubric submission<br />
	3. Final presentation rubric <br /> -->


	@php
		$vOthersFound = 0;
		$vEnableManager = 0;
		$vShow3Date = 0;
		$vStudentCompleted = 1;
		$vProposalFileEnable = 0;
		$vPanelRubricCount = 0;
		$aSupervisorScore = 0;
		$aProgramInfo = \App\Program::where(['programs.id' => $item->program_id])->get();		
		$aMeetingLogArray = array();
		if(count($meetinglogsterm2) > 0){
			foreach($meetinglogsterm2 as $meetinglogt2){
				$aMeetingLogArray[$meetinglogt2->meeting_log_seq] = $meetinglogt2;
			}
		}
		$aTemplateInfo = \App\ThesisRubricTemplate::Status()->get();
		$aTemplateMarkPercent = array();
		if(count($aTemplateInfo) > 0){
			foreach($aTemplateInfo as $template){
				$aTemplateMarkPercent[$template->template_id] = $template->mark_percentage;
			}	
		}
	@endphp						
	@if(count($term2progressdetails) > 0)							
		@foreach ($term2progressdetails as $term2progress)				
			@if(auth()->user()->role_id == 2 && ($term2progress->upload_file_status > 0 || $term2progress->approval_status > 0 ))
				@php
					$vEnableManager = 1;
				@endphp
				@if($vShow3Date == 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Term - II Completion Date') }}:</p>
						</div>						  	 
						<div class="col-8 text-left cht_text">
							<label class="custom-file-upload">
								{{($timelineinfo[0]->term2_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term2_completion)->format('d-m-Y') : '')}}
							</label>
						</div>												
					</div>
					@php
						$vShow3Date = 1;
					@endphp
				@endif				
				@if($term2progress->document_type == "presentationfile")
					@if(!empty($term2progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Final Version Presentation') }}:</p>
							</div>	
							@if($term2progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term2progress->approval_status != 1))
								<div class="col-8 text-left">
									<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="presentationfile" id="presentationfile">
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										@if($term2progress->student_upload_status != 2)
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label">
											  <input name="presentationfile_file_approve" class="form-check-input" id="presentationfile_file_approve" value="1" type="checkbox">&nbsp;
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
											<button type="button" id="fileApprove" onclick="funApproveFileSubmission('presentationfile',{{$term2progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
										</div>
										@else
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label" style="cursor: default;">
											  <input name="presentationfile_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="presentationfile_file_approve" value="1" type="checkbox">Approved
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>												
										</div>	
										@endif
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">								
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
						</div>
					@else
						<div class="row">
							<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Final Version Presentation') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="presentationfile" id="presentationfile" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
								</div>
							</div>												
						</div>
					@endif
				@endif
				@if($term2progress->document_type == "chapter2report")
					@if(!empty($term2progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Thesis Final Report') }}:</p>
							</div>	
							@if($term2progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term2progress->approval_status != 1))
								<div class="col-8 text-left">
									<input type="hidden" name="chapter2report_flag" id="chapter2report_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="chapter2report" id="chapter2report">
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										@if($term2progress->student_upload_status != 2)
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label">
											  <input name="chapter2report_file_approve" class="form-check-input" id="chapter2report_file_approve" value="1" type="checkbox">&nbsp;
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
											<button type="button" id="fileApprove" onclick="funApproveFileSubmission('chapter2report',{{$term2progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : "--") }}&nbsp;]
										</div>
										@else
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label" style="cursor: default;">
											  <input name="chapter2report_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="chapter2report_file_approve" value="1" type="checkbox">Approved
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
										</div>
										@php
										$vProposalFileEnable++;
										@endphp	
										@endif
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
						</div>
					@else
						<div class="row">
							<input type="hidden" name="chapter2report_flag" id="chapter2report_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Thesis Final Report') }}:</p>
							</div>
							@if($vProposalFileEnable == 6)						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="chapter2report" id="chapter2report" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : "--") }}&nbsp;]
									</div>
								</div>
							@else
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
										{{ __('Pending') }}
										@if(!empty($aMeetingLogArray[5]))
											@if($aMeetingLogArray[5]->supervisor_approval_status < 2)
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : "--") }}&nbsp;]
											@endif
										@else
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : "--") }}&nbsp;]
										@endif
									</div>
								</div>
							@endif												
						</div>
					@endif
				@endif					
				@if($term2progress->document_type == "minutes1")							
					@if($term2progress->student_upload_status > 0 && !empty($aMeetingLogArray[1]))
						<div class="row pt-2">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-1">
								<p>{{ __('Meeting Minutes - 1') }}:</p>
							</div>	
							@if($term2progress->student_upload_status == 2 || $term2progress->approval_status >= 0  && $term2progress->sequence = 3)
								<div class="col-8 text-left">
									<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
										@if(count($aMeetingLogArray) > 0)
											@if(!empty($aMeetingLogArray[1]))
												@php
												$vMetLogSeq = 1;
												@endphp
												<div class="form-check" style="float: right; vertical-align: middle;">
													<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,1)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
												</div>
											@endif
										@endif											
									</div>
								</div>
							@else								
								@if($term2progress->student_upload_status == 1)
									{{__('In Progress')}}	
								@else
									{{__('Pending')}}
								@endif									
							@endif
						</div>
					@else
						<div class="row">
							<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 1') }}:</p>
							</div>						  	 
							<div class="col-8 text-left pt-2">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									{{__("In Progress")}}
								</div>
							</div>												
						</div>
					@endif	
				@endif
				@if($term2progress->document_type == "minutes2")
					@if($term2progress->student_upload_status > 0 && !empty($aMeetingLogArray[2]))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 2') }}:</p>
							</div>	
							@if($term2progress->student_upload_status == 2  && $term2progress->approval_status >= 0 && $term2progress->sequence = 4)
								<div class="col-8 text-left">
									<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
										@if(count($aMeetingLogArray) > 0)
											@if(!empty($aMeetingLogArray[2]))			
												@php
												$vMetLogSeq = 2;
												@endphp
												<div class="form-check" style="float: right; vertical-align: middle;">
													<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,2)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
												</div>
											@endif
										@endif											
									</div>
								</div>
							@else
								@if($term2progress->student_upload_status == 1)
									{{__('In Progress')}}	
								@else
									{{__('Pending')}}
								@endif							
							@endif
						</div>
					@else
						<div class="row">
							<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 2') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									@if($vMetLogSeq == 2)
										{{__('In Progress')}}
									@else
										{{__('Pending')}}
									@endif
								</div>
							</div>												
						</div>
					@endif							
				@endif
				@if($term2progress->document_type == "minutes3")
					@if($term2progress->student_upload_status > 0 && !empty($aMeetingLogArray[3]))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 3') }}:</p>
							</div>	
							@if($term2progress->student_upload_status == 2  || $term2progress->approval_status >= 0 && $term2progress->sequence = 5)
								<div class="col-8 text-left">
									<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
										@if(count($aMeetingLogArray) > 0)
											@if(!empty($aMeetingLogArray[3]))			
												@php
												$vMetLogSeq = 3;
												@endphp
												<div class="form-check" style="float: right; vertical-align: middle;">
													<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,3)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
												</div>
											@endif
										@endif											
									</div>
								</div>
							@else
								@if($term2progress->student_upload_status == 1)
									{{__('In Progress')}}	
								@else
									{{__('Pending')}}
								@endif								
							@endif
						</div>
					@else
						<div class="row">
							<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 3') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									@if($vMetLogSeq == 2)
										{{__('In Progress')}}
									@else
										{{__('Pending')}}
									@endif
								</div>
							</div>												
						</div>
					@endif	
				@endif
				@if($term2progress->document_type == "minutes4")						
					@if($term2progress->student_upload_status > 0 && !empty($aMeetingLogArray[4]))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 4') }}:</p>
							</div>	
							@if($term2progress->student_upload_status == 2  || $term2progress->approval_status >= 0 && $term2progress->sequence == 4)
								<div class="col-8 text-left">
									<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
										@if(count($aMeetingLogArray) > 0)
											@if(!empty($aMeetingLogArray[4]))		
												@php
												$vMetLogSeq = 4;
												@endphp
												<div class="form-check" style="float: right; vertical-align: middle;">
													<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,4)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
												</div>
											@endif
										@endif											
									</div>
								</div>
							@else
								@if($term2progress->student_upload_status == 1)
									{{__('In Progress')}}	
								@else
									{{__('Pending')}}
								@endif								
							@endif
						</div>
					@else
						<div class="row">
							<input type="hidden" name="minutes4_flag" id="minutes4_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 4') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									@if($vMetLogSeq == 3)
										{{__('In Progress')}}
									@else
										{{__('Pending')}}
									@endif
								</div>
							</div>												
						</div>
					@endif
				@endif
				@if($term2progress->document_type == "minutes5")
					@if($term2progress->student_upload_status > 0 && !empty($aMeetingLogArray[5]))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 5') }}:</p>
							</div>	
							@if($term2progress->student_upload_status == 2  || $term2progress->approval_status >= 0 && $term2progress->sequence == 7)
								<div class="col-8 text-left">
									<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
										@if(count($aMeetingLogArray) > 0)
											@if(!empty($aMeetingLogArray[5]))		
												@php
												$vMetLogSeq = 5;
												@endphp
												<div class="form-check" style="float: right; vertical-align: middle;">
													<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,5)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
												</div>
											@endif
										@endif											
									</div>
								</div>
							@else
								@if($term2progress->student_upload_status == 1)
									{{__('In Progress')}}	
								@else
									{{__('Pending')}}
								@endif											
							@endif
						</div>
					@else
						<div class="row">
							<input type="hidden" name="minutes5_flag" id="minutes5_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 5') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									@if($vMetLogSeq == 4)
										{{__('In Progress')}}
									@else
										{{__('Pending')}}
									@endif
								</div>
							</div>												
						</div>
					@endif
				@endif				
			@endif
		@endforeach
		@if(count($panelmembers) > 0 && $term2progressdetails[0]->approval_status > 0)
			@for($memloop = 0; $memloop < count($panelmembers); $memloop++)
			@php
			$aPanelRubricInfo = \App\ThesisRubricDetails::where(['item_id' => $item->id,'rubric_term' => 2, 'rubric_type' => 3,'created_by' => $panelmembers[$memloop]->id,'status' => 1])->get();			
			$aChapIIRubricInfo = \App\ThesisRubricDetails::where(['item_id' => $item->id,'rubric_term' => 2, 'rubric_type' => 3,'created_by' => $panelmembers[$memloop]->id,'status' => 1])->get();
			$vChaper2Score = 0;
			$vPresentationScore = 0;				
			@endphp
			@if(count($aChapIIRubricInfo) > 0)
				@php
				$vPanelRubricCount++;
				@endphp
				@for($chap2_loop = 0; $chap2_loop < count($aChapIIRubricInfo); $chap2_loop++)
					@php
					if($aChapIIRubricInfo[$chap2_loop]->rubric_template_id > 0) {
						if($chap2_loop < 7){
							$vChaper2Score += ($aChapIIRubricInfo[$chap2_loop]->criteria_score_percent*$aTemplateMarkPercent[$aChapIIRubricInfo[$chap2_loop]->rubric_template_id])/100;
						}
						else {
							$vPresentationScore += ($aChapIIRubricInfo[$chap2_loop]->criteria_score_percent*$aTemplateMarkPercent[$aChapIIRubricInfo[$chap2_loop]->rubric_template_id])/100;
						}
					}						
					@endphp
				@endfor
				@php
				$aExaminerScore[$panelmembers[$memloop]->id]['chapter2'] = $vChaper2Score;
				$aExaminerScore[$panelmembers[$memloop]->id]['presentation'] = $vPresentationScore;
				@endphp
			@endif
			<div class="row">									
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right pt-2">
					<p>{{ __('Examiner - ') }}{{($memloop+1)}}{{ __(' Rubric') }}:<br />
						(&nbsp;{{$panelmembers[$memloop]->name}}&nbsp;)
					</p>
				</div>						  	 
				<div class="col-8 text-left">
					<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
						@if(count($aPanelRubricInfo) > 0)
							<div style="padding:0px; padding-top:12px;">
								<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.90rem;line-height: 0.90;" href="javascript:void(0)" onclick="funViewRubric(2,3,{{$panelmembers[$memloop]->id}})" data-original-title="" title="">View Grade</a>
							</div>
						@elseif($term2progressdetails[0]->approval_status == 3 && count($aPanelRubricInfo) == 0)
							<div style="padding:0px; padding-top:12px;">{{ __('Grading Inprogress') }}</div>
						@else
							<div style="padding:0px; padding-top:12px;">{{ __('Pending') }}</div>
						@endif
					</div>
				</div>												
			</div>
			@endfor
		@endif
		@if($term2progressdetails[0]->approval_status == 3 || $term2progressdetails[0]->approval_status == 1)
		<div class="row">									
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Score Details ') }}:</p>
			</div>						  	 
			<div class="col-md-8">
				<table class="table">    
					<tbody style="border:2px solid #ddd;">
						<tr class="cbold_text" style="background-color:#B5DBEC">
							@if(count($panelmembers) > 0)
								@for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++)
								<td colspan="2" style="text-align:center; border-right: solid 1px #ddd;">Examiner - {{($score_loop+1)}}</td>
								@endfor
							@endif
						</tr>
						<tr class="cbold_text">							
							@for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++)
								<td style="text-align:center; border-right: solid 2px #ddd;">Final Report<br />70%  Score</td>
								<td style="text-align:center; border-right: solid 2px #ddd;">Presentation<br />30% Score</td>
							@endfor
						</tr>
						<tr class="sid_text">							
							@for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++)
								<td style="text-align:center; border-right: solid 2px #ddd;">
									{{ !empty($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2']) ? number_format((($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2']*70)/100), 2, ".", "") : '--'}}
								</td>
								<td style="text-align:center; border-right: solid 2px #ddd;">
									{{ !empty($aExaminerScore[$panelmembers[$score_loop]->id]['presentation']) ? number_format((($aExaminerScore[$panelmembers[$score_loop]->id]['presentation']*30)/100), 2, ".", "") : '--'}}
								</td>
							@endfor
						</tr>
					</tbody>
				</table>					
			</div>												
		</div>
		<div class="row">									
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Overall Aggregate Score ') }}:</p>
			</div>						  	 
			<div class="col-md-8">
				@php
				$vOverallChaper2Score = 0;
				$vOverallPresentationScore = 0;
				$vOverAllAggregateScore = '--';
				$vSuccessRubric = 0;
				if(count($panelmembers) > 0){
					for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++){
						if(!empty($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2'])){
							$vOverallChaper2Score += ($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2']*70)/100;
							$vSuccessRubric++;
						}
						if(!empty($aExaminerScore[$panelmembers[$score_loop]->id]['presentation'])){
							$vOverallPresentationScore += ($aExaminerScore[$panelmembers[$score_loop]->id]['presentation']*30)/100;
						}
					}
					if($vOverallChaper2Score > 0 && $vOverallPresentationScore > 0) {
						$vOverAllAggregateScore = (($aSupervisorScore[$item->assigned_to]*30)/100)+ ($vOverallChaper2Score/$vSuccessRubric) + ($vOverallPresentationScore/$vSuccessRubric);
					}
				}					
				@endphp	
				<div class="cbold_text pt-2">
					@if((int)$vOverAllAggregateScore > 0)
						{{ number_format($vOverAllAggregateScore, 2, ".", "")}}
					@else
						{{ $vOverAllAggregateScore }}
					@endif
				</div>				
			</div>												
		</div>
		@endif					
		@if($term2progressdetails[0]->approval_status > 0)
			@if(auth()->user()->role_id == 2)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Supervisor Completion Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						@if($term2progressdetails[0]->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Change Request In Progress') }}</p>					
						@elseif($term2progressdetails[0]->upload_file_status == 1)
							<p>{{ __('Completed') }}</p>		
						@endif											
					</div>												
				</div>																		
			@endif
		@elseif($term2progressdetails[0]->approval_status == 0 && $term2progressdetails[0]->upload_file_status == 1)
			@if(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac"))
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">	
						@if($term2progressdetails[0]->approval_status == 0 && ($requestdetails[0]->external_review_status == 0 || $requestdetails[0]->external_review_status == 2))
							<p>{{ __('External Review In Progress') }}</p>							
						@elseif($term2progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && ($requestdetails[0]->defence_status == 2 || $requestdetails[0]->defence_status == 0))
							<p>{{ __('Defense In Progress') }}</p>
						@elseif($term2progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && $requestdetails[0]->defence_status == 1 )
							<p>{{ __('Defense Completed') }}</p>
						@elseif($term2progress->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Changes Requested by Manager') }}</p>	
						@else
							<p>{{ __('Pending') }}</p>		
						@endif											
					</div>												
				</div>																		
			@endif
		@endif
		@if(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac"))
			<div class="row">
				@if($term2progressdetails[0]->upload_file_status != 1)
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-4">
						<p>{{ __('Term - II  Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
							<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">
								<option value="0" {{ $term2progressdetails[0]->upload_file_status == 0 ? 'selected' : '' }} >{{ __('In Progress') }}</option>									
								<option value="1" {{ $term2progressdetails[0]->upload_file_status == 1  ? 'selected' : '' }} >{{ __('Completed') }}</option>									
							</select>
							@include('alerts.feedback', ['field' => 'submmission_status'])
						</div>
					</div>
				@elseif($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status == 0)
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Term - II Supervisor Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						<p>{{ __('Completed') }}</p>	
					</div>
				@endif										
			</div>
			@if($requestdetails[0]->progress_completion == 2)
				<div id="divCompletionMessage" style="display:none">
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Message') }}:</p>
						</div>						  	 
						<div class="col-6 text-left cht_text">
							<div class="form-group view_word {{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
								<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
								@include('alerts.feedback', ['field' => 'completionmessage'])
							</div>
						</div>												
					</div>
					<div class="row">	
						 <label class="col-sm-4 col-form-label">&nbsp;</label>
						  <div class="col-sm-8 checkbox-radios">
							  <div class="form-check">
								<label class="form-check-label cht_text">
								  <input name="progress_private_message" class="form-check-input" id="progress_private_message" value="1" onclick="funMakeItPrivate('progress_private_message')" type="checkbox"> {{ __('Private Message to Manager') }}
								  <span class="form-check-sign">
									<span class="check"></span>
								  </span>
								</label>
							  </div>
						</div>
					</div>
				</div>								
			@endif
			<div class="row">
				<div class="col-4">&nbsp;</div>	
				<div class="col-7 text-left">
					@if($term2progressdetails[0]->upload_file_status == 0)
					<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
					<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
					@if(auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac")	
						<input type="hidden" name="action" id="action" value="{{request()->get('action')}}" />	
					@endif
					<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
					<input type="hidden" name="term2rubriccount" id="term2rubriccount" value="{{$vPanelRubricCount}}">
					<button type="button" id="postprogresscomment" onclick="funTerm3SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
					@endif
				</div>												
			</div>								
		@endif
		@if($vEnableManager == 1 && auth()->user()->role_id == 2 && request()->get('action') != "ac")
			@if($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status != 1)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-4">
						<p>{{ __('Term - II Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
							<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(2)" data-style="select-with-transition" title="" data-size="100">
								@if($term2progressdetails[0]->approval_status != 2)
									<option value="2" {{ ($term2progressdetails[0]->approval_status == 2) ? 'selected' : '' }} >{{ __('Request for Changes') }}</option>
									<option value="3" {{ $term2progressdetails[0]->approval_status == 3 ? 'selected' : '' }} >{{ __('Approve Term II Completion For Grading') }}</option>				
								@else
									<option value="2">{{ __('Request for Changes') }}</option>
									<option value="3" {{ $term2progressdetails[0]->approval_status == 3 ? 'selected' : '' }} >{{ __('Approve Term II Completion For Grading') }}</option>		
									<option value="1">{{ __('Complete Term II') }}</option>	
								@endif									
							</select>
							@include('alerts.feedback', ['field' => 'submmission_status'])
						</div>
					</div>
				</div>
			@else
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Approval Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						@if($term2progressdetails[0]->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Requested for Changes') }}</p>
						@else
							<p>{{ __('Approved') }}</p>
						@endif
					</div>
				</div>
			@endif
			@if($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status != 1)	
				<div id="divCompletionMessage">								
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Message') }}:</p>
						</div>						  	 
						<div class="col-6 text-left cht_text">
							<div class="form-group view_word {{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
								<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
								@include('alerts.feedback', ['field' => 'completionmessage'])
							</div>
						</div>												
					</div>
					<div class="row">	
						 <label class="col-sm-4 col-form-label">&nbsp;</label>
						  <div class="col-sm-8 checkbox-radios">
							  <div class="form-check">
								<label class="form-check-label cht_text">
								  <input name="progress_private_message" class="form-check-input" id="progress_private_message" value="1" onclick="funMakeItPrivate('progress_private_message')" type="checkbox"> {{ __('Private Message to Supervisor') }}
								  <span class="form-check-sign">
									<span class="check"></span>
								  </span>
								</label>
							  </div>
						</div>
					</div>
				</div>			
			@endif
			<div class="row">
				<div class="col-4">&nbsp;</div>	
				<div class="col-7 text-left">
					@if($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status != 1)
						<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
						<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
						<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />
						@if(auth()->user()->manager_flag != 2)
							<button type="button" id="postprogresscomment" onclick="funTerm3SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>	
						@endif
					@endif
				</div>												
			</div>								
		@elseif($vEnableManager == 0 && auth()->user()->role_id == 2 && request()->get('action') != "ac") 
			@if(count($term2progressdetails) > 0)							
				@foreach ($term2progressdetails as $term2progress)	
					@if($vShow3Date == 0)
						<div class="row">
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Term - II Completion Date') }}:</p>
							</div>						  	 
							<div class="col-1 text-left cht_text">
								<label class="custom-file-upload">
									{{($timelineinfo[0]->term2_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term2_completion)->format('d-m-Y') : '')}}
								</label>
							</div>								
							<div class="col-2 form_chg text-right">
								<p>{{ __('Program') }}:</p>
							</div>						  	 
							<div class="col-2 text-left cht_text">
								<label class="custom-file-upload">
									{{ ($aProgramInfo[0]->description ? $aProgramInfo[0]->description : "--") }}
								</label>
							</div>												
						</div>
						@php
							$vShow3Date = 1;
						@endphp
					@endif	
					@if($term2progress->document_type == "chapter2report")
						@if($term2progress->student_upload_status == 2)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Thesis Final Report') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>											
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Thesis Final Report') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : "--") }}&nbsp;]</span>
									</label>
								</div>												
						    </div>											
						@endif
					@endif
					@if($term2progress->document_type == "presentationfile")
						@if($term2progress->student_upload_status == 2)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Final Version Presentation') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>																				
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Final Version Presentation') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term2presentation)->format('d-m-Y') : "--") }}&nbsp;]</span>	
									</label>
								</div>												
						    </div>												
						@endif
					@endif						
					@if($term2progress->document_type == "minutes1")							
						@if($term2progress->student_upload_status == 3)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,1)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
									</label>
								</div>												
						    </div>																				
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text">
											@if($term2progress->student_upload_status == 1)
												<span style="color:darkgreen; font-weight: bold;">{{__("In Progress")}}</span>
											@else
												{{ __('Pending') }}
											@endif
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes1 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes1)->format('d-m-Y') : "--") }}&nbsp;]</span>	
									</label>
								</div>												
						    </div>												
						@endif
					@endif
					@if($term2progress->document_type == "minutes2")
						@if($term2progress->student_upload_status == 3)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,2)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
									</label>
								</div>												
						    </div>																				
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text">
											@if($term2progress->student_upload_status == 1)
												<span style="color:darkgreen; font-weight: bold;">{{__("In Progress")}}</span>
											@else
												{{ __('Pending') }}
											@endif
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes2 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes2)->format('d-m-Y') : "--") }}&nbsp;]</span>	
									</label>
								</div>												
						    </div>												
						@endif
					@endif
					@if($term2progress->document_type == "minutes3")
						@if($term2progress->student_upload_status == 3)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,3)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
									</label>
								</div>												
						    </div>																				
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text">
											@if($term2progress->student_upload_status == 1)
												<span style="color:darkgreen; font-weight: bold;">{{__("In Progress")}}</span>
											@else
												{{ __('Pending') }}
											@endif
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes3 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes3)->format('d-m-Y') : "--") }}&nbsp;]</span>	
									</label>
								</div>												
						    </div>												
						@endif
					@endif

					@if($term2progress->document_type == "minutes4")
						@if($term2progress->student_upload_status == 3)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 4') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,4)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
									</label>
								</div>												
						    </div>																				
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 4') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text">
											@if($term2progress->student_upload_status == 1)
												<span style="color:darkgreen; font-weight: bold;">{{__("In Progress")}}</span>
											@else
												{{ __('Pending') }}
											@endif
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes4 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes4)->format('d-m-Y') : "--") }}&nbsp;]</span>	
									</label>
								</div>												
						    </div>												
						@endif
					@endif

					@if($term2progress->document_type == "minutes5")
						@if($term2progress->student_upload_status == 3)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 5') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,5)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
									</label>
								</div>												
						    </div>																				
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 5') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text">
											@if($term2progress->student_upload_status == 1)
												<span style="color:darkgreen; font-weight: bold;">{{__("In Progress")}}</span>
											@else
												{{ __('Pending') }}
											@endif
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes5 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes5)->format('d-m-Y') : "--") }}&nbsp;]</span>	
									</label>
								</div>												
						    </div>												
						@endif
					@endif
				@endforeach					
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Supervisor Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						<label class="custom-file-upload">
							<span class="fileinput-new cht_text">Term - II Inprogress</span>	
						</label>
					</div>												
				</div>
			@endif																
		@endif																					
	@else
		@if($requestdetails[0]->progress_completion == 2)
			<div class="row">
				<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - II In Progress') }}</label>								
			</div>
		@else
			<div class="row">
				<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - II Pending') }}</label>								
			</div>
		@endif							
	@endif
	</div>
	</div>
	</div>
	<div  class="pt-5 text-center">
		@if(request()->get('action') == 'ac')
		<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
		@else
		<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
		@endif
	</div>