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
			$aProgramInfo = \App\Program::where(['programs.id' => $item->program_id])->get();
		@endphp		
		@if(count($progressdetails) > 0)


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
									{{ ($requestdetails[0]->termI_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termI_completion_date)->format('d-m-Y') : "--") }}
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
									<p><span class="mark">*</span>{{ __('Final Version Proposal') }}:</p>
								</div>	
								@if($progress->student_upload_status != 2)
									@php
										$vStudentCompleted = 0;
									@endphp
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
										</div>
									</div>
								@else
									<div class="col-8 cht_text pt-1">												 														
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;float: left;">{{ __('View File') }}</a></span>	
										<div class="form-check pl-4" style="float: left; vertical-align: middle;">
											<label class="form-check-label" style="cursor: default;">
											  <input name="proposalfile_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="proposalfile_file_approve" value="1" type="checkbox">Approved
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
								<input type="hidden" name="proposalfile_flag" id="proposalfile_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Final Version Proposal') }}:</p>
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
					@if($progress->document_type == "minutes1")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 1') }}:</p>
								</div>	
								@if($progress->student_upload_status != 2)
									@php
										$vStudentCompleted = 0;
									@endphp
									<div class="col-8 text-left">
										<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes1" id="minutes1">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
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
								@endif
							</div>																			
						@else
							@php
								$vStudentCompleted = 0;
							@endphp
							<div class="row">
								<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes1" id="minutes1" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>												
						@endif
					@endif
					@if($progress->document_type == "minutes2")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 2') }}:</p>
								</div>	
								@if($progress->student_upload_status != 2)
									@php
										$vStudentCompleted = 0;
									@endphp
									<div class="col-8 text-left">
										<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes2" id="minutes2">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
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
								@endif
							</div>																				
						@else
							@php
								$vStudentCompleted = 0;
							@endphp
							<div class="row">
								<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes2" id="minutes2" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>												
						@endif
					@endif
					@if($progress->document_type == "minutes3")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 3') }}:</p>
								</div>	
								@if($progress->student_upload_status != 2)
									@php
										$vStudentCompleted = 0;
									@endphp
									<div class="col-8 text-left">
										<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes3" id="minutes3">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
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
								@endif
							</div>																				
						@else
							@php
								$vStudentCompleted = 0;
							@endphp
							<div class="row">
								<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes3" id="minutes3" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>												
						@endif
					@endif

					@if($progress->document_type == "minutes4")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 4') }}:</p>
								</div>	
								@if($progress->student_upload_status != 2)
									@php
										$vStudentCompleted = 0;
									@endphp
									<div class="col-8 text-left">
										<input type="hidden" name="minutes4_flag" id="minutes4_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes4" id="minutes4">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
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
								@endif
							</div>																				
						@else
							@php
								$vStudentCompleted = 0;
							@endphp
							<div class="row">
								<input type="hidden" name="minutes4_flag" id="minutes4_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 4') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes4" id="minutes4" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>												
						@endif
					@endif	
					@if($progress->document_type == "minutes5")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 5') }}:</p>
								</div>	
								@if($progress->student_upload_status != 2)
									@php
										$vStudentCompleted = 0;
									@endphp
									<div class="col-8 text-left">
										<input type="hidden" name="minutes5_flag" id="minutes5_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes5" id="minutes5">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
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
								@endif
							</div>																				
						@else
							@php
								$vStudentCompleted = 0;
							@endphp
							<div class="row">
								<input type="hidden" name="minutes5_flag" id="minutes5_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 5') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes5" id="minutes5" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>												
						@endif
					@endif																		
				@endif
				@if((auth()->user()->role_id == 2  && ($progress->upload_file_status > 0 || $progress->approval_status > 0 )) || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac") || auth()->user()->role_id == 3)
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
									{{ ($requestdetails[0]->termI_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termI_completion_date)->format('d-m-Y') : "--") }}
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
					@if($progress->document_type == "proposalrubric")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Proposal Rubric') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="proposalrubric_flag" id="proposalrubric_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="proposalrubric" id="proposalrubric">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
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
								<input type="hidden" name="proposalrubric_flag" id="proposalrubric_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Proposal Rubric') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="proposalrubric" id="proposalrubric" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif
					@endif
					@if($progress->document_type == "minutes1")	
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes1" id="minutes1">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="minutes1_file_approve" class="form-check-input" id="minutes1_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes1',{{$progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
											</div>
											@else
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label" style="cursor: default;">
												  <input name="minutes1_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes1_file_approve" value="1" type="checkbox">Approved
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
								<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes1" id="minutes1" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif																				
					@endif
					@if($progress->document_type == "minutes2")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes2" id="minutes2">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="minutes2_file_approve" class="form-check-input" id="minutes2_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes2',{{$progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
											</div>
											@else
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label" style="cursor: default;">
												  <input name="minutes2_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes2_file_approve" value="1" type="checkbox">Approved
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
								<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes2" id="minutes2" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif							
					@endif
					@if($progress->document_type == "minutes3")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes3" id="minutes3">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="minutes3_file_approve" class="form-check-input" id="minutes3_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes3',{{$progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
											</div>
											@else
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label" style="cursor: default;">
												  <input name="minutes3_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes3_file_approve" value="1" type="checkbox">Approved
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
								<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes3" id="minutes3" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif																			
					@endif



					@if($progress->document_type == "minutes4")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 4') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="minutes4_flag" id="minutes4_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes4" id="minutes4">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="minutes4_file_approve" class="form-check-input" id="minutes4_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes4',{{$progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
											</div>
											@else
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label" style="cursor: default;">
												  <input name="minutes4_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes4_file_approve" value="1" type="checkbox">Approved
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
								<input type="hidden" name="minutes4_flag" id="minutes4_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 4') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes4" id="minutes4" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif																			
					@endif
					@if($progress->document_type == "minutes5")
						@if(!empty($progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 5') }}:</p>
								</div>	
								@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
									<div class="col-8 text-left">
										<input type="hidden" name="minutes5_flag" id="minutes5_flag" value="1" /> 
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
												<span class="fileinput-new">Change file</span>
												<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
												<input type="file" name="minutes5" id="minutes5">
											</span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="minutes5_file_approve" class="form-check-input" id="minutes5_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes5',{{$progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
											</div>
											@else
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label" style="cursor: default;">
												  <input name="minutes5_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes5_file_approve" value="1" type="checkbox">Approved
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
								<input type="hidden" name="minutes5_flag" id="minutes5_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 5') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes5" id="minutes5" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif
					@endif
					@if($progress->approval_status == 1)
						@if($progress->document_type == "presentationrubric")
							<div class="row">																									
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Presentation Rubric') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text pt-1">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($progress->file_name) }}</a></span>															
									</div>
								</div>													
							</div>
						@endif
						@if($progress->document_type == "presentationrubric")
							<div class="row">																									
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Presentation Rubric') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text pt-1">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($progress->file_name) }}</a></span>															
									</div>
								</div>													
							</div>
						@endif
						@if($progress->document_type == "finalreportdraft1")
							<div class="row">																									
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Report Draft 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text pt-1">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($progress->file_name) }}</a></span>															
									</div>
								</div>													
							</div>
						@endif
						@if($progress->document_type == "finalreportdraft1rubric")
							<div class="row">																									
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Report Draft 1 Rubric') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text pt-1">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($progress->file_name) }}</a></span>															
									</div>
								</div>													
							</div>
						@endif
					@endif
				@endif
			@endforeach						
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
				@if($vStudentCompleted == 0 || ($progressdetails[0]->approval_status == 0 && $progressdetails[0]->upload_file_status == 0))
					<div class="row">
						<div class="col-4">&nbsp;</div>													  	 
						<div class="col-7 text-left form_chg">
							@if($progressdetails[0]->upload_file_status == 0)
							<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
							<input type="hidden" name="student_upload_status" id="student_upload_status" value="1" />
							<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
							<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
							<button type="button" id="postprogresscomment" onclick="funTerm1SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit File') }}</button>
							@endif																	
						</div>												
					</div>	
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
							@else
								<div style="padding:0px; padding-top:12px;">{{ __('Pending') }}</div>		
							@endif
						</div>
					</div>												
				</div>																
			@endif
			@if(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac"))
				<div class="row">
					@if($progressdetails[0]->upload_file_status != 1)
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Term Status') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">											
								<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">
									<option value="0" {{ $progressdetails[0]->upload_file_status == 0 ? 'selected' : '' }} >{{ __('In Progress') }}</option>									
									<option value="1" {{ $progressdetails[0]->upload_file_status == 1  ? 'selected' : '' }} >{{ __('Completed') }}</option>									
								</select>
								@include('alerts.feedback', ['field' => 'submmission_status'])
							</div>
						</div>											
					@else
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Term Status') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<div class="form-group cht_text {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">											
								<p>{{ __('Term I Completed') }}</p>
							</div>
						</div>
						
					@endif
				</div>
				
				@if($requestdetails[0]->progress_completion == 0)
					<div id="divCompletionMessage" style="display:none">
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
					<div class="col-8 text-left">
						@if($progressdetails[0]->upload_file_status == 0)
						<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
						<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />
						@if(auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac")	
							<input type="hidden" name="action" id="action" value="{{request()->get('action')}}" />	
						@endif
						<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
						<button type="button" id="postprogresscomment" onclick="funTerm1SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
						@endif																	
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
										<option value="1" {{ $progressdetails[0]->approval_status == 1 ? 'selected' : '' }} >{{ __('Approve Term I Completion') }}</option>									
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
								@elseif($progressdetails[0]->approval_status == 1)
									<p>{{ __('Approved') }}</p>		
								@endif																																
							</div>
						</div>
					@endif
				</div>
				@if($requestdetails[0]->progress_completion == 0)
					<div class="row" id="divPresentationRubric" style="display:none;">	
						<input type="hidden" name="manager_files" id="manager_files" value="1" />
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Presentation Rubric') }}:</p>
						</div>
						<div class="col-8 text-left">
							<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
								<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
									<span class="fileinput-new">Select file</span>
									<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
									<input type="file" name="presentationrubric" id="presentationrubric">
							  </span>
							  <span class="fileinput-filename"></span>
							  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
							</div>
						</div>											
					</div>

					<div class="row" id="divFinalReportDraft1" style="display:none;">							
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Final Report Draft - 1') }}:</p>
						</div>
						<div class="col-8 text-left">
							<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
								<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
									<span class="fileinput-new">Select file</span>
									<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
									<input type="file" name="finalreportdraft1" id="finalreportdraft1">
							  </span>
							  <span class="fileinput-filename"></span>
							  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
							</div>
						</div>											
					</div>
					<div class="row" id="divFinalReportDraft1Rubric" style="display:none;">	
						<input type="hidden" name="manager_files" id="manager_files" value="1" />
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Final Report Draft - 1 Rubric') }}:</p>
						</div>
						<div class="col-8 text-left">
							<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
								<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
									<span class="fileinput-new">Select file</span>
									<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
									<input type="file" name="finalreportdraft1rubric" id="finalreportdraft1rubric">
							  </span>
							  <span class="fileinput-filename"></span>
							  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
							</div>
						</div>											
					</div>

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
										{{ ($requestdetails[0]->termI_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termI_completion_date)->format('d-m-Y') : "--") }}
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
											<span class="fileinput-new form_chg">--</span>
										</label>
									</div>												
							    </div>											
							@endif
						@endif
						@if($progress->document_type == "presentationfile")
							@if(!empty($progress->document_file_path))
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
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif
						@if($progress->document_type == "proposalrubric")
							@if(!empty($progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Proposal Rubric') }}:</p>
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
										<p>{{ __('Proposal Rubric') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif
						@if($progress->document_type == "minutes1")
							@if(!empty($progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 1') }}:</p>
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
										<p>{{ __('Meeting Minutes - 1') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif
						@if($progress->document_type == "minutes2")
							@if(!empty($progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 2') }}:</p>
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
										<p>{{ __('Meeting Minutes - 2') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif
						@if($progress->document_type == "minutes3")
							@if(!empty($progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 3') }}:</p>
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
										<p>{{ __('Meeting Minutes - 3') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif

						@if($progress->document_type == "minutes4")
							@if(!empty($progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 4') }}:</p>
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
										<p>{{ __('Meeting Minutes - 4') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif

						@if($progress->document_type == "minutes5")
							@if(!empty($progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 5') }}:</p>
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
										<p>{{ __('Meeting Minutes - 5') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
							    </div>												
							@endif
						@endif						
					@endforeach
					@if($vOthersFound  == 0)
						<div class="row">
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Other Documents') }}:</p>
							</div>						  	 
							<div class="col-8 text-left cht_text">
								<label class="custom-file-upload">
									<span class="fileinput-new form_chg">--</span>	
								</label>
							</div>												
						</div>
					@endif
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