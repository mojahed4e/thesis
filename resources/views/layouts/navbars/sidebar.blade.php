<div class="sidebar" data-color="182B49 " data-background-color="azure">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

    Tip 2: you can also add an image using data-image tag
-->
  <div class="logo">
    <a href="{{ route('home') }}" class="simple-text logo-normal">
      <img width="80%" height="50%" src="{{ asset('material') }}/img/welcome_logo.png">
    </a>    
  </div>
  <div class="sidebar-wrapper">
    <div class="user">
      <div class="photo">	  
        <img src="{{ config('items.image_auth.path') }}{{ auth()->user()->profilePicture() }}" />
      </div>
      <div class="user-info">
        <a data-toggle="collapse" href="#collapseExample" class="username">
          <span>
            {{ auth()->user()->name }}
            <b class="caret"></b>
          </span>
        </a>
        <div class="collapse" id="collapseExample">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('profile.edit') }}">
                <i class=" icon_clr fas fa-user-tie"></i>
                <span class="sidebar-normal"> My Profile </span>
              </a>
            </li>           
			       @can('manage-users', App\User::class)
              <li class="nav-item{{ $activePage == 'role-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('role.index') }}">
				          <i class=" icon_clr far fa-address-book"></i>                  
                  <span class="sidebar-normal"> {{ __('Role Management') }} </span>
                </a>
              </li>
            @endcan
            @can('manage-users', App\User::class)
              <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('user.index') }}">
				          <i class=" icon_clr fas fa-user-cog"></i>                  
                  <span class="sidebar-normal"> {{ __('User Management') }} </span>
                </a>
              </li>
            @endcan
            @can('manage-users', App\User::class)
              <li class="nav-item{{ $activePage == 'archive-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('item.archive') }}">
                  <i class=" icon_clr fas fa-sliders-h"></i>                  
                  <span class="sidebar-normal"> {{ __('Archived Thesis') }} </span>
                </a>
              </li>
            @endcan 			
            @can('manage-items', App\User::class)
				    @if(auth()->user()->role_id != 3)
    					<li class="nav-item{{ $activePage == 'category-management' ? ' active' : '' }}">
    						<a class="nav-link" href="{{ route('category.index') }}">
    							 <i class=" icon_clr fas fa-clipboard-list"></i>							 
    							 <span class="sidebar-normal"> {{ __('Category Management') }} </span>										
    						</a>
    					</li>
    				@endif
            @endcan
            @can('manage-items', App\User::class)
      				@if(auth()->user()->role_id == 1)
      				  <li class="nav-item{{ $activePage == 'term-management' ? ' active' : '' }}">
      					<a class="nav-link" href="{{ route('term.index') }}">
      						 <i class=" icon_clr fas fa-clipboard-list"></i>						 
      						<span class="sidebar-normal"> {{ __('Cohort Management') }} </span>										
      					</a>
      				  </li>
      			  @endif
            @endcan	
          </ul>
        </div>
      </div>
    </div>
    <ul class="nav sid_text">
	  @if(auth()->user()->role_id < 3)
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="icon_clr fas fa-chart-pie"></i>
            <span class="sidebar-normal"> {{ __('Dashboard') }}</span>
        </a>
      </li>        
	  @endif
      <li class="nav-item {{ ($menuParent == 'laravel' || $activePage == 'dashboard') ? ' active' : '' }}">
        <a class="nav-link" style="padding:0px;" data-toggle="collapse" href="#laravelExample" {{ ($menuParent == 'laravel' || $activePage == 'dashboard') ? ' aria-expanded="true"' : '' }}>          
        </a>
        <div class="collapse {{ ($menuParent == 'dashboard' || $menuParent == 'laravel') ? ' show' : '' }}" id="laravelExample">
          <ul class="nav">
            @if(auth()->user()->role_id < 3)
            <li class="nav-item{{ $activePage == 'thesis-timeline' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('timeline.view-thesis-timeline') }}">
                <i class="icon_clr fas fa-sliders-h"></i>
                  <span class="sidebar-normal"> {{ __('Thesis Timeline') }}</span>
              </a>
            </li>
            @endif 
            @can('manage-items', App\User::class)
              <li class="nav-item{{ $activePage == 'item-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('item.index') }}">
				          <i class=" icon_clr fas fa-sliders-h"></i>
                  <span class="sidebar-normal"> {{ __('Thesis Management') }} </span>
                </a>
              </li>
            @else
              <li class="nav-item{{ $activePage == 'item-allotted' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('item.previous-thesis') }}">
                <i class=" icon_clr fas fa-sliders-h"></i>
                  <span class="sidebar-normal"> {{ __('Thesis Archive') }} </span>
                </a>
              </li>
              <li class="nav-item{{ $activePage == 'item-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('item.index') }}">
				        <i class=" icon_clr fas fa-sliders-h"></i>
                  <span class="sidebar-normal"> {{ __('Thesis Listing') }} </span>
                </a>
              </li>
            @endcan					
			@php
				$chkassigned = \App\Item::Status()->where(['assigned_to' => auth()->user()->id,['items.status','!=',3]])->get();
				$thesis_assigned = 0;
				if(count($chkassigned) > 0) {			
					$thesis_assigned = 1;
				}
			@endphp
			@if((auth()->user()->role_id == 3 || auth()->user()->role_id == 2)  && $thesis_assigned == 1 )
			  <li class="nav-item{{ $activePage == 'item-assigned' ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('mythesis.assigned') }}">
	          <i class=" icon_clr fas fa-sliders-h"></i>
            <span class="sidebar-normal"> {{ __('Assigned Thesis') }} </span>
          </a>
        </li>
			@endif
      @php
        $chkExamine = \App\PanelMembers::Status()->where(['user_id' => auth()->user()->id,['panel_members.status','!=',2]])->get();
        $examine_thesis = 0;
        if(count($chkExamine) > 0) {     
          $examine_thesis = 1;
        }
      @endphp
      @if($examine_thesis == 1 )
        <li class="nav-item{{ $activePage == 'item-examine' ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('mythesis.examine') }}">
            <i class=" icon_clr fas fa-sliders-h"></i>
            <span class="sidebar-normal"> {{ __('Examine Thesis') }} </span>
          </a>
        </li>
      @endif
			@php
				$requested = 0;				
				$chkrequest = \App\Item::Status()->where('requested_by', '=', auth()->user()->id)->get();			
				if(count($chkrequest) > 0) {			
					$requested = 1;
				}
				else {
					$aMemeberThesis = \App\GroupMember::select('group_members.*')->join('items','items.id','=','group_members.item_id')->where(['group_members.user_id' => auth()->user()->id,['items.status','!=',3]])->get();
					if(count($aMemeberThesis) > 0) {
						$requested = 1;						
					}
				}
				$thesis_alloted = $requested;
			@endphp			
			@if(auth()->user()->role_id == 4 && $thesis_alloted == 1 )
			  <li class="nav-item{{ $activePage == 'track-activity' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('mythesis.detail') }}">
				          <i class="material-icons" style="font-size:25px;">description</i>
                  <span class="sidebar-normal"> {{ __('My Thesis') }} </span>
                </a>
              </li>			  
			@endif
      <li class="nav-item {{ $activePage == 'template-folder' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('templates.view-folders-files') }}">
          <i class="material-icons" style="font-size:25px;">folder</i>
          <span class="sidebar-normal">{{__('Document Templates')}} </span>
        </a>
      </li>
			<li class="nav-item">
				<a class="nav-link" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
					<i class=" icon_clr fas fa-sign-out-alt fa-flip-horizontal" aria-hidden="true"></i>
					<span class="sidebar-normal">Log Out </span>
				</a>
			</li>
			
          </ul>
        </div>
      </li>    
    </ul>
  </div>
</div>
