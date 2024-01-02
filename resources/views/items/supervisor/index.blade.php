@php
$header = array('activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Management'));
$pagetitle = 'Thesis Listing';
@endphp

@extends('layouts.app',  $header )

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">                
                <h4 class="card-title view_word">{{ __($pagetitle) }}</h4>
              </div>
              <div class="card-body">	
              	@php								
								$group_member = 0;								
								$aMemeberthesis = \App\GroupMember::join('items','items.request_detail_id','=','group_members.request_detail_id')->where(['group_members.user_id' => auth()->user()->id,['items.status','!=',3]])->get();

								if(count($aMemeberthesis) > 0) {
									$requested = 1;	
									$group_member = 1;
								}
								@endphp		  
                @can('create', App\Item::class)                	
                	@if(auth()->user()->manager_flag != 2)
                		<div class="row">
                					<div class="col-1 form_chg text-right pt-2">														
															<p>{{ __('Filters') }}:</p>														
													</div>												
													<div class="col-2 form_chg text-left">
															<span>
																<select class="form-control selectpicker" data-style="btn btn-link" id="selCohort" onchange="funFilterTableData(this.value,4)">
														      <option value="0">Show All</option>
														      @if(count($cohorts) > 0)
															      @foreach ($cohorts as $cohort)
						                         	<option value="{{ $cohort->name }}" {{ $cohort->id == old('selCohort') ? 'selected' : '' }}>{{ $cohort->name }}</option>
						                        @endforeach
						                      @endif
												    		</select>
												    	</span>
													</div>
													<div class="col-2 form_chg text-left">
															<span>
																<select class="form-control selectpicker" data-style="btn btn-link" id="selProgram" onchange="funFilterTableData(this.value,3)">
														      <option value="0">Show All</option>
														      @if(count($programs) > 0)
															      @foreach ($programs as $program)
						                         	<option value="{{ $program->description }}" {{ $program->id == old('selProgram') ? 'selected' : '' }}>{{ $program->name }}</option>
						                        @endforeach
						                      @endif
												    		</select>
												    	</span>
													</div>
													<div class="col-2 form_chg text-left">
															<span>
																<select class="form-control selectpicker" data-style="btn btn-link" id="selSupervisor" onchange="funFilterTableData(this.value,2)">
														      <option value="0">Show All</option>
														      @if(count($supervisors) > 0)
															      @foreach ($supervisors as $supervisor)
						                         	<option value="{{ $supervisor->name }}" {{ $supervisor->id == old('selAcademicyear') ? 'selected' : '' }}>{{ $supervisor->name }}</option>
						                        @endforeach
						                      @endif
												    		</select>
												    	</span>
													</div>
														<div class="col-2 form_chg text-left">
															<span>
																<a href="javascript:void(0)" onclick="funResetFilters()" class="btn bt_styl btn_txtbold" style="width:140px; height:35px;font-size: 12px;">{{ __('Reset Filters') }}</a>
												    	</span>
													</div>
													<div class="col-3 text-right">
		                      	<a href="{{ route('item.create') }}" class="btn bt_styl btn_txtbold"><i class="fas fa-sliders-h pr-2"></i>{{ __('Add Thesis') }}</a>
		                    </div>
										</div>	                 
	                @endif
                @endcan               
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Thesis Title') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Project By') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Supervisor') }}
                      </th>					  					
                      @if (Auth::user()->can('manage-items', App\User::class))
	                      <th class="view_word" style="font-weight:bold;">
	                        {{ __('Program') }}
	                      </th>		
	                    @endif			  
	                    <th class="view_word" style="font-weight:bold;">
                        {{ __('Cohort') }}
                      </th>
					  					<th class="view_word" style="font-weight:bold;">
                        {{ __('Keywords') }}
                      </th>
										  @if (Auth::user()->can('manage-items', App\User::class))
										  	<th class="view_word" style="font-weight:bold;">
					               {{ __('Published') }}
					             	</th>
										  @endif
					  					<th class="view_word" style="font-weight:bold;">
                        {{ __('Status') }}
                      </th>													  
                      @if ( Auth::user()->can('manage-items', App\User::class) || Auth::user()->can('manage-views',  App\User::class) )
					  					<th class="view_word text-right" style="font-weight:bold;">
                          {{ __('Actions') }}
                      </th>
					  					@endif 				      
                    </thead>
                    <tbody class="cht_text">
											@foreach($items as $item)
												@php
                    		$aPreferedSuperVisor = \App\ItemAssignment::select('item_assignments.*')
																									 ->where([['item_assignments.user_id','=', Auth::user()->id],['item_assignments.item_id','=', $item->id],['item_assignments.status','=',1]])->get();
                    		@endphp
                        <tr>
                          <td>
                            {{ $item->name }}
                          </td>
                          <td>
                          @php
			                      $aUserInfo = \App\User::select('users.name')->where(['users.id' => $item->created_by])->get();
			                    @endphp                      
			                    @if(count($aUserInfo) > 0)
			                      {{ $aUserInfo[0]->name }}
			                    @endif
                          </td>
                          <td>
                          @php
			                      $aUserInfo = \App\User::select('users.name')->where(['users.id' => $item->assigned_to])->get();
			                    @endphp                      
			                    @if(count($aUserInfo) > 0)
			                      {{ $aUserInfo[0]->name }}
			                    @else
			                    	--
			                    @endif
                          </td>						  						
                          @if (Auth::user()->can('manage-items', App\User::class))
	                          <td>
	                            {{ !empty($item->program->name) ? $item->program->name : '--' }}
	                          </td>	
	                        @endif					 
	                        <td>
	                        	{{ !empty($item->term) ? $item->term->name : "--" }}                            
                          </td>
                          <td>
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
	                            		<br/>{{ $keyword->name }}
	                            	@endif
	                          		@php
	                          			$tagSeq++;
	                          		@endphp
	                          	@endforeach
	                          @endif 
                          </td>						  
												  @if (Auth::user()->can('manage-items', App\User::class))
													  <td>
														@if($item->status == 1)
															<button type="button" class="btn btn-success btn-link" data-original-title="" style="cursor:default" title="" onclick="#">
																<i class="material-icons">done</i>
															</button>
														@else
															<button type="button" class="btn btn-danger btn-link" data-original-title="" style="cursor:default"  title="" onclick="#">
																<i class="material-icons">clear</i>
															</button>
														@endif
													  </td>
												  @endif
												  <td>						  
                          	@if($item->status == 1 && $item->requested_by > 0 && $item->approval_status != 1)                          		
	                          		@if(count($aPreferedSuperVisor) == 0 && $item->assigned_to == 0 )
																	<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default; height: auto;" title="" onclick="#">
																		<div class="ripple-container" style="width:90px; overflow:visible;">{{ __('Awaiting for') }}  <br /> {{ __('Prefered Supervisor') }}<br /> {{ __('Selection') }}</div>
																	</button>
	                          		@else
																	<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																		<div class="ripple-container" style="width:90px">Requested</div>
																	</button>
																@endif
															@elseif ($item->status == 1 && $item->requested_by > 0 && $item->approval_status == 1)
																@foreach ($requestinfo as $request)
																	@if($request->id == $item->request_detail_id)
																		<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																			<div class="ripple-container" style="overflow:visible;min-width:80px;">
																			@php												
																				$term = (int) $request->completion_by_manager+1; 
																				$aTermChecklist = \App\TermProgressChecklist::select('terms_progress_checklist.*')
																								 ->orderBy('terms_progress_checklist.sequence', 'asc')
																					            ->where([['terms_progress_checklist.item_id','=', $item->id],['terms_progress_checklist.upload_file_status','=',0],['terms_progress_checklist.approval_status','=',2],['terms_progress_checklist.checklist_type','=', $term],['terms_progress_checklist.status','=',1]])->get();											
																			@endphp
																			@if($request->manager_approval_status == 1 && $request->supervisor_acceptence_status == 0)
																				{{ __('Awaiting for') }} <br /> {{ __('Suppervisor') }}<br /> {{ __('Acceptance') }}
																			@elseif(($request->completion_by_manager == 0 && $request->completion_by_supervisor == 0) || ($request->completion_by_manager == 0 && $request->completion_by_supervisor == 0))
																				{{ __('Term - I') }}<br /> {{ __('In Progress') }}
																			@elseif($request->completion_by_manager == 1 && $request->completion_by_supervisor == 1)
																				{{ __('Term - II') }}<br /> {{ __('In Progress') }}
																			@elseif($request->completion_by_manager == 1 && $request->completion_by_supervisor == 2)
																				{{ __('Awaiting for') }}  <br /> {{ __('Term II ') }}<br /> {{ __('Approval') }}
																			@elseif($request->completion_by_manager == 2 && $request->completion_by_supervisor == 2)
																				{{ __('Term - III') }}<br /> {{ __('In Progress') }}
																			@elseif($request->completion_by_manager == 2 && $request->completion_by_supervisor == 3)
																				{{ __('Awaiting for') }} <br /> {{ __('Term III ') }}<br /> {{ __(' Approval') }}
																			@elseif($request->completion_by_manager == 3 && $request->completion_by_supervisor == 3)
																				{{ __('Completed') }}
																			@elseif(count($aTermChecklist) > 0)
																				@if($aTermChecklist[0]->approval_status == 2)
																					@if($aTermChecklist[0]->checklist_type == 1)
																						{{ __('Term I ') }} <br />{{ __('Changes') }}<br />{{ __(' Requested') }}
																					@elseif($aTermChecklist[0]->checklist_type == 2)
																						{{ __('Term II ') }} <br />{{ __('Changes') }}<br />{{ __(' Requested') }}
																					@else
																						{{ __('Term III ') }} <br />{{ __('Changes') }}<br />{{ __(' Requested') }}
																					@endif
																				@endif
																			@else
																				{{ __('Awaiting for') }} <br />{{ __('Manager') }}<br />{{ __(' Approval') }}
																			@endif
																			</div>
																		</button>
																	@endif
																@endforeach	
															@elseif ($item->requested_by == 0 && $item->status == 1 && $item->request_approval_flag == 0)
																<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																	<div class="ripple-container" style="width:90px">Available</div>
																</button>
																@elseif ($item->requested_by == 0 && $item->status == 1 && $item->request_approval_flag == 1)
																<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																	<div class="ripple-container" style="overflow:visible;min-width:80px;">{{ __('Awaiting for') }} <br />{{ __('Supervisor') }}<br />{{ __(' Assignment') }}</div>
																</button>
															@else
																<button type="button" class="btn btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																	<div class="ripple-container" style="width:90px">Draft</div>
																</button>
															@endif
                          </td>
                            @can('manage-items', App\User::class)
                            @if (auth()->user()->can('update', $item) || auth()->user()->can('delete', $item) || auth()->user()->can('detail', $item))
                              <td class="td-actions text-right">
                                <form action="{{ route('item.destroy', $item) }}" method="post">
                                    @csrf
                                    @method('delete')                                    
																		@if($item->approval_status == 1 || $item->request_approval_flag == 1)
																			@can('update', $item)																						
																				@foreach ($requestinfo as $request)	
																					@if($request->item_id == $item->id)
																						@if($request->id == $item->request_detail_id &&  $request->supervisor_acceptence_status == 1 && $item->assigned_to == auth()->user()->id)
																							<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('mythesis.detail', [$item->id]) }}" data-original-title="" title="">							
																							View Progress
																							</a>
																						@elseif($request->id == $item->request_detail_id &&  $request->supervisor_acceptence_status == 0 && auth()->user()->role_id == 3)
																							@php
																							$aPreferedSuperVisor = \App\ItemAssignment::select('item_assignments.*')
																												 ->where([['item_assignments.item_id','=', $item->id],['item_assignments.user_id','=', auth()->user()->id], ['item_assignments.status','=',1]])->get();		
																							@endphp
																							@if(count($aPreferedSuperVisor) > 0 || $item->assigned_to == auth()->user()->id)
																								<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('mythesis.detail', [$item->id.'&action=ac']) }}" data-original-title="" title="">
																								Accept Assignment
																								</a>
																							@endif
																						@elseif(auth()->user()->role_id == 2)
																							<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('mythesis.detail', [$item->id.'&action=ac']) }}" data-original-title="" title="">
																							View Progress
																							</a>
																						@endif
																					@elseif($request->supervisor_acceptence_status == 1 && auth()->
																					user()->role_id == 2)																					
																							<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('mythesis.detail', [$item->id.'&action=ac']) }}" data-original-title="" title="">							
																							View Progress
																							</a>
																					@endif
																				@endforeach
																			@endcan
																		@elseif($item->requested_by)
																			@if (auth()->user()->can('delete', $item))
																			<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('item.approve', [$item->id.'&action=ac']) }}" data-original-title="" title="">								
																				Approve
																			</a>
																			@else
																				<div class="btn bt_styl text-capitalize">Approval Pending</div>
																			@endif										
																		@endif                                    
                                    @can('update', $item)
                                      <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('item.edit', $item) }}" data-original-title="" title="">
                                        <div class="icon_siz"><i class="far fa-edit"></i></div>
                                        <div class="ripple-container"></div>
                                      </a>
                                    @endcan										
                                   @if (($item->requested_by == 0 && $item->assigned_to == 0 && $item->approval_status == 0) && auth()->user()->can('delete', $item) && auth()->user()->manager_flag != 2)
                                      <button type="button" class="btn btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this thesis?") }}') ? this.parentElement.submit() : ''">
                                          <i class="material-icons" style="font-size: 2rem;">cancel</i>
                                          <div class="ripple-container"></div>
                                      </button>
                                    @endcan
                                </form>
                              </td>
                            @endif
                          @endcan
												  @can('manage-views', App\User::class)
												  @php								
													$group_member = 0;								
													$aMemeberThesis = \App\GroupMember::where(['group_members.user_id' => auth()->user()->id,['group_members.request_detail_id','=', $item->request_detail_id]])->get();

													if(count($aMemeberThesis) > 0) {
														$requested = 1;	
														$group_member = 1;
													}
												@endphp								
												  @if($item->requested_by == auth()->user()->id || $group_member == 1)		
													  <td class="text-right">
														  <a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 1.00rem;line-height: 1.00;" href="{{ route('mythesis.detail', [$item->id]) }}" data-original-title="" title="">								
															view my thesis
														  </a>
													  </td>
													  @if($item->created_by == auth()->user()->id && $item->approval_status == 0)
													  	<td class="text-right">
													  		<a rel="tooltip" class="btn btn-success btn-link" href="{{ route('item.editstudent-thesis', $item) }}" data-original-title="" title="">
													  			<div class="icon_siz"><i class="far fa-edit"></i></div>
						                    </a>
													  	</td>
													  @endif
												  @else
													  <td class="text-right">
														  <a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 1.00rem;line-height: 1.00;" href="{{ route('item.detail', [$item->id]) }}" data-original-title="" title="">								
															view
														  </a>
													  </td>
												  @endif							 
												  @endcan						  
                        </tr>                        
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
<!--
<style type="text/css">
table {
  table-layout:fixed;
}
table td {
  word-wrap: break-word;
  max-width: 50px;
}
#datatables td {
  white-space:initial;
}
</style>
-->
@push('js')  
  <script>
  	var table = "";
    $(document).ready(function() {
    	vUserRoleID = {{ auth()->user()->role_id}};
    	if(vUserRoleID	== 4)
    		vTargets = 5;
    	else
    		vTargets = 6;
		$('#datatables').fadeIn(1100);
    table = $('#datatables').removeAttr('width').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 100, -1],
          [10, 25, 100, "All"]
        ],
    responsive: false,
		fixedColumns: false,
		bAutoWidth: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search thesis",
        },
        "columnDefs": [
          { "orderable": false, "targets": vTargets },	
          //{ "width": 400, "targets":0},	 
        ],        
      });



   })

    function funFilterTableData(pmValue,pmID) {       
    	if(pmValue != 0){
    		$('#datatables')
        .DataTable()
        .column(pmID)
        .search(
            pmValue            
        )
        .draw();
    	}
    	else {    		
    		$('#datatables')
    		.DataTable()
        .column(pmID)
        .search("")
        .draw();
    	}
    }

    function funResetFilters() {    	
    	$('#selCohort').val(0).trigger( "change" );
    	$('#selProgram').val(0).trigger( "change" );
    	$('#selSupervisor').val(0).trigger( "change" );
    	$('input.form-control.form-control-sm').val('');
    	$('#datatables').DataTable().search("").draw();
    }
  </script>
@endpush