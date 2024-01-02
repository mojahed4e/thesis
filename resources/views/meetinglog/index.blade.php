@extends('layouts.app', ['activePage' => 'metting-logs', 'menuParent' => 'laravel', 'titlePage' => __('Meeting Logs Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">category</i>
                </div>
                <h4 class="card-title">{{ __('Meeting Logs') }}</h4>
              </div>
              <div class="card-body">
                @can('create', App\MeetingLogs::class)
                  <div class="row">
					<div class="col-12 text-right">
                      <a href="{{ route('mythesis.detail' ,request()->id.'&action=ac')}}" class="btn btn-sm btn-rose">{{ __('Back to Thesis Details') }}</a>                    
                      <a href="{{ route('meetinglogs.create','id='.request()->id) }}" class="btn btn-sm btn-rose">{{ __('Add Meeting Log') }}</a>
                    </div>
                  </div>
                @endcan
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" style="display:none">
                    <thead class="text-primary">
                      <th>
                          {{ __('Thesis Tile') }}
                      </th>
					  <th>
                          {{ __('Meeting Minutes Term') }}
                      </th>
                      <th>
                        {{ __('Approval Status') }}
                      </th>
                      @can('manage-meetinglogs', App\User::class)
                        <th class="text-right">
                          {{ __('Actions') }}
                        </th>
                      @endcan
                    </thead>
                    <tbody>
					@if(count($meetinglogs) > 0)
                      @foreach($meetinglogs as $log)						
						<tr>
						  <td>
							{{ $log->title }}
						  </td>
						  <td>
							@if($log->meeting_log_type == 1)
								{{ __('Term - I') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __(' Meeting Minutes -  ').$log->meeting_log_seq }}
							@elseif($log->meeting_log_type == 2)
								{{ __('Term - II') }}
							@else
								{{ __('Term - III') }}
							@endif							
						  </td>
						  <td>
							@if($log->approval_status ==  1)
								<button type="button" class="btn btn-success btn-link" data-original-title="" style="cursor:default" title="" onclick="#">
									<div class="ripple-container" style="overflow:visible;">
										Approved
									</div>
								</button>
							@else
								<button type="button" class="btn btn-danger btn-link" data-original-title="" style="cursor:default" title="" onclick="#">
									<div class="ripple-container" style="overflow:visible;">
										Pending
									</div>
								</button>
							@endif
						  </td>		
						 @can('update', App\MeetingLogs::class)					
						  <td class="td-actions text-right">
								@can('update', App\MeetingLogs::class)	
								  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('meetinglogs.edit', [$log,'item_id=1&action=ac']) }}" data-original-title="" title="">
									<i class="material-icons">edit</i>
									<div class="ripple-container"></div>
								  </a>
								@endcan								
							</form>
						  </td>						
						@endcan
						</tr>						
                      @endforeach
					  @else
						<tr>
						  <td calss="col-4 text-center">
							{{ __('No Records Availble') }}
						  </td>						  
						</tr>
					  @endif
					  
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
        [15, 25, 50, -1],
        [15, 25, 50, "All"]
      ],
      responsive: false,
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search terms",
      },
      "columnDefs": [
        { "orderable": false, "targets": 3 },
      ],
    });
  });
</script>
@endpush