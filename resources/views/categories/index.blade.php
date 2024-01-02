@extends('layouts.app', ['activePage' => 'category-management', 'menuParent' => 'laravel', 'titlePage' => __('Category Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('Categories') }}</h4>
              </div>
              <div class="card-body">
                @can('create', App\Category::class)
                  @if(auth()->user()->manager_flag != 2)
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="{{ route('category.create') }}" class="btn bt_styl btn_txtbold"><i class="fas fa-clipboard-list pr-2"></i>{{ __('Add category') }}</a>
                      </div>
                    </div>
                  @endif
                @endcan
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover datatable-rose" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Name') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Description') }}
                      </th> 
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('Program') }}
                      </th>                      
                      @can('manage-special-items', App\User::class)
                        <th class="view_word text-right" style="font-weight:bold;">
                          {{ __('Actions') }}
                        </th>
                      @endcan
                    </thead>
                    <tbody class="cht_text">
                      @foreach($categories as $category)
                        <tr>
                          <td>
                            {{ $category->name }}
                          </td>
                          <td>
                            {{ $category->description }}
                          </td>
                          <td>
                            @foreach ($programs as $program)
                              @if($program->id == $category->program_id)
                                {{ $program->description }}                             
                              @endif
                            @endforeach
                          </td>
                          @can('manage-items', App\User::class)
                            <td class="td-actions text-right">
                              <form action="{{ route('category.destroy', $category) }}" method="post">
                                @csrf
                                @method('delete')
                                
                                @can('update', $category)
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('category.edit', $category) }}" data-original-title="" title="">
                                    <div class="icon_siz"><i class="far fa-edit"></i></div>
                                    <div class="ripple-container"></div>
                                  </a>
                                @endcan
                                @if ($category->items->isEmpty() && auth()->user()->can('delete', $category) && auth()->user()->manager_flag != 2)
                                  <button type="button" class="btn btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''">
                                      <i class="material-icons" style="font-size: 2rem;">cancel</i>
                                      <div class="ripple-container"></div>
                                  </button>
                                @endif
                              </form>
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
          [15, 25, 50, -1],
          [15, 25, 50, "All"]
        ],
        responsive: false,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search categories",
        },
        "columnDefs": [
          { "orderable": false, "targets": 2 },
        ],
      });
    });
  </script>
@endpush