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
		$aProgramInfo = \App\Program::where(['programs.id' => $item->program_id])->get();
	@endphp						
	@if(count($term3progressdetails) > 0)							
		@foreach ($term3progressdetails as $term3progress)	
			@if(auth()->user()->role_id == 4)
				@if($vShow3Date == 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Term - II Completion Date') }}:</p>
						</div>						  	 
						<div class="col-1 text-left cht_text">
							<label class="custom-file-upload">
								{{ ($requestdetails[0]->termIII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termIII_completion_date)->format('d-m-Y') : "--") }}
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
				@if($term3progress->document_type == "proposalfile")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Final Version Report') }}:</p>
							</div>	
							@if($term3progress->student_upload_status != 2)
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
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">				
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>
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
								<p><span class="mark">*</span>{{ __('Final Version Report') }}:</p>
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
				@if($term3progress->document_type == "presentationfile")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Final Version Presentation') }}:</p>
							</div>	
							@if($term3progress->student_upload_status != 2)
								@php
									$vStudentCompleted = 0;
								@endphp
								<div class="col-8 text-left">
									<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="presentationfile" id="presentationfile" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">				
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
									<div class="form-check pl-4" style="float: left; vertical-align: middle;">
										<label class="form-check-label" style="cursor: default;">
										  <input name="presentationfile_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="presentationfile_file_approve" value="1" type="checkbox">Approved
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
				@if($term3progress->document_type == "minutes1")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Meeting Minutes - 1') }}:</p>
							</div>	
							@if($term3progress->student_upload_status != 2)
								@php
									$vStudentCompleted = 0;
								@endphp
								<div class="col-8 text-left">
									<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes1" id="minutes1" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>
									<div class="form-check pl-4" style="float: left; vertical-align: middle;">
										<label class="form-check-label" style="cursor: default;">
										  <input name="minutes1_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes1_file_approve" value="1" type="checkbox">Approved
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
				@if($term3progress->document_type == "minutes2")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Meeting Minutes - 2') }}:</p>
							</div>	
							@if($term3progress->student_upload_status != 2)
								@php
									$vStudentCompleted = 0;
								@endphp
								<div class="col-8 text-left">
									<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes2" id="minutes2" />
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">												 			<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
									<div class="form-check pl-4" style="float: left; vertical-align: middle;">
										<label class="form-check-label" style="cursor: default;">
										  <input name="minutes2_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes2_file_approve" value="1" type="checkbox">Approved
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
				@if($term3progress->document_type == "minutes3")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Meeting Minutes - 3') }}:</p>
							</div>	
							@if($term3progress->student_upload_status != 2)
								@php
									$vStudentCompleted = 0;
								@endphp
								<div class="col-8 text-left">
									<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes3" id="minutes3" />
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">												 		
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
									<div class="form-check pl-4" style="float: left; vertical-align: middle;">
										<label class="form-check-label" style="cursor: default;">
										  <input name="minutes3_file_approve" checked="checked" disabled="disabled" class="form-check-input" id="minutes3_file_approve" value="1" type="checkbox">Approved
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

				@if($term3progress->document_type == "minutes4")
						@if(!empty($term3progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 4') }}:</p>
								</div>	
								@if($term3progress->student_upload_status != 2)
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
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										</div>
									</div>
								@else
									<div class="col-8 cht_text pt-1">												 														
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>
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
					@if($term3progress->document_type == "minutes5")
						@if(!empty($term3progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p><span class="mark">*</span>{{ __('Meeting Minutes - 5') }}:</p>
								</div>	
								@if($term3progress->student_upload_status != 2)
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
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										</div>
									</div>
								@else
									<div class="col-8 cht_text pt-1">												 														
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>
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
				
				@if($term3progress->document_type == "otherdocumsnts")
					@if($vOthersFound == 0)
						@if(!empty($term3progress->document_file_path))											
							@if($term3progress->student_upload_status != 2)
								<div class="row">														
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-8 text-left">
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="otherdocumsnts[]" multiple>
										  </span>
										  <span class="fileinput-filename"></span>
										  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										</div>
									</div>												
								</div>
							@endif												
							@if(!empty($term3progress->document_file_path))
								<div class="row">														
									<div class="col-1">&nbsp;</div>
									@if($term3progress->student_upload_status == 2)
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Other Documents') }}:</p>
										</div>
									@else
										<div class="col-3 form_chg text-right pt-2">
											<p>&nbsp;</p>
										</div>
									@endif
									<div class="col-8 text-left">															
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term3progress->file_name) }}</a></span>
									</div>												
								</div>												
							@endif
						@else
							<div class="row">														
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Other Documents') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="otherdocumsnts[]" multiple>
									  </span>
									  <span class="fileinput-filename"></span>
									  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif
						@php
							$vOthersFound  = 1;
						@endphp
					@else
						<div class="row">
							@if(!empty($term3progress->document_file_path))
								<div class="col-1">&nbsp;</div>														
								<div class="col-3 form_chg text-right pt-2">
									<p>&nbsp;</p>
								</div>														
								<div class="col-8 text-left">															
									<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term3progress->file_name) }}</a></span>
								</div>
							@endif													
						</div>
					@endif
				@endif																		
			@endif
			@if((auth()->user()->role_id == 2 && ($term3progress->upload_file_status > 0 || $term3progress->approval_status > 0 )) || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac") || auth()->user()->role_id == 3)
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
								{{ ($requestdetails[0]->termIII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termIII_completion_date)->format('d-m-Y') : "--") }}
							</label>
						</div>												
					</div>
					@php
						$vShow3Date = 1;
					@endphp
				@endif
				@if($term3progress->document_type == "proposalfile")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Final Version Report') }}:</p>
							</div>	
							@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1 && request()->get('action') != "ac"))
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
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										@if($term3progress->student_upload_status != 2)
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label">
											  <input name="proposalfile_file_approve" class="form-check-input" id="proposalfile_file_approve" value="1" type="checkbox">&nbsp;
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
											<button type="button" id="fileApprove" onclick="funApproveFileSubmission('proposalfile',{{$term3progress->item_id}},3)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
								<div class="col-8 cht_text pt-1">				
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
						</div>
					@else											
						<div class="row">
							<input type="hidden" name="proposalfile_flag" id="proposalfile_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Final Version Report') }}:</p>
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
				@if($term3progress->document_type == "presentationfile")										
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Final Version Presentation') }}:</p>
							</div>	
							@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1 && request()->get('action') != "ac"))
								<div class="col-8 text-left">
									<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="presentationfile" id="presentationfile" />
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										@if($term3progress->student_upload_status != 2)
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label">
											  <input name="presentationfile_file_approve" class="form-check-input" id="presentationfile_file_approve" value="1" type="checkbox">&nbsp;
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
											<button type="button" id="fileApprove" onclick="funApproveFileSubmission('presentationfile',{{$term3progress->item_id}},3)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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
				@if($term3progress->document_type == "proposalrubric")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Report Rubric') }}:</p>
							</div>	
							@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1 && request()->get('action') != "ac"))
								<div class="col-8 text-left">
									<input type="hidden" name="proposalrubric_flag" id="proposalrubric_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="proposalrubric" id="proposalrubric" />
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									</div>
								</div>
							@else
								<div class="col-8 cht_text pt-1">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
						</div>
					@else											
						<div class="row">
							<input type="hidden" name="proposalrubric_flag" id="proposalrubric_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Report Rubric') }}:</p>
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
				@if($term3progress->document_type == "minutes1")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 1') }}:</p>
							</div>	
							@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1 && request()->get('action') != "ac"))
								<div class="col-8 text-left">
									<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes1" id="minutes1" />
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										@if($term3progress->student_upload_status != 2)
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label">
											  <input name="minutes1_file_approve" class="form-check-input" id="minutes1_file_approve" value="1" type="checkbox">&nbsp;
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
											<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes1',{{$term3progress->item_id}},3)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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
				@if($term3progress->document_type == "minutes2")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 2') }}:</p>
							</div>	
							@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1 && request()->get('action') != "ac"))
								<div class="col-8 text-left">
									<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes2" id="minutes2" />
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										@if($term3progress->student_upload_status != 2)
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label">
											  <input name="minutes2_file_approve" class="form-check-input" id="minutes2_file_approve" value="1" type="checkbox">&nbsp;
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
											<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes2',{{$term3progress->item_id}},3)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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
				@if($term3progress->document_type == "minutes3")
					@if(!empty($term3progress->document_file_path))
						<div class="row">	
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 3') }}:</p>
							</div>	
							@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1 && request()->get('action') != "ac"))
								<div class="col-8 text-left">
									<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="1" /> 
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Change file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes3" id="minutes3" />
										</span>
										<span class="fileinput-filename"></span>															
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
										@if($term3progress->student_upload_status != 2)
										<div class="form-check pl-4" style="float: right; vertical-align: middle;">
											<label class="form-check-label">
											  <input name="minutes3_file_approve" class="form-check-input" id="minutes3_file_approve" value="1" type="checkbox">&nbsp;
											  <span class="form-check-sign">
												<span class="check"></span>
											  </span>
											</label>
											<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes3',{{$term3progress->item_id}},3)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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

				@if($term3progress->document_type == "minutes4")
						@if(!empty($term3progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 4') }}:</p>
								</div>	
								@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
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
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($term3progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="minutes4_file_approve" class="form-check-input" id="minutes4_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes4',{{$term3progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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
					@if($term3progress->document_type == "minutes5")
						@if(!empty($term3progress->document_file_path))
							<div class="row">	
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 5') }}:</p>
								</div>	
								@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
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
											<span class="fileinput-filename1"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
											@if($term3progress->student_upload_status != 2)
											<div class="form-check pl-4" style="float: right; vertical-align: middle;">
												<label class="form-check-label">
												  <input name="minutes5_file_approve" class="form-check-input" id="minutes5_file_approve" value="1" type="checkbox">&nbsp;
												  <span class="form-check-sign">
													<span class="check"></span>
												  </span>
												</label>
												<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes5',{{$term3progress->item_id}},1)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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


				@if($term3progress->document_type == "otherdocumsnts")
					@if($vOthersFound == 0)
						@if(!empty($term3progress->document_file_path))											
							@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1 && request()->get('action') != "ac"))					
								<div class="row">														
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-8 text-left">
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="otherdocumsnts[]" multiple>
										  </span>
										  <span class="fileinput-filename"></span>
										  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										</div>
									</div>												
								</div>
							@endif												
							@if(!empty($term3progress->document_file_path))
								<div class="row">														
									<div class="col-1">&nbsp;</div>
									@if(($term3progress->upload_file_status == 1 && auth()->user()->role_id == 3) || ($term3progress->approval_status == 1 && auth()->user()->role_id == 2))
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Other Documents') }}:</p>
										</div>
									@else
										<div class="col-3 form_chg text-right pt-2">
											<p>&nbsp;</p>
										</div>
									@endif
									<div class="col-8 text-left">															
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term3progress->file_name) }}</a></span>															
										<!--<a href="#" onclick="deleteCallback( {{$term3progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
									</div>												
								</div>												
							@endif
						@else
							<div class="row">														
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Other Documents') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="otherdocumsnts[]" multiple>
									  </span>
									  <span class="fileinput-filename"></span>
									  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>
						@endif
						@php
							$vOthersFound  = 1;
						@endphp
					@else
						<div class="row">																								
							@if(!empty($term3progress->document_file_path))
								<div class="col-1">&nbsp;</div>														
								<div class="col-3 form_chg text-right pt-2">
									<p>&nbsp;</p>
								</div>														
								<div class="col-8 text-left">															
									<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term3progress->file_name) }}</a></span>															
									<!--<a href="#" onclick="deleteCallback( {{$term3progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
								</div>
							@endif													
						</div>
					@endif
				@endif
				@if($term3progress->approval_status == 1)
					@if($term3progress->document_type == "finalreportdraft1")
						<div class="row">																									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Final Report Draft 1') }}:</p>
							</div>						  	 
							<div class="col-8 text-left cht_text pt-1">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($term3progress->file_name) }}</a></span>															
								</div>
							</div>													
						</div>
					@endif
					@if($term3progress->document_type == "finalreportdraft1rubric")
						<div class="row">																									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Final Report Draft 1 Rubric') }}:</p>
							</div>						  	 
							<div class="col-8 text-left cht_text pt-1">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($term3progress->file_name) }}</a></span>															
								</div>
							</div>													
						</div>
					@endif

					@if($term3progress->document_type == "presentationrubric")
						<div class="row">
							<div class="col-1">&nbsp;</div>														
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Presentation Rubric') }}:</p>
								</div>														
								<div class="col-8 text-left">															
									<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>																													
								</div>
						</div>											
					@endif
					@if($term3progress->document_type == "finalreportrubric")
						<div class="row">
							<div class="col-1">&nbsp;</div>														
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Report Rubric') }}:</p>
								</div>														
								<div class="col-8 text-left">															
									<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>																													
								</div>
						</div>											
					@endif
				@endif
			@endif
		@endforeach	
		@if($vOthersFound == 0)								
			@if((auth()->user()->role_id == 3 && $term3progressdetails[0]->upload_file_status == 0) || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac" &&  $term3progressdetails[0]->upload_file_status == 0) || ( auth()->user()->role_id == 2 && (($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status != 1 && request()->get('action') != "ac") || ($term3progressdetails[0]->upload_file_status == 0 && $term3progressdetails[0]->approval_status == 2))))
				<div class="row">														
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p>{{ __('Other Documents') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
						  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
							<span class="fileinput-new">Select file</span>
							<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
							<input type="file" name="otherdocumsnts[]" multiple>
						  </span>
						  <span class="fileinput-filename"></span>
						  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
						</div>
					</div>												
				</div>
			@elseif(auth()->user()->role_id == 4 )
				@if($term3progressdetails[0]->approval_status == 0 && $term3progressdetails[0]->upload_file_status == 0)
					<div class="row">														
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Other Documents') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
								<span class="fileinput-new">Select file</span>
								<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
								<input type="file" name="otherdocumsnts[]" multiple>
							  </span>
							  <span class="fileinput-filename"></span>
							  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
							</div>
						</div>												
					</div>
				@else
					<div class="row">														
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right pt-2">
							<p>{{ __('Other Documents') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							 <span class="fileinput-filename">--</span>											 
						</div>												
					</div>
				@endif
			@elseif(($term3progressdetails[0]->upload_file_status == 1 && auth()->user()->role_id == 3) || ($term3progressdetails[0]->approval_status == 1 && auth()->user()->role_id == 2 && request()->get('action') != "ac"))						
				<div class="row">														
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p>{{ __('Other Documents') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						 <span class="fileinput-filename">--</span>											 
					</div>												
				</div>
			@endif
		@endif
		@if(auth()->user()->role_id == 4)							
			@if($term3progressdetails[0]->upload_file_status > 0)
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
			@if($term3progressdetails[0]->approval_status > 0)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">											
							@if($term3progressdetails[0]->approval_status == 1)
								<p>{{ __('Approved') }}</p>										
							@elseif($term3progressdetails[0]->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
								<p>{{ __('Changes Requested by Manager') }}</p>												
							@else
								<p>{{ __('Pending') }}</p>		
							@endif												
					</div>												
				</div>									
			@elseif($term3progressdetails[0]->upload_file_status == 1)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text ">
						@if($term3progressdetails[0]->approval_status == 0 && ($requestdetails[0]->external_review_status == 0 || $requestdetails[0]->external_review_status == 2))
							<p>{{ __('External Review In Progress') }}</p>							
						@elseif($term3progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && ($requestdetails[0]->defence_status == 2 || $requestdetails[0]->defence_status == 0))
							<p>{{ __('Defense In Progress') }}</p>
						@elseif($term3progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && $requestdetails[0]->defence_status == 1 )
							<p>{{ __('Defense Completed') }}</p>
						@elseif($term3progress->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Changes Requested by Manager') }}</p>	
						@else
							<p>{{ __('Pending') }}</p>		
						@endif
					</div>												
				</div>									
			@endif
			@if($vStudentCompleted == 0  || ($term3progressdetails[0]->approval_status == 0 && $term3progressdetails[0]->upload_file_status == 0))
				<div class="row">
					<div class="col-4">&nbsp;</div>													  	 
					<div class="col-7 text-left">
						@if($term3progressdetails[0]->upload_file_status == 0)
						<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
						<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />
						<input type="hidden" name="student_upload_status" id="student_upload_status" value="1" />		
						<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
						<button type="button" id="postprogresscomment" onclick="funTerm3SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit File') }}</button>
						@endif
					</div>												
				</div>
			@endif
		@endif							
		@if($term3progressdetails[0]->approval_status > 0)
			@if(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac"))
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">											
							@if($term3progressdetails[0]->approval_status == 1)
							<p>{{ __('Approved') }}</p>										
						@elseif($term3progress->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Changes Requested by Manager') }}</p>	
						@else
							<p>{{ __('Pending') }}</p>		
						@endif											
					</div>												
				</div>																		
			@endif
			@if(auth()->user()->role_id == 2)
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Supervisor Completion Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">											
							@if($term3progressdetails[0]->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Change Request In Progress') }}</p>																						
						@elseif($term3progressdetails[0]->upload_file_status == 1)
							<p>{{ __('Completed') }}</p>		
						@endif											
					</div>												
				</div>																		
			@endif
		@elseif($term3progressdetails[0]->approval_status == 0 && $term3progressdetails[0]->upload_file_status == 1)
			@if(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac"))
				<div class="row">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">											
						@if($term3progressdetails[0]->approval_status == 0 && ($requestdetails[0]->external_review_status == 0 || $requestdetails[0]->external_review_status == 2))
							<p>{{ __('External Review In Progress') }}</p>							
						@elseif($term3progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && ($requestdetails[0]->defence_status == 2 || $requestdetails[0]->defence_status == 0))
							<p>{{ __('Defense In Progress') }}</p>
						@elseif($term3progressdetails[0]->approval_status == 0 && $requestdetails[0]->external_review_status == 1 && $requestdetails[0]->defence_status == 1 )
							<p>{{ __('Defense Completed') }}</p>
						@elseif($term3progress->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
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
				@if($term3progressdetails[0]->upload_file_status != 1)
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-4">
						<p>{{ __('Term - II  Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
							<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">
								<option value="0" {{ $term3progressdetails[0]->upload_file_status == 0 ? 'selected' : '' }} >{{ __('In Progress') }}</option>									
								<option value="1" {{ $term3progressdetails[0]->upload_file_status == 1  ? 'selected' : '' }} >{{ __('Completed') }}</option>									
							</select>
							@include('alerts.feedback', ['field' => 'submmission_status'])
						</div>
					</div>
				@elseif($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status == 0)
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
					@if($term3progressdetails[0]->upload_file_status == 0)
					<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
					<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
					@if(auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac")	
						<input type="hidden" name="action" id="action" value="{{request()->get('action')}}" />	
					@endif
					<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
					<button type="button" id="postprogresscomment" onclick="funTerm3SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
					@endif
				</div>												
			</div>								
		@endif
		@if($vEnableManager == 1 && auth()->user()->role_id == 2 && request()->get('action') != "ac")
			<div class="row">
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right pt-2">
					<p>{{ __('External Review Completed') }}:</p>
				</div>						  	 
				<div class="col-7 text-left cht_text">
					<div class="form-check view_word">
					@foreach (config('items.review_status') as $value => $status)											  
						<label class="form-check-label">
						  <input name="external_review" class="form-check-input" id="{{ $value }}" value="{{ $value }}" type="radio" @if($requestdetails[0]->external_review_status === $value) checked @endif > {{ $status }}
						  <span class="circle">
							<span class="check"></span>
						  </span>
						</label>											 
					@endforeach
					 </div>
				</div>												
			</div>
			<div class="row">
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right pt-2">
					<p>{{ __('Defense Completed') }}:</p>
				</div>						  	 
				<div class="col-7 text-left cht_text">
					<div class="form-check view_word">										
					@foreach (config('items.review_status') as $value => $status)											  
						<label class="form-check-label" id="defencestatus">
						  <input name="defence_status" class="form-check-input" id="{{ $value }}" value="{{ $value }}" type="radio" @if($requestdetails[0]->defence_status === $value) checked @endif > {{ $status }}
						  <span class="circle">
							<span class="check"></span>
						  </span>
						</label>											 
					@endforeach
					 </div>
				</div>												
			</div>
			<div class="row">
				@if($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status != 1)
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-4">
						<p>{{ __('Term - II Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
							<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(2)" data-style="select-with-transition" title="" data-size="100">
								@if($term3progressdetails[0]->approval_status != 2)
									<option value="2" {{ ($term3progressdetails[0]->approval_status == 2) ? 'selected' : '' }} >{{ __('Request for Changes') }}</option>
									<option value="3" {{ $requestdetails[0]->review_flag == 3 ? 'selected' : '' }} >{{ __('External Review In Progress') }}</option>	
									<option value="4" {{ $requestdetails[0]->review_flag == 4 ? 'selected' : '' }} >{{ __('Defense In Progress') }}</option>
									<option value="1" {{ $term3progressdetails[0]->approval_status == 1 ? 'selected' : '' }} >{{ __('Approve Term II Completion') }}</option>		
								@else
									<option value="2">{{ __('Request for Changes') }}</option>
									<option value="3" {{ $requestdetails[0]->review_flag == 3 ? 'selected' : '' }}>{{ __('External Review In Progress') }}</option>	
									<option value="4" {{ $requestdetails[0]->review_flag == 4 ? 'selected' : '' }}>{{ __('Defense In Progress') }}</option>	
									<option value="1">{{ __('Approve Term II Completion') }}</option>	
								@endif									
							</select>
							@include('alerts.feedback', ['field' => 'submmission_status'])
						</div>
					</div>
				@else									
					@if($term3progress->approval_status == 1)
						@if($term3progress->document_type == "finalreportdraft1")
							<div class="row">																									
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Report Draft 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text pt-1">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($term3progress->file_name) }}</a></span>															
									</div>
								</div>													
							</div>
						@endif
						@if($term3progress->document_type == "finalreportdraft1rubric")
							<div class="row">																									
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Report Draft 1 Rubric') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text pt-1">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($term3progress->file_name) }}</a></span>															
									</div>
								</div>													
							</div>
						@endif

						@if($term3progress->document_type == "presentationrubric")
							<div class="row">
								<div class="col-1">&nbsp;</div>														
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Final Presentation Rubric') }}:</p>
									</div>														
									<div class="col-8 text-left">															
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>																													
									</div>
							</div>											
						@endif
						@if($term3progress->document_type == "finalreportrubric")
							<div class="row">
								<div class="col-1">&nbsp;</div>														
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Final Report Rubric') }}:</p>
									</div>														
									<div class="col-8 text-left">															
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>																													
									</div>
							</div>											
						@endif
					@endif
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Approval Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left cht_text">
						@if($term3progressdetails[0]->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Requested for Changes') }}</p>
						@else
							<p>{{ __('Approved') }}</p>
						@endif
					</div>
				@endif										
			</div>
			@if($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status != 1)	
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
				<div class="row" id="divPresentationRubric" style="display:none;">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p>{{ __('Final Presentation Rubric') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<input type="hidden" name="manager_files" id="manager_files" value="1" />
						<input type="hidden" name="presentationrubric_flag" id="presentationrubric_flag" value="0" />
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
				<div class="row" id="divFinalReportRubric" style="display:none;">
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-2">
						<p>{{ __('Final Report Rubric') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<input type="hidden" name="manager_files" id="manager_files" value="1" />
						<input type="hidden" name="presentationrubric_flag" id="presentationrubric_flag" value="0" />
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
								<span class="fileinput-new">Select file</span>
								<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
								<input type="file" name="finalreportrubric" id="finalreportrubric">
							</span>
							<span class="fileinput-filename"></span>
							<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
						</div>
					</div>												
				</div>				
			@endif
			<div class="row">
				<div class="col-4">&nbsp;</div>													  	 
				<div class="col-7 text-left">
					@if($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status != 1)
						<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
						<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
						<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />
						@if(auth()->user()->manager_flag != 2)
							<button type="button" id="postprogresscomment" onclick="funTerm3SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>	
						@endif
					@endif
				</div>												
			</div>								
		@elseif($vEnableManager == 0 && auth()->user()->role_id == 2) 
			@if(count($term3progressdetails) > 0)
				@foreach ($term3progressdetails as $term3progress)									
					@if($vShow3Date == 0)
						<div class="row">
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Term - II Completion Date') }}:</p>
							</div>						  	 
							<div class="col-8 text-left cht_text">
								<label class="custom-file-upload">
									{{ ($requestdetails[0]->termIII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termIII_completion_date)->format('d-m-Y') : "--") }}
								</label>
							</div>												
						</div>
						@php
							$vShow3Date = 1;
						@endphp
					@endif
					@if($term3progress->document_type == "proposalfile")
						@if(!empty($term3progress->document_file_path))
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Final Version Report') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>											
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Final Version Report') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<label class="custom-file-upload">
										<span class="fileinput-new form_chg">--</span>
									</label>
								</div>												
						    </div>											
						@endif										
					@endif
					@if($term3progress->document_type == "presentationfile")
						@if(!empty($term3progress->document_file_path))
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Final Version Presentation') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>											
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Final Version Presentation') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<label class="custom-file-upload">
										<span class="fileinput-new form_chg">--</span>
									</label>
								</div>												
						    </div>											
						@endif										
					@endif
					@if($term3progress->document_type == "proposalrubric")
						@if(!empty($term3progress->document_file_path))
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Report Rubric') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>											
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Report Rubric') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<label class="custom-file-upload">
										<span class="fileinput-new form_chg">--</span>
									</label>
								</div>												
						    </div>											
						@endif										
					@endif
					@if($term3progress->document_type == "minutes1")
						@if(!empty($term3progress->document_file_path))
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>											
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<label class="custom-file-upload">
										<span class="fileinput-new form_chg">--</span>
									</label>
								</div>												
						    </div>											
						@endif										
					@endif
					@if($term3progress->document_type == "minutes2")
						@if(!empty($term3progress->document_file_path))
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>											
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<label class="custom-file-upload">
										<span class="fileinput-new form_chg">--</span>
									</label>
								</div>												
						    </div>											
						@endif										
					@endif
					@if($term3progress->document_type == "minutes3")
						@if(!empty($term3progress->document_file_path))
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-8 text-left cht_text">
									<label class="custom-file-upload">
										<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</label>
								</div>												
						    </div>											
						@else
							<div class="row">
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<label class="custom-file-upload">
										<span class="fileinput-new form_chg">--</span>
									</label>
								</div>												
						    </div>											
						@endif										
					@endif

					@if($term3progress->document_type == "minutes4")
							@if(!empty($term3progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 4') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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

						@if($term3progress->document_type == "minutes5")
							@if(!empty($term3progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Meeting Minutes - 5') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
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
					
					@if($term3progress->document_type == "otherdocumsnts")
						@if($vOthersFound == 0)
							@if(!empty($term3progress->document_file_path))
								<div class="row">
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
										</label>
									</div>												
								</div>																							
							@endif
							@php
								$vOthersFound  = 1;
							@endphp
						@else
							<div class="row">
								@if(!empty($term3progress->document_file_path))	
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>&nbsp;</p>
									</div>						  	 
									<div class="col-8 text-left cht_text">
										<label class="custom-file-upload">
											<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
										</label>
									</div>
								@endif
							</div>											
						@endif
					@endif																		
				@endforeach
				@if($vOthersFound == 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Other Documents') }}:</p>
						</div>						  	 
						<div class="col-8 text-left cht_text">
							<label class="custom-file-upload">
								<span class="fileinput-new cht_text">--</span>	
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
								<span class="fileinput-new cht_text">Term - II In Progress</span>	
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