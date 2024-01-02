@extends('layouts.app', ['activePage' => 'track-activity', 'menuParent' => 'laravel', 'titlePage' => __('Approve Thesis')])
@section('head')
<meta name="csrf_token" content="{{ csrf_token() }}" />
@endsection
@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
					<form method="post" enctype="multipart/form-data" name="frmCommentUpdate" id="frmCommentUpdate" action="{{ url($upload) }}" autocomplete="off" class="form-horizontal">
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
																	<tr style="border-bottom: 2px solid rgba(0, 0, 0, 0.06);">
																		<td class="cbold_text">{{ __('Approval')}}&nbsp;{{__('Status') }}</td>
																		<td class="sid_text">
																			@if($item->approval_status == 1)
																				{{__('Approved')}}
																			@else
																				{{__('Pending')}}	
																			@endif
																		</td>
																	</tr>																									
																	</tbody>
																</table>
															</div>
															<div class="col-md-6">
																	<table class="table">
																		<tbody>
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
																			<td class="sid_text">{{$prefsupervisor[0]->name}}</td>
																		</tr>														
																		<tr>
																			<td class="cbold_text">{{ __('Supervisor Accept Status') }}</td>
																			<td class="sid_text">
																				{{__('Pending')}}																	
																			</td>
																		</tr>
																		<tr style="border-bottom: 2px solid rgba(0, 0, 0, 0.06);">
																			<td class="cbold_text">{{ __('Project By') }}</td>
																			<td class="sid_text">
																				@php
										                    $aUserInfo = \App\User::select('users.name')->where(['users.id' => $item->created_by])->get();
										                    $aTimelineInfo = \App\ThesisTimeline::select('*')->where(['thesis_timelines.term_id' => $item->term_id,'thesis_timelines.program_id' => $item->program_id])->get();
										                    @endphp                      
										                    @if(count($aUserInfo) > 0)
										                      {{ $aUserInfo[0]->name }}
										                    @endif
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
														<div class="row">								  
														  <label class="col-sm-3 col-form-label cht_text"><span class="mark">*</span>{{ __('Assign Supervisor') }}</label>
														  <div class="col-sm-7">
															<div class="form-group view_word {{ $errors->has('supervisor_id') ? ' has-danger' : '' }}">
																<select class="selectpicker col-sm-5 pl-0 pr-0" name="supervisor_id" id="supervisor_id" data-style="select-with-transition" title="" data-size="100">
																<option value="">-</option>
																@foreach ($supervisors as $supervisor)
																<option value="{{ $supervisor->id }}" {{ $supervisor->id == $prefsupervisor[0]->id ? 'selected' : '' }}>{{ $supervisor->name }}</option>
																@endforeach
															  </select>
															  @include('alerts.feedback', ['field' => 'supervisor_id'])
															  @if($item->user_role_id != 4)
																	<span class="cht_text text-capitalize">[&nbsp;{{__('Project By')}}:&nbsp;
																	@if(count($aUserInfo) > 0)
																	  {{ $aUserInfo[0]->name }}
																	@endif
																	]</span>
																@endif
															</div>											
														  </div>
														</div>
														<!--<div class="row">								  
														  <label class="col-sm-3 col-form-label cht_text">{{ __('Assign Co-Supervisor') }}</label>
														  <div class="col-sm-7">
															<div class="form-group view_word {{ $errors->has('cosupervisor_id') ? ' has-danger' : '' }}">
																<select class="selectpicker col-sm-5 pl-0 pr-0" name="cosupervisor_id" id="cosupervisor_id" data-style="select-with-transition" title="" data-size="100">
																<option value="">-</option>
																@foreach ($supervisors as $supervisor)
																	@if($supervisor->id != $prefsupervisor[0]->id)
																		<option value="{{ $supervisor->id }}" {{ $supervisor->id == $prefsupervisor[0]->id ? 'selected' : '' }}>{{ $supervisor->name }}</option>
																	@endif
																@endforeach
															  </select>
															  @include('alerts.feedback', ['field' => 'cosupervisor_id'])
															</div>											
														  </div>
														</div>
														<div class="row">								  
														  <label class="col-sm-3 col-form-label cht_text"><span class="mark">*</span>{{ __('Assign Examine') }}</label>
														  <div class="col-sm-7">
															<div class="form-group view_word {{ $errors->has('thesis_examine') ? ' has-danger' : '' }}">
																<select class="selectpicker col-sm-5 pl-0 pr-0" name="thesis_examine" id="thesis_examine" data-style="select-with-transition" title="" data-size="100">
																<option value="">-</option>
																@foreach ($supervisors as $supervisor)
																	@if($supervisor->id != $prefsupervisor[0]->id)
																		<option value="{{ $supervisor->id }}" {{ $supervisor->id == $prefsupervisor[0]->id ? 'selected' : '' }}>{{ $supervisor->name }}</option>
																	@endif
																@endforeach
															  </select>
															  @include('alerts.feedback', ['field' => 'thesis_examine'])
															</div>											
														  </div>
														</div> -->
														<div class="row">
														  <label class="col-sm-3 col-form-label cht_text"><span class="mark">*</span>{{ __('Request Status') }}</label>
														  <div class="col-sm-7">
															<div class="form-group view_word {{ $errors->has('approve_status') ? ' has-danger' : '' }}">
																<select class="selectpicker col-sm-5 pl-0 pr-0" name="approve_status" id="approve_status" onchange="funUpdateStatus()" data-style="select-with-transition" title="" data-size="100">
																<option value="">-</option>
																@foreach (config('items.approve_status') as $vValue => $status)
																<option value="{{ $vValue }}" {{ $vValue == $item->approval_status ? 'selected' : '' }}>{{ $status }}</option>
																@endforeach
															  </select>
															  @include('alerts.feedback', ['field' => 'approve_status'])
															</div>
														  </div>
														</div>
														<div id="divShowTimelineError" style="display:none;">
															<div class="row">
															  <label class="col-sm-10 col-form-label cbold_text text-center">
															  	<span  style="color:red !important">{{ __('Please create thesis timeline for this cohort before approve the thesis') }}ss</span>
															  </label>
															</div>
														</div>														
														<div id="divApproveMessage" style="display:none">											
															<div class="row">
																 <label class="col-sm-3 col-form-label cht_text"><span class="mark">*</span>{{ __('Message') }}</label>
																 <div class="col-sm-7">
																	<div class="form-group view_word {{ $errors->has('approvemessage') ? ' has-danger' : '' }}">
																		  <textarea name="approvemessage" id="approvemessage" cols="35" rows="8" class="form-control{{ $errors->has('approvemessage') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('approvemessage') }}">{{ old('approvemessage') }}</textarea>
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
														</div>										
														@if($requestdetails[0]->manager_approval_status == 0)
														<div class="row" style="text-align:center;">
															<div class="col-sm-2">&nbsp;</div>
															<div class="col-sm-7">
																<input type="hidden" name="approve_update_comments" id="approve_update_comments" value="0" />
																<input type="hidden" name="approve_track_id" id="approve_track_id" value="0" />
																@if(auth()->user()->manager_flag != 2)
																	<button type="button" onclick="funApproveMessageValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
																@endif
																<a href="{{ route('item.index') }}" class="btn bt_styl btn_txtbold">{{ __('Cancel') }}</a>
															</div>
														</div>
														@endif
														<div class="row">
															<div class="col-md-12 text-center pt-5">
																 <a class="bct_list" href="{{ route('item.index') }}"><i class="far fa-arrow-alt-circle-left"></i>&nbsp BACK TO LIST</a>
															</div>
														</div>												
											</div>
											<div class="tab-pane" id="link2">
												<div class="row">
												  <div class="col-md-12">
													<div class="card card-timeline card-plain">
													  <div class="card-body">
														<ul class="timeline">
														@if(count($trackinginfo['progress']))
															@for ($loop = 0; $loop<count($trackinginfo['progress']); $loop++) 									
																@if(auth()->user()->id == $trackinginfo['progress'][$loop]->user_id)
																	<li class="timeline-inverted">
																		@php
																			$vPicturePath = asset('storage')."/img/default-avatar.png";																			
																			$tplUserInfo = \App\User::find($trackinginfo['progress'][$loop]->user_id);																				
																			if(!empty($tplUserInfo->picture)) {
																				$vPath = storage_path().$tplUserInfo->picture;
																				if(file_exists($vPath)) {
																					$vPicturePath = config('items.image_auth.path').$tplUserInfo->picture;
																				}																				
																			}
																		@endphp
																		<div class="timeline-badge">																				
																			<img src="{{ $vPicturePath }}" style="width:2.8rem; height:2.8rem;border-radius:30px; " />
																		</div>
																		<div class="timeline-panel reply_clr">													
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
																				<span class="badge badge-pill badge-info">Date: {{ \Carbon\Carbon::parse($trackinginfo['progress'][$loop]->created_date)->format('d-m-Y')}}</span>
																			</div>
																		</div>
																	</li>
																@else
																	<li>
																		@php
																			$vPicturePath = asset('storage')."/img/default-avatar.png";																			
																			$tplUserInfo = \App\User::find($trackinginfo['progress'][$loop]->user_id);																				
																			if(!empty($tplUserInfo->picture)) {
																				$vPath = storage_path().$tplUserInfo->picture;
																				if(file_exists($vPath)) {
																					$vPicturePath = config('items.image_auth.path').$tplUserInfo->picture;
																				}																				
																			}
																		@endphp
																		<div class="timeline-badge">																				
																			<img src="{{ $vPicturePath }}" style="width:2.8rem; height:2.8rem;border-radius:30px; " />
																		</div>
																		<div class="timeline-panel stu_msg">
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
																				<span class="badge badge-pill badge-info">Date: {{ \Carbon\Carbon::parse($trackinginfo['progress'][$loop]->created_date)->format('d-m-Y')}}</span>
																			</div>
																		</div>
																	</li>
																@endif
															@endfor											
														@endif
														</ul>
													  </div>
													</div>
												  </div>
												<div>
												</div>
												</div>
												<div class="row">
												   <div class="col-sm-10">
													<div class="form-group view_word {{ $errors->has('description') ? ' has-danger' : '' }}">
													  <textarea name="description" id="description" cols="35" rows="5" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Message') }}" value="{{ old('description') }}">{{ old('description') }}</textarea>
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
												<div class="card-footer ml-auto mr-auto">
												<input type="hidden" name="update_comments" id="update_comments" value="0" />
												<input type="hidden" name="track_id" id="track_id" value="0" />
												<input type="hidden" name="item_id" id="item_id" value="{{$item->id}}" />
												<input type="hidden" name="action_page" id="action_page" value="{{ route('item.detail', [$item->id]) }}" />
												@if(auth()->user()->manager_flag != 2)
													<button type="button" id="postcomment" onclick="funApproveValidate()" class="btn bt_styl btn_txtbold">{{ __('Submit') }}</button>
												@endif								
											  </div>
											  <div class="row">
													<div class="col-md-12 text-center pt-5">
														 <a class="bct_list" href="{{ route('item.index') }}"><i class="far fa-arrow-alt-circle-left"></i>&nbsp BACK TO LIST</a>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="link3">
												<div class="table-responsive">
												  <table id="datatables" class="table table-striped table-no-bordered table-hover">
													<thead class="text-primary">
													  <th class="view_word">
														  {{ __('S.No') }}
													  </th>
													  <th class="view_word">
														{{ __('File Name') }}
													  </th>					  
													  <th class="view_word">
														{{ __('Uploaded By') }}
													  </th>
													  <th class="view_word">
														{{ __('Date') }}
													  </th>
													  <th class="view_word">
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
															<a href="{{ route('mythesis.assigned') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
														@else
															<a href="{{ route('item.index') }}" class="bct_list"><i class="far fa-arrow-alt-circle-left"></i>&nbsp &nbsp BACK TO LIST</a></a>
														@endif
													 </div>
												</div>
											</div>
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
	$.fn.selectpicker.Constructor.DEFAULTS.maxOptions = 4;
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
	vPath = '{!! $upload !!}';
	extraObj = $("#fileuploader").uploadFile({
		url:vPath,
		fileName:"thesisfiles",
		method:'POST',
		dragDropStr: "",
		dragDrop: true,
		statusBarWidth:"100%",
		dragdropWidth:"50%",
		maxFileSize:1024*10000,
		uploadStr:"Select File(s)",
		autoSubmit:false,		
		dynamicFormData: function()
		{			
			var data ={
						"_token": "{{ csrf_token() }}",
						"track_id": $("#track_id").val(), 
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
	vAttachPath = '{!! $attachupload !!}';
	approveextraObj = $("#approvefileuploader").uploadFile({
		url:vAttachPath,
		fileName:"approvefiles",
		method:'POST',
		dragDropStr: "",
		dragDrop: true,
		statusBarWidth:"100%",
		dragdropWidth:"50%",
		maxFileSize:1024*10000,
		uploadStr:"Select File(s)",
		autoSubmit:false,		
		dynamicFormData: function()
		{			
			var data ={
						"_token": "{{ csrf_token() }}",
						"supervisor_id": $("#supervisor_id").val(), 
						"approve_track_id": $("#approve_track_id").val(), 
						"approve_status": $("#approve_status").val(), 
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
				document.frmCommentUpdate.action=vAttachPath;
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
	if(tabnum != "") {		
		$('#link1').removeClass("active");
		$('#link2').addClass("active");					
		$('#tablink1').removeClass("active show");			
		$('#tablink2').addClass("active show");	
		$('#postcomment').focus();
		window.setTimeout( function() {
		funUpdateNotificationMsgViewStatus();},500);
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
			swal("", "Please enter description or Select a file", "error").then((result) => {
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
				   dataType:"json",
				   data: {
					   "_token": "{{ csrf_token() }}",
						"track_id": $("#track_id").val(), 
						"description": $('#description').val()
				   },
				   success: function( msg ) {
					   alert( msg );
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
	
	function funUpdateStatus() {
		var vTimeLineFound = {{count($aTimelineInfo)}};		
		var optValue = $("#approve_status").val();
		if(parseInt(optValue) == 1 || parseInt(optValue) == 3) {
			if(parseInt(optValue) == 1) {
				if(parseInt(vTimeLineFound) > 0){
					$(".ajax-file-upload-container").html('');
					$("#divApproveMessage").show();					
					$("#term1date").val('');
					$("#term2date").val('');					
					$("#divApproveAttachFiles").hide();
					$("#divShowTimelineError").hide();
				}
				else {
					$("#divShowTimelineError").show();
					$("#divApproveMessage").hide();
					$("#divApproveAttachFiles").hide();					
					$(".ajax-file-upload-container").html('');
				}
				
			}
			else {
				$(".ajax-file-upload-container").html('');
				$("#divApproveMessage").show();
				$("#divApproveAttachFiles").hide();				
				$("#divApproveAttachFiles").hide();
			}
		}
		else if(parseInt(optValue) == 2) {
			$("#divApproveMessage").show();
			$("#divApproveAttachFiles").show();				
			$("#divApproveAttachFiles").hide();
		}
		else {
			$("#divApproveMessage").hide();
			$("#divApproveAttachFiles").hide();			
			$("#divApproveAttachFiles").hide();
			$(".ajax-file-upload-container").html('');
		} 
	}
	function funApproveMessageValidate() {	
		var optValue = $("#approve_status").val();
		var vTimeLineFound = {{count($aTimelineInfo)}};	
		if(optValue == "") {
			swal("", "Please select request status!", "error").then((result) => {
				  $("#approve_status").focus();
			});				
		}
		else if(optValue == 1) {
			if(vTimeLineFound > 0)	{
				if($('#approvemessage').val() == "") {
					swal("", "Please enter message!", "error").then((result) => {
					  tinymce.EditorManager.get('approvemessage').focus();
					});				
				}
				else {
					$("#approve_update_comments").val(1);
					document.frmCommentUpdate.action=vAttachPath;
					document.frmCommentUpdate.method='POST'
					document.frmCommentUpdate.submit();
				}
			}
			else {
				swal("", "Please create thesis timeline for this cohort brfore approval", "error").then((result) => {
					  //tinymce.EditorManager.get('approvemessage').focus();
					});
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
					   url: vAttachPath,
					   data: {
						   "_token": "{{ csrf_token() }}",
							"track_id": $("#approve_track_id").val(), 
							"description": $('#approvemessage').val()
					   },
					   success: function( msg ) {
						   //alert( msg );
					   }
				   });
					
					$("#approve_update_comments").val(1);
					document.frmCommentUpdate.action=vAttachPath;
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
					swal("", "Please enter message!", "error").then((result) => {
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
						document.frmCommentUpdate.action=vAttachPath;
						document.frmCommentUpdate.submit();
					})
				}
			  }
			})
		}
	}
</script>
@endpush