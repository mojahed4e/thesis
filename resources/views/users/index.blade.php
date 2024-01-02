@extends('layouts.app', ['activePage' => 'user-management', 'menuParent' => 'laravel', 'titlePage' => __('User Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('Users') }}</h4>
              </div>
              <div class="card-body">
                @can('create', App\User::class)
                  @if(auth()->user()->manager_flag != 2)
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="{{ route('user.create') }}" class="btn bt_styl btn_txtbold"><i class="fas fa-user-tie pr-2"></i>{{ __('Add user') }}</a>
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
                        {{ __('Email') }}
                      </th>
					  <th class="view_word" style="font-weight:bold;">
                        {{ __('Cohort') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Role') }}
                      </th>
					  <th class="view_word" style="font-weight:bold;">
                        {{ __('Supervisor Status') }}
                      </th>
                      @can('manage-users', App\User::class)
                        <th class="text-right view_word" style="font-weight:bold;">
                          {{ __('Actions') }}
                        </th>
                      @endcan
                    </thead>
                    <tbody class="cht_text">
					@foreach($users as $user)
						@if($user->id != 1)
                        <tr>
                          <td>
                            {{ $user->name }}
                          </td>
                          <td>
                            {{ $user->email }}
                          </td>
						  <td>
							@if(!empty($user->term))
								{{ $user->term->name }}
							@else
								--
							@endif
                          </td>
                          <td>
                            {{ $user->role->name }}
                          </td>
						  <td>
							@if ($user->role->id == 3 || $user->role->id == 2)
								@if($user->availabe_flage == 1)
									<button type="button" class="btn btn-success btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
										<div class="ripple-container" style="width:90px">Available</div>
									</a>
								@else
									<button type="button" class="btn btn-link text-capitalize" data-original-title="" style="cursor:default" title="" onclick="#">
										<div class="ripple-container" style="width:60px">Busy</div>
									</a>
								@endif
							@else 
								<button type="button" class="btn btn-link" data-original-title="" style="cursor:default" title="" onclick="#">
										<div class="ripple-container" style="width:60px">--</div>
									</a>
							@endif
                          </td>
                          @can('manage-users', App\User::class)
                            @if (auth()->user()->can('update', $user) || auth()->user()->can('delete', $user))
                              <td class="td-actions text-right">
                                @if ($user->id != auth()->id() && $user->id != 1)
                                    <form action="{{ route('user.destroy', $user) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        
                                        @can('update', $user)
                                          <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('user.edit', $user) }}" data-original-title="" title="">
                                            <div class="icon_siz"><i class="far fa-edit"></i></div>
                                            <div class="ripple-container"></div>
                                          </a>
                                        @endcan
                    										@if ($user->id != 2)
                    											@can('delete', $user)
                    											  <button type="button" class="btn btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                    												  <div class="icon_siz"><i class="far fa-times-circle"></i></div>
                    												  <div class="ripple-container"></div>
                    											  </button>
                    											@endcan
                    										@endif
                                    </form>
                                    <form action="{{ route('user.behalf', $user) }}" method="post" accept-charset="UTF-8">
                                      <input type="hidden" name="login_as" id="login_as" value="{{$user->id}}">
                                        @csrf 
                                        @method('POST')                                     
                                        @if ($user->id != 2)
                                          @can('delete', $user)
                                            <button type="button" class="btn btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to login as ") }}{{$user->name}}') ? this.parentElement.submit() : ''">
                                              <div class="icon_siz"><i class="fa fa-sign-in" aria-hidden="true"></i></div>
                                              <div class="ripple-container"></div>
                                            </button>
                                          @endcan
                                        @endif
                                    </form>
                                @else
                                  @can('update', $user)
                                    <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('user.edit', $user) }}" data-original-title="" title="">
                                      <div class="icon_siz"><i class="far fa-edit"></i></div>
                                      <div class="ripple-container"></div>
                                    </a>
                                  @endcan
                                @endif
                              </td>
                            @endif
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
          searchPlaceholder: "Search users",
        },
        "columnDefs": [
          { "orderable": false, "targets": 5},
        ],
      });
    });
  </script>
@endpush