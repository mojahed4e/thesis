@extends('layouts.app', ['activePage' => 'role-management', 'menuParent' => 'laravel', 'titlePage' => __('Role Management')])

@section('content')
  <div class="content bodybg">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="card-icon">                 
                </div>
                <h4 class="card-title view_word">{{ __('Roles') }}</h4>
              </div>
              <div class="card-body">
                @can('create', App\Role::class)
                  <!--<div class="row">
                    <div class="col-12 text-right">
                      <a href="{{ route('role.create') }}" class="btn btn-sm btn-rose">{{ __('Add role') }}</a>
                    </div>
                  </div> -->
                @endcan
                <div class="">				  
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Name') }}
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
                    <tbody class="cht_text">
                      @foreach($roles as $role)
						@if($role->id != 1)
							<tr>
							  <td class="sorting_1">
								{{ $role->name }}
							  </td>
							  <td class="sorting_1">
								{{ $role->description }}
							  </td>
							  @can('manage-users', App\User::class)
								<td class="td-actions text-right">
								  @can('update', $role)
									<a rel="tooltip" class="btn btn-success btn-link" href="{{ route('role.edit', $role) }}" data-original-title="" title="">
									  <div class="icon_siz"> <i class="far fa-edit"></i></div>
									  <div class="ripple-container"></div>
									</a>
								  @endcan
								</td>
							  @endcan
							</tr>
						@endif
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
        [15, 25, 50, -1],
        [15, 25, 50, "All"]
      ],
      responsive: false,	 
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search roles",
      },
      "columnDefs": [
        { "orderable": false, "targets": 2 },
      ],
    });
  });
</script>
@endpush