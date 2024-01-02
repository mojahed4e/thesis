@extends('layouts.app', ['activePage' => 'thesis-timeline', 'menuParent' => 'laravel', 'titlePage' => __('Manage Thesis Timeline')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('Thesis Timeline') }}</h4>
              </div>
              <div class="card-body">
               @can('create', App\Category::class)
                  @if(auth()->user()->manager_flag != 2)
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="{{ route('timeline.create-thesis-timeline',['type=tc']) }}" class="btn bt_styl btn_txtbold">{{ __('Add New Timeline') }}</a>
                      </div>
                    </div>                    
                  @endif
                @endcan
                <form action="{{ route('timeline.destroy',['type=td']) }}" name="frmDeleteFolder" id="frmDeleteFolder" method="post">
                <input type="hidden" name="timeline_id" id="timeline_id" value="">
                @csrf
                 @method('delete')                 
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover datatable-rose" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('S.No') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Timeline Name') }}
                      </th>                        
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Program Name') }}
                      </th>                      
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Cohort Name') }}
                      </th>                                    
                      @can('manage-special-items', App\User::class)
                        <th class="view_word text-right" style="font-weight:bold;">
                          {{ __('Actions') }}
                        </th>
                      @endcan
                    </thead>
                    <tbody class="cht_text">                      
                      @php
                      $vSeqNo = 1;
                      @endphp
                      @foreach($timelines as $timeline) 
                        @php
                        $aProgressInfo = \App\TermProgressChecklist::where(['timeline_id' => $timeline->timeline_id, 'status' => 1])->get();                        
                        @endphp                                            
                        <tr>
                          <td>
                            {{ $vSeqNo }}
                          </td>                          
                          <td>
                            {{ $timeline->timeline_name }}
                          </td>
                          <td>
                            {{ $timeline->program->name }}
                          </td>
                          <td>
                            {{ $timeline->term->name }}
                          </td>                          
                          @can('manage-special-items', App\User::class)
                            <td class="td-actions text-right">                              
                                @can('manage-special-items', App\User::class)
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('timeline.create-thesis-timeline', ['timeline_id='.$timeline->timeline_id.'&type=te']) }}" data-original-title="" title="">
                                    <div class="icon_siz"><i class="far fa-edit"></i></div>
                                    <div class="ripple-container"></div>
                                  </a>
                                @endcan
                                 @if (count($aProgressInfo) == 0 && auth()->user()->role_id < 3 && auth()->user()->manager_flag != 2)
                                  <button type="button" class="btn btn-link" data-original-title="" title="" onclick="funDeleteFolders({{$timeline->timeline_id}})">
                                      <i class="material-icons" style="font-size: 2rem;">cancel</i>
                                      <div class="ripple-container"></div>
                                  </button>
                                @endif                              
                            </td>
                          @endcan
                        </tr>
                        @php
                          $vSeqNo++;
                        @endphp
                      @endforeach
                    </tbody>
                  </table>
                </div>
                </form>
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
          searchPlaceholder: "Search folders",
        },
        "columnDefs": [
          { "orderable": false, "targets": 1 },
        ],
      });
    });

    function funDeleteFolders(pmTimelineID) {      
      swal({
        title: 'Are you sure to delete this timeline?',
        text: "You won't be able to revert this back!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#47a44b',
        cancelButtonColor: '#ea2c6d',       
        confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {        
          swal({
            title: 'Deleted!',
            text: 'Your have deleted the timeline successfully.',
            type: 'success',
            confirmButtonColor: '#47a44b'
          }).then ((result) =>{
            document.frmDeleteFolder.method='post';
            $("#timeline_id").val(pmTimelineID);
            document.frmDeleteFolder.submit();
          })        
        }
      })
    }
  </script>
@endpush