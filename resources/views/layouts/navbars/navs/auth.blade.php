<!-- Navbar -->
<nav class="navbar navbar-expand-lg  navbar-absolute fixed-top ">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <div class="navbar-minimize">
		<button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">            
			 <i class="icon_clr fas fa-ellipsis-v visible-on-sidebar-regular"></i>
			  <i class="icon_clr fas fa-th-list visible-on-sidebar-mini"></i>
        </button>
        </div>
        <a class="navbar-brand cap_mang" href="#pablo">{{ $titlePage }}</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
         <ul class="navbar-nav">
			@if(auth()->user()->role_id < 3)
				<!--<li class="nav-item">
					<a>
						<i class="iconclr_top fas fa-envelope-open-text"></i>					
						<p class="d-lg-none d-md-block">Stats</p>
					</a>
				</li> -->
		  @endif
		  @php
			if(auth()->user()->role_id == 4)
				$aNotoficationInfo = \App\ThesisProgressTrackings::select('thesis_progress_trackings.*')->join('message_viewes_tracking','message_viewes_tracking.track_id','=','thesis_progress_trackings.id')->where(['message_viewes_tracking.user_id' => auth()->user()->id,'thesis_progress_trackings.message_type' => 1,'view_flag' => 0])->where('thesis_progress_trackings.description', '!=', "")->get();
			else
				$aNotoficationInfo = \App\ThesisProgressTrackings::select('thesis_progress_trackings.*')->join('message_viewes_tracking','message_viewes_tracking.track_id','=','thesis_progress_trackings.id')->where(['message_viewes_tracking.user_id' => auth()->user()->id,'view_flag' => 0])->where('thesis_progress_trackings.description', '!=', "")->get();
			$vNotifyCount = count($aNotoficationInfo) ? count($aNotoficationInfo) : "";			
		  @endphp
		  <li class="nav-item dropdown" style="position:relative !important;">
			@if($vNotifyCount != "")
            <a class="nav-link pt-1" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="material-icons pt-2"  style="font-size: 40px; color:#ffffff;">notifications</i>				
					<span class="notification">{{ $vNotifyCount }}</span>				
              <p class="d-lg-none d-md-block">
                Some Actions
              </p>
            </a>
			@else				
				  <i class="material-icons pt-2" style="font-size: 40px; color:#ffffff;">notifications</i>										
				  <p class="d-lg-none d-md-block">
					Some Actions
				  </p>				
			@endif
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
				@if(count($aNotoficationInfo) > 0) 
					@for($notify_loop = 0; $notify_loop < count($aNotoficationInfo); $notify_loop++)
						@php
							$vRePath = "#";
							
							$aMsgUserInfo =  \App\User::select('users.*')->where('users.id','=',$aNotoficationInfo[$notify_loop]->user_id)->get();
							$vCapReqDetails = \App\ThesisRequestDetails::select('thesis_request_details.*')->where('thesis_request_details.item_id','=',$aNotoficationInfo[$notify_loop]->item_id)->get();
							
							if(auth()->user()->role_id == 4)
								$vRePath = route('mythesis.detail',array('tab'=>2) );
							if(count($vCapReqDetails) > 0) {
								if($vCapReqDetails[0]->manager_approval_status == 0 && auth()->user()->role_id == 2)
									$vRePath = route('item.approve',array("id" => $aNotoficationInfo[$notify_loop]->item_id,'tab'=>2) );						
								elseif($vCapReqDetails[0]->manager_approval_status == 1 && auth()->user()->role_id == 2)
									$vRePath = route('mythesis.detail',array($aNotoficationInfo[$notify_loop]->item_id.'&tab=2'));
								elseif($vCapReqDetails[0]->manager_approval_status == 1 && $vCapReqDetails[0]->supervisor_acceptence_status == 0 && auth()->user()->role_id == 3)
									$vRePath = route('mythesis.detail',array($aNotoficationInfo[$notify_loop]->item_id.'&tab=2'));
								elseif($vCapReqDetails[0]->manager_approval_status == 1 && $vCapReqDetails[0]->supervisor_acceptence_status == 1 && auth()->user()->role_id == 3)
									$vRePath = route('mythesis.detail',array($aNotoficationInfo[$notify_loop]->item_id.'&tab=2'));
							}
						@endphp
						<a class="dropdown-item reply_clr" href="#" onclick="funUpdateViewStatus({{$aNotoficationInfo[$notify_loop]->item_id}},'{{ $vRePath }}')">
							<div class="timeline-inverted">
								<div class="timeline-panel">
									<div class="timeline-body cht_text">
									{{ $aMsgUserInfo[0]->name }}
									</div>									
									@if($aNotoficationInfo[$notify_loop]->action_type > 0)
										<div class="timeline-body cht_text">
										<span style="font-weight:bold;">{{ __('Action: ') }}</span>{{__(config('items.action_options')[$aNotoficationInfo[$notify_loop]->action_type])}}
										</div>
									@else
										<div class="timeline-body cht_text">
										<span style="font-weight:bold;">{{ __('Action: ') }}</span>Posted a message
										</div>
									@endif									
									<div class="timeline-body cht_text" style="max-width:30.9rem; display: inline-block; word-wrap: break-word !important; white-space:initial;">
									{!! $aNotoficationInfo[$notify_loop]->description !!}
									</div>
									<div class="timeline-body">
										<span class="badge badge-pill badge-info">Date: {{ \Carbon\Carbon::parse($aNotoficationInfo[$notify_loop]->created_date)->format('d-m-Y h:i:s')}}</span>
									</div>
								</div>
							</div>
						</a>				   
					@endfor 
				@endif
            </div>
          </li>		  
		   <li class="nav-item dropdown">
				<a class="nav-item  nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">					
					<img src="{{ config('items.image_auth.path') }}{{ auth()->user()->profilePicture() }}" style="width:2.8rem; height:2.8rem;border-radius: 30px;"  />			  
					<p class="d-lg-none d-md-block">
					  {{ __('Account') }}
				  </p>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
					<a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>					
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log Out') }}</a>
				</div>
			<!-- fresh work end -->
			</li>		  
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
@push('js') 
	<script>
	  function funUpdateViewStatus(pmItemID,redirectPath) {		
			var vMsgPath = '{{ url("/item/update-msgviewed") }}';		
			$.ajax({
			   type: 'POST',
			   url: vMsgPath,		   
			   data: {
				   "_token": "{{ csrf_token() }}",			  
					"msg_item_id": pmItemID
			   },
			   success: function( msg ) {
				 window.location.href = redirectPath;
			   }
		   });	   
		}
	</script>
@endpush