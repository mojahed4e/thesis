<div class="card-body">
 <!-- Supervisor:<br />
	1. Final Presentation process document<br />						
	2. Meeting Minutes Submission<br />						
Coordinator:<br />
	1. Review and Approve<br />
	-->
	@php
		$vOthersFound = 0;
		$vEnableManager = 0;
		$vShow2Date = 0;
		$vStudentCompleted = 1;
	@endphp						
	@if(count($term2progressdetails) > 0) 
		@foreach ($term2progressdetails as $term2progress)
			@if(auth()->user()->role_id == 4)
				@if($vShow2Date == 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Term - II Completion Date') }}:</p>
						</div>						  	 
						<div class="col-8 text-left cht_text">
							<label class="custom-file-upload">
								{{ ($requestdetails[0]->termII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termII_completion_date)->format('d-m-Y') : "--") }}
							</label>
						</div>												
					</div>
					@php
						$vShow2Date = 1;
					@endphp
				@endif
				@if($term2progress->document_type == "presentationfile")
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Term - II Project Document') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->student_upload_status != 2)	
								@php
									$vStudentCompleted = 0;
								@endphp											
								<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="presentationfile" id="presentationfile" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
								</div>														
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
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
						</div>
					@else
						@php
							$vStudentCompleted = 0;
						@endphp
						<div class="row">
							<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Term - II Project Document') }}:</p>
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
				@if($term2progress->document_type == "minutes1")
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Meeting Minutes - 1') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->student_upload_status != 2)	
								@php
									$vStudentCompleted = 0;
								@endphp											
								<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="minutes1" id="minutes1" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
								</div>														
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
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
				@if($term2progress->document_type == "minutes2")
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Meeting Minutes - 2') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->student_upload_status != 2)	
								@php
									$vStudentCompleted = 0;
								@endphp									
								<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="minutes2" id="minutes2" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
								</div>														
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
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
				@if($term2progress->document_type == "minutes3")
					
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p><span class="mark">*</span>{{ __('Meeting Minutes - 3') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->student_upload_status != 2)
								@php
									$vStudentCompleted = 0;
								@endphp
								<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="minutes3" id="minutes3" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
								</div>														
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline; float: left;">{{ __('View File') }}</a></span>	
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
				
				@if($term2progress->document_type == "otherdocumsnts")
					@if($vOthersFound == 0)
						@if($term2progress->student_upload_status != 2)
							<div class="row">
								<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
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
						@if(!empty($term2progress->document_file_path))
						<div class="row">
								<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
								<div class="col-1">&nbsp;</div>
								@if($term2progress->student_upload_status == 2)
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Other Documents') }}:</p>
									</div>
								@else
									<div class="col-3 form_chg text-right pt-2">
										<p>&nbsp;</p>
									</div>
								@endif																					  	 
								<div class="col-8 text-left cht_text pt-1">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>					
									</div>
								</div>												
							</div>
						@endif																					
						@php
							$vOthersFound  = 1;
						@endphp
					@else												
						<div class="row">
							@if(!empty($term2progress->document_file_path))
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>&nbsp;</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</div>								
								</div>
							@endif
						</div>										
					@endif
				@endif									
			@endif
			@if((auth()->user()->role_id == 2 && ($term2progress->upload_file_status > 0 || $term2progress->approval_status > 0 )) || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac") || auth()->user()->role_id == 3)
				@php
					$vEnableManager = 1;
				@endphp	
				@if($vShow2Date == 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Term - II Completion Date') }}:</p>
						</div>						  	 
						<div class="col-8 text-left cht_text">
							<label class="custom-file-upload">
								{{ ($requestdetails[0]->termII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termII_completion_date)->format('d-m-Y') : "--") }}
							</label>
						</div>												
					</div>
					@php
						$vShow2Date = 1;
					@endphp
				@endif
				@if($term2progress->document_type == "presentationfile")
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Term - II Project Document') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term2progress->approval_status != 1))													
								<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="presentationfile" id="presentationfile" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									@if($term2progress->student_upload_status != 2)
									<div class="form-check pl-4" style="float: right; vertical-align: middle;">
										<label class="form-check-label">
										  <input name="presentationfile_file_approve" class="form-check-input" id="presentationfile_file_approve" value="1" type="checkbox">&nbsp;
										  <span class="form-check-sign">
											<span class="check"></span>
										  </span>
										</label>
										<button type="button" id="fileApprove" onclick="funApproveFileSubmission('presentationfile',{{$term2progress->item_id}},2)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
							</div>
						</div>
					@else											
						<div class="row">
							<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="0" /> 
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Term - II Project Document') }}:</p>
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
				
				@if($term2progress->document_type == "minutes1")
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 1') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term2progress->approval_status != 1))													
								<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="minutes1" id="minutes1" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									@if($term2progress->student_upload_status != 2)
									<div class="form-check pl-4" style="float: right; vertical-align: middle;">
										<label class="form-check-label">
										  <input name="minutes1_file_approve" class="form-check-input" id="minutes1_file_approve" value="1" type="checkbox">&nbsp;
										  <span class="form-check-sign">
											<span class="check"></span>
										  </span>
										</label>
										<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes1',{{$term2progress->item_id}},2)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
							</div>
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
				@if($term2progress->document_type == "minutes2")
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 2') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term2progress->approval_status != 1))													
								<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="minutes2" id="minutes2" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									@if($term2progress->student_upload_status != 2)
									<div class="form-check pl-4" style="float: right; vertical-align: middle;">
										<label class="form-check-label">
										  <input name="minutes2_file_approve" class="form-check-input" id="minutes2_file_approve" value="1" type="checkbox">&nbsp;
										  <span class="form-check-sign">
											<span class="check"></span>
										  </span>
										</label>
										<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes2',{{$term2progress->item_id}},2)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
							</div>
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
				@if($term2progress->document_type == "minutes3")
					@if(!empty($term2progress->document_file_path))
						<div class="row">												
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right pt-2">
								<p>{{ __('Meeting Minutes - 3') }}:</p>
							</div>
							<div class="col-8 text-left">
							@if($term2progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term2progress->approval_status != 1))													
								<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="1" /> 
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Change file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="minutes3" id="minutes3" />
									</span>
									<span class="fileinput-filename"></span>
									<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
									@if($term2progress->student_upload_status != 2)
									<div class="form-check pl-4" style="float: right; vertical-align: middle;">
										<label class="form-check-label">
										  <input name="minutes3_file_approve" class="form-check-input" id="minutes3_file_approve" value="1" type="checkbox">&nbsp;
										  <span class="form-check-sign">
											<span class="check"></span>
										  </span>
										</label>
										<button type="button" id="fileApprove" onclick="funApproveFileSubmission('minutes3',{{$term2progress->item_id}},2)"  class="btn bt_styl text-capitalize" style="padding: 0.60625rem 0.80rem;line-height: 0.70; cursor: pointer;">{{ __('Approve') }}</button>
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
							@else
								<div class="cht_text">												 														
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>
							@endif
							</div>
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
				@if($term2progress->document_type == "otherdocumsnts")									
					@if($vOthersFound == 0)
						@if(!empty($term2progress->document_file_path))
							@if($term2progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term2progress->approval_status != 1))
								<div class="row">
									<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
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
							@if(!empty($term2progress->document_file_path))	
								<div class="row">
									<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
									<div class="col-1">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										@if(($term2progress->upload_file_status == 1 && auth()->user()->role_id == 3) || ($term2progress->approval_status == 1 && auth()->user()->role_id == 2))
											<p>{{ __('Other Documents') }}:</p>
										@else
											<p>&nbsp;</p>	
										@endif
									</div>						  	 
									<div class="col-8 text-left pt-2">
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>															
											<!--<a href="#" onclick="deleteCallback( {{$term2progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
										</div>								
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
							@if(!empty($term2progress->document_file_path))													
								<label class="col-sm-3 col-form-label" style="padding:0px; padding-top:4px;">&nbsp;</label>	
								<div class="col-sm-5">												 
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>															
									<!--<a href="#" onclick="deleteCallback( {{$term2progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
								</div>
							@endif
						</div>
					@endif
				@endif						
			@endif
		@endforeach 
		@if($vOthersFound == 0 && (($term2progressdetails[0]->upload_file_status != 1 && auth()->user()->role_id == 3) || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac") || (($term2progressdetails[0]->approval_status != 1 && auth()->user()->role_id == 2 && $term2progressdetails[0]->upload_file_status == 1) || ($term2progressdetails[0]->approval_status == 2 && auth()->user()->role_id == 2 && $term2progressdetails[0]->upload_file_status == 0))))
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
		@if($vOthersFound == 0 && (($term2progressdetails[0]->upload_file_status == 1 && auth()->user()->role_id == 3) || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac" && $term2progressdetails[0]->upload_file_status == 1 ) || ($term2progressdetails[0]->approval_status == 1 && auth()->user()->role_id == 2)))								
			<div class="row">									
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right">
					<p>{{ __('Other Documents') }}:</p>
				</div>						  	 
				<div class="col-8 text-left">
					<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
						{{ __('--') }}
					</div>								
				</div>												
			</div>
		@endif

		@if($vOthersFound == 0 && auth()->user()->role_id == 4)								
			<div class="row">
				<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
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

		@if(auth()->user()->role_id == 4)							
			@if($term2progressdetails[0]->upload_file_status > 0)
			<div class="row">									
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right">
					<p>{{ __('Supervisor Status ') }}:</p>
				</div>						  	 
				<div class="col-8 text-left">
					<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
						<p>{{ __('Completed') }}</p>
					</div>								
				</div>												
			</div>								
			@else
				<div class="row">									
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Supervisor Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							<p>{{ __('In Progress') }}</p>
						</div>								
					</div>												
				</div>									
			@endif
			@if($term2progressdetails[0]->approval_status > 0)
				<div class="row">									
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							@if($term2progressdetails[0]->approval_status == 1)
								<p>{{ __('Approved') }}</p>							
							@elseif($term2progressdetails[0]->approval_status == 2)
								<p>{{ __('Requested for Changes') }}</p>												
							@endif
						</div>								
					</div>												
				</div>								
			@else								
				<div class="row">									
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Manager Approval Status ') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							<p>{{ __('Pending') }}</p>
						</div>								
					</div>												
				</div>
			@endif
			@if($vStudentCompleted == 0 || ($term2progressdetails[0]->approval_status == 0 && $term2progressdetails[0]->upload_file_status == 0))
				<div class="row">
					<div class="col-4">&nbsp;</div>													  	 
					<div class="col-6 text-left">
						@if($term2progressdetails[0]->upload_file_status == 0)
						<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
						<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />
						<input type="hidden" name="student_upload_status" id="student_upload_status" value="1" />	
						<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
						<button type="button" id="postprogresscomment" onclick="funTerm2SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit File') }}</button>
						@endif
					</div>												
				</div>
			@endif
		@endif
		@if((auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac")) && ($term2progressdetails[0]->approval_status > 0 || $term2progressdetails[0]->upload_file_status == 1))
			<div class="row">									
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right">
					<p>{{ __('Manager Approval Status ') }}:</p>
				</div>						  	 
				<div class="col-8 text-left">
					<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
						@if($term2progressdetails[0]->approval_status == 1)
							<p>{{ __('Approved') }}</p>
						@elseif($term2progressdetails[0]->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 0)
							<p>{{ __('Requested for Changes') }}</p>
						@elseif($term2progressdetails[0]->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 1)
							<p>{{ __('Awaiting for Manager Approval') }}</p>
						@else
							<p>{{ __('Pending') }}</p>		
						@endif
					</div>								
				</div>												
			</div>																
		@endif
		@if(auth()->user()->role_id == 2)
			@if($term2progressdetails[0]->approval_status != 0 || $term2progressdetails[0]->upload_file_status == 1)
				<div class="row">									
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Supervisor Completion Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							@if($term2progressdetails[0]->approval_status == 2 && $term2progressdetails[0]->upload_file_status == 0)
								<p>{{ __('Change Request In Progress') }}</p>
							@else
								<p>{{ __('Completed') }}</p>
							@endif
						</div>								
					</div>												
				</div>										
			@endif
		@endif
		@if(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id && request()->get('action') == "ac"))
			<div class="row">
				@if($term2progressdetails[0]->upload_file_status != 1)
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right pt-4">
					<p>{{ __('Term - II Status') }}:</p>
				</div>						  	 
				<div class="col-8 text-left">
					<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">											
						<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">
							<option value="0" {{ $term2progressdetails[0]->upload_file_status == 0 ? 'selected' : '' }} >{{ __('In Progress') }}</option>									
							<option value="1" {{ $term2progressdetails[0]->upload_file_status == 1  ? 'selected' : '' }} >{{ __('Completed') }}</option>									
						</select>
						@include('alerts.feedback', ['field' => 'submmission_status'])
					</div>								
				</div>
				@else
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right">
						<p>{{ __('Term - II Status') }}:</p>
					</div>						  	 
					<div class="col-8 text-left">
						<p>{{ __('Completed') }}</p>							
					</div>
				@endif
			</div>							
			@if($term2progressdetails[0]->upload_file_status == 0)
				<div id="divCompletionMessage" style="display:none">
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Message') }}:</p>
						</div>						  	 
						<div class="col-8 text-left">
							<div class="form-group view_word{{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
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
					<button type="button" id="postprogresscomment" onclick="funTerm2SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
					@endif
				</div>												
			</div>									
		@endif
	@if($vEnableManager == 1 && auth()->user()->role_id == 2 && request()->get('action') != "ac")
		<div class="row">
			@if($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status != 1)
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-4">
				<p>{{ __('Approval Status') }}:</p>
			</div>						  	 
			<div class="col-8 text-left">
				<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
					<select class="selectpicker pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(1)" data-style="select-with-transition" title="" data-size="100">
						<option value="2" {{ $term2progressdetails[0]->approval_status == 2  ? 'selected' : '' }} >{{ __('Request for Changes') }}</option>											
						<option value="1" {{ $term2progressdetails[0]->approval_status == 1 ? 'selected' : '' }} >{{ __('Approve Term II Completion') }}</option>																			
					</select>
					@include('alerts.feedback', ['field' => 'submmission_status'])
				</div>								
			</div>
			@else
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right">
					<p>{{ __('Approval Status') }}:</p>
				</div>						  	 
				<div class="col-8 cht_text text-left">
					@if($term2progressdetails[0]->upload_file_status == 0 && $term2progressdetails[0]->approval_status == 2)
						<p>{{ __('Requested for Changes') }}</p>											
					@elseif($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status ==1)
						<p>{{ __('Approved') }}</p>	
					@endif
				</div>
			@endif
		</div>
		@if($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status != 1 )
			<div id="divCompletionMessage">
				<div class="row">								
					<div class="col-1">&nbsp;</div>
					<div class="col-3 form_chg text-right pt-4">
						<p>{{ __('Message') }}:</p>
					</div>						  	 
					<div class="col-7 text-left">
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
						<button type="button" id="postprogresscomment" onclick="funTerm2SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
					@endif	
				@endif
			</div>												
		</div>								
	@elseif($vEnableManager == 0 && auth()->user()->role_id == 2) 
		@if(count($term2progressdetails) > 0)
			@foreach ($term2progressdetails as $term2progress)									
				@if($vShow2Date == 0)
					<div class="row">
						<div class="col-1">&nbsp;</div>
						<div class="col-3 form_chg text-right">
							<p>{{ __('Term - II Completion Date') }}:</p>
						</div>						  	 
						<div class="col-8 text-left cht_text">
							<label class="custom-file-upload">
								{{ ($requestdetails[0]->termII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termII_completion_date)->format('d-m-Y') : "--") }}
							</label>
						</div>												
					</div>
					@php
						$vShow2Date = 1;
					@endphp
				@endif
				@if($term2progress->document_type == "presentationfile")
					@if(!empty($term2progress->document_file_path))
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Term - II Project Document') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>								
							</div>												
						</div>
					@else
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Term - II Project Document') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new">--</span>	
								</div>								
							</div>												
						</div>
					@endif
				@endif									
				@if($term2progress->document_type == "minutes1")
					@if(!empty($term2progress->document_file_path))
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Meeting Minutes - 1') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>								
							</div>												
						</div>
					@else
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Meeting Minutes - 1') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new">--</span>	
								</div>								
							</div>												
						</div>
					@endif										
				@endif
				@if($term2progress->document_type == "minutes2")
					@if(!empty($term2progress->document_file_path))
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Meeting Minutes - 2') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>								
							</div>												
						</div>
					@else
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Meeting Minutes - 2') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new">--</span>	
								</div>								
							</div>												
						</div>
					@endif										
				@endif
				@if($term2progress->document_type == "minutes3")
					
					@if(!empty($term2progress->document_file_path))
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Meeting Minutes -3') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
								</div>								
							</div>												
						</div>
					@else
						<div class="row">									
							<div class="col-1">&nbsp;</div>
							<div class="col-3 form_chg text-right">
								<p>{{ __('Meeting Minutes - 3') }}:</p>
							</div>						  	 
							<div class="col-8 text-left">
								<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									<span class="fileinput-new">--</span>	
								</div>								
							</div>												
						</div>
					@endif
				@endif
				
				@if($term2progress->document_type == "otherdocumsnts")
					@if($vOthersFound == 0)
						@if(!empty($term2progress->document_file_path))
							<div class="row">									
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Other Documents') }}:</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</div>								
								</div>												
							</div>																						
						@endif
						@php
							$vOthersFound  = 1;
						@endphp
					@else												
						<div class="row">
							@if(!empty($term2progress->document_file_path))
								<div class="col-1">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>&nbsp;</p>
								</div>						  	 
								<div class="col-8 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
									</div>								
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
					<div class="col-8 text-left">
						<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
							<span class="fileinput-new">--</span>	
						</div>								
					</div>												
				</div>	
			@endif
			<div class="row">									
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right">
					<p>{{ __('Supervisor Status') }}:</p>
				</div>						  	 
				<div class="col-8 text-left">
					<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
						<span class="fileinput-new">Term - II In Progress</span>	
					</div>								
				</div>												
			</div>	
		@endif						
	@endif						
		
	@elseif(auth()->user()->role_id == 3 && $requestdetails[0]->progress_completion == 1 )	
		
		<div class="row">
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right">
				<p>{{ __('Term - II Completion Date') }}:</p>
			</div>						  	 
			<div class="col-8 text-left cht_text">
				<label class="custom-file-upload">
					{{ ($requestdetails[0]->termII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termII_completion_date)->format('d-m-Y') : "--") }}
				</label>
			</div>												
		</div>
		
		<div class="row">
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Term - II Project Document') }}:</p>
			</div>						  	 
			<div class="col-8 text-left">
				<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
					<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
						<span class="fileinput-new">Select file</span>
						<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
						<input type="file" name="presentationfile">
					</span>
					<span class="fileinput-filename"></span>
					<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
				</div>								
			</div>												
		</div>
		
		<div class="row">
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Meeting Minutes - 1') }}:</p>
			</div>						  	 
			<div class="col-8 text-left">
				<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
					<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
						<span class="fileinput-new">Select file</span>
						<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
						<input type="file" name="minutes1">
					</span>
					<span class="fileinput-filename"></span>
					<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
				</div>								
			</div>												
		</div>
		
		<div class="row">
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Meeting Minutes - 2') }}:</p>
			</div>						  	 
			<div class="col-8 text-left">
				<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
					<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
						<span class="fileinput-new">Select file</span>
						<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
						<input type="file" name="minutes2">
					</span>
					<span class="fileinput-filename"></span>
					<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
				</div>								
			</div>												
		</div>
			
		<div class="row">
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Meeting Minutes - 3') }}:</p>
			</div>						  	 
			<div class="col-8 text-left">
				<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
					<span class="btn btn-outline-secondary btn-file" style="line-height: 0.10">
						<span class="fileinput-new">Select file</span>
						<span class="fileinput-exists" style="line-height: 0.10">Change File</span>
						<input type="file" name="minutes3">
					</span>
					<span class="fileinput-filename"></span>
					<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
				</div>								
			</div>												
		</div>	
		
		<div class="row">
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Other Documents') }}:</p>
			</div>						  	 
			<div class="col-8 text-left">
				<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
					<span class="btn btn-outline-secondary btn-file" style="line-height: 0.10">
						<span class="fileinput-new">Select file</span>
						<span class="fileinput-exists" style="line-height: 0.10">Change File</span>
						<input type="file" name="otherdocumsnts[]" multiple>
					</span>								 
					<span class="fileinput-filename input-group-append"></span>
					<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
				</div>								
			</div>												
		</div>
		
		<div class="row">
			<div class="col-1">&nbsp;</div>
			<div class="col-3 form_chg text-right pt-2">
				<p>{{ __('Term - II Status') }}:</p>
			</div>						  	 
			<div class="col-8 text-left">
				<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
					<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">																													
						<option value="0">{{ __('In Progress') }}</option>									
						<option value="1">{{ __('Completed') }}</option>									
					</select>
					@include('alerts.feedback', ['field' => 'submmission_status'])
				</div>								
			</div>												
		</div>	

		<div id="divCompletionMessage" style="display:none">
			<div class="row">
				<div class="col-1">&nbsp;</div>
				<div class="col-3 form_chg text-right pt-2">
					<p>{{ __('Message') }}:</p>
				</div>						  	 
				<div class="col-8 text-left">
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
		<div class="row">
			<div class="col-4">&nbsp;</div>													  	 
			<div class="col-7 text-left">
				<input type="hidden" name="recadd" id="recadd" value="1" />
				<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
				<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
				<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
				<button type="button" id="postprogresscomment" onclick="funTerm2SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
			</div>												
		</div>					
	@else
		@if($requestdetails[0]->progress_completion == 1)
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