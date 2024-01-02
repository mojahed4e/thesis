@extends('layouts.app', ['activePage' => 'template-folder', 'menuParent' => 'laravel', 'titlePage' => __('Manage Document Folder')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('Document Folders') }}</h4>
              </div>
              <div class="card-body">
               @can('create', App\Category::class)
                  @if(auth()->user()->manager_flag != 2)
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="{{ route('templates.create-folder-files',['type=fc']) }}" class="btn bt_styl btn_txtbold"><i class="material-icons" style="font-size:20px;">folder</i>&nbsp;&nbsp;{{ __('Add New Folder') }}</a>&nbsp;&nbsp;
                        <a href="{{ route('templates.create-folder-files',['folder_id=1&type=sfc&req=main']) }}" class="btn bt_styl btn_txtbold"><i class="material-icons" style="font-size:20px;">folder</i>&nbsp;&nbsp;{{ __('Add New Sub Folder') }}</a>&nbsp;&nbsp;
                        <a href="{{ route('templates.create-folder-files',['type=uf&req=main']) }}" class="btn bt_styl btn_txtbold"><i class="material-icons" style="font-size:20px;">folder</i>&nbsp;&nbsp;{{ __('Add New File') }}</a>
                      </div>
                    </div>                    
                  @endif
                @endcan

                <form action="{{ route('templates.delete-folder-files',['type=fd']) }}" method="post" name="frmDeleteFolder" id="frmDeleteFolder">
                <input type="hidden" name="folder_id" id="folder_id" value="">
                @csrf
                @method('post')                                

                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover datatable-rose" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('S.No') }}
                      </th>
                       @if (auth()->user()->role_id != 4)
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Program Name') }}
                      </th>
                      @endif
                      @if (auth()->user()->role_id != 4)
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Cohort Name') }}
                      </th>
                      @endif
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Folder Name') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('View Sub Folders') }}
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
                      @foreach($templatefolders as $folder)
                        <tr>
                          <td>
                            {{ $vSeqNo }}
                          </td>
                          @if (auth()->user()->role_id != 4)
                          <td>
                            {{ $folder->program }}
                          </td>
                          @endif
                          @if (auth()->user()->role_id != 4)
                          <td>
                            {{ $folder->cohort }}
                          </td>
                          @endif
                          <td>
                            {{ $folder->folder_name }}
                          </td>
                          <td>
                            <a rel="tooltip" href="{{ route('templates.view-folders-files', ['folder_id='.$folder->folder_id.'&type=sf']) }}" class="btn bt_styl btn_txtbold" data-original-title="" title="" style="padding: 0.60625 rem 0.90rem;line-height: 0.50;">
                                      View Sub Folder
                                  </a>
                          </td>
                          @can('manage-special-items', App\User::class)
                            <td class="td-actions text-right">                              
                                @can('manage-special-items', App\User::class)
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('templates.create-folder-files', ['folder_id='.$folder->folder_id.'&type=fe']) }}" data-original-title="" title="">
                                    <div class="icon_siz"><i class="far fa-edit"></i></div>
                                    <div class="ripple-container"></div>
                                  </a>
                                @endcan
                                 @if (auth()->user()->role_id < 3 && auth()->user()->manager_flag != 2)
                                  <button type="button" class="btn btn-link" data-original-title="" title="" onclick="funDeleteFolders({{$folder->folder_id}})">
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

    function funDeleteFolders(pmFolderID) {
      swal({
        title: 'Are you sure to delete this folder?',
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
            text: 'Your have deleted the folder successfully.',
            type: 'success',
            confirmButtonColor: '#47a44b'
          }).then ((result) =>{
            document.frmDeleteFolder.method='POST';
            $("#folder_id").val(pmFolderID);
            document.frmDeleteFolder.submit();
          })        
        }
      })
    }
  </script>
@endpush