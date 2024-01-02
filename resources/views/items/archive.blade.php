@if(auth()->user()->role_id == 4)
	@php
	$header = array('activePage' => 'archive-management', 'menuParent' => 'laravel', 'titlePage' => __('Archive Thesis Listing'));
	$pagetitle = 'Archive Thesis Listing';
	@endphp
@else
	@php
	$header = array('activePage' => 'archive-management', 'menuParent' => 'laravel', 'titlePage' => __('Archive Thesis Management'));
	$pagetitle = 'Archive Thesis Listing';
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
                        {{ __('Category') }}
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
                            {{ $item->category->name }}
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
														<button type="button" class="btn btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
															<div class="ripple-container" style="width:90px">Archived</div>
														</button>
													  </td>						 
						 								<td>
                            	 @can('update', $item)
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('item.edit', [$item->id,'&ref=arch']) }}" data-original-title="" title="">
                                    <div class="icon_siz"><i class="far fa-edit"></i></div>
                                    <div class="ripple-container"></div>
                                  </a>
                                @endcan                                    									
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

@push('js')  
  <script>
    $(document).ready(function() {
		$('#datatables').fadeIn(1100);
      $('#datatables').removeAttr('width').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 100, -1],
          [10, 25, 100, "All"]
        ],
        responsive: false,
		fixedColumns: false,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search thesis",
        },
        "columnDefs": [
          { "orderable": false, "targets": 6 },		 
        ],
      });
	  
    });
  </script>
@endpush