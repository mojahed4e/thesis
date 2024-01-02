@if(auth()->user()->role_id == 2 && ($item->assigned_to != auth()->user()->id || (($item->assigned_to == auth()->user()->id && $requestdetails[0]->completion_by_supervisor == 2 && $requestdetails[0]->completion_by_manager == 1) || (($requestdetails[0]->completion_by_supervisor == 3 && $requestdetails[0]->completion_by_manager == 2)))))
	@php
	$header = array('activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Management'));
	$pagetitle = 'Thesis Details';
	@endphp
@elseif(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id ))
	@php
	$header = array('activePage' => 'item-assigned', 'menuParent' => 'laravel', 'titlePage' => __('Assigned Thesis Management'));
	$pagetitle = 'Thesis Details';
	@endphp
@else
	@php
	$header = array('activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('My Thesis'));
	$pagetitle = 'My Thesis Details';
	@endphp
@endif

@extends('layouts.app',  $header )

@section('content')
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			<form method="post" enctype="multipart/form-data" name="frmCommentUpdate" id="frmCommentUpdate" action="{{ url($upload) }}" autocomplete="off" class="form-horizontal">
			<input type="hidden" name="meeting_log_seq" id="meeting_log_seq" value="0" />
			<input type="hidden" name="term_seq" id="term_seq" value="0" />
			<input type="hidden" name="viewflag" id="viewflag" value="0">
			<input type="hidden" name="rubricterm" id="rubricterm" value="">
			<input type="hidden" name="rubrictype" id="rubrictype" value="">
			<input type="hidden" name="rubriccreatedby" id="rubriccreatedby" value="">
			<input type="hidden" name="panelmembercount" id="panelmembercount" value="{{count($panelmembers)}}">
			@csrf					
			@method('post')
				<div class="card ">					
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">					  						
								<div class="card-body ">
									<ul class="nav nav-pills nav-pills-primary nav-pills-icons justify-content-center" role="tablist">
										<li class="nav-item">
										<a class="nav-link active act_style" data-toggle="tab" href="#link1" id="tablink1" role="tablist">
										 Thesis Details
										</a>										
										</li>	
										@if($item->approval_status == 1 && $requestdetails[0]->supervisor_acceptence_status == 1)
										<li class="nav-item">
										<a class="nav-link act_style" data-toggle="tab" href="#link4" id="tablink4" role="tablist">
										 Progress Tracking
										</a>
										</li>
										@endif
										<li class="nav-item">
										<a class="nav-link act_style" data-toggle="tab" href="#link2" id="tablink2" role="tablist">
										Posted Message(s)
										</a>
										</li>						  
										<li class="nav-item">
										<a class="nav-link act_style" data-toggle="tab" href="#link3" id="tablink3" role="tablist">
										Uploaded File(s)
										</a>
										</li>						  
									</ul>
									<div class="tab-content tab-space">
										<div class="tab-pane active" id="link1">

											<dir class="row">
												<div class="col-md-6">
													<table class="table">    
														<tbody>
														<tr>
															<td class="cbold_text">{{ __('Title') }}</td>
															<td class="sid_text">{{ $item->name }}</td>
														</tr>
														<tr>
															<td class="cbold_text">{{ __('Keywords') }}</td>
															<td class="sid_text">
															@php
                          										$itemtags = Illuminate\Support\Facades\DB::table('item_tag')->select('tags.*')
            															->join('tags','tags.id','=','item_tag.tag_id')
            															->where(['item_id' => $item->id,'item_tag.status' => 1])->get();
                      											$tagSeq = 0;
                      										@endphp
															@if(count($itemtags) > 0) 
																@foreach($itemtags as $keyword)                    	
								                        		    @if($tagSeq == 0)
										                                {{ $keyword->name }}
										                            @else
									                            		,&nbsp;&nbsp;{{ $keyword->name }}
									                            	@endif
									                          		@php
									                          			$tagSeq++;
									                          		@endphp
									                          	@endforeach
								                          	@endif  
															</td>
														</tr>
														<tr>
															<td class="cbold_text">{{ __('Requested By') }}</td>
															<td class="sid_text">{{$groupowner[0]->name}}</td>
														</tr>		
														@if($item->term_id < 22)
															 <tr>
																<td class="cbold_text">{{ __('Approval Status') }}</td>
																<td class="sid_text">
																	@if($item->approval_status == 1)
																		{{__('Approved')}}
																	@else
																		{{__('Pending')}}	
																	@endif
																</td>
															</tr>
														@endif
														<tr>
															<td class="cbold_text">{{ __('Project By') }}</td>
															<td class="sid_text">
																@php
											                    $aUserInfo = \App\User::select('users.name')->where(['users.id' => $item->created_by])->get();
											                    @endphp                      
											                    @if(count($aUserInfo) > 0)
											                      {{ $aUserInfo[0]->name }}
											                    @endif
															</td>
														</tr>

														@if($item->term_id > 21)
														 <tr>
															<td class="cbold_text">{{ __('Approval Status') }}</td>
															<td class="sid_text">
																@if($item->approval_status == 1)
																	{{__('Approved')}}
																@else
																	{{__('Pending')}}	
																@endif
															</td>
														</tr>
														@endif
														</tbody>
													</table>
												</div>												
												<div class="col-md-6">
														<table class="table">
															<tbody>
															<tr>
																<td class="cbold_text">{{ __('Program') }}</td>
																<td class="sid_text">
																	@php
																	$aProgramInfo = \App\Program::where(['programs.id' => $item->program_id])->get();
																	@endphp
																	{{ ($aProgramInfo[0]->description ? $aProgramInfo[0]->description : "--") }}	
																</td>
															</tr>
															<tr>
																<td class="cbold_text">{{ __('Cohort') }}</td>
																<td class="sid_text">
																	@foreach ($terms as $term)
																		@if($term->id ==  $item->term_id)
																			{{ $term->name }}
																		@endif
																	@endforeach	
																</td>
															</tr>
															<tr>
																@if($item->approval_status == 1)
																	<td class="cbold_text">{{ __('Assigned Supervisor') }}</td>					
																@else
																	<td class="cbold_text">{{ __('Prefered Supervisor') }}</td>					
																@endif									
																	<td class="sid_text">@if(count($prefsupervisor) > 0)
																		{{ $prefsupervisor[0]->name }}
																	@else
																		--
																	@endif
																	</td>
															</tr>								
															<tr>
																<td class="cbold_text">{{ __('Supervisor Accept Status') }}</td>
																<td class="sid_text">
																	@if($requestdetails[0]->supervisor_acceptence_status == 1)
																		{{__('Accepted')}}
																	@else
																		{{__('Pending')}}	
																	@endif
																</td>
															</tr>
															<tr>
															<td class="cbold_text">&nbsp;</td>
															<td class="sid_text">
																&nbsp;
															</td>
														</tr>
														</tbody>
													</table>
												</div>         
											</dir>

											<div class="row pl-5">
												<h6 class="cbold_text pb-2 text-capitalize">{{ __('Description') }}&nbsp;&nbsp;</h6>
												<div class="sid_text pt-3 pl-4 col-md-10" style=" text-align:justify;">{!! $item->description !!}</div>
											</div>
											<div class="row pl-5">
												<h6 class="cbold_text pb-2 text-capitalize">{{ __('Aim') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h6>
												<div class="sid_text pt-3 pl-5 col-md-10" style=" text-align:justify;">{!! $item->aim !!}</div>
											</div>
											<div class="row pl-5">
												<h6 class="cbold_text pb-2 text-capitalize">{{ __('Objectives') }}</h6>
												<div class="sid_text pt-3 pl-5 col-md-10" style=" text-align:justify;">{!! $item->objectives !!}</div>
											</div> 
											
											@php
												if($requestdetails[0]->supervisor_acceptence_status == 0  || request()->get('action') != 'ac') 
													$vDispText = 'style="display:none"';
												else 
													$vDispText = 'style="display:block"';
											@endphp
											<div id="divShowTermDates" {{ $vDispText  }} >
												<div class="row">
												  <label class="col-sm-3 col-form-label cht_text">{{ __('Term - I Completion Date') }}</label>
												  <div class="col-sm-3">
													<div class="form-group view_word {{ $errors->has('term1date') ? ' has-danger' : '' }}">
														<input type="text"  name="term1date" id="term1date" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1date', ($timelineinfo[0]->term1_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term1_completion)->format('d-m-Y') : '')) }}"/>						
														@include('alerts.feedback', ['field' => 'term1date'])
													</div>
												  </div>
												</div>
												<div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 1 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1meet1') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1meet1" id="term1meet1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet1', ($timelineinfo[0]->t1_meeting_minutes1 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes1)->format('d-m-Y') : '')) }}"/>            
								                      @include('alerts.feedback', ['field' => 'term1meet1'])
								                    </div>
								                  </div>                                    
								                </div>
												<div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 2 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1meet2') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1meet2" id="term1meet2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet2', ($timelineinfo[0]->t1_meeting_minutes2 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes2)->format('d-m-Y') : '')) }}"/>            
								                      @include('alerts.feedback', ['field' => 'term1meet2'])
								                    </div>
								                  </div>                                    
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 3 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1meet3') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1meet3" id="term1meet3" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet3', ($timelineinfo[0]->t1_meeting_minutes3 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes3)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term1meet3'])
								                    </div>
								                  </div>                                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - I  Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1chapter1') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1chapter1" id="term1chapter1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1chapter1', ($timelineinfo[0]->term1chapter1 ? \Carbon\Carbon::parse($timelineinfo[0]->term1chapter1)->format('d-m-Y') : '')) }}"/>            
								                      @include('alerts.feedback', ['field' => 'term1chapter1'])
								                    </div>
								                  </div>                                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>                  
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 4 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1meet4') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1meet4" id="term1meet4" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet4', ($timelineinfo[0]->t1_meeting_minutes4 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes4)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term1meet4'])
								                    </div>
								                  </div>                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 5 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1meet5') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1meet5" id="term1meet5" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1meet5', ($timelineinfo[0]->t1_meeting_minutes5 ? \Carbon\Carbon::parse($timelineinfo[0]->t1_meeting_minutes5)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term1meet5'])
								                    </div>
								                  </div>                                     
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - II  Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1chapter2') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1chapter2" id="term1chapter2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1chapter2', ($timelineinfo[0]->term1chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term1chapter2)->format('d-m-Y') : '')) }}"/>            
								                      @include('alerts.feedback', ['field' => 'term1chapter2'])
								                    </div>
								                  </div>                                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Presentaion  Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term1presentation') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term1presentation" id="term1presentation" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term1presentation', ($timelineinfo[0]->term1presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term1presentation)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term1presentation'])
								                    </div>
								                  </div>                                  
								                </div>
												<div class="row">
												  <label class="col-sm-3 col-form-label cht_text">{{ __('Term - II Completion Date') }}</label>
												  <div class="col-sm-3">
													<div class="form-group view_word {{ $errors->has('term2date') ? ' has-danger' : '' }}">
														<input type="text"  name="term2date" id="term2date" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->term2_completion ? \Carbon\Carbon::parse($timelineinfo[0]->term2_completion)->format('d-m-Y') : '')) }}"/>						
														@include('alerts.feedback', ['field' => 'term2date'])
													</div>
												  </div>
												</div>
												<div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 1 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2meet1') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2meet1" id="term2meet1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes1 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes1)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term2meet1'])
								                    </div>
								                  </div>                                    
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 2 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2meet2') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2meet2" id="term2meet2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes2 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes2)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term2meet2'])
								                    </div>
								                  </div>                                    
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 3 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2meet3') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2meet3" id="term2meet3" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes3 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes3)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term2meet3'])
								                    </div>
								                  </div>                                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - I  Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2chapter1') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2chapter1" id="term2chapter1" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2chapter1', ($timelineinfo[0]->term2chapter1 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter1)->format('d-m-Y') : '')) }}"/>            
								                      @include('alerts.feedback', ['field' => 'term2chapter1'])
								                    </div>
								                  </div>                                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>                  
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 4 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2meet4') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2meet4" id="term2meet4" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes4 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes4)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term2meet4'])
								                    </div>
								                  </div>                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Meeting  Minutes - 5 Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2meet5') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2meet5" id="term2meet5" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2date', ($timelineinfo[0]->t2_meeting_minutes5 ? \Carbon\Carbon::parse($timelineinfo[0]->t2_meeting_minutes5)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term2meet5'])
								                    </div>
								                  </div>                                     
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Chapter - II  Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2chapter2') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2chapter2" id="term2chapter2" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2chapter2', ($timelineinfo[0]->term2chapter2 ? \Carbon\Carbon::parse($timelineinfo[0]->term2chapter2)->format('d-m-Y') : '')) }}"/>            
								                      @include('alerts.feedback', ['field' => 'term2chapter2'])
								                    </div>
								                  </div>                                  
								                </div>
								                <div class="row">
								                  <div class="col-sm-2">&nbsp;</div>
								                  <label class="col-sm-4 col-form-label form_chg">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('Presentation  Completion Date') }}</label>
								                  <div class="col-sm-2">
								                    <div class="form-group view_word {{ $errors->has('term2presentation') ? ' has-danger' : '' }}">
								                      <input type="text"  name="term2presentation" id="term2presentation" placeholder="{{ __('Select date') }}" class="form-control datetimepicker" value="{{ old('term2presentation', ($timelineinfo[0]->term2presentation ? \Carbon\Carbon::parse($timelineinfo[0]->term2presentation)->format('d-m-Y') : '')) }}"/>
								                      @include('alerts.feedback', ['field' => 'term2presentation'])
								                    </div>
								                  </div>                                  
								                </div>
											</div>
											@if($requestdetails[0]->manager_approval_status == 1 && $item->assigned_to == auth()->user()->id && $requestdetails[0]->supervisor_acceptence_status == 1)
											<div class="row" style="text-align:center;">
												<div class="col-sm-2">&nbsp;</div>
												<div class="col-sm-7">
													<input type="hidden" name="approve_update_comments" id="approve_update_comments" value="0" />
													<input type="hidden" name="update_dates_flag" id="update_dates_flag" value="0" />
													<input type="hidden" name="approve_track_id" id="approve_track_id" value="0" />
													@if(auth()->user()->id == $item->assigned_to && $requestdetails[0]->progress_completion != 3)
														<button type="button" onclick="funUpdateThesisTimelineDetails()" class="btn bt_styl btn_txtbold">{{ __('Save') }}</button>
													@endif														
												</div>
											</div>
											@endif	
												
											@if(auth()->user()->role_id == 3 || (auth()->user()->role_id == 2 && $item->assigned_to	== auth()->user()->id))	
												@if($item->approval_status == 1 && $requestdetails[0]->supervisor_acceptence_status == 0)
												<div class="row">
													<label class="col-sm-3 col-form-label cht_text"><span class="mark">*</span>{{ __('Accept Status') }}</label>
													<div class="col-sm-7">														
														<div class="form-group view_word {{ $errors->has('accept_status') ? ' has-danger' : '' }}">
															<select class="selectpicker col-sm-5 pl-0 pr-0" name="accept_status" id="accept_status" onchange="funUpdateAcceptStatus()" data-style="select-with-transition" title="" data-size="100">
															<option value="">-</option>
															@foreach (config('items.accept_status') as $vValue => $status)
															<option value="{{ $vValue }}" {{ $vValue == $requestdetails[0]->supervisor_acceptence_status ? 'selected' : '' }} >{{ $status }}</option>
															@endforeach
															</select>
															@include('alerts.feedback', ['field' => 'accept_status'])
														</div>
														
													</div>
												</div>											
												<div id="divApproveMessage" style="display:none">
													<div class="row">
														<label class="col-sm-3 col-form-label cht_text"><span class="mark">*</span>{{ __('Description') }}</label>
														<div class="col-sm-7">
															<div class="form-group view_word {{ $errors->has('approvemessage') ? ' has-danger' : '' }}">
																<textarea name="approvemessage" id="approvemessage" cols="35" rows="15" class="form-control{{ $errors->has('approvemessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('approvemessage') }}">{{ old('approvemessage') }}</textarea>
																@include('alerts.feedback', ['field' => 'approvemessage'])
															</div>
														</div>
													</div>
													<div class="row" id="divApproveAttachFiles" style="display:none">	
														<div class="col-sm-3">&nbsp;</div>
														<div class="col-sm-7">
															<div id="approvefileuploader" style="line-height: 25px;">Select Files</div>
														</div>
													</div>
													<div class="row">	
														 <label class="col-sm-3 col-form-label">&nbsp;</label>
														  <div class="col-sm-7 checkbox-radios">													
															  <div class="form-check">
																<label class="form-check-label cht_text">
																  <input name="accp_private_message" class="form-check-input" id="accp_private_message" value="1" onclick="funMakeItPrivate('accp_private_message')" type="checkbox"> {{ __('Private Message to Manager') }}
																  <span class="form-check-sign">
																	<span class="check"></span>
																  </span>
																</label>
															  </div>
														</div>
													</div>													
												</div>				  

												<div class="row" style="text-align:center;">
													<div class="col-sm-3">&nbsp;</div>
													<div class="col-sm-7">
														<input type="hidden" name="approve_update_comments" id="approve_update_comments" value="0" />
														<input type="hidden" name="approve_track_id" id="approve_track_id" value="0" />
														@if(auth()->user()->manager_flag != 2)
															<button type="button" onclick="funAcceptMessageValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
														@endif
														<a href="{{ route('item.index') }}" class="btn bt_styl">{{ __('Cancel') }}</a>
													</div>
												</div>
												@endif
											@endif
											<div  class="pt-5 text-center">
											@if(request()->get('action') == 'ac')
												<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
											@else
												<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
											@endif
											</div>
										</div>
										<div class="tab-pane" id="link2">
											@can('update', $item)
											<!--<div  class="pt-5 text-right">
												@if(request()->get('action') == 'ac')
													<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
												@else
													<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
												@endif
											</div>-->
											@endcan
											<div class="row">
												<div class="col-md-12">
													<div class="card card-timeline card-plain">
														<div class="card-body">
															<ul class="timeline">
															@if(count($trackinginfo['progress']))
																@for ($loop = 0; $loop<count($trackinginfo['progress']); $loop++) 
																	@if(auth()->user()->role_id != 4 && (!empty($trackinginfo['progress'][$loop]->description) || count($trackinginfo['attachments'][$loop]) > 0))	
																		@if(auth()->user()->id == $trackinginfo['progress'][$loop]->user_id)
																		<li class="timeline-inverted">
																			<div class="timeline-badge">																				
																				<img src="{{ config('items.image_auth.path') }}{{ auth()->user()->profilePicture() }}" style="width:2.8rem; height:2.8rem;border-radius:30px; " />
																			</div>
																			<div class="reply_clr timeline-panel">	

																				<div class="timeline-body cht_text" style="max-width:30.9rem; display: inline-block; word-wrap: break-word !important; white-space:initial;">
																					@if($trackinginfo['progress'][$loop]->action_type > 0)
																						<span style="font-weight:bold;">{{ __('Action: ') }}</span>{{__(config('items.action_options')[$trackinginfo['progress'][$loop]->action_type])}}
																					@endif
																					{!! $trackinginfo['progress'][$loop]->description !!}
																					@if (count($trackinginfo['attachments'][$loop]) > 0)
																						@for ($attach_loop = 0; $attach_loop<count($trackinginfo['attachments'][$loop]); $attach_loop++)
																							<p><a href="{{ route('download.viewfile', $trackinginfo['attachments'][$loop][$attach_loop]->id) }}" target="_blank">{{ $trackinginfo['attachments'][$loop][$attach_loop]->file_name }}</a> 
																						</p>
																						@endfor
																					@endif		
																				</div>
																				<div class="timeline-body cht_text">
																					<span class="badge badge-pill badge-info">Date: {{ \Carbon\Carbon::parse($trackinginfo['progress'][$loop]->created_date)->format('d-m-Y h:i:s')}}</span>
																				</div>
																			</div>
																		</li>
																		@else
																		<li>
																			@php
																				$vPicturePath = asset('storage')."/img/default-avatar.png";																			
																				$tplUserInfo = \App\User::find($trackinginfo['progress'][$loop]->user_id);																				
																				if(!empty($tplUserInfo->picture)) {
																					$vPath = public_path().$tplUserInfo->picture;
																					if(file_exists($vPath)) {
																						$vPicturePath = config('items.image_auth.path').$tplUserInfo->picture;
																					}																				
																				}
																			@endphp
																			<div class="timeline-badge">																				
																				<img src="{{ $vPicturePath }}" style="width:2.8rem; height:2.8rem;border-radius:30px; " />
																			</div>
																			<div class="stu_msg timeline-panel">
																				<div class="timeline-body cht_text" style="max-width:30.9rem; display: inline-block; word-wrap: break-word !important; white-space:initial;">						
																					@if($trackinginfo['progress'][$loop]->action_type > 0)
																						<span style="font-weight:bold;">{{ __('Action: ') }}</span>{{__(config('items.action_options')[$trackinginfo['progress'][$loop]->action_type])}}
																					@endif
																					{!! $trackinginfo['progress'][$loop]->description !!}
																					@if (count($trackinginfo['attachments'][$loop]) > 0)
																						@for ($attach_loop = 0; $attach_loop<count($trackinginfo['attachments'][$loop]); $attach_loop++)
																							<p><a href="{{ route('download.viewfile', $trackinginfo['attachments'][$loop][$attach_loop]->id) }}" style="color:rgb(61, 68, 101) !important;text-decoration: underline;" target="_blank">{{ $trackinginfo['attachments'][$loop][$attach_loop]->file_name }}</a> 
																						</p>
																						@endfor
																					@endif	
																				</div>
																				<div class="timeline-body cht_text">
																					<span class="badge badge-pill badge-info">Date: {{ \Carbon\Carbon::parse($trackinginfo['progress'][$loop]->created_date)->format('d-m-Y h:i:s')}}</span>
																				</div>
																			</div>
																		</li>
																		@endif
																	@else
																		@if($trackinginfo['progress'][$loop]->message_type == 1 && (!empty($trackinginfo['progress'][$loop]->description) || count($trackinginfo['attachments'][$loop]) > 0))
																			@if(auth()->user()->id == $trackinginfo['progress'][$loop]->user_id)
																			<li class="timeline-inverted">
																				<div class="timeline-badge">
																					<img src="{{ config('items.image_auth.path') }}{{ auth()->user()->profilePicture() }}" style="width:2.8rem; height:2.8rem;border-radius:30px; " />
																				</div>
																				<div class="reply_clr timeline-panel">
																					<div class="timeline-body cht_text" style="max-width:30.9rem; display: inline-block; word-wrap: break-word !important; white-space:initial;">
																						@if($trackinginfo['progress'][$loop]->action_type > 0)
																							<span style="font-weight:bold;">{{ __('Action: ') }}</span>{{__(config('items.action_options')[$trackinginfo['progress'][$loop]->action_type])}}
																						@endif
																						{!! $trackinginfo['progress'][$loop]->description !!}
																						@if (count($trackinginfo['attachments'][$loop]) > 0)
																							@for ($attach_loop = 0; $attach_loop<count($trackinginfo['attachments'][$loop]); $attach_loop++)
																								<p><a href="{{ route('download.viewfile', $trackinginfo['attachments'][$loop][$attach_loop]->id) }}" style="color:rgb(61, 68, 101) !important;text-decoration: underline;" target="_blank">{{ $trackinginfo['attachments'][$loop][$attach_loop]->file_name }}</a> 
																							</p>
																							@endfor
																						@endif		
																					</div>
																					<div class="timeline-body cht_text">
																						<span class="badge badge-pill badge-info">Date: {{ \Carbon\Carbon::parse($trackinginfo['progress'][$loop]->created_date)->format('d-m-Y h:i:s')}}</span>
																					</div>
																				</div>
																			</li>
																			@else
																			<li>
																				@php
																					$vPicturePath = asset('storage')."/img/default-avatar.png";																			
																					$tplUserInfo = \App\User::find($trackinginfo['progress'][$loop]->user_id);																				
																					if(!empty($tplUserInfo->picture)) {
																						$vPath = public_path().$tplUserInfo->picture;
																						if(file_exists($vPath)) {
																							$vPicturePath = config('items.image_auth.path').$tplUserInfo->picture;
																						}																				
																					}
																				@endphp
																				<div class="timeline-badge">																				
																					<img src="{{ $vPicturePath }}" style="width:2.8rem; height:2.8rem;border-radius:30px; " />
																				</div>
																				<div class="stu_msg timeline-panel">
																					<div class="timeline-body cht_text" style="max-width:30.9rem; display: inline-block; word-wrap: break-word !important; white-space:initial;">
																						@if($trackinginfo['progress'][$loop]->action_type > 0)
																							<span style="font-weight:bold;">{{ __('Action: ') }}</span>{{__(config('items.action_options')[$trackinginfo['progress'][$loop]->action_type])}}
																						@endif
																						{!! $trackinginfo['progress'][$loop]->description !!}
																						@if (count($trackinginfo['attachments'][$loop]) > 0)
																							@for ($attach_loop = 0; $attach_loop<count($trackinginfo['attachments'][$loop]); $attach_loop++)
																								<p><a href="{{ route('download.viewfile', $trackinginfo['attachments'][$loop][$attach_loop]->id) }}" style="color:rgb(61, 68, 101) !important;text-decoration: underline;" target="_blank">{{ $trackinginfo['attachments'][$loop][$attach_loop]->file_name }}</a> 
																							</p>
																							@endfor
																						@endif	
																					</div>
																					<div class="timeline-body cht_text">
																						<span class="badge badge-pill badge-info" style="text-align: left;">Date: {{ \Carbon\Carbon::parse($trackinginfo['progress'][$loop]->created_date)->format('d-m-Y h:i:s')}}</span>
																					</div>
																				</div>
																			</li>
																			@endif
																		@endif
																	@endif
																@endfor											
															@endif
															</ul>
														</div>
													</div>
												</div>											
											</div>
											@if($requestdetails[0]->progress_completion  < 3)												
												<div class="row">
													<div class="col-sm-7">
														<div class="form-group view_word {{ $errors->has('description') ? ' has-danger' : '' }}">
															<textarea name="description" id="description" cols="20" rows="5" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('description') }}">{{ old('description') }}</textarea>
															@include('alerts.feedback', ['field' => 'description'])
														</div>
													</div>
												</div>
												<div class="row" id="divAttachFiles" style="display:block">
													<label class="col-sm-2 col-form-label cht_text"><span class="mark">*</span>{{ __('Attach Files') }}</label>
													<div class="col-sm-7 cht_text">
														<div id="fileuploader" style="line-height: 25px;">Select Files</div>
													</div>
												</div>
												@if(auth()->user()->role_id  == 3)
												<div class="row">												  
													  <div class="col-sm-10 checkbox-radios">													
														  <div class="form-check">
															<label class="form-check-label">
															  <input name="private_message" class="form-check-input" id="private_message" value="1" onclick="funMakeItPrivate('private_message')" type="checkbox"> {{ __('Private Message to Manager') }}
															  <span class="form-check-sign">
																<span class="check"></span>
															  </span>
															</label>
														  </div>
													</div>
												</div>
												@endif
												@if(auth()->user()->role_id  == 2)
												<div class="row">												  
													  <div class="col-sm-10 checkbox-radios">													
														  <div class="form-check">
															<label class="form-check-label">
															  <input name="private_message" class="form-check-input" id="private_message" value="1" onclick="funMakeItPrivate('private_message')" type="checkbox"> {{ __('Private Message to Supervisor') }}
															  <span class="form-check-sign">
																<span class="check"></span>
															  </span>
															</label>
														  </div>
													</div>
												</div>
												@endif
												<div class="card-footer ml-auto mr-auto">
													<input type="hidden" name="update_comments" id="update_comments" value="0" />
													<input type="hidden" name="track_id" id="track_id" value="0" />
													<input type="hidden" name="item_id" id="item_id" value="{{$item->id}}" />
													<input type="hidden" name="action_flag" id="action_flag" value="{{request()->get('action')}}" />
													@if(auth()->user()->manager_flag != 2)
														<button type="button" id="postcomment" onclick="funApproveValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>	
													@endif							
												</div>
											@endif
											@can('update', $item)
												<div  class="pt-5 text-center">
													@if(request()->get('action') == 'ac')
														<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
													@else
														<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
													@endif
												</div>
											@endcan
										</div>
										<div class="tab-pane" id="link3">
											@can('update', $item)
											<!--<div class="row">
												<div class="col-md-12 text-right">									  
													@if(request()->get('action') == 'ac')
														<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
													@else
														<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
													@endif
												</div>
											</div> -->
											@endcan
											<div class="table-responsive">
												<table id="datatables" class="table table-striped table-no-bordered table-hover">
													<thead class="text-primary">
														<th class="view_word" style="font-weight:bold;">
															{{ __('S.No') }}
														</th>
														<th class="view_word" style="font-weight:bold;">
															{{ __('File Name') }}
														</th>					  
														<th class="view_word" style="font-weight:bold;">
															{{ __('Uploaded By') }}
														</th>
														<th class="view_word" style="font-weight:bold;">
															{{ __('Date') }}
														</th>
														<th class="view_word" style="font-weight:bold;">
															{{ __('View') }}
														</th>									  
													</thead>									
													<tbody class="cht_text">
													@if(count($trackinginfo['progress']))
													@php $attachSeq = 0 @endphp
													@for ($loop = 0; $loop<count($trackinginfo['progress']); $loop++)
														@if (count($trackinginfo['attachments'][$loop]) > 0)
														@for ($attach_loop = 0; $attach_loop<count($trackinginfo['attachments'][$loop]); $attach_loop++)
														<tr>
															<td>
																{{ $attachSeq+1 }}
															</td>
															<td>
																{{ $trackinginfo['attachments'][$loop][$attach_loop]->file_name }}
															</td>						 
															<td>
																{{ $trackinginfo['progress'][$loop]->name }}
															</td>
															<td>
																{{ $trackinginfo['progress'][$loop]->created_date }}
															</td>
															<td>
																<a href="{{ route('download.viewfile', $trackinginfo['attachments'][$loop][$attach_loop]->id) }}" target="_blank"><div class="icon_siz text-left"><i class="far fa-edit"></i></div></a>																
															</td>
														</tr>
														@php $attachSeq++ @endphp
														@endfor													
														@endif												
													@endfor											
													@endif
													</tbody>
												</table>
												 <div  class="pt-5 text-center">
													@if(request()->get('action') == 'ac')
														<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
													@else
														<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a>
													@endif
												 </div>
											</div>
										</div>
										@if($item->approval_status == 1 && $requestdetails[0]->supervisor_acceptence_status == 1)											
										<div class="tab-pane" id="link4">
											@include('mythesis.termprogress')
										</div>
										@endif
									</div>					  
								</div>
							</div>				
						</div>
					</div>			
				</div>			
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

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
		selector: '#description',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});
	tinymce.init({
		selector: '#approvemessage',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});
	tinymce.init({
		selector: '#statusdescription',
		setup: function (editor) {
			editor.on('change', function () {
				editor.save();
			});
		}
	});
	vPath = '{!! $upload !!}';
	extraObj = $("#fileuploader").uploadFile({
		url:vPath,
		fileName:"thesisfiles",
		method:'POST',
		dragDropStr: "",
		dragDrop: true,
		statusBarWidth:"100%",
		dragdropWidth:"50%",
		maxFileSize:1024*1024*100,
		uploadStr:"Select File(s)",
		autoSubmit:false,		
		dynamicFormData: function()
		{			
			var data ={
						"_token": "{{ csrf_token() }}",
						"track_id": $("#track_id").val(), 
						"private_message": $("#private_message").val(),
						"description": $('#description').val()
					};
			return data;        
		},
		onSuccess:function(files,data,xhr,pd)
		{
			$("#track_id").val(data['track_id']);
		
		},
		afterUploadAll: function(obj)
		{
			swal({
				  title: 'Comments posted successfully!',
				  text: '',
				  type: 'success',
				  confirmButtonColor: '#47a44b'
			}).then ((result) =>{
				$("#update_comments").val(1);
				document.frmCommentUpdate.method='post'
				document.frmCommentUpdate.submit();
			})
		}	
	});
	vAcceptPath = '{!! $acceptPath !!}';
	approveextraObj = $("#approvefileuploader").uploadFile({
		url:vAcceptPath,
		fileName:"approvefiles",
		method:'POST',
		dragDropStr: "",
		dragDrop: true,
		statusBarWidth:"100%",
		dragdropWidth:"50%",
		maxFileSize:1024*1024*100,
		uploadStr:"Select File(s)",
		autoSubmit:false,		
		dynamicFormData: function()
		{			
			var data ={
						"_token": "{{ csrf_token() }}",						
						"approve_track_id": $("#approve_track_id").val(), 
						"approve_status": $("#approve_status").val(), 
						"accp_private_message": $("#accp_private_message").val(),
						"approvemessage": $('#approvemessage').val()
					};
			return data;        
		},
		onSuccess:function(files,data,xhr,pd)
		{
			$("#approve_track_id").val(data['approve_track_id']);			
		},
		afterUploadAll: function(obj)
		{
			swal({
				  title: 'Comments posted successfully!',
				  text: '',
				  type: 'success',
				  confirmButtonColor: '#47a44b'
			}).then ((result) =>{
				$("#approve_update_comments").val(1);
				document.frmCommentUpdate.method='POST'
				document.frmCommentUpdate.action=vAcceptPath;
				document.frmCommentUpdate.submit();
			})
		}
		
	});
	vSuccessPath = '{!! $successPath !!}';
	successextraObj = $("#statusfileuploader").uploadFile({
		url:vSuccessPath,
		fileName:"successfiles",
		method:'POST',
		dragDropStr: "",
		dragDrop: true,
		statusBarWidth:"100%",
		dragdropWidth:"50%",
		maxFileSize:1024*1024*100,
		uploadStr:"Select File(s)",
		autoSubmit:false,		
		dynamicFormData: function()
		{			
			var data ={
						"_token": "{{ csrf_token() }}",						
						"statusupdate_track_id": $("#statusupdate_track_id").val(), 
						"progress_status": $("#progress_status").val(), 
						"statusdescription": $('#statusdescription').val(),
						"assignedtomanager": $('#assignedtomanager').val()
					};
			return data;        
		},
		onSuccess:function(files,data,xhr,pd)
		{
			$("#statusupdate_track_id").val(data['statusupdate_track_id']);			
		},
		afterUploadAll: function(obj)
		{
			swal({
				  title: 'Completion status updated successfully!',
				  text: '',
				  type: 'success',
				  confirmButtonColor: '#47a44b'
			}).then ((result) =>{												
				$("#statsupdate_comments").val(1);
				document.frmCommentUpdate.method='POST'
				document.frmCommentUpdate.action=vSuccessPath;
				document.frmCommentUpdate.submit();
			})
		}
		
	});
	$("#tablink2").click( function() {
		window.setTimeout( function() {
		$('#postcomment').focus();},200);
		window.setTimeout( function() {
		funUpdateNotificationMsgViewStatus();},500);
	});
	var tabnum =  getParameterByName('tab');
	if(tabnum == 2) {		
		$('#link1').removeClass("active");
		$('#link2').addClass("active");					
		$('#tablink1').removeClass("active show");			
		$('#tablink2').addClass("active show");	
		$('#postcomment').focus();	
		window.setTimeout( function() {
		funUpdateNotificationMsgViewStatus();},500);
	}
	else if(tabnum == 4) {
		$('#link1').removeClass("active");
		$('#link4').addClass("active");					
		$('#tablink1').removeClass("active show");			
		$('#tablink4').addClass("active show");	
		$('#postcomment').focus();	
	}
  });
	function getParameterByName( name ){
		var regexS = "[\\?&]"+name+"=([^&#]*)", 
		regex = new RegExp( regexS ),
		results = regex.exec( window.location.search );
		if( results == null ){
			return "";
		} else{
			return decodeURIComponent(results[1].replace(/\+/g, " "));
		}
	}
	
	function funUpdateNotificationMsgViewStatus() {
		var item_id = {{$item->id}};
		var vMsgPath = '{{ url("/item/update-msgviewed") }}';
		
		$.ajax({
		   type: 'POST',
		   url: vMsgPath,		   
		   data: {
			   "_token": "{{ csrf_token() }}",			  
				"msg_item_id": item_id
		   },
		   success: function( msg ) {
			  //alert("mesage view updated  ===> ");
		   }
	   });	   
	}

	function funApproveValidate() {
		if($('#description').val() == "" && extraObj.fileCounter == 1) {
			swal("", "Please enter message or Select a file", "error").then((result) => {
			  tinymce.EditorManager.get('description').focus();
			});				
		}
		else if($('#description').val() != "" && extraObj.fileCounter == 1) {
			swal({
				  title: 'Comments posted successfully!',
				  text: '',
				  type: 'success',
				  confirmButtonColor: '#47a44b'
			}).then ((result) =>{
				$.ajax({
				   type: "POST",
				   url: vPath,
				   data: {
					   "_token": "{{ csrf_token() }}",
					   "private_message": $("#private_message").val(), 
						"track_id": $("#track_id").val(), 
						"description": $('#description').val()
				   },
				   success: function( msg ) {
					   //alert( msg );
				   }
			   });
				
				$("#update_comments").val(1);
				document.frmCommentUpdate.method='post'				
				document.frmCommentUpdate.submit();
			})				
		}
		else if(extraObj.fileCounter != 1)  {
			extraObj.startUpload();
		}
	}
	
	function funUpdateAcceptStatus () {
		var optValue = $("#accept_status").val();
		if(parseInt(optValue) == 1 || parseInt(optValue) == 3) {
			$(".ajax-file-upload-container").html('');
			$("#divApproveMessage").show();
			$("#divApproveAttachFiles").hide();
		}
		else if(parseInt(optValue) == 2) {
			$("#divApproveMessage").show();
			$("#divApproveAttachFiles").show();			
		}
		else {
			$("#divApproveMessage").hide();
			$("#divApproveAttachFiles").hide();
			$(".ajax-file-upload-container").html('');
		} 
	}
	function funAcceptMessageValidate () {	
		var optValue = $("#accept_status").val();		
		if(optValue == "") {
			swal("", "Please select accept status!", "error").then((result) => {
				  $("#accept_status").focus();
			});				
		}
		else if(optValue == 1) {			
			if($('#approvemessage').val() == "") {
				swal("", "Please enter message!", "error").then((result) => {
				  tinymce.EditorManager.get('approvemessage').focus();
				});				
			}
			else {
				$("#approve_update_comments").val(1);
				document.frmCommentUpdate.action=vAcceptPath;
				document.frmCommentUpdate.method='POST'
				document.frmCommentUpdate.submit();
			}
		}
		else if(optValue == 2) {				
			if($('#approvemessage').val() == "" && approveextraObj.fileCounter == 1) {
				swal("", "Please enter message or Select a file", "error").then((result) => {
				  tinymce.EditorManager.get('approvemessage').focus();
				});				
			}
			else if($('#approvemessage').val() != "" && approveextraObj.fileCounter == 1) {
				swal({
					  title: 'Message posted successfully!',
					  text: '',
					  type: 'success',
					  confirmButtonColor: '#47a44b'
				}).then ((result) =>{
					$.ajax({
					   type: "POST",
					   url: vAcceptPath,
					   data: {
						   "_token": "{{ csrf_token() }}",
							"track_id": $("#approve_track_id").val(), 
							"accp_private_message": $("#accp_private_message").val(), 
							"description": $('#approvemessage').val()
					   },
					   success: function( msg ) {
						   //alert( msg );
					   }
				   });
					
					$("#approve_update_comments").val(1);
					document.frmCommentUpdate.action=vAcceptPath;
					document.frmCommentUpdate.method='post'
					document.frmCommentUpdate.submit();
				})				
			}
			else if(approveextraObj.fileCounter != 1)  {
				approveextraObj.startUpload();
			}
		}
		else if(optValue == 3) {	
			swal({
			  title: 'Are you sure to reject?',
			  text: "You won't be able to revert this back!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#47a44b',
			  cancelButtonColor: '#ea2c6d',			  
			  confirmButtonText: 'Yes, Reject it!'
			}).then((result) => {
			  if (result.value) {
				if($('#approvemessage').val() == "") {
					swal("", "Please enter description!", "error").then((result) => {
					  tinymce.EditorManager.get('approvemessage').focus();
					});				
				}
				else {
					swal({
					  title: 'Rejected!',
					  text: 'Your have rejected the request.',
					  type: 'success',
					  confirmButtonColor: '#47a44b'
					}).then ((result) =>{
						$("#approve_update_comments").val(1);
						document.frmCommentUpdate.method='POST';
						document.frmCommentUpdate.action=vAcceptPath;
						document.frmCommentUpdate.submit();
					})
				}
			  }
			})
		}
	}
	function funUpdateThesisDetails() {
		$("#update_dates_flag").val(1);
		document.frmCommentUpdate.action=vAcceptPath;
		document.frmCommentUpdate.method='POST'
		document.frmCommentUpdate.submit();
	}

	function funUpdateThesisTimelineDetails() {			
		$("#update_dates_flag").val(8);		
		document.frmCommentUpdate.action=vAcceptPath;
		document.frmCommentUpdate.method='POST'
		document.frmCommentUpdate.submit();
	}
	
	function funMakeItPrivate(id) {		
		if($('#'+id).is(":checked"))
			$('#'+id).val(2);		
		else
			$('#'+id).val(1);		
	}

	function funPrepareMeetingMinutes(pmTerm,pmSeq) {
		$("#meeting_log_seq").val(pmSeq);
		document.frmCommentUpdate.action='{{ url("/mythesis/") }}/'+{{$item->id}}+'/prepare-meeting-minutes?tab=4&action=ac';
		document.frmCommentUpdate.method='POST'
		document.frmCommentUpdate.submit();		
	}

	function funViewMeetingMinutes(pmTerm,pmSeq) {				
		$("#meeting_log_seq").val(pmSeq);
		$("#term_seq").val(pmTerm);
		$("#viewflag").val(1);
		document.frmCommentUpdate.action='{{ url("/mythesis/") }}/'+{{$item->id}}+'/view-meeting-minutes?tab=4&action=ac';
		document.frmCommentUpdate.method='POST'
		document.frmCommentUpdate.submit();		
	}
	function funViewRubric(pmTerm,pmType) {    	
    	$("#rubricterm").val(pmTerm);
    	$("#rubrictype").val(pmType);
    	document.frmCommentUpdate.action='{{ url("/item/view-rubric") }}';
			document.frmCommentUpdate.method='POST'
			document.frmCommentUpdate.submit();	
    }

    function funPrepareRubric(pmTerm,pmType) {
    	$("#rubricterm").val(pmTerm);
    	$("#rubrictype").val(pmType);
    	document.frmCommentUpdate.action='{{ url("/item/prepare-rubric") }}';
			document.frmCommentUpdate.method='POST'
			document.frmCommentUpdate.submit();	
    }
</script>
@endpush