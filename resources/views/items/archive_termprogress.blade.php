<div class="row">
        <div class="col-md-12">
          <div class="card">         	
            <div class="card-body">
              <div id="accordion" role="tablist">
                <div class="card-collapse">
					<div class="card-header" role="tab" id="headingOne">
						<h5 class="mb-0">
							@if($requestdetails[0]->progress_completion == 0)
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="text-center font-weight-bold collapsed">
							@else
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="text-center font-weight-bold collapsed">
							@endif														
								@if(count($progressdetails) > 0)
									@if($progressdetails[0]->upload_file_status == 0 && $requestdetails[0]->progress_completion == 0 && $progressdetails[0]->approval_status == 0)
										Term - I In Progress
									@elseif( $progressdetails[0]->upload_file_status == 1 && $requestdetails[0]->progress_completion == 0 && $progressdetails[0]->approval_status != 1)
										Term - I Awating for Manager Aproval
									@elseif( $progressdetails[0]->upload_file_status == 0 && $progressdetails[0]->approval_status == 2)
										Term - I Requested Changes by the Manager
									@elseif($progressdetails[0]->upload_file_status == 1 && $progressdetails[0]->approval_status == 1)	
										Term - I Completed									
									@endif
								@else
									Term - I In Progress
								@endif								
							<i class="prog_icon material-icons">keyboard_arrow_down</i>
							</a>
						</h5>
					</div>
					@if($requestdetails[0]->progress_completion == 0)
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
					@else
						<div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
					@endif
					<div class="card-body">	
						<!--Supervisor:<br />
							1. Proposal Submission<br />
							2. Pesentation Submission<br />
							3. Meeting Minutes Submission<br />
							4. Proposal Rubric Submission<br />
						Coordinator:<br />
							1. Review and Approve<br />
							2. Presentation Rubric Submission<br /> -->	
						@php
							$vOthersFound = 0;
							$vEnableManager = 0;
							$vSeqIndex = 0;
							$vShowDate = 0;
						@endphp
						@if(count($progressdetails) > 0)								
							@foreach ($progressdetails as $progress)
								@if(auth()->user()->role_id == 4)
									@if($vShowDate == 0)
										<div class="row">
											<div class="col-2">&nbsp;</div>
											<div class="col-3 form_chg text-right">
												<p>{{ __('Term - I Completion Date') }}:</p>
											</div>						  	 
											<div class="col-7 text-left cht_text">
												<label class="custom-file-upload">
													{{ ($requestdetails[0]->termI_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termI_completion_date)->format('d-m-Y') : "--") }}
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Proposal') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>											
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Proposal') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>																				
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Proposal Rubric') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>																				
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Proposal Rubric') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>																				
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>																				
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>																				
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new form_chg">--</span>	
													</label>
												</div>												
										    </div>												
										@endif
									@endif
									
									@if($progress->document_type == "otherdocumsnts")
										@if($vOthersFound == 0)											
											@if(!empty($progress->document_file_path))
												<div class="row">
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right">
														<p>{{ __('Other Documents') }}:</p>
													</div>						  	 
													<div class="col-7 text-left cht_text">
														<label class="custom-file-upload">
															<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
														</label>
													</div>												
												</div>																						
											@endif
											@php
												$vOthersFound  = 1;
											@endphp
										@else
											<div class="row">
												@if(!empty($progress->document_file_path))
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>&nbsp;</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>
												@endif
											</div>											
										@endif
									@endif																		
								@endif
								@if((auth()->user()->role_id == 2 && ($progress->upload_file_status > 0 || $progress->approval_status > 0 )) || auth()->user()->role_id == 3)
									@php
										$vEnableManager = 1;
									@endphp
									@if($vShowDate == 0)
										<div class="row">
											<div class="col-2">&nbsp;</div>
											<div class="col-3 form_chg text-right">
												<p>{{ __('Term - I Completion Date') }}:</p>
											</div>						  	 
											<div class="col-7 text-left cht_text">
												<label class="custom-file-upload">
													{{ ($requestdetails[0]->termI_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termI_completion_date)->format('d-m-Y') : "--") }}
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Proposal') }}:</p>
												</div>	
												@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else											
											<div class="row">
												<input type="hidden" name="proposalfile_flag" id="proposalfile_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Proposal') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>	
												@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else
											<div class="row">
												<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Proposal Rubric') }}:</p>
												</div>	
												@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else
											<div class="row">
												<input type="hidden" name="proposalrubric_flag" id="proposalrubric_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Proposal Rubric') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>	
												@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else
											<div class="row">
												<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>	
												@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else
											<div class="row">
												<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>	
												@if($progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else
											<div class="row">
												<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
									@if($progress->document_type == "otherdocumsnts")
										@if($vOthersFound == 0)
											@if(!empty($progress->document_file_path))
												@if(($progress->upload_file_status == 0 && auth()->user()->role_id == 3) || ((auth()->user()->role_id == 2 && $progress->approval_status != 1 && $progress->upload_file_status == 1) || (auth()->user()->role_id == 2 && $progress->approval_status == 2 && $progress->upload_file_status == 0)))
													<div class="row">
														<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
														<div class="col-2">&nbsp;</div>
														<div class="col-3 form_chg text-right pt-2">
															<p>{{ __('Other Documents') }}:</p>
														</div>						  	 
														<div class="col-7 text-left">
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
												@if(!empty($progress->document_file_path))
												<div class="row">
														<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
														<div class="col-2">&nbsp;</div>
														@if(($progress->upload_file_status == 1 && auth()->user()->role_id == 3) || ($progress->approval_status == 1 && auth()->user()->role_id == 2))
															<div class="col-3 form_chg text-right pt-2">
																<p>{{ __('Other Documents') }}:</p>
															</div>
														@else
															<div class="col-3 form_chg text-right pt-2">
																<p>&nbsp;</p>
															</div>
														@endif																					  	 
														<div class="col-7 text-left cht_text pt-1">
															<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
																<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($progress->file_name) }}</a></span>															
																<!--<a href="#" onclick="deleteCallback( {{$progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
															</div>
														</div>												
													</div>
												@endif
											@else
												<div class="row">
													<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right pt-2">
														<p>{{ __('Other Documents') }}:</p>
													</div>						  	 
													<div class="col-7 text-left">
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
												@if(!empty($progress->document_file_path))
													@if($requestdetails[0]->progress_completion == 0)
														<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="1" />
													@endif
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right pt-2">
														<p>&nbsp;</p>
													</div>						  	 
													<div class="col-7 text-left cht_text pt-1">
														<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
															<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($progress->file_name) }}</a></span>															
														</div>
													</div>	
												@endif
											</div>											
										@endif
									@endif
									@if($progress->approval_status == 1)
										@if($progress->document_type == "presentationrubric")
											<div class="row">																									
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Presentation Rubric') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text pt-1">
													<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration:underline">{{ __($progress->file_name) }}</a></span>															
													</div>
												</div>													
											</div>
										@endif
									@endif
								@endif
							@endforeach
							@if($vOthersFound == 0 && auth()->user()->role_id == 4)
								<div class="row">
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
										<label class="custom-file-upload">
											<span class="fileinput-new form_chg">--</span>	
										</label>
									</div>												
								</div>
							@endif
							@if(auth()->user()->role_id == 4)							
								@if($progressdetails[0]->upload_file_status > 0)
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<label class="custom-file-upload">
												<span class="fileinput-new cht_text">{{ __('Completed') }}</span>	
											</label>
										</div>												
									</div>									
								@else
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<label class="custom-file-upload">
												<span class="fileinput-new cht_text">{{ __('In Progress') }}</span>	
											</label>
										</div>												
									</div>
								@endif
								@if($progressdetails[0]->approval_status > 0)
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Manager Approval Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Manager Approval Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<label class="custom-file-upload">												
												<span class="fileinput-new cht_text">{{ __('Pending') }}</span>																																
											</label>
										</div>												
									</div>									
								@endif
							@endif
							@if($vOthersFound == 0 && auth()->user()->role_id == 3 && $progressdetails[0]->upload_file_status == 0)
								<div class="row">
									<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
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
							@if($vOthersFound == 0 && auth()->user()->role_id == 2 && (($progressdetails[0]->upload_file_status == 1 && $progressdetails[0]->approval_status != 1) || ($progressdetails[0]->upload_file_status ==0 && $progressdetails[0]->approval_status == 2)))
								<div class="row">
									<input type="hidden" name="otherdocumsnts_flag" id="otherdocumsnts_flag" value="0" /> 
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
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
							@if(auth()->user()->role_id == 3 && ($progressdetails[0]->approval_status > 0 || $progressdetails[0]->upload_file_status == 1))
								<div class="row">									
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Manager Approval Status ') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
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
							@if(auth()->user()->role_id == 3)
								<div class="row">
									@if($progressdetails[0]->upload_file_status != 1)
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Term Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">											
												<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">
													<option value="0" {{ $progressdetails[0]->upload_file_status == 0 ? 'selected' : '' }} >{{ __('In Progress') }}</option>									
													<option value="1" {{ $progressdetails[0]->upload_file_status == 1  ? 'selected' : '' }} >{{ __('Completed') }}</option>									
												</select>
												@include('alerts.feedback', ['field' => 'submmission_status'])
											</div>
										</div>											
									@else
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Term Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<div class="form-group cht_text {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">											
												<p>{{ __('Term I Completed') }}</p>
											</div>
										</div>
										
									@endif
								</div>
								
								@if($requestdetails[0]->progress_completion == 0)
									<div class="row" id="divCompletionMessage" style="display:none">										 
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Completion Message') }}:</p>
										</div>						  	 
										<div class="col-6 text-left">
											<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
												<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Completion Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
												@include('alerts.feedback', ['field' => 'completionmessage'])
											</div>
										</div>												
									</div>								
								@endif
								<div class="row">
									<div class="col-5">&nbsp;</div>													  	 
									<div class="col-6 text-left">
										@if($progressdetails[0]->upload_file_status == 0)
										<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
										<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
										<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
										<button type="button" id="postprogresscomment" onclick="funTerm1SubmmitValidate()" class="btn bt_styl">{{ __('Submit') }}</button>
										@endif																	
									</div>												
								</div>								
							@endif
							@if($vEnableManager == 1 && auth()->user()->role_id == 2)
								@if(auth()->user()->role_id == 2)
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Completion Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">											
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-4">
											<p>{{ __('Approval Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
												
													<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(1)" data-style="select-with-transition" title="" data-size="100">
														<option value="2" {{ $progressdetails[0]->approval_status == 2  ? 'selected' : '' }} >{{ __('Request for Changes') }}</option>											
														<option value="1" {{ $progressdetails[0]->approval_status == 1 ? 'selected' : '' }} >{{ __('Approve Term I Completion') }}</option>									
													</select>											
												
												@include('alerts.feedback', ['field' => 'submmission_status'])
											</div>
										</div>
									@else
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Approval Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Presentation Rubric') }}:</p>
										</div>
										<div class="col-7 text-left">
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
									@if($progressdetails[0]->upload_file_status == 1)
										<div class="row">										 
											<div class="col-2">&nbsp;</div>
											<div class="col-3 form_chg text-right pt-2">
												<p>{{ __('Completion Message') }}:</p>
											</div>						  	 
											<div class="col-6 text-left">
												<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
													<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Completion Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
													@include('alerts.feedback', ['field' => 'completionmessage'])
												</div>
											</div>												
										</div>																			
									@endif
								@endif
								<div class="row">
									<div class="col-5">&nbsp;</div>													  	 
									<div class="col-6 text-left">
										@if($progressdetails[0]->upload_file_status == 1 && $progressdetails[0]->approval_status != 1)
											<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
											<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
											<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />
											@if(auth()->user()->manager_flag != 2)
												<button type="button" id="postprogresscomment" onclick="funTerm1SubmmitValidate()" class="btn bt_styl">{{ __('Submit') }}</button>	
											@endif
										@endif																	
									</div>												
								</div>								
							@elseif($vEnableManager == 0 && auth()->user()->role_id == 2) 
								<div class="row">
									<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - I In Progress') }}</label>								
								</div>
							@endif								
						@elseif(auth()->user()->role_id == 3)
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Term - I Completion Date') }}:</p>
								</div>						  	 
								<div class="col-7 text-left cht_text">
									<label class="custom-file-upload">
										{{ ($requestdetails[0]->termI_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termI_completion_date)->format('d-m-Y') : "--") }}
									</label>
								</div>												
							</div>										
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Version Proposal') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
									  <span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
										<span class="fileinput-new">Select file</span>
										<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
										<input type="file" name="proposalfile">
									  </span>
									  <span class="fileinput-filename"></span>
									  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>
								</div>												
							</div>	
			
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Version Presentation') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Proposal Rubric') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="proposalrubric">
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>								
								</div>												
							</div>
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes3">
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>								
								</div>												
							</div>
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Other Documents') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="fileinput fileinput-new form_chg" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="otherdocumsnts[]" multiple>
										</span>								 
										<span class="fileinput-filename input-group-append"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>								
								</div>												
							</div>
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-4">
									<p>{{ __('Term Status') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
										<select class="selectpicker pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">																				
										<option value="0">{{ __('In Progress') }}</option>									
										<option value="1">{{ __('Completed') }}</option>									
										</select>
										@include('alerts.feedback', ['field' => 'submmission_status'])
									</div>																	
								</div>												
							</div>
							
							<div class="row" id="divCompletionMessage" style="display:none">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Completion Message') }}:</p>
								</div>						  	 
								<div class="col-6 text-left">
									<div class="form-group view_word {{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
										<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Completion Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
										@include('alerts.feedback', ['field' => 'completionmessage'])
									</div>																	
								</div>												
							</div>
							
							<div class="row">
								<div class="col-5">&nbsp;</div>													  	 
								<div class="col-6 text-left">
									<input type="hidden" name="recadd" id="recadd" value="1" /> 
									<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
									<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
									<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
									<button type="button" id="postprogresscomment" onclick="funTerm1SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>																	
								</div>												
							</div>				
						@else
							<div class="row">
								<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - I In Progress') }}</label>								
							</div>
						@endif
					</div>
				  </div>
                </div>
                <div class="card-collapse">
				  <div class="card-header" role="tab" id="headingTwo">
						<h5 class="mb-0">
							@if($requestdetails[0]->progress_completion == 0)
								<a data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="collapsed text-center font-weight-bold">
							@else
								<a data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="collapsed text-center font-weight-bold">
							@endif
								@if(count($term2progressdetails) > 0)
									@if($term2progressdetails[0]->upload_file_status == 0 && $requestdetails[0]->progress_completion == 1 && $term2progressdetails[0]->approval_status == 0)
										Term - II In Progress
									@elseif( $term2progressdetails[0]->upload_file_status == 1 && $requestdetails[0]->progress_completion == 1 && $term2progressdetails[0]->approval_status != 1)
										Term - II Awating for Manager Aproval
									@elseif( $term2progressdetails[0]->upload_file_status == 0 && $term2progressdetails[0]->approval_status == 2 && $requestdetails[0]->progress_completion == 1)
										Term - II Requested Changes by the Manager
									@elseif($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status == 1)	
										Term - II Completed
									@endif
								@else
									@if($requestdetails[0]->progress_completion == 1)
										Term - II In Progress
									@else
										Term - II Pending
									@endif
								@endif
							<i class="prog_icon material-icons">keyboard_arrow_down</i>
							</a>
						</h5>
					</div>				  
				  
				  @if($requestdetails[0]->progress_completion == 1)
                  <div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
				  @else
				  <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
			      @endif
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
						@endphp						
						@if(count($term2progressdetails) > 0) 
							@foreach ($term2progressdetails as $term2progress)
								@if(auth()->user()->role_id == 4)
									@if($vShow2Date == 0)
										<div class="row">
											<div class="col-2">&nbsp;</div>
											<div class="col-3 form_chg text-right">
												<p>{{ __('Term - II Completion Date') }}:</p>
											</div>						  	 
											<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Term - II Project Document') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
													<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>								
												</div>												
											</div>
										@else
											<div class="row">									
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Term - II Project Document') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
													<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>								
												</div>												
											</div>
										@else
											<div class="row">									
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
													<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>								
												</div>												
											</div>
										@else
											<div class="row">									
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes -3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
													<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>								
												</div>												
											</div>
										@else
											<div class="row">									
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right">
														<p>{{ __('Other Documents') }}:</p>
													</div>						  	 
													<div class="col-7 text-left">
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
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right">
														<p>&nbsp;</p>
													</div>						  	 
													<div class="col-7 text-left">
														<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
															<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
														</div>								
													</div>
												@endif
											</div>										
										@endif
									@endif									
								@endif
								@if((auth()->user()->role_id == 2 && ($term2progress->upload_file_status > 0 || $term2progress->approval_status > 0 )) || auth()->user()->role_id == 3)
									@php
										$vEnableManager = 1;
									@endphp	
									@if($vShow2Date == 0)
										<div class="row">
											<div class="col-2">&nbsp;</div>
											<div class="col-3 form_chg text-right">
												<p>{{ __('Term - II Completion Date') }}:</p>
											</div>						  	 
											<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Term - II Project Document') }}:</p>
												</div>
												<div class="col-7 text-left">
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
														<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Term - II Project Document') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>
												<div class="col-7 text-left">
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
														<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>
												<div class="col-7 text-left">
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
														<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>
												<div class="col-7 text-left">
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
														<span class="fileinput-filename1">&nbsp;&nbsp;<a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
														<div class="col-2">&nbsp;</div>
														<div class="col-3 form_chg text-right pt-2">
															<p>{{ __('Other Documents') }}:</p>
														</div>						  	 
														<div class="col-7 text-left">
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
														<div class="col-2">&nbsp;</div>
														<div class="col-3 form_chg text-right pt-2">
															@if(($term2progress->upload_file_status == 1 && auth()->user()->role_id == 3) || ($term2progress->approval_status == 1 && auth()->user()->role_id == 2))
																<p>{{ __('Other Documents') }}:</p>
															@else
																<p>&nbsp;</p>	
															@endif
														</div>						  	 
														<div class="col-7 text-left pt-2">
															<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
																<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term2progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term2progress->file_name) }}</a></span>															
																<!--<a href="#" onclick="deleteCallback( {{$term2progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
															</div>								
														</div>												
													</div>												
												@endif											
											@else
												<div class="row">													
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right pt-2">
														<p>{{ __('Other Documents') }}:</p>
													</div>						  	 
													<div class="col-7 text-left">
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
							@if($vOthersFound == 0 && (($term2progressdetails[0]->upload_file_status != 1 && auth()->user()->role_id == 3) || (($term2progressdetails[0]->approval_status != 1 && auth()->user()->role_id == 2 && $term2progressdetails[0]->upload_file_status == 1) || ($term2progressdetails[0]->approval_status == 2 && auth()->user()->role_id == 2 && $term2progressdetails[0]->upload_file_status == 0))))
								<div class="row">									
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
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
							@if($vOthersFound == 0 && (auth()->user()->role_id == 4 || ($term2progressdetails[0]->upload_file_status == 1 && auth()->user()->role_id == 3) || ($term2progressdetails[0]->approval_status == 1 && auth()->user()->role_id == 2)))								
								<div class="row">									
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Other Documents') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											{{ __('--') }}
										</div>								
									</div>												
								</div>
							@endif
							@if(auth()->user()->role_id == 4)							
								@if($term2progressdetails[0]->upload_file_status > 0)
								<div class="row">									
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Supervisor Status ') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
										<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
											<p>{{ __('Completed') }}</p>
										</div>								
									</div>												
								</div>								
								@else
									<div class="row">									
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
												<p>{{ __('In Progress') }}</p>
											</div>								
										</div>												
									</div>									
								@endif
								@if($term2progressdetails[0]->approval_status > 0)
									<div class="row">									
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Manager Approval Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
												@if($term2progressdetails[0]->approval_status == 1)
													<p>{{ __('Approved') }}</p>							
												@elseif($term2progressdetails[0]->approval_status == 2)
													<p>{{ __('Requested for Changes') }}</p>												
												@endif
											</div>								
										</div>												
									</div>								
								@elseif($term2progressdetails[0]->upload_file_status == 1)									
									<div class="row">									
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Manager Approval Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
												<p>{{ __('Pending') }}</p>
											</div>								
										</div>												
									</div>
								@endif
							@endif
							@if(auth()->user()->role_id == 3 && ($term2progressdetails[0]->approval_status > 0 || $term2progressdetails[0]->upload_file_status == 1))
								<div class="row">									
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Manager Approval Status ') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Completion Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
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
							@if(auth()->user()->role_id == 3)
								<div class="row">
									@if($term2progressdetails[0]->upload_file_status != 1)
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-4">
										<p>{{ __('Term - II Status') }}:</p>
									</div>						  	 
									<div class="col-7 text-left">
										<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">											
											<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">
												<option value="0" {{ $term2progressdetails[0]->upload_file_status == 0 ? 'selected' : '' }} >{{ __('In Progress') }}</option>									
												<option value="1" {{ $term2progressdetails[0]->upload_file_status == 1  ? 'selected' : '' }} >{{ __('Completed') }}</option>									
											</select>
											@include('alerts.feedback', ['field' => 'submmission_status'])
										</div>								
									</div>
									@else
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Term - II Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<p>{{ __('Completed') }}</p>							
										</div>
									@endif
								</div>							
								@if($term2progressdetails[0]->upload_file_status == 0)
									<div class="row" id="divCompletionMessage" style="display:none">										
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Message') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											<div class="form-group view_word{{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
												<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
												@include('alerts.feedback', ['field' => 'completionmessage'])
											</div>							
										</div>										
									</div>
								@endif
								<div class="row">
									<div class="col-5">&nbsp;</div>													  	 
									<div class="col-6 text-left">
										@if($term2progressdetails[0]->upload_file_status == 0)
										<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
										<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
										<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
										<button type="button" id="postprogresscomment" onclick="funTerm2SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
										@endif
									</div>												
								</div>									
							@endif
						@if($vEnableManager == 1 && auth()->user()->role_id == 2)
							<div class="row">
								@if($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status != 1)
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-4">
									<p>{{ __('Approval Status') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
										<select class="selectpicker pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(1)" data-style="select-with-transition" title="" data-size="100">
											<option value="2" {{ $term2progressdetails[0]->approval_status == 2  ? 'selected' : '' }} >{{ __('Request for Changes') }}</option>											
											<option value="1" {{ $term2progressdetails[0]->approval_status == 1 ? 'selected' : '' }} >{{ __('Approve Term II Completion') }}</option>																			
										</select>
										@include('alerts.feedback', ['field' => 'submmission_status'])
									</div>								
								</div>
								@else
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right">
										<p>{{ __('Approval Status') }}:</p>
									</div>						  	 
									<div class="col-7 cht_text text-left">
										@if($term2progressdetails[0]->upload_file_status == 0 && $term2progressdetails[0]->approval_status == 2)
											<p>{{ __('Requested for Changes') }}</p>											
										@elseif($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status ==1)
											<p>{{ __('Approved') }}</p>	
										@endif
									</div>
								@endif
							</div>
							@if($term2progressdetails[0]->upload_file_status == 1 && $term2progressdetails[0]->approval_status != 1 )
								<div class="row" id="divCompletionMessage">								
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-4">
										<p>{{ __('Message') }}:</p>
									</div>						  	 
									<div class="col-6 text-left">
										<div class="form-group view_word {{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
											<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
											@include('alerts.feedback', ['field' => 'completionmessage'])
										</div>							
									</div>																
								</div>
							@endif
							<div class="row">
								<div class="col-5">&nbsp;</div>													  	 
								<div class="col-6 text-left">
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
							<div class="row">
								<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - II In Progress') }}</label>								
							</div>
						@endif						
							
						@elseif(auth()->user()->role_id == 3 && $requestdetails[0]->progress_completion == 1 )	
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Term - II Completion Date') }}:</p>
								</div>						  	 
								<div class="col-7 text-left cht_text">
									<label class="custom-file-upload">
										{{ ($requestdetails[0]->termII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termII_completion_date)->format('d-m-Y') : "--") }}
									</label>
								</div>												
							</div>
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Term - II Project Document') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Other Documents') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Term - II Status') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
										<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">																													
											<option value="0">{{ __('In Progress') }}</option>									
											<option value="1">{{ __('Completed') }}</option>									
										</select>
										@include('alerts.feedback', ['field' => 'submmission_status'])
									</div>								
								</div>												
							</div>	

							<div class="row" id="divCompletionMessage" style="display:none">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Message') }}:</p>
								</div>						  	 
								<div class="col-6 text-left">
									<div class="form-group view_word {{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
										<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
										@include('alerts.feedback', ['field' => 'completionmessage'])
									</div>								
								</div>												
							</div>	
							
							<div class="row">
								<div class="col-5">&nbsp;</div>													  	 
								<div class="col-6 text-left">
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
                  </div>
                </div>
                <div class="card-collapse">
				   <div class="card-header" role="tab" id="headingThree">
						<h5 class="mb-0">
							@if($requestdetails[0]->progress_completion == 0)
								<a data-toggle="collapse" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree" class="collapsed text-center font-weight-bold">
							@else
								<a data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="collapsed text-center font-weight-bold">
							@endif
								@if(count($term3progressdetails) > 0)
									@if($term3progressdetails[0]->upload_file_status == 0 && $requestdetails[0]->progress_completion == 2 && $term3progressdetails[0]->approval_status == 0)
										Term - III In progress
									@elseif( $term3progressdetails[0]->upload_file_status == 1 && $requestdetails[0]->progress_completion == 2 && $term3progressdetails[0]->approval_status != 1)
										Term - III Awating for Manager Aproval
									@elseif( $term3progressdetails[0]->upload_file_status == 0 && $requestdetails[0]->progress_completion == 2 && $term3progressdetails[0]->approval_status == 2)
										Term - III Requested Changes by the Manager
									@elseif($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status == 1)	
										Term - III Completed
									@endif
								@else
									@if($requestdetails[0]->progress_completion == 2)
										Term - III In Progress
									@else
										Term - III Pending
									@endif
								@endif
							<i class="material-icons">keyboard_arrow_down</i>
							</a>
						</h5>
					</div>	
				  
				  @if($requestdetails[0]->progress_completion >= 2)
                  <div id="collapseThree" class="collapse show" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
				  @else
				  <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">	  
				  @endif
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
						@endphp						
						@if(count($term3progressdetails) > 0)							
							@foreach ($term3progressdetails as $term3progress)	
								@if(auth()->user()->role_id == 4)
									@if($vShow3Date == 0)
										<div class="row">
											<div class="col-2">&nbsp;</div>
											<div class="col-3 form_chg text-right">
												<p>{{ __('Term - III Completion Date') }}:</p>
											</div>						  	 
											<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Report') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>											
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Report') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>											
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Report Rubric') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>											
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Report Rubric') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>											
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>											
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left cht_text">
													<label class="custom-file-upload">
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</label>
												</div>												
										    </div>											
										@else
											<div class="row">
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right">
														<p>{{ __('Other Documents') }}:</p>
													</div>						  	 
													<div class="col-7 text-left cht_text">
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
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right">
														<p>&nbsp;</p>
													</div>						  	 
													<div class="col-7 text-left cht_text">
														<label class="custom-file-upload">
															<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
														</label>
													</div>
												@endif
											</div>											
										@endif
									@endif																		
								@endif
								@if((auth()->user()->role_id == 2 && ($term3progress->upload_file_status > 0 || $term3progress->approval_status > 0 )) || auth()->user()->role_id == 3)
									@php
										$vEnableManager = 1;
									@endphp
									@if($vShow3Date == 0)
										<div class="row">
											<div class="col-2">&nbsp;</div>
											<div class="col-3 form_chg text-right">
												<p>{{ __('Term - III Completion Date') }}:</p>
											</div>						  	 
											<div class="col-7 text-left cht_text">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Report') }}:</p>
												</div>	
												@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else											
											<div class="row">
												<input type="hidden" name="proposalfile_flag" id="proposalfile_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Report') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>	
												@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else											
											<div class="row">
												<input type="hidden" name="presentationfile_flag" id="presentationfile_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Final Version Presentation') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Report Rubric') }}:</p>
												</div>	
												@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else											
											<div class="row">
												<input type="hidden" name="proposalrubric_flag" id="proposalrubric_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Report Rubric') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>	
												@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else											
											<div class="row">
												<input type="hidden" name="minutes1_flag" id="minutes1_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 1') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>	
												@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else											
											<div class="row">
												<input type="hidden" name="minutes2_flag" id="minutes2_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 2') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>	
												@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))
													<div class="col-7 text-left">
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
													<div class="col-7 cht_text pt-1">												 														
														<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>	
													</div>
												@endif
											</div>
										@else											
											<div class="row">
												<input type="hidden" name="minutes3_flag" id="minutes3_flag" value="0" /> 
												<div class="col-2">&nbsp;</div>
												<div class="col-3 form_chg text-right pt-2">
													<p>{{ __('Meeting Minutes - 3') }}:</p>
												</div>						  	 
												<div class="col-7 text-left">
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
									@if($term3progress->document_type == "otherdocumsnts")
										@if($vOthersFound == 0)
											@if(!empty($term3progress->document_file_path))											
												@if($term3progress->upload_file_status == 0 || (auth()->user()->role_id == 2 && $term3progress->approval_status != 1))													
													<div class="row">														
														<div class="col-2">&nbsp;</div>
														<div class="col-3 form_chg text-right pt-2">
															<p>{{ __('Other Documents') }}:</p>
														</div>						  	 
														<div class="col-7 text-left">
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
														<div class="col-2">&nbsp;</div>
														@if(($term3progress->upload_file_status == 1 && auth()->user()->role_id == 3) || ($term3progress->approval_status == 1 && auth()->user()->role_id == 2))
															<div class="col-3 form_chg text-right pt-2">
																<p>{{ __('Other Documents') }}:</p>
															</div>
														@else
															<div class="col-3 form_chg text-right pt-2">
																<p>&nbsp;</p>
															</div>
														@endif
														<div class="col-7 text-left">															
															<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term3progress->file_name) }}</a></span>															
															<!--<a href="#" onclick="deleteCallback( {{$term3progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
														</div>												
													</div>												
												@endif
											@else
												<div class="row">														
													<div class="col-2">&nbsp;</div>
													<div class="col-3 form_chg text-right pt-2">
														<p>{{ __('Other Documents') }}:</p>
													</div>						  	 
													<div class="col-7 text-left">
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
													<div class="col-2">&nbsp;</div>														
													<div class="col-3 form_chg text-right pt-2">
														<p>&nbsp;</p>
													</div>														
													<div class="col-7 text-left">															
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __($term3progress->file_name) }}</a></span>															
														<!--<a href="#" onclick="deleteCallback( {{$term3progress}} )" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>-->
													</div>
												@endif													
											</div>
										@endif
									@endif
									@if($term3progress->approval_status == 1)										
										@if($term3progress->document_type == "presentationrubric")
											<div class="row">
												<div class="col-2">&nbsp;</div>														
													<div class="col-3 form_chg text-right pt-2">
														<p>{{ __('Final Presentation Rubric') }}:</p>
													</div>														
													<div class="col-7 text-left">															
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>																													
													</div>
											</div>											
										@endif
										@if($term3progress->document_type == "finalreportrubric")
											<div class="row">
												<div class="col-2">&nbsp;</div>														
													<div class="col-3 form_chg text-right pt-2">
														<p>{{ __('Final Report Rubric') }}:</p>
													</div>														
													<div class="col-7 text-left">															
														<span class="fileinput-new cht_text"><a href="{{ route('download.viewfile', [$term3progress->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>																													
													</div>
											</div>											
										@endif
									@endif
								@endif
							@endforeach	
							@if($vOthersFound == 0)								
								@if((auth()->user()->role_id == 3 && $term3progressdetails[0]->upload_file_status == 0) || ( auth()->user()->role_id == 2 && (($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status != 1) || ($term3progressdetails[0]->upload_file_status == 0 && $term3progressdetails[0]->approval_status == 2))))
									<div class="row">														
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Other Documents') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
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
								@elseif(auth()->user()->role_id == 4 || ($term3progressdetails[0]->upload_file_status == 1 && auth()->user()->role_id == 3) || ($term3progressdetails[0]->approval_status == 1 && auth()->user()->role_id == 2))								
									<div class="row">														
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Other Documents') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
											 <span class="fileinput-filename">--</span>											 
										</div>												
									</div>
								@endif
							@endif
							@if(auth()->user()->role_id == 4)							
								@if($term3progressdetails[0]->upload_file_status > 0)
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">											
											<span class="fileinput-new cht_text">{{ __('Completed') }}</span>												
										</div>												
									</div>								
								@else
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">											
												<span class="fileinput-new cht_text">{{ __('In Progress') }}</span>	
										</div>												
									</div>									
								@endif
								@if($term3progressdetails[0]->approval_status > 0)
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Manager Approval Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">											
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Manager Approval Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text ">
											<p>{{ __('Pending') }}</p>																																			
										</div>												
									</div>									
								@endif
							@endif							
							@if($term3progressdetails[0]->approval_status > 0)
								@if(auth()->user()->role_id == 3)
									<div class="row">
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Manager Approval Status ') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">											
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Supervisor Completion Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">											
												@if($term3progressdetails[0]->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
												<p>{{ __('Change Request In Progress') }}</p>																						
											@elseif($term3progressdetails[0]->upload_file_status == 1)
												<p>{{ __('Completed') }}</p>		
											@endif											
										</div>												
									</div>																		
								@endif
							@endif
							@if(auth()->user()->role_id == 3)								
								<div class="row">
									@if($term3progressdetails[0]->upload_file_status != 1)
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-4">
											<p>{{ __('Term - III Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">
											<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
												<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">
													<option value="0" {{ $term3progressdetails[0]->upload_file_status == 0 ? 'selected' : '' }} >{{ __('In Progress') }}</option>									
													<option value="1" {{ $term3progressdetails[0]->upload_file_status == 1  ? 'selected' : '' }} >{{ __('Completed') }}</option>									
												</select>
												@include('alerts.feedback', ['field' => 'submmission_status'])
											</div>
										</div>
									@else
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Term - III Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">																																	
											<p>{{ __('Completed') }}</p>													
										</div>
									@endif										
								</div>
								
								@if($requestdetails[0]->progress_completion == 2)
									<div class="row" id="divCompletionMessage" style="display:none">
										<div class="col-2">&nbsp;</div>
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
								@endif
								<div class="row">
									<div class="col-5">&nbsp;</div>													  	 
									<div class="col-6 text-left">
										@if($term3progressdetails[0]->upload_file_status == 0)
										<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
										<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
										<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
										<button type="button" id="postprogresscomment" onclick="funTerm3SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
										@endif
									</div>												
								</div>								
							@endif
							@if($vEnableManager == 1 && auth()->user()->role_id == 2)
								<div class="row">
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('External Review Completed') }}:</p>
									</div>						  	 
									<div class="col-6 text-left cht_text">
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
									<div class="col-2">&nbsp;</div>
									<div class="col-3 form_chg text-right pt-2">
										<p>{{ __('Defense Completed') }}:</p>
									</div>						  	 
									<div class="col-6 text-left cht_text">
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-4">
											<p>{{ __('Term - III Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">
											<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
												<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(2)" data-style="select-with-transition" title="" data-size="100">
													@if($term3progressdetails[0]->approval_status != 2)
														<option value="2" {{ ($term3progressdetails[0]->approval_status == 2) ? 'selected' : '' }} >{{ __('Request for Changes') }}</option>	
														<option value="1" {{ $term3progressdetails[0]->approval_status == 1 ? 'selected' : '' }} >{{ __('Approve Term III Completion') }}</option>	
														<option value="3" {{ $requestdetails[0]->review_flag == 3 ? 'selected' : '' }} >{{ __('External Review In Progress') }}</option>	
														<option value="4" {{ $requestdetails[0]->review_flag == 4 ? 'selected' : '' }} >{{ __('Defense In Progress') }}</option>	
													@else
														<option value="2">{{ __('Request for Changes') }}</option>	
														<option value="1">{{ __('Approve Term III Completion') }}</option>	
														<option value="3" {{ $requestdetails[0]->review_flag == 3 ? 'selected' : '' }}>{{ __('External Review In Progress') }}</option>	
														<option value="4" {{ $requestdetails[0]->review_flag == 4 ? 'selected' : '' }}>{{ __('Defense In Progress') }}</option>	
													@endif									
												</select>
												@include('alerts.feedback', ['field' => 'submmission_status'])
											</div>
										</div>
									@else
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right">
											<p>{{ __('Approval Status') }}:</p>
										</div>						  	 
										<div class="col-7 text-left cht_text">
											@if($term3progressdetails[0]->approval_status == 2 && $term3progressdetails[0]->upload_file_status == 0)
												<p>{{ __('Requested for Changes') }}</p>
											@else
												<p>{{ __('Approved') }}</p>
											@endif
										</div>
									@endif										
								</div>
								@if($term3progressdetails[0]->upload_file_status == 1 && $term3progressdetails[0]->approval_status != 1)									
									<div class="row" id="divCompletionMessage">
										<div class="col-2">&nbsp;</div>
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
									<div class="row" id="divPresentationRubric" style="display:none;">														
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Final Presentation Rubric') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
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
										<div class="col-2">&nbsp;</div>
										<div class="col-3 form_chg text-right pt-2">
											<p>{{ __('Final Report Rubric') }}:</p>
										</div>						  	 
										<div class="col-7 text-left">
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
									<div class="col-5">&nbsp;</div>													  	 
									<div class="col-6 text-left">
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
								<div class="row">
									<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - III In Progress') }}</label>								
								</div>
							@endif							
						@elseif(auth()->user()->role_id == 3 && $requestdetails[0]->progress_completion >= 2)
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right">
									<p>{{ __('Term - III Completion Date') }}:</p>
								</div>						  	 
								<div class="col-7 text-left cht_text">
									<label class="custom-file-upload">
										{{ ($requestdetails[0]->termIII_completion_date ? \Carbon\Carbon::parse($requestdetails[0]->termIII_completion_date)->format('d-m-Y') : "--") }}
									</label>
								</div>												
							</div>
									
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Version Report') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="proposalfile">
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>								
								</div>												
							</div>
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Final Version Presentation') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Report Rubric') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="proposalrubric">
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>								
								</div>												
							</div>

							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 1') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 2') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Meeting Minutes - 3') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="fileinput fileinput-new cht_text" data-provides="fileinput">
										<span class="btn btn-outline-secondary btn-file" style="line-height: 0.20">
											<span class="fileinput-new">Select file</span>
											<span class="fileinput-exists" style="line-height: 0.20">Change File</span>
											<input type="file" name="minutes3">
										</span>
										<span class="fileinput-filename"></span>
										<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
									</div>								
								</div>												
							</div>
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-2">
									<p>{{ __('Other Documents') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
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
							
							<div class="row">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-4">
									<p>{{ __('Term Status') }}:</p>
								</div>						  	 
								<div class="col-7 text-left">
									<div class="form-group view_word {{ $errors->has('submmission_status') ? ' has-danger' : '' }}">
										<select class="selectpicker col-sm-5 pl-0 pr-0" name="submmission_status" id="submmission_status" onchange="funUpdateTermCompletiontStatus(0)" data-style="select-with-transition" title="" data-size="100">																				
										<option value="0">{{ __('In Progress') }}</option>									
										<option value="1">{{ __('Completed') }}</option>									
										</select>
										@include('alerts.feedback', ['field' => 'submmission_status'])
									</div>								
								</div>												
							</div>
							
							<div class="row" id="divCompletionMessage" style="display:none">
								<div class="col-2">&nbsp;</div>
								<div class="col-3 form_chg text-right pt-4">
									<p>{{ __('Completion Message') }}:</p>
								</div>						  	 
								<div class="col-6 text-left">
									<div class="form-group view_word {{ $errors->has('completionmessage') ? ' has-danger' : '' }}">
										<textarea name="completionmessage" id="completionmessage" cols="35" rows="5" class="form-control{{ $errors->has('completionmessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Completion Message') }}" value="{{ old('completionmessage') }}">{{ old('completionmessage') }}</textarea>
										@include('alerts.feedback', ['field' => 'completionmessage'])
									</div>								
								</div>												
							</div>
							
							<div class="row">
								<div class="col-5">&nbsp;</div>													  	 
								<div class="col-6 text-left">
									<input type="hidden" name="recadd" id="recadd" value="1" />
									<input type="hidden" name="statsupdate_comments" id="statsupdate_comments" value="0" />
									<input type="hidden" name="statusupdate_track_id" id="statusupdate_track_id" value="0" />	
									<input type="hidden" name="checklist_type" id="checklist_type" value="{{ ($requestdetails[0]->progress_completion+1) }}" />	
									<button type="button" id="postprogresscomment" onclick="funTerm3SubmmitValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
								</div>												
							</div>														
						@else
							@if($requestdetails[0]->progress_completion == 2)
								<div class="row">
									<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - III In Progress') }}</label>								
								</div>
							@else
								<div class="row">
									<label class="col-sm-3 col-form-label" style="padding-top:20px;">{{ __('Term - III Pending') }}</label>								
								</div>
							@endif							
						@endif
						
						
						
                    </div>
                  </div>
                </div>
				<div  class="pt-5 text-center">
					@if(request()->get('action') == 'ac')
						<a href="{{ route('item.archive') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
					@else
						<a href="{{ route('item.archive') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
					@endif
				 </div>
              </div>
            </div>
          </div>
        </div>        
      </div>
@push('js')
<script src="{{ asset('material') }}/uploader/jquery.uploadfile.min.js"></script>
<script src="{{ asset('material') }}/wnumb/wNumb.min.js"></script>
<script>

  $(document).ready(function() {
    $('.datetimepicker').datetimepicker({
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
      format: 'DD-MM-YYYY'
    });
	tinymce.init({
		selector: '#completionmessage',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});	
	vProgressPath = '{!! $successPath !!}';
	
	$("#tablink2").click( function() {
		window.setTimeout( function() {
		$('#postcomment').focus();},200);	
	});
	var tabnum =  getParameterByName('tab');
	if(tabnum == 2) {		
		$('#link1').removeClass("active");
		$('#link2').addClass("active");					
		$('#tablink1').removeClass("active show");			
		$('#tablink2').addClass("active show");	
		$('#postcomment').focus();	
	}
	else if(tabnum == 4) {
		$('#link1').removeClass("active");
		$('#link4').addClass("active");					
		$('#tablink1').removeClass("active show");			
		$('#tablink4').addClass("active show");	
		$('#postcomment').focus();	
	}
  });
  
	function funTerm1SubmmitValidate() {		
		var userRoleID = {{auth()->user()->role_id}};
		if($("#submmission_status").val() != "") {
			if($("#submmission_status").val() == 1) {
				if(userRoleID == 3) {
					if($("#proposalfile").val() == "" && $("#proposalfile_flag").val() == 0) {
						swal({
							title: "Please choose proposal file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						$("#proposalfile .ajax-upload-dragdrop").focus();
						return false;
					}
					else if($("#presentationfile").val() == "" && $("#presentationfile_flag").val() == 0) {
						swal({
							title: "Please choose presentaion file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#proposalrubric").val() == "" && $("#proposalrubric_flag").val() == 0) {
						swal({
							title: "Please choose poposal rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes1").val() == "" && $("#minutes1_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 1 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes2").val() == "" && $("#minutes2_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 2 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes3").val() == "" && $("#minutes3_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 3 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				else {
					if($('#presentationrubric').val() == "") {						
						swal("", "Please choose presentaion rubric file!", "error").then((result) => {
						  $('#presentationrubric').focus();
						});	
					}
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				
			}
			else if($("#submmission_status").val() == 2) {
				if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else {				
				$("#statsupdate_comments").val(1);
				document.frmCommentUpdate.method='POST';
				document.frmCommentUpdate.action=vProgressPath;
				document.frmCommentUpdate.submit();
			}
		}		
	}
	
	function funTerm2SubmmitValidate() {		
		var userRoleID = {{auth()->user()->role_id}};		
		if($("#submmission_status").val() == 1) {
			if(userRoleID == 3) {					
				if($("#presentationfile").val() == "" && $("#presentationfile_flag").val() == 0) {
					swal({
						title: "Please choose presentaion file(s)",
						text: '',
						type: 'error',
						confirmButtonColor: '#47a44b'
					})
					return false;
				}
				else if($("#minutes1").val() == "" && $("#minutes1_flag").val() == 0) {
					swal({
						title: "Please choose meeting minutes 1 file(s)",
						text: '',
						type: 'error',
						confirmButtonColor: '#47a44b'
					})
					return false;
				}
				else if($("#minutes2").val() == "" && $("#minutes2_flag").val() == 0) {
					swal({
						title: "Please choose meeting minutes 2 file(s)",
						text: '',
						type: 'error',
						confirmButtonColor: '#47a44b'
					})
					return false;
				}
				else if($("#minutes3").val() == "" && $("#minutes3_flag").val() == 0) {
					swal({
						title: "Please choose meeting minutes 3 file(s)",
						text: '',
						type: 'error',
						confirmButtonColor: '#47a44b'
					})
					return false;
				}
				else if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else {
				if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}				
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
		
		}
		else if($("#submmission_status").val() == 2) {
			if($('#completionmessage').val() == "") {
				swal("", "Please enter message!", "error").then((result) => {
				  tinymce.EditorManager.get('completionmessage').focus();
				});				
			}
			else {
				$("#statsupdate_comments").val(1);
				document.frmCommentUpdate.method='POST';
				document.frmCommentUpdate.action=vProgressPath;
				document.frmCommentUpdate.submit();
			}
		}
		else {			
			$("#statsupdate_comments").val(1);
			document.frmCommentUpdate.method='POST';
			document.frmCommentUpdate.action=vProgressPath;
			document.frmCommentUpdate.submit();
		}		
	}

	
	function funTerm3SubmmitValidate() {		
		var userRoleID = {{auth()->user()->role_id}};		
		if($("#submmission_status").val() != "") {
			if($("#submmission_status").val() == 1) {
				if(userRoleID == 3) {
					if($("#proposalfile").val() == "" && $("#proposalfile_flag").val() == 0) {
						swal({
							title: "Please choose proposal file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						$("#proposalfile .ajax-upload-dragdrop").focus();
						return false;
					}
					else if($("#presentationfile").val() == "" && $("#presentationfile_flag").val() == 0) {
						swal({
							title: "Please choose presentaion file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#proposalrubric").val() == "" && $("#proposalrubric_flag").val() == 0) {
						swal({
							title: "Please choose poposal rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes1").val() == "" && $("#minutes1_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 1 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes2").val() == "" && $("#minutes2_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 2 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($("#minutes3").val() == "" && $("#minutes3_flag").val() == 0) {
						swal({
							title: "Please choose meeting minutes 3 file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				else {
					if($('input:radio[name="external_review"]:checked').val() != 1) {
						swal("", "Please choose 'YES' to make it external review completed!", "error").then((result) => {
							$('input:radio[name="external_review"]').focus();
						});							
						return false;
					}
					else if($('input:radio[name="defence_status"]:checked').val() != 1) {
						swal("", "Please choose 'YES' to make it defense completed !", "error").then((result) => {
							$('input:radio[name="external_review"]').focus();
						});	
						return false;
					}
					else if($('#completionmessage').val() == "") {
						swal("", "Please enter message!", "error").then((result) => {
						  tinymce.EditorManager.get('completionmessage').focus();
						});				
					}
					else if($('#presentationrubric').val() == "") {
						swal({
							title: "Please choose Presentation Rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}
					else if($('#finalreportrubric').val() == "") {
						swal({
							title: "Please choose Report Rubric file(s)",
							text: '',
							type: 'error',
							confirmButtonColor: '#47a44b'
						})
						return false;
					}					
					else {
						$("#statsupdate_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vProgressPath;
						document.frmCommentUpdate.submit();
					}
				}
				
			}
			else if($("#submmission_status").val() == 2) {
				if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else if($("#submmission_status").val() == 3) {								
				if($('input:radio[name="external_review"]:checked').length == 0) {
					swal("", "Please choose external review completion status!", "error").then((result) => {
					  $('input:radio[name="external_review"]').focus();
					});	
				}
				else if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else if($("#submmission_status").val() == 4) {				
				if($('input:radio[name="defence_status"]:checked').length == 0) {
					swal("", "Please choose defense completion status!", "error").then((result) => {
					  $('input:radio[name="defence_status"]').focus();
					});	
				}
				else if($('#completionmessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('completionmessage').focus();
					});				
				}
				else {
					$("#statsupdate_comments").val(1);
					document.frmCommentUpdate.method='POST';
					document.frmCommentUpdate.action=vProgressPath;
					document.frmCommentUpdate.submit();
				}
			}
			else {			
				$("#statsupdate_comments").val(1);
				document.frmCommentUpdate.method='POST';
				document.frmCommentUpdate.action=vProgressPath;
				document.frmCommentUpdate.submit();
			}
		}						
	}
	
	
	function funUpdateTermCompletiontStatus(pmValue) {		
		var optValue = $("#submmission_status").val();
		if(pmValue == 0) {
			if(parseInt(optValue) == 1) {			
				$("#divCompletionMessage").show();
			}
			else {
				$("#divCompletionMessage").hide();			
			}
		}
		else if(pmValue == 1) {			
			if(parseInt(optValue)== 1) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").show();
			}
			else if(parseInt(optValue)== 2) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
			}
			else {
				$("#divCompletionMessage").hide();
				$("#divPresentationRubric").hide();
			}
		}
		else {
			if(parseInt(optValue)== 1) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").show();
				$("#divFinalReportRubric").show();
			}
			else if(parseInt(optValue)== 2) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
			}
			else if(parseInt(optValue)== 3) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
			}
			else if(parseInt(optValue)== 4) {
				$("#divCompletionMessage").show();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
			}
			else {
				$("#divCompletionMessage").hide();
				$("#divPresentationRubric").hide();
				$("#divFinalReportRubric").hide();
			}
		}
	}
	
	function funShowBrowseBtn(pmName,value) {		
		if(value == 1) {
			$("#"+pmName+"file").show();
			$("#"+pmName+"link").hide();
		}
		else {
			$("#"+pmName+"file").hide();
			$("#"+pmName+"link").show();
		}
		return false;
	}
	
	function deleteCallback(data) {
		$.ajax({
			type: "POST",
			url: vProgressPath,
			dataType: 'json',
			data: {
				"_token": "{{ csrf_token() }}",
				"field_name": 'otherdocumsnts',						
				"name": data,						
				"delete_flag": 1
			},
			success: function( msg ) {

			}
		});
	}
  </script>
@endpush