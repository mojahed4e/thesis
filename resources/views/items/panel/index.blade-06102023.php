@if(auth()->user()->role_id == 4)
	@php
	$header = array('activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Listing'));
	$pagetitle = 'Thesis Listing';
	@endphp
@else
	@php
	$header = array('activePage' => 'item-management', 'menuParent' => 'laravel', 'titlePage' => __('Thesis Management'));
	$pagetitle = 'Thesis Listing';
	@endphp
@endif
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
              	<form method="post" enctype="multipart/form-data" name="frmCommentUpdate" id="frmCommentUpdate" action="" autocomplete="off" class="form-horizontal">
              	@csrf					
								@method('post')             		  
                @can('create', App\Item::class)                	
                	@if(auth()->user()->manager_flag != 2)
                		<div class="row">
                					<div class="col-1 form_chg text-right pt-2">														
															<p>{{ __('Filters') }}:</p>														
													</div>											
													<div class="col-2 form_chg text-left">
															<span>
																<select class="form-control selectpicker" data-style="btn btn-link" id="selCohort" onchange="funFilterTableData(this.value,3)">
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
																<select class="form-control selectpicker" data-style="btn btn-link" id="selProgram" onchange="funFilterTableData(this.value,4)">
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
                        {{ __('Status') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Term - I') }}<br/>{{ __('Presentation') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Term - I') }}<br/>{{ __('Chapter - II') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
				               {{ __('Grade') }}
				             	</th>													  
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Term - II') }}<br/>{{ __('Presentation') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Term - II') }}<br/>{{ __('Chapter - II') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
				               {{ __('Grade') }}
				             	</th> 				      
                    </thead>
                    <tbody class="cht_text">
											@foreach($items as $item)						
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
			                    @endif
                          </td>						  						
                          @if (Auth::user()->can('manage-items', App\User::class))
	                          <td>
	                            {{ !empty($item->program->name) ? $item->program->name : '--' }}
	                          </td>	
	                        @endif					 
	                        <td>
                            {{ !empty($item->term->name) ? $item->term->name : '--' }}
                          </td>
                          <td>						  
                          	@if($item->status == 1 && $item->requested_by > 0 && $item->approval_status != 1)
															<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																<div class="ripple-container" style="width:90px">Requested</div>
															</button>
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
                          <td>
                          	<input type="hidden" name="item_id" id="item_id" value="{{$item->id}}">
														<input type="hidden" name="rubricterm" id="rubricterm" value="">
														<input type="hidden" name="rubrictype" id="rubrictype" value="">
                            @php
	                          	$term1presentaion =Illuminate\Support\Facades\DB::table('terms_progress_checklist')->select('terms_progress_checklist.*')
            											->where(['terms_progress_checklist.item_id' => $item->id, 'terms_progress_checklist.student_upload_status' => 2 , 
            											'terms_progress_checklist.document_type' => "presentationfile",'terms_progress_checklist.checklist_type' => 1])->get();
            									$aRubricTerm1Info = Illuminate\Support\Facades\DB::table('thesis_rubric_details')->select('thesis_rubric_details.*')
            											->where(['thesis_rubric_details.item_id' => $item->id, 'thesis_rubric_details.created_by' => Auth::user()->id, 
            											'thesis_rubric_details.rubric_term' => 1,
            											'thesis_rubric_details.rubric_type' => 2])->get();            									
	                      		@endphp	                      		
														@if(count($term1presentaion) > 0)																
															<div class="col-7 cht_text pt-1">					
																<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term1presentaion[0]->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
															</div>
														@else
															<span class="view_word" data-original-title="" style="font-size: 14px; color:green !important;"  title="" onclick="#">
																	Awaiting for <br />file Approval
															</span>
														@endif
                          </td>
                          <td>                          	
                            @php	                          
            									$term1Chapter2 =Illuminate\Support\Facades\DB::table('terms_progress_checklist')->select('terms_progress_checklist.*')
            											->where(['terms_progress_checklist.item_id' => $item->id, 'terms_progress_checklist.student_upload_status' => 2 , 
            											'terms_progress_checklist.document_type' => "chapter2report",'terms_progress_checklist.checklist_type' => 1])->get();
	                      		@endphp	                      		
														@if(count($term1Chapter2) > 0)																
															<div class="col-7 cht_text pt-1">																					
																<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term1presentaion[0]->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
															</div>
														@else
															<span class="view_word" data-original-title="" style="font-size: 14px; color:green !important;"  title="" onclick="#">
																	Awaiting for <br />file Approval
															</span>
														@endif
                          </td>
                          <td>                          
												  @if(count($term1presentaion) > 0)
															@if(count($aRubricTerm1Info) > 0)
																<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="javascript:void(0)" onclick="funViewRubric(1,2)" data-original-title="" title="">View Rubric</a>
															@else
																<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="javascript:void(0)" onclick="funPrepareRubric(1,2)" data-original-title="" title="">Prepare Rubric</a>
															@endif
														@else
															<span  data-original-title="" style="cursor:default;color:red;"  title="" onclick="#">
																Pending
															</span>
														@endif
												  </td>
												  <td>
												   	@php											   									   	
												  	$termIIpresentaion = Illuminate\Support\Facades\DB::table('terms_progress_checklist')->select('terms_progress_checklist.*')
	            											->where(['terms_progress_checklist.item_id' => $item->id, 'terms_progress_checklist.student_upload_status' => 2 , 
	            											'terms_progress_checklist.document_type' => "presentationfile",'terms_progress_checklist.checklist_type' => 2])->get();
	            							$aRubricTerm2Info = Illuminate\Support\Facades\DB::table('thesis_rubric_details')->select('thesis_rubric_details.*')
	            											->where(['thesis_rubric_details.item_id' => $item->id, 'thesis_rubric_details.created_by' => Auth::user()->id, 
	            											'thesis_rubric_details.rubric_term' => 2,
	            											'thesis_rubric_details.rubric_type' => 2])->get();
	                      		@endphp	                      																
														@if(count($termIIpresentaion) > 0)																				
																<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term1presentaion[0]->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
														@else
															<span class="view_word" data-original-title="" style="font-size: 14px; color:green !important;"  title="" onclick="#">
																Awaiting for <br />file Approval
															</span>
														@endif													 
												  </td>
												   <td>
												    @php	                          
            								$term1Chapter2 =Illuminate\Support\Facades\DB::table('terms_progress_checklist')->select('terms_progress_checklist.*')
            											->where(['terms_progress_checklist.item_id' => $item->id, 'terms_progress_checklist.student_upload_status' => 2 , 
            											'terms_progress_checklist.document_type' => "chapter2report",'terms_progress_checklist.checklist_type' => 2])->get();
	                      		@endphp	                      																
														@if(count($term1Chapter2) > 0)																				
																<span class="fileinput-new"><a href="{{ route('download.viewfile', [$term1presentaion[0]->id,'type=term']) }}" target="_blank" style="color:rgb(61, 68, 101) !important;text-decoration: underline;">{{ __('View File') }}</a></span>
														@else
															<span class="view_word" data-original-title="" style="font-size: 14px; color:green !important;"  title="" onclick="#">
																Awaiting for <br />file Approval
															</span>
														@endif													 
												  </td>
												  <td>
												  	@if(count($termIIpresentaion) > 0)
															@if(count($aRubricTerm1Info) > 0)
																<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="javascript:void(0)" onclick="funViewRubric(2,2)" data-original-title="" title="">View Rubric</a>
															@else
																<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="javascript:void(0)" onclick="funPrepareRubric(2,2)" data-original-title="" title="">Prepare Rubric</a>
															@endif
														@else
															<span  data-original-title="" style="cursor:default;color:red;"  title="" onclick="#">
																Pending
															</span>
														@endif
												  </td>
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