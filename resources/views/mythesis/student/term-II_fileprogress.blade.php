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
		$vChapterFileEnable = 0;	
		$vShowTermIPrepareRubric = 0;
		$aProgramInfo = \App\Program::where(['programs.id' => $item->program_id])->get();
		$aMeetingLogArray = array();		
		if(count($meetinglogsterm2) > 0){
			foreach($meetinglogsterm2 as $meetinglogt2){
				$aMeetingLogArray[$meetinglogt2->meeting_log_seq] = $meetinglogt2;
				if($meetinglogt2->supervisor_approval_status == 2) {
					$vCompleteMeet2Count++;
				}
			}
		}
	@endphp						
	@if(count($term2progressdetails) > 0)
		@if($term2progressdetails[0]->upload_file_status == 0)
			<div class="row pt-3 pb-4">
				<div class="col-1">&nbsp;</div>
				<div class="col-11 text-left form_chg">
					<span class="warning" style="color:red;"><strong><u>{{__('Note:') }}</u></strong>
					It is a mandatory requirement to complete 5 meeting minutes on or before the scheduled completion date.</span>	
				</div>												
			</div>
		@endif							
		@foreach ($term2progressdetails as $term2progress)	
			@if(auth()->user()->role_id == 4)
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
				@if($term2progress->document_type == "proposalfile")
					@php
						$vProposalFileEnable++;	
					@endphp									
				@endif											
				@if($term2progress->document_type == "minutes1")				
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
										<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,1)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
										@if($aMeetingLogArray[1]->supervisor_approval_status == 2)	
											@php
											$vProposalFileEnable++;	
											@endphp
										@endif		
									@endif
								@else
									<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(2,1)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
									@php
										$vStudentCompleted = 0;
									@endphp
								@endif
								@if(!empty($aMeetingLogArray[1]))
									@if($aMeetingLogArray[1]->supervisor_approval_status < 2)
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes1 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes1)->format('d-m-Y') : "--") }}&nbsp;]
									@endif
								@else
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes1 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes1)->format('d-m-Y') : "--") }}&nbsp;]
								@endif				
							</div>
						</div>												
					</div>																
				@endif
				@if($term2progress->document_type == "minutes2")									
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
										<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,2)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
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
										<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(2,2)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
									@else
										{{ __('Pending') }}
									@endif
								@else
									{{ __('Pending') }}
								@endif
								@if(!empty($aMeetingLogArray[2]))
									@if($aMeetingLogArray[2]->supervisor_approval_status < 2)
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes2 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes2)->format('d-m-Y') : "--") }}&nbsp;]
									@endif
								@else
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes2 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes2)->format('d-m-Y') : "--") }}&nbsp;]
								@endif	
							</div>
						</div>												
					</div>																
				@endif
				@if($term2progress->document_type == "minutes3")								
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
										<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,3)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
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
										<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(2,3)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
									@else
										{{ __('Pending') }}
									@endif
								@else
									{{ __('Pending') }}
								@endif
								@if(!empty($aMeetingLogArray[3]))
									@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes3 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes3)->format('d-m-Y') : "--") }}&nbsp;]
									@endif
								@else
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes3 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes3)->format('d-m-Y') : "--") }}&nbsp;]
								@endif
							</div>
						</div>											
					</div>
				@endif
				@if($term2progress->document_type == "chapter1report")
					@if(!empty($term2progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Chapter - I Report') }}:</p>
							</div>	
							@if($term2progress->student_upload_status != 2)
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
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;float: left;">{{ __('View File') }}</a></span>	
									<div class="form-check pl-4" style="float: left; vertical-align: middle;">
										<label class="form-check-label" style="cursor: default;">
										  <input name="chapter1report_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="chapter1report_file_approve" value="1" type="checkbox">Approved
										  <span class="form-check-sign">
											<span class="check"></span>
										  </span>
										</label>												
									</div>
								</div>
								@if($term2progress->student_upload_status == 2)	
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
								<p><span class="mark">*</span>{{ __('Chapter - I Report') }}:</p>
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
									  &nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter1 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter1)->format('d-m-Y') : "--") }}&nbsp;]
									</div>
								</div>
							@else								
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
										{{ __('Pending') }}
										@if(!empty($aMeetingLogArray[3]))
											@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter1 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter1)->format('d-m-Y') : "--") }}&nbsp;]
											@endif
										@else
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter1 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter1)->format('d-m-Y') : "--") }}&nbsp;]
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
										'thesis_rubric_details.rubric_term' => 2,
										'thesis_rubric_details.rubric_type' => 1])->get();
					if(count($aRubricTerm1Info) > 0) {
						$vProposalFileEnable++;
					}	
				}					
				@endphp	
				@if($term2progress->document_type == "minutes4")				
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
										<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,4)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>			
										@if($aMeetingLogArray[4]->supervisor_approval_status == 2)	
											@php
											$vProposalFileEnable++;
											@endphp
										@endif					
									@endif
								@elseif(!empty($aMeetingLogArray[3]))
									@if($aMeetingLogArray[3]->meeting_log_seq == 3 && $aMeetingLogArray[3]->supervisor_approval_status == 2)
										@php
											$vStudentCompleted = 0;
										@endphp
										<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(2,4)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
									@else
										{{ __('Pending') }}
									@endif
								@else
									{{ __('Pending') }}
								@endif
								@if(!empty($aMeetingLogArray[4]))
									@if($aMeetingLogArray[4]->supervisor_approval_status < 2)
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes4 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes4)->format('d-m-Y') : "--") }}&nbsp;]
									@endif
								@else
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes4 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes4)->format('d-m-Y') : "--") }}&nbsp;]
								@endif											
							</div>
						</div>												
					</div>
				@endif
				@if($term2progress->document_type == "chapter2report")
					@if(!empty($term2progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Chapter - II Report') }}:</p>
							</div>	
							@if($term2progress->student_upload_status != 2)
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
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a>
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : "--") }}&nbsp;]
										</span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">									
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
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
								<p><span class="mark">*</span>{{ __('Chapter - II Report') }}:</p>
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
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : "--") }}&nbsp;]
									</div>
								</div>
							@else
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
										{{ __('Pending') }}
										@if(!empty($aMeetingLogArray[3]))
											@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
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
				@if($term2progress->document_type == "minutes5")				
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
										<a href="javascript:void(0)" onclick="funViewMeetingMinutes(2,5)" class="bct_list" style="font-size:14px;">View Meeting Minutes</a>
										@if($aMeetingLogArray[5]->supervisor_approval_status == 2)
											@php
											$vProposalFileEnable++;
											@endphp
										@endif								
									@endif
								@elseif(!empty($aMeetingLogArray[4]))
									@if($aMeetingLogArray[4]->meeting_log_seq == 4 && $aMeetingLogArray[4]->supervisor_approval_status == 2)
										@php
											$vStudentCompleted = 0;
										@endphp
										<a href="javascript:void(0)" onclick="funPrepareMeetingMinutes(2,5)" class="bct_list" style="font-size:14px;">Prepare Meeting Minutes</a>
									@else
										{{ __('Pending') }}
									@endif
								@else
									{{ __('Pending') }}
								@endif
								@if(!empty($aMeetingLogArray[5]))
									@if($aMeetingLogArray[5]->supervisor_approval_status < 2)
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes5 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes5)->format('d-m-Y') : "--") }}&nbsp;]
									@endif
								@else
									&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->t2_meeting_minutes5 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes5)->format('d-m-Y') : "--") }}&nbsp;]
								@endif	
							</div>
						</div>											
					</div>
				@endif
				@if($term2progress->document_type == "presentationfile")
					@if(!empty($term2progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Final Version Presentation') }}:</p>
							</div>	
							@if($term2progress->student_upload_status != 2)
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
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term2presentation)->format('d-m-Y') : "--") }}&nbsp;]
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">								
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
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
										&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term2presentation)->format('d-m-Y') : "--") }}&nbsp;]
									</div>
								</div>
							@else
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text pt-2" data-provides="fileinput">
										{{ __('Pending') }}
										@if(!empty($aMeetingLogArray[3]))
											@if($aMeetingLogArray[3]->supervisor_approval_status < 2)
												&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term2presentation)->format('d-m-Y') : "--") }}&nbsp;]
											@endif
										@else
											&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;Completion Date:{{ ($timelineinfo[0]->term2presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term2presentation)->format('d-m-Y') : "--") }}&nbsp;]
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
			@if($term2progressdetails[0]->upload_file_status > 0)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Supervisor Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						<span class="fileinput-new cht_text">{{ __('Completed') }}</span>
					</div>												
				</div>								
			@else
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Supervisor Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">	
						<span class="fileinput-new cht_text">{{ __('In Progress') }}</span>	
					</div>												
				</div>									
			@endif
			@if($term2progressdetails[0]->approval_status > 0)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						@if($term2progressdetails[0]->approval_status == 1)
							<p>{{ __('Approved') }}</p>										
						@elseif($term2progressdetails[0]->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Changes Requested by Manager') }}</p>												
						@else
							<p>{{ __('Pending') }}</p>		
						@endif												
					</div>												
				</div>									
			@elseif($term2progressdetails[0]->upload_file_status == 1)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text ">
						@if($term2progressdetails[0]->approval_status == 0 && ($requestdetails[0]->external_review_status == 0 || $requestdetails[0]->external_review_status == 2))
							<p>{{ __('External Review In Progress') }}</p>							
						@elseif($term2progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && ($requestdetails[0]->defence_status == 2 || $requestdetails[0]->defence_status == 0))
							<p>{{ __('Defense In Progress') }}</p>
						@elseif($term2progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && $requestdetails[0]->defence_status == 1 )
							<p>{{ __('Defense Completed') }}</p>
						@elseif($term2progressdetails[0]->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Changes Requested by Manager') }}</p>	
						@else
							<p>{{ __('Pending') }}</p>		
						@endif
					</div>												
				</div>									
			@endif
			@if($vStudentCompleted == 0)
				<div class="row">
					<div class="col-4">&nbsp;</div>	
					<div class="col-7 text-left">
						@if($term2progressdetails[0]->upload_file_status == 0)
						<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
						<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />
						<input type="hidden" name="student_upload_status" id="student_upload_status" value="1" />									
						<button type="button" id="postprogresscomment" onclick="funTerm2SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
						@endif
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
		<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
		@else
		<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
		@endif
	</div>