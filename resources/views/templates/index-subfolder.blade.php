@extends('layouts.app', ['activePage' => 'template-folder', 'menuParent' => 'laravel', 'titlePage' => __('Manage Document Sub Folder')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">               
                <h4 class="card-title view_word">{{ __('Document Sub Folder') }}</h4>
              </div>
              <div class="card-body">
               @can('create', App\Category::class)
                  @if(auth()->user()->manager_flag != 2)
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="{{ route('templates.view-folders-files',['&type=uf']) }}" class="btn bt_styl btn_txtbold"><i class="material-icons" style="font-size:20px;">arrow_back_ios</i>&nbsp;&nbsp;{{ __('Back To Folder Listing') }}</a>
                        <a href="{{ route('templates.create-folder-files',['folder_id='.$folder_id.'&type=sfc']) }}" class="btn bt_styl btn_txtbold"><i class="material-icons" style="font-size:20px;">folder</i>&nbsp;&nbsp;{{ __('Add New Sub Folder') }}</a>
                      </div>
                    </div>
                  @endif
                @endcan

                <form action="{{ route('templates.delete-folder-files',['type=sfd']) }}" method="post" name="frmDeleteFolder" id="frmDeleteFolder">
                <input type="hidden" name="subfolder_id" id="subfolder_id" value="">
                <input type="hidden" name="folder_id" id="folder_id" value="">
                @csrf
                @method('post')
                <div class="table-responsive">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover datatable-rose" style="display:none">
                    <thead class="text-primary">
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('S.No') }}
                      </th>
                      <!--                      
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Folder Name') }}
                      </th>
                      -->
                      <th class="view_word" style="font-weight:bold;">
                          {{ __('Sub Folder Name') }}
                      </th>
                      <th class="view_word" style="font-weight:bold;">
                        {{ __('View Files') }}
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
                      @foreach($subfolders as $folder)
                        <tr>
                          <td>
                            {{ $vSeqNo }}
                          </td>
                          @if (auth()->user()->role_id != 4)
                          <!--
                          <td>
                            {{ $folder->folder_name }}
                          </td>
                          -->
                          @endif
                          <td>
                            {{ $folder->subfolder_name }}
                          </td>
                          <td>
                            <a rel="tooltip" href="{{ route('templates.view-folders-files', ['folder_id='.$folder->folder_id.'&subfolder_id='.$folder->subfolder_id.'&type=vf']) }}" class="btn bt_styl btn_txtbold" data-original-title="" title="" style="padding: 0.60625 rem 0.90rem;line-height: 0.50;">
                                      View Files
                                  </a>
                          </td>
                          @can('manage-special-items', App\User::class)
                            <td class="td-actions text-right">                              
                                @can('manage-special-items', App\User::class)
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('templates.create-folder-files', ['folder_id='.$folder->folder_id.'&subfolder_id='.$folder->subfolder_id.'&type=sfe']) }}" data-original-title="" title="">
                                    <div class="icon_siz"><i class="far fa-edit"></i></div>
                                    <div class="ripple-container"></div>
                                  </a>
                                @endcan
                                 @if (auth()->user()->role_id < 3 && auth()->user()->manager_flag != 2)
                                  <button type="button" class="btn btn-link" data-original-title="" title="" onclick="funDeleteFolders({{$folder->folder_id}},{{$folder->subfolder_id}})">
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

    function funDeleteFolders(pmFolderID,pmSubFolderID) {
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
            $("#subfolder_id").val(pmSubFolderID);
            $("#folder_id").val(pmFolderID);
            document.frmDeleteFolder.submit();
          })        
        }
      })
    }
  </script>
@endpush