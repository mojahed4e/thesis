@extends('layouts.app', ['activePage' => 'item-assigned', 'menuParent' => 'laravel', 'titlePage' => __('Assigned Thesis Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('Thesis Listing') }}</h4>
              </div>
              <div class="card-body">			                  
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
					                        {{ __('Cohort') }}
					                      </th>					  
										  <th class="view_word" style="font-weight:bold;">
					                        {{ __('Keywords') }}
					                      </th>
										  @if (Auth::user()->can('manage-items', App\User::class))
										  <th class="view_word" style="font-weight:bold;">
					                        {{ __('Published') }}
					                      </th>
										  <th class="view_word" style="font-weight:bold;">
					                        {{ __('Status') }}
					                      </th>
										  @endif
					                      @if ( Auth::user()->can('manage-items', App\User::class) || Auth::user()->can('manage-views',  App\User::class) )
										  <th class="view_word text-right" style="font-weight:bold;">
					                          {{ __('Actions') }}
					                      </th>
										  @endif 				      
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
                            {{ $item->term->name }}
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
															</a>
														@else
															<button type="button" class="btn btn-danger btn-link" data-original-title="" style="cursor:default" title="" onclick="#">
																<i class="material-icons">clear</i>
															</a>
														@endif
                          </td>
						  						<td>						  
                            @if($item->status == 1 && $item->requested_by > 0 && $item->approval_status != 1)
															<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																<div class="ripple-container" style="width:70px">Requested</div>
															</a>
														@elseif ($item->status == 1 && $item->requested_by > 0 && $item->approval_status == 1)
															@foreach ($requestinfo as $request)
																@if($request->item_id == $item->id)
																	<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																		<div class="ripple-container" style="overflow:visible;">
																		@php												
																			$term = (int) $request->completion_by_manager+1;												
																			$aTermChecklist = \App\TermProgressChecklist::select('terms_progress_checklist.*')
																							 ->orderBy('terms_progress_checklist.sequence', 'asc')
																				            ->where([['terms_progress_checklist.item_id','=', $item->id],['terms_progress_checklist.upload_file_status','=',0],['terms_progress_checklist.approval_status','=',2],['terms_progress_checklist.checklist_type','=', $term],['terms_progress_checklist.status','=',1]])->get();												
																		@endphp
																		@if($request->manager_approval_status == 1 && $request->supervisor_acceptence_status == 0)												
																			{{ __('Awaiting for') }} <br /> {{ __('Suppervisor') }}<br /> {{ __('Acceptance') }}
																		@elseif($request->completion_by_manager == 0 && $request->completion_by_supervisor == 0)

																			{{ __('Term - I') }}<br /> {{ __('In Progress') }}
																		@elseif($request->completion_by_manager == 1 && $request->completion_by_supervisor == 1)

																			{{ __('Term - II') }}<br /> {{ __('In Progress') }}
																		@elseif($request->completion_by_manager == 1 && $request->completion_by_supervisor == 2)											
																			{{ __('Awaiting for') }} <br /> {{ __('Term II') }}<br /> {{ __('Completion Approval') }}
																		@elseif($request->completion_by_manager == 2 && $request->completion_by_supervisor == 2)

																			{{ __('Term - III') }}<br /> {{ __('In Progress') }}
																		@elseif($request->completion_by_manager == 2 && $request->completion_by_supervisor == 3)												
																			{{ __('Awaiting for') }} <br /> {{ __('Term III') }}<br /> {{ __('Completion Approval') }}												
																		@elseif($request->completion_by_manager == 3 && $request->completion_by_supervisor == 3)												
																			{{ __('Completed') }} <br /> {{ __('Successfully') }}
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
																			{{ __('Awaiting for') }} <br /> {{ __('Manager') }}<br /> {{ __('Approval') }}	
																		@endif
																		</div>
																	</a>
																@endif
															@endforeach	
														@elseif ($item->requested_by == 0 && $item->status == 1)
															<button type="button" class="btn bt_styl btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																<div class="ripple-container" style="width:70px">Available</div>
															</a>
														@else
															<button type="button" class="btn bt_styl btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
																<div class="ripple-container" style="width:70px">Draft</div>
															</a>
														@endif
							              </td>
													  @endif
                            @can('manage-items', App\User::class)
                            @if (auth()->user()->can('update', $item) || auth()->user()->can('delete', $item) || auth()->user()->can('detail', $item))
                              <td class="td-actions text-right">
                                <form action="{{ route('item.destroy', $item) }}" method="post">
                                    @csrf
                                    @method('delete')
									
									@if($item->approval_status == 1)
										@can('update', $item)
											@foreach ($requestinfo as $request)	
												@if($request->item_id == $item->id)
													@if($request->id == $item->request_detail_id &&  $request->supervisor_acceptence_status == 1)
														<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('mythesis.detail', [$item->id.'&action=ac']) }}" data-original-title="" title="">					
														View Progress
														</a>
													@elseif(($request->id == $item->request_detail_id &&  $request->supervisor_acceptence_status == 0 && auth()->user()->role_id == 3) || (auth()->user()->role_id == 2 && $item->assigned_to == auth()->user()->id))
														<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('mythesis.detail', [$item->id.'&action=ac']) }}" data-original-title="" title="">
														Accept Request
														</a>
													@elseif(auth()->user()->role_id == 2)
														<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('mythesis.detail', [$item->id]) }}" data-original-title="" title="">							
														View Progress
														</a>
													@endif
												@endif
											@endforeach
										@endcan
									@elseif($item->requested_by)
										@if (auth()->user()->can('delete', $item))
										<a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 0.60rem;line-height: 0.70;" href="{{ route('item.approve', [$item->id]) }}" data-original-title="" title="">								
											Approve
										</a>
										@else
											<div class="btn bt_styl text-capitalize">Approval Pending</div>
										@endif										
									@endif                                    										
                                   @if (($item->requested_by == 0 && $item->assigned_to == 0 && $item->approval_status == 0) && auth()->user()->can('delete', $item))
                                      <button type="button" class="btn" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this thesis?") }}') ? this.parentElement.submit() : ''">
                                          <i class="material-icons" style="font-size: 2rem;">cancel</i>
                                          <div class="ripple-container"></div>
                                      </button>
                                    @endcan
                                </form>
                              </td>
                            @endif
                          @endcan
						  @can('manage-views', App\User::class)						  
						  <td class="text-right">
							  <a rel="tooltip" class="btn bt_styl text-capitalize" style="padding: 0.40625rem 1.00rem;line-height: 1.00;" href="{{ route('item.detail', [$item->id]) }}" data-original-title="" title="">								
								view
							  </a>
						  </td>
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

@push('js')  
  <script>
    $(document).ready(function() {
      $('#datatables').fadeIn(1100);
      $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 100, -1],
          [10, 25, 100, "All"]
        ],
        responsive: false,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search Thesis",
        },
        "columnDefs": [
          { "orderable": false, "targets": 6 },
        ],
      });
    });
  </script>
@endpush