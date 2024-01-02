@extends('layouts.app', ['activePage' => 'term-management', 'menuParent' => 'laravel', 'titlePage' => __('Term Management')])

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
                <h4 class="card-title">{{ __('Terms') }}</h4>
              </div>
              <div class="card-body">
                @can('create', App\Term::class)
                  @if(auth()->user()->manager_flag != 2)
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="{{ route('term.create') }}" class="btn bt_styl btn_txtbold">{{ __('Add Term') }}</a>
                      </div>
                    </div>
                  @endif
                @endcan
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Name') }}
                      </th>
					  <th class="view_word" style="font-weight:bold;">
                          {{ __('Academic Year') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Description') }}
                      </th>
                      @can('manage-special-items', App\User::class)
                        <th class="view_word text-right" style="font-weight:bold;">
                          {{ __('Actions') }}
                        </th>
                      @endcan
                    </thead>
                    <tbody>
					@if(count($terms) > 0)
                      @foreach($terms as $term)						
						<tr>
						  <td>
							{{ $term->name }}
						  </td>
						  <td>
							{{ $term->academic_year }}
						  </td>
						  <td>
							{{ $term->description }}
						  </td>		
						@can('manage-items', App\User::class)
						@if (auth()->user()->can('update', $term) || auth()->user()->can('delete', $term))
						  <td class="td-actions text-right">
							<form action="{{ route('term.destroy', $term) }}" method="post">
								@csrf
								@method('delete')
								
								@can('update', $term)
								  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('term.edit', $term) }}" data-original-title="" title="">
									<i class="material-icons">edit</i>
									<div class="ripple-container"></div>
								  </a>
								@endcan
								@if ($term->items->isEmpty() && auth()->user()->can('delete', $term) && auth()->user()->manager_flag != 2)
								  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this term?") }}') ? this.parentElement.submit() : ''">
									  <i class="material-icons">close</i>
									  <div class="ripple-container"></div>
								  </button>
								@endif
							</form>
						  </td>
						@endif
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