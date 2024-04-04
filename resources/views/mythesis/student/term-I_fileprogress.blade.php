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
	$vProposalFileEnable = 0;
	$vChapterFileEnable = 0;	
	$vShowTermIPrepareRubric = 0;				
	$aProgramInfo = \App\Program::where(['programs.id' => $item->program_id])->get();
	$aMeetingLogArray = array();	
	if(count($meetinglogs) > 0){
		foreach($meetinglogs as $meetinglog){
			$aMeetingLogArray[$meetinglog->meeting_log_seq] = $meetinglog;
			if($meetinglog->supervisor_approval_status == 2) {
				$vCompleteMeet1Count++;
			}
		}
	}		
@endphp		
@if(count($progressdetails) > 0)
	@if($progressdetails[0]->upload_file_status == 0)
		<div class="row pt-1 pb-3">
			<div class="col-1">&nbsp;</div>
			<div class="col-11 text-left form_chg">
				<span class="warning" style="color:red;"><strong><u>{{__('Note:') }}</u></strong>
				It is a mandatory requirement to complete 5 meeting minutes on or before the scheduled completion date.</span>	
			</div>												
		</div>
	@endif
	@foreach ($progressdetails as $progress)
		@if(auth()->user()->role_id == 4)				
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
			@if($progress->document_type == "minutes1")				
				<div class="row">
					<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="0" /> 
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p><span class="mark">*</span>{{ __('Meeting Minutes - 1') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
							@if(!empty($aMeetingLogArray))
								@if($aMeetingLogArray[1]->meeting_log_seq == 1 && $aMeetingLogArray[1]->student_submit_status == 1)
									<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,1)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>	
									@if($aMeetingLogArray[1]->supervisor_approval_status == 2)	
										@php
										$vProposalFileEnable++;	
										@endphp
									@endif	
								@endif
							@else
								@php
									$vStudentCompleted = 0;
								@endphp
								<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(1,1)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
							@endif
							@if(!empty($aMeetingLogArray[1]))
								@if($aMeetingLogArray[1]->supervisor_approval_status < 2)
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
								@endif
							@else
								&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
							@endif				
						</div>
					</div>												
				</div>																
			@endif
			@if($progress->document_type == "minutes2")									
				<div class="row">
					<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="0" /> 
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p><span class="mark">*</span>{{ __('Meeting Minutes - 2') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
							@if(!empty($aMeetingLogArray[2]))
								@if($aMeetingLogArray[2]->meeting_log_seq == 2 && $aMeetingLogArray[2]->student_submit_status == 1)
									<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,2)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
									@if($aMeetingLogArray[2]->supervisor_approval_status == 2)	
										@php
										$vProposalFileEnable++;	
										@endphp
									@endif								
								@else
									{{ __('Pending') }}
								@endif
							@elseif(!empty($aMeetingLogArray[1]))
								@if($aMeetingLogArray[1]->meeting_log_seq == 1 && $aMeetingLogArray[1]->supervisor_approval_status == 2)
									@php
										$vStudentCompleted = 0;
									@endphp
									<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(1,2)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
								@else
									{{ __('Pending') }}
								@endif
							@else
								{{ __('Pending') }}
							@endif
							@if(!empty($aMeetingLogArray[2]))
								@if($aMeetingLogArray[2]->supervisor_approval_status < 2)
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
								@endif
							@else
								&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
							@endif	
						</div>
					</div>												
				</div>																
			@endif
			@if($progress->document_type == "minutes3")								
				<div class="row">
					<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="0" /> 
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p><span class="mark">*</span>{{ __('Meeting Minutes - 3') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
							@if(!empty($aMeetingLogArray[3]))
								@if($aMeetingLogArray[3]->meeting_log_seq == 3 && $aMeetingLogArray[3]->student_submit_status == 1)
									<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,3)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
									@if($aMeetingLogArray[3]->supervisor_approval_status == 2)	
										@php
										$vProposalFileEnable++;	
										@endphp
									@endif
								@endif
							@elseif(!empty($aMeetingLogArray[2]))
								@if($aMeetingLogArray[2]->meeting_log_seq == 2 && $aMeetingLogArray[2]->supervisor_approval_status == 2)
									@php
										$vStudentCompleted = 0;
									@endphp
									<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(1,3)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
								@else
									{{ __('Pending') }}
								@endif
							@else
								{{ __('Pending') }}
							@endif
							@if(!empty($aMeetingLogArray[3]))
								@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
								@endif
							@else
								&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
							@endif
						</div>
					</div>											
				</div>
			@endif						
			@if($progress->document_type == "proposalfile")				
				@php
				$vProposalFileEnable++;	
				@endphp
			@endif			
			@if($progress->document_type == "chapter1report")
				@if(!empty($progress->document_file_path))
					<div class="row">	
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p><span class="mark">*</span>{{ __('Chapter on Thesis Proposal') }}:</p>
						</div>	
						@if($progress->student_upload_status != 2)
							@php
								$vStudentCompleted = 0;
							@endphp
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
								</div>
							</div>
						@else
							<div class="col-8 cht_text pt-1">
								<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;float: left;">{{ __('View File') }}</a></span>	
								<div class="form-check pl-4" style="float: left; vertical-align: middle;">
									<label class="form-check-label" style="cursor: default;">
									  <input name="chapter1report_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="chapter1report_file_approve" value="1" type="checkbox">Approved
									  <span class="form-check-sign">
										<span class="check"></span>
									  </span>
									</label>												
								</div>
							</div>
							@if($progress->student_upload_status == 2)	
								@php
								$vProposalFileEnable++;	
								@endphp
							@endif
						@endif
					</div>											
				@else
					@php
						$vStudentCompleted = 0;
					@endphp
					<div class="row">
						<input type="hidden" name="chapter1report_flag" id="chapter1report_flag" value="0" /> 
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p><span class="mark">*</span>{{ __('Chapter on Thesis Proposal') }}:</p>
						</div>
						@if($vProposalFileEnable == 3)						  	 
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
									@if(!empty($aMeetingLogArray[3]))
										@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
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
			@php
			if($vProposalFileEnable == 5)	{
				$aRubricTerm1Info = Illuminate\Support\Facades\DB::table('thesis_rubric_details')
									->select('thesis_rubric_details.*')
									->where(['thesis_rubric_details.item_id' => $item->id, 'thesis_rubric_details.created_by' => $item->assigned_to, 
									'thesis_rubric_details.rubric_term' => 1,
									'thesis_rubric_details.rubric_type' => 1])->get();
				//if(count($aRubricTerm1Info) > 0) {
					$vProposalFileEnable++;
				//}	
			}					
			@endphp								
			@if($progress->document_type == "minutes4")			
				<div class="row">
					<input type="hidden" name="minutes4_flag" id="minutes4_flag" value="0" /> 
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p><span class="mark">*</span>{{ __('Meeting Minutes - 4') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
							@if(!empty($aMeetingLogArray[4]))
								@if($aMeetingLogArray[4]->meeting_log_seq == 4 && $aMeetingLogArray[4]->student_submit_status == 1)
									<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,4)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
									@if($aMeetingLogArray[4]->supervisor_approval_status == 2)	
										@php
										$vProposalFileEnable++;
										@endphp
									@endif	
								@endif
							@elseif(!empty($aMeetingLogArray[3]))
								@if($aMeetingLogArray[3]->meeting_log_seq == 3)
									@php
										$vStudentCompleted = 0;
									@endphp
									<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(1,4)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
								@else
									{{ __('Pending') }}
								@endif
							@else
								{{ __('Pending') }}
							@endif
							@if(!empty($aMeetingLogArray[4]))
								@if($aMeetingLogArray[4]->supervisor_approval_status < 2)
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
								@endif
							@else
								&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
							@endif											
						</div>
					</div>												
				</div>
			@endif
			@if($progress->document_type == "chapter2report")
				@if(!empty($progress->document_file_path))
					<div class="row">	
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p><span class="mark">*</span>{{ __('Chapter on Theoretical Background') }}:</p>
						</div>	
						@if($progress->student_upload_status != 2)
							@php
								$vStudentCompleted = 0;
							@endphp
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
									<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a>
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
									</span>
								</div>
							</div>
						@else
							<div class="col-8 cht_text pt-1">									
								<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
								<div class="form-check pl-4" style="float: left; vertical-align: middle;">
									<label class="form-check-label" style="cursor: default;">
									  <input name="chapter2report_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="chapter2report_file_approve" value="1" type="checkbox">Approved
									  <span class="form-check-sign">
										<span class="check"></span>
									  </span>
									</label>												
								</div>
							</div>
						@endif
					</div>	
				@else
					@php
						$vStudentCompleted = 0;
					@endphp
					<div class="row">
						<input type="hidden" name="chapter2report_flag" id="chapter2report_flag" value="0" /> 
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p><span class="mark">*</span>{{ __('Chapter on Theoretical Background') }}:</p>
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
									@if(!empty($aMeetingLogArray[3]))
										@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
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
			@if($progress->document_type == "minutes5")				
				<div class="row">
					<input type="hidden" name="minutes5_flag" id="minutes5_flag" value="0" /> 
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p><span class="mark">*</span>{{ __('Meeting Minutes - 5') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
							@if(!empty($aMeetingLogArray[5]))
								@if($aMeetingLogArray[5]->meeting_log_seq == 5 && $aMeetingLogArray[5]->student_submit_status == 1)
									<a href="javascript:void(0)" onclick="funViewMeetingMinutes(1,5)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
									@if($aMeetingLogArray[5]->supervisor_approval_status == 2)
										@php
										$vProposalFileEnable++;
										@endphp
									@endif
								@endif
							@elseif(!empty($aMeetingLogArray[4]))
								@if($aMeetingLogArray[4]->meeting_log_seq == 4)
									@php
										$vStudentCompleted = 0;
									@endphp
									<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(1,5)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
								@else
									{{ __('Pending') }}
								@endif
							@else
								{{ __('Pending') }}
							@endif
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
													
			@if($progress->document_type == "presentationfile")
				@if(!empty($progress->document_file_path))
					<div class="row">	
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p><span class="mark">*</span>{{ __('Final Version Presentation') }}:</p>
						</div>	
						@if($progress->student_upload_status != 2)
							@php
								$vStudentCompleted = 0;
							@endphp
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
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($progress->completion_date ? \Carbon\Carbon::parse($progress->completion_date)->format('d-m-Y') : "--") }}&nbsp;]
								</div>
							</div>
						@else
							<div class="col-8 cht_text pt-1">								
								<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
								<div class="form-check pl-4" style="float: left; vertical-align: middle;">
									<label class="form-check-label" style="cursor: default;">
									  <input name="proposalfile_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="proposalfile_file_approve" value="1" type="checkbox">Approved
									  <span class="form-check-sign">
										<span class="check"></span>
									  </span>
									</label>												
								</div>
							</div>
							@php
							$aRubricTerm1Info = Illuminate\Support\Facades\DB::table('thesis_rubric_details')->select('thesis_rubric_details.*')
				            			->where(['thesis_rubric_details.item_id' => $item->id, 
				            			'thesis_rubric_details.created_by' => $item->assigned_to,           						'thesis_rubric_details.rubric_term' => 1,
				            			'thesis_rubric_details.rubric_type' => 1])->get();
				            
				            if(count($aRubricTerm1Info) > 0 && $vShowTermIPrepareRubric == 0){
				            	$vProposalFileEnable++;	
				            	$vShowTermIPrepareRubric = 1;  
				            }			
							@endphp
						@endif
					</div>	
				@else
					@php
						$vStudentCompleted = 0;
					@endphp
					<div class="row">
						<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="0" /> 
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p><span class="mark">*</span>{{ __('Final Version Presentation') }}:</p>
						</div>
						@if($vProposalFileEnable == 7)							  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="presentationfile" id="presentationfile" />
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
									@if(!empty($aMeetingLogArray[3]))
										@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
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
		@endif
	@endforeach
	@if(auth()->user()->role_id == 4)
				@if($progressdetails[0]->upload_file_status > 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Supervisor Status ') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<label class="custom-file-upload">
								<span class="fileinput-new cht_text">{{ __('Completed') }}</span>	
							</label>
						</div>												
					</div>									
				@else
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Supervisor Status ') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<label class="custom-file-upload">
								<span class="fileinput-new cht_text">{{ __('In Progress') }}</span>	
							</label>
						</div>												
					</div>
				@endif
				@if($progressdetails[0]->approval_status > 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Manager Approval Status ') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<label class="custom-file-upload">
								@if($progress->approval_status == 1)
									<span class="fileinput-new cht_text">{{ __('Approved') }}</span>
								@elseif($progress->approval_status == 2 && $progressdetails[0]->upload_file_status == 0)
									<span class="fileinput-new cht_text">{{ __('Requested for Changes') }}</span>	
								@else
									<span class="fileinput-new cht_text">{{ __('Pending') }}</span>	
								@endif												
							</label>
						</div>												
					</div>								
				@else
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Manager Approval Status ') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<label class="custom-file-upload">
								<span class="fileinput-new cht_text">{{ __('Pending') }}</span>
							</label>
						</div>												
					</div>									
				@endif				
				<input type="hidden" name="meeting_log_seq" id="meeting_log_seq" value="0" />	
				<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />		
				@if($vStudentCompleted == 0)
					<div class="row">
						<div class="col-4">&nbsp;</div>
						<div class="col-7 text-left form_chg">
							@if($progressdetails[0]->upload_file_status == 0)
							<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
							<input type="hidden" name="student_upload_status" id="student_upload_status" value="1" />
							<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
							<button type="button" id="postprogresscomment" onclick="funTerm1SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit File') }}</button>
							@endif	
						</div>												
					</div>	
				@endif				
			@endif	
		@endif
	</div>
</div>
</div>
