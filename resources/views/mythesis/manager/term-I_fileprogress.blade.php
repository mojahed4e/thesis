<div class="card-body">	
		<!--
		Student:
			1. Proposal Submission
			2. Pesentation Submission
			3. Meeting Minutes (5) Submission
		Supervisor:
			1. Proposal Submission
			2. Pesentation Submission
			3. Meeting Minutes (5) Submission
			4. Proposal Rubric Submission
			5. Review and Approve
		Panel:
			1. Review and Approve						
		Manager:
			1. Review and Approve			
			2. Final Report Draft 1
			3. Final Report Draft 1 Rubric  
			4. Presentation Rubric Submission 
			5. Complete Term - I to Initiate Term - II
			-->	
		@php
			$vOthersFound = 0;
			$vEnableManager = 0;
			$vSeqIndex = 0;
			$vShowDate = 0;
			$vStudentCompleted = 1;	
			$vMetLogSeq = 0;	
			$vProposalFileEnable = 0;
			$aSupervisorScore = array();
			$aExaminerScore = array();			
			$aTemplateMarkPercent = array();
			$vPanelRubricCount = 0;			
			$aProgramInfo = \App\Program::where(['programs.id' => $item->program_id])->get();
			$aTemplateInfo = \App\ThesisRubricTemplate::Status()->get();
			if(count($aTemplateInfo) > 0){
				foreach($aTemplateInfo as $template){
					$aTemplateMarkPercent[$template->template_id] = $template->mark_percentage;
				}	
			}
			$aMeetingLogArray = array();
			if(count($meetinglogs) > 0){
				foreach($meetinglogs as $meetinglog){
					$aMeetingLogArray[$meetinglog->meeting_log_seq] = $meetinglog;
				}
			}	
		@endphp		
		@if(count($progressdetails) > 0)
			@foreach ($progressdetails as $progress)
				@if((auth()->user()->role_id == 2  && ($progress->upload_file_status > 0 || $progress->approval_status > 0 )))
					@php
						$vEnableManager = 1;
					@endphp
					@if($vShowDate == 0)
						<div class="row">
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Term - I Completion Date') }}:</p>
							</div>						  	 
							<div class="col-1 text-left cht_text">
								<label class="custom-file-upload">
									{{($timelineinfo[0]->term1_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term1_completion)->format('d-m-Y') : '')}}
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
							$vShowDate = 1;
						@endphp
					@endif
					@if($progress->document_type == "proposalfile")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Version Proposal') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="proposalfile_flag" id="proposalfile_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="proposalfile" id="proposalfile">
											</span>
											<span class="fileinput-filename"></span>															
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="proposalfile_file_approve" class="form-check-input" id="proposalfile_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('proposalfile',{{$progress->item_id}},1)" class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
											</div>
											@else
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label" style="cursor: default;">
												  <input name="proposalfile_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="proposalfile_file_approve" value="1" type="checkbox">Approved
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>												
											</div>	
											@endif
										</div>
									</div>
								@else
									<div class="col-7 cht_text pt-1">					
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</div>
								@endif
							</div>
						@else											
							<div class="row">
								<input type="hidden" name="proposalfile_flag" id="proposalfile_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Version Proposal') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="proposalfile" id="proposalfile">
									  </span>
									  <span class="fileinput-filename"></span>
									  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif
					@endif
					@if($progress->document_type == "presentationfile")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Version Presentation') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
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
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="presentationfile_file_approve" class="form-check-input" id="presentationfile_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('presentationfile',{{$progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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
					@if($progress->document_type == "chapter1report")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Chapter on Thesis Proposal') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="chapter1report_flag" id="chapter1report_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="chapter1report" id="chapter1report">
											</span>
											<span class="fileinput-filename"></span>															
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="chapter1report_file_approve" class="form-check-input" id="chapter1report_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('chapter1report',{{$progress->item_id}},1)" class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
											</div>
											@else
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label" style="cursor: default;">
												  <input name="chapter1report_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="chapter1report_file_approve" value="1" type="checkbox">Approved
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
									<div class="col-7 cht_text pt-1">					
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</div>
								@endif
							</div>							
							@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status > -1))
								@php
								$aChapIRubricInfo = \App\ThesisRubricDetails::where(['item_id' => $item->id,'rubric_term' => 1, 'rubric_type' => 1,'status' => 1])->get();
								$vChaper1Score = 0;								
								@endphp
								@if(count($aChapIRubricInfo) > 0)
									@for($chap1_loop = 0; $chap1_loop < count($aChapIRubricInfo); $chap1_loop++)
										@php
										if($aChapIRubricInfo[$chap1_loop]->rubric_template_id > 0) {
											$vChaper1Score += ($aChapIRubricInfo[$chap1_loop]->criteria_score_percent*$aTemplateMarkPercent[$aChapIRubricInfo[$chap1_loop]->rubric_template_id])/100;
										}
										@endphp
									@endfor
									@php
									$aSupervisorScore[$item->assigned_to] = $vChaper1Score;
									@endphp
									<div class="row">	
										<div class="col-1">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Chapter on Thesis Proposal Grade') }}:</p>
										</div>
										<div class="col-8 cht_text pt-1">
											<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.90rem;line-height: 0.90;" href="javascript:void(0)" onclick="funViewRubric(1,1,{{$item->assigned_to}})" data-original-title="" title="">View Grade</a>
										</div>								
									</div>
								@else
									<div class="row">	
										<div class="col-1">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Chapter on Thesis Proposal Grade') }}:</p>
										</div>
										<div class="col-8 cht_text pt-1">
											{{ __('Pending') }}
										</div>								
									</div>								
								@endif	
							@endif
						@else											
							<div class="row">
								<input type="hidden" name="chapter1report_flag" id="chapter1report_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Chapter on Thesis Proposal') }}:</p>
								</div>
								@if($vProposalFileEnable == 7)							  	 
									<div class="col-8 text-left">
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="chapter1report" id="chapter1report">
										  </span>
										  <span class="fileinput-filename"></span>
										  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										  &nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
										</div>
									</div>
								@else
									<div class="col-8 text-left">
										<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
											{{ __('Pending') }}
											@if(!empty($aMeetingLogArray[5]))
												@if($aMeetingLogArray[5]->supervisor_approval_status < 2)
													&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
												@endif
											@else
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
											@endif
										</div>
									</div>
								@endif												
							</div>
							<div>
								<div class="row">
									<input type="hidden" name="proposalrubric_flag" id="proposalrubric_flag" value="0" /> 
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Chapter on Thesis Proposal Grade') }}:</p>
									</div>						  	 
									<div class="col-8 text-left">
										@if(!empty($aMeetingLogArray[5]))
											@if($aMeetingLogArray[5]->supervisor_approval_status < 2)
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
											@endif
										@else
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
										@endif
									</div>												
								</div>
							</div>
						@endif
					@endif
					@if($progress->document_type == "chapter2report")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Chapter on Theoretical Background') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
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
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="chapter2report_file_approve" class="form-check-input" id="chapter2report_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('chapter2report',{{$progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
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
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</div>
								@endif
							</div>
						@else
							<div class="row">
								<input type="hidden" name="chapter2report_flag" id="chapter2report_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Chapter on Theoretical Background') }}:</p>
								</div>
								@if($vProposalFileEnable == 7)						  	 
									<div class="col-8 text-left">
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Select file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="chapter2report" id="chapter2report" />
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
										</div>
									</div>
								@else
									<div class="col-8 text-left">
										<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
											{{ __('Pending') }}
											@if(!empty($aMeetingLogArray[5]))
												@if($aMeetingLogArray[5]->supervisor_approval_status < 2)
													&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
												@endif
											@else
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
											@endif
										</div>
									</div>
								@endif												
							</div>
						@endif
					@endif					
					@if($progress->document_type == "minutes1")							
						@if($progress->student_upload_status > 0 && !empty($aMeetingLogArray[1]))
							<div class="row pt-2">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-1">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>	
								@if($progress->student_upload_status == 2 || $progress->approval_status >= 0  && $progress->sequence = 3)
									<div class="col-8 text-left">
										<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
											@if(count($aMeetingLogArray) > 0)
												@if(!empty($aMeetingLogArray[1]))
													@php
													$vMetLogSeq = 1;
													@endphp
													<div class="form-check" style="float: right; vertical-align: middle;">
														<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,1)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
													</div>
												@endif
											@endif											
										</div>
									</div>
								@else
									@if($progress->student_upload_status == 1)
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
					@if($progress->document_type == "minutes2")
						@if($progress->student_upload_status > 0 && !empty($aMeetingLogArray[2]))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>	
								@if($progress->student_upload_status == 2  && $progress->approval_status >= 0 && $progress->sequence = 4)
									<div class="col-8 text-left">
										<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
											@if(count($aMeetingLogArray) > 0)
												@if(!empty($aMeetingLogArray[2]))			
													@php
													$vMetLogSeq = 2;
													@endphp
													<div class="form-check" style="float: right; vertical-align: middle;">
														<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,2)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
													</div>
												@endif
											@endif											
										</div>
									</div>
								@else
									@if($progress->student_upload_status == 1)
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
					@if($progress->document_type == "minutes3")
						@if($progress->student_upload_status > 0 && !empty($aMeetingLogArray[3]))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>	
								@if($progress->student_upload_status == 2  || $progress->approval_status >= 0 && $progress->sequence = 5)
									<div class="col-8 text-left">
										<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
											@if(count($aMeetingLogArray) > 0)
												@if(!empty($aMeetingLogArray[3]))			
													@php
													$vMetLogSeq = 3;
													@endphp
													<div class="form-check" style="float: right; vertical-align: middle;">
														<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,3)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
													</div>
												@endif
											@endif											
										</div>
									</div>
								@else
									@if($progress->student_upload_status == 1)
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
					@if($progress->document_type == "minutes4")						
						@if($progress->student_upload_status > 0 && !empty($aMeetingLogArray[4]))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 4') }}:</p>
								</div>	
								@if($progress->student_upload_status == 2  || $progress->approval_status >= 0 && $progress->sequence == 4)
									<div class="col-8 text-left">
										<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
											@if(count($aMeetingLogArray) > 0)
												@if(!empty($aMeetingLogArray[4]))		
													@php
													$vMetLogSeq = 4;
													@endphp
													<div class="form-check" style="float: right; vertical-align: middle;">
														<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,4)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
													</div>
												@endif
											@endif											
										</div>
									</div>
								@else
									@if($progress->student_upload_status == 1)
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
					@if($progress->document_type == "minutes5")
						@if($progress->student_upload_status > 0 && !empty($aMeetingLogArray[5]))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 5') }}:</p>
								</div>	
								@if($progress->student_upload_status == 2  || $progress->approval_status >= 0 && $progress->sequence == 7)
									<div class="col-8 text-left">
										<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text " data-provides="fileinput">
											@if(count($aMeetingLogArray) > 0)
												@if(!empty($aMeetingLogArray[5]))		
													@php
													$vMetLogSeq = 5;
													@endphp
													<div class="form-check" style="float: right; vertical-align: middle;">
														<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,5)" class="bct_list" style="font-size:14px; top: 5px !important;">View Meeting Minutes</a>
													</div>
												@endif
											@endif											
										</div>
									</div>
								@else
									@if($progress->student_upload_status == 1)
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
			@if(count($panelmembers) > 0)
				@for($memloop = 0; $memloop < count($panelmembers); $memloop++)
				@php
				$aPanelRubricInfo = \App\ThesisRubricDetails::where(['item_id' => $item->id,'rubric_term' => 1, 'rubric_type' => 2, 'status' => 1, 'created_by' => $panelmembers[$memloop]->id])->get();
				@endphp
				@php
				$aChapIIRubricInfo = \App\ThesisRubricDetails::where(['item_id' => $item->id,'rubric_term' => 1, 'rubric_type' => 2, 'status' => 1, 'created_by' => $panelmembers[$memloop]->id])->get();
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
							if($chap2_loop < 4){
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
						<p>{{ __('Examiner - ') }}{{($memloop+1)}}{{ __(' Grade') }}:<br />
							(&nbsp;{{$panelmembers[$memloop]->name}}&nbsp;)
						</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							@if(count($aPanelRubricInfo) > 0)
								<div style="padding:0px; padding-top:12px;">
									<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.90rem;line-height: 0.90;" href="javascript:void(0)" onclick="funViewRubric(1,2,{{$panelmembers[$memloop]->id}})" data-original-title="" title="">View Grade</a>
								</div>
							@elseif($progressdetails[0]->approval_status == 3 && count($aPanelRubricInfo) == 0)
								<div style="padding:0px; padding-top:12px;">{{ __('Grading Inprogress') }}</div>
							@else
								<div style="padding:0px; padding-top:12px;">{{ __('Pending') }}</div>
							@endif
						</div>
					</div>												
				</div>
				@endfor
			@endif
			@if($progressdetails[0]->approval_status == 3 || $progressdetails[0]->approval_status == 1)
				<div class="row">									
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p>{{ __('Score Details ') }}:</p>
					</div>						  	 
					<div class="col-md-8">
						<table class="table">    
							<tbody style="border:2px solid #ddd;">
								<tr class="cbold_text" style="background-color:#B5DBEC">
									<td style="text-align:center; border-right: solid 1px #ddd;">Supervisor</td>
									@if(count($panelmembers) > 0)
										@for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++)
										<td colspan="2" style="text-align:center; border-right: solid 1px #ddd;">Examiner - {{($score_loop+1)}}</td>
										@endfor
									@endif
								</tr>
								<tr class="cbold_text">
									<td style="text-align:center; border-right: solid 2px #ddd;">Chapter on Thesis Proposal <br />30% Score</td>
									@for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++)
										<td style="text-align:center; border-right: solid 2px #ddd;">Chapter on Theoretical Background<br />40%  Score</td>
										<td style="text-align:center; border-right: solid 2px #ddd;">Presentation<br />30% Score</td>
									@endfor
								</tr>
								<tr class="sid_text">
									<td style="text-align:center; border-right: solid 2px #ddd;">{{ !empty($aSupervisorScore) ? number_format((($aSupervisorScore[$item->assigned_to]*30)/100), 2, ".", "") : '--'}}</td>
									@for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++)
										<td style="text-align:center; border-right: solid 2px #ddd;">
											{{ !empty($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2']) ? number_format((($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2']*40)/100), 2, ".", "") : '--'}}
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
						<p>{{ __('Aggregate Score Details ') }}:</p>
					</div>						  	 
					<div class="col-md-8">
						<table class="table">    
							<tbody style="border:2px solid #ddd;">							
								<tr class="cbold_text" style="background-color:#B5DBEC">
									<td style="text-align:center; border-right: solid 2px #ddd;">Chapter on Thesis Proposal  &nbsp;&nbsp;&nbsp;(30% Score)</td>
									<td style="text-align:center; border-right: solid 2px #ddd;">Chapter on Theoretical Background &nbsp;&nbsp;&nbsp;(40%  Score)</td>
									<td style="text-align:center; border-right: solid 2px #ddd;">Presentation &nbsp;&nbsp;&nbsp;(30% Score)</td>							
								</tr>
								<tr class="sid_text">
									<td style="text-align:center; border-right: solid 2px #ddd;">{{ !empty($aSupervisorScore) ? number_format((($aSupervisorScore[$item->assigned_to]*30)/100), 2, ".", "") : '--'}}</td>
									@php
									$vPresentScore = 0;								
									$vChap2Score = 0;
									$vRubricCount = 0;
									@endphp
									@for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++)
										@php
										if(!empty($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2'])){
											$vRubricCount++;
											$vChap2Score = $vChap2Score + (($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2']*40)/100);
										}
										if(!empty($aExaminerScore[$panelmembers[$score_loop]->id]['presentation'])){

											$vPresentScore = $vPresentScore + (($aExaminerScore[$panelmembers[$score_loop]->id]['presentation']*30)/100);
										}
										@endphp									
									@endfor
									<td style="text-align:center; border-right: solid 2px #ddd;">
										{{ ($vChap2Score > 0) ? number_format(($vChap2Score/$vRubricCount), 2, ".", "") : '--'}}
									</td>
									<td style="text-align:center; border-right: solid 2px #ddd;">
										{{ ($vPresentScore > 0) ? number_format(($vPresentScore/$vRubricCount), 2, ".", "") : '--'}}
									</td>
								</tr>
							</tbody>
						</table>					
					</div>												
				</div>
				@if($vRubricCount == count($panelmembers))
					<div class="row">
						<input type="hidden" name="gradingcomplete" id="gradingcomplete" value="1">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Overall Aggregate Score ') }}:</p>
						</div>						  	 
						<div class="col-md-1">
							@php
							$vOverallChaper2Score = 0;
							$vOverallPresentationScore = 0;
							$vOverAllAggregateScore = '--';
							$vSuccessRubric = 0;
							if(count($panelmembers) > 0){
								for($score_loop = 0; $score_loop < count($panelmembers); $score_loop++){
									if(!empty($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2'])){
										$vOverallChaper2Score += ($aExaminerScore[$panelmembers[$score_loop]->id]['chapter2']*40)/100;
										$vSuccessRubric++;
									}
									if(!empty($aExaminerScore[$panelmembers[$score_loop]->id]['presentation'])){
										$vOverallPresentationScore += ($aExaminerScore[$panelmembers[$score_loop]->id]['presentation']*30)/100;
									}
								}
								if($vOverallChaper2Score > 0 && $vOverallPresentationScore > 0 && !empty($aSupervisorScore)) {
									$vOverAllAggregateScore = (($aSupervisorScore[$item->assigned_to]*30)/100)+ ($vOverallChaper2Score/$vSuccessRubric) + ($vOverallPresentationScore/$vSuccessRubric);
								}
							}
							$aGradeScale = array();
							if((int)$vOverAllAggregateScore){
								$aGradeScale = \App\GradingScales::select('*')
								 ->where('range_from','<=',round($vOverAllAggregateScore))
		    					 ->where('range_to','>=',round($vOverAllAggregateScore))->get();
							}
							@endphp	
							<div class="cbold_text pt-2">
								@if((int)$vOverAllAggregateScore)
									{{ number_format($vOverAllAggregateScore, 2, ".", "")}}
								@else
									{{ $vOverAllAggregateScore }}
								@endif
							</div>				
						</div>
						<div class="col-2 form_chg text-right pt-2">
							<p>{{ __('Letter Grade ') }}:</p>
						</div>
						<div class="cbold_text pt-2">
							@if(count($aGradeScale) > 0)
								{{$aGradeScale[0]->letter_grade}}
							@else
								{{ __('--') }}
							@endif
						</div>
						<div class="col-2 form_chg text-right pt-2">
							<p>{{ __('Points ') }}:</p>
						</div>	
						<div class="cbold_text pt-2">
							@if(count($aGradeScale) > 0)
								{{$aGradeScale[0]->points}}
							@else
								{{ __('--') }}
							@endif
						</div>											
					</div>
				@else
					<input type="hidden" name="gradingcomplete" id="gradingcomplete" value="0">
				@endif			
			@endif
			@if((auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac")) && ($progressdetails[0]->approval_status > 0 || $progressdetails[0]->upload_file_status == 1))
				<div class="row">									
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							@if($progressdetails[0]->approval_status == 1)
								<div style="padding:0px; padding-top:12px;">{{ __('Approved') }}</div>
							@elseif($progressdetails[0]->approval_status == 2 && $progressdetails[0]->upload_file_status == 0)
								<div style="padding:0px; padding-top:12px;">{{ __('Requested for Changes') }}</div>	
							@elseif($progressdetails[0]->approval_status == 3 && $progressdetails[0]->upload_file_status == 0)
								<div style="padding:0px; padding-top:12px;">{{ __('Grading Inprogress') }}</div>
							@else
								<div style="padding:0px; padding-top:12px;">{{ __('Pending') }}</div>
							@endif
						</div>
					</div>												
				</div>																
			@endif			
			@if($vEnableManager == 1 && auth()->user()->role_id == 2 && request()->get('action') != "ac")
				@if(auth()->user()->role_id == 2)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Supervisor Completion Status') }}:</p>
						</div>						  	 
						<div class="col-8 text-left cht_text">											
							@if($progressdetails[0]->approval_status == 2 && $progressdetails[0]->upload_file_status == 0)
								<p>{{ __('Change Request In Progress') }}</p>																						
							@elseif($progressdetails[0]->upload_file_status == 1)
								<p>{{ __('Completed') }}</p>		
							@endif											
						</div>												
					</div>																		
				@endif
				<div class="row">
					@if($progressdetails[0]->upload_file_status == 1 && $progressdetails[0]->approval_status != 1)
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-4">
							<p>{{ __('Approval Status') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
								<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(1)" data-style="select-with-transition" title="" data-size="100">
									<option value="2" {{ $progressdetails[0]->approval_status == 2  ? 'selected' : '' }} >{{ __('Request for Changes') }}</option>
									<option value="3" {{ $progressdetails[0]->approval_status == 3  ? 'selected' : '' }} >{{ __('Approve Term I Completion For Grading') }}</option>
									<option value="1" {{ $progressdetails[0]->approval_status == 1 ? 'selected' : '' }} >{{ __('Complete Term I') }}</option>
								</select>	
								@include('alerts.feedback', ['field' => 'submmission_status'])
							</div>
						</div>
					@else
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Approval Status') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<div class="form-group cht_text {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
								@if($progressdetails[0]->approval_status == 2 && $progressdetails[0]->upload_file_status == 0)
									<p>{{ __('Requested for Changes') }}</p>
								@elseif($progressdetails[0]->approval_status == 3)
									<p>{{ __('Grading Inprogress') }}</p>	
								@elseif($progressdetails[0]->approval_status == 1)
									<p>{{ __('Approved') }}</p>		
								@endif
							</div>
						</div>
					@endif
				</div>
				@if($requestdetails[0]->progress_completion == 0)					
					@if($progressdetails[0]->upload_file_status == 1)
						<div class="row">										 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Completion Message') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
									<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Completion Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
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
					@endif
				@endif
				<div class="row">
					<div class="col-4">&nbsp;</div>	
					<div class="col-8 text-left">
						@if($progressdetails[0]->upload_file_status == 1 && $progressdetails[0]->approval_status != 1)
							<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
							<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />								
							<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />
							<input type="hidden" name="term1rubriccount" id="term1rubriccount" value="{{$vPanelRubricCount}}">
							@if(auth()->user()->manager_flag != 2)
								<button type="button" id="postprogresscomment" onclick="funTerm1SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>	
							@endif
						@endif																	
					</div>												
				</div>								
			@elseif($vEnableManager == 0 && auth()->user()->role_id == 2 && request()->get('action') != "ac") 
				@if(count($progressdetails) > 0)
					@foreach ($progressdetails as $progress)										
						@if($vShowDate == 0)
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Term - I Completion Date') }}:</p>
								</div>						  	 
								<div class="col-1 text-left cht_text">
									<label class="custom-file-upload">
										{{($timelineinfo[0]->term1_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term1_completion)->format('d-m-Y') : '')}}
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
								$vShowDate = 1;
							@endphp
						@endif
						@if($progress->document_type == "proposalfile")
							@if($progress->student_upload_status == 2)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Final Version Proposal') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
										</label>
									</div>												
							    </div>											
							@else
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Final Version Proposal') }}:</p>
									</div>						  	 
									<div class="col-8 text-left">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>
										</label>
									</div>												
							    </div>											
							@endif
						@endif
						@if($progress->document_type == "chapter1report")
							@if($progress->student_upload_status == 2)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Chapter on Thesis Proposal') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
										</label>
									</div>												
							    </div>											
							@else
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Chapter on Thesis Proposal') }}:</p>
									</div>						  	 
									<div class="col-8 text-left">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>
										</label>
									</div>												
							    </div>											
							@endif							
						@endif
						@if($progress->document_type == "chapter2report")
							@if($progress->student_upload_status == 2)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Chapter on Theoretical Background') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
										</label>
									</div>												
							    </div>											
							@else
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Chapter on Theoretical Background') }}:</p>
									</div>						  	 
									<div class="col-8 text-left">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>
										</label>
									</div>												
							    </div>											
							@endif
						@endif
						@if($progress->document_type == "presentationfile")
							@if($progress->student_upload_status == 2)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Final Version Presentation') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif						
						@if($progress->document_type == "minutes1")							
							@if($progress->student_upload_status == 3)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 1') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,1)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
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
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif
						@if($progress->document_type == "minutes2")
							@if($progress->student_upload_status == 3)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 2') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,2)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
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
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif
						@if($progress->document_type == "minutes3")
							@if($progress->student_upload_status == 3)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 3') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,3)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
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
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif

						@if($progress->document_type == "minutes4")
							@if($progress->student_upload_status == 3)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 4') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,4)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
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
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif

						@if($progress->document_type == "minutes5")
							@if($progress->student_upload_status == 3)
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 5') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,5)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a></span>	
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
											<span class="fileinput-new cht_text">{{ __('Pending') }}&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]</span>	
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
								<span class="fileinput-new cht_text">Term - I Inprogress</span>	
							</label>
						</div>												
					</div>
				@endif																
			@endif														
		@else
			<div class="row">
				<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - I In Progress') }}</label>								
			</div>
		@endif
	</div>
  </div>